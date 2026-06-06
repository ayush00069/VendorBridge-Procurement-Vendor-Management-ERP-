
<?php
session_start();

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

include 'db.php';

if(!isset($_GET['po_id']))
{
    die("Purchase Order ID Missing");
}

$po_id = (int)$_GET['po_id'];

$query = mysqli_query(
$conn,
"SELECT
po.*,
v.vendor_name,
v.email,
v.contact_person,
v.gst_number
FROM purchase_orders po
LEFT JOIN vendors v
ON po.vendor_id = v.vendor_id
WHERE po.po_id = '$po_id'"
);

$data = mysqli_fetch_assoc($query);

if(!$data)
{
    die("Purchase Order Not Found");
}

$subtotal = $data['amount'];

$gst_rate = 18;

$gst_amount =
($subtotal * $gst_rate) / 100;

$grand_total =
$subtotal + $gst_amount;

$invoice_no =
"INV-" .
date("Ymd") .
"-" .
$po_id;
?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<title>Invoice</title>

<style>

body{
font-family:'Poppins',sans-serif;
background:#f8fafc;
padding:30px;
}

.invoice-box{
max-width:900px;
margin:auto;
background:#fff;
padding:40px;
border-radius:20px;
box-shadow:0 10px 30px rgba(0,0,0,.08);
}

.header{
display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:30px;
}

.company h1{
margin:0;
color:#2563eb;
}

.company p{
margin:5px 0;
color:#64748b;
}

.invoice-title{
text-align:right;
}

.invoice-title h2{
margin:0;
color:#0f172a;
}

.info{
display:grid;
grid-template-columns:1fr 1fr;
gap:30px;
margin-bottom:30px;
}

.card{
background:#f8fafc;
padding:20px;
border-radius:15px;
}

.card h3{
margin-bottom:15px;
color:#2563eb;
}

table{
width:100%;
border-collapse:collapse;
margin-top:20px;
}

table th{
background:#2563eb;
color:white;
padding:14px;
text-align:left;
}

table td{
padding:14px;
border-bottom:1px solid #e2e8f0;
}

.total{
margin-top:25px;
text-align:right;
}

.total p{
font-size:18px;
margin-bottom:10px;
}

.total h2{
color:#2563eb;
}

.btn{
padding:12px 22px;
background:#2563eb;
color:white;
border:none;
border-radius:10px;
cursor:pointer;
margin-top:20px;
margin-right:10px;
}

.btn:hover{
background:#1d4ed8;
}

.terms{
margin-top:30px;
background:#f8fafc;
padding:20px;
border-radius:12px;
}

@media print{

.btn{
display:none;
}

body{
background:white;
padding:0;
}

.invoice-box{
box-shadow:none;
}

}

</style>

</head>

<body>

<div class="invoice-box">

<div class="header">

<div class="company">

<h1>VendorBridge ERP</h1>

<p>Procurement & Vendor Management ERP</p>

</div>

<div class="invoice-title">

<h2>TAX INVOICE</h2>

<p>
Invoice No:
<strong><?php echo $invoice_no; ?></strong>
</p>

<p>
Date:
<?php echo date('d-m-Y'); ?>
</p>

</div>

</div>

<div class="info">

<div class="card">

<h3>Vendor Details</h3>

<p>
<strong>Name:</strong>
<?php echo $data['vendor_name']; ?>
</p>

<p>
<strong>Contact:</strong>
<?php echo $data['contact_person']; ?>
</p>

<p>
<strong>Email:</strong>
<?php echo $data['email']; ?>
</p>

<p>
<strong>GST:</strong>
<?php echo $data['gst_number']; ?>
</p>

</div>

<div class="card">

<h3>Purchase Order</h3>

<p>
<strong>PO Number:</strong>
<?php echo $data['po_number']; ?>
</p>

<p>
<strong>PO Date:</strong>
<?php echo $data['po_date']; ?>
</p>

<p>
<strong>Status:</strong>
<?php echo ucfirst($data['status']); ?>
</p>

</div>

</div>

<table>

<tr>

<th>Description</th>
<th>Amount</th>

</tr>

<tr>

<td>
Purchase Order Amount
</td>

<td>
₹<?php echo number_format($subtotal,2); ?>
</td>

</tr>

</table>

<div class="total">

<p>
Subtotal :
₹<?php echo number_format($subtotal,2); ?>
</p>

<p>
GST (18%) :
₹<?php echo number_format($gst_amount,2); ?>
</p>

<h2>
Grand Total :
₹<?php echo number_format($grand_total,2); ?>
</h2>

</div>

<form method="POST" action="save_invoice.php">

<input type="hidden"
name="po_id"
value="<?php echo $po_id; ?>">

<input type="hidden"
name="vendor_id"
value="<?php echo $data['vendor_id']; ?>">

<input type="hidden"
name="invoice_number"
value="<?php echo $invoice_no; ?>">

<input type="hidden"
name="subtotal"
value="<?php echo $subtotal; ?>">

<input type="hidden"
name="gst_amount"
value="<?php echo $gst_amount; ?>">

<input type="hidden"
name="grand_total"
value="<?php echo $grand_total; ?>">

<button
type="button"
onclick="window.print()"
class="btn">

Print Invoice

</button>

<button
type="submit"
class="btn">

Save Invoice

</button>

</form>

<div class="terms">

<h3>Terms & Conditions</h3>

<ul>

<li>Payment due within 30 days.</li>

<li>GST charged as per government rules.</li>

<li>Late payment may attract penalties.</li>

<li>This is a system generated invoice.</li>

</ul>

</div>

</div>

</body>
</html>
