
<?php
session_start();

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

include 'db.php';

$page = "quotation";

$quotations = mysqli_query(
$conn,
"SELECT * FROM quotations
ORDER BY quotation_id DESC"
);

$totalQuotes = mysqli_num_rows(
mysqli_query(
$conn,
"SELECT * FROM quotations"
));

$approvedQuotes = mysqli_num_rows(
mysqli_query(
$conn,
"SELECT * FROM quotations
WHERE status='approved'"
));

$pendingQuotes = mysqli_num_rows(
mysqli_query(
$conn,
"SELECT * FROM quotations
WHERE status='submitted'"
));

$rejectedQuotes = mysqli_num_rows(
mysqli_query(
$conn,
"SELECT * FROM quotations
WHERE status='rejected'"
));
?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<title>Quotation Management</title>

<link rel="stylesheet"
href="dashboard.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>

<body>

<?php include 'sidebar.php'; ?>

<div class="main-content">

    <!-- Topbar -->

    <div class="topbar">

        <div>

            <h1>Quotation Management</h1>

            <p>
                Compare and manage vendor quotations
            </p>

        </div>

        <div class="profile">

            <i class="fa-solid fa-user-circle"></i>

        </div>

    </div>

    <!-- Cards -->

    <div class="cards">

        <div class="card">

            <i class="fa-solid fa-file-invoice-dollar"></i>

            <h2>
                <?php echo $totalQuotes; ?>
            </h2>

            <p>Total Quotations</p>

        </div>

        <div class="card">

            <i class="fa-solid fa-circle-check"></i>

            <h2>
                <?php echo $approvedQuotes; ?>
            </h2>

            <p>Approved</p>

        </div>

        <div class="card">

            <i class="fa-solid fa-clock"></i>

            <h2>
                <?php echo $pendingQuotes; ?>
            </h2>

            <p>Pending</p>

        </div>

        <div class="card">

            <i class="fa-solid fa-circle-xmark"></i>

            <h2>
                <?php echo $rejectedQuotes; ?>
            </h2>

            <p>Rejected</p>

        </div>

    </div>

    <!-- Quotation Table -->

   <div class="section-header">

    <h2>Quotation Directory</h2>

    <div class="section-actions">

        <a href="compare_quotation.php"
        class="action-btn">

            <i class="fa-solid fa-scale-balanced"></i>

            Compare Quotes

        </a>

    </div>

</div>

        <table>

            <thead>

            <tr>

                <th>ID</th>
                <th>RFQ ID</th>
                <th>Vendor ID</th>
                <th>Price</th>
                <th>Delivery Days</th>
                <th>Status</th>
                <th>Action</th>

            </tr>

            </thead>

            <tbody>

            <?php while($row=mysqli_fetch_assoc($quotations)){ ?>

            <tr>

                <td>

                    #<?php echo $row['quotation_id']; ?>

                </td>

                <td>

                    RFQ-<?php echo $row['rfq_id']; ?>

                </td>

                <td>

                    Vendor-<?php echo $row['vendor_id']; ?>

                </td>

                <td>

                    ₹<?php echo number_format($row['price'],2); ?>

                </td>

                <td>

                    <?php echo $row['delivery_days']; ?> Days

                </td>

                <td>

                    <?php

                    if($row['status']=="approved")
                    {
                        echo '<span class="status approved">Approved</span>';
                    }
                    elseif($row['status']=="rejected")
                    {
                        echo '<span class="status rejected">Rejected</span>';
                    }
                    else
                    {
                        echo '<span class="status pending">Pending</span>';
                    }

                    ?>

                </td>

                <td>

                    <a
                    href="view_quotation.php?id=<?php echo $row['quotation_id']; ?>"
                    class="edit-btn">

                        <i class="fa-solid fa-eye"></i>

                    </a>

                    <a
                    href="edit_quotation.php?id=<?php echo $row['quotation_id']; ?>"
                    class="edit-btn">

                        <i class="fa-solid fa-pen"></i>

                    </a>

                </td>

            </tr>

            <?php } ?>

            </tbody>

        </table>

    </div>

</div>

</body>
</html>
