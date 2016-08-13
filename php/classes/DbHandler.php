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
	function AddPersonWithPeriod($name, $dateFrom, $dateTo, $phoneNum = null, $notes = null, $gender = null, $nationalityID = null, $email = null){
		$volunteerID = $this->AddPerson($name);
		//If there is an error, AddPerson will return an array with false at the first index and an error message on the second index, so we return that to display on the front end
		if(is_array($volunteerID)){
			return $volunteerID;
		}

		$periodID = $this->AddPeriod($volunteerID, $dateFrom, $dateTo);
		//Same with AddPeriod
		if(is_array($periodID)){
			return $periodID;
		}
		return true;

	}
	function AddPerson($name, $phoneNum = null, $notes = null, $gender = null, $nationalityID = null, $email = null){
		try{
			$active = 1;
			$conn = new PDO("mysql:host=".$this->ini['host'].";dbname=".$this->ini['db_name'], $this->ini['db_username'], $this->ini['db_password']);

			// prepare sql and bind parameters
		    $stmt = $conn->prepare("INSERT INTO ".$this->ini['db_volunteerTable']." (name, phoneNum, notes, gender, nationalityID, email, active)
		    VALUES (:name, :phoneNum, :notes, :gender, :nationalityID, :email, :active)");
		    $stmt->bindParam(':name', $name);
		    $stmt->bindParam(':phoneNum', $phoneNum);
		    $stmt->bindParam(':notes', $notes);
		    $stmt->bindParam(':gender', $gender);
		    $stmt->bindParam(':nationalityID', $nationalityID);
		    $stmt->bindParam(':email', $email);
		    $stmt->bindParam(':active', $active);

		    $stmt->execute();

		    $lastId = $conn->lastInsertId();

		    $conn = null;
		    return $lastId;	
		}
		catch(PDOException $e){
			return array(false, $e->getMessage(). " trace: ".$e->getTraceAsString());
		}
	}
	function AddPeriod($volunteerID, $dateFrom, $dateTo, $moneyOwed = null, $moneyPaid = null, $contractSigned = null){
		try{
			$active = 1;
			$dateFrom = date('Y-m-d', strtotime(str_replace('-', '/', $dateFrom)));
			$dateTo = date('Y-m-d', strtotime(str_replace('-', '/', $dateTo)));
			$conn = new PDO("mysql:host=".$this->ini['host'].";dbname=".$this->ini['db_name'], $this->ini['db_username'], $this->ini['db_password']);

			// prepare sql and bind parameters
		    $stmt = $conn->prepare("INSERT INTO ".$this->ini['db_periodTable']." (volunteerID, dateFrom, dateTo, moneyOwed, moneyPaid, contractSigned, active)
		    VALUES (:volunteerID, :dateFrom, :dateTo, :moneyOwed, :moneyPaid, :contractSigned, :active)");
		    $stmt->bindParam(':volunteerID', $volunteerID);
		    $stmt->bindParam(':dateFrom', $dateFrom);
		    $stmt->bindParam(':dateTo', $dateTo);
		    $stmt->bindParam(':moneyOwed', $moneyOwed);
		    $stmt->bindParam(':moneyPaid', $moneyPaid);
		    $stmt->bindParam(':contractSigned', $contractSigned);
		    $stmt->bindParam(':active', $active);

		    $stmt->execute();

		    $lastId = $conn->lastInsertId();

		    $conn = null;
		    return true;	
		}
		catch(PDOException $e){
			return array(false, $e->getMessage(). " trace: ".$e->getTraceAsString());
		}
	}
}
	
?>