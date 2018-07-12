<?php ob_start(); ?>
<?php include 'config/db.php';
session_start();
date_default_timezone_set('UTC');

//SESSSION VARIABLES
$displayName = $_SESSION['displayname'];
$_SESSION['username'];
$_SESSION['id'];
$userID = $_SESSION['id'];

$select_issues="";
$error = "";

$dateTime = date("g:i");
$dateDay = date("m/d/Y");

$welcome = '<div class="alert alert-success"><span style="font-size:1.5em;">'.$displayName.'</span><a href="javascript: logout_onclick()" id="logout-btn-2">Log Out</a><span class="display-date">'.$dateDay.'</span></div>';

//IF NO DISPLAY NAME REDIRECT TO LOGIN PAGE
if(!$_SESSION['displayname']){
        header("Location: index.php");
    } else {
        
        if(isset ($_POST['submit'])){

            $issueText = $_POST['textBox'];

            if($issueText == "" || empty($issueText)){
                    $error = "I can't read your mind, please TYPE your thoughts. :)";
                }
            
            if (!$error){
                //IF NO ERRORS INSERT THOUGHT INTO DATABASE :::::::::::::::
                $issueComplete = 0;

                $thought_query = $connection->prepare("INSERT INTO `issues` (user_id, complete, description)VALUES (?, ?, ?)");
                $thought_query->bind_param("iss", $userID, $issueComplete, $issueText);
                if($thought_query->execute()){
                    $thought_query->close();
                    header("Location: main.php");
                    
                } else {
                    die("QUERY FAILED!!");
                }
            }
        }

}            
        ?>
<?php
    

?>

<!DOCTYPE html>
<html>
  <head>
      <title>Meffley Company-Thought Catcher</title>
      <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
      <link rel="stylesheet" href="css/bootstrap.css?v=1.00" type="text/css">
      <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css?family=Shrikhand" rel="stylesheet">
      <link rel="stylesheet" type="text/css" href="css/styles.css?=1.22">
  </head>
  <body>
    
    <!-- Site Header -->
    <header class="header">
        <p id="header-text">Thought Catcher<span style="font-size:.5em">(Beta)</span></p>
        <p class="sub-header">An app for capturing fleeting thoughts</p>
    </header>
  
    <section class="container">
    <!-- Welcome or Error alert bar -->
	  <section class="row">
      <div class="col">
        <?php echo $welcome;  ?>
        <?php echo $error   ?>
      </div>
    </section>

    <!-- Issue entry block -->        
      <form method="post">
        <div class="row mt-4">
          <div class="col">
            <div class="form-group">
                <label for="customer"><h3>Add a Thought</h3></label>
                <input class="form-control" name="textBox" id="textBox" aria-describedby="thought text box" placeholder="Type your thought">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col">
            <button type="submit" name="submit" id=submit-button class="btn btn-outline-primary">Submit</button>
          </div>

        </div>   
      </form>
    
        <!-- Edit and update issue -->
      <form method="post">
            <div class="form-group">

      <!-- Echo Contents -->
              <?php 
                  if (isset($_GET['edit'])){

                      $issue_id = $_GET['edit'];
                      $edit_query = $connection->prepare("SELECT description FROM issues WHERE id=?");
                      $edit_query->bind_param("s",$issue_id);
                      $edit_query->execute();
                      $edit_query->bind_result($list_issue);
                      $edit_query->fetch();
                      $edit_query->close();
              ?>
      
              <div class="edit-container">
                  <label><strong>Update</strong></label>
                  <input value="<?php if(isset($issue_id)){echo $list_issue;} ?>" class="form-control" name="desc_update" type="text" >
                  <div class="row">
                      <div class="col">
                          <button type="submit" name="update"  id="update-btn" class="btn btn-warning">Update</button>
                      </div>
                      <div class="col">
                          <a name="cancel" id="cancel-btn"  href="main.php" id="update-button" class="btn btn-danger">Cancel</a>
                      </div>
                  </div>
              <?php  } ?>
              </div>
              <!-- Update list query -->
              <?php 
                  if(isset($_POST['update'])){

                      $get_row_id = $_GET['edit'];
                      $updated_description = $_POST['desc_update'];

                      $write_edit_query = $connection->prepare("UPDATE issues SET description = ? WHERE id = ?");
                      $write_edit_query->bind_param("si", $updated_description, $get_row_id);
                      if($write_edit_query->execute()){
                          $write_edit_query->close();
                          header("Location: main.php");
                      }
                  }
              ?> 
          </div>
      </form>  
        
        <br><br><br>
        
        <!-- DISPLAY ISSUES SECTION (TABLE) -->
        <div id="sort-nav-bar">
            <div class="row">
                <div class="col">
                    <a href='main.php?sort=date'>Sort By Date Added</a><small>&nbsp(Newest First)</small>
                </div>    
            </div>
        </div>    

            <div class="form">
                <table class="table table-bordered  table-hover"> 
                    <thead>
                        <tr>
                            <th>THOUGHTS</th>
                            <!--<th>Done</th>-->
                            <th>EDIT</th>
                            <th>DELETE</th>
                        </tr>
                    </thead>
            
                    <tbody>

                    <?php 
                        if(isset($_GET['sort'])){

                            $sort_query = $connection->prepare("SELECT id, description FROM issues WHERE user_id = ? ORDER BY date_created DESC");
                            $sort_query->bind_param("s", $userID);
                            $sort_query->execute();
                            $sort_query->bind_result($id, $description);
                            
                            

                            while($sort_query->fetch()){
                                $list_id = $id;
                                //$list_user_id = $row['user_id'];
                                $list_issue = $description;
                                
                                echo "<tr>";
                                echo "<td>{$list_issue}</td>";
                                //echo "<td><a href='main.php?done={$list_id}'>Done</a></td>";
                                echo "<td><a href='main.php?edit={$list_id}'>Edit</a></td>";
                                echo "<td><a href='main.php?delete={$list_id}'>Delete</a></td>";
                                echo "</tr>";
                            }
                        
                        } else{  
       
                            $query = $connection->prepare("SELECT id, description FROM issues WHERE user_id = ?");
                            $query->bind_param("s", $userID);
                            $query->execute();
                            $query->bind_result($id, $description);
                            
                            
                    
                            while($query->fetch()){
                                $list_id = $id;
                                //$list_user_id = $row['user_id'];
                                $list_issue = $description;
                                
                                echo "<tr>";
                                echo "<td>{$list_issue}</td>";
                                //echo "<td><a href='main.php?done={$list_id}'>Done</a></td>";
                                echo "<td><a href='main.php?edit={$list_id}'>Edit</a></td>";
                                echo "<td><a href='main.php?delete={$list_id}'>Delete</a></td>";
                                echo "</tr>";
                            }
                        }

                    ?>
                        
                    <?php
                        
                    //----------DELETE QUERY-------------
                        
                        if(isset($_GET['delete'])){
                            
                            $get_row_id = $_GET['delete'];
                            $delete_query = $connection->prepare("DELETE FROM  `issues` WHERE `id` = ?");
                            $delete_query->bind_param("i", $get_row_id);
                            if($delete_query->execute()){
                                $delete_query->close();
                                header("Location: main.php");
                            }
                             
                        }
                        
                    ?>

                    </tbody>
                    
                </table>
            </div>
 
    <!-- LOGOUT BUTTON -->    
    <!--<div class="row logout-btn-wrapper">
            <button type="button" id="logout-button" class="btn btn-danger" onclick="return logout_onclick()">Log Out</button>
    </div>-->
</section>

<footer class="footer">
    <div class="container footer-content">
        <p>&copy;<?php echo date('Y'); ?> The Meffley Company L.L.C.</p>
    </div>  
</footer>


<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript">

   function logout_onclick() {
            window.location.href = "logout.php";
        }
   function disableSubmit(){

        document.getElementById("submit-button").disabled = true;

   }
        
    
</script>
           
    </body>
</html>