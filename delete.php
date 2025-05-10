<?php
include 'db.php';
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);
if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $empid = $_POST['empid'];


    if (empty($empid)) {
        echo "Invalid or missing Employee ID.";
        exit;
    }

    $conn->begin_transaction();
    try {


        $tables = ['empmaster', 'empdetails', 'password'];
        foreach ($tables as $table) {
            $stmt = $conn->prepare("UPDATE $table SET RowDeleted = 1 WHERE empid = ?");
            $stmt->bind_param("s", $empid);
            $stmt->execute();
        }
        $conn->commit();
        header("Location: dashboard.php");
        exit();
    } catch (PDOException $e) {
        $conn->rollBack();
        error_log($e->getMessage());
        echo "An error occurred. Please try again later.";
    }
}
$empid = $_GET['empid'];
if (empty($empid)) {
    echo "Employee ID not provided.";
    exit;
}
$stmt = $conn->prepare("
    SELECT empmaster.empid, fname, lname, address, gender, mob, dob, pincode, email
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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Employee</title>
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

        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 400px;
            width: 100%;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        button {
            padding: 12px 20px;
            background-color: #e74c3c;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #c0392b;
        }

        @media (max-width: 500px) {
            .container {
                padding: 20px;
            }

            button {
                width: 100%;
                font-size: 14px;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>Delete Employee - <?= ($empid) ?></h2>
        <form action="delete.php?empid=<?= ($empid) ?>" method="POST">
            <input type="hidden" name="empid" value="<?=($empid) ?>">
            <button type="submit">DELETE</button>
        </form>
    </div>

</body>

</html>