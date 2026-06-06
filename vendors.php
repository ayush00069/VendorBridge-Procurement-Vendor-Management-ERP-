
<?php
session_start();

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

include 'db.php';

$page = "vendors";

/* Search */

$search = "";

if(isset($_GET['search']))
{
    $search = mysqli_real_escape_string(
        $conn,
        trim($_GET['search'])
    );
}

/* Vendor Data */

if(!empty($search))
{
    $vendors = mysqli_query(
    $conn,
    "SELECT * FROM vendors
    WHERE
    vendor_name LIKE '%$search%'
    OR vendor_category LIKE '%$search%'
    OR gst_number LIKE '%$search%'
    OR contact_person LIKE '%$search%'
    OR email LIKE '%$search%'
    OR phone LIKE '%$search%'
    OR status LIKE '%$search%'
    ORDER BY vendor_id DESC"
    );
}
else
{
    $vendors = mysqli_query(
    $conn,
    "SELECT * FROM vendors
    ORDER BY vendor_id DESC"
    );
}

/* Statistics */

$totalVendors = mysqli_num_rows(
mysqli_query(
$conn,
"SELECT * FROM vendors"
));

$activeVendors = mysqli_num_rows(
mysqli_query(
$conn,
"SELECT * FROM vendors
WHERE status='active'"
));

$inactiveVendors = mysqli_num_rows(
mysqli_query(
$conn,
"SELECT * FROM vendors
WHERE status='inactive'"
));
?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<title>Vendor Management</title>

<link rel="stylesheet" href="dashboard.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>

<body>

<?php include 'sidebar.php'; ?>

<div class="main-content">

<div class="topbar">

<div>

<h1>Vendor Management</h1>

<p>
Manage all vendor partners and suppliers
</p>

</div>

<div class="profile">

<i class="fa-solid fa-user-circle"></i>

</div>

</div>

<div class="cards">

<div class="card">

<i class="fa-solid fa-users"></i>

<h2><?php echo $totalVendors; ?></h2>

<p>Total Vendors</p>

</div>

<div class="card">

<i class="fa-solid fa-user-check"></i>

<h2><?php echo $activeVendors; ?></h2>

<p>Active Vendors</p>

</div>

<div class="card">

<i class="fa-solid fa-user-slash"></i>

<h2><?php echo $inactiveVendors; ?></h2>

<p>Inactive Vendors</p>

</div>

<div class="card">

<i class="fa-solid fa-star"></i>

<h2>4.8</h2>

<p>Vendor Rating</p>

</div>

</div>

<div class="section">

<div class="section-header">

<div>

<h2>Vendor Directory</h2>

<div class="section-subtitle">

Showing
<strong>
<?php echo mysqli_num_rows($vendors); ?>
</strong>
vendor(s)

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
placeholder="Search Vendors..."
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
href="vendors.php"
class="action-btn"
style="background:#64748b;">

<i class="fa-solid fa-rotate-left"></i>
Reset

</a>

<?php } ?>

</form>

<a
href="add_vendor.php"
class="action-btn">

<i class="fa-solid fa-plus"></i>
Add Vendor

</a>

</div>

</div>

<table>

<thead>

<tr>

<th>ID</th>
<th>Vendor</th>
<th>Category</th>
<th>GST Number</th>
<th>Contact Person</th>
<th>Email</th>
<th>Status</th>
<th>Action</th>

</tr>

</thead>

<tbody>

<?php

if(mysqli_num_rows($vendors) > 0)
{

while($row=mysqli_fetch_assoc($vendors))
{

?>

<tr>

<td>

#<?php echo $row['vendor_id']; ?>

</td>

<td>

<div class="vendor-box">

<div class="avatar">

<?php
echo strtoupper(
substr(
$row['vendor_name'],
0,
1
));
?>

</div>

<div>

<strong>

<?php
echo htmlspecialchars(
$row['vendor_name']
);
?>

</strong>

</div>

</div>

</td>

<td>

<?php
echo htmlspecialchars(
$row['vendor_category']
);
?>

</td>

<td>

<?php
echo htmlspecialchars(
$row['gst_number']
);
?>

</td>

<td>

<?php
echo htmlspecialchars(
$row['contact_person']
);
?>

</td>

<td>

<?php
echo htmlspecialchars(
$row['email']
);
?>

</td>

<td>

<?php

if($row['status']=="active")
{
?>

<span class="status approved">
Active
</span>

<?php
}
else
{
?>

<span class="status rejected">
Inactive
</span>

<?php
}
?>

</td>

<td>

<a
href="edit_vendor.php?id=<?php echo $row['vendor_id']; ?>"
class="edit-btn">

<i class="fa-solid fa-pen"></i>

</a>

<a
href="delete_vendor.php?id=<?php echo $row['vendor_id']; ?>"
onclick="return confirm('Delete Vendor?')"
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

<td
colspan="8"
style="text-align:center;padding:40px;">

No vendors found

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
