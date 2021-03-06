<?php
  session_start();
  date_default_timezone_set('America/New_York');
  include_once("../../config/db.php");
  include_once('../functions.php');
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"> -->

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,500" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Muli:300,400,600" rel="stylesheet"> 

    <!-- Local CSS -->
    <link rel="stylesheet" href="../../css/packing-slip.css?v=1.15">

    <title>Indoff Packing Slip</title>
  </head>
    <body>
    <!-- Page Wrapper -->
    <section id="wrapper">
      <!-- Page Header -->
      <header>
        <div>
          <img src="../img/igt-america-logo-horiz.svg" />
        </div>
      </header>
      <div class="row row-bordered round-corners">
            <div class="col-2 address-1">
                <p><small class="red-text">Shipped From</small></p>
                <p><span class="address-head">IGT America, Inc.</span></p>
                <p>1515 S. Dixie Hwy.</p>
                <p>Lima, Ohio, 45804</p>
            </div>
            <div class="col-2 address-2">
              <p><small class="red-text">Shipping To</small></p>
                <p><span class="address-head">Rose Laminating</span></p>
                <p>4269 Armstrong Blvd</p>
                <p>Batavia, Ohio, 45103</p>
            </div>
        </div>
        <div class="row">
          <div class="col-3">
            <span class="red-text text-medium"><b>Ship Date: </b></span><?php echo date("M-d-Y"); ?>
          </div>
          <div class="col-3">
            <span class="red-text text-medium"><b>Customer Contact: </b></span>Jenn
          </div>
          <div class="col-3">
            <span class="red-text text-medium"><b>Customer Account #: </b></span>900
          </div>
        </div>
    
      <table class='table'>
        <thead>
          <tr>
            <th class="border-top-0">PURCHASE ORDER</th>
            <th class="border-top-0">ITEM</th>
            <th class="border-top-0">REFERENCE</th>
            <th class="border-top-0">UNIT TYPE</th>            
            <th class="border-top-0">QUANITY</th>
          </tr>
          </thead>
          <tbody>
            <?php
              //Total variable
              $listPartQuanity = 0;
              $crateSize = 0;
              $crateDesc = "";
              //Query orders
              $result = $conn->query("SELECT * FROM indoff_packing_slip ORDER BY order_number ASC");
              //Insert each row into the table
              foreach($result as $row){
                //Store the order number for 'done' button
                $ordNumber = $row[5];
                $lineNumber = $row[1];
                $listPartQuanity = $listPartQuanity + $row[4];
                
                //Display rows
                  echo "<tr>";
                  echo "<td>".$row[6]."</td>";
                  echo "<td>".$row[2]."</td>";
                  echo "<td>".$row[3]."</td>";
                  echo "<td>ea</td>";
                  echo "<td>".$row[4]."</td>";
                  echo "</tr>";

              }

            ?>
          </tbody>
      </table>
      <section id="totals-container">
        <div class="totals-box-one">&nbsp;</div>
        <div class="totals-box-two">Total: </div>
        <div class="totals-box-three"><?php echo $listPartQuanity; ?></div>
      </section>

      <section id="crate-detail">
        <div class="select-group">
          <div class="grey-text pad-top-5">Weight:</div>
          <input type="text">
        </div>
        <div class="select-group">
          <div class="grey-text pad-top-5">Crate Size: </div>
            <select id="selectedCrateSize" class="select-box">
                  <option>Select</option>
                  <option>42"x54"x12"</option>
                  <option>78"x54"x12"</option>
                  <option>102"x54"x12"</option>
            </select>
          </div>
      </section>
      <div id="footer">
        <p>Please contact us at 567-940-9100 with any questions or concerns.<br>
      <b>Thank you for your business.</b></p> 
      </div>
    </section> 
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script>
          var el = document.getElementById("selectedCrateSize");
          var cratesize = el.selectedIndex;
          console.log(cratesize);
        </script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
  </body>
</html>