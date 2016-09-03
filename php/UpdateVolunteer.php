<?php
	
	spl_autoload_register(function ($class_name) {
		include "../php/classes/".$class_name . '.php';
	});
	$dbHandler = new DbHandler();
	
	if(isset($_POST['pk'])){
		if($_POST['name'] == 'volunteerName'){
			$dbHandler->UpdateVolunteer($_POST['pk'], 'name', $_POST['value']);
		}
		if($_POST['name'] == 'phoneNum'){
			$dbHandler->UpdateVolunteer($_POST['pk'], 'phoneNum', $_POST['value']);
		}
		if($_POST['name'] == 'email'){
			$dbHandler->UpdateVolunteer($_POST['pk'], 'email', $_POST['value']);
		}
		if($_POST['name'] == 'notes'){
			$dbHandler->UpdateVolunteer($_POST['pk'], 'notes', $_POST['value']);
		}
	}
?>