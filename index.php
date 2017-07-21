<?php
include_once('lock.php');

$configs = include('config.inc.php');
$userType = $_SESSION['userType'];
?>

<html>
<head>
    <title><?php print $configs->application_title . " " . $configs->application_version; ?> | Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="resources/css/main.css" type="text/css" media="screen"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<body>
<div id="wrapper">
  <?php include_once('menu.php'); ?>
    <h1>Dashboard</h1>
    <hr/>
    <div class="container-fluid"> <!-- If Needed Left and Right Padding in 'md' and 'lg' screen means use container class -->
      <div class="row">
          <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
              <p>Dashboard content</p>
          </div>
          <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
              <?php include_once('stats.php'); ?>
          </div>
      </div>
        </div>
</div>
</body>

</html>
