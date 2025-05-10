# Advance-Crud-Application
This is an Advanced CRUD (Create, Read, Update, Delete) web application built using PHP and MySQL. The system is designed with data integrity, modularity, and auditability in mind. It utilizes three relational tables: empmaster, empdetails, and password to manage employee data efficiently.

🚀 Features
✅ Three-table relational structure:

empmaster – stores employee names and address.

empdetails – stores gender, mobile, DOB, and pincode.

password – handles login credentials and email.

🔄 Soft Delete Functionality:

Instead of permanently deleting records, a RowDeleted flag is used to mark entries as deleted.

This preserves data for future reference or rollback.

✏️ Smart Update Logic:

On updating a record:

The current data is preserved with RowDeleted = 1.

A new row is inserted with updated data and RowDeleted = 0.

This ensures an audit trail of all changes.

📄 Form Validations (HTML5):

Validations for mobile, email, name, pincode, etc., are enforced on the frontend.

🧑‍💼 User Interface:

Responsive UI for registration, login, dashboard, and update using HTML & CSS.

🔐 Secure Authentication:

Passwords are hashed using PHP’s password_hash() and verified with password_verify().

🗂️ Database Schema Overview
empmaster
Column	Type	Description
empid	VARCHAR(12)	Primary Key
fname	VARCHAR(50)	First Name
lname	VARCHAR(50)	Last Name
address	TEXT	Address
RowDeleted	TINYINT(1)	Soft delete flag

empdetails
Column	Type	Description
empid	VARCHAR(12)	Foreign Key
gender	ENUM('m','f')	Gender
mob	BIGINT	Mobile Number
dob	DATE	Date of Birth
pincode	INT	Postal Code
RowDeleted	TINYINT(1)	Soft delete flag
createdatetime	TIMESTAMP	Creation timestamp
modifydatetime	TIMESTAMP	Last update timestamp

password
Column	Type	Description
empid	VARCHAR(12)	Foreign Key
email	VARCHAR(100)	Email Address
password	VARCHAR(255)	Hashed Password
RowDeleted	TINYINT(1)	Soft delete flag

📦 Technologies Used
PHP (OOP)

MySQL (Relational DB)

HTML5 & CSS3 (Responsive Forms)

XAMPP / Laragon (Local Development Environment)

🛠️ Setup Instructions
Clone the repository:

bash
Copy
Edit
git clone https://github.com/your-username/advanced-php-crud.git
Import the SQL file into your MySQL database.

Configure db.php with your DB credentials.

Run the app in your local server (e.g., XAMPP or Laragon).


📌 Future Enhancements
Admin Panel with role-based access

Soft delete restoration

Pagination and search filters

Activity logs for updates

