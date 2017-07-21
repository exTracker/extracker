<?php

function insertExpense($date, $amount, $description, $category, $userId)
{
    $dbconfigs = include('../config.db.php');
    $configs = include('../config.inc.php');
    $log = new KLogger ($configs->log_path, KLogger::DEBUG);

    $db = mysqli_connect($dbconfigs->hostname, $dbconfigs->username, $dbconfigs->password, $dbconfigs->database);
    if (!$db) {
        $log->logDebug("database connection failure");
        die("Connection failed: " . mysqli_connect_error());
    }

    $insertExp = "INSERT INTO expenses VALUES (NULL, '$date', '$description', '$category', '$amount', '$userId')";
    mysqli_query($db, $insertExp);

    mysqli_close($db);
}