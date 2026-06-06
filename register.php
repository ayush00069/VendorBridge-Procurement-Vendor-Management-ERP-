<?php
session_start();
include 'db.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-7.1.1/src/Exception.php';
require 'PHPMailer-7.1.1/src/PHPMailer.php';
require 'PHPMailer-7.1.1/src/SMTP.php';

if(isset($_POST['register']))
{
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $phone    = trim($_POST['phone']);
    $role     = trim($_POST['role']);

    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if($password != $confirm_password)
    {
        echo "<script>
        alert('Passwords do not match!');
        window.location='register.php';
        </script>";
        exit();
    }

    $check = $conn->prepare(
        "SELECT id FROM users WHERE email=? OR username=?"
    );

    $check->bind_param("ss",$email,$username);
    $check->execute();

    $result = $check->get_result();

    if($result->num_rows > 0)
    {
        echo "<script>
        alert('Email or Username already exists!');
        window.location='register.php';
        </script>";
        exit();
    }

    $hashed_password =
    password_hash($password,PASSWORD_DEFAULT);

    $otp = rand(100000,999999);

    $_SESSION['otp'] = $otp;
    $_SESSION['username'] = $username;
    $_SESSION['email'] = $email;
    $_SESSION['phone'] = $phone;
    $_SESSION['password'] = $hashed_password;
    $_SESSION['role'] = $role;

    $mail = new PHPMailer(true);

    try{

        $mail->isSMTP();
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPAuth = true;

        $mail->Username =
        "helpdesk0699@gmail.com";

        $mail->Password =
        "jrui cacs undf edcg";

        $mail->SMTPSecure =
        PHPMailer::ENCRYPTION_STARTTLS;

        $mail->Port = 587;

        $mail->setFrom(
        "helpdesk0699@gmail.com",
        "VendorBridge ERP"
        );

        $mail->addAddress(
        $email,
        $username
        );

        $mail->isHTML(true);

        $mail->Subject =
        "VendorBridge Email Verification OTP";

        $mail->Body = "

        <div style='font-family:Poppins'>

            <h2>VendorBridge Verification</h2>

            <p>Hello <b>$username</b>,</p>

            <p>Your OTP for account verification is:</p>

            <h1 style='color:#4CAF50'>
            $otp
            </h1>

            <p>This OTP is valid for 10 minutes.</p>

            <p>
            Selected Role:
            <b>$role</b>
            </p>

        </div>
        ";

        $mail->send();

        header("Location: otp.php");
        exit();

    }
    catch(Exception $e)
    {
        echo "
        <script>
        alert('OTP Email Failed To Send');
        window.location='register.php';
        </script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">
<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>VendorBridge Registration</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Poppins',sans-serif;
}

body{
min-height:100vh;
display:flex;
justify-content:center;
align-items:center;
background:linear-gradient(
135deg,
#5B21B6,
#7C3AED,
#A855F7
);
padding:20px;
}

.container{
width:1000px;
display:flex;
background:#fff;
border-radius:25px;
overflow:hidden;
box-shadow:0 20px 40px rgba(0,0,0,.2);
}

.left-panel{
width:40%;
background:#5B21B6;
color:white;
padding:50px 30px;
display:flex;
flex-direction:column;
justify-content:center;
}

.logo{
font-size:60px;
margin-bottom:15px;
}

.left-panel h1{
font-size:35px;
margin-bottom:10px;
}

.left-panel p{
opacity:.9;
}

.features{
margin-top:40px;
}

.feature{
margin-bottom:20px;
font-size:18px;
}

.feature i{
margin-right:10px;
}

.right-panel{
width:60%;
display:flex;
justify-content:center;
align-items:center;
padding:40px;
}

.card{
width:100%;
max-width:450px;
}

.card h2{
text-align:center;
margin-bottom:10px;
}

.subtitle{
text-align:center;
color:#666;
margin-bottom:25px;
}

.input-box{
display:flex;
align-items:center;
border:1px solid #ddd;
padding:12px;
border-radius:12px;
margin-bottom:15px;
}

.input-box i{
color:#7C3AED;
margin-right:10px;
}

.input-box input,
.input-box select{
width:100%;
border:none;
outline:none;
font-size:15px;
background:transparent;
}

.btn{
width:100%;
padding:14px;
border:none;
background:#7C3AED;
color:white;
font-size:16px;
font-weight:600;
border-radius:12px;
cursor:pointer;
transition:.3s;
}

.btn:hover{
background:#5B21B6;
}

.login-link{
text-align:center;
margin-top:15px;
}

.login-link a{
text-decoration:none;
color:#7C3AED;
font-weight:600;
}

@media(max-width:768px)
{
.container{
flex-direction:column;
}

.left-panel,
.right-panel{
width:100%;
}
}

</style>

</head>

<body>

<div class="container">

<div class="left-panel">

<div class="logo">
<i class="fa-solid fa-building"></i>
</div>

<h1>VendorBridge</h1>

<p>
Procurement & Vendor
Management ERP
</p>

<div class="features">

<div class="feature">
<i class="fa-solid fa-users"></i>
Vendor Management
</div>

<div class="feature">
<i class="fa-solid fa-file-contract"></i>
RFQ & Quotations
</div>

<div class="feature">
<i class="fa-solid fa-file-invoice"></i>
Invoices & Purchase Orders
</div>

</div>

</div>

<div class="right-panel">

<div class="card">

<h2>Create Account</h2>

<p class="subtitle">
Join VendorBridge ERP
</p>

<form method="POST">

<div class="input-box">
<i class="fa-solid fa-at"></i>
<input type="text"
name="username"
placeholder="Username"
required>
</div>

<div class="input-box">
<i class="fa-solid fa-envelope"></i>
<input type="email"
name="email"
placeholder="Email Address"
required>
</div>

<div class="input-box">
<i class="fa-solid fa-phone"></i>
<input type="text"
name="phone"
placeholder="Phone Number"
required>
</div>

<div class="input-box">
<i class="fa-solid fa-user-tag"></i>

<select name="role" required>

<option value="">
Select Role
</option>

<option value="vendor">
Vendor
</option>

<option value="officer">
Procurement Officer
</option>

<option value="manager">
Manager / Approver
</option>

</select>

</div>

<div class="input-box">
<i class="fa-solid fa-lock"></i>
<input type="password"
name="password"
placeholder="Password"
required>
</div>

<div class="input-box">
<i class="fa-solid fa-lock"></i>
<input type="password"
name="confirm_password"
placeholder="Confirm Password"
required>
</div>

<button type="submit"
name="register"
class="btn">
Create Account
</button>

</form>

<div class="login-link">

Already have an account?

<a href="login.php">
Login
</a>

</div>

</div>

</div>

</div>

</body>
</html>