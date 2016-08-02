<?php

	$ini = parse_ini_file('../app.ini');
	// Connect to MySQL
	$link = mysql_connect($ini['host'], $ini['db_username'], $ini['db_password']);
	// Make my_db the current database
	$db_selected = mysql_select_db($ini['db_name'], $link);

	if(!$db_selected && isset($_GET["makeDB"])){
		$sql = 'CREATE DATABASE '.$ini['db_name'];
		mysql_query($sql, $link);
		$db_selected = mysql_select_db($ini['db_name'], $link);
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
	<?php if($db_selected){ ?>
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
