<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

if($_SESSION['role']!='manager')
{
    header("Location: login.php");
    exit();
}

$result = mysqli_query(
$conn,
"
SELECT *
FROM activity_logs
ORDER BY activity_time DESC
"
);
?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0">

<title>
Activity Logs
</title>

<link
rel="stylesheet"
href="manager_activity.css">

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

<i class="fa-solid fa-clock-rotate-left"></i>

System Activity Logs

</h1>

<p>

Monitor all procurement activities

</p>

</div>

<div class="table-box">

<table>

<thead>

<tr>

<th>Log ID</th>

<th>User ID</th>

<th>Role</th>

<th>Activity</th>

<th>Date & Time</th>

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
<?php echo $row['log_id']; ?>
</td>

<td>
<?php echo $row['user_id']; ?>
</td>

<td>
<?php echo $row['role']; ?>
</td>

<td>
<?php echo $row['activity']; ?>
</td>

<td>
<?php echo $row['activity_time']; ?>
</td>

</tr>

<?php
}
}
else
{
?>

<tr>

<td colspan="5">

No Activity Logs Found

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