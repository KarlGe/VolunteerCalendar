<?php
	
	spl_autoload_register(function ($class_name) {
		include "../php/classes/".$class_name . '.php';
	});
	$dbHandler = new DbHandler();
	if(isset($_POST['pk'])){
		if($_POST['name'] == 'delete'){
			$dbHandler->UpdateTransaction($_POST['pk'], 'active', 0);
		}
		if($_POST['name'] == 'restore'){
			$dbHandler->UpdateTransaction($_POST['pk'], 'active', 1);
		}
		if($_POST['name'] == 'description'){
			$dbHandler->UpdateTransaction($_POST['pk'], 'description', $_POST['value']);
		}
	}
?>