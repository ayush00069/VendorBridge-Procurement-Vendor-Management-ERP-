<?php
session_start();
include 'db.php';

if(!isset($_GET['po_id']))
{
    die("PO ID Missing");
}

$po_id = intval($_GET['po_id']);

$query = "
SELECT
po.*,
q.quotation_amount,
q.delivery_days,
v.company_name,
v.owner_name,
v.gst_number,
v.address,
v.city,
v.state,
v.pincode

FROM purchase_orders po

INNER JOIN quotations q
ON po.quotation_id=q.quotation_id

INNER JOIN vendors v
ON po.vendor_id=v.vendor_id

WHERE po.po_id='$po_id'
";

$result = mysqli_query($conn,$query);

if(mysqli_num_rows($result)==0)
{
    die("PO Not Found");
}

$data = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<title>
Purchase Order Details
</title>

<link rel="stylesheet"
href="po_details.css">

</head>

<body>

<div class="container">

<div class="card">

<h1>
Purchase Order Details
</h1>

<div class="info">

<p>

<strong>
PO Number:
</strong>

<?php echo $data['po_number']; ?>

</p>

<p>

<strong>
Vendor:
</strong>

<?php echo $data['company_name']; ?>

</p>

<p>

<strong>
Owner:
</strong>

<?php echo $data['owner_name']; ?>

</p>

<p>

<strong>
GST:
</strong>

<?php echo $data['gst_number']; ?>

</p>

<p>

<strong>
Amount:
</strong>

₹<?php echo number_format($data['total_amount']); ?>

</p>

<p>

<strong>
Issue Date:
</strong>

<?php echo $data['issue_date']; ?>

</p>

<p>

<strong>
Delivery Date:
</strong>

<?php echo $data['delivery_date']; ?>

</p>

<p>

<strong>
Status:
</strong>

<?php echo ucfirst($data['status']); ?>

</p>

</div>

<div class="actions">

<a
href="invoice.php?po_id=<?php echo $data['po_id']; ?>"
class="invoice-btn">

Generate Invoice

</a>

</div>

</div>

</div>

</body>

</html>