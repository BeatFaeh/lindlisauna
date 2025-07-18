<?php 

    # URLs definieren
    $test_url = "https://tst.lindlisauna.ch";
    $prod_url = "https://lindlisauna.ch";

    # Aktuelle URL ermitteln
    $current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");
    $current_url .= "://$_SERVER[HTTP_HOST]";

    # Entscheidung mit switch
    switch ($current_url) {
        case $test_url:
            $username = "tst_lindlisauna_usr";
            $password = "qSqVf6St$5i^my7e";
            $host     = "localhost";
            $dbname   = "tst_lindlisauna_db";
            break;
        case $prod_url:
            $username = "lindlisauna_usr";
            $password = "qSqVf6St$5i^my7e";
            $host     = "localhost";
            $dbname   = "lindlisauna_db";
            break;
    }

    $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'); 
    try { 
    $db = new PDO("mysql:host={$host};dbname={$dbname};charset=utf8", $username, $password, $options); 
	}
	catch(PDOException $ex) { 
		die("Database credentials are incorrect. {$ex->getMessage()}");
	}
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); 
    header('Content-Type: text/html; charset=utf-8'); 
	
	
	
	// 1. Create a database connection
	$conection=mysqli_connect($host,$username,$password,$dbname);
	if (!$conection) {
		die("Database connection failed: " . mysqli_error());
	}

	// 2. Select a database to use 
	$db_selected = mysqli_select_db($conection, $dbname);
	if (!$db_selected) {
		die("Database selection failed: " . mysqli_error());
	}
	
    session_start(); 
?>