<?php

include('../plugins/KLogger.php');

$configs = include('../config.inc.php');
$dbconfigs = include('../config.db.php');

$log = new KLogger ($configs->log_path, KLogger::INFO);

$db = mysqli_connect($dbconfigs->hostname, $dbconfigs->username, $dbconfigs->password, $dbconfigs->database);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // username and password sent from form
    $myemail = addslashes($_POST['email']);
    $mypassword = addslashes($_POST['password']);

    $log->logAlert("processing login for user [" . $myemail . "]");

    $encrypt_password = md5($mypassword);
    $encrypted_password = stripslashes($encrypt_password);

    $sql = "SELECT id,status FROM user_accounts WHERE email='$myemail' and password='$encrypted_password'";
    $result = mysqli_query($db, $sql);
    $row = mysqli_fetch_array($result);
    $status = $row['status'];
    $userId = $row['id'];

    $count = mysqli_num_rows($result);

    // If result matched $myemail and $mypassword, table row must be 1 row
    if ($count == 1) {
        if ($status == 'ACTIVE') {
            session_start("extracker");
            $_SESSION['login_email'] = $myemail;
            $log->logAlert("[" . $userId . "] user " . $myemail . " logged in successfully");
            header("location: ../index.php");

        } else if ($status == 'SUSPENDED') {
            $log->logAlert("[" . $userId . "] user " . $myemail . " Is in SUSPENDED state");
            $error_message = "The user is Suspended, Please contact the administrator";
            header("location: ../login.php?error_message=" . $error_message);

        }
    } else {
        $log->logAlert("login failed for user " . $myemail);
        $error_message = "Invalid Username or Password";
        header("location: ../login.php?error_message=" . $error_message);
    }
}
