<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Dashboard</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background: #f2f2f2;
        }

        header {
            background-color: #4CAF50;
            padding: 15px 30px;
            color: white;
            text-align: center;
            font-size: 24px;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
        }

        form {
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="date"] {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        .radio-group {
            display: flex;
            gap: 10px;
            margin-top: 5px;
        }

        input[type="radio"] {
            margin-right: 5px;
        }

        input[type="submit"] {
            margin-top: 20px;
            width: 100%;
            padding: 12px;
            background: #4CAF50;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background: #45a049;
        }

        a {
            display: block;
            margin-top: 15px;
            text-align: center;
            text-decoration: none;
            color: #4CAF50;
        }

        a:hover {
            text-decoration: underline;
        }

        @media (max-width: 600px) {
            header {
                font-size: 20px;
                padding: 10px;
            }

            form {
                padding: 20px;
            }

            input[type="submit"] {
                padding: 10px;
            }
        }
    </style>
</head>

<body>

    <header>
        Employee Registration Dashboard
    </header>

    <div class="container">
        <form action="" method="POST">
            <h2>Register Employee</h2>

            <div class="form-group">
                <label>First Name:</label>
                <input type="text" name="fname" pattern="[A-Za-z]+" maxlength="30" required title="Only letters allowed.">
            </div>

            <div class="form-group">
                <label>Last Name:</label>
                <input type="text" name="lname" pattern="[A-Za-z]+" maxlength="30" required title="Only letters allowed.">
            </div>

            <div class="form-group">
                <div class="radio-group">

                    <label>Gender:</label>
                    <select name="gender" required>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
            </div>

            <div class="phone-input">
                <label>Mobile:</label>
                <img src="in.png" class="flag" alt="IND" height="15px" width="20px">
                <span class="code">+91</span>
                <input type="tel" name="mob" pattern="[6-9]{1}[0-9]{9}" maxlength="10" required title="Valid 10-digit mobile number">
            </div><br>

            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" required>
            </div>

            <div class="form-group">
                <label>DOB:</label>
                <input type="date" name="dob" required>
            </div>

            <div class="form-group">
                <label>Pincode:</label>
                <input type="text" name="pincode" pattern="[0-9]{6}" maxlength="6" required title="6-digit pincode only">
            </div>

            <div class="form-group">
                <label>Address:</label>
                <input type="text" name="address" required>
            </div>

            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" required>
            </div>

            <div class="form-group">
                <label>Confirm Password:</label>
                <input type="password" name="cpassword" required>
            </div>

            <input type="submit" value="Submit">
            <a href="login.php">Already have an account? Login</a>
        </form>
    </div>

</body>

</html>

<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $empid = ['EMP'];
    $gender = $_POST['gender'];
    $mob = $_POST['mob'];
    $dob = $_POST['dob'];
    $pincode = $_POST['pincode'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];


    $query = "SELECT empid FROM password ORDER BY empid DESC LIMIT 1";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();

    if (empty($row['empid'])) {
        $empid = 'EMP-0001';
    } else {
        $lastId = $row['empid'];
        $num = (int) substr($lastId, 4);
        $num++;
        $empid = 'EMP-' . str_pad($num, 4, '0', STR_PAD_LEFT);
    }



    if ($password !== $cpassword) {
        echo "Passwords do not match.";
        exit();
    }
    $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $conn->begin_transaction();

    try {
        $stmt1 = $conn->prepare("INSERT INTO empdetails (empid, gender, mob, dob, pincode) VALUES (?, ?, ?, ?, ?)");
        $stmt1->bind_param("sssss", $empid, $gender, $mob, $dob, $pincode);
        $stmt1->execute();


        $stmt2 = $conn->prepare("INSERT INTO empmaster (empid, fname, lname, address) VALUES (?, ?, ?, ?)");
        $stmt2->bind_param("ssss", $empid, $fname, $lname, $address);
        $stmt2->execute();

        $stmt3 = $conn->prepare("INSERT INTO password (empid, email, password) VALUES (?, ?, ?)");
        $stmt3->bind_param("sss", $empid, $email, $hashed_password);
        $stmt3->execute();

        $conn->commit();
        // header("Location: dashboard.php");



        $sql = "
        SELECT 
            empmaster.empid,
            fname, lname, address,
            gender, mob, dob, pincode,
            email
        FROM empmaster
        INNER JOIN empdetails ON empmaster.empid = empdetails.empid
        INNER JOIN password ON empmaster.empid = password.empid
        WHERE empmaster.empid = ?
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $empid);
        $stmt->execute();
        $result = $stmt->get_result();


        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
            }
        } else {
            echo "No joined data found.";
        }
    } catch (Exception $e) {
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }

    $conn->close();
}
?>
