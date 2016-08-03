<?php
    $ini = parse_ini_file('../app.ini');
    // Report simple running errors

	spl_autoload_register(function ($class_name) {
	    include "../php/classes/".$class_name . '.php';
	});

	error_reporting(0);
  	include '../php/dbConnect.php';

    if($ini['debug']){
    	error_reporting(E_ALL);
    }
?>