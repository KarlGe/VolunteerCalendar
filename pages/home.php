<?php  
include '../includes/header.php';
?>

<form id="submitPersonForm">
	<ul class="inlineList">
		<li>
			<label for="addPersonName">Name</label><br />
			<input type="text" id="addPersonName" placeholder="Name" name="volunteerName">
		</li>
		<li>
			<label for="addPersonDateFrom">From</label><br />
			<input type="date" id="addPersonDateFrom" placeholder="DD-MM-YYYY" name="dateFrom">
		</li>
		<span class="dateDash"> - </span>
		<li>
			<label for="addPersonDateTo">To</label><br />
			<input type="date" id="addPersonDateTo" placeholder="DD-MM-YYYY" name="dateTo">
		</li>
		<li>
			<input type="submit" value="Submit">
		</li>
	</ul>
</form>


<div id="calendarWrapper">
	<?php 
		include("../php/calendarPrinter.php"); 
	?>
</div>

<?php  
include '../includes/footer.php';
?>