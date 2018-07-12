<?php
  ob_start();
  session_start();
  include_once("config/db.php");
  include_once('functions.php');

  if(!$_SESSION['loggedIn']){
    header("Location: ../index.php");
  }

  // Order complete function
  if(isset($_GET['done'])){
    orderComplete();
    header("Location:active-orders.php");
  }
  if(isset($_GET['clear']))
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,500" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Muli:300,400,600" rel="stylesheet"> 

    <!-- Local CSS -->
    <link rel="stylesheet" href="css/main.css?v=1.20">

    <title>IGT Order Application</title>
  </head>
    <body>
    <!-- Top toolbar to dock buttons -->
    <div class="container-fluid top-toolbar">
      <div>
          <label for="clear-buttons" class="pad-1">Clear Packing List</label>
          <a class="btn btn-primary" href="functions.php?clear=indoff">Indoff</a>
          <a class="btn btn-primary" href="functions.php?clear=mv">MV</a>
          <a class="btn btn-primary" href="functions.php?clear=general">General</a>
          <a class="btn btn-danger float-right" href="../logout.php">Log Out</a>
        </div>
        
    </div>
      
      <header>
        <div class="center">
          <img src="img/igt-america-logo-horiz.svg" />
        </div>
      </header>
      <ul class="nav nav-tabs">
        <li class="nav-item">
          <a class="nav-link active" href="index.php">Active Orders</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="complete-orders.php">Completed Orders</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="packing-slip/indoff.php" target="_blank">Indoff Packing Slip</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="packing-slip/mv.php" target="_blank">MV Packing Slip</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="order-archive.php" target="_blank">Order Archive</a>
        </li>
        <li class="nav-item">
          
        </li>
      </ul>
    <section class="container-fluid">
      <table class='table'>
        <thead  class="thead-dark">
          <tr>
            <!-- <th>Line</th> -->
            <th class="border-top-0">Item</th>
            <th class="border-top-0">Reference</th>
            <th class="border-top-0">Quantity</th>
            <th class="border-top-0">Order</th>
            <th class="border-top-0">Purchase Order</th>
            <th class="border-top-0">Customer</th>
            <th class="border-top-0">Ordered</th>
            <th class="border-top-0">Due Date</th>
            <th class="border-top-0">Description</th>
            <th class="border-top-0"></th>
          </tr>
          </thead>
          <tbody>
            <?php
              //Query orders
              $result = $conn->query("SELECT * FROM active_orders ORDER BY due_date ASC");
              //Insert each row into the table
              foreach($result as $row){
                //Store the order number for 'done' button
                $ordNumber = $row[5];
                $lineNumber = $row[1];
                //Display rows

                  echo "<tr>";
                  //echo "<td>".$row[1]."</td>";
                  echo "<td>".$row[2]."</td>";
                  echo "<td>".$row[3]."</td>";
                  echo "<td>".$row[4]."</td>";
                  echo "<td>".$row[5]."</td>";
                  echo "<td>".$row[6]."</td>";
                  echo "<td>".$row[7]."</td>";
                  echo "<td>".$row[8]."</td>";
                  echo "<td>".$row[9]."</td>";
                  echo "<td>".$row[10]."</td>";                  
                  echo "<td><a href='active-orders.php?done=1&ordNo={$ordNumber}&lineNo={$lineNumber}' class='igt-btn btn-text'>Done</a></td>";
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

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script>
        document.getElementById("order-load-button").onclick = function(){
            location.href = "load_orders.php";
        };
    </script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
  </body>
</html>