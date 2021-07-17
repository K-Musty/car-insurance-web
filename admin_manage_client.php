<?php

// query user
$usernriclist = "";

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

function usernriclist()
{
    $host = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $dbName = "insurance";

    $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);
    $usernriclist = "";
    if (mysqli_connect_error()) {
        die('Connection Error:( ' . mysqli_connect_errno() . ' ) ' . mysqli_connect_error());
    } else {
        $GETUSERLIST = "SELECT CLIENT_IC, CLIENT_NAME FROM client";
        $result = $conn->query($GETUSERLIST);

        $usernriclist = $usernriclist . "<option value=''>Select User</option>";

        while ($row = $result->fetch_assoc()) {
            $usernriclist = $usernriclist . "<option value='" . $row['CLIENT_IC'] . "'>" . $row['CLIENT_IC'] . " - " . $row['CLIENT_NAME'] . "</option>";
        }
        return $usernriclist;
        $result->close();
        $conn->close();
    }
}
$clientnric = $clientname = $clientphoneno = $clientaddress = "";
$error_msg = "";
$buttonUpdate = $buttonDelete = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" &&  isset($_POST['getUserData'])) {
    $cnric = $_POST['usernric'];
    $host = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $dbName = "insurance";
    if ($cnric !== "" || $cnric !== '' || !empty($cnric)) {
        $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);

        if (mysqli_connect_error()) {
            die('Connection Error:( ' . mysqli_connect_errno() . ' ) ' . mysqli_connect_error());
        } else {
            // this
            $SELECT = "SELECT CLIENT_NAME, CLIENT_PHONE_NO, CLIENT_ADDRESS FROM client WHERE CLIENT_IC = ?";
            $stmt = $conn->prepare($SELECT);
            $stmt->bind_param("s", $cnric);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($getname, $getphoneno, $getaddress);
            $stmt->fetch();
            $clientnric = $cnric;
            $clientname = $getname;
            $clientphoneno = $getphoneno;
            $clientaddress = $getaddress;

            $buttonUpdate = "<input type='submit' name='updateData' value='UPDATE'>";
            $buttonDelete = "<input type='submit' name='deleteData' value='DELETE'>";

            $stmt->close();
            $conn->close();
        }
    } else {
        $error_msg = "<p>Please Choose The User</p>";
    }
}
$buttonDeleteConfirm = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" &&  isset($_POST['deleteData'])) {
    $cnric = $_POST['clientnric'];
    $cname = $_POST['clientname'];
    $cphoneno = $_POST['clientphoneno'];
    $caddress = $_POST['clientaddress'];
    $clientnric = $cnric;
    $clientname = $cname;
    $clientphoneno = $cphoneno;
    $clientaddress = $caddress;
    $buttonDeleteConfirm = "<input type='submit' name='deleteDataConfirm' value='CONFIRM DELETE'><input type='submit' name='cancel' value='CANCEL'>";
}
if ($_SERVER["REQUEST_METHOD"] == "POST" &&  isset($_POST['deleteDataConfirm'])) {
    $cnric = $_POST['clientnric'];
    $host = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $dbName = "insurance";
    $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);
    if (mysqli_connect_error()) {
        die('Connection Error:( ' . mysqli_connect_errno() . ' ) ' . mysqli_connect_error());
    } else {
        // car
        $DELCAR = "DELETE FROM car WHERE CLIENT_IC = '$cnric'";
        $test="";
        if($conn->query($DELCAR) === true) {
            $test = $test."DELETE FROM car SUCCESS. ";
        } else {
            $test = $test."ERROR DELETE FROM car . ".$conn->error;
        }
        $DELCHOOSE = "DELETE FROM choose WHERE CLIENT_IC = '$cnric'";
        if($conn->query($DELCHOOSE) === true) {
            $test = $test."DELETE FROM choose SUCCESS. ";
        } else {
            $test = $test."ERROR DELETE FROM choose . ".$conn->error;
        }
        $DELPAY = "DELETE FROM payment WHERE CLIENT_IC = '$cnric'";
        if($conn->query($DELPAY) === true) {
            $test = $test."DELETE FROM payment SUCCESS. ";
        } else {
            $test = $test."ERROR DELETE FROM payment . ".$conn->error;
        }
        $DELETE = "DELETE FROM client WHERE CLIENT_IC = '$cnric'";
        if($conn->query($DELETE) === true) {
            $test = $test."DELETE FROM client SUCCESS. ";
        } else {
            $test = $test."ERROR DELETE FROM client . ".$conn->error;
        }

        $error_msg = "Successfully Delete All of Client Data. ($cnric).";

        $conn->close();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" &&  isset($_POST['updateData'])) {
    $cnric = $_POST['clientnric'];
    $cname = $_POST['clientname'];
    $cphoneno = $_POST['clientphoneno'];
    $caddress = $_POST['clientaddress'];
    $host = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $dbName = "insurance";
    if ($cnric !== "" || $cnric !== '' || !empty($cnric) || $cname !== "" || $cname !== '' || !empty($cname) || $cphoneno !== "" || $cphoneno !== '' || !empty($cphoneno) || $caddress !== "" || $caddress !== '' || !empty($caddress)) {
        $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);
        if (mysqli_connect_error()) {
            die('Connection Error:( ' . mysqli_connect_errno() . ' ) ' . mysqli_connect_error());
        } else {
            $UPDATE = "UPDATE client SET CLIENT_NAME = ?, CLIENT_PHONE_NO = ?, CLIENT_ADDRESS = ? WHERE CLIENT_IC = ?";
            $stmt = $conn->prepare($UPDATE);
            $stmt->bind_param("ssss", $cname, $cphoneno, $caddress, $cnric);
            $stmt->execute();
            $stmt->close();

            $error_msg = "<p>Successfully Updated The User.</p>";

            $conn->close();
        }
    } else {
        $error_msg = "<p>Please don't put any blank input. </p>";
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
    <button class="button" name="backButton" value="Back"><a style="text-decoration: none;" href="admin_menu.php">Back</a></button>
    <h2><u>Manage Client</u></h2>

    <?php
    echo $error_msg;
    ?>

    <form id="staff-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <table>
            <tr>
                <td>Choose User NRIC: </td>
                <td>
                    <select name="usernric">
                        <?php
                        echo $usernriclist = usernriclist();
                        ?>
                    </select>
                </td>
                <td>
                    <input class="button" style="cursor: pointer;" name="getUserData" type="submit" value="GET">
                </td>
            </tr>
        </table>
    </form>


    <form id="staff-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <table>
            <tr>
                <td>Client NRIC: </td>
                <td><input type="text" name="clientnric" id="nric" value="<?php echo $clientnric; ?>" placeholder="000000000000" readonly></td>
            </tr>
            <tr>
                <td>Client Name: </td>
                <td><input type="text" name="clientname" id="name" value="<?php echo $clientname; ?>" placeholder="FirstName LastName" required></td>
            </tr>
            <tr>
                <td>Client Phone Number: </td>
                <td><input type="text" name="clientphoneno" id="phoneno" value="<?php echo $clientphoneno; ?>" placeholder="01121196666" required></td>
            </tr>
            <tr>
                <td>Client Address: </td>
                <td><input type="text" name="clientaddress" id="adress" value="<?php echo $clientaddress; ?>" placeholder="Address" required></td>
            </tr>
            <tr>
                <td></td>
                <td><?php echo $buttonUpdate; ?> <?php echo $buttonDelete; ?></td>
            </tr>
            <tr>
            <td></td>
            <td><?php echo $buttonDeleteConfirm; ?></td>
            </tr>
        </table>
    </form>


</body>

</html>