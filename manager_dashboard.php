<?php
session_start();

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

if($_SESSION['role'] != 'manager')
{
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Manager Dashboard | VendorBridge</title>

<link rel="stylesheet" href="manager.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>

<body>

<div class="container">

    <div class="sidebar">

        <div class="logo">
            <i class="fa-solid fa-building"></i>
            <h2>VendorBridge</h2>
        </div>

        <ul>

            <li class="active">
                <a href="#">
                    <i class="fa-solid fa-house"></i>
                    Dashboard
                </a>
            </li>

            <li>
                <a href="review_req.php">
                    <i class="fa-solid fa-circle-check"></i>
                    Approvals
                </a>
            </li>

            <li>
                <a href="approval_history.php">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                    Approval History
                </a>
            </li>

            <li>
                <a href="monitor_workflow.php">
                    <i class="fa-solid fa-diagram-project"></i>
                    Workflow Monitor
                </a>
            </li>

            <li>
                <a href="pro_ana.php">
                    <i class="fa-solid fa-chart-line"></i>
                    Analytics
                </a>
            </li>

            <li>
                <a href="manager_activity.php">
                    <i class="fa-solid fa-list-check"></i>
                    Activity Logs
                </a>
            </li>

            <li>
                <a href="logout.php">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    Logout
                </a>
            </li>

        </ul>

    </div>

    <div class="main">

        <div class="topbar">

            <h1>Manager Dashboard</h1>

            <div class="user">
                Welcome,
                <b><?php echo $_SESSION['username']; ?></b>
            </div>

        </div>

        <div class="cards">

            <div class="card">
                <i class="fa-solid fa-hourglass-half"></i>
                <h2>15</h2>
                <p>Pending Approvals</p>
            </div>

            <div class="card">
                <i class="fa-solid fa-circle-check"></i>
                <h2>120</h2>
                <p>Approved Requests</p>
            </div>

            <div class="card">
                <i class="fa-solid fa-file-circle-xmark"></i>
                <h2>8</h2>
                <p>Rejected Requests</p>
            </div>

            <div class="card">
                <i class="fa-solid fa-chart-column"></i>
                <h2>₹18.5L</h2>
                <p>Total Procurement</p>
            </div>

        </div>

        <div class="quick-actions">

            <h2>Quick Actions</h2>

            <div class="action-grid">

                <a href="review_req.php" class="action-card">
                    Review Requests
                </a>

                <a href="monitor_workflow.php" class="action-card">
                    Monitor Workflow
                </a>

                <a href="pro_ana.php" class="action-card">
                    Procurement Analytics
                </a>

                <a href="manager_activity.php" class="action-card">
                    Activity Logs
                </a>

                <a href="approval_history.php" class="action-card">
                    Approval History
                </a>

                <a href="reports.php" class="action-card">
                    View Reports
                </a>

            </div>

        </div>

        <div class="recent-section">

            <div class="recent-card">

                <h3>Pending Approval Requests</h3>

                <table>

                    <tr>
                        <th>Request ID</th>
                        <th>Department</th>
                        <th>Status</th>
                    </tr>

                    <tr>
                        <td>APR001</td>
                        <td>IT</td>
                        <td>Pending</td>
                    </tr>

                    <tr>
                        <td>APR002</td>
                        <td>Admin</td>
                        <td>Pending</td>
                    </tr>

                    <tr>
                        <td>APR003</td>
                        <td>HR</td>
                        <td>Pending</td>
                    </tr>

                </table>

            </div>

            <div class="recent-card">

                <h3>Recent Activities</h3>

                <table>

                    <tr>
                        <th>Activity</th>
                        <th>Date</th>
                    </tr>

                    <tr>
                        <td>RFQ Approved</td>
                        <td>20 Jun</td>
                    </tr>

                    <tr>
                        <td>PO Approved</td>
                        <td>19 Jun</td>
                    </tr>

                    <tr>
                        <td>Invoice Verified</td>
                        <td>18 Jun</td>
                    </tr>

                </table>

            </div>

        </div>

    </div>

</div>

</body>
</html>