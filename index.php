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
    <title>Home | Car Insurance Limited Kano</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', Arial, sans-serif;
            background: linear-gradient(135deg, #2b67f6, #67d3f0);
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .container {
            background: #fff;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            max-width: 500px;
            text-align: center;
        }
        h2 {
            color: #2b67f6;
        }
        .custom-btn {
            background-color: #2b67f6;
            border: none;
            color: #fff;
            font-weight: bold;
            transition: background-color 0.3s, transform 0.3s;
        }
        .custom-btn:hover {
            background-color: #1a4bb8;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mb-3">Welcome to Car Insurance Limited Kano</h2>
        <p>A dedicated platform for getting car insurance.<br>Get your car insurance now!</p>
        
        <!-- Image Section -->
        <div class="my-4">
            <img src="car.png" alt="Welcome Image" height="50px" width="250px" class="img-fluid rounded">
        </div>
        
        <div class="mb-3">
            <a href="login.php" class="btn custom-btn btn-lg w-100 mb-2">Login</a>
        </div>
        <div>
            <p>Don’t have an account? Register now!</p>
            <a href="register.php" class="btn custom-btn btn-lg w-100">Register</a>
        </div>
    </div>

    <footer class="text-center text-white mt-4">
        &copy; 2024 Car Insurance Limited Kano. All rights reserved. | <a href="terms.html" class="text-warning text-decoration-none">Terms & Conditions</a>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
