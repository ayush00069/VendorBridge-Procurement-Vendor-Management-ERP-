
<?php
session_start();

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

include 'db.php';

$page = "approval";

$approvals = mysqli_query(
$conn,
"SELECT
q.*,
v.vendor_name
FROM quotations q
LEFT JOIN vendors v
ON q.vendor_id=v.vendor_id
ORDER BY q.quotation_id DESC"
);

$totalApprovals = mysqli_num_rows(
mysqli_query(
$conn,
"SELECT * FROM quotations"
));

$approvedCount = mysqli_num_rows(
mysqli_query(
$conn,
"SELECT * FROM quotations
WHERE status='approved'"
));

$pendingCount = mysqli_num_rows(
mysqli_query(
$conn,
"SELECT * FROM quotations
WHERE status='submitted'"
));

$rejectedCount = mysqli_num_rows(
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

<title>Approval Workflow</title>

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

            <h1>Approval Workflow</h1>

            <p>
                Review and approve procurement quotations
            </p>

        </div>

        <div class="profile">

            <i class="fa-solid fa-user-circle"></i>

        </div>

    </div>

    <!-- Cards -->

    <div class="cards">

        <div class="card">

            <i class="fa-solid fa-file-circle-check"></i>

            <h2>

                <?php echo $totalApprovals; ?>

            </h2>

            <p>Total Requests</p>

        </div>

        <div class="card">

            <i class="fa-solid fa-check-circle"></i>

            <h2>

                <?php echo $approvedCount; ?>

            </h2>

            <p>Approved</p>

        </div>

        <div class="card">

            <i class="fa-solid fa-clock"></i>

            <h2>

                <?php echo $pendingCount; ?>

            </h2>

            <p>Pending</p>

        </div>

        <div class="card">

            <i class="fa-solid fa-xmark-circle"></i>

            <h2>

                <?php echo $rejectedCount; ?>

            </h2>

            <p>Rejected</p>

        </div>

    </div>

    <!-- Approval Table -->

    <div class="section">

        <div class="section-header">

            <div>

                <h2>Approval Requests</h2>

                <p class="section-subtitle">

                    Review vendor quotations before generating purchase orders.

                </p>

            </div>

        </div>

        <table>

            <thead>

            <tr>

                <th>ID</th>
                <th>Vendor</th>
                <th>RFQ</th>
                <th>Quoted Price</th>
                <th>Delivery</th>
                <th>Status</th>
                <th>Action</th>

            </tr>

            </thead>

            <tbody>

            <?php while($row=mysqli_fetch_assoc($approvals)){ ?>

            <tr>

                <td>

                    #<?php echo $row['quotation_id']; ?>

                </td>

                <td>

                    <?php echo $row['vendor_name']; ?>

                </td>

                <td>

                    RFQ-<?php echo $row['rfq_id']; ?>

                </td>

                <td>

                    ₹<?php
                    echo number_format(
                    $row['price'],
                    2
                    );
                    ?>

                </td>

                <td>

                    <?php echo $row['delivery_days']; ?>

                    Days

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
                    elseif($row['status']=="rejected")
                    {
                        echo '
                        <span class="status rejected">
                        Rejected
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

                    <?php
                    if($row['status']=="submitted")
                    {
                    ?>

                    <a
                    href="approve_request.php?id=<?php echo $row['quotation_id']; ?>"
                    class="edit-btn">

                        <i class="fa-solid fa-check"></i>

                    </a>

                    <a
                    href="reject_request.php?id=<?php echo $row['quotation_id']; ?>"
                    class="delete-btn">

                        <i class="fa-solid fa-xmark"></i>

                    </a>

                    <?php
                    }
                    else
                    {
                        echo "-";
                    }
                    ?>

                </td>

            </tr>

            <?php } ?>

            </tbody>

        </table>

    </div>

</div>

</body>
</html>