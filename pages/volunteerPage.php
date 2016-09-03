<?php  
include '../includes/header.php';

/// <summary>
/// Prints an editable span with either the supplied value or the alt text if it isn't available
/// </summary>
/// <param name="primaryKey">Some Parameter.</param>
/// <returns>What this method returns.</returns>
function PrintValue($primaryKey, $id, $value, $title, $altText, $dataUrl, $dataType = "text"){
	if(!isset($value) || $value == ""){
		$value = $altText;
	}
	return '<div title="'.$title.'" class="edit" id="'.$id.'" data-type="'.$dataType.'" data-pk="'.$primaryKey.'" data-url="../php/'.$dataUrl.'">'.$value.'</div>';
}
if(isset($_GET['id'])):
	$volunteer = $dbHandler->GetVolunteer(intval($_GET['id']));
	$currentPeriod;
	if(isset($_GET['periodID'])){
		foreach ($volunteer->periods as $period) {
			if($period->id == $_GET['periodID']) {
				$currentPeriod = $period;
			}
		}
	}
	else{
		foreach ($volunteer->periods as $period) {
			if(strtotime(date("Y/m/d")) >= strtotime($period->dateFrom) && strtotime(date("Y/m/d")) <= strtotime($period->dateTo)){
				$currentPeriod = $period;
			}
		}	
	}
?>


    


<div id="volunteerHeader">
	<div class="inlineBlock" id="name">
		<h1 title="Volunteer name" class="edit" id="volunteerName" data-type="text" data-pk="<?php echo $volunteer->id; ?>" data-url="../php/UpdateVolunteer.php"><?php echo $volunteer->name ?> </h1>
	</div>
	<div id="volunteerDates">
		<div class='inlineBlock dateBlock'>
			<span>
				<?php 
				if(isset($currentPeriod)){
					$timeStamp = strtotime($currentPeriod->dateFrom);
					echo date('d M Y', $timeStamp); 
				}
				?>
			</span>
		</div>	
		<div class='inlineBlock positionRelative'>
			<span class="dateDash">
				-
			</span>
		</div>
		<div class='inlineBlock dateBlock'>
			<span>
				<?php 
				if(isset($currentPeriod)){
					$timeStamp = strtotime($currentPeriod->dateTo);
					echo date('d M Y', $timeStamp); 
				}
				?>
			</span>
		</div>
	</div>
	<div class="volunteerContactInfo">
			<span class="glyphicon glyphicon-earphone"> </span>
			<?php echo PrintValue($volunteer->id, 'phoneNum', $volunteer->phoneNumber, "The volunteers phone number", "Click to add number", "UpdateVolunteer.php"); ?>
		<span class="glyphicon glyphicon-envelope"> </span>
		<?php echo PrintValue($volunteer->id, 'email', $volunteer->email, "The volunteers email address", "Click to add email", "UpdateVolunteer.php"); ?>
	</div>
</div>
<?php if(isset($currentPeriod)): ?>
	<div id="periodInfo">
		<h2>Notes</h2>
		<div id="volunteerNotes">
			<?php echo PrintValue($volunteer->id, 'notes', $volunteer->notes, "Notes about the volunteer and his/her visit", "No notes found, click to add", "UpdateVolunteer.php", "textarea"); ?>
		</div>
		<div id="contractSigned"> 
			<span>Contract signed</span> <span class="checkBox" data-pk="<?php echo $currentPeriod->id; ?>" value="<?php echo $currentPeriod->contractSigned; ?>" id="contractCheckBox"> <img class="checked <?php echo $currentPeriod->contractSigned != 1 ? "hidden" : ""; ?>" src="../img/checkmark.png" /> </span>
		</div>
	</div>
	<div id="transactionHistory">
		<h2>Transaction history</h2>
		<form id="submitTransactionForm" class="inlineList">
			<ul>
				<li>
					<label for="transactionAmount">Amount</label><br />
					<input type="number" id="transactionAmount" placeholder="Amount" name="transactionAmount">
				</li>
				<li>
					<label for="transactionDate">Date</label><br />
					<input type="date" id="transactionDate" value="<?php echo date("Y-m-d"); ?>" name="transactionDate">
				</li>
				<li>
					<label for="paidByList">Paid by</label><br />
					<select id="paidByList" name="paidByVolunteer">
						<option selected value="0">Felidae</option>
						<option value="1">Volunteer</option>
					</select>
				</li>
				<li>
					<label for="transactionDescription">Date</label><br />
					<input type="text" id="transactionDescription" placeholder="Description of transaction" name="transactionDescription">
				</li>
				<li>
					<input type="hidden" name="periodID" value="<?php echo $currentPeriod->id; ?>">
					<input id="transactionSubmit" type="submit" value="Add transaction">
				</li>
			</ul>
		</form>
		<?php if(count($currentPeriod->transactions) > 0): ?>
			<table id="transactionHistoryTable" class="table table-striped">
				<thead>
						<th>Description</th>
						<th>Paid by</th>
						<th>Date</th>
						<th>Amount</th>
						<th></th>
				</thead>
			<?php foreach ($currentPeriod->transactions as $transaction): ?>
				<tr data-pk="<?php echo $transaction->id; ?>" active="<?php echo $transaction->active;?>" <?php echo $transaction->active == 0 ? 'class="deletedTransaction"' : ""; ?>>	
					<td>
						<?php echo PrintValue($transaction->id, 'description', $transaction->description, "Description of the transaction", "Click to add description", "UpdateTransaction.php"); ?>
					</td>
					<td><?php echo $transaction->paidByVolunteer == 0 ? "Felidae" : "Volunteer" ?></td>
					<td><?php echo date("d M Y",strtotime($transaction->transactionDate)) ?></td>	
					<td class="transactionAmount"><?php echo $transaction->paidByVolunteer == 1 ? $transaction->amount : $transaction->amount * -1 ?></td>
					<td class="transactionTrash"><?php echo $transaction->active == 0 ? '<span class="restore">Restore</span>' : '<span class="trashCan"></span>'; ?></td>
				</tr>
			<?php endforeach; ?>		
			</table>
			<div class="btn btn-default" id="showDeletedButton">Show deleted</div>
			<div id="transactionHistoryTotal">
				Current balance: <?php echo "<span>".$currentPeriod->GetTransactionTotal()."</span> ".$ini['currency']; ?>
			</div>
		<?php else: ?>
			<h2>No transactions found for this period</h2>
		<?php endif ?>
	</div>
	<div class="clearFix padding1emtop"> <hr /> </div>
<?php else: ?>
	<h2>No period selected, select one from the visiting history</h2>
<?php endif ?>
<h2>Visiting history</h2>
	<table class="table table-striped">
		<thead>
				<th>Date from</th>
				<th>Date to</th>
		</thead>
	<?php foreach ($volunteer->periods as $period): ?>
		<tr>	
			<td><a href="volunteerPage.php?id=<?php echo $volunteer->id."&periodID=".$period->id ?>"><?php echo date("d M Y",strtotime($period->dateFrom)) ?></a></td>	
			<td><a href="volunteerPage.php?id=<?php echo $volunteer->id."&periodID=".$period->id ?>"><?php echo date("d M Y",strtotime($period->dateTo)) ?></a></td>	
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif ?>
<?php include '../includes/footer.php';?>
