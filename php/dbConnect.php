<?php
	if(!isset($dbHandler)){
		$dbHandler = new DbHandler();
		
		if(!$dbHandler->CheckDBAvailable($ini)){
				header('Location: ../pages/createDatabase.php');
		}
	}
?>