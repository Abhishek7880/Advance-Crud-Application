<?php
include 'db.php';

$sql = "
SELECT 
    empmaster.empid,
    fname, lname, address,
    gender, mob, dob, pincode,
    email,modifydatetime,createdatetime
FROM empmaster
INNER JOIN empdetails ON empmaster.empid = empdetails.empid
INNER JOIN password ON empmaster.empid = password.empid WHERE empmaster.RowDeleted=0 and empdetails.RowDeleted=0 and password.RowDeleted=0
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .top-links {
            text-align: center;
            margin-bottom: 20px;
        }

        .top-links a {
            text-decoration: none;
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            margin: 0 10px;
            border-radius: 6px;
            display: inline-block;
        }

        .top-links a:hover {
            background-color: #45a049;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow-x: auto;
        }

        th,
        td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ccc;
        }

        th {
            background-color: #f2f2f2;
            color: #333;
        }

        button {
            padding: 6px 12px;
            margin: 2px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            color: white;
        }

        button:hover {
            opacity: 0.9;
        }

        .update-btn {
            background-color: #007bff;
        }

        .delete-btn {
            background-color: #dc3545;
        }

        @media (max-width: 768px) {
            table {
                font-size: 14px;
            }

            th,
            td {
                padding: 8px;
            }

            .top-links a {
                padding: 8px 15px;
                margin: 5px;
            }

            button {
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <h2>Employee Dashboard</h2>

    <div class="top-links">
        <a href="register.php">Register</a>
        <a href="logout.php">Logout</a>
    </div>

    <table>
        <tr>
            <th>EmpID</th>
            <th>Name</th>
            <th>Gender</th>
            <th>Mobile</th>
            <th>DOB</th>
            <th>Pincode</th>
            <th>Address</th>
            <th>Email</th>
            <th>CreateDateTime</th>
            <th>ModifyDateTime</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?= $row['empid'] ?></td>
                <td><?= $row['fname'] . " " . $row['lname'] ?></td>
                <td><?= $row['gender'] ?></td>
                <td><?= $row['mob'] ?></td>
                <td><?= $row['dob'] ?></td>
                <td><?= $row['pincode'] ?></td>
                <td><?= $row['address'] ?></td>
                <td><?= $row['email'] ?></td>
                <td><?= $row['createdatetime'] ?></td>
                <td><?= $row['modifydatetime'] ?></td>
                <td>
                    <button class="update-btn" onclick="location.href='update.php?empid=<?= $row['empid']; ?>'">Update</button>
                    <button class="delete-btn" onclick="location.href='delete.php?empid=<?= $row['empid']; ?>'">Delete</button>
                </td>
            </tr>
        <?php } ?>
    </table>
</body>

</html>