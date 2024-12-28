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

$error_msg = "";
$emp_id = $nric = $password = "";
$user_type = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $host = "localhost";
    $dbUsername = "root";
    $dbPassword = "Moussamj9$";
    $dbName = "insurance";

    $user_type = $_POST["user_type"]; // get the radiobutton value
    if (!empty($user_type)) {

        $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName); // make connection to db

        if (mysqli_connect_error()) {
            die('Connection Error:( ' . mysqli_connect_errno() . ')' . mysqli_connect_error());
        }

        if ($user_type == "user") {
            // user
            $nric = $_POST["nric"];
            $password = $_POST["password"];
            if (!empty($nric) || !empty($password)) {
                $CHECKUSER = "SELECT CLIENT_IC, CLIENT_PASSWORD FROM client WHERE CLIENT_IC = ?";

                $stmt = $conn->prepare($CHECKUSER);
                $stmt->bind_param("s", $nric);
                $stmt->execute();
                $stmt->store_result();
                $rnum = $stmt->num_rows;


                if ($rnum == 1) {
                    $stmt->bind_result($nric, $passwordDB);
                    $stmt->fetch();
                    $password = md5($password, true);

                    if ($password == $passwordDB) {

                        $_SESSION["loggedin"] = true;
                        $_SESSION["nric"] = $nric;
                        $_SESSION["user_type"] = "user";

                        header("location: user_menu.php");
                        exit;

                    } else {
                        $error_msg = "Wrong NRIC or Password Entered.";
                    }
                } else {
                    $error_msg = "Wrong NRIC or Password Entered.";
                }
                $stmt->close();
                $conn->close();
            } else {
                $error_msg = "Please enter all the fields.";
            }
        }
        if ($user_type == "staff") {
            // staff
            $staff_id = $_POST["staff_id"];
            $password = $_POST["password"];
            if (!empty($staff_id) || !empty($password)) {
                $CHECKSTAFF = "SELECT EMP_ID, EMP_PASSWORD FROM employee WHERE EMP_ID = ?";

                $stmt = $conn->prepare($CHECKSTAFF);
                $stmt->bind_param("s", $staff_id);
                $stmt->execute();
                $stmt->store_result();
                $rnum = $stmt->num_rows;


                if ($rnum == 1) {
                    $stmt->bind_result($staff_id, $passwordDB);
                    $stmt->fetch();
                    $password = md5($password, true);

                    if ($password == $passwordDB) {

                        $_SESSION["loggedin"] = true;
                        $_SESSION["staff_id"] = $staff_id;
                        $_SESSION["user_type"] = "staff";

                        header("location: staff_menu.php");
                        exit;

                    } else {
                        $error_msg = "Wrong Staff ID or Password Entered.";
                    }
                } else {
                    $error_msg = "Wrong Staff ID or Password Entered.";
                }
                $stmt->close();
                $conn->close();
            } else {
                $error_msg = "Please enter all the fields.";
            }
        }
        if ($user_type == "admin") {
            // admin
            $admin_id = $_POST["admin_id"];
            $password = $_POST["password"];
            if (!empty($admin_id) || !empty($password)) {
                $CHECKADMIN = "SELECT ADMIN_ID, ADMIN_PASSWORD FROM admin WHERE ADMIN_ID = ?";

                $stmt = $conn->prepare($CHECKADMIN);
                $stmt->bind_param("s", $admin_id);
                $stmt->execute();
                $stmt->store_result();
                $rnum = $stmt->num_rows;


                if ($rnum == 1) {
                    $stmt->bind_result($admin_id, $passwordDB);
                    $stmt->fetch();
                    $password = md5($password, true);

                    if ($password == $passwordDB) {

                        $_SESSION["loggedin"] = true;
                        $_SESSION["admin_id"] = $admin_id;
                        $_SESSION["user_type"] = "admin";

                        header("location: admin_menu.php");
                        exit;

                    } else {
                        $error_msg = "Wrong Admin ID or Password Entered.";
                    }
                } else {
                    $error_msg = "Wrong Admin ID or Password Entered.";
                }
                $stmt->close();
                $conn->close();
            } else {
                $error_msg = "Please enter all the fields.";
            }
        }

    } else {
        $error_msg = "Please choose login option.";
    }

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Car Insurance</title>
    <link rel="stylesheet" href="script/main.css">
    <style>
        body {
            background: linear-gradient(to bottom, #2b67f6, #67d3f0);
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background-color: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        h2 {
            color: #2b67f6;
            font-size: 1.5em;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        .button {
            background-color: #2b67f6;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }

        .button:hover {
            background-color: #67d3f0;
        }

        .login-btns {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
        }

        .login-btns button {
            background-color: #2b67f6;
            color: white;
            padding: 15px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
         
            font-size: 16px;
        }

        .login-btns button:hover {
            background-color: #67d3f0;
        }

        #error-form {
            color: red;
            font-size: 14px;
            margin-bottom: 15px;
        }

        a {
            color: #2b67f6;
        }
    </style>
    <script>
        function change(id) {
            document.getElementById("login-form").style.display = "block";
            switch (id) {
                case 1:
                    document.getElementById("title").innerHTML = "<u>User Login</u>";
                    document.getElementById("login").innerHTML = "NRIC: ";
                    document.getElementById("logininput").innerHTML = "<input type='text' name='nric' id='nric' placeholder='000000000000' required>";
                    document.getElementById("usertype").innerHTML = "<input type='hidden' name='user_type' value='user'>";
                    break;
                case 2:
                    document.getElementById("title").innerHTML = "<u>Staff Login</u>";
                    document.getElementById("login").innerHTML = "Staff ID: ";
                    document.getElementById("logininput").innerHTML = "<input type='text' name='staff_id' id='staff_id' placeholder='Staff ID' required>";
                    document.getElementById("usertype").innerHTML = "<input type='hidden' name='user_type' value='staff'>";
                    break;
                case 3:
                    document.getElementById("title").innerHTML = "<u>Admin Login</u>";
                    document.getElementById("login").innerHTML = "Admin ID: ";
                    document.getElementById("logininput").innerHTML = "<input type='text' name='admin_id' id='admin_id' placeholder='Admin ID' required>";
                    document.getElementById("usertype").innerHTML = "<input type='hidden' name='user_type' value='admin'>";
                    break;
                default:
                    break;
            }
        }
    </script>
</head>

<body>
    <div class="login-container">
        <h2 id="title"></h2>
        <div id="error-form"><?php echo $error_msg; ?></div>
        <form id="login-form" style="display: none;" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div id="login"></div>
            <div id="logininput"></div>
            <div id="usertype" style="display: none;"></div>
            <input type="password" name="password" id="password" placeholder="Password" required>
            <input class="button" type="submit" value="Login">
        </form>
        <br>
        <div class="login-btns">
            <button onmousedown="change(1)">User Login</button>
            <br>
            <br>
            <button onmousedown="change(2)">Staff Login</button>
            <button onmousedown="change(3)">Admin Login</button>
        </div>
        <p><a href="register.php">Don't have an account? Click here to register.</a></p>
    </div>
</body>

</html>
