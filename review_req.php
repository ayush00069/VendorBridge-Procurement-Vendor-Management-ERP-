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

/* APPROVE RFQ */

if(isset($_GET['approve']))
{
    $rfq_id = intval($_GET['approve']);

    $stmt = $conn->prepare(
    "UPDATE rfq
     SET status='approved'
     WHERE rfq_id=?"
    );

    $stmt->bind_param("i",$rfq_id);

    if($stmt->execute())
    {
        mysqli_query(
        $conn,
        "INSERT INTO activity_logs
        (user_id,role,activity,activity_time)
        VALUES
        (
        '{$_SESSION['user_id']}',
        'manager',
        'Approved RFQ ID $rfq_id',
        NOW()
        )"
        );

        header("Location: review_req.php");
        exit();
    }
}

/* REJECT RFQ */

if(isset($_GET['reject']))
{
    $rfq_id = intval($_GET['reject']);

    $stmt = $conn->prepare(
    "UPDATE rfq
     SET status='rejected'
     WHERE rfq_id=?"
    );

    $stmt->bind_param("i",$rfq_id);

    if($stmt->execute())
    {
        mysqli_query(
        $conn,
        "INSERT INTO activity_logs
        (user_id,role,activity,activity_time)
        VALUES
        (
        '{$_SESSION['user_id']}',
        'manager',
        'Rejected RFQ ID $rfq_id',
        NOW()
        )"
        );

        header("Location: review_req.php");
        exit();
    }
}

/* FETCH RFQ DATA */

$query = "
SELECT
r.*,
o.full_name

FROM rfq r

INNER JOIN officers o
ON r.officer_id = o.officer_id

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

<title>
Manager Review Requests
</title>

<link rel="stylesheet"
href="review_req.css">

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
<i class="fa-solid fa-clipboard-check"></i>
RFQ Approval Requests
</h1>

<p>
Review procurement requests and approve/reject them
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
<th>Quantity</th>
<th>Budget</th>
<th>Deadline</th>
<th>Status</th>
<th>Action</th>

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
<?php echo $row['quantity']; ?>
</td>

<td>
₹<?php echo number_format($row['estimated_budget']); ?>
</td>

<td>
<?php echo $row['deadline']; ?>
</td>

<td>

<?php

$status = $row['status'];

if($status=="open")
{
    echo "<span class='pending'>Open</span>";
}
elseif($status=="approved")
{
    echo "<span class='approved'>Approved</span>";
}
elseif($status=="rejected")
{
    echo "<span class='rejected'>Rejected</span>";
}
elseif($status=="awarded")
{
    echo "<span class='awarded'>Awarded</span>";
}
else
{
    echo "<span class='pending'>Pending</span>";
}

?>

</td>

<td>

<?php if($row['status']=="open" || $row['status']==""){ ?>

<a
href="review_req.php?approve=<?php echo $row['rfq_id']; ?>"
class="approve"
onclick="return confirm('Approve this RFQ?')">

Approve

</a>

<a
href="review_req.php?reject=<?php echo $row['rfq_id']; ?>"
class="reject"
onclick="return confirm('Reject this RFQ?')">

Reject

</a>

<?php } elseif($row['status']=="approved"){ ?>

<span class="approved">
Approved
</span>

<?php } elseif($row['status']=="rejected"){ ?>

<span class="rejected">
Rejected
</span>

<?php } elseif($row['status']=="awarded"){ ?>

<span class="awarded">
Awarded
</span>

<?php } ?>

</td>

</tr>

<?php
    }
}
else
{
?>

<tr>

<td colspan="9">

No RFQ Requests Found

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