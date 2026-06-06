<?php

include 'db.php';

$id = $_GET['id'];

mysqli_query(
$conn,
"UPDATE quotations
SET status='rejected'
WHERE quotation_id='$id'"
);

header(
"Location: approvals.php"
);

exit();
?>