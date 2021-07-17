<?php

session_start();

// check whether user already login
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    if(isset($_SESSION["user_type"]) && $_SESSION["user_type"] == "user") {
        header("location: user_menu.php");
        exit;
    }
    if(isset($_SESSION["user_type"]) && $_SESSION["user_type"] == "staff") {
        header("location: staff_menu.php");
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
    <title>Admin Menu | Car Insurance</title>

    <h1>Welcome</h1>
    <button><a style="text-decoration: none;" href="admin_manage_client.php">Manage Client</a></button>
    <button><a style="text-decoration: none;" href="admin_manage_car.php">Manage Car</a></button>
    <button><a style="text-decoration: none;" href="admin_manage_employee.php">Manage Employee</a></button>
    <button><a style="text-decoration: none;" href="admin_manage_insurance_policy.php">Manage Insurance Policy</a></button>
    <p><a href="logout.php">Click here to logout</a></p>
</head>
<body>
    
</body>
</html>