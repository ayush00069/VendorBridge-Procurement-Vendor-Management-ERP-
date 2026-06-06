<?php
session_start();

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

if($_SESSION['role'] != 'vendor')
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

<title>Vendor Dashboard | VendorBridge</title>

<link rel="stylesheet" href="vendor.css">

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
                <a href="view_rfq.php">
                    <i class="fa-solid fa-file-circle-plus"></i>
                    Available RFQs
                </a>
            </li>

            <li>
                <a href="submit_quotation.php">
                    <i class="fa-solid fa-file-signature"></i>
                    Submit Quotation
                </a>
            </li>

            <li>
                <a href="track_quotation.php">
                    <i class="fa-solid fa-list-check"></i>
                    My Quotations
                </a>
            </li>

            <li>
                <a href="view_orders.php">
                    <i class="fa-solid fa-cart-shopping"></i>
                    Purchase Orders
                </a>
            </li>

            <li>
                <a href="invoice.php">
                    <i class="fa-solid fa-file-invoice"></i>
                    Invoices
                </a>
            </li>

            <li>
                <a href="vendor_profile.php">
                    <i class="fa-solid fa-user"></i>
                    Profile
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

            <h1>Vendor Dashboard</h1>

            <div class="user">
                Welcome,
                <b><?php echo $_SESSION['username']; ?></b>
            </div>

        </div>

        <div class="cards">

            <div class="card">
                <i class="fa-solid fa-file-circle-plus"></i>
                <h2>12</h2>
                <p>Open RFQs</p>
            </div>

            <div class="card">
                <i class="fa-solid fa-file-signature"></i>
                <h2>8</h2>
                <p>Submitted Quotes</p>
            </div>

            <div class="card">
                <i class="fa-solid fa-cart-shopping"></i>
                <h2>4</h2>
                <p>Purchase Orders</p>
            </div>

            <div class="card">
                <i class="fa-solid fa-file-invoice"></i>
                <h2>3</h2>
                <p>Invoices</p>
            </div>

        </div>

        <div class="quick-actions">

            <h2>Quick Actions</h2>

            <div class="action-grid">

                <a href="view_rfq.php" class="action-card">
                    View RFQs
                </a>

                <a href="submit_quotation.php" class="action-card">
                    Submit Quote
                </a>

                <a href="track_quotation.php" class="action-card">
                    Track Quotations
                </a>

                <a href="view_orders.php" class="action-card">
                    View Purchase Orders
                </a>

                <a href="invoice.php" class="action-card">
                    View Invoices
                </a>

                <a href="vendor_profile.php" class="action-card">
                    Update Profile
                </a>

            </div>

        </div>

        <div class="recent-section">

            <div class="recent-card">

                <h3>Latest RFQs</h3>

                <table>

                    <tr>
                        <th>RFQ ID</th>
                        <th>Item</th>
                        <th>Deadline</th>
                    </tr>

                    <tr>
                        <td>RFQ101</td>
                        <td>Laptops</td>
                        <td>25 June</td>
                    </tr>

                    <tr>
                        <td>RFQ102</td>
                        <td>Printers</td>
                        <td>30 June</td>
                    </tr>

                </table>

            </div>

            <div class="recent-card">

                <h3>Quotation Status</h3>

                <table>

                    <tr>
                        <th>Quote ID</th>
                        <th>RFQ</th>
                        <th>Status</th>
                    </tr>

                    <tr>
                        <td>QT001</td>
                        <td>RFQ101</td>
                        <td>Pending</td>
                    </tr>

                    <tr>
                        <td>QT002</td>
                        <td>RFQ102</td>
                        <td>Accepted</td>
                    </tr>

                </table>

            </div>

        </div>

    </div>

</div>

</body>
</html>