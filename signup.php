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

    <!-- Web Fonts--> 
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300|Raleway:300,500" rel="stylesheet"> 
    <!-- Local CSS-->
    <link rel="stylesheet" type="text/css" href="css/main.css?=<?php echo time(); ?>">
</head>
  <body>
    
    <!-- Error Section -->
    <div class="container" id="error"><?php echo $error; ?></div>

    <section class="container">
        <div class="row">
            <img class="login-header-logo" src="img/igt-america-logo.svg"/>
            <p class="heading-md text-center text-color-primary">Production Suite</p>
            <div class="login-box">
                <p class="heading-sm text-center text-secondary">Sign Up</p>
                <form name="signupForm" onsubmit="return passwordMatch()" method="post">
                <div>
                    <input type="text" id="displayName" name="displayName" aria-describedby="displayName" placeholder="Choose a display name." required>
                </div>
                <div>
                    <input type="email" id="userName" name="userName" aria-describedby="emailHelp" placeholder="Enter email" required>
                </div>
                <div>
                    <input type="password" id="password" name="password" placeholder="Password" required>
                </div>
                <div>
                    <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password" required/>
                </div>
                <button type="submit" name="submit" class="btn btn-primary">Sign Up</button>
                <a href="index.php" class="btn btn-link text-secondary">Login</a>
                </form>
            </div>
            </div>
    </section>
    <script src="js/main.js"></script>
    <!-- jQuery first, then Tether, then Bootstrap JS. -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.0.0/jquery.min.js" integrity="sha384-THPy051/pYDQGanwU6poAc/hOdQxjnOEXzbT+OuUAFqNqFjL+4IGLBgCJC3ZOShY" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.2.0/js/tether.min.js" integrity="sha384-Plbmg8JY28KFelvJVai01l8WyZzrYWG825m+cZ0eDDS1f7d/js6ikvy1+X+guPIB" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.4/js/bootstrap.min.js" integrity="VjEeINv9OSwtWFLAtmc4JCtEJXXBub00gtSnszmspDLCtC0I4z4nqz7rEFbIZLLU" crossorigin="anonymous"></script>

    

  </body>
</html>