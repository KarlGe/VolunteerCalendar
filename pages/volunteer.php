<?php  
include '../includes/header.php';
if(isset($_GET['id'])):
	$volunteer = $dbHandler->GetVolunteer(intval($_GET['id']));
?>
<div id="volunteerName"><h1><?php echo $volunteer->name ?> </h1></div>

<?php endif ?>
<?php  
include '../includes/footer.php';
?>