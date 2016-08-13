<?php

	require_once('../php/internalConfig.php');

	if(isset($_GET["makeDB"]) && !$dbHandler->CheckDBAvailable($ini)){
		$dbHandler->CreateDB($ini);
		$dbHandler->CreateTables($ini);
	}
	else if(isset($_GET["makeDB"]) && !$dbHandler->CheckTablesSetup($ini)){
		$dbHandler->CreateTables($ini);
	}
?>

<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">

  <title>Database creation</title>
  <meta name="description" content="Database creation">
  <meta name="author" content="SitePoint">
	<link href="../css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/styles.css?v=1.0">

  <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
</head>

<body>
	<?php if($dbHandler->CheckTablesSetup($ini)){ ?>
		<div id="databaseCreationBox" class='alert alert-success'>
			<p>The database is ready, please click the button below to return home, or contact admin for further questions</p>
			<a <?php echo "href='../".$ini['homePage']."'" ?> class="btn btn-lg btn-info">Home</a>
		</div>
	<?php } else{ ?>
		<div id="databaseCreationBox" class='alert alert-danger'>
			<p>Database not found, if this is the first time running the application, please click the button below to automatically set up the database. If not, please contact admin for a backup of the database </p>
			<a href="?makeDB=true" class="btn btn-lg btn-info">Create Database</a>
		</div>
	<?php } ?>
</body>
</html>
