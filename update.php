<?php
include 'db.php';
$empid = $_GET['empid'];

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $empid = $_GET['empid'];
    if (empty($empid)) {
        echo "Employee ID not provided.";
        exit;
    }

    // Collect POST data
    $gender = $_POST['gender'];
    $mob = $_POST['mob'];
    $dob = $_POST['dob'];
    $pincode = $_POST['pincode'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate current password
    $pwdStmt = $conn->prepare("SELECT password FROM password WHERE empid = ? AND RowDeleted = 0");
    $pwdStmt->bind_param("s", $empid);
    $pwdStmt->execute();
    $pwdResult = $pwdStmt->get_result();
    $pwdData = $pwdResult->fetch_assoc();
    $pwdStmt->close();

    if (!$pwdData || !password_verify($password, $pwdData['password'])) {
        echo "Invalid password. Update not permitted.";
        exit;
    }

    // Start transaction
    $conn->begin_transaction();

    try {
        // 1. Soft delete old rows
        $tables = ['empdetails', 'empmaster', 'password'];
        foreach ($tables as $table) {
            $stmt = $conn->prepare("UPDATE $table SET RowDeleted = 1 WHERE empid = ?");
            $stmt->bind_param("s", $empid);
            $stmt->execute();
            $stmt->close();
        }

        // 2. Insert new rows (RowDeleted = 0 by default or explicitly)
        $stmt1 = $conn->prepare("INSERT INTO empdetails (empid, gender, mob, dob, pincode, RowDeleted) VALUES (?, ?, ?, ?, ?, 0)");
        $stmt1->bind_param("sssss", $empid, $gender, $mob, $dob, $pincode);
        $stmt1->execute();
        $stmt1->close();

        $stmt2 = $conn->prepare("INSERT INTO empmaster (empid, fname, lname, address, RowDeleted) VALUES (?, ?, ?, ?, 0)");
        $stmt2->bind_param("ssss", $empid, $fname, $lname, $address);
        $stmt2->execute();
        $stmt2->close();

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt3 = $conn->prepare("INSERT INTO password (empid, email, password, RowDeleted) VALUES (?, ?, ?, 0)");
        $stmt3->bind_param("sss", $empid, $email, $hashed_password);
        $stmt3->execute();
        $stmt3->close();

        // Commit the transaction
        $conn->commit();
        echo "Employee Updated Successfully.";
        header("Location: dashboard.php");
    } catch (Exception $e) {
        $conn->rollback();
        echo "Update failed: " . $e->getMessage();
    }

    $conn->close();
    exit;
}

$empid = $_GET['empid'];
if (empty($empid)) {
    echo "Employee ID not provided.";
    exit;
}

$stmt = $conn->prepare("
    SELECT empmaster.empid, fname, lname, address, gender, mob, dob, pincode, email, modifydatetime
    FROM empmaster
    INNER JOIN empdetails ON empmaster.empid = empdetails.empid
    INNER JOIN password ON empmaster.empid = password.empid
    WHERE empmaster.empid = ?
");

$stmt->bind_param("s", $empid);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    echo "No employee found with ID: $empid";
    exit;
}
?>


<!-- 
$stmt = $conn->prepare("
SELECT empmaster.empid, fname, lname, address, gender, mob, dob, pincode, email, modifydatetime
FROM empmaster
INNER JOIN empdetails ON empmaster.empid = empdetails.empid
INNER JOIN password ON empmaster.empid = password.empid
WHERE empmaster.empid = ?
");

$stmt->bind_param("s", $empid);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
echo "No employee found with ID: $empid";
exit;
}
?> -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Employee</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        form {
            background-color: #fff;
            padding: 25px;
            border-radius: 10px;
            width: 100%;
            max-width: 500px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
            color: #444;
        }

        input[type="text"],
        input[type="date"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        @media (max-width: 600px) {
            form {
                padding: 20px;
            }

            input,
            button {
                font-size: 14px;
            }
        }
    </style>
</head>

<body>

    <form action="update.php?empid=<?= htmlspecialchars($empid) ?>" method="POST">
        <h2>Update Employee - <?= htmlspecialchars($empid) ?></h2>

        <input type="hidden" name="empid" value="<?= htmlspecialchars($empid) ?>">

        <label>First Name:</label>
        <input type="text" name="fname" value="<?= htmlspecialchars($data['fname'] ?? '') ?>" required>

        <label>Last Name:</label>
        <input type="text" name="lname" value="<?= htmlspecialchars($data['lname'] ?? '') ?>" required>

        <label>Address:</label>
        <input type="text" name="address" value="<?= htmlspecialchars($data['address'] ?? '') ?>" required>

        <label>Gender:</label>
        <select name="gender" required>
            <option value="Male" <?= (isset($data['gender']) && $data['gender'] === 'Male') ? 'selected' : '' ?>>Male</option>
            <option value="Female" <?= (isset($data['gender']) && $data['gender'] === 'Female') ? 'selected' : '' ?>>Female</option>
        </select>

        <label>Mobile:</label>
        <img src="in.png" class="flag" alt="IND" height="15px" width="20px">
        <span class="code">+91</span>
        <input type="tel" name="mob" pattern="[6-9]{1}[0-9]{9}" maxlength="10"
            value="<?= htmlspecialchars($data['mob'] ?? '') ?>"
            required title="Valid 10-digit mobile number">

        <label>DOB:</label>
        <input type="date" name="dob" value="<?= htmlspecialchars($data['dob'] ?? '') ?>" required>

        <label>Pincode:</label>
        <input type="text" name="pincode" value="<?= htmlspecialchars($data['pincode'] ?? '') ?>"
            pattern="[0-9]{6}" maxlength="6" required>

        <label>Email:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($data['email'] ?? '') ?>" required>

        <label>Password (for confirmation):</label>
        <input type="password" name="password" required>

        <button type="submit">Update</button>
    </form>


</body>

</html>