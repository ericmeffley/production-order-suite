<?php
  ob_start();
  session_start();
  include_once("config/db.php");
  include_once('functions.php');
  
  //Check if logged in
  if(!$_SESSION['loggedIn']){
    header("Location: ../index.php");
  }

  // Order Complete Query
  if(isset($_GET['restore'])){
    restoreOrder();
    header("Location:complete-orders.php");
  }

  if(isset($_GET['pack'])){
    addToPackingList();
    header("Location:complete-orders.php");
  }

  if(isset($_GET['archive'])){
    archiveOrder();
    header("Location:complete-orders.php");
  }
  
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
    <link rel="stylesheet" href="css/main.css?v=1.16">

    <title>IGT Order Suite</title>
  </head>
  <body>
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
    <ul class="nav nav-pills em-nav">
      <li class="nav-item">
        <a class="nav-link em-nav-link" href="index.php">Active Orders</a>
      </li>
      <li class="nav-item">
        <a class="nav-link em-nav-link active">Completed Orders</a>
      </li>
      <li class="nav-item">
        <a class="nav-link em-nav-link" href="order-archive.php">Order Archive</a>
      </li>
      <!-- Dropdown List -->
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle em-nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Packing Lists</a>
        <div class="dropdown-menu">
          <a class="dropdown-item" href="packing-slip/indoff.php" target="_blank">Indoff</a>
          <a class="dropdown-item" href="packing-slip/mv.php" target="_blank">Magna Visual</a>
          <a class="dropdown-item" href="packing-slip/general-ps.php" target="_blank">General</a>
        </div>
      </li>
    </ul>
    <section class="container-fluid">
      <table class='table border-top-0 table-hover'>
        <thead class="thead-dark">
          <tr>
            <th class="border-top-0">Line</th>
            <th class="border-top-0">Item</th>
            <th class="border-top-0">Reference</th>
            <th class="border-top-0">Quantity</th>
            <th class="border-top-0">Order</th>
            <th class="border-top-0">Purchase Order</th>
            <th class="border-top-0">Customer</th>
            <th class="border-top-0">Ordered</th>
            <th class="border-top-0">Due Date</th>
            <th class="border-top-0">Description</th>
            <th class="border-top-0 icon-col-header" >Pack</th>
            <th class="border-top-0 icon-col-header">Restore</th>
            <th class="border-top-0 icon-col-header">Archive</th>
          </tr>
          </thead>
          <tbody>
            <?php
              //Query orders
              $result = $conn->query("SELECT * FROM completed_orders");

              if(empty($result)){ 

                  echo "<tr>";
                    echo "<td>No records found.</td>";
                  echo "</tr>";

              }else{ 
                //Insert each row into the table
                foreach($result as $row){
                  //Store the order and line number for text button
                  $ordNumber = $row[5];
                  $lineNumber = $row[1];
                  $customer = $row[7];
                  //Display rows
                    echo "<tr>";
                      echo "<td>".$row[1]."</td>";
                      echo "<td>".$row[2]."</td>";
                      echo "<td>".$row[3]."</td>";
                      echo "<td>".$row[4]."</td>";
                      echo "<td>".$row[5]."</td>";
                      echo "<td>".$row[6]."</td>";
                      echo "<td>".$row[7]."</td>";
                      echo "<td>".$row[8]."</td>";
                      echo "<td>".$row[9]."</td>";
                      echo "<td>".$row[10]."</td>";                  
                      
                      echo "<td class='icon icon-col'>
                              <a href='complete-orders.php?pack=1&ordNo={$ordNumber}&lineNo={$lineNumber}&custNo={$customer}' title='Pack Order'>
                                <img src='img/pack-icon.svg' width='35' >
                              </a>
                            </td>";
                      echo "<td class='icon icon-col'>
                              <a href='complete-orders.php?restore=1&ordNo={$ordNumber}&lineNo={$lineNumber}' title='Restore Order'>
                                <img src='img/restore-icon.svg' width='35'>
                              </a>
                            </td>";

                      echo "<td class='icon icon-col'>
                              <a href='complete-orders.php?archive=1&ordNo={$ordNumber}&lineNo={$lineNumber}&custNo={$customer}' title='Archive Order'>
                                <img src='img/archive-icon.svg' width='35'>
                              </a>
                            </td>";
                    echo "</tr>";
                }
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
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
  </body>
</html>