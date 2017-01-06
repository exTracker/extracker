<?php
include_once('lock.php');

$dbconfigs = include('config.db.php');
$configs = include('config.inc.php');

$userType = $_SESSION['userType'];
?>
<html>

<head>
    <title><?php print $pageTitle; ?></title>

    <!-- CSS -->
    <link rel="stylesheet" type="text/css"
          href="http://localhost/codeigniter/code/resources/css/style.css">
    <link rel="stylesheet" type="text/css"
          href="http://localhost/codeigniter/code/resources/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css"
          href="http://localhost/codeigniter/code/resources/vendor/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css"
          href="http://localhost/codeigniter/code/resources/vendor/datatables/css/dataTables.bootstrap.min.css">
</head>
<body>
<div id="wrapper">
    <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand"
               href="http://localhost/extracker"><b><?php print $configs->appname . " " . $configs->appversion; ?></b></a>
        </div>
        <!-- /.navbar-header -->
        <div class="details_top">
            <?php print $_SESSION['firstName'] . " " . $_SESSION['lastName'] . " [" . $userType . "]" ?>
        </div>
    </nav>
    <div class="sidebar">
        <div class="navbar-default" role="navigation">
            <div class="sidebar-nav navbar-collapse">
                <ul class="nav" id="side-menu">

                    <?php
                    if ($userType == 'PREMIUM' || $userType == 'FREE') {
                        echo "<li>";
                        echo "<a href='http://localhost/extracker'><i
                                class='fa fa-dashboard fa-fw'></i> Dashboard</a>";
                        echo "</li>";
                    }

                    ?>
                    <li>
                        <a href="http://localhost/extracker/expenses.php"><i class="fa fa-money fa-fw"></i>
                            Expenses<span class="fa arrow"></span></a>
                    </li>
                    <li>
                        <a href="http://localhost/extracker/category.php"><i class="fa fa-bars fa-fw"></i>
                            Categories<span class="fa arrow"></span></a>
                    </li>
                    <li>
                        <a href="http://localhost/extracker/reports.php"><i class="fa fa-pie-chart fa-fw"></i>
                            Reports<span class="fa arrow"></span></a>
                    </li>
                    <li>
                        <a href="http://localhost/extracker/settings.php"><i class="fa fa-wrench fa-fw"></i>
                            Settings<span class="fa arrow"></span></a>
                    </li>
                    <li>
                        <a href="http://localhost/extracker/logout.php"><i
                                    class="fa fa-sign-out fa-fw"></i>
                            Logout<span class="fa arrow"></span></a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="sidebar_copyrights">
            <em>nu1silva.com &copy; 2016</em>
        </div>
    </div>

    <div id="page-wrapper">
        <h3><?php print $pageHeading ?></h3>
        <hr>
        <?php echo $body; ?>
    </div>
</body>
<!-- SCRIPTS -->
<script type="text/javascript"
        src="http://localhost/codeigniter/code/resources/vendor/jquery/jquery.min.js"></script>
<script type="text/javascript"
        src="http://localhost/codeigniter/code/resources/vendor/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript"
        src="http://localhost/codeigniter/code/resources/vendor/datatables/js/jquery.datatables.min.js"></script>
<script type="text/javascript"
        src="http://localhost/codeigniter/code/resources/vendor/datatables/js/dataTables.bootstrap.min.js"></script>
<script>
    $(document).ready(function () {
        $('#table_category').DataTable();
    });
    $(document).ready(function () {
        $("#message-alert").fadeIn().delay(5000).fadeOut();
    }, 3000);
</script>
</html>
