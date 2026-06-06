<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* UPDATE FIELD */

if(isset($_POST['update']))
{
    $field = $_POST['field'];
    $new_value = trim($_POST['new_value']);

    $allowed = [
        'company_name',
        'owner_name',
        'business_type',
        'address',
        'city',
        'state',
        'pincode',
        'bank_name',
        'account_number',
        'ifsc_code'
    ];

    if(in_array($field,$allowed))
    {
        $sql = "UPDATE vendors SET $field=? WHERE user_id=?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si",$new_value,$user_id);

        if($stmt->execute())
        {
            header("Location: vendor_profile.php");
            exit();
        }
    }
}

/* CREATE PROFILE */

if(isset($_POST['save_profile']))
{
    $company_name = trim($_POST['company_name']);
    $gst_number = trim($_POST['gst_number']);
    $owner_name = trim($_POST['owner_name']);
    $business_type = trim($_POST['business_type']);
    $address = trim($_POST['address']);
    $city = trim($_POST['city']);
    $state = trim($_POST['state']);
    $pincode = trim($_POST['pincode']);
    $bank_name = trim($_POST['bank_name']);
    $account_number = trim($_POST['account_number']);
    $ifsc_code = trim($_POST['ifsc_code']);

    $stmt = $conn->prepare("
        INSERT INTO vendors
        (
            user_id,
            company_name,
            gst_number,
            owner_name,
            business_type,
            address,
            city,
            state,
            pincode,
            bank_name,
            account_number,
            ifsc_code
        )
        VALUES
        (?,?,?,?,?,?,?,?,?,?,?,?)
    ");

    $stmt->bind_param(
        "isssssssssss",
        $user_id,
        $company_name,
        $gst_number,
        $owner_name,
        $business_type,
        $address,
        $city,
        $state,
        $pincode,
        $bank_name,
        $account_number,
        $ifsc_code
    );

    if($stmt->execute())
    {
        header("Location: vendor_profile.php");
        exit();
    }
}

/* FETCH PROFILE */

$stmt = $conn->prepare(
"SELECT * FROM vendors WHERE user_id=?"
);

$stmt->bind_param("i",$user_id);
$stmt->execute();

$result = $stmt->get_result();

$profileExists = false;

if($result->num_rows > 0)
{
    $profileExists = true;
    $vendor = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Vendor Profile</title>

<link rel="stylesheet" href="vendor_profile.css">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

</head>

<body>

<div class="container">

<?php if(!$profileExists){ ?>

<div class="card">

<h2>Complete Vendor Profile</h2>

<form method="POST">

<input type="text"
name="company_name"
placeholder="Company Name"
required>

<input type="text"
name="gst_number"
placeholder="GST Number"
required>

<input type="text"
name="owner_name"
placeholder="Owner Name"
required>

<input type="text"
name="business_type"
placeholder="Business Type"
required>

<textarea
name="address"
placeholder="Address"
required></textarea>

<input type="text"
name="city"
placeholder="City"
required>

<input type="text"
name="state"
placeholder="State"
required>

<input type="text"
name="pincode"
placeholder="Pincode"
required>

<input type="text"
name="bank_name"
placeholder="Bank Name"
required>

<input type="text"
name="account_number"
placeholder="Account Number"
required>

<input type="text"
name="ifsc_code"
placeholder="IFSC Code"
required>

<button
type="submit"
name="save_profile">

Save Profile

</button>

</form>

</div>

<?php } else { ?>

<div class="profile-header">

<h2>
<?php echo $vendor['company_name']; ?>
</h2>

<p>
GST Number :
<?php echo $vendor['gst_number']; ?>
</p>

</div>

<div class="profile-card">

<div class="row">
<div class="label">Company Name</div>
<div class="value"><?php echo $vendor['company_name']; ?></div>
<div><a href="?edit=company_name" class="edit-btn">Edit</a></div>
</div>

<div class="row">
<div class="label">Owner Name</div>
<div class="value"><?php echo $vendor['owner_name']; ?></div>
<div><a href="?edit=owner_name" class="edit-btn">Edit</a></div>
</div>

<div class="row">
<div class="label">Business Type</div>
<div class="value"><?php echo $vendor['business_type']; ?></div>
<div><a href="?edit=business_type" class="edit-btn">Edit</a></div>
</div>

<div class="row">
<div class="label">Address</div>
<div class="value"><?php echo $vendor['address']; ?></div>
<div><a href="?edit=address" class="edit-btn">Edit</a></div>
</div>

<div class="row">
<div class="label">City</div>
<div class="value"><?php echo $vendor['city']; ?></div>
<div><a href="?edit=city" class="edit-btn">Edit</a></div>
</div>

<div class="row">
<div class="label">State</div>
<div class="value"><?php echo $vendor['state']; ?></div>
<div><a href="?edit=state" class="edit-btn">Edit</a></div>
</div>

<div class="row">
<div class="label">Pincode</div>
<div class="value"><?php echo $vendor['pincode']; ?></div>
<div><a href="?edit=pincode" class="edit-btn">Edit</a></div>
</div>

<div class="row">
<div class="label">Bank Name</div>
<div class="value"><?php echo $vendor['bank_name']; ?></div>
<div><a href="?edit=bank_name" class="edit-btn">Edit</a></div>
</div>

<div class="row">
<div class="label">Account Number</div>
<div class="value"><?php echo $vendor['account_number']; ?></div>
<div><a href="?edit=account_number" class="edit-btn">Edit</a></div>
</div>

<div class="row">
<div class="label">IFSC Code</div>
<div class="value"><?php echo $vendor['ifsc_code']; ?></div>
<div><a href="?edit=ifsc_code" class="edit-btn">Edit</a></div>
</div>

</div>

<?php
if(isset($_GET['edit']))
{
$field = $_GET['edit'];
?>

<div class="edit-box">

<h3>
Edit <?php echo ucwords(str_replace("_"," ",$field)); ?>
</h3>

<form method="POST">

<input
type="hidden"
name="field"
value="<?php echo $field; ?>">

<input
type="text"
name="new_value"
value="<?php echo $vendor[$field]; ?>"
required>

<button
type="submit"
name="update">

Update

</button>

</form>

</div>

<?php
}
?>

<?php } ?>

</div>

</body>
</html>