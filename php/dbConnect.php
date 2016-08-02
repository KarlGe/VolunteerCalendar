<?php
	try
	{
		$conn = mysqli_connect($ini['host'],$ini['db_username'],$ini['db_password'],$ini['db_name']);
		// Check connection
		if (mysqli_connect_errno()) {
			throw new Exception(mysqli_connect_error());
		}
	}
	catch (Exception $e)
	{
		if(mysqli_connect_errno() == 1049){

			header('Location: ../pages/createDatabase.php');
		}
	}
?>