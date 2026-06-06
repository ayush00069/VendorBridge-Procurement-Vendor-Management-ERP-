<?php
session_start();
include 'db.php';

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

$totalRFQ = mysqli_fetch_assoc(
mysqli_query(
$conn,
"SELECT COUNT(*) AS total FROM rfq"
)
)['total'];

$openRFQ = mysqli_fetch_assoc(
mysqli_query(
$conn,
"SELECT COUNT(*) AS total
FROM rfq
WHERE status='open'"
)
)['total'];

$closedRFQ = mysqli_fetch_assoc(
mysqli_query(
$conn,
"SELECT COUNT(*) AS total
FROM rfq
WHERE status IN ('approved','awarded','closed')"
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

$recentRFQ = mysqli_query(
$conn,
"SELECT *
FROM rfq
ORDER BY created_at DESC
LIMIT 5"
);

$recentQuotes = mysqli_query(
$conn,
"
SELECT
q.*,
v.company_name,
r.rfq_title
FROM quotations q
INNER JOIN vendors v
ON q.vendor_id=v.vendor_id
INNER JOIN rfq r
ON q.rfq_id=r.rfq_id
ORDER BY q.submitted_at DESC
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

<title>Officer Reports</title>

<link rel="stylesheet"
href="officer_reports.css">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>

<body>

<div class="container">

<div class="header">

<h1>
<i class="fa-solid fa-chart-column"></i>
Officer Reports
</h1>

<p>
Procurement Reports & Analytics
</p>

</div>

<div class="cards">

<div class="card">
<i class="fa-solid fa-file-circle-plus"></i>
<h2><?php echo $totalRFQ; ?></h2>
<p>Total RFQs</p>
</div>

<div class="card green">
<i class="fa-solid fa-folder-open"></i>
<h2><?php echo $openRFQ; ?></h2>
<p>Open RFQs</p>
</div>

<div class="card blue">
<i class="fa-solid fa-folder-closed"></i>
<h2><?php echo $closedRFQ; ?></h2>
<p>Closed RFQs</p>
</div>

<div class="card orange">
<i class="fa-solid fa-file-signature"></i>
<h2><?php echo $totalQuotes; ?></h2>
<p>Quotations</p>
</div>

<div class="card purple">
<i class="fa-solid fa-file-contract"></i>
<h2><?php echo $totalPO; ?></h2>
<p>Purchase Orders</p>
</div>

<div class="card red">
<i class="fa-solid fa-users"></i>
<h2><?php echo $totalVendors; ?></h2>
<p>Vendors</p>
</div>

</div>

<div class="tables">

<div class="table-box">

<h3>Recent RFQs</h3>

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

<td><?php echo $rfq['rfq_id']; ?></td>

<td><?php echo $rfq['rfq_title']; ?></td>

<td><?php echo ucfirst($rfq['status']); ?></td>

</tr>
<?php
}
?>

</table>

</div>

<div class="table-box">

<h3>Recent Quotations</h3>

<table>

<tr>
<th>Vendor</th>
<th>RFQ</th>
<th>Amount</th>
</tr>

<?php
while($quote=mysqli_fetch_assoc($recentQuotes))
{
?>

<tr>

<td>
<?php echo $quote['company_name']; ?>
</td>

<td>
<?php echo $quote['rfq_title']; ?>
</td>

<td>
₹<?php echo number_format($quote['quotation_amount']); ?>
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