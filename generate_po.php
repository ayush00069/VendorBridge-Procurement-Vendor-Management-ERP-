
<?php
session_start();

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

include 'db.php';

if(isset($_POST['generate_po']))
{
    $rfq_id = $_POST['rfq_id'];
    $vendor_id = $_POST['vendor_id'];
    $po_title = mysqli_real_escape_string($conn,$_POST['po_title']);
    $quantity = $_POST['quantity'];
    $amount = $_POST['amount'];
    $delivery_date = $_POST['delivery_date'];
    $notes = mysqli_real_escape_string($conn,$_POST['notes']);

    $po_number =
    "PO-".date("Ymd")."-".rand(1000,9999);

    mysqli_query($conn,"
    INSERT INTO purchase_orders
    (
        po_number,
        rfq_id,
        vendor_id,
        po_title,
        quantity,
        amount,
        delivery_date,
        notes,
        status,
        created_by
    )
    VALUES
    (
        '$po_number',
        '$rfq_id',
        '$vendor_id',
        '$po_title',
        '$quantity',
        '$amount',
        '$delivery_date',
        '$notes',
        'Pending',
        '{$_SESSION['user_id']}'
    )
    ");

    header("Location: purchase_orders.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Generate Purchase Order</title>

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Segoe UI',sans-serif;
}

body{
background:#f1f5f9;
padding:40px;
}

.container{
max-width:1100px;
margin:auto;
}

.card{
background:#fff;
padding:35px;
border-radius:20px;
box-shadow:0 10px 30px rgba(0,0,0,.08);
}

.card-header{
margin-bottom:30px;
}

.card-header h2{
display:flex;
align-items:center;
gap:12px;
font-size:28px;
color:#0f172a;
}

.card-header i{
color:#2563eb;
}

.form-grid{
display:grid;
grid-template-columns:repeat(2,1fr);
gap:20px;
}

.form-group{
display:flex;
flex-direction:column;
}

.form-group label{
margin-bottom:8px;
font-weight:600;
color:#334155;
}

.form-group input,
.form-group select,
.form-group textarea{
padding:14px;
border:1px solid #cbd5e1;
border-radius:12px;
font-size:14px;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus{
outline:none;
border-color:#2563eb;
box-shadow:0 0 0 4px rgba(37,99,235,.15);
}

.full-width{
grid-column:1/-1;
}

.form-actions{
display:flex;
gap:15px;
margin-top:30px;
}

.save-btn{
background:#2563eb;
color:white;
border:none;
padding:14px 24px;
border-radius:12px;
cursor:pointer;
font-size:15px;
font-weight:600;
display:flex;
align-items:center;
gap:8px;
}

.save-btn:hover{
background:#1d4ed8;
}

.cancel-btn{
background:#ef4444;
color:white;
text-decoration:none;
padding:14px 24px;
border-radius:12px;
font-weight:600;
display:flex;
align-items:center;
gap:8px;
}

.cancel-btn:hover{
background:#dc2626;
}

@media(max-width:768px){

body{
padding:15px;
}

.form-grid{
grid-template-columns:1fr;
}

.form-actions{
flex-direction:column;
}

}

</style>

</head>

<body>

<div class="container">

<div class="card">

<div class="card-header">
<h2>
<i class="fa-solid fa-file-invoice-dollar"></i>
Generate Purchase Order
</h2>
</div>

<form method="POST">

<div class="form-grid">

<div class="form-group">
<label>Select RFQ</label>

<select name="rfq_id" required>

<option value="">Select RFQ</option>

<?php

$rfqs = mysqli_query(
$conn,
"SELECT * FROM rfqs
WHERE status='open'
ORDER BY rfq_id DESC"
);

while($rfq=mysqli_fetch_assoc($rfqs))
{
?>

<option value="<?php echo $rfq['rfq_id']; ?>">

<?php echo $rfq['rfq_title']; ?>

</option>

<?php
}
?>

</select>

</div>

<div class="form-group">

<label>Select Vendor</label>

<select name="vendor_id" required>

<option value="">Select Vendor</option>

<?php

$vendors = mysqli_query(
$conn,
"SELECT * FROM vendors
WHERE status='active'
ORDER BY vendor_name"
);

while($vendor=mysqli_fetch_assoc($vendors))
{
?>

<option value="<?php echo $vendor['vendor_id']; ?>">

<?php echo $vendor['vendor_name']; ?>

</option>

<?php
}
?>

</select>

</div>

<div class="form-group">
<label>PO Title</label>
<input type="text" name="po_title" required>
</div>

<div class="form-group">
<label>Quantity</label>
<input type="number" name="quantity" required>
</div>

<div class="form-group">
<label>Total Amount</label>
<input type="number" step="0.01" name="amount" required>
</div>

<div class="form-group">
<label>Delivery Date</label>
<input type="date" name="delivery_date" required>
</div>

<div class="form-group full-width">
<label>Notes</label>
<textarea
name="notes"
rows="5"></textarea>
</div>

</div>

<div class="form-actions">

<button
type="submit"
name="generate_po"
class="save-btn">

<i class="fa-solid fa-check"></i>
Generate PO

</button>

<a
href="purchase_orders.php"
class="cancel-btn">

<i class="fa-solid fa-xmark"></i>
Cancel

</a>

</div>

</form>

</div>

</div>

</body>
</html>