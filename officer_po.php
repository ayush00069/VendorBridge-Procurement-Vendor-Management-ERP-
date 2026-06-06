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

$message = "";

if(!isset($_GET['quotation_id']))
{
    die("Quotation ID Missing");
}

$quotation_id = intval($_GET['quotation_id']);

$query = "
SELECT
q.*,
v.vendor_id,
v.company_name,
r.rfq_title
FROM quotations q
INNER JOIN vendors v
ON q.vendor_id = v.vendor_id
INNER JOIN rfq r
ON q.rfq_id = r.rfq_id
WHERE q.quotation_id = '$quotation_id'
";

$result = mysqli_query($conn,$query);

if(mysqli_num_rows($result)==0)
{
    die("Quotation Not Found");
}

$data = mysqli_fetch_assoc($result);

if(isset($_POST['generate_po']))
{
    $vendor_id = $data['vendor_id'];
    $rfq_id = $data['rfq_id'];

    $issue_date = $_POST['issue_date'];
    $delivery_date = $_POST['delivery_date'];

    $total_amount = $data['quotation_amount'];

    $officerUserId = $_SESSION['user_id'];

    $officerQuery = mysqli_query(
    $conn,
    "SELECT officer_id
     FROM officers
     WHERE user_id='$officerUserId'"
    );

    $officerData =
    mysqli_fetch_assoc($officerQuery);

    $officer_id =
    $officerData['officer_id'];

    $po_number =
    "PO".date("Ymd").rand(100,999);

    $insert = "
    INSERT INTO purchase_orders
    (
        quotation_id,
        vendor_id,
        officer_id,
        po_number,
        total_amount,
        issue_date,
        delivery_date,
        status
    )
    VALUES
    (
        '$quotation_id',
        '$vendor_id',
        '$officer_id',
        '$po_number',
        '$total_amount',
        '$issue_date',
        '$delivery_date',
        'pending'
    )
    ";

    if(mysqli_query($conn,$insert))
    {
        mysqli_query(
$conn,
"INSERT INTO activity_logs
(user_id,role,activity,activity_time)
VALUES
(
'{$_SESSION['user_id']}',
'officer',
'Generated Purchase Order $po_number',
NOW()
)"
);

        mysqli_query(
        $conn,
        "UPDATE quotations
         SET status='selected'
         WHERE quotation_id='$quotation_id'"
        );

        mysqli_query(
        $conn,
        "UPDATE quotations
         SET status='rejected'
         WHERE rfq_id='$rfq_id'
         AND quotation_id!='$quotation_id'"
        );

        mysqli_query(
        $conn,
        "UPDATE rfq
         SET status='awarded'
         WHERE rfq_id='$rfq_id'"
        );

      $getPO = mysqli_query(
$conn,
"
SELECT po_id
FROM purchase_orders
ORDER BY po_id DESC
LIMIT 1
"
);

$poData = mysqli_fetch_assoc($getPO);

header(
"Location: po_details.php?po_id=".$poData['po_id']
);

exit();

exit();
    }
    else
    {
        $message =
        "Error Generating Purchase Order";
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
Generate Purchase Order
</title>

<link
rel="stylesheet"
href="officer_po.css">

<link
href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
rel="stylesheet">

</head>

<body>

<div class="container">

<div class="card">

<h1>
Generate Purchase Order
</h1>

<?php
if($message!="")
{
?>
<div class="message">
<?php echo $message; ?>
</div>
<?php
}
?>

<div class="details">

<p>

<strong>RFQ:</strong>

<?php echo $data['rfq_title']; ?>

</p>

<p>

<strong>Vendor:</strong>

<?php echo $data['company_name']; ?>

</p>

<p>

<strong>Quotation Amount:</strong>

₹<?php echo number_format($data['quotation_amount']); ?>

</p>

<p>

<strong>Delivery Days:</strong>

<?php echo $data['delivery_days']; ?>

 Days

</p>

</div>

<form method="POST">

<div class="form-group">

<label>
Issue Date
</label>

<input
type="date"
name="issue_date"
required>

</div>

<div class="form-group">

<label>
Delivery Date
</label>

<input
type="date"
name="delivery_date"
required>

</div>

<button
type="submit"
name="generate_po">

Generate PO

</button>

</form>

</div>

</div>

</body>

</html>