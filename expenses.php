<?php
include_once('lock.php');
include('plugins/KLogger.php');
include('plugins/html_table.class.php');

$configs = include('config.inc.php');
$dbconfigs = include('config.db.php');

$log = new KLogger ("/var/www/git/extracker/logs/", KLogger::DEBUG);

$db = mysqli_connect($dbconfigs->hostname, $dbconfigs->username, $dbconfigs->password, $dbconfigs->database);
$userType = $_SESSION['userType'];

$error_message = $_GET['error_message'];
$success_message = $_GET['success_message'];

// number of rows per page
$page_rows = 10;
?>

<html>
<head>
    <title><?php print $configs->application_title . " " . $configs->application_version; ?> | Expenses</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="resources/css/main.css" type="text/css" media="screen"/>
    <link rel="stylesheet" href="resources/css/bootstrap-datepicker3.min.css" type="text/css" media="screen"/>
    <link rel="stylesheet" href="resources/css/pagination.css" type="text/css" media="screen"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="resources/js/bootstrap-datepicker.min.js"></script>
    <style type="text/css">
        table.demoTbl {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            font-family: "lucida grande", tahoma, verdana, arial, sans-serif;
            font-size: 12px;
        }

        table.demoTbl .description {
            width: 50%;
        }

        table.demoTbl .category {
            width: 20%;
        }

        table.demoTbl .amount {
            width: 15%;
        }

        table.demoTbl .actions {
            width: 15%;
        }

        table.demoTbl td, table.demoTbl th {
            padding: 1px;
        }

        table.demoTbl th.first {
            text-align: center;
            padding: 3px;
            background-color: #CCCCCC;
            border: 2px solid #FFFFFF;
        }

        table.demoTbl td.description {
            text-align: left;
            padding-left: 10%;
        }

        table.demoTbl td.category {
            text-align: center;
        }

        table.demoTbl td.amount {
            text-align: right;
            padding-right: 20px;
        }

        table.demoTbl td.actions {
            text-align: center;
        }

        table.demoTbl td.foot {
            text-align: center;
        }

    </style>
    <script>
        $('.input-group.date').datepicker();
    </script>
</head>

<body>
<div id="wrapper">
    <?php include_once('menu.php'); ?>
    <h1>Expense Management</h1>
    <hr/>
    <div class="container-fluid">
        <!-- If Needed Left and Right Padding in 'md' and 'lg' screen means use container class -->
        <div class="row">
            <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                        <h4>Add Expense</h4>
                        <form id="loan_creation_form" action="handlers/expense_handler.php" method="post">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="date" name="date"
                                           placeholder="Date" required><span class="input-group-addon"><i
                                                class="glyphicon glyphicon-calendar"></i></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="text" name="amount" id="amount" class="form-control" placeholder="Amount"
                                       required>
                            </div>
                            <div class="form-group">
                                <input name="description" id="description" class="form-control"
                                       placeholder="Description" required>
                            </div>
                            <div class="form-group">
                                <select type="number" name="category" id="category" class="form-control"
                                        placeholder="Select Category" required>
                                    <?php
                                    $getcatsql = "SELECT id,category from category WHERE status='ACTIVE' AND userId='" . $_SESSION['userId'] . "'";
                                    $cat_result = mysqli_query($db, $getcatsql);

                                    $rowcount = mysqli_num_rows($cat_result);

                                    if ($rowcount >= 1) {
                                        while ($row = mysqli_fetch_array($cat_result)) {
                                            $id = $row['id'];
                                            $ctgry = $row['category'];

                                            echo "<option value='" . $id . "'>" . $ctgry . "</option>";
                                        }
                                    } else {
                                        echo "<option disabled>No Categories Available</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <input type="submit" id="addExpense" class="btn btn-block" value="Add Expense">
                        </form>
                        <?php
                        if (isset($error_message)) {
                            echo "<div class='alert alert-danger' id='error_bar' style='text-align: center'>";
                            echo "<strong> Error! </strong > $error_message";
                            echo "</div>";
                        } else if (isset($success_message)) {
                            echo "<div class='alert alert-success' id='error_bar' style='text-align: center'>";
                            echo "<strong> Done! </strong > $success_message";
                            echo "</div>";
                        }
                        ?>
                    </div>
                    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                        <p>Todays Expenses (charts)</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
                        <h4>Todays Expenses</h4>
                        <?php
                        // pagination
                        $pagenum = $_GET['pagenum'];

                        $log->logInfo("[" . $_SESSION['userId'] . "] retrieving expenses for date " . date("Y-m-d"));

                        // populate table
                        $getexpsql = "SELECT id,description,categoryId,amount from expenses WHERE date='" . date("Y-m-d") . "' AND userId='" . $_SESSION['userId'] . "'";
                        $exp_result = mysqli_query($db, $getexpsql);
                        $rowcount = mysqli_num_rows($exp_result);

                        if ($rowcount >= 1) {

                            // arguments: id, class, border,
                            // can include associative array of optional additional attributes
                            $tbl = new HTML_Table('', 'demoTbl', 0);

                            $tbl->addColgroup();
                            // span, class
                            $tbl->addCol(0, 'description');
                            $tbl->addCol(1, 'category');
                            $tbl->addCol(2, 'amount');
                            $tbl->addCol(3, 'actions');

                            // thead
                            $tbl->addTSection('thead');
                            $tbl->addRow();
                            // arguments: cell content, class, type (default is 'data' for td, pass 'header' for th)
                            // can include associative array of optional additional attributes
                            $tbl->addCell('Description', 'first', 'header');
                            $tbl->addCell('Category', 'first', 'header');
                            $tbl->addCell('Amount', 'first', 'header');
                            $tbl->addCell('Actions', 'first', 'header');

                            // tfoot
                            $tbl->addTSection('tfoot');
                            $tbl->addRow();
                            // span all 3 columns
                            $tbl->addCell('', 'foot', 'data', array('colspan' => 4));

                            // tbody
                            $tbl->addTSection('tbody');


                            if (!(isset($pagenum))) {
                                $pagenum = 1;
                            }

                            //This tells us the page number of our last page
                            $last = ceil($rowcount / $page_rows);

                            //this makes sure the page number isn't below one, or more than our maximum pages
                            if ($pagenum < 1) {
                                $pagenum = 1;
                            } elseif ($pagenum > $last) {
                                $pagenum = $last;
                            }

                            //This sets the range to display in our query
                            $max = 'limit ' . ($pagenum - 1) * $page_rows . ',' . $page_rows;

                            //This is your query again, the same one... the only difference is we add $max into it
                            $data_p = mysqli_query($db, $getexpsql . $max) or die(mysql_error());

                            while ($row = mysqli_fetch_array($data_p)) {
                                $desc = $row['description'];
                                $catid = getCategoryName($db, $row['categoryId']);
                                $amnt = number_format($row['amount'], 2);
                                $actions = "<button class=\"btn btn-info btn-sm\" title=\"edit category\"><span class=\"glyphicon glyphicon-pencil\"></span></button>&nbsp;<button class=\"btn btn-danger btn-sm\" title=\"delete category\"><span class=\"glyphicon glyphicon-trash\"></span></button>";


                                $tbl->addRow();
                                $tbl->addCell($desc, 'description');
                                $tbl->addCell($catid, 'category');
                                $tbl->addCell($amnt, 'amount');
                                $tbl->addCell($actions, 'actions');
                            }

                            echo $tbl->display();
                            echo "<br>";

                            $html = '';
                            $current_page = $pagenum;

                            if ($current_page != 1) {
                                $html .= '<a class="first" title="First" href="expenses.php?pagenum=1">&laquo;</a>';
                                $previousPageNumber = $current_page - 1;
                                $previousPage = "expenses.php?pagenum=" . $previousPageNumber;
                                $html .= '<a class="first" title="Previous" href=' . $previousPage . '>Previous</a>';
                            } else {
                                $html .= '<span class="disabled first" title="First">&laquo;</span>';
                                $html .= '<span class="disabled first" title="Previous">Previous</span>';
                            }
                            for ($i = 1; $i <= $last; $i++) {
                                if ($i != $current_page) {
                                    $pageNumber = "expenses.php?pagenum=" . $i;
                                    $html .= '<a title="' . $i . '" href=' . $pageNumber . '>' . $i . '</a>';
                                } else {
                                    $html .= '<span class="current">' . $i . '</span>';
                                }
                            }
                            if ($current_page != $last) {
                                $nextPageNumber = $current_page + 1;
                                $nextPage = "expenses.php?pagenum=" . $nextPageNumber;
                                $html .= '<a class="next" title="Next" href=' . $nextPage . '>Next</a>';
                                $lastPage = "expenses.php?pagenum=" . $last;
                                $html .= '<a class="last" title="Last" href=' . $lastPage . '>&raquo;</a>';
                            } else {
                                $html .= '<span class="disabled next" title="Next">Next</span>';
                                $html .= '<span class="disabled last" title="Last">&raquo;</span>';
                            }
                            echo '<div class="pagination">' . $html . '</div>';
                        } else {
                            print "<div class=\"alert alert-warning\">";
                            print "No Expenses added today? Please add one.";
                            print "</div>";
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                <?php include_once('stats.php'); ?>
            </div>
        </div>
    </div>
</div>
</body>

<?php
function getCategoryName($db, $catid)
{
    $getcat = "SELECT category FROM category WHERE id='$catid'";
    $result = mysqli_query($db, $getcat);
    $row = mysqli_fetch_array($result);
    $cat = $row["category"];
    return $cat;
}

?>
</html>
