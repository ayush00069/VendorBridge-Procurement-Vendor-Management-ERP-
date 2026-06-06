<?php

include 'db.php';

$password =
password_hash(
'Admin@123',
PASSWORD_DEFAULT
);

mysqli_query($conn,"
INSERT INTO users
(
fullname,
username,
email,
phone,
password,
role,
status
)
VALUES
(
'System Administrator',
'admin',
'admin@vendorbridge.com',
'9876543210',
'$password',
'admin',
'active'
)
");

echo "Admin Created Successfully";
?>