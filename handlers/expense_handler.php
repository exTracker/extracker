<?php

include('../lock.php');
include('../libs/expenses.php');
include('../plugins/KLogger.php');

$configs = include('../config.inc.php');
$log = new KLogger ($configs->log_path, KLogger::DEBUG);

$log->logInfo("[" . $_SESSION['userId'] . "] adding expense for user");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $expDate = addslashes($_POST['date']);;
    $expAmount = addslashes($_POST['amount']);;
    $expDescription = addslashes($_POST['description']);;
    $expCategory = addslashes($_POST['category']);;

    $log->logDebug("[" . $_SESSION['userId'] . "] Expense details : {date : " . $expDate . ", amount : " . $expAmount . ", description : " . $expDescription . ", categoryId : " . $expCategory . "}");

    if ($expDate == "" || $expAmount == "" || $expDescription == "" || $expCategory == "") {
        $log->logInfo("[" . $_SESSION['userId'] . "] validation failed. some information missing sending error message");
        $error_message = "please enter all the details";
        header("Location: ../expenses.php?error_message=" . $expDate);
    } else {
        $log->logInfo("[" . $_SESSION['userId'] . "] validation success. adding record to database");
        insertExpense($expDate, $expAmount, $expDescription, $expCategory, $_SESSION['userId']);
        $success_message = "Expense added successfully";
        header("Location: ../expenses.php?success_message=" . $success_message);
    }

}