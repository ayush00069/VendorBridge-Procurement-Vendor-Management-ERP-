
<?php
session_start();
include 'db.php';

$msg = "";

if(isset($_POST['login']))
{
    $email = mysqli_real_escape_string($conn,$_POST['email']);
    $password = $_POST['password'];

    $query = mysqli_query(
        $conn,
        "SELECT * FROM users WHERE email='$email'"
    );

    if(mysqli_num_rows($query) > 0)
    {
        $user = mysqli_fetch_assoc($query);

        if(password_verify($password,$user['password']))
        {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['fullname'] = $user['fullname'];
            $_SESSION['role'] = $user['role'];

            header("Location: dashboard.php");
            exit();
        }
        else
        {
            $msg = "Invalid Password";
        }
    }
    else
    {
        $msg = "Account Not Found";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>VendorBridge ERP Admin Login</title>

<link rel="stylesheet" href="login.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
rel="stylesheet">

</head>

<body>

<div class="blob blob1"></div>
<div class="blob blob2"></div>

<div class="container">

    <div class="left-panel">

        <div class="logo">
            <i class="fa-solid fa-building"></i>
        </div>

        <div class="brand">

            <h1>VendorBridge</h1>

            <p>
                Procurement & Vendor Management ERP
            </p>

        </div>

        <div class="features">

            <div class="feature">
                <i class="fa-solid fa-users"></i>
                Vendor Management
            </div>

            <div class="feature">
                <i class="fa-solid fa-file-contract"></i>
                RFQ Management
            </div>

            <div class="feature">
                <i class="fa-solid fa-scale-balanced"></i>
                Quotation Comparison
            </div>

            <div class="feature">
                <i class="fa-solid fa-cart-shopping"></i>
                Purchase Orders
            </div>

            <div class="feature">
                <i class="fa-solid fa-file-invoice-dollar"></i>
                Invoice Generation
            </div>

        </div>

    </div>

    <div class="right-panel">

        <div class="card">

            <div class="card-logo">

                <i class="fa-solid fa-warehouse"></i>

            </div>

            <h2>Welcome Back Admin</h2>

            <p class="subtitle">

                Sign in to continue to VendorBridge ERP

            </p>

            <?php if($msg!=""){ ?>

            <div class="error">

                <?php echo $msg; ?>

            </div>

            <?php } ?>

            <form method="POST">

                <div class="input-box">

                    <i class="fa-solid fa-envelope left-icon"></i>

                    <input
                    type="email"
                    name="email"
                    placeholder="Email Address"
                    required>

                </div>

                <div class="input-box">

                    <i class="fa-solid fa-lock left-icon"></i>

                    <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Password"
                    required>

                    <button
                    type="button"
                    class="toggle-password"
                    onclick="togglePassword()">

                        <i
                        id="eyeIcon"
                        class="fa-solid fa-eye"></i>

                    </button>

                </div>

                <div class="options">

                    <label>

                        <input type="checkbox">

                        Remember Me

                    </label>

                    <a href="#">

                        Forgot Password?

                    </a>

                </div>

                <button
                type="submit"
                name="login"
                class="btn">

                    Sign In

                </button>

            </form>

            <div class="footer-text">

                VendorBridge ERP © 2026

            </div>

        </div>

    </div>

</div>

<script>

function togglePassword()
{
    const password =
    document.getElementById("password");

    const eyeIcon =
    document.getElementById("eyeIcon");

    if(password.type==="password")
    {
        password.type="text";

        eyeIcon.classList.remove("fa-eye");
        eyeIcon.classList.add("fa-eye-slash");
    }
    else
    {
        password.type="password";

        eyeIcon.classList.remove("fa-eye-slash");
        eyeIcon.classList.add("fa-eye");
    }
}

</script>

</body>
</html>