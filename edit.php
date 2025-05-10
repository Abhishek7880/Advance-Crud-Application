<?php
include 'db.php';
$empid = $_GET['EMP'];

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET['EMP']) || empty($_GET['EMP'])) {
        echo "Employee ID not provided.";
        exit;
    }




    $stmt = $conn->prepare("
    SELECT empmaster.empid, fname, lname, address,
           gender, mob, dob, pincode, email
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
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Update Employee</title>
</head>

<body>
    <h2>Update Employee - <?= ($empid) ?></h2>
    <form action="update.php" method="POST" onSubmit=(begin_transaction())>
        <input type="hidden" name="empid" value="<?php echo 'EMP0001' ?>">

        First Name: <input type="text" name="fname" value="<?= ($data['fname']) ?>"><br>
        Last Name: <input type="text" name="lname" value="<?= ($data['lname']) ?>"><br>
        Address: <input type="text" name="address" value="<?= ($data['address']) ?>"><br>
        Gender: <input type="text" name="gender" value="<?= ($data['gender']) ?>"><br>
        Mobile: <input type="text" name="mob" value="<?= ($data['mob']) ?>"><br>
        DOB: <input type="date" name="dob" value="<?= ($data['dob']) ?>"><br>
        Pincode: <input type="text" name="pincode" value="<?= ($data['pincode']) ?>"><br>
        Email: <input type="email" name="email" value="<?= ($data['email']) ?>"><br>

        <button type="submit" value="Update">
    </form>
</body>

</html>