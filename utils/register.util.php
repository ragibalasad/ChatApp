<?php

include '../includes/session.inc.php';
include '../includes/model.inc.php';

if (isset($_POST['submit'])) {
    $username = $_POST['user'];
    $email = $_POST['email'];
    $password = $_POST['pass'];
    $hashed = password_hash($password, PASSWORD_BCRYPT);

    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed')";
    mysqli_query($conn, $sql);

    header('location: user_auth.util.php?_redirect=1&_='.$email.'&__='.$password.'&hack=false');
}

?>