
<?php
session_start();

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

include 'db.php';

if(isset($_POST['create_rfq']))
{
    $rfq_title = mysqli_real_escape_string($conn,$_POST['rfq_title']);
    $description = mysqli_real_escape_string($conn,$_POST['description']);
    $quantity = mysqli_real_escape_string($conn,$_POST['quantity']);
    $deadline = mysqli_real_escape_string($conn,$_POST['deadline']);
    $vendor_id = mysqli_real_escape_string($conn,$_POST['vendor_id']);

    $attachment = "";

    if(isset($_FILES['attachment']) && $_FILES['attachment']['name']!="")
    {
        $attachment =
        time().'_'.$_FILES['attachment']['name'];

        move_uploaded_file(
            $_FILES['attachment']['tmp_name'],
            "uploads/".$attachment
        );
    }

    $created_by = $_SESSION['user_id'];

    mysqli_query($conn,"
        INSERT INTO rfqs
        (
            rfq_title,
            description,
            quantity,
            attachment,
            deadline,
            vendor_id,
            created_by,
            status
        )
        VALUES
        (
            '$rfq_title',
            '$description',
            '$quantity',
            '$attachment',
            '$deadline',
            '$vendor_id',
            '$created_by',
            'open'
        )
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

<title>Create RFQ</title>

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
    color:#0f172a;
    font-size:28px;
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

.form-actions{
    display:flex;
    gap:15px;
    margin-top:30px;
}

.save-btn{
    background:#2563eb;
    color:#fff;
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

.save-btn:hover{
    background:#1d4ed8;
}

.cancel-btn{
    background:#ef4444;
    color:#fff;
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
<i class="fa-solid fa-file-contract"></i>
Create RFQ
</h2>
</div>

<form method="POST" enctype="multipart/form-data">

<div class="form-grid">

<div class="form-group">
<label>RFQ Title</label>
<input
type="text"
name="rfq_title"
required>
</div>

<div class="form-group">
<label>Quantity</label>
<input
type="number"
name="quantity"
required>
</div>

<div class="form-group full-width">
<label>Description</label>
<textarea
name="description"
rows="5"
required></textarea>
</div>

<div class="form-group">
<label>Deadline</label>
<input
type="date"
name="deadline"
required>
</div>

<div class="form-group">
<label>Select Vendor</label>

<select name="vendor_id" required>

<option value="">
Select Vendor
</option>

<?php

$vendors =
mysqli_query(
$conn,
"SELECT * FROM vendors
WHERE status='active'
ORDER BY vendor_name"
);

while($row=mysqli_fetch_assoc($vendors))
{
?>

<option value="<?php echo $row['vendor_id']; ?>">

<?php echo $row['vendor_name']; ?>

</option>

<?php
}
?>

</select>

</div>

<div class="form-group full-width">
<label>Attachment</label>
<input
type="file"
name="attachment">
</div>

</div>

<div class="form-actions">

<button
type="submit"
name="create_rfq"
class="save-btn">

<i class="fa-solid fa-paper-plane"></i>
Create RFQ

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
