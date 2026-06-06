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

$user_id = $_SESSION['user_id'];

/* FETCH OFFICER ID FROM OFFICERS TABLE */

$getOfficer = $conn->prepare(
"
SELECT officer_id
FROM officers
WHERE user_id=?
"
);

$getOfficer->bind_param(
"i",
$user_id
);

$getOfficer->execute();

$officerResult =
$getOfficer->get_result();

if($officerResult->num_rows == 0)
{
    die("
    Officer profile not found.
    Please create officer record first.
    ");
}

$officer =
$officerResult->fetch_assoc();

$officer_id =
$officer['officer_id'];

$success = "";
$error = "";

if(isset($_POST['create_rfq']))
{
    $rfq_title =
    trim($_POST['rfq_title']);

    $category =
    trim($_POST['category']);

    $description =
    trim($_POST['description']);

    $quantity =
    intval($_POST['quantity']);

    $estimated_budget =
    floatval($_POST['estimated_budget']);

    $deadline =
    $_POST['deadline'];

    $stmt = $conn->prepare(
    "
    INSERT INTO rfq
    (
        officer_id,
        rfq_title,
        category,
        description,
        quantity,
        estimated_budget,
        deadline,
        status
    )
    VALUES
    (
        ?,
        ?,
        ?,
        ?,
        ?,
        ?,
        ?,
        'open'
    )
    "
    );

    $stmt->bind_param(
        "isssids",
        $officer_id,
        $rfq_title,
        $category,
        $description,
        $quantity,
        $estimated_budget,
        $deadline
    );

    if($stmt->execute())
    {
        $success =
        "RFQ Created Successfully";

 
    mysqli_query(
    $conn,
    "INSERT INTO activity_logs
    (user_id,role,activity,activity_time)
    VALUES
    (
    '{$_SESSION['user_id']}',
    'officer',
    'Created RFQ',
    NOW()
    )"
    );


    }
    else
    {
        $error =
        "Database Error : "
        .$stmt->error;
    }
}
?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0">

<title>
Create RFQ
</title>

<link
rel="stylesheet"
href="create_rfq.css">

<link
href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
rel="stylesheet">

<link
rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>

<body>

<div class="container">

<div class="card">

<div class="header">

<i class="fa-solid fa-file-circle-plus"></i>

<h1>
Create RFQ
</h1>

<p>
Request For Quotation
</p>

</div>

<?php
if($success!="")
{
?>

<div class="success">

<?php
echo $success;
?>

</div>

<?php
}
?>

<?php
if($error!="")
{
?>

<div class="error">

<?php
echo $error;
?>

</div>

<?php
}
?>

<form method="POST">

<div class="form-group">

<label>
RFQ Title
</label>

<input
type="text"
name="rfq_title"
placeholder="Enter RFQ Title"
required>

</div>

<div class="form-group">

<label>
Category
</label>

<select
name="category"
required>

<option value="">
Select Category
</option>

<option value="IT Equipment">
IT Equipment
</option>

<option value="Furniture">
Furniture
</option>

<option value="Construction">
Construction
</option>

<option value="Office Supplies">
Office Supplies
</option>

<option value="Services">
Services
</option>

</select>

</div>

<div class="form-group">

<label>
Description
</label>

<textarea
name="description"
placeholder="Enter RFQ Description"
required></textarea>

</div>

<div class="row">

<div class="form-group">

<label>
Quantity
</label>

<input
type="number"
name="quantity"
required>

</div>

<div class="form-group">

<label>
Estimated Budget
</label>

<input
type="number"
step="0.01"
name="estimated_budget"
required>

</div>

</div>

<div class="form-group">

<label>
Deadline
</label>

<input
type="date"
name="deadline"
required>

</div>

<button
type="submit"
name="create_rfq">

Create RFQ

</button>

</form>

</div>

</div>

</body>

</html>