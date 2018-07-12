<?php
	ob_start();
    session_start();
    date_default_timezone_set("America/New_York");
    include_once 'config/db.php';


    //Variables
    $error = "";
    $successMessage = "";
    $_SESSION['displayname']="";
    
    //Check if logged in
    if($_SESSION['displayname'] && $_SESSION['username'] && $_SESSION['id']){
        header("Location: main.php");
    }else{
    
        if (isset($_POST['submit'])){
            //STORE ENTERED USERNAME AND PASSWORD
            $enteredPassword = $_POST['password'];
            $enteredUsername = $_POST['username'];

            //ENCRYPT PASSWORD
            $hash = "$2y$10$";
            $salt = "l9asia5oanfv8no1aieh6a";
            $hash_and_salt = $hash.$salt;
            $enteredPassword = crypt($enteredPassword,$hash_and_salt);

            //QUERY USERNAME
            if($stmt = $conn->prepare("SELECT id, username FROM `users` WHERE username = ?")){
                $stmt->bindParam("1", $enteredUsername);
                $stmt->execute();
                //$stmt->bind_result($id, $userName);
                $userName = $stmt->fetch();
                //$stmt->close();
            }else{
                $error = '<div class="alert alert-warning">Query Failed, Please try again.</div';
            }
            
            //IF USERNAME MATCH
            if($enteredUsername == $userName[1]){
                //QUERY PASSWORD
                if($pass_stmt = $conn->prepare("SELECT username, password, displayname FROM `users` WHERE password = ? AND username = ?")){
                    $pass_stmt->bindParam("1", $enteredPassword);
                    $pass_stmt->bindParam("2", $enteredUsername);
                    $pass_stmt->execute();
                    $password = $pass_stmt->fetch();
                } else {
                    $error = '<div class="alert alert-warning">Query Failed, Please try again.</div>';
                }
                
                if($enteredPassword == $password[1]){
                    //IF PASSWORDS MATCH    
                    $_SESSION['displayname'] = $displayName;
                    $_SESSION['username'] = $userName;
                    $_SESSION['id'] = $id;
                    $_SESSION['loggedIn'] = 1;
                    header("Location: orders/index.php");
                } else{
                    $error = '<div class="alert alert-danger">Password Not Correct.</div>';
                }
                
            } else {
                $error = '<div class="alert alert-danger">Username Not Found.</div>';
            }
        
        }
    }
        
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags always come first -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.css">
    
      
     <!-- Web Fonts--> 
    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Shrikhand" rel="stylesheet"> 
    
    <!-- Local CSS-->
    <link rel="stylesheet" type="text/css" href="css/styles.css?=1.18">
   
  </head>

<body>

    <div id="error"><?php echo $error; ?></div>
    
    <header>
        <div class="row">
            <div class="col">
                <p class="login-headline">IGT Production Suite</p>
            </div>
        </div>
    </header>

        <section class="container">
           <div class="login-box">
            <h2 style="text-align:center;color:#559cd6">Log In</h2>

            <div class="row">
                <div class="col-2"></div>
                <div class="col-8">
                    <form method="post" action="" class="login-form">
                        <div class="form-group">
                            <label for="username"><strong>Email</strong></label>
                            <input type="text" class="form-control" id="username" name="username" aria-describedby="emailHelp" placeholder="Email">
                        </div>
                        <div class="form-group">
                            <label><strong>Password</strong></label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                        </div>
                        <button type="submit" name="submit" class="btn btn-primary">Login</button>
                        <span class="signup-link"><a href="signup.php" class="signup">Signup</a></span>
                        <span class="signup-link"><a href="reset_request.php" class="signup" style="float:right">Forgot Password</a></span>
                    </form>
                </div>
                <div class="col-2"></div>
            </div>
    </div>
        </section>
 

    <div class="footer">
        <div class="container footer-content">
            <p>&copy;<?php echo date('Y'); ?>&nbsp;The Meffley Company L.L.C.</p>
        </div>  
    </div>
    
    <!-- jQuery first, then Tether, then Bootstrap JS. -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.0.0/jquery.min.js" integrity="sha384-THPy051/pYDQGanwU6poAc/hOdQxjnOEXzbT+OuUAFqNqFjL+4IGLBgCJC3ZOShY" crossorigin="anonymous"></script>
      
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.2.0/js/tether.min.js" integrity="sha384-Plbmg8JY28KFelvJVai01l8WyZzrYWG825m+cZ0eDDS1f7d/js6ikvy1+X+guPIB" crossorigin="anonymous"></script>
      
    <!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.3/js/bootstrap.min.js" integrity="sha384-ux8v3A6CPtOTqOzMKiuo3d/DomGaaClxFYdCu2HPMBEkf6x2xiDyJ7gkXU0MWwaD" crossorigin="anonymous"></script>-->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
      
    <script type="text/javascript">
        
        $("form").submit(function(e){
                
            var error = "";

                      
            if($("#password").val() == ""){

            error+= "Password is a required field. </br>";

            }
            
            if($("#username").val() == ""){

            error+= "Username is a required field.";

            }
                      
            if(error != ""){

                $("#error").html('<div class="alert alert-danger" role="alert">' + error + '</div>');

                return false;

            } else {

                return true;

            }
      
        })
      
      
    </script>  
  </body>
</html>