<?php

// related to employee table
// updating table
// insert
// update
// delete
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

$adminID = $_SESSION['admin_id'];

$error_msg = "";

$inputOption = ""; // insertForm   updateDeleteForm

$host = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "insurance";

if ($_SERVER["REQUEST_METHOD"] == "POST" &&  isset($_POST['submitINSERT'])) {
    $newEMPId = $_POST['newEmpId'];
    $newEMPName = $_POST['newEmpName'];
    $newEMPPassword = $_POST['newEmpPassword'];
    $newEMPPosition = $_POST['newEmpPosition'];
    $newEMPSalary = $_POST['newEmpSalary'];
    $newEMPBonus = $_POST['newEmpBonus'];
    if (is_numeric($newEMPBonus) || is_numeric($newEMPSalary)) {
        $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);
        if (mysqli_connect_error()) {
            die('Connection Error:( ' . mysqli_connect_errno() . ' ) ' . mysqli_connect_error());
        } else {
            $CHECK = "SELECT * FROM employee WHERE EMP_ID = ?";
            $stmt = $conn->prepare($CHECK);
            $stmt->bind_param("s", $newEMPId);
            $stmt->execute();
            $stmt->store_result();
            $rnum = $stmt->num_rows;
            $stmt->close();

            if ($rnum == 0) {
                // user not exist, so can create new	
                // EMP_ID, EMP_PASSWORD, EMP_NAME, EMP_POSITION, EMP_SALARY, EMP_BONUS, ADMIN_ID
                $INSERT = "INSERT INTO employee(EMP_ID, EMP_PASSWORD, EMP_NAME, EMP_POSITION, EMP_SALARY, EMP_BONUS, ADMIN_ID) VALUES(?, ?, ?, ?, ?, ?, ?)";
                $pass_hash = md5($newEMPPassword, true);

                $stmt = $conn->prepare($INSERT);
                $stmt->bind_param("ssssdds", $newEMPId, $pass_hash, $newEMPName, $newEMPPosition, $newEMPSalary, $newEMPBonus, $adminID);
                $stmt->execute();
                $error_msg = "<p> Successfully INSERT new employee; (EMP_ID: $newEMPId - EMP_NAME: $newEMPName) </p>";
                $stmt->close();
            } else {
                $error_msg = "<p> Please check again, there is already employee with the same input employee id. (EMP_ID: $newEMPId)</p>";
            }
        }
        $conn->close();
    } else {
        $error_msg = "The Salary/Bonus must be numeric.";
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST" &&  isset($_POST['insertForm'])) {
    // output insert Form for employee
    $form = "";
    $form = $form . "<tr><td>Employee ID: </td>";
    $form = $form . "<td><input type='text' name='newEmpId' required></td></tr>";
    $form = $form . "<tr><td>Employee Name: </td>";
    $form = $form . "<td><input type='text' name='newEmpName' required></td></tr>";
    $form = $form . "<tr><td>Employee Password: </td>";
    $form = $form . "<td><input type='password' name='newEmpPassword' required></td></tr>";
    $form = $form . "<tr><td>Employee Position: </td>";
    $form = $form . "<td><input type='text' name='newEmpPosition' required></td></tr>";
    $form = $form . "<tr><td>Employee Salary: RM</td>";
    $form = $form . "<td><input type='text' name='newEmpSalary' placeholder='00000.00' required></td></tr>";
    $form = $form . "<tr><td>Employee Bonus: RM</td>";
    $form = $form . "<td><input type='text' name='newEmpBonus' placeholder='00000.00' required></td></tr>";
    $form = $form . "<tr><td></td>";
    $form = $form . "<td><input type='submit' name='submitINSERT' value='INSERT New Employee Data'></td></tr>";

    $inputOption = $form;
}
if ($_SERVER["REQUEST_METHOD"] == "POST" &&  isset($_POST['updateDeleteForm'])) {
    // output insert Form for employee
    $empList = "";
    $empList = empList();
    $form = "";
    $form = $form . "<tr><td>Employee ID: </td>";
    $form = $form . "<td><select name='empID'>$empList</select></td><td><input type='submit' name='getEMPData' value='GET Employee Data'></td></tr>";

    $inputOption = $form;
}
$inputEMPData = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" &&  isset($_POST['getEMPData'])) {
    $getEMPId = $_POST['empID'];
    if ($getEMPId == '' || $getEMPId == "" || empty($getEMPId)) {
        $error_msg = "Please choose the employee id";
    } else {
        $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);
        if (mysqli_connect_error()) {
            die('Connection Error:( ' . mysqli_connect_errno() . ' ) ' . mysqli_connect_error());
        } else {
            $SELECT = "SELECT EMP_NAME, EMP_POSITION, EMP_SALARY, EMP_BONUS FROM employee WHERE EMP_ID = ?";
            $stmt = $conn->prepare($SELECT);
            $stmt->bind_param("s", $getEMPId);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($empName, $empPosition, $empSalary, $empBonus);
            $stmt->fetch();

            $form = "";
            $form = $form . "<tr><td>Employee ID: </td>";
            $form = $form . "<td><input type='text' name='newEmpId' value='$getEMPId' readonly></td></tr>";
            $form = $form . "<tr><td>Employee Name: </td>";
            $form = $form . "<td><input type='text' name='newEmpName' value='$empName' required></td></tr>";
            $form = $form . "<tr><td>Employee Position: </td>";
            $form = $form . "<td><input type='text' name='newEmpPosition' value='$empPosition' required></td></tr>";
            $form = $form . "<tr><td>Employee Salary: RM</td>";
            $form = $form . "<td><input type='text' name='newEmpSalary' value='$empSalary' required></td></tr>";
            $form = $form . "<tr><td>Employee Bonus: RM</td>";
            $form = $form . "<td><input type='text' name='newEmpBonus' value='$empBonus' required></td></tr>";
            $form = $form . "<tr><td></td>";
            $form = $form . "<td><input type='submit' name='submitUPDATE' value='UPDATE Employee Data'></td><td><input type='submit' name='submitDELETEbefore' value='DELETE Employee Data'></td></tr>";
            $inputEMPData = $form;
            $stmt->close();
        }
        $conn->close();
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST" &&  isset($_POST['submitDELETEbefore'])) {
    $getEMPId = $_POST['newEmpId'];
    $empName = $_POST['newEmpName'];
    $empPosition = $_POST['newEmpPosition'];
    $empSalary = $_POST['newEmpSalary'];
    $empBonus = $_POST['newEmpBonus'];

    $form = "";
    $form = $form . "<tr><td>Employee ID: </td>";
    $form = $form . "<td><input type='text' name='newEmpId' value='$getEMPId' readonly></td></tr>";
    $form = $form . "<tr><td>Employee Name: </td>";
    $form = $form . "<td><input type='text' name='newEmpName' value='$empName' required></td></tr>";
    $form = $form . "<tr><td>Employee Position: </td>";
    $form = $form . "<td><input type='text' name='newEmpPosition' value='$empPosition' required></td></tr>";
    $form = $form . "<tr><td>Employee Salary: RM</td>";
    $form = $form . "<td><input type='text' name='newEmpSalary' value='$empSalary' required></td></tr>";
    $form = $form . "<tr><td>Employee Bonus: RM</td>";
    $form = $form . "<td><input type='text' name='newEmpBonus' value='$empBonus' required></td></tr>";
    $form = $form . "<tr><td></td>";
    $form = $form . "<td><input type='submit' name='submitDELETEafter' value='Confirm DELETE Employee Data'></td><td><input type='submit' name='cancel' value='CANCEL'></td></tr>";
    $inputEMPData = $form;
}
if ($_SERVER["REQUEST_METHOD"] == "POST" &&  isset($_POST['submitDELETEafter'])) {
    $newEMPId = $_POST['newEmpId'];
    $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);
    if (mysqli_connect_error()) {
        die('Connection Error:( ' . mysqli_connect_errno() . ' ) ' . mysqli_connect_error());
    } else {
        //DELETE FROM car WHERE CLIENT_IC = ? AND CAR_NO = ?
        $test = "";

        $DELUPDATING = "DELETE FROM updating WHERE EMP_ID = '$newEMPId'";
        if($conn->query($DELUPDATING) === true) {
            $test = $test."DELETE FROM updating SUCCESS. ";
        } else {
            $test = $test."ERROR DELETE FROM updating . ".$conn->error;
        }

        $DELCLIENT = "DELETE FROM client WHERE EMP_ID = $newEMPId";
        if($conn->query($DELCLIENT) === true) {
            $test = $test."DELETE FROM client SUCCESS. ";
        } else {
            $test = $test."ERROR DELETE FROM client . ".$conn->error;
        }

        $DELEMP = "DELETE FROM employee WHERE EMP_ID = $newEMPId";
        if($conn->query($DELEMP) === true) {
            $test = $test."DELETE FROM employee SUCCESS. ";
        } else {
            $test = $test."ERROR DELETE FROM employee . ".$conn->error;
        }

        $error_msg = "Successfully Delete All Data Related to employee ID: $newEMPId";
    }
    $conn->close();
}
if ($_SERVER["REQUEST_METHOD"] == "POST" &&  isset($_POST['submitUPDATE'])) {
    // update employee
    $newEMPId = $_POST['newEmpId'];
    $newEMPName = $_POST['newEmpName'];
    $newEMPPosition = $_POST['newEmpPosition'];
    $newEMPSalary = $_POST['newEmpSalary'];
    $newEMPBonus = $_POST['newEmpBonus'];

    if (is_numeric($newEMPBonus) || is_numeric($newEMPSalary)) {
        $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);
        if (mysqli_connect_error()) {
            die('Connection Error:( ' . mysqli_connect_errno() . ' ) ' . mysqli_connect_error());
        } else {
            $CHECK = "SELECT * FROM employee WHERE EMP_ID = ?";
            $stmt = $conn->prepare($CHECK);
            $stmt->bind_param("s", $newEMPId);
            $stmt->execute();
            $stmt->store_result();
            $rnum = $stmt->num_rows;
            $stmt->close();

            if ($rnum == 1) {
                // user exist, so can update	
                // EMP_ID, EMP_PASSWORD, EMP_NAME, EMP_POSITION, EMP_SALARY, EMP_BONUS, ADMIN_ID
                $UPDATE = "UPDATE employee SET EMP_NAME = ?, EMP_POSITION = ?, EMP_SALARY = ?, EMP_BONUS = ? WHERE EMP_ID = ?";
                $stmt = $conn->prepare($UPDATE);
                $stmt->bind_param("ssdds", $newEMPName, $newEMPPosition, $newEMPSalary, $newEMPBonus, $newEMPId);
                $stmt->execute();

                $error_msg = "<p> Successfully UPDATE employee; (EMP_ID: $newEMPId - EMP_NAME: $newEMPName) </p>";
                $stmt->close();
            } else {
                $error_msg = "<p> Please check again, there is no employee with the same employee id. (EMP_ID: $newEMPId)</p>";
            }
        }
        $conn->close();
    } else {
        $error_msg = "The Salary/Bonus must be numeric.";
    }
}
function empList()
{
    $host = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $dbName = "insurance";

    $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);
    $empList = "";
    if (mysqli_connect_error()) {
        die('Connection Error:( ' . mysqli_connect_errno() . ' ) ' . mysqli_connect_error());
    } else {
        $GETUSERLIST = "SELECT EMP_ID, EMP_NAME, EMP_POSITION, EMP_SALARY, EMP_BONUS FROM employee";
        $result = $conn->query($GETUSERLIST);

        $empList = $empList . "<option value=''>Select Employee</option>";

        while ($row = $result->fetch_assoc()) {
            $empList = $empList . "<option value='" . $row['EMP_ID'] . "'>" . $row['EMP_ID'] . " - " . $row['EMP_NAME'] . "</option>";
        }
        return $empList;
        $result->close();
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
    <button class="button" name="backButton" value="Back"><a style="text-decoration: none;" href="admin_menu.php">Back</a></button>
    <h2><u>Manage Employee</u></h2>

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
                    <input class="button" style="cursor: pointer;" name="insertForm" type="submit" value="INSERT Employee">
                </td>
                <td>
                    <input class="button" style="cursor: pointer;" name="updateDeleteForm" type="submit" value="UPDATE/DELETE Employee">
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
            echo $inputEMPData;
            ?>

        </table>
    </form>

</body>

</html>