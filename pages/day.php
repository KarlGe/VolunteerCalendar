<?php  
include '../includes/header.php';

$date = $_GET["date"];

$volunteerPeriods = $dbHandler->GetVolunteersWithPeriodOnDate($date);

?>
<h2>Volunteers</h2>
<table class="table table-striped">
	<tr>
		<th>Name</th>
		<th>Date from</th>
		<th>Date to</th>
	</tr>
<?php foreach ($volunteerPeriods as $volunteerPeriod): ?>
	<tr>	
		<td><a href="volunteerPage.php<?php echo "?id=".$volunteerPeriod["volunteerID"]."&periodID=".$volunteerPeriod["ID"] ?>"><?php echo $volunteerPeriod["name"] ?></a></td>	
		<td><a href="volunteerPage.php<?php echo "?id=".$volunteerPeriod["volunteerID"]."&periodID=".$volunteerPeriod["ID"] ?>"><?php echo date('d M Y', strtotime($volunteerPeriod["dateFrom"])) ?></a></td>	
		<td><a href="volunteerPage.php<?php echo "?id=".$volunteerPeriod["volunteerID"]."&periodID=".$volunteerPeriod["ID"] ?>"><?php echo date('d M Y', strtotime($volunteerPeriod["dateFrom"])) ?></a></td>	
	</tr>
<?php endforeach; ?>
</table>
<h2>Transactions</h2>
<table class="table table-striped">
	<tr>
		<th>Name</th>
		<th>Date from</th>
		<th>Date to</th>
	</tr>
<?php foreach ($volunteerPeriods as $volunteerPeriod): ?>
	<tr>	
		<td><a href="volunteerPage.php<?php echo "?id=".$volunteerPeriod["volunteerID"]."&periodID=".$volunteerPeriod["ID"] ?>"><?php echo $volunteerPeriod["name"] ?></a></td>	
		<td><a href="volunteerPage.php<?php echo "?id=".$volunteerPeriod["volunteerID"]."&periodID=".$volunteerPeriod["ID"] ?>"><?php echo date('d M Y', strtotime($volunteerPeriod["dateFrom"])) ?></a></td>	
		<td><a href="volunteerPage.php<?php echo "?id=".$volunteerPeriod["volunteerID"]."&periodID=".$volunteerPeriod["ID"] ?>"><?php echo date('d M Y', strtotime($volunteerPeriod["dateFrom"])) ?></a></td>	
	</tr>
<?php endforeach; ?>
</table>



<?php  
include '../includes/footer.php';
?>