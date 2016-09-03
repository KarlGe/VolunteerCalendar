<?php
	class Transaction{
		public $id;
		public $periodID;
		public $transactionDate;
		public $description;
		public $amount;
		public $paidByVolunteer;

		function __construct($id = "", $periodID = "", $transactionDate = "", $description = "", $amount = "", $paidByVolunteer = "") {
			$this->id = $id;
			$this->periodID = $periodID;       
			$this->transactionDate = $transactionDate;
			$this->description = $description;
			$this->amount = $amount;
			$this->paidByVolunteer = $paidByVolunteer;
		}
	}
?>