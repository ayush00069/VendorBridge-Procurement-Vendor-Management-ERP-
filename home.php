
<?php
session_start();

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

$name = $_SESSION['fullname'];
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Dashboard | Route Rover</title>

<link rel="stylesheet" href="home.css">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>

<body>

<div class="blob blob1"></div>
<div class="blob blob2"></div>

<nav>

    <div class="logo">

        <i class="fa-solid fa-route"></i>

        Route Rover

    </div>

    <a href="logout.php" class="logout-btn">

        <i class="fa-solid fa-right-from-bracket"></i>

        Logout

    </a>

</nav>

<div class="container">

    <div class="welcome-card">

        <h1>
            Welcome,
            <?php echo htmlspecialchars($name); ?> 👋
        </h1>

        <p>
            Manage your transportation activities
            from one place.
        </p>

    </div>



    <div class="main-grid">

        <div class="profile-card">

            <h3>
                <i class="fa-solid fa-user"></i>
                Profile
            </h3>

            <p><strong>Name:</strong> <?php echo htmlspecialchars($name); ?></p>

            <p><strong>Status:</strong> Active</p>

            <p><strong>Role:</strong> User</p>

        </div>

        <div class="ai-card">

            <div class="ai-header">

                <i class="fa-solid fa-robot"></i>

                AI Assistant

            </div>

            <div id="chat-box">

                <div class="bot-message">
                    Hello 👋 How can I help you today?
                </div>

            </div>

            <div class="chat-input">

                <input
                type="text"
                id="message"
                placeholder="Ask AI something...">

                <button onclick="sendMessage()">

                    <i class="fa-solid fa-paper-plane"></i>

                </button>

            </div>

        </div>

    </div>

</div>

<script>

async function sendMessage()
{
    let input =
    document.getElementById("message");

    let message =
    input.value.trim();

    if(message === "")
    {
        return;
    }

    let chatBox =
    document.getElementById("chat-box");

    chatBox.innerHTML += `
    <div class="user-message">
        ${message}
    </div>`;

    input.value = "";

    chatBox.innerHTML += `
    <div class="bot-message loading">
        Thinking...
    </div>`;

    chatBox.scrollTop =
    chatBox.scrollHeight;

    try
    {
        const response =
        await fetch(
        "http://127.0.0.1:5000/chat?message=" +
        encodeURIComponent(message)
        );

        const data =
        await response.json();

        document
        .querySelector(".loading")
        .remove();

        chatBox.innerHTML += `
        <div class="bot-message">
            ${data.reply}
        </div>`;
    }
    catch(error)
    {
        document
        .querySelector(".loading")
        .remove();

        chatBox.innerHTML += `
        <div class="bot-message">
            Server Connection Failed
        </div>`;
    }

    chatBox.scrollTop =
    chatBox.scrollHeight;
}

document
.getElementById("message")
.addEventListener(
"keypress",
function(e)
{
    if(e.key === "Enter")
    {
        sendMessage();
    }
});

</script>

</body>
</html>