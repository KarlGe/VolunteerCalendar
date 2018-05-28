<?php

	require_once('../php/config.php');

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title><?php $ini['app_name'] ?></title>

    <!-- Bootstrap -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/jquery-ui.css" rel="stylesheet">
    <link href="../css/calendar.css" rel="stylesheet">
    <link href="../js/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet">
    <link href="../css/styles.css" rel="stylesheet">
    <link href="../css/media-queries.css" rel="stylesheet">
    

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>
  <header>
    <div id="headerContent">
      <H1><a href="home.php">Volunteer Calendar</a></H1> 
      <article id="volunteerSearch">
        <input id="search-hidden-mode" name="search" placeholder="Search for volunteers here" type="text" data-list=".hidden_mode_list" data-nodata="No results found" autocomplete="off">
        <hr style="display: none;"/>
        <ul style="display: none;" class="vertical hidden_mode_list">
          <?php 
            $volunteers = $dbHandler->GetAllVolunteers();
            foreach ($volunteers as $volunteer) :
          ?>
          <li style="display: none;"><a href="volunteerPage.php<?php echo "?id=".$volunteer->id;?>"><?php echo $volunteer->name; ?></a></li>
          <?php endforeach ?>
        </ul>
      </article>
    </div>
  </header>
  <main>