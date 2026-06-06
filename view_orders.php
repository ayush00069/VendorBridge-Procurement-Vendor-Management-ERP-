<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$vendorQuery = $conn->prepare(
"SELECT vendor_id
FROM vendors
WHERE user_id=?"
);

$vendorQuery->bind_param(
"i",
$user_id
);

$vendorQuery->execute();

$vendorResult =
$vendorQuery->get_result();

$vendor =
$vendorResult->fetch_assoc();

$vendor_id =
$vendor['vendor_id'];

$stmt = $conn->prepare(
"
SELECT *
FROM purchase_orders
WHERE vendor_id=?
ORDER BY created_at DESC
"
);

$stmt->bind_param(
"i",
$vendor_id
);

$stmt->execute();

$result =
$stmt->get_result();
?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0">

<title>
Purchase Orders
</title>

<link
rel="stylesheet"
href="view_orders.css">

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
<i class="fa-solid fa-file-contract"></i>
My Purchase Orders
</h1>

<p>
Track all purchase orders issued to you
</p>

</div>

<div class="table-box">

<table>

<thead>

<tr>

<th>PO Number</th>

<th>Total Amount</th>

<th>Issue Date</th>

<th>Delivery Date</th>

<th>Status</th>

<th>Created At</th>

</tr>

</thead>

<tbody>

<?php

if($result->num_rows > 0)
{
    while($row =
    $result->fetch_assoc())
    {
?>

<tr>

<td>

<?php
echo $row['po_number'];
?>

</td>

<td>

₹<?php
echo number_format(
$row['total_amount'],
2
);
?>

</td>

<td>

<?php
echo date(
"d M Y",
strtotime(
$row['issue_date']
)
);
?>

</td>

<td>

<?php
echo date(
"d M Y",
strtotime(
$row['delivery_date']
)
);
?>

</td>

<td>

<?php

$status =
$row['status'];

if($status=="pending")
{
echo "<span class='pending'>Pending</span>";
}
elseif($status=="approved")
{
echo "<span class='approved'>Approved</span>";
}
elseif($status=="completed")
{
echo "<span class='completed'>Completed</span>";
}
elseif($status=="delivered")
{
echo "<span class='completed'>Delivered</span>";
}
else
{
echo "<span class='cancelled'>Cancelled</span>";
}

?>

</td>

<td>

<?php
echo date(
"d M Y",
strtotime(
$row['created_at']
)
);
?>

</td>

</tr>

<?php
    }
}
else
{
?>

<tr>

<td colspan="6">

No Purchase Orders Available

</td>

</tr>

<?php
}
?>

</tbody>

</table>

</div>

</div>

</body>

</html>