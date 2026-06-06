<?php
session_start();

if (!isset($_SESSION['otp'])) {
    header("Location: register.php");
    exit();
}

include 'db.php';

if (isset($_POST['verify'])) {

    $userOtp = trim($_POST['otp']);

    if ($userOtp == $_SESSION['otp']) {

        $username = $_SESSION['username'];
        $email    = $_SESSION['email'];
        $phone    = $_SESSION['phone'];
        $password = $_SESSION['password'];
        $role     = $_SESSION['role'];

        $stmt = $conn->prepare("
            INSERT INTO users
            (
                username,
                email,
                phone,
                password,
                role
            )
            VALUES
            (?,?,?,?,?)
        ");

        $stmt->bind_param(
            "sssss",
            $username,
            $email,
            $phone,
            $password,
            $role
        );

        if ($stmt->execute()) {

            session_unset();
            session_destroy();

            echo "
            <script>
                alert('Registration Successful');
                window.location='login.php';
            </script>";
            exit();

        } else {

            echo "
            <script>
                alert('Database Error');
            </script>";
        }
    } else {

        echo "
        <script>
            alert('Invalid OTP');
        </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>VendorBridge | OTP Verification</title>

<link rel="stylesheet" href="otp.css">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>

<body>

<div class="otp-container">

    <div class="otp-card">

        <div class="icon">
            <i class="fa-solid fa-envelope-circle-check"></i>
        </div>

        <h2>Email Verification</h2>

        <p>Enter the OTP sent to</p>

        <div class="email-box">
            <?php echo $_SESSION['email']; ?>
        </div>

        <form method="POST">

            <div class="input-box">

                <i class="fa-solid fa-key"></i>

                <input
                    type="text"
                    name="otp"
                    maxlength="6"
                    placeholder="Enter 6 Digit OTP"
                    required>

            </div>

            <button
                type="submit"
                name="verify"
                class="verify-btn">

                Verify OTP

            </button>

        </form>

    </div>

</div>

</body>
</html>