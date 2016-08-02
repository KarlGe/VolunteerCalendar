<?php
    $ini = parse_ini_file('../app.ini');
    // Report simple running errors

	error_reporting(0);
  	include '../php/dbConnect.php';

    if($ini['debug']){
    	error_reporting(E_ALL);
    }
?>