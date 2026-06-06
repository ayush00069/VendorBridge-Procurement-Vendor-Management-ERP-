<?php
session_start();
include 'db.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-7.1.1/src/Exception.php';
require 'PHPMailer-7.1.1/src/PHPMailer.php';
require 'PHPMailer-7.1.1/src/SMTP.php';

$message = "";

if(isset($_POST['send_otp']))
{
    $email = mysqli_real_escape_string($conn,$_POST['email']);

    $check = mysqli_query(
        $conn,
        "SELECT * FROM users WHERE email='$email'"
    );

    if(mysqli_num_rows($check) > 0)
    {
        $otp = rand(100000,999999);

        $_SESSION['reset_email'] = $email;
        $_SESSION['reset_otp'] = $otp;
        $_SESSION['otp_sent'] = true;

        try
        {
            $mail = new PHPMailer(true);

            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;

            $mail->Username = 'helpdesk0699@gmail.com';
            $mail->Password = 'jrui cacs undf edcg';

            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom(
                'helpdesk0699@gmail.com',
                'Route Rover'
            );

            $mail->addAddress($email);

            $mail->isHTML(true);

            $mail->Subject = 'Password Reset OTP';

            $mail->Body = "
            <h2>Password Reset</h2>
            <p>Your OTP is:</p>
            <h1>$otp</h1>
            <p>Do not share this OTP.</p>
            ";

            $mail->send();

            $message = "OTP sent successfully.";
        }
        catch(Exception $e)
        {
            $message = "Email sending failed.";
        }
    }
    else
    {
        $message = "Email not found.";
    }
}

if(isset($_POST['verify_otp']))
{
    $user_otp = $_POST['otp'];

    if($user_otp == $_SESSION['reset_otp'])
    {
        $_SESSION['otp_verified'] = true;
        $message = "OTP Verified.";
    }
    else
    {
        $message = "Invalid OTP.";
    }
}

if(isset($_POST['change_password']))
{
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if($password != $confirm_password)
    {
        $message = "Passwords do not match.";
    }
    else
    {
        $hashed_password =
        password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        $email = $_SESSION['reset_email'];

        mysqli_query(
            $conn,
            "UPDATE users
             SET password='$hashed_password'
             WHERE email='$email'"
        );

        session_unset();
        session_destroy();

        echo "
        <script>
            alert('Password Changed Successfully');
            window.location='login.php';
        </script>";
        exit();
    }
}
include 'forgot_password_view.php';
?>

