<?php

// INSERT/ UPDATE / DELETE 

session_start();

// check whether user already login
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    if (isset($_SESSION["user_type"]) && $_SESSION["user_type"] == "user") {
        header("location: user_menu.php");
        exit;
    }
    if (isset($_SESSION["user_type"]) && $_SESSION["user_type"] == "staff") {
        header("location: staff_menu.php");
        exit;
    }
} else {
    header("location: login.php");
    exit;
}
$adminID = $_SESSION['admin_id'];

$error_msg = "";

$inputOption = ""; // insertForm   
// updateDeleteForm
$inputIPData = "";

$host = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "insurance";

if ($_SERVER["REQUEST_METHOD"] == "POST" &&  isset($_POST['insertForm'])) {
    $form = "";

    $form = $form . "<tr><td>Insurance ID: </td>";
    $form = $form . "<td><input type='text' name='insID' required> </td></tr>";
    $form = $form . "<tr><td>Insurance Name: </td>";
    $form = $form . "<td><input type='text' name='insName' required> </td></tr>";
    $form = $form . "<tr><td>Insurance Coverage: </td>";
    $form = $form . "<td><input type='text' name='insCoverage' required> </td></tr>";
    $form = $form . "<td>Insurance Limit: </td>";
    $form = $form . "<td><input type='text' name='insLimit' required> </td></tr>";
    $form = $form . "<td>Insurance Duration: </td>";
    $form = $form . "<td><input type='text' name='insDuration' required>Format: xxYxxMxxD, where x is a numeric </td></tr>";
    $form = $form . "<tr><td></td>";
    $form = $form . "<td><input type='submit' name='submitINSERT' value='INSERT New Insurance Policy'></td></tr>";

    $inputOption = $form;
}
if ($_SERVER["REQUEST_METHOD"] == "POST" &&  isset($_POST['submitINSERT'])) {
    $insu_id = $_POST["insID"];
    $insu_name = $_POST["insName"];
    $insu_Coverage = $_POST["insCoverage"];
    $insu_Duration = $_POST["insDuration"];
    $insu_limit = $_POST["insLimit"];

    if (is_numeric($insu_Coverage) || is_numeric($insu_limit)) {
        $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);
        if (mysqli_connect_error()) {
            die('Connection Error:( ' . mysqli_connect_errno() . ' ) ' . mysqli_connect_error());
        } else {
            $CHECK = "SELECT * FROM insurance_policy WHERE INSURANCE_ID = ?";
            $stmt = $conn->prepare($CHECK);
            $stmt->bind_param("s", $insu_id);
            $stmt->execute();
            $stmt->store_result();
            $rnum = $stmt->num_rows;
            $stmt->close();

            if ($rnum == 0) {
                // ip not exist, so can create new	
                $INSERT = "INSERT INTO insurance_policy(INSURANCE_ID, INSURANCE_NAME, INSURANCE_COVERAGE, INSURANCE_DURATION, INSURANCE_LIMIT, ADMIN_ID) VALUES(?, ?, ?, ?, ?, ?)";

                $stmt = $conn->prepare($INSERT);
                $stmt->bind_param("ssdsds", $insu_id, $insu_name, $insu_Coverage, $insu_Duration, $insu_limit, $adminID);
                $stmt->execute();
                $error_msg = "<p> Successfully INSERT new insurance policy; (INSURANCE_ID: $insu_id - INSURANCE_NAME: $insu_name) </p>";
                $stmt->close();
            } else {
                $error_msg = "<p> Please check again, there is already insurance policy with the same input insurance id. (INSURANCE_ID: $insu_id)</p>";
            }
        }
        $conn->close();
    } else {
        $error_msg = "The Limit/Coverage must be numeric.";
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST" &&  isset($_POST['updateDeleteForm'])) {
    // output insert Form for employee
    $iplist = "";
    $iplist = getInsurancePolicy();
    $form = "";
    $form = $form . "<tr><td>Insurance ID: </td>";
    $form = $form . "<td><select name='ipID'>$iplist</select></td><td><input type='submit' name='getIPData' value='GET Insurance Policy Data'></td></tr>";

    $inputOption = $form;
}

function getInsurancePolicy()
{
    $host = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $dbName = "insurance";

    $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);
    $inputHTML = "";
    if (mysqli_connect_error()) {
        die('Connection Error:( ' . mysqli_connect_errno() . ' ) ' . mysqli_connect_error());
    } else {
        $GETINSURANCE = "SELECT INSURANCE_ID, INSURANCE_NAME, INSURANCE_COVERAGE, INSURANCE_DURATION, INSURANCE_LIMIT FROM insurance_policy";

        $result = $conn->query($GETINSURANCE);

        $inputHTML = $inputHTML . "<option value=''>Select Policy</option>";

        while ($row = $result->fetch_assoc()) {
            $inputHTML = $inputHTML . "<option value='" . $row['INSURANCE_ID'] . "'>" . $row['INSURANCE_NAME'] . " - RM" . $row['INSURANCE_COVERAGE'] . "/year - Duration: " . $row['INSURANCE_DURATION'] . "</option>";
        }
        return $inputHTML;
        $result->close();
        $conn->close();
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST" &&  isset($_POST['getIPData'])) {
    $getIPId = $_POST['ipID'];
    if ($getIPId == '' || $getIPId == "" || empty($getIPId)) {
        $error_msg = "Please choose the insurance policy id";
    } else {
        $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);
        if (mysqli_connect_error()) {
            die('Connection Error:( ' . mysqli_connect_errno() . ' ) ' . mysqli_connect_error());
        } else {
            $SELECT = "SELECT INSURANCE_NAME, INSURANCE_COVERAGE, INSURANCE_DURATION, INSURANCE_LIMIT FROM insurance_policy WHERE INSURANCE_ID = ?";
            $stmt = $conn->prepare($SELECT);
            $stmt->bind_param("s", $getIPId);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($ipName, $ipCoverage, $ipDuration, $ipLimit);
            $stmt->fetch();

            $form = "";
            $form = $form . "<tr><td>Insurance ID: </td>";
            $form = $form . "<td><input type='text' name='insID' value='$getIPId' readonly> </td></tr>";
            $form = $form . "<tr><td>Insurance Name: </td>";
            $form = $form . "<td><input type='text' name='insName' value='$ipName' required> </td></tr>";
            $form = $form . "<tr><td>Insurance Coverage: </td>";
            $form = $form . "<td><input type='text' name='insCoverage' value='$ipCoverage' required> </td></tr>";
            $form = $form . "<td>Insurance Limit: </td>";
            $form = $form . "<td><input type='text' name='insLimit' value='$ipLimit' required> </td></tr>";
            $form = $form . "<td>Insurance Duration: </td>";
            $form = $form . "<td><input type='text' name='insDuration' value='$ipDuration' required></td><td>Format: xxYxxMxxD, where x is a numeric </td></tr>";
            $form = $form . "<tr><td></td>";
            $form = $form . "<td><input type='submit' name='submitUPDATE' value='UPDATE Insurance Policy Data'></td><td><input type='submit' name='submitDELETEbefore' value='DELETE Insurance Policy Data'></td></tr>";
            $inputIPData = $form;
            $stmt->close();
        }
        $conn->close();
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST" &&  isset($_POST['submitUPDATE'])) {
    $host = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $dbName = "insurance";

    $insu_id = $_POST["insID"];
    $insu_name = $_POST["insName"];
    $insu_Coverage = $_POST["insCoverage"];
    $insu_Duration = $_POST["insDuration"];
    $insu_limit = $_POST["insLimit"];

    if ($insu_name !== "" || $insu_name !== '' || !empty($insu_name) || $insu_Coverage !== "" || $insu_Coverage !== '' || !empty($insu_Coverage) || $insu_Duration !== "" || $insu_Duration !== '' || !empty($insu_Duration)) {
        $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);
        $inputHTML = "";
        if (mysqli_connect_error()) {
            die('Connection Error:( ' . mysqli_connect_errno() . ' ) ' . mysqli_connect_error());
        } else {
            // update
            $UPDATEIP = "UPDATE insurance_policy SET INSURANCE_NAME = ?, INSURANCE_COVERAGE = ?, INSURANCE_DURATION = ?, INSURANCE_LIMIT = ? WHERE INSURANCE_ID = ?";
            $stmt = $conn->prepare($UPDATEIP);
            $stmt->bind_param("sdsds", $insu_name, $insu_Coverage, $insu_Duration, $insu_limit, $insu_id);
            $stmt->execute();
            $stmt->close();

            $error_msg = "Update Successfull For Insurance Policy: $insu_id";

            $conn->close();
        }
    } else {
        $error_msg = "<p>Please don't let all the fields empty.</p>";
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST" &&  isset($_POST['submitDELETEbefore'])) {
    $insu_id = $_POST["insID"];
    $insu_name = $_POST["insName"];
    $insu_Coverage = $_POST["insCoverage"];
    $insu_Duration = $_POST["insDuration"];
    $insu_limit = $_POST["insLimit"];

    $form = "";
    $form = $form . "<tr><td>Insurance ID: </td>";
    $form = $form . "<td><input type='text' name='insID' value='$insu_id' readonly> </td></tr>";
    $form = $form . "<tr><td>Insurance Name: </td>";
    $form = $form . "<td><input type='text' name='insName' value='$insu_name' required> </td></tr>";
    $form = $form . "<tr><td>Insurance Coverage: </td>";
    $form = $form . "<td><input type='text' name='insCoverage' value='$insu_Coverage' required> </td></tr>";
    $form = $form . "<td>Insurance Limit: </td>";
    $form = $form . "<td><input type='text' name='insLimit' value='$insu_limit' required> </td></tr>";
    $form = $form . "<td>Insurance Duration: </td>";
    $form = $form . "<td><input type='text' name='insDuration' value='$insu_Duration' required></td><td>Format: xxYxxMxxD, where x is a numeric </td></tr>";
    $form = $form . "<tr><td></td>";
    $form = $form . "<td><input type='submit' name='submitDELETEafter' value='Confirm DELETE Employee Data'></td><td><input type='submit' name='cancel' value='CANCEL'></td></tr>";
    $inputIPData = $form;
}
if ($_SERVER["REQUEST_METHOD"] == "POST" &&  isset($_POST['submitDELETEafter'])) {
    $insu_id = $_POST["insID"];
    $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);
    if (mysqli_connect_error()) {
        die('Connection Error:( ' . mysqli_connect_errno() . ' ) ' . mysqli_connect_error());
    } else {

        $test = "";


        $DELPAYMENT = "DELETE FROM payment WHERE INSURANCE_ID = '$insu_id'";

        if($conn->query($DELPAYMENT) === true) {
            $test = $test."DELETE FROM payment SUCCESS. ";
        } else {
            $test = $test."ERROR DELETE FROM payment. ".$conn->error;
        }

        $DELCHOOSE = "DELETE FROM choose WHERE INSURANCE_ID = '$insu_id'";

        if($conn->query($DELCHOOSE) === true) {
            $test = $test."DELETE FROM CHOOSE SUCCESS. ";
        } else {
            $test = $test."ERROR DELETE FROM CHOOSE. ".$conn->error;
        }

        $DELUPDATING = "DELETE FROM updating WHERE INSURANCE_ID = '$insu_id'";

        if($conn->query($DELUPDATING) === true) {
            $test = $test."DELETE FROM updating SUCCESS. ";
        } else {
            $test = $test."ERROR DELETE FROM updating . ".$conn->error;
        }


        $DELEMP = "DELETE FROM insurance_policy WHERE INSURANCE_ID = ?";
        $stmt = $conn->prepare($DELEMP);
        $stmt->bind_param("s", $insu_id);
        $stmt->execute();
        $stmt->close();
        $error_msg = "Successfully Delete All Data Related to Insurance ID: $insu_id";
    }
    $conn->close();
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
    <h2><u>Manage Insurance Policy</u></h2>

    <?php
    echo $error_msg;
    ?>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <table>
            <tr>
                <td>
                    Choose Option:
                </td>
                <td>
                    <input class="button" style="cursor: pointer;" name="insertForm" type="submit" value="INSERT Insurance Policy">
                </td>
                <td>
                    <input class="button" style="cursor: pointer;" name="updateDeleteForm" type="submit" value="UPDATE/DELETE Insurance Policy">
                </td>
            </tr>
        </table>
    </form>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <table>
            <?php
            echo $inputOption;
            ?>

        </table>
    </form>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <table>
            <?php
            echo $inputIPData;
            ?>

        </table>
    </form>
</body>

</html>