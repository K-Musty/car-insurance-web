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
$nric = $_SESSION["nric"];

$error_msg = "";
$phoneno = $address = $name = "";


$host = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "insurance";

$conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);
if (mysqli_connect_error()) {
    die('Connection Error:( ' . mysqli_connect_errno() . ' ) ' . mysqli_connect_error());
} else {
    $GETUSER = "SELECT CLIENT_NAME, CLIENT_PHONE_NO, CLIENT_ADDRESS FROM client WHERE CLIENT_IC = ?";

    $stmt = $conn->prepare($GETUSER);
    $stmt->bind_param("s", $nric);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($name, $phoneno, $address);
    $stmt->fetch();
    $stmt->close();
    $conn->close();
}


if ($_SERVER["REQUEST_METHOD"] == "POST" &&  isset($_POST['updateButton'])) {
    $phoneno = $_POST["phoneno"];
    $address = $_POST["address"];
    $oldpass = $_POST["oldpassword"];
    $newpass = $_POST["newpassword"];

    if (!empty($phoneno) || !empty($address) || !empty($oldpass) || !empty($newpass)) {
        $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);
        if (mysqli_connect_error()) {
            die('Connection Error:( ' . mysqli_connect_errno() . ' ) ' . mysqli_connect_error());
        } else {
            $CHECKPASS = "SELECT CLIENT_PASSWORD FROM client WHERE CLIENT_IC = ?";
            $stmt = $conn->prepare($CHECKPASS);
            $stmt->bind_param("s", $nric);
            $stmt->execute();
            $stmt->store_result();
            $rnum = $stmt->num_rows;

            if ($rnum == 1) {
                $stmt->bind_result($original_pass);
                $stmt->fetch();
                $oldpass = md5($oldpass, true);
                if($oldpass == $original_pass) {
                    $stmt->close();
                    $newpass = md5($newpass, true);
                    $UPDATE = "UPDATE client SET CLIENT_PHONE_NO = ?, CLIENT_ADDRESS = ?, CLIENT_PASSWORD = ? WHERE CLIENT_IC = ?";
                    $stmt = $conn->prepare($UPDATE);
                    $stmt->bind_param("ssss", $phoneno, $address, $newpass, $nric);
                    $stmt->execute();

                    $error_msg = "Successfully Updated Your Profile.";
                } else {
                    $error_msg = "Your old password is incorrect.";
                }
            }

            $stmt->close();
            $conn->close();
        }
    } else {
        $error_msg = "Please Enter All Fields.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h2><u>Update Profile</u></h2>
    <p>Note: You can only update your phone number, address, and password.</p>
    <p>
        <?php echo $error_msg; ?>
    </p>
    <form id="user-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">

        <table style="border: none;">
            <tr>
                <td style="border: none;">Name: </td>
                <td><input type="text" name="name" id="name" value="<?php echo $name; ?>" placeholder="FirstName LastName" readonly></td>
            </tr>
            <tr>
                <td style="border: none;">NRIC: </td>
                <td><input type="text" name="nric" id="nric" value="<?php echo $nric; ?>" placeholder="000000000000" readonly></td>
            </tr>
            <tr>
                <td style="border: none;">Phone Number: </td>
                <td><input type="text" name="phoneno" id="phoneno" value="<?php echo $phoneno; ?>" placeholder="01121196666" required></td>
            </tr>
            <tr>
                <td style="border: none;">Address: </td>
                <td><input type="text" name="address" id="adress" value="<?php echo $address; ?>" placeholder="Address" required></td>
            </tr>
            <tr>
                <td style="border: none;">Old Password: </td>
                <td><input type="password" name="oldpassword" id="oldpassword" placeholder="Old password" required></td>
            </tr>
            <tr>
                <td style="border: none;">New Password: </td>
                <td><input type="password" name="newpassword" id="newpassword" placeholder="New password" required></td>
            </tr>
            <td style="border: none;"></td>
            <td style="border: none;">
                <input class="button" style="cursor: pointer;" type="submit" name="updateButton" value="Update">
                <button class="button" name="backButton" value="Back"><a style="text-decoration: none;" href="user_menu.php">Back</a></button>
            </td>
        </table>
    </form>

</body>

</html>