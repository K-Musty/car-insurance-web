<?php

// register page for user

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
$nric = $name = $phoneno = $address = $password = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nric = $_POST['nric'];
    $name = $_POST['name'];
    $phoneno = $_POST['phoneno'];
    $address = $_POST['address'];
    $password = $_POST['password'];
    $emp_id = "emp2021";
    $admin_id = "admin2021";

    if (!empty($nric) || !empty($name) || !empty($phoneno) || !empty($address) || !empty($password)) {

        // connect to db
        $host = "localhost";
        $dbUsername = "root";
        $dbPassword = "";
        $dbName = "insurance";

        $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);

        if (mysqli_connect_error()) {
            die('Connection Error:( ' . mysqli_connect_errno() . ' ) ' . mysqli_connect_error());
        } else {
            $CHECKNRIC = "SELECT CLIENT_IC FROM client WHERE CLIENT_IC = ? LIMIT 1";
            $INSERT = "INSERT INTO client(CLIENT_IC, CLIENT_NAME, CLIENT_PHONE_NO, CLIENT_ADDRESS, CLIENT_PASSWORD, EMP_ID, ADMIN_ID) VALUES(?,?,?,?,?,?,?)";

            $stmt = $conn->prepare($CHECKNRIC);
            $stmt->bind_param("s", $nric);
            $stmt->execute();
            $stmt->bind_result($nric);
            $stmt->store_result();
            $rnum = $stmt->num_rows;

            // CLIENT_IC	CLIENT_NAME	CLIENT_PHONE_NO	CLIENT_ADDRESS	CLIENT_PASSWORD	EMP_ID	ADMIN_ID
            if ($rnum == 0) {
                $stmt->close();


                $pass_hash = md5($password, true);

                $stmt = $conn->prepare($INSERT);
                $stmt->bind_param("sssssss", $nric, $name, $phoneno, $address, $pass_hash, $emp_id, $admin_id);
                $stmt->execute();

                $error_msg = "<p style='color: white;'> Registration Success. <a href='login.php' style='color:blue;'>Please click here to login.</a></p>";
            } else {
                $error_msg = "Sorry, the NRIC entered has already been registered. <a href='login.php'>Please click here to login.</a>";
            }
            $stmt->close();
            $conn->close();
        }
    } else {
        $error_msg = "Please enter all the fields.";
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Car Insurance</title>

    <link rel="stylesheet" href="script/main.css">
    <script>
        function checkData() {

            var alphanumeric = /^[0-9a-zA-Z]+$/;
            var numbering = /^[0-9]+$/;
            // Check data
            var checkPassword = document.getElementById("password").value;
            if (!(checkPassword.match(alphanumeric))) {
                document.getElementById("result-form").innerHTML = "Password: Please enter alphanumeric character only.";
                document.getElementById("password").focus();
                return false;
            }
            var checkNRIC = document.getElementById("nric").value;
            if (!(checkNRIC.match(numbering))) {
                document.getElementById("result-form").innerHTML = "NRIC: Please enter numbers only.";
                document.getElementById("nric").focus();
                return false;
            }
            var checkPhoneno = document.getElementById("phoneno").value;
            if (!(checkPhoneno.match(numbering))) {
                document.getElementById("result-form").innerHTML = "Phone number: Please enter numbers only.";
                document.getElementById("phoneno").focus();
                return false;
            }

            if (checkNRIC.length > 12) {
                document.getElementById("result-form").innerHTML = "NRIC: nric can't be longer than 12 letters";
                document.getElementById("nric").focus();
                return false;
            }
            if (document.getElementById("name").value.length > 25) {
                document.getElementById("result-form").innerHTML = "Name: name can't be longer than 25 letters";
                document.getElementById("name").focus();
                return false;
            }
            if (checkPhoneno.length > 11) {
                document.getElementById("result-form").innerHTML = "Phone Number: phone number can't be longer than 11 letters";
                document.getElementById("phoneno").focus();
                return false;
            }
            if (document.getElementById("address").value.length > 50) {
                document.getElementById("result-form").innerHTML = "Address: address can't be longer than 50 letters";
                document.getElementById("address").focus();
                return false;
            }

        }
    </script>

</head>

<body class="center">

    <button><a href="index.php">Back</a></button>
    <h2><u>User Registeration Form</u></h2>

    <div id="result-form" style="color: red;"></div>
    <div id="error-form" style="color: red;">
        <?php
        echo $error_msg;
        ?>
    </div>
    <form id="user-form" onsubmit="return checkData()" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <table style="border: none;">
            <tr>
                <td style="color: white; border: none;">Name: </td>
                <td><input type="text" name="name" id="name" placeholder="FirstName LastName" required></td>
            </tr>
            <tr>
                <td style="color: white; border: none;">NRIC: </td>
                <td><input type="text" name="nric" id="nric" placeholder="000000000000" required></td>
            </tr>
            <tr>
                <td style="color: white; border: none;">Phone Number: </td>
                <td><input type="text" name="phoneno" id="phoneno" placeholder="01121196666" required></td>
            </tr>
            <tr>
                <td style="color: white; border: none;">Address: </td>
                <td><input type="text" name="address" id="adress" placeholder="Address" required></td>
            </tr>
            <tr>
                <td style="color: white; border: none;">Password: </td>
                <td><input type="password" name="password" id="password" placeholder="password" required></td>
            </tr>
            <td style="border: none;"></td>
            <td style="border: none;">
                <input class="button" type="submit" value="submit">
            </td>
        </table>
    </form>
    <p><a href="login.php">Already have an account? Click here to login.</p>
</body>

</html>