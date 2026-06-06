<?php
session_start();

include 'db.php';
include 'config/constants.php';
include 'config/mail.php';
if(!isset($_GET['po_id']))
{
    die("Purchase Order ID Missing");
}

$po_id = intval($_GET['po_id']);

$query = "
SELECT
po.*,

q.quotation_amount,
q.delivery_days,

v.vendor_id,
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
    die("Purchase Order Not Found");
}

$data = mysqli_fetch_assoc($result);

/* CALCULATIONS */

$subtotal = $data['quotation_amount'];

$gst_rate = 18;

$gst_amount =
($subtotal * $gst_rate) / 100;

$grand_total =
$subtotal + $gst_amount;

$invoice_no =
"INV".date("Ymd").$po_id;
?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>
Invoice
</title>

<link rel="stylesheet"
href="invoice.css">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
rel="stylesheet">

</head>

<body>

<div class="invoice-box">

<div class="header">

<h1>
VendorBridge
</h1>

<h2>
Tax Invoice
</h2>

</div>

<hr>

<div class="invoice-info">

<p>

<strong>
Invoice Number :
</strong>

<?php echo $invoice_no; ?>

</p>

<p>

<strong>
Invoice Date :
</strong>

<?php echo date("d-m-Y"); ?>

</p>

<p>

<strong>
PO Number :
</strong>

<?php echo $data['po_number']; ?>

</p>

</div>

<hr>

<h3>
Vendor Details
</h3>

<p>

<strong>
Company :
</strong>

<?php echo $data['company_name']; ?>

</p>

<p>

<strong>
Owner :
</strong>

<?php echo $data['owner_name']; ?>

</p>

<p>

<strong>
GST :
</strong>

<?php echo $data['gst_number']; ?>

</p>

<p>

<strong>
Address :
</strong>

<?php
echo
$data['address'].", ".
$data['city'].", ".
$data['state']." - ".
$data['pincode'];
?>

</p>

<hr>

<table>

<tr>

<th>Description</th>

<th>Amount</th>

</tr>

<tr>

<td>

Purchase Order

<?php
echo $data['po_number'];
?>

</td>

<td>

₹<?php
echo number_format(
$subtotal,
2
);
?>

</td>

</tr>

</table>

<div class="totals">

<p>

Subtotal :

₹<?php
echo number_format(
$subtotal,
2
);
?>

</p>

<p>

GST (18%) :

₹<?php
echo number_format(
$gst_amount,
2
);
?>

</p>

<h2>

Grand Total :

₹<?php
echo number_format(
$grand_total,
2
);
?>

</h2>

</div>

<div class="actions">

<button
onclick="window.print()"
class="btn">

Print Invoice

</button>

<form
action="save_invoice.php"
method="POST">

<input
type="hidden"
name="po_id"
value="<?php echo $po_id; ?>">

<input
type="hidden"
name="quotation_id"
value="<?php echo $data['quotation_id']; ?>">

<input
type="hidden"
name="vendor_id"
value="<?php echo $data['vendor_id']; ?>">

<input
type="hidden"
name="invoice_number"
value="<?php echo $invoice_no; ?>">

<input
type="hidden"
name="subtotal"
value="<?php echo $subtotal; ?>">

<input
type="hidden"
name="gst_amount"
value="<?php echo $gst_amount; ?>">

<input
type="hidden"
name="grand_total"
value="<?php echo $grand_total; ?>">

<button
type="submit"
class="btn save-btn">

Save Invoice

</button>

</form>

</div>

<hr>

<h4>
Terms & Conditions
</h4>

<ul>

<li>
Payment due within 30 days.
</li>

<li>
GST charged as per government rules.
</li>

<li>
Late payments may attract penalties.
</li>

<li>
This is a system-generated invoice.
</li>

</ul>

</div>

</body>
</html>