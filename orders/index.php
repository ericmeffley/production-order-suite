<?php
    ob_start();
    session_start();
    date_default_timezone_set("America/New_York");    
    include_once("../config/db.php");
    include_once("functions.php");
    ini_set('auto_detect_line_endings', true);

    $orderNumber = 0;
    $lastOrderImported = getlastOrderImported() + 1;

    //Check if logged in
    if(!$_SESSION['loggedIn']){
        header("Location: ../index.php");
    }
    
    //Open text file (readonly)
    $textFile = fopen("files/orders.txt", "r") or die("Unable to open file!");
    
    if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
        
    }else{
        
        while(!feof($textFile)) {

            //Load each line into variable
            $line = fgets($textFile);

            //Separate each line into an array
            $lineArray = (explode(",",$line));

            //Strip off leading 3 characters
            $orderNumber = 0;
            $str = $lineArray[4];
            
            //Extract characters off the front of the order number
            $arr1 = str_split($str);
            $orderNumber = $arr1[3].$arr1[4].$arr1[5].$arr1[6].$arr1[7];

            if($orderNumber >= $lastOrderImported){

                $lastOrderImported = $orderNumber;
                $stmt = $conn->query("UPDATE order_number SET last_order_number = $orderNumber WHERE id = 1");

                //Convert date order was entered
                $orderCreatedOn = $lineArray[7];
                $orderCreatedOn = strtotime($orderCreatedOn);
                $orderCreatedOn = date('Y-m-d',$orderCreatedOn);
                
                //Convert Due Date
                $orderDueOn = $lineArray[8];
                $orderDueOn = strtotime($orderDueOn);
                $orderDueOn = date('Y-m-d', $orderDueOn);

                //Store order number
                $orderNumber = $lineArray[4];

                //Set query variable
                $stmt = $conn->query("INSERT INTO `active_orders` (`line_number`, `item`, `reference`, `quantity`, `order_number`, `purchase_order`, `customer_number`, `created_at`, `due_date`, `description`) VALUES('$lineArray[0]','$lineArray[1]','$lineArray[2]','$lineArray[3]','$lineArray[4]','$lineArray[5]','$lineArray[6]','$orderCreatedOn','$orderDueOn','$lineArray[9]')");
                
            }
        }
        
    }
    
    //Close the file and database connection
    fclose($textFile);

    header("Location: active-orders.php");
    //echo '<a href="active-orders.php">Go To Active Orders =></a>';
    exit();

?>