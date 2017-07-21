<?php

include('../lock.php');
include('../libs/category.php');


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $categoryName = addslashes($_POST['category']);

    if (!isset($categoryName) || trim($categoryName) == '') {
        $error_message = "Category name should not be blank!";
        header("location: ../category.php?error_message=" . $error_message);
    } else {
        $status = insertCategoryForUser($_SESSION['userId'], $categoryName);

        if ($status == 1) {
            $success_message = "Category added successfully!";
            header("location: ../category.php?success_message=" . $success_message);
        } else if ($status == 2) {
            $error_message = "Category already exists!";
            header("location: ../category.php?error_message=" . $error_message);
        } else {
            $error_message = "Error While adding category";
            header("location: ../category.php?error_message=" . $error_message);
        }
    }
}


?>
