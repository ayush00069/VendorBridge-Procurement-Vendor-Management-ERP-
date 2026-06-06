<?php
session_start();

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

if($_SESSION['role'] != 'officer')
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

<title>Officer Dashboard | VendorBridge</title>

<link rel="stylesheet" href="officer.css">

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
                <a href="create_rfq.php">
                    <i class="fa-solid fa-file-circle-plus"></i>
                    Create RFQ
                </a>
            </li>

            <li>
                <a href="manage_vendor.php">
                    <i class="fa-solid fa-users"></i>
                    Vendors
                </a>
            </li>

            <li>
                <a href="quotation_comparison.php">
                    <i class="fa-solid fa-scale-balanced"></i>
                    Quotations
                </a>
            </li>

            <li>
                <a href="view_purchase_orders.php">
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
                <a href="officer_reports.php">
                    <i class="fa-solid fa-chart-line"></i>
                    Reports
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

            <h1>
                Procurement Officer Dashboard
            </h1>

            <div class="user">

                Welcome,
                <b>
                    <?php echo $_SESSION['username']; ?>
                </b>

            </div>

        </div>

        <div class="cards">

            <div class="card">
                <i class="fa-solid fa-file-circle-plus"></i>
                <h2>18</h2>
                <p>Active RFQs</p>
            </div>

            <div class="card">
                <i class="fa-solid fa-users"></i>
                <h2>65</h2>
                <p>Registered Vendors</p>
            </div>

            <div class="card">
                <i class="fa-solid fa-cart-shopping"></i>
                <h2>24</h2>
                <p>Purchase Orders</p>
            </div>

            <div class="card">
                <i class="fa-solid fa-file-invoice"></i>
                <h2>32</h2>
                <p>Invoices Generated</p>
            </div>

        </div>

        <div class="quick-actions">

            <h2>Quick Actions</h2>

            <div class="action-grid">

                <a href="create_rfq.php" class="action-card">
                    Create RFQ
                </a>

                <a href="manage_vendor.php" class="action-card">
                    Manage Vendors
                </a>

                <a href="quotation_comparison.php" class="action-card">
                    Compare Quotes
                </a>

                <a href="view_purchase_orders.php" class="action-card">
    View Purchase Orders
</a>

               <a href="view_invoices.php" class="action-card">
    View Invoices
</a>

                <a href="officer_reports.php" class="action-card">
                    View Reports
                </a>

            </div>

        </div>

        <div class="recent-section">

            <div class="recent-card">

                <h3>Recent RFQs</h3>

                <table>

                    <tr>
                        <th>RFQ ID</th>
                        <th>Item</th>
                        <th>Status</th>
                    </tr>

                    <tr>
                        <td>RFQ001</td>
                        <td>Laptops</td>
                        <td>Open</td>
                    </tr>

                    <tr>
                        <td>RFQ002</td>
                        <td>Printers</td>
                        <td>Open</td>
                    </tr>

                </table>

            </div>

            <div class="recent-card">

                <h3>Recent Purchase Orders</h3>

                <table>

                    <tr>
                        <th>PO ID</th>
                        <th>Vendor</th>
                        <th>Amount</th>
                    </tr>

                    <tr>
                        <td>PO101</td>
                        <td>ABC Pvt Ltd</td>
                        <td>₹75,000</td>
                    </tr>

                    <tr>
                        <td>PO102</td>
                        <td>XYZ Traders</td>
                        <td>₹45,000</td>
                    </tr>

                </table>

            </div>

        </div>

    </div>

</div>

</body>
</html>