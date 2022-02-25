<?php
  ob_start();
  session_start();
  include_once("../config/db.php");
  include_once('functions.php');
  $error = "";
  $editRow = [];

  //Check if logged in
  if(!$_SESSION['loggedIn']){
    header("Location: ../index.php");
  }

  // Order complete function
  if(isset($_GET['done'])){
    orderComplete();
    header("Location:active-orders.php");
  }

  if(isset($_GET['sort'], $_GET['pagerequest'])){
    $sort = sortby($_GET['sort'], $_GET['pagerequest']);
  } else {
    $sort = $conn->query("SELECT * FROM active_orders ORDER BY due_date ASC");
  }

  if(isset($_GET['edit'])){
    
    //Store $_GET variable
    $issue_id = $_GET['edit'];

    //echo $issue_id;
      $edit_query = $conn->prepare("SELECT * FROM active_orders WHERE id = ?");
      $edit_query->execute(array($issue_id));
      $row = $edit_query->fetch();
      $editRow = [$row[2], $row[3],$row[4],$row[5],$row[6],$row[7],$row[9],$row[10],];

  }

  if(isset($_POST['update'])){

    // print_r($_POST['update']);
    header("Location: active-orders.php");
  }

?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Web Fonts--> 
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300|Raleway:500" rel="stylesheet"> 
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous"> 
    <!-- Local CSS -->
    <link rel="stylesheet" href="../css/main.css?v=<?php echo time(); ?>">

    <title>IGT Production Suite</title>
  </head>
  <body>
    <!-- Top toolbar to dock buttons -->
    <section class="top-toolbar">
      <a class="btn btn-secondary float-right" href="../logout.php">Log Out</a>
    </section>
    <div id="error"><?php echo $error; ?></div> 
    <header class="header">
        <img class="header-logo" src="img/igt-america-logo-horiz.svg" />
    </header>
    <!-- Navigation bar -->
    <nav class="nav">
      <div class="nav-item">
        <a class="nav-link active" href="index.php">Active Orders</a>
      </div>
      <div class="nav-item">
        <a class="nav-link" href="complete-orders.php">Complete Orders</a>
      </div>
      <div class="nav-item">
        <a class="nav-link" href="order-archive.php">Order Archive</a>
      </div>
    </nav>
    
      <!-- Edit Container -->
      <?php if(isset($_GET["edit"])){
        echo '
        <section class="container-wide edit-container">
        <form method="post">
            <div id="edit-block">
              <div class="grid-container">
                <p class="grid-header">Item</p>
                <p class="grid-header">Reference</p>
                <p class="grid-header">Quantity</p>
                <p class="grid-header">Order #</p>
                <p class="grid-header">PO #</p>
                <p class="grid-header">Customer #</p>
                <p class="grid-header">Due Date</p>
                <p class="grid-header">Description</p>
                <input type="text" class="grid-item" value="' ?><?php if(isset($editRow[0])){echo $editRow[0];} ?> <?php echo '"> 
                <input type="text" class="grid-item" value="' ?><?php if(isset($editRow[1])){echo $editRow[1];} ?> <?php echo '">
                <input type="text" class="grid-item" value="' ?><?php if(isset($editRow[0])){echo $editRow[2];} ?> <?php echo '">
                <input type="text" class="grid-item" value="' ?><?php if(isset($editRow[0])){echo $editRow[3];} ?> <?php echo '">
                <input type="text" class="grid-item" value="' ?><?php if(isset($editRow[0])){echo $editRow[4];} ?> <?php echo '">
                <input type="text" class="grid-item" value="' ?><?php if(isset($editRow[0])){echo $editRow[5];} ?> <?php echo '">
                <input type="text" class="grid-item" value="'?><?php if(isset($editRow[0])){echo $editRow[6];} ?><?php echo '">
                <input type="text" class="grid-item" value="'?><?php if(isset($editRow[0])){echo $editRow[7];} ?> <?php echo '">
              </div>
              <button class="btn btn-update" name="update" >Update</button>
            </div>
          </form>
        </section>';} ?>
        
      
      <table class='table'>
        <thead>
          <tr>
            <th>Item</th>
            <th>Reference</th>
            <th>Quantity</th>
            <th>Order</th>
            <th>Purchase Order</th>
            <th><a class="btn-link" href="active-orders.php?sort=customer&pagerequest=active">Customer</a></th>
            <!-- <th>Ordered</th> -->
            <th><a class="btn-link" href="active-orders.php?sort=duedate&pagerequest=active">Due Date</a></th>
            <th>Description</th>
            <th>Complete</th>
            <th>Edit</th>
          </tr>
        </thead>
        <tbody class="table-striped">
          <?php
            //Query orders
            $result = $sort;
            
            //Insert each row into the table
            foreach($result as $row){
              //Store the order number for 'done' button
              $ordNumber = $row[5];
              $lineNumber = $row[1];

              $rowId = $row[0];
              //Display rows
                echo "<tr>";
                echo "<td>".$row[2]."</td>";
                echo "<td>".$row[3]."</td>";
                echo "<td>".$row[4]."</td>";
                echo "<td>".$row[5]."</td>";
                echo "<td>".$row[6]."</td>";
                echo "<td>".$row[7]."</td>";
                //echo "<td>".$row[8]."</td>";
                echo "<td>".$row[9]."</td>";
                echo "<td style='overflow-x:hidden;'>".$row[10]."</td>";                  
                echo "<td class='icon icon-col'>
                          <a href='active-orders.php?done=1&ordNo={$ordNumber}&lineNo={$lineNumber}' title='Mark Order Complete'>
                          <i class='fas fa-clipboard-check fa-lg'></i>
                          </a>
                      </td>";
                      echo "<td class='icon icon-col'>
                      <a href='http://localhost/ordersuite/orders/active-orders.php?edit=$rowId'><i class='fas fa-edit fa-lg'></i></a></td>";
                echo "</tr>";

            }

          ?>
        </tbody>
      </table>
    </section>        

<footer class="footer">
  <div class="container">
    <p class="text-center">&copy;2018 IGT America Inc.</p>
  </div>
</footer>

    <!-- Show Edit Container -->
    <script>
      function showEdit() {
        //document.getElementById("edit-container").style.display = "block";
        document.getElementById("edit-container").style.display = "block";

      }
    </script>
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
  </body>
</html>