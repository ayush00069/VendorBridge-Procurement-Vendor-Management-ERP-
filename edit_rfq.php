
<?php
session_start();

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

include 'db.php';

if(!isset($_GET['id']))
{
    header("Location: rfqs.php");
    exit();
}

$id = (int)$_GET['id'];

$rfq = mysqli_fetch_assoc(
mysqli_query(
$conn,
"SELECT * FROM rfqs WHERE rfq_id='$id'"
));

if(!$rfq)
{
    header("Location: rfqs.php");
    exit();
}

if(isset($_POST['update_rfq']))
{
    $rfq_title = mysqli_real_escape_string($conn,$_POST['rfq_title']);
    $description = mysqli_real_escape_string($conn,$_POST['description']);
    $quantity = mysqli_real_escape_string($conn,$_POST['quantity']);
    $deadline = mysqli_real_escape_string($conn,$_POST['deadline']);
    $vendor_id = mysqli_real_escape_string($conn,$_POST['vendor_id']);
    $status = mysqli_real_escape_string($conn,$_POST['status']);

    $attachment = $rfq['attachment'];

    if(!empty($_FILES['attachment']['name']))
    {
        $attachment =
        time().'_'.$_FILES['attachment']['name'];

        move_uploaded_file(
            $_FILES['attachment']['tmp_name'],
            "uploads/".$attachment
        );
    }

    mysqli_query($conn,"
    UPDATE rfqs SET

    rfq_title='$rfq_title',
    description='$description',
    quantity='$quantity',
    attachment='$attachment',
    deadline='$deadline',
    vendor_id='$vendor_id',
    status='$status'

    WHERE rfq_id='$id'
    ");

    header("Location: rfqs.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Edit RFQ</title>

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Segoe UI',sans-serif;
}

body{
background:#f1f5f9;
padding:40px;
}

.container{
max-width:1100px;
margin:auto;
}

.card{
background:#fff;
border-radius:20px;
padding:35px;
box-shadow:0 10px 30px rgba(0,0,0,.08);
}

.card-header{
margin-bottom:30px;
}

.card-header h2{
display:flex;
align-items:center;
gap:12px;
font-size:28px;
color:#0f172a;
}

.card-header i{
color:#2563eb;
}

.form-grid{
display:grid;
grid-template-columns:repeat(2,1fr);
gap:20px;
}

.form-group{
display:flex;
flex-direction:column;
}

.form-group label{
margin-bottom:8px;
font-weight:600;
color:#334155;
}

.form-group input,
.form-group select,
.form-group textarea{
padding:14px;
border:1px solid #cbd5e1;
border-radius:12px;
font-size:14px;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus{
outline:none;
border-color:#2563eb;
box-shadow:0 0 0 4px rgba(37,99,235,.15);
}

.full-width{
grid-column:1/-1;
}

.current-file{
background:#eff6ff;
padding:12px;
border-radius:10px;
margin-top:10px;
}

.current-file a{
color:#2563eb;
text-decoration:none;
font-weight:600;
}

.form-actions{
display:flex;
gap:15px;
margin-top:30px;
}

.update-btn{
background:#2563eb;
color:white;
border:none;
padding:14px 24px;
border-radius:12px;
cursor:pointer;
font-size:15px;
font-weight:600;
display:flex;
align-items:center;
gap:8px;
}

.update-btn:hover{
background:#1d4ed8;
}

.cancel-btn{
background:#ef4444;
color:white;
text-decoration:none;
padding:14px 24px;
border-radius:12px;
font-weight:600;
display:flex;
align-items:center;
gap:8px;
}

.cancel-btn:hover{
background:#dc2626;
}

@media(max-width:768px){

body{
padding:15px;
}

.form-grid{
grid-template-columns:1fr;
}

.form-actions{
flex-direction:column;
}

}

</style>

</head>

<body>

<div class="container">

<div class="card">

<div class="card-header">
<h2>
<i class="fa-solid fa-file-pen"></i>
Edit RFQ
</h2>
</div>

<form method="POST" enctype="multipart/form-data">

<div class="form-grid">

<div class="form-group">
<label>RFQ Title</label>

<input
type="text"
name="rfq_title"
value="<?php echo htmlspecialchars($rfq['rfq_title']); ?>"
required>

</div>

<div class="form-group">
<label>Quantity</label>

<input
type="number"
name="quantity"
value="<?php echo $rfq['quantity']; ?>"
required>

</div>

<div class="form-group full-width">

<label>Description</label>

<textarea
name="description"
rows="5"
required><?php echo htmlspecialchars($rfq['description']); ?></textarea>

</div>

<div class="form-group">
<label>Deadline</label>

<input
type="date"
name="deadline"
value="<?php echo $rfq['deadline']; ?>"
required>

</div>

<div class="form-group">

<label>Vendor</label>

<select name="vendor_id" required>

<?php

$vendors = mysqli_query(
$conn,
"SELECT * FROM vendors
ORDER BY vendor_name"
);

while($vendor=mysqli_fetch_assoc($vendors))
{
?>

<option
value="<?php echo $vendor['vendor_id']; ?>"
<?php if($rfq['vendor_id']==$vendor['vendor_id']) echo "selected"; ?>>

<?php echo $vendor['vendor_name']; ?>

</option>

<?php
}
?>

</select>

</div>

<div class="form-group">

<label>Status</label>

<select name="status">

<option value="open"
<?php if($rfq['status']=="open") echo "selected"; ?>>
Open
</option>

<option value="closed"
<?php if($rfq['status']=="closed") echo "selected"; ?>>
Closed
</option>

</select>

</div>

<div class="form-group">

<label>New Attachment</label>

<input
type="file"
name="attachment">

<?php if(!empty($rfq['attachment'])){ ?>

<div class="current-file">

<a
href="uploads/<?php echo $rfq['attachment']; ?>"
target="_blank">

<i class="fa-solid fa-paperclip"></i>
Current Attachment

</a>

</div>

<?php } ?>

</div>

</div>

<div class="form-actions">

<button
type="submit"
name="update_rfq"
class="update-btn">

<i class="fa-solid fa-floppy-disk"></i>
Update RFQ

</button>

<a
href="rfq.php"
class="cancel-btn">

<i class="fa-solid fa-xmark"></i>
Cancel

</a>

</div>

</form>

</div>

</div>

</body>
</html>