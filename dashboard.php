
<?php
session_start();

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

include 'db.php';

/* Dashboard Statistics */

$totalVendors = 125;
$activeRFQs = 18;
$pendingApprovals = 7;
$purchaseOrders = 42;
$totalInvoices = 35;
$monthlySpend = "₹12,45,000";
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>VendorBridge ERP Dashboard</title>

<link rel="stylesheet" href="dashboard.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
rel="stylesheet">

</head>

<body>

<!-- Sidebar -->

<div class="sidebar">

    <div class="logo">

        <i class="fa-solid fa-building"></i>

        <span>VendorBridge</span>

    </div>

    <ul>

        <li class="active">
            <a href="#">
                <i class="fa-solid fa-chart-line"></i>
                Dashboard
            </a>
        </li>

        <li>
            <a href="vendors.php">
                <i class="fa-solid fa-users"></i>
                Vendors
            </a>
        </li>

        <li>
            <a href="rfq.php">
                <i class="fa-solid fa-file-contract"></i>
                RFQs
            </a>
        </li>

        <li>
            <a href="quotations.php">
                <i class="fa-solid fa-scale-balanced"></i>
                Quotations
            </a>
        </li>

        <li>
            <a href="approvals.php">
                <i class="fa-solid fa-circle-check"></i>
                Approvals
            </a>
        </li>

        <li>
            <a href="purchase_orders.php">
                <i class="fa-solid fa-cart-shopping"></i>
                Purchase Orders
            </a>
        </li>

   

        <li>
            <a href="reports.php">
                <i class="fa-solid fa-chart-pie"></i>
                Reports
            </a>
        </li>

        <li>
            <a href="login.php">
                <i class="fa-solid fa-right-from-bracket"></i>
                Logout
            </a>
        </li>

    </ul>

</div>

<!-- Main Content -->

<div class="main-content">

    <!-- Top Bar -->

    <div class="topbar">

        <div>

            <h1>Dashboard</h1>

            <p>
                Welcome back,
                <?php echo $_SESSION['fullname']; ?>
            </p>

        </div>

        <div class="profile">

            <i class="fa-solid fa-user-circle"></i>

        </div>

    </div>

    <!-- Statistics Cards -->

    <div class="cards">

        <div class="card">

            <i class="fa-solid fa-users"></i>

            <h2><?php echo $totalVendors; ?></h2>

            <p>Total Vendors</p>

        </div>

        <div class="card">

            <i class="fa-solid fa-file-contract"></i>

            <h2><?php echo $activeRFQs; ?></h2>

            <p>Active RFQs</p>

        </div>

        <div class="card">

            <i class="fa-solid fa-circle-check"></i>

            <h2><?php echo $pendingApprovals; ?></h2>

            <p>Pending Approvals</p>

        </div>

        <div class="card">

            <i class="fa-solid fa-cart-shopping"></i>

            <h2><?php echo $purchaseOrders; ?></h2>

            <p>Purchase Orders</p>

        </div>

        <div class="card">

            <i class="fa-solid fa-file-invoice-dollar"></i>

            <h2><?php echo $totalInvoices; ?></h2>

            <p>Invoices</p>

        </div>

        <div class="card">

            <i class="fa-solid fa-indian-rupee-sign"></i>

            <h2><?php echo $monthlySpend; ?></h2>

            <p>Monthly Spend</p>

        </div>

    </div>

    <!-- Quick Actions -->

    

    <!-- Recent RFQs -->

    <div class="section">

        <h2>Recent RFQs</h2>

        <table>

            <tr>
                <th>RFQ No</th>
                <th>Title</th>
                <th>Deadline</th>
                <th>Status</th>
            </tr>

            <tr>
                <td>RFQ-1001</td>
                <td>Office Laptops</td>
                <td>15 Jul 2026</td>
                <td><span class="status open">Open</span></td>
            </tr>

            <tr>
                <td>RFQ-1002</td>
                <td>Network Switches</td>
                <td>18 Jul 2026</td>
                <td><span class="status pending">Pending</span></td>
            </tr>

        </table>

    </div>

    <!-- Recent Activity -->

    <div class="section">

        <h2>Recent Activity</h2>

        <div class="activity">

            <p>✔ RFQ-1001 created by Procurement Officer</p>

            <p>✔ Vendor ABC submitted quotation</p>

            <p>✔ Purchase Order PO-2026-004 approved</p>

            <p>✔ Invoice INV-2026-011 generated</p>

        </div>

    </div>

</div>

</body>
</html>