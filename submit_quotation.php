<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if(!isset($_GET['rfq_id']))
{
    header("Location: view_rfq.php");
    exit();
}

$rfq_id = $_GET['rfq_id'];

$vendorQuery = mysqli_query(
$conn,
"SELECT vendor_id
 FROM vendors
 WHERE user_id='$user_id'"
);

$vendorData =
mysqli_fetch_assoc($vendorQuery);

$vendor_id =
$vendorData['vendor_id'];

$rfqQuery = mysqli_query(
$conn,
"SELECT * FROM rfq
 WHERE rfq_id='$rfq_id'"
);

$rfq =
mysqli_fetch_assoc($rfqQuery);

$message = "";

if(isset($_POST['submit_quote']))
{
    $quotation_amount =
    $_POST['quotation_amount'];

    $delivery_days =
    $_POST['delivery_days'];

    $remarks =
    trim($_POST['remarks']);

    $fileName = "";

    if(isset($_FILES['quotation_file'])
    &&
    $_FILES['quotation_file']['error']==0)
    {
        $uploadDir =
        "uploads/quotations/";

        if(!is_dir($uploadDir))
        {
            mkdir(
            $uploadDir,
            0777,
            true
            );
        }

        $fileName =
        time().
        "_".
        basename(
        $_FILES['quotation_file']['name']
        );

        move_uploaded_file(
        $_FILES['quotation_file']['tmp_name'],
        $uploadDir.$fileName
        );
    }

    $check =
    mysqli_query(
    $conn,
    "SELECT quotation_id
     FROM quotations
     WHERE rfq_id='$rfq_id'
     AND vendor_id='$vendor_id'"
    );

    if(mysqli_num_rows($check)>0)
    {
        $message =
        "You have already submitted quotation.";
    }
    else
    {
        $sql = "
        INSERT INTO quotations
        (
            rfq_id,
            vendor_id,
            quotation_amount,
            delivery_days,
            remarks,
            quotation_file,
            status
        )
        VALUES
        (
            '$rfq_id',
            '$vendor_id',
            '$quotation_amount',
            '$delivery_days',
            '$remarks',
            '$fileName',
            'submitted'
        )
        ";

        if(mysqli_query($conn,$sql))
        {
            echo "
    <script>

    alert('Quotation Submitted Successfully');

    window.location='vendor_dashboard.php';

    </script>
    ";
    exit();
        }
        else
        {
            $message =
            "Database Error";
        }
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
Submit Quotation
</title>

<link
rel="stylesheet"
href="submit_quotation.css">

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

<h1>
Submit Quotation
</h1>

<?php
if($message!="")
{
?>

<div class="message">

<?php
echo $message;
?>

</div>

<?php
}
?>

<div class="rfq-info">

<h2>
<?php
echo $rfq['rfq_title'];
?>
</h2>

<p>

<strong>Category:</strong>

<?php
echo $rfq['category'];
?>

</p>

<p>

<strong>Quantity:</strong>

<?php
echo $rfq['quantity'];
?>

</p>

<p>

<strong>Budget:</strong>

₹<?php
echo number_format(
$rfq['estimated_budget']
);
?>

</p>

<p>

<strong>Deadline:</strong>

<?php
echo $rfq['deadline'];
?>

</p>

</div>

<form
method="POST"
enctype="multipart/form-data">

<div class="input-group">

<label>
Quotation Amount
</label>

<input
type="number"
name="quotation_amount"
required>

</div>

<div class="input-group">

<label>
Delivery Days
</label>

<input
type="number"
name="delivery_days"
required>

</div>

<div class="input-group">

<label>
Remarks
</label>

<textarea
name="remarks"
placeholder="Additional notes...">
</textarea>

</div>

<div class="input-group">

<label>
Upload Quotation PDF
</label>

<input
type="file"
name="quotation_file"
accept=".pdf"
required>

</div>

<button
type="submit"
name="submit_quote">

Submit Quotation

</button>

</form>

</div>

</div>

</body>

</html>