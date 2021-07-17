<?php

// menu 1
// all the list of insurance policy
// and descriptions etc.

// menu 2
// list the insurance policy that user have choosen

// or
// both
session_start();
$usernric = $_SESSION['nric'];

$inputResult = ""; // to echo for the results.

function getIP()
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
            $rnum = $stmt->num_rows;
            $IP_name = "Insurance Policy Name: " . $row["INSURANCE_NAME"];
            $IP_limit = "Insurance Policy Limit: RM" . $row["INSURANCE_LIMIT"];
            $IP_duration = "Insurance Policy Duration: " . $row["INSURANCE_DURATION"];
            $IP_coverage = "Insurance Policy Coverage: RM" . $row["INSURANCE_COVERAGE"];
            if ($rnum == 1) {
                $html = $html . "<div class='flex-item'>";
                $html = $html . "<p>$IP_name</p><p>$IP_limit</p><p>$IP_duration</p><p>$IP_coverage</p><p>Status: You already have this insurance policy.</p>";
                $html = $html . "</div>";
                $inputHTML = $inputHTML . $html;
                //   style="border: 1px solid black; width: 200px; padding-left: 50px; padding-right: 50px; margin: 20px;"
                // output picked
            } else {
                $html = $html . "<div class='flex-item'>";
                $html = $html . "<p>$IP_name</p><p>$IP_limit</p><p>$IP_duration</p><p>$IP_coverage</p><p>Status: You don't have this insurance policy.</p>";
                $html = $html . "</div>";
                $inputHTML = $inputHTML . $html;
                // output not picked
            }

            $stmt->close();
        }
        return $inputHTML;
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
    <style>
    div {
        padding: 10px;
    }
    .container {
        display: flex;
    }
    .fixed {
        width: 200px;
        margin: 20px;
        padding-left: 50px;
        padding-right: 50px; 
    }
    .flex-item {
        border: 1px solid black;
        flex-grow: 1;
        margin: 20px;
        padding-left: 50px;
        padding-right: 50px; 
    }
    </style>
</head>

<body>
    <button class="button" name="backButton" value="Back"><a style="text-decoration: none;" href="user_menu.php">Back</a></button>
    <h2><u>Insurance Policy</u></h2>
    <p><a style="text-decoration: none;" href="user_register_insurance.php">Click here to register your insurance policy now</a></p>

    <?php echo $inputResult; ?>
    <div class="container">
    <?php echo getIP(); ?>
    </div>


</body>

</html>