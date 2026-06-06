<?php
include 'db.php';
include 'config/constants.php';
include 'config/mail.php';

session_start();



if($_SERVER['REQUEST_METHOD'] != "POST")
{
    die("Invalid Request");
}

$po_id = $_POST['po_id'];
$vendor_id = $_POST['vendor_id'];
$invoice_number = $_POST['invoice_number'];
$grand_total = $_POST['grand_total'];

/* CHECK DUPLICATE */

$check = $conn->prepare(
"
SELECT invoice_id
FROM invoices
WHERE po_id=?
"
);

$check->bind_param(
"i",
$po_id
);

$check->execute();

$checkResult = $check->get_result();

if($checkResult->num_rows > 0)
{
    echo "
    <script>
    alert('Invoice Already Exists');
    window.history.back();
    </script>
    ";
    exit();
}

/* SAVE INVOICE */

$status = "generated";

$stmt = $conn->prepare(
"
INSERT INTO invoices
(
po_id,
vendor_id,
invoice_number,
invoice_amount,
invoice_date,
status,
created_at
)
VALUES
(
?,
?,
?,
?,
CURDATE(),
?,
NOW()
)
"
);

$stmt->bind_param(
"iisds",
$po_id,
$vendor_id,
$invoice_number,
$grand_total,
$status
);

if(!$stmt->execute())
{
    die("Invoice Insert Failed : ".$stmt->error);
}

mysqli_query(
$conn,
"INSERT INTO activity_logs
(
user_id,
role,
activity,
activity_time
)
VALUES
(
'{$_SESSION['user_id']}',
'officer',
'Generated Invoice $invoice_number',
NOW()
)"
);
/* FETCH VENDOR */

$vendorQuery = $conn->prepare(
"SELECT
company_name,
owner_name,
gst_number,
user_id,
vendor_id
FROM vendors
WHERE vendor_id=?"
);

$vendorQuery->bind_param(
"i",
$vendor_id
);

$vendorQuery->execute();

$vendorResult =
$vendorQuery->get_result();

$vendor =
$vendorResult->fetch_assoc();

/* GET EMAIL FROM USERS TABLE */

$userQuery = $conn->prepare(
"
SELECT email
FROM users
WHERE id=?
"
);

$userQuery->bind_param(
"i",
$vendor['user_id']
);

$userQuery->execute();

$userResult =
$userQuery->get_result();

$user =
$userResult->fetch_assoc();

$email = $user['email'];


/* SEND EMAIL */

$subject =
"Invoice Generated - ".$invoice_number;

$body = "

<h2>VendorBridge ERP</h2>

<p>Hello ".$vendor['owner_name'].",</p>

<p>
Invoice has been generated successfully.
</p>

<table border='1'
cellpadding='10'
cellspacing='0'>

<tr>
<th>Invoice Number</th>
<td>".$invoice_number."</td>
</tr>

<tr>
<th>Amount</th>
<td>₹".number_format($grand_total,2)."</td>
</tr>

<tr>
<th>Date</th>
<td>".date("d-m-Y")."</td>
</tr>

</table>

<br>

<p>
Please login to VendorBridge
for more details.
</p>

";

sendEmail(
$email,
$subject,
$body,
true
);

/* SUCCESS */

echo "
<script>

alert('Invoice Saved Successfully');

window.location='officer_dashboard.php';

</script>
";
?>