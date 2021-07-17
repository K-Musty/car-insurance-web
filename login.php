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

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $host = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $dbName = "insurance";

    $user_type = $_POST["user_type"]; // get the radiobutton value
    if(!empty($user_type)) {

        
        $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName); // make connection to db

        if(mysqli_connect_error()) {
            die('Connection Error:( '.mysqli_connect_errno().')'.mysqli_connect_error());
        }

        if($user_type == "user") {
            // user
            $nric = $_POST["nric"];
            $password = $_POST["password"];
            if(!empty($nric) || !empty($password)) {
                $CHECKUSER = "SELECT CLIENT_IC, CLIENT_PASSWORD FROM client WHERE CLIENT_IC = ?";
                
                $stmt = $conn->prepare($CHECKUSER);
                $stmt->bind_param("s", $nric);
                $stmt->execute();
                $stmt->store_result();
                $rnum = $stmt->num_rows;
                

                if($rnum == 1) {
                    $stmt->bind_result($nric, $passwordDB);
                    $stmt->fetch();
                    $password = md5($password, true); 

                    if($password == $passwordDB) {

                        //session_start();
                        $_SESSION["loggedin"] = true;
                        $_SESSION["nric"] = $nric;
                        $_SESSION["user_type"] = "user";

                        header("location: user_menu.php");
                        exit;

                    }else {
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
        if($user_type == "staff") {
            // staff
            $staff_id = $_POST["staff_id"];
            $password = $_POST["password"];
            if(!empty($staff_id) || !empty($password)) {
                $CHECKSTAFF = "SELECT EMP_ID, EMP_PASSWORD FROM employee WHERE EMP_ID = ?";
                
                $stmt = $conn->prepare($CHECKSTAFF);
                $stmt->bind_param("s", $staff_id);
                $stmt->execute();
                $stmt->store_result();
                $rnum = $stmt->num_rows;
                

                if($rnum == 1) {
                    $stmt->bind_result($staff_id, $passwordDB);
                    $stmt->fetch();
                    $password = md5($password, true); 

                    if($password == $passwordDB) {

                        //session_start();
                        $_SESSION["loggedin"] = true;
                        $_SESSION["staff_id"] = $staff_id;
                        $_SESSION["user_type"] = "staff";

                        header("location: staff_menu.php");
                        exit;

                    }else {
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
        if($user_type == "admin") {
            // admin
            $admin_id = $_POST["admin_id"];
            $password = $_POST["password"];
            if(!empty($admin_id) || !empty($password)) {
                $CHECKADMIN = "SELECT ADMIN_ID, ADMIN_PASSWORD FROM admin WHERE ADMIN_ID = ?";
                
                $stmt = $conn->prepare($CHECKADMIN);
                $stmt->bind_param("s", $admin_id);
                $stmt->execute();
                $stmt->store_result();
                $rnum = $stmt->num_rows;
                

                if($rnum == 1) {
                    $stmt->bind_result($admin_id, $passwordDB);
                    $stmt->fetch();
                    $password = md5($password, true); 

                    if($password == $passwordDB) {

                        //session_start();
                        $_SESSION["loggedin"] = true;
                        $_SESSION["admin_id"] = $admin_id;
                        $_SESSION["user_type"] = "admin";

                        header("location: admin_menu.php");
                        exit;

                    }else {
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
    <script>

        function change(id) {
            document.getElementById("login-form").style.display = "block";

            switch (id) {
                case 1:
                    document.getElementById("title").innerHTML = "<u>User Login</u>";
                    document.getElementById("login").innerHTML = "NRIC: ";
                    document.getElementById("logininput").innerHTML = "<input type='text' name='nric' id='nric' placeholder='000000000000' required>";
                    document.getElementById("usertype").innerHTML = "<td><input type='text' name='user_type' id='user_type' value='user'></td>";
                    break;

                case 2:
                    document.getElementById("title").innerHTML = "<u>Staff Login</u>";
                    document.getElementById("login").innerHTML = "Staff ID: ";
                    document.getElementById("logininput").innerHTML = "<input type='text' name='staff_id' id='staff_id' placeholder='staff ID' required>";
                    document.getElementById("usertype").innerHTML = "<td><input type='text' name='user_type' id='user_type' value='staff'></td>";
                    break;

                case 3:
                    document.getElementById("title").innerHTML = "<u>Admin Login</u>";
                    document.getElementById("login").innerHTML = "Admin ID: ";
                    document.getElementById("logininput").innerHTML = "<input type='text' name='admin_id' id='admin_id' placeholder='staff ID' required>";
                    document.getElementById("usertype").innerHTML = "<td><input type='text' name='user_type' id='user_type' value='admin'></td>";
                    break;
                default:
                    break;
            }
        }


        function fetchgo() {

            var numbering = /^[0-9]+$/;
            var alphanumeric = /^[0-9a-zA-Z]+$/;
            // Check data
            var checkNRIC = document.getElementById("nric").value;
            if (!(checkNRIC.match(numbering))) {
                document.getElementById("result-form").innerHTML = "NRIC: Please enter numbers only.";
                document.getElementById("nric").focus();
                return;
            }
            var checkPassword = document.getElementById("password").value;
            if (!(checkPassword.match(alphanumeric))) {
                document.getElementById("result-form").innerHTML = "Password: Please enter alphanumeric character only.";
                document.getElementById("password").focus();
                return;
            }

        }
    </script>

</head>

<body class="center">
    <!-- onload="change(1)"-->

    <button><a href="index.php">Back</a></button>
    <p>Please select your login type:</p>
    <button id="user.form" style="cursor: pointer;" onmousedown="change(1)">User Login</button>
    <button id="staff.form" style="cursor: pointer;" onmousedown="change(2)">Staff Login</button>
    <button id="admin.form" style="cursor: pointer;" onmousedown="change(3)">Admin Login</button>

    <div id="result-form" style="color: red;"></div>
    <div id="error-form" style="color: red;">
        <?php
    echo $error_msg;
    ?>
    </div>


    <h2 id="title"></h2>
    <form id="login-form" style="display: none;" onsubmit="return fetchgo()" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <table style="border: none;">
            <tr>
                <td id="login" style="color: white; border: none;"></td>
                <td id="logininput"></td>
            </tr>
            <tr>
                <td id="password.id" style="color: white; border: none;">Password: </td>
                <td id="password.input"><input type="password" name="password" id="password" placeholder="password"
                        required></td>
            </tr>
            <tr id="usertype" style="display: none;">
            </tr>
                <td style="border: none;"></td>
                <td style="border: none;">
                    <input class="button" type="submit" value="submit" onmousedown="return fetchgo()">
                </td>
        </table>
    </form>
    <p><a href="register.php">Doesn't have an account? Click here to register.</p>

</body>

</html>