<?php
session_start();

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

include 'db.php';

$page = "reports";

$totalVendors = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM vendors"));
$totalRFQs = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM rfqs"));
$totalQuotations = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM quotations"));
$totalPOs = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM purchase_orders"));
$totalInvoices = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM invoices"));

$revenueData = mysqli_fetch_assoc(
mysqli_query(
$conn,
"SELECT SUM(total_amount) AS revenue FROM invoices"
)
);

$revenue = $revenueData['revenue'] ?? 0;

$vendors = mysqli_query(
$conn,
"SELECT * FROM vendors ORDER BY rating DESC"
);
?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<title>Reports & Analytics</title>

<link rel="stylesheet" href="dashboard.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>

<?php include 'sidebar.php'; ?>

<div class="main-content">

    <div class="topbar">

        <div>

            <h1>Reports & Analytics</h1>

            <p>
                Procurement performance insights and business analytics
            </p>

        </div>

        <div class="profile">

            <i class="fa-solid fa-chart-pie"></i>

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

            <h2><?php echo $totalRFQs; ?></h2>

            <p>Total RFQs</p>

        </div>

        <div class="card">

            <i class="fa-solid fa-scale-balanced"></i>

            <h2><?php echo $totalQuotations; ?></h2>

            <p>Quotations</p>

        </div>

        <div class="card">

            <i class="fa-solid fa-cart-shopping"></i>

            <h2><?php echo $totalPOs; ?></h2>

            <p>Purchase Orders</p>

        </div>

        <div class="card">

            <i class="fa-solid fa-file-invoice"></i>

            <h2><?php echo $totalInvoices; ?></h2>

            <p>Invoices</p>

        </div>

        <div class="card">

            <i class="fa-solid fa-indian-rupee-sign"></i>

            <h2>₹<?php echo number_format($revenue); ?></h2>

            <p>Total Revenue</p>

        </div>

    </div>

    <!-- Charts -->

    <div class="chart-grid">

        <div class="section">

            <h2>Monthly Procurement Trend</h2>

            <canvas id="trendChart"></canvas>

        </div>

        <div class="section">

            <h2>Module Distribution</h2>

            <canvas id="distributionChart"></canvas>

        </div>

    </div>

    <!-- Vendor Analytics -->

    <div class="section">

        <div class="section-header">

            <div>

                <h2>Vendor Performance</h2>

                <p class="section-subtitle">

                    Vendor rating and status analysis

                </p>

            </div>

        </div>

        <table>

            <thead>

            <tr>

                <th>Vendor</th>
                <th>Category</th>
                <th>Rating</th>
                <th>Status</th>

            </tr>

            </thead>

            <tbody>

            <?php while($vendor=mysqli_fetch_assoc($vendors)){ ?>

            <tr>

                <td>

                    <?php echo $vendor['vendor_name']; ?>

                </td>

                <td>

                    <?php echo $vendor['vendor_category']; ?>

                </td>

                <td>

                    ⭐ <?php echo $vendor['rating']; ?>

                </td>

                <td>

                    <?php if($vendor['status']=="active"){ ?>

                    <span class="status approved">

                        Active

                    </span>

                    <?php } else { ?>

                    <span class="status rejected">

                        Inactive

                    </span>

                    <?php } ?>

                </td>

            </tr>

            <?php } ?>

            </tbody>

        </table>

    </div>

    <!-- Export Actions -->

    <div class="section">

        <h2>Export Reports</h2>

        <div class="quick-actions">

            <a href="#"
            class="action-btn">

                <i class="fa-solid fa-file-excel"></i>

                Export Excel

            </a>

            <a href="#"
            class="action-btn">

                <i class="fa-solid fa-file-pdf"></i>

                Export PDF

            </a>

            <a href="#"
            class="action-btn">

                <i class="fa-solid fa-print"></i>

                Print Report

            </a>

        </div>

    </div>

</div>

<script>

/* Line Chart */

new Chart(
document.getElementById('trendChart'),
{
type:'line',

data:{
labels:[
'Jan',
'Feb',
'Mar',
'Apr',
'May',
'Jun'
],

datasets:[{
label:'Procurement Requests',
data:[12,18,15,22,30,40],
borderColor:'#2563eb',
backgroundColor:'rgba(37,99,235,.15)',
fill:true,
tension:.4
}]
},

options:{
responsive:true
}
}
);

/* Doughnut Chart */

new Chart(
document.getElementById('distributionChart'),
{
type:'doughnut',

data:{
labels:[
'RFQs',
'Quotations',
'Purchase Orders',
'Invoices'
],

datasets:[{
data:[
<?php echo $totalRFQs; ?>,
<?php echo $totalQuotations; ?>,
<?php echo $totalPOs; ?>,
<?php echo $totalInvoices; ?>
],

backgroundColor:[
'#2563eb',
'#0ea5e9',
'#8b5cf6',
'#10b981'
]
}]
},

options:{
responsive:true
}
}
);

</script>

</body>
</html>