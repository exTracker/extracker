<?php
include_once('lock.php');

$configs = include('config.inc.php');
$userType = $_SESSION['userType'];
?>

<html>
<head>
    <title><?php print $configs->application_title . " " . $configs->application_version; ?> | Roadmap</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="resources/css/main.css" type="text/css" media="screen"/>
</head>

<body>
<div id="wrapper">
  <?php include_once('menu.php'); ?>
    <h1>Development Roadmap</h1>
    <hr/>
    <p>This page describes the roadmap whcih will be taken in developing exTracker</p>
</div>
</body>

</html>
