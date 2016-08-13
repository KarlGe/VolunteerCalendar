<?php
	require_once('../php/internalConfig.php');
	if($dbHandler->CheckDBAvailable($ini)){
		$result = $dbHandler->AddPersonWithPeriod($_POST['volunteerName'], $_POST['dateFrom'], $_POST['dateTo']);
		if(is_array($result)){
			echo "<div class='errorMessage'>".$result[1]."</div>";
		}
		else{
			echo "<div class='alert alert-success'>".$_POST['volunteerName']." added succesfully from ".$_POST['dateFrom']." to ".$_POST['dateTo']."</div>";
		}
	}
	
?>
