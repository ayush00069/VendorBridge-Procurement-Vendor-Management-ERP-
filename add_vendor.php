
<?php
session_start();

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

include 'db.php';

if(isset($_POST['add_vendor']))
{
    $vendor_name = mysqli_real_escape_string($conn,$_POST['vendor_name']);
    $vendor_category = mysqli_real_escape_string($conn,$_POST['vendor_category']);
    $gst_number = mysqli_real_escape_string($conn,$_POST['gst_number']);
    $contact_person = mysqli_real_escape_string($conn,$_POST['contact_person']);
    $email = mysqli_real_escape_string($conn,$_POST['email']);
    $phone = mysqli_real_escape_string($conn,$_POST['phone']);
    $address = mysqli_real_escape_string($conn,$_POST['address']);

    mysqli_query($conn,"
    INSERT INTO vendors
    (
        vendor_name,
        vendor_category,
        gst_number,
        contact_person,
        email,
        phone,
        address,
        status
    )
    VALUES
    (
        '$vendor_name',
        '$vendor_category',
        '$gst_number',
        '$contact_person',
        '$email',
        '$phone',
        '$address',
        'active'
    )
    ");

    header("Location: vendors.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Add Vendor</title>

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
    background:#ffffff;
    border-radius:20px;
    padding:35px;
    box-shadow:0 10px 30px rgba(0,0,0,0.08);
}

.card-header{
    margin-bottom:30px;
}

.card-header h2{
    color:#0f172a;
    font-size:28px;
    display:flex;
    align-items:center;
    gap:12px;
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
.form-group textarea{
    width:100%;
    padding:14px;
    border:1px solid #cbd5e1;
    border-radius:12px;
    font-size:14px;
    transition:0.3s;
}

.form-group input:focus,
.form-group textarea:focus{
    outline:none;
    border-color:#2563eb;
    box-shadow:0 0 0 4px rgba(37,99,235,0.15);
}

.full-width{
    grid-column:1/-1;
}

.form-actions{
    margin-top:30px;
    display:flex;
    gap:15px;
}

.save-btn{
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
    transition:0.3s;
}

.save-btn:hover{
    background:#1d4ed8;
    transform:translateY(-2px);
}

.cancel-btn{
    background:#ef4444;
    color:white;
    text-decoration:none;
    padding:14px 24px;
    border-radius:12px;
    font-size:15px;
    font-weight:600;
    display:flex;
    align-items:center;
    gap:8px;
    transition:0.3s;
}

.cancel-btn:hover{
    background:#dc2626;
    transform:translateY(-2px);
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

    .save-btn,
    .cancel-btn{
        justify-content:center;
    }
}

</style>

</head>

<body>

<div class="container">

<div class="card">

<div class="card-header">
    <h2>
        <i class="fa-solid fa-user-plus"></i>
        Add Vendor
    </h2>
</div>

<form method="POST">

<div class="form-grid">

<div class="form-group">
    <label>Vendor Name</label>
    <input type="text" name="vendor_name" required>
</div>

<div class="form-group">
    <label>Vendor Category</label>
    <input type="text" name="vendor_category" required>
</div>

<div class="form-group">
    <label>GST Number</label>
    <input type="text" name="gst_number">
</div>

<div class="form-group">
    <label>Contact Person</label>
    <input type="text" name="contact_person">
</div>

<div class="form-group">
    <label>Email</label>
    <input type="email" name="email">
</div>

<div class="form-group">
    <label>Phone</label>
    <input type="text" name="phone">
</div>

<div class="form-group full-width">
    <label>Address</label>
    <textarea name="address" rows="4"></textarea>
</div>

</div>

<div class="form-actions">

<button type="submit" name="add_vendor" class="save-btn">
    <i class="fa-solid fa-floppy-disk"></i>
    Save Vendor
</button>

<a href="vendors.php" class="cancel-btn">
    <i class="fa-solid fa-xmark"></i>
    Cancel
</a>

</div>

</form>

</div>

</div>

</body>
</html>
