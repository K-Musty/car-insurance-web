<?php

session_start(); // start the session

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    if (isset($_SESSION["user_type"]) && $_SESSION["user_type"] == "user") {
        header("location: user_menu.php");
        exit;
    }
}
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    if (isset($_SESSION["user_type"]) && $_SESSION["user_type"] == "staff") {
        header("location: staff_menu.php");
        exit;
    }
}
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    if (isset($_SESSION["user_type"]) && $_SESSION["user_type"] == "admin") {
        header("location: admin_menu.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home | Car Insurance</title>
    <link rel="stylesheet" href="script/main.css">
</head>
<body class="center">
    <div class="content content-under">
        <h2><u>Welcome to Car Insurance</u></h2>
        <p>A dedicated platform for getting car insurance.
        <br>Get your car insurance now!</p>
    </div>
    <div class="content content-under">
        <p>Already have an account ? Login now.</p>
        <button class="button"><a style="color: white;" href="login.php">Login</a></button>
        <p>Doesn't have an account? Register Now!</p>
        <button class="button"><a style="color: white;" href="register.php">Register</a></button>
    </div>
</body>
</html>