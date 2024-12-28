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
    <link rel="stylesheet" href="script/main.css">
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', Arial, sans-serif;
            background: linear-gradient(135deg, #2b67f6, #67d3f0);
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            animation: fadeIn 1.5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .container {
            background: #fff;
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            text-align: center;
            max-width: 450px;
            width: 100%;
            animation: slideUp 1.2s ease-in-out;
        }

        @keyframes slideUp {
            from {
                transform: translateY(50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        h2 {
            font-size: 2rem;
            color: #2b67f6;
            margin-bottom: 1.5rem;
        }

        p {
            font-size: 1rem;
            margin-bottom: 1.5rem;
            color: #555;
            line-height: 1.6;
        }

        .button {
            display: inline-block;
            margin: 0.5rem 0;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            font-weight: bold;
            color: #fff;
            background-color: #2b67f6;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            text-transform: uppercase;
            text-decoration: none;
            transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
        }

        .button:hover {
            background-color: #1a4bb8;
            transform: scale(1.1);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        footer {
            position: absolute;
            bottom: 20px;
            text-align: center;
            font-size: 0.9rem;
            color: #fff;
        }

        footer a {
            color: #ffda44;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        footer a:hover {
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Welcome to Car Insurance Limited Kano</h2>
        <p>A dedicated platform for getting car insurance.<br>Get your car insurance now!</p>

        <p>Already have an account? Login now.</p>
        <a href="login.php" class="button">Login</a>

        <p>Don’t have an account? Register now!</p>
        <a href="register.php" class="button">Register</a>
    </div>

    <footer>
        &copy; 2024 Car Insurance Limited Kano. All rights reserved. | <a href="terms.html">Terms & Conditions</a>
    </footer>
</body>
</html>
