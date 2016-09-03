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
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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
	function GetVolunteer($id){
		try{
			$dbh = new PDO("mysql:host=".$this->ini['host'].";dbname=".$this->ini['db_name'], $this->ini['db_username'], $this->ini['db_password']);
			$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$sql = "SELECT volunteer.*, countries.country FROM volunteer LEFT JOIN countries on volunteer.nationalityID = countries.ID WHERE volunteer.ID = :id";
			$sth = $dbh->prepare($sql);
			$sth->bindParam(':id', $id, PDO::PARAM_INT);
			$sth->execute();
			$result = $sth->fetch(PDO::FETCH_ASSOC);
		    $dbh = null;


			$volunteer = new Volunteer(
				$result['ID'],
				$result['name'],
				$result['phoneNum'],
				$result['country'],
				$result['notes'],
				$result['gender'],
				$result['email']
			);
		    $volunteer->periods = $this->GetPeriodsForVolunteer($id);

		    return $volunteer;	
		}
		catch(PDOException $e){
			if($this->ini['debug']){
				echo $e->getMessage(). " trace: ".$e->getTraceAsString();
			}
			return false;
		}
	}
	//Returns all time periods for a given volunteer
	function GetPeriodsForVolunteer($volunteerID){
		try{
			$dbh = new PDO("mysql:host=".$this->ini['host'].";dbname=".$this->ini['db_name'], $this->ini['db_username'], $this->ini['db_password']);
			$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$sql = "SELECT * FROM volunteerhistory WHERE volunteerID = :volunteerID ORDER BY dateTo";
			$sth = $dbh->prepare($sql);
			$sth->bindParam(':volunteerID', $volunteerID, PDO::PARAM_INT);
			$sth->execute();
			$results = $sth->fetchAll();

			$volunteerArray = array();
			foreach ($results as $result) {
				$volunteerPeriod = new VolunteerPeriod(
					$result["ID"],
					$result["dateFrom"],
					$result["dateTo"],
					$result["contractSigned"],
					$result["reg_date"],
					$result["active"]
				);

				$volunteerPeriod->transactions = $this->GetTransactionsForPeriod($volunteerPeriod->id);

				array_push($volunteerArray, $volunteerPeriod);
			}
		    $dbh = null;
		    return $volunteerArray;	
		}
		catch(PDOException $e){
			if($this->ini['debug']){
				echo $e->getMessage(). " trace: ".$e->getTraceAsString();
			}
			return false;
		}
	}
	function GetTransactionsForPeriod($periodID){
		try{
			$dbh = new PDO("mysql:host=".$this->ini['host'].";dbname=".$this->ini['db_name'], $this->ini['db_username'], $this->ini['db_password']);
			$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$sql = "SELECT * FROM volunteerTransactionHistory WHERE periodID = :periodID ORDER BY transactionDate";
			$sth = $dbh->prepare($sql);
			$sth->bindParam(':periodID', $periodID, PDO::PARAM_INT);
			$sth->execute();
			$results = $sth->fetchAll();

			$transactionArray = array();
			foreach ($results as $result) {
				$transaction = new Transaction(
					$result["ID"],
					$result["periodID"],
					$result["transactionDate"],
					$result["description"],
					$result["amount"],
					$result["paidByVolunteer"],
					$result["active"]
				);
				array_push($transactionArray, $transaction);
			}

		    $dbh = null;
		    return $transactionArray;	
		}
		catch(PDOException $e){
			if($this->ini['debug']){
				echo $e->getMessage(). " trace: ".$e->getTraceAsString();
			}
			return false;
		}
	}
	//Returns all time period entries that overlaps with the given month
	function GetPeriods($month, $columns = "*"){
		try{
			$dbh = new PDO("mysql:host=".$this->ini['host'].";dbname=".$this->ini['db_name'], $this->ini['db_username'], $this->ini['db_password']);
			$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$sql = "SELECT ".$columns." FROM volunteerhistory WHERE :month BETWEEN MONTH(dateFrom) AND MONTH(dateTo)";
			$sth = $dbh->prepare($sql);
			$sth->bindParam(':month', $month, PDO::PARAM_INT);
			$sth->execute();
			$result = $sth->fetchAll();
		    $dbh = null;
		    return $result;	
		}
		catch(PDOException $e){
			if($this->ini['debug']){
				echo $e->getMessage(). " trace: ".$e->getTraceAsString();
			}
			return false;
		}
	}
	function GetVolunteersWithPeriodOnDate($date, $columns = "*"){
		try{
			$dbh = new PDO("mysql:host=".$this->ini['host'].";dbname=".$this->ini['db_name'], $this->ini['db_username'], $this->ini['db_password']);
			$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sql = "SELECT ".$columns." FROM volunteerhistory LEFT JOIN volunteer ON volunteerhistory.volunteerID=volunteer.ID WHERE :date BETWEEN dateFrom AND dateTo";
			$sth = $dbh->prepare($sql);
			$sth->bindParam(':date', $date, PDO::PARAM_STR);
			$sth->execute();
			$result = $sth->fetchAll();
		    $dbh = null;
		    return $result;	
		}
		catch(PDOException $e){
			if($this->ini['debug']){
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
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			// prepare sql and bind parameters
		    $stmt = $conn->prepare("INSERT INTO volunteer (name, phoneNum, notes, gender, nationalityID, email, active)
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
	function AddPeriod($volunteerID, $dateFrom, $dateTo, $contractSigned = 0){
		try{
			$active = 1;
			$dateFrom = date('Y-m-d', strtotime($dateFrom));
			$dateTo = date('Y-m-d', strtotime($dateTo));
			$conn = new PDO("mysql:host=".$this->ini['host'].";dbname=".$this->ini['db_name'], $this->ini['db_username'], $this->ini['db_password']);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			// prepare sql and bind parameters
		    $stmt = $conn->prepare("INSERT INTO volunteerhistory (volunteerID, dateFrom, dateTo, contractSigned, active)
		    VALUES (:volunteerID, :dateFrom, :dateTo, :contractSigned, :active)");
		    $stmt->bindParam(':volunteerID', $volunteerID);
		    $stmt->bindParam(':dateFrom', $dateFrom);
		    $stmt->bindParam(':dateTo', $dateTo);
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
	function AddTransaction($periodID, $transactionAmount, $transactionDate, $paidByVolunteer, $description){
		try{
			$date = date('Y-m-d', strtotime($transactionDate));
			$conn = new PDO("mysql:host=".$this->ini['host'].";dbname=".$this->ini['db_name'], $this->ini['db_username'], $this->ini['db_password']);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			// prepare sql and bind parameters
		    $stmt = $conn->prepare("INSERT INTO volunteerTransactionHistory (periodID, amount, transactionDate, paidByVolunteer, description)
		    VALUES (:periodID, :amount, :transactionDate, :paidByVolunteer, :description)");
		    $stmt->bindParam(':periodID', $periodID);
		    $stmt->bindParam(':amount', $transactionAmount);
		    $stmt->bindParam(':transactionDate', $date);
		    $stmt->bindParam(':paidByVolunteer', $paidByVolunteer);
		    $stmt->bindParam(':description', $description);

		    $stmt->execute();

		    $conn = null;
		    return true;	
		}
		catch(PDOException $e){
			return array(false, $e->getMessage(). " trace: ".$e->getTraceAsString());
		}
	}
	function UpdateVolunteer($volunteerID, $column, $value){
		$sql = "UPDATE volunteer SET volunteer.".$column." = :value WHERE volunteer.ID = :volunteerID";
		$params = array('value' => $value, 'volunteerID' => $volunteerID);
		$this->ExecuteSQL($sql,$params);
	}
	function UpdatePeriod($periodID, $column, $value){
		$sql = "UPDATE volunteerhistory SET volunteerhistory.".$column." = :value WHERE volunteerhistory.ID = :periodID";
		$params = array('value' => $value, 'periodID' => $periodID);
		$this->ExecuteSQL($sql,$params);
	}
	function UpdateTransaction($transactionID, $column, $value){
		$sql = "UPDATE volunteertransactionhistory SET volunteertransactionhistory.".$column." = :value WHERE volunteertransactionhistory.ID = :transactionID";
		$params = array('value' => $value, 'transactionID' => $transactionID);
		$this->ExecuteSQL($sql,$params);
	}
	function ExecuteSQL($sql, $params){
		try{
			$dbh = new PDO("mysql:host=".$this->ini['host'].";dbname=".$this->ini['db_name'], $this->ini['db_username'], $this->ini['db_password']);
			$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$sth = $dbh->prepare($sql);
			$sth->execute($params);
		    $dbh = null;
		    return true;	
		}
		catch(PDOException $e){
			if($this->ini['debug']){
				echo $e->getMessage(). " trace: ".$e->getTraceAsString();
			}
			return false;
		}
	}
}
	
?>