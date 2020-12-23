<?php

include '../includes/session.inc.php';
include '../includes/model.inc.php';

// USER AUTHENTICATION AFTER REGISTRATION

if (isset($_GET['_redirect'])) {
    $email = $_GET['_'];
    $password = $_GET['__'];

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $query = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
        $password_from_db =  $row['password'];
        $check_password = password_verify($password, $password_from_db);
        if ($check_password == true) {
            $_SESSION['user_logged_in'] = true;
            $_SESSION['user_id'] = $row['id'];
            break;
        } else {
            $_SESSION['user_logged_in'] = false;
            continue;
        }
    } header('location: ../index.php');  
}

// USER AUTHENTICATION AFTER LOGIN

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['pass'];

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $query = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
        $password_from_db =  $row['password'];
        $check_password = password_verify($password, $password_from_db);
        if ($check_password == true) {
            $_SESSION['user_logged_in'] = true;
            $_SESSION['user_id'] = $row['id'];
            break;
        } else {
            $_SESSION['user_logged_in'] = false;
            continue;
        }
    } header('location: ../index.php');
}

?>