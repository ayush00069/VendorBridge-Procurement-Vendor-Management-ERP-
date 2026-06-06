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

$search = "";

$sql = "
SELECT
v.*,
u.username,
u.email,
u.phone
FROM vendors v
INNER JOIN users u
ON v.user_id = u.id
";

if(isset($_GET['search']) && !empty($_GET['search']))
{
    $search = trim($_GET['search']);

    $sql .= "
    WHERE
    v.company_name LIKE '%$search%'
    OR
    v.owner_name LIKE '%$search%'
    ";
}

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
Manage Vendors
</title>

<link
rel="stylesheet"
href="manage_vendor.css">

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
<i class="fa-solid fa-users"></i>
Manage Vendors
</h1>

<p>
View and Manage Registered Vendors
</p>

</div>

<form
method="GET"
class="search-box">

<input
type="text"
name="search"
placeholder="Search Vendor..."
value="<?php echo $search; ?>">

<button type="submit">

<i class="fa-solid fa-magnifying-glass"></i>

Search

</button>

</form>

<div class="table-box">

<table>

<thead>

<tr>

<th>ID</th>
<th>Company</th>
<th>Owner</th>
<th>GST</th>
<th>Business Type</th>
<th>Email</th>
<th>Phone</th>
<th>City</th>
<th>State</th>

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
<?php echo $row['vendor_id']; ?>
</td>

<td>
<?php echo $row['company_name']; ?>
</td>

<td>
<?php echo $row['owner_name']; ?>
</td>

<td>
<?php echo $row['gst_number']; ?>
</td>

<td>
<?php echo $row['business_type']; ?>
</td>

<td>
<?php echo $row['email']; ?>
</td>

<td>
<?php echo $row['phone']; ?>
</td>

<td>
<?php echo $row['city']; ?>
</td>

<td>
<?php echo $row['state']; ?>
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

No Vendors Found

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