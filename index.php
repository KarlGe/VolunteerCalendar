<?php

$ini = parse_ini_file('app.ini');
header('Location: '.$ini['homePage']);

?>