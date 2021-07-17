<?php

// query user
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
$usernriclist = "";
$usercarlist = "";
$clientnric = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" &&  isset($_POST['getUserCarList'])) {
    $cnric = $_POST['usernric'];
    $clientnric = $cnric;
    $usercarlist = usercarlist();
}
function usercarlist()
{
    $host = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $dbName = "insurance";
    $cnric = $_POST['usernric'];

    $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);
    $usernriclist = "";
    if (mysqli_connect_error()) {
        die('Connection Error:( ' . mysqli_connect_errno() . ' ) ' . mysqli_connect_error());
    } else {
        $usercarlist = "";
        $GETCARLIST = "SELECT CAR_NO, CAR_CONDITIONS, CAR_MODEL, CAR_MANUFACTURED FROM car WHERE CLIENT_IC = $cnric";
        $result = $conn->query($GETCARLIST);

        $usercarlist = $usercarlist . "<option value=''>Select Car</option>";

        while ($row = $result->fetch_assoc()) {
            $usercarlist = $usercarlist . "<option value='" . $row['CAR_NO'] . "'>" . $row['CAR_NO'] . " - " . $row['CAR_MODEL'] . "</option>";
        }
        return $usercarlist;
        $result->close();
        $conn->close();
    }
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
$error_msg = "";
$buttonUpdate = $buttonDelete = "";
$cno = $ccondition = $cmodel = $cmanu = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" &&  isset($_POST['getCarDetails'])) {
    $cnric = $_POST['clientnric2'];
    $carno = $_POST['usercar'];
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
            $GETCARLIST = "SELECT CAR_CONDITIONS, CAR_MODEL, CAR_MANUFACTURED FROM car WHERE CLIENT_IC = ? AND CAR_NO = ?";
            $stmt = $conn->prepare($GETCARLIST);
            $stmt->bind_param("ss", $cnric, $carno);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($carcondition, $carmodel, $carmanufactured);
            $stmt->fetch();

            $clientnric = $cnric;

            $cno = $carno;
            $ccondition = $carcondition;
            $cmodel = $carmodel;
            $cmanu = $carmanufactured;

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
    $clientnric = $cnric;
    $cno = $_POST['carno'];
    $ccondition = $_POST['carcondition'];
    $cmodel = $_POST['carmodel'];
    $cmanu = $_POST['carmanu'];

    $buttonDeleteConfirm = "<input type='submit' name='deleteDataConfirm' value='CONFIRM DELETE'><input type='submit' name='cancel' value='CANCEL'>";
}
if ($_SERVER["REQUEST_METHOD"] == "POST" &&  isset($_POST['deleteDataConfirm'])) {
    $cnric = $_POST['clientnric'];
    $cno = $_POST['carno'];
    $host = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $dbName = "insurance";
    $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);
    if (mysqli_connect_error()) {
        die('Connection Error:( ' . mysqli_connect_errno() . ' ) ' . mysqli_connect_error());
    } else {
        // car
        $test = "";
        
        $DELCAR = "DELETE FROM car WHERE CLIENT_IC = '$cnric' AND CAR_NO = '$cno'";

        $test="";

        if($conn->query($DELCAR) === true) {
            $test = $test."DELETE FROM car SUCCESS. ";
        } else {
            $test = $test."ERROR DELETE FROM car . ".$conn->error;
        }

        $error_msg = "Successfully Delete Client Car Data ($cno). ($cnric).";

    }
    $conn->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" &&  isset($_POST['updateData'])) {
    $cnric = $_POST['clientnric'];
    $cno = $_POST['carno'];
    $ccondition = $_POST['carcondition'];
    $cmodel = $_POST['carmodel'];
    $cmanu = $_POST['carmanu'];
    $host = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $dbName = "insurance";
    if ($cnric !== "" || $cnric !== '' || !empty($cnric) || $cno !== "" || $cno !== '' || !empty($cno) || $ccondition !== "" || $ccondition !== '' || !empty($ccondition) || $cmodel !== "" || $cmodel !== '' || !empty($cmodel) || $cmanu !== "" || $cmanu !== '' || !empty($cmanu)) {
        $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);
        if (mysqli_connect_error()) {
            die('Connection Error:( ' . mysqli_connect_errno() . ' ) ' . mysqli_connect_error());
        } else {
            $UPDATE = "UPDATE car SET CAR_CONDITIONS = ?, CAR_MODEL = ?, CAR_MANUFACTURED = ? WHERE CLIENT_IC = ? AND CAR_NO = ?";
            $stmt = $conn->prepare($UPDATE);
            $stmt->bind_param("sssss", $ccondition, $cmodel, $cmanu, $cnric, $cno);
            $stmt->execute();
            $stmt->close();

            $error_msg = "<p>Successfully Updated The Car ($cno). ($cnric)</p>";

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
                    <input class="button" style="cursor: pointer;" name="getUserCarList" type="submit" value="GET">
                </td>
            </tr>
        </table>
    </form>
    <form id="staff-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <table>
            <tr>
                <td><input style="display: none;" type="text" name="clientnric2" id="nric" value="<?php echo $clientnric; ?>" placeholder="000000000000" readonly></td>
            </tr>
            <tr>
                <td>Choose Client Car: </td>
                <td>
                    <select name="usercar">
                        <?php
                        echo $usercarlist;
                        ?>
                    </select>
                </td>
                <td>
                    <input class="button" style="cursor: pointer;" name="getCarDetails" type="submit" value="GET">
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
                <td style="border: none;">Car Number: </td>
                <td><input type="text" name="carno" id="carno" value="<?php echo $cno; ?>" placeholder="Your car number" readonly></td>
            </tr>
            <tr>
                <td style="border: none;">Car Condition: </td>
                <td><input type="text" name="carcondition" id="carcondition" value="<?php echo $ccondition; ?>" placeholder="Your car number" required></td>
            </tr>
            <tr>
                <td style="border: none;">Car Model: </td>
                <td><input type="text" name="carmodel" id="carmodel" value="<?php echo $cmodel; ?>" placeholder="car model" required></td>
            </tr>
            <tr>
                <td style="border: none;">Car Manufactured: </td>
                <td><input type="date" name="carmanu" id="carmanu" value="<?php echo $cmanu; ?>" placeholder="mm-dd-yyyy" required></td>
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