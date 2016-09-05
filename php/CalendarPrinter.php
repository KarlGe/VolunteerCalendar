<?php
	//Supposed to print a calendar for ajax call to asynchronously update the calendar. We print the whole thing instead of updating individual values
	require_once("../php/internalConfig.php");
	require_once("../php/classes/Calendar.php"); 
	$calendar = new Calendar();
	echo $calendar->show();
?>