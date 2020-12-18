<?php

    //DB Params
    $servername = "localhost";
    $username = "appdev";
    $password = "";
    $dbname = "igt_orders";

    //DSN
	$dsn = 'mysql:sername=' . $servername . ';dbname=' . $dbname;
	// Instantiate new connection
    $conn = new PDO($dsn, $username, $password);

?>