<?php
  ob_start();
  session_start();
  include_once("config/db.php");
  include_once('functions.php');
  $error = "";

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

  if(isset($_GET['sort'], $_GET['pagerequest'])){
    $sort = sortby($_GET['sort'], $_GET['pagerequest']);
  } else {
    $sort = $conn->query("SELECT * FROM completed_orders ORDER BY due_date ASC");
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
    <link rel="stylesheet" href="../css/main.css?v=<?php echo time() ?>">

    <title>IGT Production Suite</title>
  </head>
  <body>
    <section class="top-toolbar">
      <div>
          <label for="clear-buttons">Clear Packing List >></label>
          <a class="btn btn-primary inline-btn btn-round" onclick="clearPackingSlip('indoff')">Indoff</a>
          <a class="btn btn-primary inline-btn" onclick="clearPackingSlip('mv')">MV</a>
          <a class="btn btn-primary inline-btn" onclick="clearPackingSlip('misc')">Misc.</a>
          <a class="btn btn-secondary float-right" href="../logout.php">Log Out</a>
        </div>
    </section>
    <div id="error"><?php echo $error; ?></div>
    <header class="header">
      <img class="header-logo" src="img/igt-america-logo-horiz.svg" />
    </header>

    <!-- Navbar section -->
    <nav class="nav">
      <div class="nav-item">
        <a class="nav-link" href="index.php">Active Orders</a>
      </div>
      <div class="nav-item">
        <a class="nav-link active">Complete Orders</a>
      </div>
      <div class="nav-item">
        <div class="dropdown">
          <a class="nav-link"><i class="fas fa-sort-down fa-lg"></i>&nbsp;Packing Slips</a>
          <div class="dropdown-content">
            <a href="packing-slip/indoff.php" target="_blank">Indoff</a>
            <a href="packing-slip/mv.php" target="_blank">Magna Visual</a>
            <a href="packing-slip/other.php" target="_blank">Other</a>
          </div>
        </div> 
      </div>
      <div class="nav-item">
        <a class="nav-link" href="order-archive.php">Order Archive</a>
      </div>
      
    </nav>
    <!-- Table data display -->
    <section class="container-wide">
      <table class='table border-top-0 table-hover'>
        <thead class="thead-dark">
          <tr>
            <th class="border-top-0">Item</th>
            <th class="border-top-0">Reference</th>
            <th class="border-top-0">Quantity</th>
            <th class="border-top-0">Order</th>
            <th class="border-top-0">Purchase Order</th>
            <th class="border-top-0"><a class="btn-link" href="complete-orders.php?sort=customer&pagerequest=complete">Customer</a></th>
            <!-- <th class="border-top-0">Ordered</th> -->
            <th class="border-top-0"><a class="btn-link" href="complete-orders.php?sort=duedate&pagerequest=complete">Due Date</th>
            <th class="border-top-0">Description</th>
            <th class="border-top-0 icon-col-header" >Pack</th>
            <th class="border-top-0 icon-col-header">Restore</th>
            <th class="border-top-0 icon-col-header">Archive</th>
          </tr>
          </thead>
          <tbody>
            <?php
              //Query orders
              $result = $sort;

                //Insert each row into the table
                foreach($result as $row){
                  //Store the order and line number for text button
                  $ordNumber = $row[5];
                  $lineNumber = $row[1];
                  $customer = $row[7];
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
                      echo "<td>".$row[10]."</td>";                  
                      
                      echo "<td class='icon icon-col'>
                              <a href='complete-orders.php?pack=1&ordNo={$ordNumber}&lineNo={$lineNumber}&custNo={$customer}' title='Pack Order'>
                              <i class='fas fa-truck fa-lg'></i>
                              </a>
                            </td>";
                      echo "<td class='icon icon-col'>
                              <a href='complete-orders.php?restore=1&ordNo={$ordNumber}&lineNo={$lineNumber}' title='Restore Order'>
                              <i class='far fa-window-restore fa-lg'></i>
                              </a>
                            </td>";

                      echo "<td class='icon icon-col'>
                              <a href='complete-orders.php?archive=1&ordNo={$ordNumber}&lineNo={$lineNumber}&custNo={$customer}' title='Archive Order'>
                              <i class='fas fa-archive fa-lg'></i>
                              </a>
                            </td>";
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
    <script>
      function clearPackingSlip(n) {
        var slipDetail = n;
        if (slipDetail == "indoff"){
          var response = confirm("Clear INDOFF Slip??")
          if (response == true){
            document.location = "functions.php?clear=indoff";
          } else {
            document.location = "complete-orders.php";
          }
        } else if (slipDetail == "mv") {
          var response = confirm("Clear Magna Visual Slip??")
          if (response == true){
            document.location = "functions.php?clear=mv";
          } else {
            document.location = "complete-orders.php";
          }
        } else {
          var response = confirm("Clear Miscellaneous Slip??")
          if (response == true){
            document.location = "functions.php?clear=misc";
          } else {
            document.location = "complete-orders.php";
          }
        }
      }
    </script>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
  </body>
</html>