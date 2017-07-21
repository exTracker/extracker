<?php
include_once('lock.php');

$configs = include('config.inc.php');
$userType = $_SESSION['userType'];
?>

<html>
<body>
    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="index.php"><?php print $configs->application_title . " " . $configs->application_version; ?></a>
            </div>
            <ul class="nav navbar-nav">
      <li class="active"><a href="index.php">Dashboard</a></li>
      <li><a href="expenses.php">Expenses</a></li>
      <li><a href="category.php">Categories</a></li>
      <li><a href="#">Reports</a></li>
    </ul>
    <ul class="nav navbar-nav navbar-right">
      <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><?php print $_SESSION['firstName'] . " " . $_SESSION['lastName'] . " [" . $userType . "]" ?>
        <span class="glyphicon glyphicon-user"></a>
        <ul class="dropdown-menu">
          <li><a href="#"><span class="glyphicon glyphicon-log-out">&nbsp;Settings</a></li>
          <li><a href="logout.php"><span class="glyphicon glyphicon-log-out">&nbsp;Logout</a></li>
        </ul>
      </li>
    </ul>
        </div>
    </nav>
</body>

</html>
