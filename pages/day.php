<?php  
include '../includes/header.php';

$date = $_GET["date"];

$volunteers = $dbHandler->GetVolunteersWithPeriodOnDate($date);

?>

<table class="table table-striped">
	<tr>
		<th>Name</th>
		<th>Date from</th>
		<th>Date to</th>
	</tr>
<?php foreach ($volunteers as $volunteer): ?>
	<tr>	
		<td><a href="volunteerPage.php?id=<?php echo $volunteer["volunteerID"] ?>"><?php echo $volunteer["name"] ?></a></td>	
		<td><a href="volunteerPage.php?id=<?php echo $volunteer["volunteerID"] ?>"><?php echo date('d M Y', strtotime($volunteer["dateFrom"])) ?></a></td>	
		<td><a href="volunteerPage.php?id=<?php echo $volunteer["volunteerID"] ?>"><?php echo date('d M Y', strtotime($volunteer["dateFrom"])) ?></a></td>	
	</tr>
<?php endforeach; ?>
</table>



<?php  
include '../includes/footer.php';
?>