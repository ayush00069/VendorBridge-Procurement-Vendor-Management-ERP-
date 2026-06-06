<?php
session_start();
include 'db.php';

$error = "";

if(isset($_POST['login']))
{
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if($result->num_rows > 0)
    {
        $user = $result->fetch_assoc();

        if(password_verify($password, $user['password']))
        {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            if($user['role'] == "vendor")
            {
                header("Location: vendor_dashboard.php");
                exit();
            }
            elseif($user['role'] == "officer")
            {
                header("Location: officer_dashboard.php");
                exit();
            }
            elseif($user['role'] == "manager")
            {
                header("Location: manager_dashboard.php");
                exit();
            }
        }
        else
        {
            $error = "Invalid Password";
        }
    }
    else
    {
        $error = "Email Not Registered";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>VendorBridge ERP | Login</title>

<link rel="stylesheet" href="login.css">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>

<div class="container">

    <div class="left-panel">

        <div class="logo">
            <i class="fa-solid fa-building"></i>
        </div>

        <h1>VendorBridge</h1>

        <p>
            Procurement & Vendor Management ERP
        </p>

        <div class="features">

            <div class="feature">
                <i class="fa-solid fa-user-shield"></i>
                Role Based Access
            </div>

            <div class="feature">
                <i class="fa-solid fa-file-contract"></i>
                RFQ Management
            </div>

            <div class="feature">
                <i class="fa-solid fa-file-invoice"></i>
                Invoice & PO Tracking
            </div>

        </div>

    </div>

    <div class="right-panel">

        <div class="card">

            <h2>Welcome Back</h2>

            <p class="subtitle">
                Sign in to VendorBridge ERP
            </p>

            <form method="POST">

                <div class="input-box">
                    <i class="fa-solid fa-envelope"></i>
                    <input
                        type="email"
                        name="email"
                        placeholder="Email Address"
                        required>
                </div>

                <div class="input-box password-box">

                    <i class="fa-solid fa-lock"></i>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Password"
                        required>

                    <span
                        class="toggle-password"
                        onclick="togglePassword()">

                        <i
                            id="eyeIcon"
                            class="fa-solid fa-eye">
                        </i>

                    </span>

                </div>

                <div class="login-options">
                    <a href="forgot_password.php">
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

            <div class="register-link">

                Don't have an account?

                <a href="register.php">
                    Create Account
                </a>

            </div>

        </div>

    </div>

</div>

<?php
if(!empty($error))
{
    echo "
    <script>
    Swal.fire({
        icon:'error',
        title:'Login Failed',
        text:'$error'
    });
    </script>";
}
?>

<script>

function togglePassword()
{
    const password =
    document.getElementById("password");

    const eyeIcon =
    document.getElementById("eyeIcon");

    if(password.type === "password")
    {
        password.type = "text";
        eyeIcon.classList.replace(
            "fa-eye",
            "fa-eye-slash"
        );
    }
    else
    {
        password.type = "password";
        eyeIcon.classList.replace(
            "fa-eye-slash",
            "fa-eye"
        );
    }
}

</script>

</body>
</html>