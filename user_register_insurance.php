<?php

// car number
// condition (1-10) rate ?
// model
// manufacturer
// choose policy (multiple selection)

// first query insurance policy to the multiple selection
//
//$inputHTML = "";
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
$usernric = $_SESSION['nric'];
$admin_id = "admin2021";

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
            $inputHTML = $inputHTML . "<option value='" . $row['INSURANCE_ID'] . "'>" . $row['INSURANCE_NAME'] . " - RM" . $row['INSURANCE_COVERAGE'] . "/year - Duration: ".$row['INSURANCE_DURATION']."</option>";
        }
        return $inputHTML;
        $conn->close();
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST" &&  isset($_POST['submitData'])) {
    // sent a notice when succesfull submission, and give user a link to see the payment link
    // choose; CLIENT_IC, INSURANCE_ID, CHOOSE_DATE
    // payment; CLIENT_IC, INSURANCE_ID, PAYMENT_STATUS, PAYMENT_DUE, PAYMENT_AMOUNT
    // PAYMENT_STATUS = PENDING/PAID - monthly
    // car; CAR_NO,	CAR_CONDITIONS,	CAR_MODEL,	CAR_MANUFACTURED,	CLIENT_IC,	ADMIN_ID
    $error_msg = "";
    $carno = $_POST['carno'];
    $carcondition = $_POST['carcondition']; // good // very good
    $carmodel = $_POST['carmodel'];
    $carmanu = $_POST['carmanu'];
    $insurancepolicy = $_POST['insurancepolicy'];
    if (!empty($carno) || !empty($carcondition) || !empty($carmodel) || !empty($carmanu) || !empty($insurancepolicy) || $carcondition !== "" || $insurancepolicy !== "") {
        $host = "localhost";
        $dbUsername = "root";
        $dbPassword = "";
        $dbName = "insurance";
        $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);
        if (mysqli_connect_error()) {
            die('Connection Error:( ' . mysqli_connect_errno() . ' ) ' . mysqli_connect_error());
        } else {
            $today = date("Y-m-d");
            $estimateDUE = strftime("%Y-%m-%d", strtotime("$today +1 month"));
            $amount = 0.00;
            $INSERTCAR = "INSERT INTO car(CAR_NO, CAR_CONDITIONS, CAR_MODEL, CAR_MANUFACTURED, CLIENT_IC, ADMIN_ID) VALUES(?, ?, ?, ?, ?, ?)";
            $INSERTCHOOSE = "INSERT INTO choose(CLIENT_IC, INSURANCE_ID, CHOOSE_DATE) VALUES(?, ?, NOW())";
            $INSERTPAYMENT = "INSERT INTO payment(CLIENT_IC, INSURANCE_ID, PAYMENT_STATUS, PAYMENT_DUE, PAYMENT_AMOUNT) VALUES(?, ?, 'PENDING', ?, ?)";

            $CHECKCHOOSE = "SELECT * FROM choose WHERE CLIENT_IC = ? AND INSURANCE_ID = ?";
            
            $stmt = $conn->prepare($CHECKCHOOSE);
            $stmt->bind_param("ss", $usernric, $insurancepolicy);
            $stmt->execute();
            $stmt->store_result();
            $rnum = $stmt->num_rows;
            
            if($rnum == 1) {
                $error_msg = "You already have the insurance policy.";
                $stmt->close();
            } else {
                $stmt->close();

                $CHECKCAR = "SELECT * FROM car WHERE CLIENT_IC = ? AND CAR_NO = ?";

                $stmt = $conn->prepare($CHECKCAR);
                $stmt->bind_param("ss", $usernric, $carno);
                $stmt->execute();
                $stmt->store_result();
                $rnum2 = $stmt->num_rows;

                if($rnum2 == 0) {
                    $stmt->close();
                    // to make sure only one car of the same car no is entered.
                    $stmt = $conn->prepare($INSERTCAR);
                    $stmt->bind_param("ssssss", $carno, $carcondition, $carmodel, $carmanu, $usernric, $admin_id);
                    $stmt->execute();
                    
                    $stmt->close();
                }

                $stmt = $conn->prepare($INSERTCHOOSE);
                $stmt->bind_param("ss", $usernric, $insurancepolicy);
                $stmt->execute();
                
                $stmt->close();
                $stmt = $conn->prepare($INSERTPAYMENT);
                $stmt->bind_param("sssd", $usernric, $insurancepolicy, $estimateDUE, $amount);
                $stmt->execute();
                
                $stmt->close();
                $error_msg = "You have choose the insurance policy successfully.";
            }
        }
        $conn->close();
    } else {
        $error_msg = "Please enter all the fields";
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
    <h2><u>Choose Insurance Policy</u></h2>
    <p>Note: Payment Are Monthly.</p>
    <p>
    <?php echo $error_msg; ?>
    </p>
    <form id="user-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <table style="border: none;">
            <tr>
                <td style="border: none;">Car Number: </td>
                <td><input type="text" name="carno" id="carno" placeholder="Your car number" required></td>
            </tr>
            <tr>
                <td style="border: none;">Car Condition: </td>
                <td>
                    <select name="carcondition" id="carcondition">
                        <option value="">Select Condition</option>
                        <option value="good">Good</option>
                        <option value="very good">Very Good</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td style="border: none;">Car Model: </td>
                <td><input type="text" name="carmodel" id="carmodel" placeholder="car model" required></td>
            </tr>
            <tr>
                <td style="border: none;">Car Manufactured: </td>
                <td><input type="date" name="carmanu" id="carmanu" placeholder="mm-dd-yyyy" required></td>
            </tr>
            <tr>
                <td style="border: none;">Choose Insurance Policy: </td>
                <td>
                    <select name="insurancepolicy">
                        <?php
                        echo $inputHTML = getInsurancePolicy();
                        ?>
                    </select>
                </td>
            </tr>
            <td style="border: none;"></td>
            <td style="border: none;">
                <input class="button" style="cursor: pointer;" name="submitData" type="submit" value="submit">
                <button class="button" name="backButton" value="Back"><a style="text-decoration: none;" href="user_menu.php">Back</a></button>
            </td>
        </table>
    </form>
    <p><a href="user_insurance_policy.php">Click here If You want to know the list and about our Car Insurance Policy</a></p>

</body>

</html>