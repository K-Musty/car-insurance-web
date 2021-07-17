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

$inputHTML = "";

function getUserIP()
{
    $usernric = $_SESSION['nric'];
    $host = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $dbName = "insurance";

    $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);
    $inputHTML = "";
    if (mysqli_connect_error()) {
        die('Connection Error:( ' . mysqli_connect_errno() . ' ) ' . mysqli_connect_error());
    } else {
        // 	INSURANCE_ID, INSURANCE_NAME, INSURANCE_LIMIT, INSURANCE_DURATION, INSURANCE_COVERAGE
        $Q_IP = "SELECT INSURANCE_ID, INSURANCE_NAME, INSURANCE_LIMIT, INSURANCE_DURATION, INSURANCE_COVERAGE From insurance_policy";
        $inputHTML = $inputHTML . "<option value=''>Select Policy</option>";

        $result = $conn->query($Q_IP);
        while ($row = $result->fetch_assoc()) {
            $html = "";
            // check
            $C_UIP = "SELECT * FROM choose WHERE CLIENT_IC = ? AND INSURANCE_ID = ?";
            $stmt = $conn->prepare($C_UIP);
            $insuranceID = $row["INSURANCE_ID"];
            $stmt->bind_param("ss", $usernric, $insuranceID);
            $stmt->execute();
            $stmt->store_result();
            $rnum = $stmt->num_rows;/*
            $IP_name = "Insurance Policy Name: " . $row["INSURANCE_NAME"];
            $IP_limit = "Insurance Policy Limit: RM" . $row["INSURANCE_LIMIT"];
            $IP_duration = "Insurance Policy Duration: " . $row["INSURANCE_DURATION"];
            $IP_coverage = "Insurance Policy Coverage: RM" . $row["INSURANCE_COVERAGE"];*/
            if ($rnum == 1) {
                $inputHTML = $inputHTML . "<option value='" . $row['INSURANCE_ID'] . "'>" . $row['INSURANCE_NAME'] . "</option>";
                // output picked
            } else {
                // output not picked
            }

            $stmt->close();
        }
        return $inputHTML;
        $conn->close();
    }
}


$insu_name = "";
$amount_pending = "";
$due_date = "";
$pay_status = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" &&  isset($_POST['submitData'])) {
    $host = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $dbName = "insurance";

    $insuranceID = $_POST["insurancepolicy"];
    $usernric = $_SESSION['nric'];

    $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);
    $inputHTML = "";
    if (mysqli_connect_error()) {
        die('Connection Error:( ' . mysqli_connect_errno() . ' ) ' . mysqli_connect_error());
    } else {

        // SQL COMMAND 
        $GETCHOOSE = "SELECT CHOOSE_DATE FROM choose WHERE CLIENT_IC = ? AND INSURANCE_ID = ?";
        $GETPAYMENT = "SELECT PAYMENT_STATUS, PAYMENT_DUE, PAYMENT_AMOUNT FROM payment WHERE CLIENT_IC = ? AND INSURANCE_ID = ?";
        $GETINSURANCE = "SELECT INSURANCE_ID, INSURANCE_NAME, INSURANCE_LIMIT, INSURANCE_DURATION, INSURANCE_COVERAGE FROM insurance_policy WHERE INSURANCE_ID = ?";

        // first get insurance data
        // then get payment data
        // with choose date

        $stmt = $conn->prepare($GETINSURANCE); // PREPARING SQL COMMAND
        $stmt->bind_param("s", $insuranceID); // BIND PARAMETER "?" TO $insuranceID
        $stmt->execute(); // EXECUTE COMMAND
        $stmt->store_result(); // STORE RESULT
        $stmt->bind_result($insID, $ins_name, $ins_limit, $ins_duration, $ins_coverage); // BIND RESULT TO VARIABLE
        $stmt->fetch(); // FETCH RESULT
        $stmt->close(); // CLOSE $stmt

        $stmt = $conn->prepare($GETPAYMENT);
        $stmt->bind_param("ss", $usernric, $insuranceID);
        $stmt->execute(); // EXECUTE COMMAND
        $stmt->store_result(); // STORE RESULT
        //	PAYMENT_STATUS, PAYMENT_DUE, PAYMENT_AMOUNT
        $stmt->bind_result($payment_status, $payment_due, $payment_amount); // BIND RESULT TO VARIABLE
        $stmt->fetch(); // FETCH RESULT
        $stmt->close(); // CLOSE $stmt

        $stmt = $conn->prepare($GETCHOOSE);
        $stmt->bind_param("ss", $usernric, $insuranceID);
        $stmt->execute(); // EXECUTE COMMAND
        $stmt->store_result(); // STORE RESULT
        //	PAYMENT_STATUS, PAYMENT_DUE, PAYMENT_AMOUNT
        $stmt->bind_result($choose_date); // BIND RESULT TO VARIABLE
        $stmt->fetch(); // FETCH RESULT
        $stmt->close(); // CLOSE $stmt

        $insu_name = "$ins_name";

        // total needed to be paid
        // amount pending = insurance_limit - payment_amount
        $amount_pending = $ins_limit - $payment_amount;
        // if paid fully status changed to paid, else it will just be pending until limit is paid
        $pay_status = "$payment_status";

        // user can also paid monthly // that's why there is a due date

        // when user have paid, the due date are changed manually by the receiving staff
        $due_date = "$payment_due";

        $conn->close();

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
    <button class="button" name="backButton" value="Back"><a style="text-decoration: none;" href="user_menu.php">Back</a></button>

    <h2><u>To Pay</u></h2>
    <p>Please contact our staff for transaction, at 60-1121199669.<br>Working hours: 8:00AM - 11:00PM From Monday Until Saturday.<br>Status will still be 'PENDING', unless you paid the full amount of the due amount.</p>

    <form id="user-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <table style="border: none;">
            <tr>
                <td style="border: none;">Choose Insurance Policy: </td>
                <td>
                    <select name="insurancepolicy">
                        <?php
                        echo $inputHTML = getUserIP();
                        ?>
                    </select>
                    <input class="button" style="cursor: pointer;" name="submitData" type="submit" value="GET">
                </td>
            </tr>
            <td style="border: none;"></td>
            <td style="border: none;">
                <!--<button class="button" name="backButton" value="Back"><a style="text-decoration: none;" href="user_menu.php">Back</a></button> -->
            </td>
        </table>
    </form>

    <form id="user-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST"><table style="border: none;">
            <tr>
                <td style="border: none;">Insurance Policy Name: </td>
                <td>
                    <input type="text" name="insuranceName" value="<?php echo $insu_name; ?>" readonly>
                </td>
            </tr>
            <tr>
                <td style="border: none;">Due Amount (total due minus paid amount): RM</td>
                <td>
                    <input type="text" name="dueAmount" value="<?php echo $amount_pending; ?>" readonly>
                </td>
            </tr>
            <tr>
                <td style="border: none;">Due Date: </td>
                <td>
                    <input type="date" name="dueDate" value="<?php echo $due_date; ?>" readonly>
                </td>
            </tr>
            <tr>
                <td style="border: none;">Payment Status: </td>
                <td>
                    <input type="text" name="paymentStatus" value="<?php echo $pay_status; ?>" readonly>
                </td>
            </tr>
            <td style="border: none;"></td>
            <td style="border: none;">
                <!--<button class="button" name="backButton" value="Back"><a style="text-decoration: none;" href="user_menu.php">Back</a></button> -->
            </td>
        </table>
    </form>

</body>

</html>