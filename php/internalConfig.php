<?php

	$ini = parse_ini_file('../app.ini');

	spl_autoload_register(function ($class_name) {
	    include "../php/classes/".$class_name . '.php';
	});

	$dbHandler = new DbHandler();
?>