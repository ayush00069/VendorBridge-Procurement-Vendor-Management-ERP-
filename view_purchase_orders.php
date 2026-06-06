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

$query = "
SELECT
po.*,
v.company_name,
r.rfq_title

FROM purchase_orders po

INNER JOIN vendors v
ON po.vendor_id = v.vendor_id

INNER JOIN quotations q
ON po.quotation_id = q.quotation_id

INNER JOIN rfq r
ON q.rfq_id = r.rfq_id

ORDER BY po.created_at DESC
";

$result = mysqli_query($conn,$query);
?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">

<title>Purchase Orders</title>

<link rel="stylesheet" href="view_purchase_orders.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>

<body>

<div class="container">

<div class="header">

<h1>
<i class="fa-solid fa-cart-shopping"></i>
Purchase Orders
</h1>

</div>

<div class="table-box">

<table>

<tr>

<th>PO Number</th>
<th>RFQ</th>
<th>Vendor</th>
<th>Amount</th>
<th>Issue Date</th>
<th>Delivery Date</th>
<th>Status</th>
<th>Action</th>

</tr>

<?php
while($row=mysqli_fetch_assoc($result))
{
?>

<tr>

<td>
<?php echo $row['po_number']; ?>
</td>

<td>
<?php echo $row['rfq_title']; ?>
</td>

<td>
<?php echo $row['company_name']; ?>
</td>

<td>
₹<?php echo number_format($row['total_amount'],2); ?>
</td>

<td>
<?php echo $row['issue_date']; ?>
</td>

<td>
<?php echo $row['delivery_date']; ?>
</td>

<td>
<?php echo ucfirst($row['status']); ?>
</td>

<td>

<a
href="po_details.php?po_id=<?php echo $row['po_id']; ?>"
class="view-btn">

View

</a>

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