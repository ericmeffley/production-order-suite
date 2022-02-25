<?php

    //DB Params
    $servername = "localhost";
    $username = "appdev";
    $password = "";
    $dbname = "production_suite_db";

    //DSN
	$dsn = 'mysql:sername=' . $servername . ';dbname=' . $dbname;
	
    $conn = new PDO($dsn, $username, $password);

?>