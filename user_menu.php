<?php

session_start();

// check whether user already login
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    if(isset($_SESSION["user_type"]) && $_SESSION["user_type"] == "staff") {
        header("location: staff_menu.php");
        exit;
    }
    if(isset($_SESSION["user_type"]) && $_SESSION["user_type"] == "admin") {
        header("location: admin_menu.php");
        exit;
    }
}else {
    header("location: login.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Menu | Car Insurance</title>

    <h1>HOME</h1>
    <button><a style="text-decoration: none;" href="user_register_insurance.php">Register Insurance</a></button>
    <button><a style="text-decoration: none;" href="user_profile.php">Update Profile</a></button>
    <button><a style="text-decoration: none;" href="user_insurance_policy.php">Insurance Policy</a></button>
    <button><a style="text-decoration: none;" href="user_topay.php">To Pay</a></button>
    <p><a href="logout.php">Click here to logout</a></p>
</head>
<body>
    
</body>
</html>