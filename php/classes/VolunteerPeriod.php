<?php
	class VolunteerPeriod{
		public $id;
		public $dateFrom;
		public $dateTo;
		public $moneyOwed;
		public $moneyPaid;
		public $contractSigned;
		public $regTimestamp;
		public $active;
		public $transactions = array();

		function __construct($id = "", $dateFrom = "", $dateTo = "", $moneyOwed = "", $moneyPaid = "", $contractSigned = "", $regTimestamp = "", $active = "") {
			$this->id = $id;
			$this->dateFrom = $dateFrom;       
			$this->dateTo = $dateTo;
			$this->moneyOwed = $moneyOwed;
			$this->moneyPaid = $moneyPaid;
			$this->contractSigned = $contractSigned;
			$this->regTimestamp = $regTimestamp;
			$this->active = $active;
		}

		function GetTransactionTotal(){
			$total = 0;
			foreach ($this->transactions as $transaction) {
				if($transaction->paidByVolunteer == 1){
					$total += $transaction->amount;
				}
				else{
					$total -= $transaction->amount;
				}
			}
			return $total;
		}
	}
?>