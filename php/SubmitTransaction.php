<?php
	require_once('../php/internalConfig.php');
	if($dbHandler->CheckDBAvailable($ini)){
		$amount = $_POST['transactionAmount'];
		if($amount < 0){
			$amount *= -1;
		}
		$result = $dbHandler->AddTransaction($_POST['periodID'],$amount, $_POST['transactionDate'], $_POST['paidByVolunteer'], $_POST['transactionDescription']);
		if(is_array($result)){
			echo "<div class='errorMessage'>".$result[1]."</div>";
		}
		else{
			echo "<div class='alert alert-success'>Transaction of ".$_POST['transactionAmount']." ".$ini['currency']." on ".$_POST['transactionDate']." succesfully added</div>";
		}
	}
	else{
		echo "<div class='errorMessage'>Database not found</div>";
	}
	
	
?>
