<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$vendorQuery = mysqli_query(
$conn,
"SELECT vendor_id
 FROM vendors
 WHERE user_id='$user_id'"
);

$vendor = mysqli_fetch_assoc($vendorQuery);

$vendor_id = $vendor['vendor_id'];

$sql = "
SELECT
q.*,
r.rfq_title,
r.category
FROM quotations q
INNER JOIN rfq r
ON q.rfq_id = r.rfq_id
WHERE q.vendor_id='$vendor_id'
ORDER BY q.submitted_at DESC
";

$result = mysqli_query($conn,$sql);
?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0">

<title>
Track Quotations
</title>

<link
rel="stylesheet"
href="track_quotation.css">

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
My Quotations
</h1>

<p>
Track all submitted quotations
</p>

</div>

<div class="table-container">

<table>

<thead>

<tr>

<th>RFQ Title</th>

<th>Category</th>

<th>Amount</th>

<th>Delivery Days</th>

<th>Status</th>

<th>Submitted On</th>

<th>Quotation File</th>

</tr>

</thead>

<tbody>

<?php

if(mysqli_num_rows($result)>0)
{
    while($row=mysqli_fetch_assoc($result))
    {
?>

<tr>

<td>
<?php echo $row['rfq_title']; ?>
</td>

<td>
<?php echo $row['category']; ?>
</td>

<td>
₹<?php echo number_format($row['quotation_amount']); ?>
</td>

<td>
<?php echo $row['delivery_days']; ?>
Days
</td>

<td>

<?php

$status = $row['status'];

if($status=="submitted")
{
echo "<span class='submitted'>Submitted</span>";
}
elseif($status=="shortlisted")
{
echo "<span class='shortlisted'>Shortlisted</span>";
}
elseif($status=="selected")
{
echo "<span class='selected'>Selected</span>";
}
elseif($status=="rejected")
{
echo "<span class='rejected'>Rejected</span>";
}

?>

</td>

<td>
<?php echo date(
"d M Y",
strtotime($row['submitted_at'])
); ?>
</td>

<td>

<?php
if($row['quotation_file']!="")
{
?>

<a
href="uploads/quotations/<?php echo $row['quotation_file']; ?>"
target="_blank"
class="download-btn">

Download

</a>

<?php
}
else
{
echo "N/A";
}
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

<td colspan="7">

No Quotations Submitted Yet

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