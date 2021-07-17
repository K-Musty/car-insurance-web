<?php

// BRB
//  staff can view client and client car
// view client insurance policy
// view payment / update payment

// get client ->display option client/car/insurance policy/payment
// get option -> display output

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
function getUserNRIC()
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
        $GETUSERLIST = "SELECT CLIENT_IC, CLIENT_NAME FROM client";
        $result = $conn->query($GETUSERLIST);

        $inputHTML = $inputHTML . "<option value=''>Select User</option>";

        while ($row = $result->fetch_assoc()) {
            $inputHTML = $inputHTML . "<option value='" . $row['CLIENT_IC'] . "'>" . $row['CLIENT_IC'] . " - " . $row['CLIENT_NAME'] . "</option>";
        }
        return $inputHTML;
        $result->close();
        $conn->close();
    }
}
$error_msg = "";
$resultHTML = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" &&  isset($_POST['viewClientDetails'])) {
    $userNRIC = $_POST["userNRIC"];
    $host = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $dbName = "insurance";

    if ($userNRIC == "" || $userNRIC == '' || empty($userNRIC)) {
        $error_msg = "Please choose the user";
    } else {
        $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);
        $resultHTML = "";
        if (mysqli_connect_error()) {
            die('Connection Error:( ' . mysqli_connect_errno() . ' ) ' . mysqli_connect_error());
        } else {
            //	CLIENT_IC, CLIENT_NAME, CLIENT_PHONE_NO, CLIENT_ADDRESS
            // READONLY
            $GETUSER = "SELECT CLIENT_IC, CLIENT_NAME, CLIENT_PHONE_NO, CLIENT_ADDRESS FROM client WHERE CLIENT_IC = ?";
            $stmt = $conn->prepare($GETUSER);
            $stmt->bind_param("s", $userNRIC);
            $stmt->execute();
            $stmt->store_result(); // STORE RESULT
            $stmt->bind_result($client_ic, $client_name, $client_phoneno, $client_address); // BIND RESULT TO VARIABLE
            $stmt->fetch(); // FETCH RESULT
            $stmt->close(); // CLOSE $stmt

            $resultHTML = $resultHTML . "<table><tr><td>Client Name: </td>";
            $resultHTML = $resultHTML . "<td><input type='text' name='clientName' value='$client_name' readonly></td></tr>";

            $resultHTML = $resultHTML . "<tr><td>Client NRIC: </td>";
            $resultHTML = $resultHTML . "<td><input type='text' name='clientNRIC' value='$client_ic' readonly></td></tr>";

            $resultHTML = $resultHTML . "<tr><td>Client Phone No: </td>";
            $resultHTML = $resultHTML . "<td><input type='text' name='clientPhone' value='$client_phoneno' readonly></td></tr>";

            $resultHTML = $resultHTML . "<tr><td>Client Address: </td>";
            $resultHTML = $resultHTML . "<td><input type='text' name='clientAddress' value='$client_address' readonly></td></tr></table>";

            //$stmt->close();
            $conn->close();
        }
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST" &&  isset($_POST['viewClientCar'])) {
    $userNRIC = $_POST["userNRIC"];
    $host = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $dbName = "insurance";

    if ($userNRIC == "" || $userNRIC == '' || empty($userNRIC)) {
        $error_msg = "Please choose the user";
    } else {
        $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);
        $resultHTML = "";
        if (mysqli_connect_error()) {
            die('Connection Error:( ' . mysqli_connect_errno() . ' ) ' . mysqli_connect_error());
        } else {
            //	CAR_NO, CAR_CONDITIONS, CAR_MODEL, CAR_MANUFACTURED
            $GETUSERCAR = "SELECT CAR_NO, CAR_CONDITIONS, CAR_MODEL, CAR_MANUFACTURED FROM car WHERE CLIENT_IC = $userNRIC";
            $result = $conn->query($GETUSERCAR);

            $GETUSER = "SELECT CLIENT_IC, CLIENT_NAME, CLIENT_PHONE_NO, CLIENT_ADDRESS FROM client WHERE CLIENT_IC = ?";
            $stmt = $conn->prepare($GETUSER);
            $stmt->bind_param("s", $userNRIC);
            $stmt->execute();
            $stmt->store_result(); // STORE RESULT
            $stmt->bind_result($client_ic, $client_name, $client_phoneno, $client_address); // BIND RESULT TO VARIABLE
            $stmt->fetch(); // FETCH RESULT
            $stmt->close(); // CLOSE $stmt
            $resultHTML = $resultHTML . "<table><tr><td>Client Name: </td>";
            $resultHTML = $resultHTML . "<td><input type='text' name='clientName' value='$client_name' readonly></td></tr>";

            $resultHTML = $resultHTML . "<tr><td>Client NRIC: </td>";
            $resultHTML = $resultHTML . "<td><input type='text' name='clientNRIC' value='$client_ic' readonly></td></tr></table>";

            while ($row = $result->fetch_assoc()) {
                //
                $resultHTML = $resultHTML . "<table>";
                $resultHTML = $resultHTML . "<tr><td>Car No: </td><td><input type='text' name='carNo' value='" . $row['CAR_NO'] . "' readonly></td></tr>";
                $resultHTML = $resultHTML . "<tr><td>Car Conditions: </td><td><input type='text' name='carCond' value='" . $row['CAR_CONDITIONS'] . "' readonly></td></tr>";
                $resultHTML = $resultHTML . "<tr><td>Car Model: </td><td><input type='text' name='carModel' value='" . $row['CAR_MODEL'] . "' readonly></td></tr>";
                $resultHTML = $resultHTML . "<tr><td>Car Manufactured: </td><td><input type='text' name='carManu' value='" . $row['CAR_MANUFACTURED'] . "' readonly></td></tr>";
                $resultHTML = $resultHTML . "</table>";
            }

            $result->close();
            $conn->close();
        }
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST" &&  isset($_POST['viewClientInsurancePolicy'])) {
    $userNRIC = $_POST["userNRIC"];
    $host = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $dbName = "insurance";

    if ($userNRIC == "" || $userNRIC == '' || empty($userNRIC)) {
        $error_msg = "Please choose the user";
    } else {
        $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);
        $resultHTML = "";
        if (mysqli_connect_error()) {
            die('Connection Error:( ' . mysqli_connect_errno() . ' ) ' . mysqli_connect_error());
        } else {
            // 	INSURANCE_ID, INSURANCE_NAME, INSURANCE_LIMIT, INSURANCE_DURATION, INSURANCE_COVERAGE
            $GETINSURANCEPOLICY = "SELECT INSURANCE_NAME, INSURANCE_LIMIT, INSURANCE_DURATION, INSURANCE_COVERAGE FROM insurance_policy WHERE INSURANCE_ID = ?";
            $GETCHOOSE = "SELECT INSURANCE_ID, CHOOSE_DATE FROM choose WHERE CLIENT_IC = $userNRIC";
            $result = $conn->query($GETCHOOSE);

            $GETUSER = "SELECT CLIENT_IC, CLIENT_NAME, CLIENT_PHONE_NO, CLIENT_ADDRESS FROM client WHERE CLIENT_IC = ?";
            $stmt = $conn->prepare($GETUSER);
            $stmt->bind_param("s", $userNRIC);
            $stmt->execute();
            $stmt->store_result(); // STORE RESULT
            $stmt->bind_result($client_ic, $client_name, $client_phoneno, $client_address); // BIND RESULT TO VARIABLE
            $stmt->fetch(); // FETCH RESULT
            $stmt->close(); // CLOSE $stmt
            $resultHTML = $resultHTML . "<table><tr><td>Client Name: </td>";
            $resultHTML = $resultHTML . "<td><input type='text' name='clientName' value='$client_name' readonly></td></tr>";

            $resultHTML = $resultHTML . "<tr><td>Client NRIC: </td>";
            $resultHTML = $resultHTML . "<td><input type='text' name='clientNRIC' value='$client_ic' readonly></td></tr></table>";
            while ($row = $result->fetch_assoc()) {
                $IP_id = $row["INSURANCE_ID"];
                $stmt = $conn->prepare($GETINSURANCEPOLICY);
                $stmt->bind_param("s", $IP_id);
                $stmt->execute();
                $stmt->store_result(); // STORE RESULT
                $stmt->bind_result($ins_name, $ins_limit, $ins_duration, $ins_coverage); // BIND RESULT TO VARIABLE
                $stmt->fetch(); // FETCH RESULT
                $stmt->close(); // CLOSE $stmt

                $resultHTML = $resultHTML . "<table>";
                $resultHTML = $resultHTML . "<tr><td>Insurance Name: </td><td><input type='text' name='insName' value='$ins_name' readonly></td></tr>";
                $resultHTML = $resultHTML . "<tr><td>Insurance Limit: RM</td><td><input type='text' name='insLimit' value='$ins_limit' readonly></td></tr>";
                $resultHTML = $resultHTML . "<tr><td>Insurance Duration: </td><td><input type='text' name='insDuration' value='$ins_duration' readonly></td></tr>";
                $resultHTML = $resultHTML . "<tr><td>Insurance Coverage: RM</td><td><input type='text' name='insCoverage' value='$ins_coverage' readonly></td></tr>";
                $resultHTML = $resultHTML . "</table>";
            }

            $result->close();
            $conn->close();
        }
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST" &&  isset($_POST['updateClientPayment'])) {
    $userNRIC = $_POST["userNRIC"];
    $host = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $dbName = "insurance";

    if ($userNRIC == "" || $userNRIC == '' || empty($userNRIC)) {
        $error_msg = "Please choose the user";
    } else {
        $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);
        $resultHTML = "";
        if (mysqli_connect_error()) {
            die('Connection Error:( ' . mysqli_connect_errno() . ' ) ' . mysqli_connect_error());
        } else {
            // this
            $GETPAYMENT = "SELECT INSURANCE_ID, PAYMENT_STATUS, PAYMENT_DUE, PAYMENT_AMOUNT FROM payment WHERE CLIENT_IC = $userNRIC";
            $result = $conn->query($GETPAYMENT);

            $GETUSER = "SELECT CLIENT_IC, CLIENT_NAME, CLIENT_PHONE_NO, CLIENT_ADDRESS FROM client WHERE CLIENT_IC = ?";
            $stmt = $conn->prepare($GETUSER);
            $stmt->bind_param("s", $userNRIC);
            $stmt->execute();
            $stmt->store_result(); // STORE RESULT
            $stmt->bind_result($client_ic, $client_name, $client_phoneno, $client_address); // BIND RESULT TO VARIABLE
            $stmt->fetch(); // FETCH RESULT
            $stmt->close(); // CLOSE $stmt
            $resultHTML = $resultHTML . "<table><tr><td>Client Name: </td>";
            $resultHTML = $resultHTML . "<td><input type='text' name='clientName' value='$client_name' readonly></td></tr>";

            $resultHTML = $resultHTML . "<tr><td>Client NRIC: </td>";
            $resultHTML = $resultHTML . "<td><input type='text' name='clientNRIC' value='$client_ic' readonly></td></tr></table>";


            $resultHTML = $resultHTML . "<table><tr><td>Insurance Name: </td> <td><select name='userIP'><option value=''>Choose Insurance Policy</option>";
            while ($row = $result->fetch_assoc()) {
                $ins_id = $row["INSURANCE_ID"];
                $pay_stats = $row["PAYMENT_STATUS"];
                $pay_due = $row["PAYMENT_DUE"];
                $pay_amount = $row["PAYMENT_AMOUNT"];

                $GETINSURANCEPOLICY = "SELECT INSURANCE_NAME, INSURANCE_LIMIT, INSURANCE_DURATION, INSURANCE_COVERAGE FROM insurance_policy WHERE INSURANCE_ID = ?";

                $stmt = $conn->prepare($GETINSURANCEPOLICY);
                $stmt->bind_param("s", $ins_id);
                $stmt->execute();
                $stmt->store_result(); // STORE RESULT
                $stmt->bind_result($ins_name, $ins_limit, $ins_duration, $ins_coverage); // BIND RESULT TO VARIABLE
                $stmt->fetch(); // FETCH RESULT
                $stmt->close(); // CLOSE $stmt

                $resultHTML = $resultHTML . "<option value='$ins_id'>$ins_name</option>";
            }
            $resultHTML = $resultHTML . "</select><td><input type='submit' name='getIPPayment' value='GET'></td></td></tr></table>";

            $conn->close();
        }
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST" &&  isset($_POST['getIPPayment'])) {
    $userNRIC = $_POST["clientNRIC"];
    $ins_id = $_POST["userIP"];
    $host = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $dbName = "insurance";

    if ($userNRIC == "" || $userNRIC == '' || empty($userNRIC) || $ins_id == "" || $ins_id == '' || empty($ins_id)) {
        $error_msg = "Please choose the insurance policy";
    } else {
        $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);
        $resultHTML = "";
        if (mysqli_connect_error()) {
            die('Connection Error:( ' . mysqli_connect_errno() . ' ) ' . mysqli_connect_error());
        } else {
            // this

            $GETUSER = "SELECT CLIENT_IC, CLIENT_NAME, CLIENT_PHONE_NO, CLIENT_ADDRESS FROM client WHERE CLIENT_IC = ?";
            $stmt = $conn->prepare($GETUSER);
            $stmt->bind_param("s", $userNRIC);
            $stmt->execute();
            $stmt->store_result(); // STORE RESULT
            $stmt->bind_result($client_ic, $client_name, $client_phoneno, $client_address); // BIND RESULT TO VARIABLE
            $stmt->fetch(); // FETCH RESULT
            $stmt->close(); // CLOSE $stmt
            $resultHTML = $resultHTML . "<table><tr><td>Client Name: </td>";
            $resultHTML = $resultHTML . "<td><input type='text' name='clientName' value='$client_name' readonly></td></tr>";

            $resultHTML = $resultHTML . "<tr><td>Client NRIC: </td>";
            $resultHTML = $resultHTML . "<td><input type='text' name='clientNRIC2' value='$client_ic' readonly></td></tr></table>";

            $GETPAYMENT = "SELECT PAYMENT_STATUS, PAYMENT_DUE, PAYMENT_AMOUNT FROM payment WHERE INSURANCE_ID = ? AND CLIENT_IC = ?";

            $GETINSURANCEPOLICY = "SELECT INSURANCE_NAME, INSURANCE_LIMIT, INSURANCE_DURATION, INSURANCE_COVERAGE FROM insurance_policy WHERE INSURANCE_ID = ?";

            $stmt = $conn->prepare($GETINSURANCEPOLICY);
            $stmt->bind_param("s", $ins_id);
            $stmt->execute();
            $stmt->store_result(); // STORE RESULT
            $stmt->bind_result($ins_name, $ins_limit, $ins_duration, $ins_coverage); // BIND RESULT TO VARIABLE
            $stmt->fetch(); // FETCH RESULT
            $stmt->close(); // CLOSE $stmt

            $stmt = $conn->prepare($GETPAYMENT);
            $stmt->bind_param("ss", $ins_id, $userNRIC);
            $stmt->execute();
            $stmt->store_result(); // STORE RESULT
            $stmt->bind_result($pay_stats, $pay_due, $pay_amount); // BIND RESULT TO VARIABLE
            $stmt->fetch(); // FETCH RESULT
            $stmt->close(); // CLOSE $stmt


            $dueAmount = $ins_limit - $pay_amount;

            $resultHTML = $resultHTML . "<table>";
            $resultHTML = $resultHTML . "<tr><td>Insurance Name: </td><td><input type='text' name='insName' value='$ins_name' readonly></td><td><input type='text' name='insID' value='$ins_id' style='display: none;' readonly></td></tr>";
            $resultHTML = $resultHTML . "<tr><td>Due Amount (total due minus paid amount): RM</td><td><input type='text' name='payDue' value='$dueAmount' readonly></td></tr>";
            $resultHTML = $resultHTML . "<tr><td>Due Date: </td><td><input type='date' name='dueDate' value='$pay_due' readonly></td></tr>";
            $resultHTML = $resultHTML . "<tr><td>Payment Status: </td><td><input type='text' name='payStatus' value='$pay_stats' readonly></td></tr>";
            $resultHTML = $resultHTML . "<tr><td>Payment Amount (total paid amount): RM</td><td><input type='text' name='payAmount' value='$pay_amount' readonly></td></tr>";
            $resultHTML = $resultHTML . "<tr><td>Payment Paid (client paid): RM</td><td><input type='text' name='payPaid' placeholder='00000.00' value='0.00' required></td><td>Insert here for user paid transaction</td></tr>";
            $resultHTML = $resultHTML."<tr><td></td><td><input type='submit' name='updatePayment' value='Update Payment'></td></tr>";
            $resultHTML = $resultHTML . "</table>";


            $conn->close();
        }
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST" &&  isset($_POST['updatePayment'])) {
    $userNRIC = $_POST["clientNRIC2"];
    $ins_id = $_POST["insID"];
    $getPaymentPaid = $_POST["payPaid"];
    $host = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $dbName = "insurance";

    if ($userNRIC == "" || $userNRIC == '' || empty($userNRIC) || $ins_id == "" || $ins_id == '' || empty($ins_id) || $getPaymentPaid == "" || $getPaymentPaid == '' || empty($getPaymentPaid) || !is_numeric($getPaymentPaid)) {
        $error_msg = "Please don't let the fields empty and make sure the input is numeric";
    } else {
        $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);
        $resultHTML = "";
        if (mysqli_connect_error()) {
            die('Connection Error:( ' . mysqli_connect_errno() . ' ) ' . mysqli_connect_error());
        } else {

            $GETINSURANCEPOLICY = "SELECT INSURANCE_NAME, INSURANCE_LIMIT, INSURANCE_DURATION, INSURANCE_COVERAGE FROM insurance_policy WHERE INSURANCE_ID = ?";
            $stmt = $conn->prepare($GETINSURANCEPOLICY);
            $stmt->bind_param("s", $ins_id);
            $stmt->execute();
            $stmt->store_result(); // STORE RESULT
            $stmt->bind_result($ins_name, $ins_limit, $ins_duration, $ins_coverage); // BIND RESULT TO VARIABLE
            $stmt->fetch(); // FETCH RESULT
            $stmt->close(); // CLOSE $stmt

            $GETPAYMENT = "SELECT PAYMENT_STATUS, PAYMENT_DUE, PAYMENT_AMOUNT FROM payment WHERE INSURANCE_ID = ? AND CLIENT_IC = ?";
            $stmt = $conn->prepare($GETPAYMENT);
            $stmt->bind_param("ss", $ins_id, $userNRIC);
            $stmt->execute();
            $stmt->store_result(); // STORE RESULT
            $stmt->bind_result($pay_stats, $pay_due, $pay_amount); // BIND RESULT TO VARIABLE
            $stmt->fetch(); // FETCH RESULT
            $stmt->close(); // CLOSE $stmt
            
            
            $p_amount = $pay_amount + $getPaymentPaid; // latest

            $dueAmount = $ins_limit - $p_amount; // limit - latest total

            $stat = "PENDING";
            if($dueAmount >= 1) {
                $stat = "PENDING";
                $today = date("Y-m-d");
                $estimateDUE = strftime("%Y-%m-%d", strtotime("$today +1 month"));
            } else {
                $stat = "PAID";
                $estimateDUE = "";
            }


            $UPDATE = "UPDATE payment SET PAYMENT_STATUS = ?, PAYMENT_DUE = ?, PAYMENT_AMOUNT = ? WHERE INSURANCE_ID = ? AND CLIENT_IC = ?";
            $stmt = $conn->prepare($UPDATE);
            $stmt->bind_param("ssdss", $stat, $estimateDUE, $p_amount, $ins_id, $userNRIC);
            $stmt->execute();

            $error_msg = "<p>Successfull updated data.</p>";

            $stmt->close();
            $conn->close();
        }
    }
}
/* 
$today = date("Y-m-d");
$estimateDUE = strftime("%Y-%m-%d", strtotime("$today +1 month"));*/
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

    <h2><u>To Pay</u></h2>
    <?php
    echo $error_msg;
    ?>
    <form id="staff-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <table style="border: none;">
            <tr>
                <td style="border: none;">Get User (IC): </td>
                <td>
                    <select name="userNRIC">
                        <?php
                        echo $inputHTML = getUserNRIC();
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Option (GET): </td>
                <td>
                    <input class="button" style="cursor: pointer;" name="viewClientDetails" type="submit" value="Client Details">
                    <input class="button" style="cursor: pointer;" name="viewClientCar" type="submit" value="Client Car Details">
                    <input class="button" style="cursor: pointer;" name="viewClientInsurancePolicy" type="submit" value="Client Insurance Policy Details">
                    <input class="button" style="cursor: pointer;" name="updateClientPayment" type="submit" value="Client Payment">
                </td>
            </tr>
        </table>
    </form>
    <p></p>
    <form id="staff-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <?php
        echo $resultHTML;
        ?>
    </form>

</body>

</html>