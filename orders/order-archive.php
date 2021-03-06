<?php
  session_start();
  include_once("../config/db.php");
  include_once('functions.php');
                 
  //Order Complete Query
    if(isset($_GET['restore'])){
      restoreOrder();
      //header("Location:order-archive.php");
    }

    // if(isset($_GET['pack'])){
    //   addToPackingList();
    //   header("Location:complete-orders.php");
    // }
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
    <div class="container-wide top-toolbar">
      <div>
          <a class="btn btn-secondary float-right" href="../logout.php">Log Out</a>
      </div>
    </div>
    <header class="header">
      <div class="center">
        <img class="header-logo" src="img/igt-america-logo-horiz.svg" />
      </div>
    </header>
    <!-- Navigation bar -->
    <nav class="nav">
      <div class="nav-item">
        <a class="nav-link" href="index.php">Active Orders</a>
      </div>
      <div class="nav-item">
        <a class="nav-link" href="complete-orders.php">Complete Orders</a>
      </div>
      <div class="nav-item">
        <a class="nav-link active">Order Archive</a>
      </div>
    </nav>
    <section class="container-fluid">
      <table class='table border-top-0 table-hover'>
        <thead class="thead-dark">
          <tr>
            <th class="border-top-0">Item</th>
            <th class="border-top-0">Reference</th>
            <th class="border-top-0">Quantity</th>
            <th class="border-top-0">Order</th>
            <th class="border-top-0">Purchase Order</th>
            <th class="border-top-0">Customer</th>
            <th class="border-top-0">Ordered</th>
            <th class="border-top-0">Due Date</th>
            <th class="border-top-0">Description</th>
            <th class="border-top-0 icon-col-header">Restore</th>
          </tr>
          </thead>
          <tbody>
            <?php
              //Query orders
              $result = $conn->query("SELECT * FROM archived_orders");

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
                  //Display rows
                    echo "<tr>";
                      echo "<td>".$row[2]."</td>";
                      echo "<td>".$row[3]."</td>";
                      echo "<td>".$row[4]."</td>";
                      echo "<td>".$row[5]."</td>";
                      echo "<td>".$row[6]."</td>";
                      echo "<td>".$row[7]."</td>";
                      echo "<td>".$row[8]."</td>";
                      echo "<td>".$row[9]."</td>";
                      echo "<td>".$row[10]."</td>";
                      echo "<td class='icon icon-col'><a href='order-archive.php?restore=2&ordNo={$ordNumber}&lineNo={$lineNumber}'>
                                  <i class='far fa-window-restore'></i>
                                  <!-- <img class='icon' src='img/restore-icon-3.svg'> -->
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