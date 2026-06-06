
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

$query = mysqli_query($conn,"
SELECT r.*,
       v.vendor_name,
       v.contact_person,
       v.email,
       v.phone
FROM rfqs r
LEFT JOIN vendors v
ON r.vendor_id = v.vendor_id
WHERE r.rfq_id = '$id'
");

$rfq = mysqli_fetch_assoc($query);

if(!$rfq)
{
    header("Location: rfqs.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>View RFQ</title>

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
    max-width:1200px;
    margin:auto;
}

.card{
    background:#fff;
    border-radius:20px;
    padding:35px;
    box-shadow:0 10px 30px rgba(0,0,0,.08);
}

.page-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:30px;
    flex-wrap:wrap;
    gap:15px;
}

.page-header h2{
    color:#0f172a;
    font-size:28px;
}

.page-header h2 i{
    color:#2563eb;
    margin-right:10px;
}

.back-btn{
    background:#64748b;
    color:white;
    text-decoration:none;
    padding:12px 20px;
    border-radius:10px;
    font-weight:600;
}

.back-btn:hover{
    background:#475569;
}

.details-grid{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:20px;
}

.detail-box{
    background:#f8fafc;
    border:1px solid #e2e8f0;
    border-radius:15px;
    padding:20px;
}

.detail-box h4{
    color:#64748b;
    font-size:13px;
    text-transform:uppercase;
    margin-bottom:8px;
}

.detail-box p{
    color:#0f172a;
    font-size:16px;
    font-weight:500;
}

.full-width{
    grid-column:1/-1;
}

.status{
    display:inline-block;
    padding:8px 16px;
    border-radius:20px;
    font-size:13px;
    font-weight:600;
}

.open{
    background:#dcfce7;
    color:#15803d;
}

.closed{
    background:#fee2e2;
    color:#dc2626;
}

.download-btn{
    display:inline-flex;
    align-items:center;
    gap:8px;
    background:#2563eb;
    color:white;
    text-decoration:none;
    padding:12px 18px;
    border-radius:10px;
    margin-top:10px;
}

.download-btn:hover{
    background:#1d4ed8;
}

.vendor-section{
    margin-top:30px;
}

.vendor-title{
    font-size:22px;
    color:#0f172a;
    margin-bottom:20px;
}

@media(max-width:768px){

    body{
        padding:15px;
    }

    .details-grid{
        grid-template-columns:1fr;
    }

    .page-header{
        flex-direction:column;
        align-items:flex-start;
    }
}

</style>

</head>

<body>

<div class="container">

<div class="card">

<div class="page-header">

<h2>
<i class="fa-solid fa-file-contract"></i>
RFQ Details
</h2>

<a href="rfq.php" class="back-btn">
<i class="fa-solid fa-arrow-left"></i>
Back
</a>

</div>

<div class="details-grid">

<div class="detail-box">
<h4>RFQ ID</h4>
<p>#<?php echo $rfq['rfq_id']; ?></p>
</div>

<div class="detail-box">
<h4>Status</h4>

<p>
<span class="status <?php echo $rfq['status']; ?>">
<?php echo ucfirst($rfq['status']); ?>
</span>
</p>

</div>

<div class="detail-box full-width">
<h4>RFQ Title</h4>
<p><?php echo htmlspecialchars($rfq['rfq_title']); ?></p>
</div>

<div class="detail-box full-width">
<h4>Description</h4>
<p><?php echo nl2br(htmlspecialchars($rfq['description'])); ?></p>
</div>

<div class="detail-box">
<h4>Quantity</h4>
<p><?php echo $rfq['quantity']; ?></p>
</div>

<div class="detail-box">
<h4>Deadline</h4>
<p><?php echo $rfq['deadline']; ?></p>
</div>

<div class="detail-box">
<h4>Created By</h4>
<p>User #<?php echo $rfq['created_by']; ?></p>
</div>

<div class="detail-box">
<h4>Created At</h4>
<p><?php echo $rfq['created_at']; ?></p>
</div>

<div class="detail-box full-width">

<h4>Attachment</h4>

<?php if(!empty($rfq['attachment'])){ ?>

<a
href="uploads/<?php echo $rfq['attachment']; ?>"
target="_blank"
class="download-btn">

<i class="fa-solid fa-download"></i>
Download Attachment

</a>

<?php } else { ?>

<p>No Attachment Uploaded</p>

<?php } ?>

</div>

</div>

<div class="vendor-section">

<h3 class="vendor-title">
<i class="fa-solid fa-building"></i>
 Vendor Information
</h3>

<div class="details-grid">

<div class="detail-box">
<h4>Vendor Name</h4>
<p><?php echo htmlspecialchars($rfq['vendor_name']); ?></p>
</div>

<div class="detail-box">
<h4>Contact Person</h4>
<p><?php echo htmlspecialchars($rfq['contact_person']); ?></p>
</div>

<div class="detail-box">
<h4>Email</h4>
<p><?php echo htmlspecialchars($rfq['email']); ?></p>
</div>

<div class="detail-box">
<h4>Phone</h4>
<p><?php echo htmlspecialchars($rfq['phone']); ?></p>
</div>

</div>

</div>

</div>

</div>

</body>
</html>
