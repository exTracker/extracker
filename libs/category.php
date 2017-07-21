<?php

$dbconfigs = include('../config.db.php');

$db = mysqli_connect($dbconfigs->hostname, $dbconfigs->username, $dbconfigs->password, $dbconfigs->database);
// Check connection
if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}

// TODO - implementation

function getCategoriesForUser($userID)
{
    // TODO
}

/**
 * This function will insert categories set by the user to the database.
 *
 * @param $userID   userId of the current logged in user
 * @param $categoryName Newly added category
 * @return int
 */
function insertCategoryForUser($userID, $categoryName)
{

    $dbconfigs = include('../config.db.php');

    $db = mysqli_connect($dbconfigs->hostname, $dbconfigs->username, $dbconfigs->password, $dbconfigs->database);
    if (!$db) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $checkQuery = "SELECT category, status FROM category WHERE category = '" . $categoryName . "' AND userId = '" . $userID . "'";
    $result = mysqli_query($db, $checkQuery);

    $row = mysqli_fetch_array($result);
    $fcats = $row['category'];
    $fcstatus = $row['status'];

    $check_count = mysqli_num_rows($result);

    if ($check_count > 0 && $fcstatus == 'ACTIVE') {
        return 2;
    } else if ($check_count > 0 && $fcstatus == 'DELETED') {
        $updatecat = "UPDATE category SET status='ACTIVE' WHERE category='$fcats'";
        mysqli_query($db, $updatecat);
        return 1;
    } else if ($check_count == 0) {
        $insertCat = "INSERT INTO category VALUES (NULL, '" . $categoryName . "', " . $userID . ", 'ACTIVE')";
        mysqli_query($db, $insertCat);
        return 1;
    }

    mysqli_close($db);
}

function insertInitialCategoryList($userId)
{
    // TODO
}

?>
