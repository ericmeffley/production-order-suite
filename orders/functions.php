<?php
include_once("config/db.php");

    if(isset($_GET['clear'])){
        global $conn;
        $clearSlip = $_GET['clear'];

        if($clearSlip == "indoff"){
            if($stmt = $conn->query('TRUNCATE TABLE indoff_packing_slip')){
                echo '<script>if(confirm("Indoff slip has been cleared")) document.location = "index.php"</script>';
            }
        }

        if($clearSlip == "mv"){
            if($stmt = $conn->query('TRUNCATE TABLE mv_packing_slip')){
                echo '<script>if(confirm("Magna Visual slip has been cleared")) document.location = "index.php"</script>';
            }
        }
        
    }

    function getlastOrderImported(){
        global $conn;
        $id = 1;
        $orderQuery = $conn->prepare("SELECT last_order_number FROM order_number WHERE id=(:id)");
        $orderQuery->bindParam(':id', $id);
        $orderQuery->execute();
        $result = $orderQuery->fetch();
        $lastOrderImported = $result[0];
        return $lastOrderImported;
    }

    function orderComplete(){
        global $conn;
        $order_number = $_GET['ordNo'];
        $line_number = $_GET['lineNo'];
        $row = array();

        //Copy order to completed orders table
        $move_stmt = $conn->prepare("SELECT * FROM active_orders WHERE order_number=(:orderNum) && line_number=(:lineNum)");
        $move_stmt->bindParam(':orderNum', $order_number);
        $move_stmt->bindParam(':lineNum', $line_number);
        $move_stmt->execute();
        $result = $move_stmt->fetch();
        //print_r($result);

        //Insert done order into completed orders table
        $insert_stmt = $conn->query("INSERT INTO `completed_orders` (`line_number`, `item`, `reference`, `quantity`, `order_number`, `purchase_order`, `customer_number`, `created_at`, `due_date`, `description`) VALUES('$result[1]','$result[2]','$result[3]','$result[4]','$result[5]','$result[6]','$result[7]','$result[8]','$result[9]','$result[10]')");


        //Delete active orders from table
        $delete_stmt = $conn->prepare("DELETE FROM active_orders WHERE order_number=(:orderNum) and line_number=(:lineNum)");
        $delete_stmt->bindParam(':orderNum', $order_number);
        $delete_stmt->bindParam(':lineNum', $line_number);
        $delete_stmt->execute();  
    }

    function restoreOrder(){
        global $conn;
        $order_number = $_GET['ordNo'];
        $line_number = $_GET['lineNo'];
        $row = array();
        
        //Copy order to completed orders table
        $move_stmt = $conn->prepare("SELECT * FROM completed_orders WHERE order_number=(:orderNum) && line_number=(:lineNum)");
        $move_stmt->bindParam(':orderNum', $order_number);
        $move_stmt->bindParam(':lineNum', $line_number);
        $move_stmt->execute();
        $result = $move_stmt->fetch();

        //Insert done order into completed orders table
        $insert_stmt = $conn->query("INSERT INTO `active_orders` (`line_number`, `item`, `reference`, `quantity`, `order_number`, `purchase_order`, `customer_number`, `created_at`, `due_date`, `description`) VALUES('$result[1]','$result[2]','$result[3]','$result[4]','$result[5]','$result[6]','$result[7]','$result[8]','$result[9]','$result[10]')");


        //Delete active orders from table
        $delete_stmt = $conn->prepare("DELETE FROM completed_orders WHERE order_number=(:orderNum) and line_number=(:lineNum)");
        $delete_stmt->bindParam(':orderNum', $order_number);
        $delete_stmt->bindParam(':lineNum', $line_number);
        $delete_stmt->execute();  
    }

    function archiveOrder(){
        global $conn;
        $order_number = $_GET['ordNo'];
        $line_number = $_GET['lineNo'];
        $customer_number = $_GET['custNo'];
        $row = array();

        $move_stmt = $conn->prepare("SELECT * FROM completed_orders WHERE order_number=(:orderNum) && line_number=(:lineNum)");
              $move_stmt->bindParam(':orderNum', $order_number);
              $move_stmt->bindParam(':lineNum', $line_number);
              $move_stmt->execute();
              $result = $move_stmt->fetch();
              
              //Archive order
              $insert_stmt = $conn->query("INSERT INTO `archived_orders` (`line_number`, `item`, `reference`, `quantity`, `order_number`, `purchase_order`, `customer_number`, `created_at`, `due_date`, `description`) VALUES('$result[1]','$result[2]','$result[3]','$result[4]','$result[5]','$result[6]','$result[7]','$result[8]','$result[9]','$result[10]')");
              
              //Delete order from completed list
              $delete_stmt = $conn->prepare("DELETE FROM completed_orders WHERE order_number=(:orderNum) and line_number=(:lineNum)");
              $delete_stmt->bindParam(':orderNum', $order_number);
              $delete_stmt->bindParam(':lineNum', $line_number);
              $delete_stmt->execute();
    }

    function addToPackingList(){
        global $conn;
        $order_number = $_GET['ordNo'];
        $line_number = $_GET['lineNo'];
        $customer_number = $_GET['custNo'];
        $row = array();

        if($customer_number == "900"){
            //Copy order to completed orders list
            $move_stmt = $conn->prepare("SELECT * FROM completed_orders WHERE order_number=(:orderNum) && line_number=(:lineNum)");
            $move_stmt->bindParam(':orderNum', $order_number);
            $move_stmt->bindParam(':lineNum', $line_number);
            $move_stmt->execute();
            $result = $move_stmt->fetch();
            
            //Insert order into packing slip table
            $insert_stmt = $conn->query("INSERT INTO `indoff_packing_slip` (`line_number`, `item`, `reference`, `quantity`, `order_number`, `purchase_order`, `customer_number`, `created_at`, `due_date`, `description`) VALUES('$result[1]','$result[2]','$result[3]','$result[4]','$result[5]','$result[6]','$result[7]','$result[8]','$result[9]','$result[10]')");
            
            
            //Archive order
            $insert_stmt = $conn->query("INSERT INTO `archived_orders` (`line_number`, `item`, `reference`, `quantity`, `order_number`, `purchase_order`, `customer_number`, `created_at`, `due_date`, `description`) VALUES('$result[1]','$result[2]','$result[3]','$result[4]','$result[5]','$result[6]','$result[7]','$result[8]','$result[9]','$result[10]')");
            
            //Delete order from completed list
            $delete_stmt = $conn->prepare("DELETE FROM completed_orders WHERE order_number=(:orderNum) and line_number=(:lineNum)");
            $delete_stmt->bindParam(':orderNum', $order_number);
            $delete_stmt->bindParam(':lineNum', $line_number);
            $delete_stmt->execute();

        } elseif($customer_number == "1300"){
              //Copy order to completed orders list
              $move_stmt = $conn->prepare("SELECT * FROM completed_orders WHERE order_number=(:orderNum) && line_number=(:lineNum)");
              $move_stmt->bindParam(':orderNum', $order_number);
              $move_stmt->bindParam(':lineNum', $line_number);
              $move_stmt->execute();
              $result = $move_stmt->fetch();
              
              //Insert order into packing slip table
              $insert_stmt = $conn->query("INSERT INTO `mv_packing_slip` (`line_number`, `item`, `reference`, `quantity`, `order_number`, `purchase_order`, `customer_number`, `created_at`, `due_date`, `description`) VALUES('$result[1]','$result[2]','$result[3]','$result[4]','$result[5]','$result[6]','$result[7]','$result[8]','$result[9]','$result[10]')");
              
              
              //Archive order
              $insert_stmt = $conn->query("INSERT INTO `archived_orders` (`line_number`, `item`, `reference`, `quantity`, `order_number`, `purchase_order`, `customer_number`, `created_at`, `due_date`, `description`) VALUES('$result[1]','$result[2]','$result[3]','$result[4]','$result[5]','$result[6]','$result[7]','$result[8]','$result[9]','$result[10]')");
              
              //Delete order from completed list
              $delete_stmt = $conn->prepare("DELETE FROM completed_orders WHERE order_number=(:orderNum) and line_number=(:lineNum)");
              $delete_stmt->bindParam(':orderNum', $order_number);
              $delete_stmt->bindParam(':lineNum', $line_number);
              $delete_stmt->execute();

        } else {
            $move_stmt = $conn->prepare("SELECT * FROM completed_orders WHERE order_number=(:orderNum) && line_number=(:lineNum)");
              $move_stmt->bindParam(':orderNum', $order_number);
              $move_stmt->bindParam(':lineNum', $line_number);
              $move_stmt->execute();
              $result = $move_stmt->fetch();
              
              //Insert order into packing slip table
              $insert_stmt = $conn->query("INSERT INTO `general_packing_slip` (`line_number`, `item`, `reference`, `quantity`, `order_number`, `purchase_order`, `customer_number`, `created_at`, `due_date`, `description`) VALUES('$result[1]','$result[2]','$result[3]','$result[4]','$result[5]','$result[6]','$result[7]','$result[8]','$result[9]','$result[10]')");
              
              
              //Archive order
              $insert_stmt = $conn->query("INSERT INTO `archived_orders` (`line_number`, `item`, `reference`, `quantity`, `order_number`, `purchase_order`, `customer_number`, `created_at`, `due_date`, `description`) VALUES('$result[1]','$result[2]','$result[3]','$result[4]','$result[5]','$result[6]','$result[7]','$result[8]','$result[9]','$result[10]')");
              
              //Delete order from completed list
              $delete_stmt = $conn->prepare("DELETE FROM completed_orders WHERE order_number=(:orderNum) and line_number=(:lineNum)");
              $delete_stmt->bindParam(':orderNum', $order_number);
              $delete_stmt->bindParam(':lineNum', $line_number);
              $delete_stmt->execute();
        }
        
          
    }
    
?>