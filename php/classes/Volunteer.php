<?php 
	class Volunteer{
		public $id;
		public $name;
		public $phoneNumber;
		public $nationality;
		public $notes;
		public $gender;
		public $email;
		public $periods = array();

		function __construct($id = "", $name = "", $phoneNumber = "", $nationality = "", $notes = "", $gender = "", $email = "") {
			$this->id = $id;
			$this->name = $name;       
			$this->phoneNumber = $phoneNumber;
			$this->nationality = $nationality;
			$this->notes = $notes;
			$this->gender = $gender;
			$this->email = $email;
		}
	}
?>