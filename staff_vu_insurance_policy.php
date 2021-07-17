<?php

session_start();

// check whether user already login
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    if(isset($_SESSION["user_type"]) && $_SESSION["user_type"] == "user") {
        header("location: user_menu.php");
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
$empId = $_SESSION["staff_id"];

$inputHTML = "";
$insID = $insName = $insDuration = $insCoverage = $updateDate = $insLimit = "";
// last update etc
$buttonUpdate = "";
$error_msg = "";
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
        $GETINSURANCE = "SELECT INSURANCE_ID, INSURANCE_NAME, INSURANCE_COVERAGE, INSURANCE_DURATION FROM insurance_policy";

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

if ($_SERVER["REQUEST_METHOD"] == "POST" &&  isset($_POST['getIP'])) {
    $host = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $dbName = "insurance";

    $IPid = $_POST["insurancepolicy"];

    if ($IPid !== "" || $IPid !== '' || !empty($IPid)) {
        $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);
        $inputHTML = "";
        if (mysqli_connect_error()) {
            die('Connection Error:( ' . mysqli_connect_errno() . ' ) ' . mysqli_connect_error());
        } else {
            // GET insurance_policy
            // GET updating
            $SELECTIP = "SELECT INSURANCE_NAME, INSURANCE_COVERAGE, INSURANCE_DURATION, INSURANCE_LIMIT FROM insurance_policy WHERE INSURANCE_ID = ?";

            //$SELECTUPD = "SELECT UPDATE_DATE, EMP_ID FROM updating WHERE INSURANCE_ID = ?";

            $stmt = $conn->prepare($SELECTIP);
            $stmt->bind_param("s", $IPid);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($IPName, $IPCoverage, $IPDuration, $IPLimit);
            $stmt->fetch();
            $stmt->close();

            $insID = $IPid;
            $insName = $IPName;
            $insCoverage = $IPCoverage;
            $insDuration = $IPDuration;
            $insLimit = $IPLimit;

            $buttonUpdate = "<input class='button' style='cursor: pointer;' name='updateIP' type='submit' value='UPDATE'>";
            $conn->close();
        }
    } else {
        $error_msg = "<p>Please select an Insurance Policy.</p>";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" &&  isset($_POST['updateIP'])) {
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

            // EMP_ID	INSURANCE_ID	UPDATE_DATE updating
            $INSERTUPDATING = "INSERT INTO updating(EMP_ID, INSURANCE_ID, UPDATE_DATE) VALUES(?, ?, NOW())";
            $stmt = $conn->prepare($INSERTUPDATING);
            $stmt->bind_param("ss", $empId, $insu_id);
            $stmt->execute();
            $stmt->close();

            $UPDATEUPD = "UPDATE updating SET UPDATE_DATE = NOW() WHERE EMP_ID = ? AND INSURANCE_ID = ?";
            $stmt = $conn->prepare($UPDATEUPD);
            $stmt->bind_param("ss", $empId, $insu_id);
            $stmt->execute();
            $stmt->close();

            $conn->close();
        }
    } else {
        $error_msg = "<p>Please don't let all the fields empty.</p>";
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
    <button class="button" name="backButton" value="Back"><a style="text-decoration: none;" href="staff_menu.php">Back</a></button>

    <h2><u>View/Update Insurance Policy</u></h2>

    <?php
    echo $error_msg;
    ?>
    <form id="staff-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <table>
            <tr>
                <td style="border: none;">Choose Insurance Policy: </td>
                <td>
                    <select name="insurancepolicy">
                        <?php
                        echo $inputHTML = getInsurancePolicy();
                        ?>
                    </select>
                </td>
                <td>
                    <input class="button" style="cursor: pointer;" name="getIP" type="submit" value="GET">
                </td>
            </tr>
        </table>
    </form>

    <form id="staff-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <table>
            <!-- INSURANCE_ID, INSURANCE_NAME, INSURANCE_COVERAGE, INSURANCE_DURATION -->
            <tr>
                <td>Insurance ID: </td>
                <td><input type="text" name="insID" value="<?php echo $insID; ?>" readonly> </td>
            </tr>
            <tr>
                <td>Insurance Name: </td>
                <td><input type="text" name="insName" value="<?php echo $insName; ?>" required> </td>
            </tr>
            <tr>
                <td>Insurance Coverage: </td>
                <td><input type="text" name="insCoverage" value="<?php echo $insCoverage; ?>" required> </td>
            </tr>
            <tr>
                <td>Insurance Limit: </td>
                <td><input type="text" name="insLimit" value="<?php echo $insLimit; ?>" required> </td>
            </tr>
            <tr>
                <td>Insurance Duration: </td>
                <td><input type="text" name="insDuration" value="<?php echo $insDuration; ?>" required>
                    Format: xxYxxMxxD, where x is a numeric </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <?php
                    echo $buttonUpdate;
                    ?>
                </td>
            </tr>
        </table>
    </form>
</body>

</html>