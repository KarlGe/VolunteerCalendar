<?php
class DbHandler{
	public $ini;
	function __construct() {
		$this->ini = parse_ini_file('../app.ini'); 
	}
	function CreateDB($ini){
		//Creates the database
		try {
			$initialDb = new PDO("mysql:host=".$ini['host'], $ini['db_username'], $ini['db_password']);
			$initialDb->exec( 'CREATE DATABASE '.$ini['db_name']) or die(print_r($initialDb->errorInfo(), true));
		} catch (PDOException $e) {
	    	die("DB ERROR: ". $e->getMessage());
	    	return false;
		}
		$initialDb = null;
		return true;
	}
	function CreateTables($ini){
		//Creates the tables
		$pdo = new PDO("mysql:host=".$ini['host'].";dbname=".$ini['db_name'], $ini['db_username'], $ini['db_password']);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		try {
			$pdo->beginTransaction();
			$query = file_get_contents("../sql/tblCountriesCreate.sql");
			$stmt = $pdo->prepare($query);
			$stmt->execute();
			$query = file_get_contents("../sql/tblVolunteerCreate.sql");
			$stmt = $pdo->prepare($query);
			$stmt->execute();
		} catch (PDOException $e) {
			$pdo->rollBack();
			//Return true if error code is 42S01, I.E tables already exist.
    		if($e->getCode() == "42S01"){
    			return true;
    		}
    		echo 'Connection failed: ' . $e->getMessage();
			return false;
		}

		$pdo->commit();
		$pdo = null;
		return true;
	}
	function CheckDBAvailable($ini){
		try { 
			$pdo = new PDO("mysql:host=".$ini['host'].";dbname=".$ini['db_name'], $ini['db_username'], $ini['db_password']);
		}
		catch (PDOException $e) {
			return false;
		}
		if(isset($pdo)){
			$pdo = null;
			return true;;
		}
		return false;
	}
	function CheckTablesSetup($ini){
		try{
			$pdo = new PDO("mysql:host=".$ini['host'].";dbname=".$ini['db_name'], $ini['db_username'], $ini['db_password']);
			$results = $pdo->query("SHOW TABLES LIKE 'countries'")->rowCount();
		    if($results == 0) {
		        return false;
		    }
		    $pdo = null;
		    return true;	
		}
		catch(PDOException $e){
			if($ini['debug']){
				echo $e->getMessage(). " trace: ".$e->getTraceAsString();
			}
			return false;
		}
	}
}
	
?>