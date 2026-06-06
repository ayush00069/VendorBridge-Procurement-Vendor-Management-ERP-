
<?php
session_start();

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

include 'db.php';

$page = "po";

$purchaseOrders = mysqli_query(
$conn,
"SELECT
po.*,
v.vendor_name
FROM purchase_orders po
LEFT JOIN vendors v
ON po.vendor_id=v.vendor_id
ORDER BY po.po_id DESC"
);

$totalPO = mysqli_num_rows(
mysqli_query(
$conn,
"SELECT * FROM purchase_orders"
));

$pendingPO = mysqli_num_rows(
mysqli_query(
$conn,
"SELECT * FROM purchase_orders
WHERE status='pending'"
));

$approvedPO = mysqli_num_rows(
mysqli_query(
$conn,
"SELECT * FROM purchase_orders
WHERE status='approved'"
));

$completedPO = mysqli_num_rows(
mysqli_query(
$conn,
"SELECT * FROM purchase_orders
WHERE status='completed'"
));
?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<title>Purchase Orders</title>

<link rel="stylesheet"
href="dashboard.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>

<body>

<?php include 'sidebar.php'; ?>

<div class="main-content">

    <div class="topbar">

        <div>

            <h1>Purchase Orders</h1>

            <p>
                Manage procurement purchase orders
            </p>

        </div>

        <div class="profile">

            <i class="fa-solid fa-user-circle"></i>

        </div>

    </div>

    <!-- Analytics -->

    <div class="cards">

        <div class="card">

            <i class="fa-solid fa-cart-shopping"></i>

            <h2>

                <?php echo $totalPO; ?>

            </h2>

            <p>Total Orders</p>

        </div>

        <div class="card">

            <i class="fa-solid fa-clock"></i>

            <h2>

                <?php echo $pendingPO; ?>

            </h2>

            <p>Pending</p>

        </div>

        <div class="card">

            <i class="fa-solid fa-check-circle"></i>

            <h2>

                <?php echo $approvedPO; ?>

            </h2>

            <p>Approved</p>

        </div>

        <div class="card">

            <i class="fa-solid fa-truck"></i>

            <h2>

                <?php echo $completedPO; ?>

            </h2>

            <p>Completed</p>

        </div>

    </div>

    <!-- Purchase Orders -->

    <div class="section">

        <div class="section-header">

            <div>

                <h2>Purchase Order Directory</h2>

                <p class="section-subtitle">

                    Approved quotations converted into purchase orders.

                </p>

            </div>

            <a href="generate_po.php"
            class="action-btn">

                <i class="fa-solid fa-plus"></i>

                Generate PO

            </a>

        </div>

        <table>

            <thead>

            <tr>

                <th>PO Number</th>
                <th>Vendor</th>
                <th>Amount</th>
                <th>Date</th>
                <th>Status</th>
                <th>Action</th>

            </tr>

            </thead>

            <tbody>

            <?php while($row=mysqli_fetch_assoc($purchaseOrders)){ ?>

            <tr>

                <td>

                    <?php echo $row['po_number']; ?>

                </td>

                <td>

                    <?php echo $row['vendor_name']; ?>

                </td>

                <td>

                    ₹<?php
                    echo number_format(
                    $row['amount'],
                    2
                    );
                    ?>

                </td>

                <td>

                    <?php echo $row['po_date']; ?>

                </td>

                <td>

                    <?php

                    if($row['status']=="approved")
                    {
                        echo '
                        <span class="status approved">
                        Approved
                        </span>';
                    }
                    elseif($row['status']=="completed")
                    {
                        echo '
                        <span class="status open">
                        Completed
                        </span>';
                    }
                    else
                    {
                        echo '
                        <span class="status pending">
                        Pending
                        </span>';
                    }

                    ?>

                </td>

             <td>

   <div class="action-group">

    <a href="view_po.php?id=<?php echo $row['po_id']; ?>"
    class="edit-btn">
        <i class="fa fa-eye"></i>
    </a>
    <a href="invoice.php?po_id=<?php echo $row['po_id']; ?>"
class="action-btn">
    <i class="fa-solid fa-file-invoice"></i>
    Invoice
</a>

</div>

</td>

            </tr>

            <?php } ?>

            </tbody>

        </table>

    </div>

</div>

</body>
</html>
