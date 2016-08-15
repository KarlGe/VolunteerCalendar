<?php  
include '../includes/header.php';

$date = $_GET["date"];

$volunteers = $dbHandler->GetVolunteersAndPeriodOnDate($date);

?>
<table class="table table-striped">
	<tr>
		<th>Name</th>
		<th>Date from</th>
		<th>Date to</th>
	</tr>
<?php foreach ($volunteers as $volunteer): ?>
	<tr data-href="volunteer.php?id=<?php echo $volunteer["volunteerID"] ?>">	
		<th><?php echo $volunteer["name"] ?></th>	
		<th><?php echo $volunteer["dateFrom"] ?></th>	
		<th><?php echo $volunteer["dateTo"] ?></th>	
	</tr>
<?php endforeach; ?>
</table>



<?php  
include '../includes/footer.php';
?>