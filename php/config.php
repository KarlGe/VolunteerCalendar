<?php
    $ini = parse_ini_file('../app.ini');

	spl_autoload_register(function ($class_name) {
	    include "../php/classes/".$class_name . '.php';
	});

	error_reporting(0);
	
	if(!isset($dbHandler)){
		$dbHandler = new DbHandler();
		
		if(!$dbHandler->CheckDBAvailable($ini)){
				header('Location: ../pages/createDatabase.php');
		}
	}

    if($ini['debug']){
    	error_reporting(E_ALL);
    }
?>