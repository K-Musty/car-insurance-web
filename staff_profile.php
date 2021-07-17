<?php

// name, position, salary, bonus

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
$staffID = $_SESSION["staff_id"];
$staffName = $staffPosition = $staffSalary = $staffBonus = "";


$host = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "insurance";

$conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);
$inputHTML = "";
if (mysqli_connect_error()) {
    die('Connection Error:( ' . mysqli_connect_errno() . ' ) ' . mysqli_connect_error());
} else {
    //	EMP_ID, EMP_PASSWORD, EMP_NAME, EMP_POSITION, EMP_SALARY, EMP_BONUS
    $GETSTAFF = "SELECT EMP_NAME, EMP_POSITION, EMP_SALARY, EMP_BONUS FROM employee WHERE EMP_ID = ?";

    $stmt = $conn->prepare($GETSTAFF);
    $stmt->bind_param("s", $staffID);
    $stmt->execute();
    $stmt->store_result(); // STORE RESULT
    $stmt->bind_result($emp_name, $emp_pos, $emp_salary, $emp_bonus); // BIND RESULT TO VARIABLE
    $stmt->fetch(); // FETCH RESULT
    $stmt->close(); // CLOSE $stmt

    $staffName = "$emp_name";
    $staffPosition = "$emp_pos";
    $staffSalary = "$emp_salary";
    $staffBonus = "$emp_bonus";


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
    <button class="button" name="backButton" value="Back"><a style="text-decoration: none;" href="staff_menu.php">Back</a></button>

    <h2><u>To Pay</u></h2>

    <form id="staff-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <table style="border: none;">
            <tr>
                <td style="border: none;">Name: </td>
                <td>
                    <input type="text" name="staffName" value="<?php echo $staffName; ?>" readonly>
                </td>
            </tr>
            <tr>
                <td style="border: none;">Position: </td>
                <td>
                    <input type="text" name="staffPosition" value="<?php echo $staffPosition; ?>" readonly>
                </td>
            </tr>
            <tr>
                <td style="border: none;">Salary: RM</td>
                <td>
                    <input type="text" name="staffSalary" value="<?php echo $staffSalary; ?>" readonly>
                </td>
            </tr>
            <tr>
                <td style="border: none;">Bonus: RM</td>
                <td>
                    <input type="text" name="staffBonus" value="<?php echo $staffBonus; ?>" readonly>
                </td>
            </tr>
            <td style="border: none;"></td>
            <td style="border: none;">
                <!--<button class="button" name="backButton" value="Back"><a style="text-decoration: none;" href="staff_menu.php">Back</a></button> -->
            </td>
        </table>
    </form>
</body>

</html>