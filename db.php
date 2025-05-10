<!-- <?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "crud_app";

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

?> -->
<?php
$servername = "localhost";
$username = "root"; // adjust as needed
$password = "";
$dbname = "crud_app"; // replace with your actual DB name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL to create empmaster table
$sql1 = "CREATE TABLE IF NOT EXISTS empmaster (
    empid VARCHAR(10) NOT NULL PRIMARY KEY,
    fname VARCHAR(50),
    lname VARCHAR(50),
    address TEXT,
    RowDeleted TINYINT(1) DEFAULT 0
)";

// SQL to create empdetails table
$sql2 = "CREATE TABLE IF NOT EXISTS empdetails (
    empid VARCHAR(10) NOT NULL,
    gender VARCHAR(10),
    mob VARCHAR(10) UNIQUE,
    dob DATE,
    pincode VARCHAR(6),
    RowDeleted TINYINT(1) DEFAULT 0,
    PRIMARY KEY (empid),
    FOREIGN KEY (empid) REFERENCES empmaster(empid)
)";

// SQL to create password table
$sql3 = "CREATE TABLE IF NOT EXISTS password (
    empid VARCHAR(10) NOT NULL,
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    RowDeleted TINYINT(1) DEFAULT 0,
    PRIMARY KEY (empid),
    FOREIGN KEY (empid) REFERENCES empmaster(empid)
)";

// Execute queries
if ($conn->query($sql1) === TRUE) {
    
} else {
    echo "Error creating empmaster: " . $conn->error . "<br>";
}

if ($conn->query($sql2) === TRUE) {
    
} else {
    echo "Error creating empdetails: " . $conn->error . "<br>";
}

if ($conn->query($sql3) === TRUE) {
    
} else {
    echo "Error creating password: " . $conn->error . "<br>";
}

?>
