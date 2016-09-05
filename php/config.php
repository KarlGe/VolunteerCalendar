<?php
	require_once("internalConfig.php");

	error_reporting(0);
	
	if(!$dbHandler->CheckDBAvailable($ini)){
			header('Location: ../pages/createDatabase.php');
	}

    if($ini['debug']){
    	error_reporting(E_ALL);
    }
?>