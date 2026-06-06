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

/* COUNTS */

$totalRFQ = mysqli_fetch_assoc(
mysqli_query(
$conn,
"SELECT COUNT(*) AS total FROM rfq"
)
)['total'];

$approvedRFQ = mysqli_fetch_assoc(
mysqli_query(
$conn,
"SELECT COUNT(*) AS total
FROM rfq
WHERE status='approved'"
)
)['total'];

$rejectedRFQ = mysqli_fetch_assoc(
mysqli_query(
$conn,
"SELECT COUNT(*) AS total
FROM rfq
WHERE status='rejected'"
)
)['total'];

$totalQuotes = mysqli_fetch_assoc(
mysqli_query(
$conn,
"SELECT COUNT(*) AS total
FROM quotations"
)
)['total'];

$totalPO = mysqli_fetch_assoc(
mysqli_query(
$conn,
"SELECT COUNT(*) AS total
FROM purchase_orders"
)
)['total'];

$totalVendors = mysqli_fetch_assoc(
mysqli_query(
$conn,
"SELECT COUNT(*) AS total
FROM vendors"
)
)['total'];

/* RECENT RFQS */

$recentRFQ = mysqli_query(
$conn,
"
SELECT *
FROM rfq
ORDER BY created_at DESC
LIMIT 5
"
);

/* RECENT PURCHASE ORDERS */

$recentPO = mysqli_query(
$conn,
"
SELECT *
FROM purchase_orders
ORDER BY created_at DESC
LIMIT 5
"
);
?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Monitor Workflow</title>

<link rel="stylesheet"
href="monitor_workflow.css">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>

<body>

<div class="container">

<div class="header">

<h1>

<i class="fa-solid fa-chart-line"></i>

Monitor Workflow

</h1>

<p>
Track procurement activities and workflow status
</p>

</div>

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
<h2><?php echo $totalQuotes; ?></h2>
<p>Total Quotations</p>
</div>

<div class="card purple">
<i class="fa-solid fa-file-contract"></i>
<h2><?php echo $totalPO; ?></h2>
<p>Purchase Orders</p>
</div>

<div class="card orange">
<i class="fa-solid fa-users"></i>
<h2><?php echo $totalVendors; ?></h2>
<p>Registered Vendors</p>
</div>

</div>

<div class="tables">

<div class="table-box">

<h3>
Recent RFQs
</h3>

<table>

<tr>
<th>ID</th>
<th>Title</th>
<th>Status</th>
</tr>

<?php
while($rfq=mysqli_fetch_assoc($recentRFQ))
{
?>

<tr>

<td>
<?php echo $rfq['rfq_id']; ?>
</td>

<td>
<?php echo $rfq['rfq_title']; ?>
</td>

<td>
<?php echo ucfirst($rfq['status']); ?>
</td>

</tr>

<?php
}
?>

</table>

</div>

<div class="table-box">

<h3>
Recent Purchase Orders
</h3>

<table>

<tr>
<th>PO No</th>
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

</div>

</body>

</html>