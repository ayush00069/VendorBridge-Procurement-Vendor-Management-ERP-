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
i.*,
v.company_name,
po.po_number

FROM invoices i

INNER JOIN vendors v
ON i.vendor_id = v.vendor_id

INNER JOIN purchase_orders po
ON i.po_id = po.po_id

ORDER BY i.created_at DESC
";

$result = mysqli_query($conn,$query);
?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>
Invoices
</title>

<link rel="stylesheet"
href="view_invoices.css">

<link
href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>

<body>

<div class="container">

<div class="header">

<h1>
<i class="fa-solid fa-file-invoice"></i>
Generated Invoices
</h1>

<p>
View all generated invoices
</p>

</div>

<div class="table-box">

<table>

<thead>

<tr>

<th>Invoice No</th>
<th>PO Number</th>
<th>Vendor</th>
<th>Amount</th>
<th>Date</th>

</tr>

</thead>

<tbody>

<?php

if(mysqli_num_rows($result) > 0)
{
    while($row = mysqli_fetch_assoc($result))
    {
?>

<tr>

<td>
<?php echo $row['invoice_number']; ?>
</td>

<td>
<?php echo $row['po_number']; ?>
</td>

<td>
<?php echo $row['company_name']; ?>
</td>

<td>
₹<?php echo number_format($row['invoice_amount'],2); ?>
</td>

<td>
<?php echo date(
"d M Y",
strtotime($row['invoice_date'])
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

No Invoices Found

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