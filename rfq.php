
<?php
session_start();

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

include 'db.php';

$page = "rfq";

/* Search */

$search = "";

if(isset($_GET['search']))
{
    $search = mysqli_real_escape_string(
        $conn,
        trim($_GET['search'])
    );
}

/* RFQ Data */

if(!empty($search))
{
    $rfqs = mysqli_query(
    $conn,
    "SELECT * FROM rfqs
    WHERE
    rfq_title LIKE '%$search%'
    OR description LIKE '%$search%'
    OR quantity LIKE '%$search%'
    OR deadline LIKE '%$search%'
    OR status LIKE '%$search%'
    ORDER BY rfq_id DESC"
    );
}
else
{
    $rfqs = mysqli_query(
    $conn,
    "SELECT * FROM rfqs
    ORDER BY rfq_id DESC"
    );
}

/* Statistics */

$totalRFQ = mysqli_num_rows(
mysqli_query(
$conn,
"SELECT * FROM rfqs"
));

$openRFQ = mysqli_num_rows(
mysqli_query(
$conn,
"SELECT * FROM rfqs
WHERE status='open'"
));

$closedRFQ = mysqli_num_rows(
mysqli_query(
$conn,
"SELECT * FROM rfqs
WHERE status='closed'"
));

?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<title>RFQ Management</title>

<link rel="stylesheet"
href="dashboard.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>

<body>

<?php include 'sidebar.php'; ?>

<div class="main-content">

<div class="topbar">

<div>

<h1>RFQ Management</h1>

<p>
Create and manage procurement requests
</p>

</div>

<div class="profile">

<i class="fa-solid fa-user-circle"></i>

</div>

</div>

<div class="cards">

<div class="card">

<i class="fa-solid fa-file-contract"></i>

<h2><?php echo $totalRFQ; ?></h2>

<p>Total RFQs</p>

</div>

<div class="card">

<i class="fa-solid fa-folder-open"></i>

<h2><?php echo $openRFQ; ?></h2>

<p>Open RFQs</p>

</div>

<div class="card">

<i class="fa-solid fa-lock"></i>

<h2><?php echo $closedRFQ; ?></h2>

<p>Closed RFQs</p>

</div>

<div class="card">

<i class="fa-solid fa-clock"></i>

<h2>12</h2>

<p>Pending Responses</p>

</div>

</div>

<div class="section">

<div class="section-header">

<div>

<h2>RFQ Directory</h2>

<div class="section-subtitle">

Showing
<strong>

<?php echo mysqli_num_rows($rfqs); ?>

</strong>

RFQ(s)

<?php if(!empty($search)){ ?>

for

<strong>
"<?php echo htmlspecialchars($search); ?>"
</strong>

<?php } ?>

</div>

</div>

<div class="section-actions">

<form
method="GET"
style="display:flex;gap:10px;align-items:center;">

<input
type="text"
name="search"
class="search-input"
placeholder="Search RFQs..."
value="<?php echo htmlspecialchars($search); ?>">

<button
type="submit"
class="action-btn"
style="border:none;cursor:pointer;">

<i class="fa-solid fa-search"></i>
Search

</button>

<?php if(!empty($search)){ ?>

<a
href="rfq.php"
class="action-btn"
style="background:#64748b;">

<i class="fa-solid fa-rotate-left"></i>
Reset

</a>

<?php } ?>

</form>

<a
href="create_rfq.php"
class="action-btn">

<i class="fa-solid fa-plus"></i>

Create RFQ

</a>

</div>

</div>

<table>

<thead>

<tr>

<th>ID</th>
<th>RFQ Title</th>
<th>Quantity</th>
<th>Deadline</th>
<th>Status</th>
<th>Action</th>

</tr>

</thead>

<tbody>

<?php

if(mysqli_num_rows($rfqs) > 0)
{

while($row=mysqli_fetch_assoc($rfqs))
{

?>

<tr>

<td>

#<?php echo $row['rfq_id']; ?>

</td>

<td>

<?php
echo htmlspecialchars(
$row['rfq_title']
);
?>

</td>

<td>

<?php
echo $row['quantity'];
?>

</td>

<td>

<?php
echo $row['deadline'];
?>

</td>

<td>

<?php

if($row['status']=="open")
{
?>

<span class="status approved">
Open
</span>

<?php
}
else
{
?>

<span class="status rejected">
Closed
</span>

<?php
}
?>

</td>

<td>

<a
href="view_rfq.php?id=<?php echo $row['rfq_id']; ?>"
class="edit-btn">

<i class="fa-solid fa-eye"></i>

</a>

<a
href="edit_rfq.php?id=<?php echo $row['rfq_id']; ?>"
class="edit-btn">

<i class="fa-solid fa-pen"></i>

</a>

<a
href="delete_rfq.php?id=<?php echo $row['rfq_id']; ?>"
onclick="return confirm('Delete RFQ?')"
class="delete-btn">

<i class="fa-solid fa-trash"></i>

</a>

</td>

</tr>

<?php

}

}
else
{
?>

<tr>

<td colspan="6"
style="text-align:center;padding:40px;">

No RFQs found

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

