<?php
	ob_start();
    session_start();
    date_default_timezone_set("America/New_York");
    include_once 'config/db.php';


    //Initialize variables
    $error = ""; $successMessage = ""; $userfocus = ""; $passfocus = "";

    //Initialize session variables
    $_SESSION['displayname']="";
    
    //Set cursor focus
    if(empty($_SESSION['username'])){

        $_SESSION['username'] = "";
        $userfocus = "autofocus";
    } else {
            $passfocus = "autofocus";
            $error = '<div class="alert alert-danger">Password Not Correct.</div>';
        }
    
    //Check if logged in
    if($_SESSION['displayname'] && $_SESSION['username'] && $_SESSION['id']){
        header("Location: main.php");
    }else{
    
        if (isset($_POST['submit'])){
            //Store Entered Username And Password
            $_SESSION['username'] = $_POST['username'];
            $enteredPassword = $_POST['password'];
            $enteredUsername = $_POST['username'];

            //Encrypt Password
            $hash = "$2y$10$";
            $salt = "l9asia5oanfv8no1aieh6a";
            $hash_and_salt = $hash.$salt;
            $enteredPassword = crypt($enteredPassword,$hash_and_salt);

            //Query Username
            if($stmt = $conn->prepare("SELECT id, username FROM `users` WHERE username = ?")){
                $stmt->bindParam("1", $enteredUsername);
                $stmt->execute();
                //$stmt->bind_result($id, $userName);
                $userName = $stmt->fetch();
                //$stmt->close();
            }else{
                $error = '<div class="alert alert-warning">Query Failed, Please try again.</div';
            }
            
            //If Username Match
            if($enteredUsername == $userName[1]){
                //Query Password
                if($pass_stmt = $conn->prepare("SELECT username, password, displayname FROM `users` WHERE password = ? AND username = ?")){
                    $pass_stmt->bindParam("1", $enteredPassword);
                    $pass_stmt->bindParam("2", $enteredUsername);
                    $pass_stmt->execute();
                    $password = $pass_stmt->fetch();
                } else {
                    $error = '<div class="alert alert-warning">Query Failed, Please try again.</div>';
                }
                
                if($enteredPassword == $password[1]){
                    //If Passwords Match    
                    $_SESSION['displayname'] = $displayName;
                    $_SESSION['username'] = $userName;
                    $_SESSION['id'] = $id;
                    $_SESSION['loggedIn'] = 1;
                    header("Location: orders/index.php");
                } else{
                    $error = '<div class="alert alert-danger">Password Not Correct.</div>';
                    header("Location: orders/index.php");
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

     <!-- Web Fonts--> 
     <link href="https://fonts.googleapis.com/css?family=Montserrat:300|Raleway:300,500" rel="stylesheet"> 
    <!-- Local CSS-->
    <link rel="stylesheet" type="text/css" href="css/main.css?=<?php echo time(); ?>">
   
  </head>

<body>

    <div id="error"><?php echo $error; ?></div>
    
    <header class="container header">
        
    </header>

    <section class="container">
        <div class="row">
            <img class="login-header-logo" src="img/igt-america-logo.svg"/>
            <p class="heading-md text-center text-color-primary">Production Suite</p>
            <div class="login-box">
            <p class="heading-sm text-center text-secondary">Log In</p>
            <form method="post" action="">
                <div class="row">
                    <div>
                        <input type="text" id="username" name="username" aria-describedby="emailHelp" placeholder="Email" value="<?php if($_SESSION['username']){ echo $_SESSION['username'];} ?>" <?php  echo $userfocus ?> >
                    </div>
                    <div>
                        <input type="password" id="password" name="password" placeholder="Password" <?php echo $passfocus ?>>
                    </div>
                </div>
                <div class="vh-2"></div>
                <div class="row">
                    <button type="submit" name="submit" class="btn btn-primary">Login</button>
                    <a href="signup.php" class="btn btn-link">Signup</a>
                    <!-- <a href="reset_request.php" class="btn btn-link" style="float:right;margin-top:10px;">Forgot Password</a> -->
                </div>
            </form>
            </div>
        </div>
    </section>
 

    <footer class="footer">
        <div class="container">
            <p class="text-center">&copy;<?php echo date('Y'); ?>&nbsp;IGT America Inc.</p>
        </div>  
    </footer>
    
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