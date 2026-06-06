<?php
session_start();
include 'db.php';

if(!isset($_GET['id']))
{
    die("RFQ ID Missing");
}

$rfq_id = intval($_GET['id']);

$query = "
SELECT *
FROM rfq
WHERE rfq_id='$rfq_id'
";

$result = mysqli_query($conn,$query);

if(mysqli_num_rows($result)==0)
{
    die("RFQ Not Found");
}

$row = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>RFQ Details</title>

<link rel="stylesheet" href="view_rfq.css">

<style>
.container{
    max-width:900px;
    margin:30px auto;
    padding:20px;
}

.card{
    background:#fff;
    padding:25px;
    border-radius:12px;
    box-shadow:0 3px 10px rgba(0,0,0,.1);
}

h1{
    margin-bottom:20px;
}

.detail{
    margin-bottom:15px;
}

strong{
    color:#333;
}
</style>

</head>
<body>

<div class="container">

<div class="card">

<h1><?php echo $row['rfq_title']; ?></h1>

<div class="detail">
<strong>Category:</strong>
<?php echo $row['category']; ?>
</div>

<div class="detail">
<strong>Description:</strong>
<?php echo $row['description']; ?>
</div>

<div class="detail">
<strong>Quantity:</strong>
<?php echo $row['quantity']; ?>
</div>

<div class="detail">
<strong>Estimated Budget:</strong>
₹<?php echo number_format($row['estimated_budget']); ?>
</div>

<div class="detail">
<strong>Deadline:</strong>
<?php echo $row['deadline']; ?>
</div>

<div class="detail">
<strong>Status:</strong>
<?php echo ucfirst($row['status']); ?>
</div>

<br>

<a
href="submit_quotation.php?rfq_id=<?php echo $row['rfq_id']; ?>"
class="quote-btn">

Submit Quotation

</a>

</div>

</div>

</body>
</html>