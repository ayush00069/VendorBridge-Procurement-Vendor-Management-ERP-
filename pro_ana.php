<?php
session_start();
include 'db.php';

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

/* DASHBOARD COUNTS */

$totalRFQ = mysqli_fetch_assoc(
mysqli_query($conn,
"SELECT COUNT(*) total FROM rfq")
)['total'];

$approvedRFQ = mysqli_fetch_assoc(
mysqli_query($conn,
"SELECT COUNT(*) total
FROM rfq
WHERE status='approved'")
)['total'];

$rejectedRFQ = mysqli_fetch_assoc(
mysqli_query($conn,
"SELECT COUNT(*) total
FROM rfq
WHERE status='rejected'")
)['total'];

$totalQuotations = mysqli_fetch_assoc(
mysqli_query($conn,
"SELECT COUNT(*) total
FROM quotations")
)['total'];

$totalPO = mysqli_fetch_assoc(
mysqli_query($conn,
"SELECT COUNT(*) total
FROM purchase_orders")
)['total'];

$totalVendors = mysqli_fetch_assoc(
mysqli_query($conn,
"SELECT COUNT(*) total
FROM vendors")
)['total'];

$totalValue = mysqli_fetch_assoc(
mysqli_query($conn,
"SELECT SUM(total_amount) total
FROM purchase_orders")
);

$procurementValue =
$totalValue['total'] ?? 0;

/* TOP VENDORS */

$topVendors = mysqli_query(
$conn,
"
SELECT
v.company_name,
COUNT(po.po_id) total_orders,
SUM(po.total_amount) total_value

FROM purchase_orders po

INNER JOIN vendors v
ON po.vendor_id=v.vendor_id

GROUP BY po.vendor_id

ORDER BY total_orders DESC

LIMIT 5
"
);

/* RECENT ACTIVITIES */

$recentPO = mysqli_query(
$conn,
"
SELECT
po.po_number,
po.total_amount,
po.status,
v.company_name

FROM purchase_orders po

INNER JOIN vendors v
ON po.vendor_id=v.vendor_id

ORDER BY po.created_at DESC

LIMIT 10
"
);
?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0">

<title>
Procurement Analytics
</title>

<link
rel="stylesheet"
href="pro_ana.css">

<link
href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
rel="stylesheet">

<link
rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>

<body>

<div class="container">

<div class="header">

<h1>

<i class="fa-solid fa-chart-pie"></i>

Procurement Analytics

</h1>

<p>

Manager Procurement Performance Dashboard

</p>

</div>

<!-- CARDS -->

<div class="cards">

<div class="card">
<i class="fa-solid fa-file-circle-plus"></i>
<h2><?php echo $totalRFQ; ?></h2>
<p>Total RFQs</p>
</div>

<div class="card green">
<i class="fa-solid fa-circle-check"></i>
<h2><?php echo $approvedRFQ; ?></h2>
<p>Approved RFQs</p>
</div>

<div class="card red">
<i class="fa-solid fa-circle-xmark"></i>
<h2><?php echo $rejectedRFQ; ?></h2>
<p>Rejected RFQs</p>
</div>

<div class="card blue">
<i class="fa-solid fa-file-signature"></i>
<h2><?php echo $totalQuotations; ?></h2>
<p>Quotations</p>
</div>

<div class="card purple">
<i class="fa-solid fa-file-contract"></i>
<h2><?php echo $totalPO; ?></h2>
<p>Purchase Orders</p>
</div>

<div class="card orange">
<i class="fa-solid fa-indian-rupee-sign"></i>
<h2>₹<?php echo number_format($procurementValue); ?></h2>
<p>Total Procurement</p>
</div>

</div>

<!-- TOP VENDORS -->

<div class="section">

<h2>

<i class="fa-solid fa-trophy"></i>

Top Vendors

</h2>

<table>

<tr>
<th>Vendor</th>
<th>Orders</th>
<th>Total Value</th>
</tr>

<?php
while($vendor=mysqli_fetch_assoc($topVendors))
{
?>

<tr>

<td>
<?php echo $vendor['company_name']; ?>
</td>

<td>
<?php echo $vendor['total_orders']; ?>
</td>

<td>
₹<?php echo number_format($vendor['total_value']); ?>
</td>

</tr>

<?php
}
?>

</table>

</div>

<!-- RECENT ACTIVITIES -->

<div class="section">

<h2>

<i class="fa-solid fa-clock-rotate-left"></i>

Recent Procurement Activities

</h2>

<table>

<tr>

<th>PO Number</th>
<th>Vendor</th>
<th>Amount</th>
<th>Status</th>

</tr>

<?php
while($po=mysqli_fetch_assoc($recentPO))
{
?>

<tr>

<td>
<?php echo $po['po_number']; ?>
</td>

<td>
<?php echo $po['company_name']; ?>
</td>

<td>
₹<?php echo number_format($po['total_amount']); ?>
</td>

<td>
<?php echo ucfirst($po['status']); ?>
</td>

</tr>

<?php
}
?>

</table>

</div>

</div>

</body>

</html>