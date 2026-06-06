<?php
include '../db.php';

$result =
mysqli_query($conn,
"SELECT * FROM users");
?>

<table border="1">

<tr>
<th>ID</th>
<th>Name</th>
<th>Email</th>
<th>Role</th>
<th>Status</th>
</tr>

<?php
while($row=mysqli_fetch_assoc($result))
{
?>

<tr>
<td><?=$row['id']?></td>
<td><?=$row['full_name']?></td>
<td><?=$row['email']?></td>
<td><?=$row['role']?></td>
<td><?=$row['status']?></td>
</tr>

<?php
}
?>

</table>