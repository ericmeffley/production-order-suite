<?php
    include_once("config/db.php");

    $error = "";
    $result = "";

    if(isset($_POST['submit'])){

        //STORE POST VARIABLES
        $username = $_POST["userName"];
        $password = $_POST["password"];
        $displayName = $_POST["displayName"];

        //ENCRYPT PASSWORD
        $hash = "$2y$10$";
        $salt = "l9asia5oanfv8no1aieh6a";
        $hash_and_salt = $hash.$salt;
        $password = crypt($password,$hash_and_salt);

        $sign_up_query = $conn->prepare("INSERT INTO users (username,password,displayname) VALUES (?,?,?)");
        $sign_up_query->bindParam(1, $username);
        $sign_up_query->bindParam(2, $password);
        $sign_up_query->bindParam(3, $displayName);
        $result = $sign_up_query->execute();
        
        if($result){
            $error="<div class='alert alert-success' role='alert'><p>Signup successfull, welcome to the family</p></div>";

            header( "Refresh:2; url=index.php", true, 303);
        } else {
            die("Sign up unsuccessful please try again later");
        }
    }
    


?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags always come first -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.css">

    <!-- Google Web Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Shrikhand" rel="stylesheet"> 

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/styles.css?=1.05">
    

  </head>
  <body>
    

    <div class="container" id="error"><?php echo $error; ?></div>
    <div class="container">
    <div style="text-align:center;margin-top:50px;">
      <h1 style="color:navy;">IGT Production Suite</h1>
    </div>
        <div class="signup-container">
            <h2 style="text-align:center;color:#559cd6">Sign Up</h2>
            <br>
        <div class="row">
          <div class="col-sm-2"></div>
            <div class="col-sm-8">
              <form name="signupForm" onsubmit="return passwordMatch()" method="post">
                <div class="form-group">
                    <label for="displayName">Display Name</label>
                    <input type="text" class="form-control" id="displayName" name="displayName" aria-describedby="displayName" placeholder="Choose a display name." required>
                </div>
                <div class="form-group">
                    <label for="userName">Username</label>
                    <input type="email" class="form-control" id="userName" name="userName" aria-describedby="emailHelp" placeholder="Enter email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                </div>
                <div class="form-group">
                    <label for="confirmPassword">Confirm Password</label>
                    <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="" required/>
                </div>
                <input type="submit" value="Sign Up" name="submit" class="btn btn-primary">
                <span class="signup-link"><a href="index.php" class="signup">Login</a></span>
              </form>
            </div>
          </div>
          <div class="col-sm-2"></div>
        </div>
    </div>
    <script src="js/main.js"></script>
    <!-- jQuery first, then Tether, then Bootstrap JS. -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.0.0/jquery.min.js" integrity="sha384-THPy051/pYDQGanwU6poAc/hOdQxjnOEXzbT+OuUAFqNqFjL+4IGLBgCJC3ZOShY" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.2.0/js/tether.min.js" integrity="sha384-Plbmg8JY28KFelvJVai01l8WyZzrYWG825m+cZ0eDDS1f7d/js6ikvy1+X+guPIB" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.4/js/bootstrap.min.js" integrity="VjEeINv9OSwtWFLAtmc4JCtEJXXBub00gtSnszmspDLCtC0I4z4nqz7rEFbIZLLU" crossorigin="anonymous"></script>

    

  </body>
</html>