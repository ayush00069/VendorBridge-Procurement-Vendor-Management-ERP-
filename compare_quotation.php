
<?php
session_start();

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

include 'db.php';

$page = "quotation";

$quotes = mysqli_query(
$conn,
"SELECT
q.*,
v.vendor_name,
v.rating
FROM quotations q
LEFT JOIN vendors v
ON q.vendor_id = v.vendor_id
ORDER BY q.price ASC"
);

$totalQuotes = mysqli_num_rows($quotes);

$lowestPriceData = mysqli_fetch_assoc(
mysqli_query(
$conn,
"SELECT MIN(price) AS min_price
FROM quotations"
)
);

$lowestPrice = $lowestPriceData['min_price'] ?? 0;
?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<title>Compare Quotations</title>

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

            <h1>Quotation Comparison</h1>

            <p>
                Compare vendor quotations and select the best procurement option
            </p>

        </div>

        <div class="topbar-actions">

            <a href="quotations.php"
            class="back-btn">

                <i class="fa-solid fa-arrow-left"></i>

                Back

            </a>

            <div class="profile">

                <i class="fa-solid fa-user-circle"></i>

            </div>

        </div>

    </div>

    <!-- Analytics Cards -->

    <div class="cards">

        <div class="card">

            <i class="fa-solid fa-file-invoice-dollar"></i>

            <h2>

                <?php echo $totalQuotes; ?>

            </h2>

            <p>Total Quotations</p>

        </div>

        <div class="card">

            <i class="fa-solid fa-indian-rupee-sign"></i>

            <h2>

                ₹<?php echo number_format($lowestPrice); ?>

            </h2>

            <p>Lowest Quote</p>

        </div>

        <div class="card">

            <i class="fa-solid fa-star"></i>

            <h2>4.8</h2>

            <p>Vendor Rating</p>

        </div>

        <div class="card">

            <i class="fa-solid fa-check-circle"></i>

            <h2>98%</h2>

            <p>Approval Success</p>

        </div>

    </div>

    <!-- Comparison Section -->

    <div class="section">

        <div class="section-header">

            <div>

                <h2>Vendor Comparison Matrix</h2>

                <p class="section-subtitle">

                    Lowest quotation is highlighted automatically.

                </p>

            </div>

        </div>

        <table>

            <thead>

            <tr>

                <th>Vendor</th>
                <th>RFQ ID</th>
                <th>Quoted Price</th>
                <th>Delivery Days</th>
                <th>Rating</th>
                <th>Status</th>
                <th>Action</th>

            </tr>

            </thead>

            <tbody>

            <?php

            mysqli_data_seek($quotes,0);

            while($row=mysqli_fetch_assoc($quotes))
            {

                $isLowest =
                ($row['price'] == $lowestPrice);

            ?>

            <tr
            <?php if($isLowest){ ?>
            style="background:#ecfdf5;"
            <?php } ?>>

                <td>

                    <strong>

                    <?php
                    echo $row['vendor_name'];
                    ?>

                    </strong>

                    <?php if($isLowest){ ?>

                    <br>

                    <span class="status open">

                        Best Price

                    </span>

                    <?php } ?>

                </td>

                <td>

                    RFQ-<?php
                    echo $row['rfq_id'];
                    ?>

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

                    <?php
                    echo $row['delivery_days'];
                    ?>

                    Days

                </td>

                <td>

                    ⭐

                    <?php
                    echo $row['rating'];
                    ?>

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
                    if($row['status']!="approved")
                    {
                    ?>

                    <a
                    href="approve_quote.php?id=<?php echo $row['quotation_id']; ?>"
                    class="action-btn">

                        <i class="fa-solid fa-check"></i>

                        Approve

                    </a>

                    <?php
                    }
                    else
                    {
                        echo '
                        <span class="status approved">
                        Selected
                        </span>';
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