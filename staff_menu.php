<?php

session_start();

// check whether user already login
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    if(isset($_SESSION["user_type"]) && $_SESSION["user_type"] == "user") {
        header("location: user_menu.php");
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
    <title>Staff Menu | Car Insurance</title>

    <h1>Welcome</h1>
    <button><a style="text-decoration: none;" href="staff_profile.php">View Profile</a></button>
    <button><a style="text-decoration: none;" href="staff_vu_client.php">View/Update Client</a></button>
    <button><a style="text-decoration: none;" href="staff_vu_insurance_policy.php">View/Update Insurance Policy</a></button>
    <p><a href="logout.php">Click here to logout</a></p>
</head>
<body>
    
</body>
</html>