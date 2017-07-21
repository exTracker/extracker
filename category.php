<?php
include_once('lock.php');
include('plugins/html_table.class.php');

$configs = include('config.inc.php');
$dbconfigs = include('config.db.php');

$db = mysqli_connect($dbconfigs->hostname, $dbconfigs->username, $dbconfigs->password, $dbconfigs->database);
$userType = $_SESSION['userType'];

$error_message = $_GET['error_message'];
$success_message = $_GET['success_message'];

// number of rows per page
$page_rows = 10;
?>

<html>
<head>
    <title><?php print $configs->application_title . " " . $configs->application_version; ?> | Categories</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="resources/css/main.css" type="text/css" media="screen"/>
    <link rel="stylesheet" href="resources/css/pagination.css" type="text/css" media="screen"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <style type="text/css">
        table.demoTbl {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            font-family: "lucida grande", tahoma, verdana, arial, sans-serif;
            font-size: 12px;
        }

        table.demoTbl .category {
            width: 75%;
        }

        table.demoTbl .actions {
            width: 25%;
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

        table.demoTbl td.cat {
            text-align: left;
            padding-left: 10%;
        }

        table.demoTbl td.actions {
            text-align: center;
        }

        table.demoTbl td.foot {
            text-align: center;
        }

    </style>
</head>

<body>
<div id="wrapper">
    <?php include_once('menu.php'); ?>
    <h1>Category Management</h1>
    <hr/>
    <div class="container-fluid">
        <!-- If Needed Left and Right Padding in 'md' and 'lg' screen means use container class -->
        <div class="row">
            <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                <div class="row">
                    <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                        <form id="loan_creation_form" action="handlers/category_handler.php" method="post">
                            <div class="form-group">
                                <input type="text" name="category" id="category" class="form-control"
                                       placeholder="Category Name" required>
                            </div>
                            <input type="submit" id="createCategory" class="btn btn-block" value="Add Category">
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
                    <div class="col-xs-7 col-sm-7 col-md-7 col-lg-7">
                        <?php
                        // pagination
                        $pagenum = $_GET['pagenum'];

                        // populate table
                        $getcatsql = "SELECT id,category from category WHERE status='ACTIVE' AND userId ='" . $_SESSION['userId'] . "'";
                        $cat_result = mysqli_query($db, $getcatsql);
                        $rowcount = mysqli_num_rows($cat_result);

                        if ($rowcount >= 1) {

                            // arguments: id, class, border,
                            // can include associative array of optional additional attributes
                            $tbl = new HTML_Table('', 'demoTbl', 0);

                            $tbl->addColgroup();
                            // span, class
                            $tbl->addCol(0, 'category');
                            $tbl->addCol(1, 'actions');

                            // thead
                            $tbl->addTSection('thead');
                            $tbl->addRow();
                            // arguments: cell content, class, type (default is 'data' for td, pass 'header' for th)
                            // can include associative array of optional additional attributes
                            $tbl->addCell('Category', 'first', 'header');
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
                            $data_p = mysqli_query($db, $getcatsql . $max) or die(mysql_error());

                            while ($row = mysqli_fetch_array($data_p)) {
                                $id = $row['id'];
                                $ctgry = $row['category'];
                                $actions = "<button class=\"btn btn-info btn-sm\" title=\"edit category\"><span class=\"glyphicon glyphicon-pencil\"></span></button>&nbsp;<button class=\"btn btn-danger btn-sm\" title=\"delete category\"><span class=\"glyphicon glyphicon-trash\"></span></button>";


                                $tbl->addRow();
                                $tbl->addCell($ctgry, 'cat');
                                $tbl->addCell($actions, 'actions');
                            }

                            echo $tbl->display();
                            echo "<br>";

                            $html = '';
                            $current_page = $pagenum;

                            if ($current_page != 1) {
                                $html .= '<a class="first" title="First" href="category.php?pagenum=1">&laquo;</a>';
                                $previousPageNumber = $current_page - 1;
                                $previousPage = "category.php?pagenum=" . $previousPageNumber;
                                $html .= '<a class="first" title="Previous" href=' . $previousPage . '>Previous</a>';
                            } else {
                                $html .= '<span class="disabled first" title="First">&laquo;</span>';
                                $html .= '<span class="disabled first" title="Previous">Previous</span>';
                            }
                            for ($i = 1; $i <= $last; $i++) {
                                if ($i != $current_page) {
                                    $pageNumber = "category.php?pagenum=" . $i;
                                    $html .= '<a title="' . $i . '" href=' . $pageNumber . '>' . $i . '</a>';
                                } else {
                                    $html .= '<span class="current">' . $i . '</span>';
                                }
                            }
                            if ($current_page != $last) {
                                $nextPageNumber = $current_page + 1;
                                $nextPage = "category.php?pagenum=" . $nextPageNumber;
                                $html .= '<a class="next" title="Next" href=' . $nextPage . '>Next</a>';
                                $lastPage = "category.php?pagenum=" . $last;
                                $html .= '<a class="last" title="Last" href=' . $lastPage . '>&raquo;</a>';
                            } else {
                                $html .= '<span class="disabled next" title="Next">Next</span>';
                                $html .= '<span class="disabled last" title="Last">&raquo;</span>';
                            }
                            echo '<div class="pagination">' . $html . '</div>';
                        } else {
                            print "<div class=\"alert alert-warning\">";
                            print "<strong>Oops!</strong> No Categories available. Please add one.";
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

</html>