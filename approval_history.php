<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

if($_SESSION['role'] != 'manager')
{
    header("Location: login.php");
    exit();
}

$query = "
SELECT
r.rfq_id,
r.rfq_title,
r.category,
r.estimated_budget,
r.deadline,
r.status,
o.full_name,
r.created_at

FROM rfq r

INNER JOIN officers o
ON r.officer_id = o.officer_id

WHERE r.status IN ('approved','rejected','awarded')

ORDER BY r.created_at DESC
";

$result = mysqli_query($conn,$query);
?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Approval History</title>

<link rel="stylesheet"
href="approval_history.css">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>

<body>

<div class="container">

<div class="header">

<h1>
<i class="fa-solid fa-clock-rotate-left"></i>
Approval History
</h1>

<p>
Track all approved and rejected RFQs
</p>

</div>

<div class="table-box">

<table>

<thead>

<tr>

<th>RFQ ID</th>
<th>Officer</th>
<th>RFQ Title</th>
<th>Category</th>
<th>Budget</th>
<th>Deadline</th>
<th>Status</th>
<th>Created Date</th>

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
<?php echo $row['rfq_id']; ?>
</td>

<td>
<?php echo $row['full_name']; ?>
</td>

<td>
<?php echo $row['rfq_title']; ?>
</td>

<td>
<?php echo $row['category']; ?>
</td>

<td>
₹<?php echo number_format($row['estimated_budget']); ?>
</td>

<td>
<?php echo $row['deadline']; ?>
</td>

<td>

<?php

if($row['status']=="approved")
{
    echo "<span class='approved'>Approved</span>";
}
elseif($row['status']=="rejected")
{
    echo "<span class='rejected'>Rejected</span>";
}
elseif($row['status']=="awarded")
{
    echo "<span class='awarded'>Awarded</span>";
}

?>

</td>

<td>
<?php echo date('d M Y',strtotime($row['created_at'])); ?>
</td>

</tr>

<?php
    }
}
else
{
?>

<tr>

<td colspan="8">

No Approval History Found

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