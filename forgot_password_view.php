<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f5f7fb;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .card {
            width: 400px;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-top: 0;
        }
        input {
            width: 100%;
            padding: 12px;
            margin-top: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 12px;
            border: none;
            background: #4f46e5;
            color: white;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background: #4338ca;
        }
        .message {
            text-align: center;
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<div class="card">
    <h2>Forgot Password</h2>

    <?php if (!empty($message)): ?>
        <div class="message"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <?php if (!isset($_SESSION['otp_sent'])): ?>
        <form action="forgot_password.php" method="POST">
            <input type="email" name="email" placeholder="Enter Email Address" required>
            <button type="submit" name="send_otp">Send OTP</button>
        </form>
    <?php endif; ?>

    <?php if (isset($_SESSION['otp_sent']) && !isset($_SESSION['otp_verified'])): ?>
        <form action="forgot_password.php" method="POST">
            <input type="text" name="otp" placeholder="Enter OTP" required>
            <button type="submit" name="verify_otp">Verify OTP</button>
        </form>
    <?php endif; ?>

    <?php if (isset($_SESSION['otp_verified'])): ?>
        <form action="forgot_password.php" method="POST">
            <input type="password" name="password" placeholder="New Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <button type="submit" name="change_password">Reset Password</button>
        </form>
    <?php endif; ?>
</div>

</body>
</html>