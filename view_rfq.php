<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

$search = "";
$category = "";

$sql = "SELECT * FROM rfq WHERE status='open'";

if(isset($_GET['search']))
{
    $search = trim($_GET['search']);

    $sql .= " AND rfq_title LIKE '%$search%'";
}

if(isset($_GET['category']) && $_GET['category'] != "")
{
    $category = $_GET['category'];

    $sql .= " AND category='$category'";
}

$sql .= " ORDER BY created_at DESC";

$result = mysqli_query($conn,$sql);
?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Available RFQs</title>

<link rel="stylesheet" href="view_rfq.css">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>

<body>

<div class="container">

    <div class="header">

        <h1>
            Available RFQs
        </h1>

        <p>
            Browse and Participate in Procurement Opportunities
        </p>

    </div>

    <form method="GET" class="filter-box">

        <input
        type="text"
        name="search"
        placeholder="Search RFQ Title..."
        value="<?php echo $search; ?>">

        <select name="category">

            <option value="">
                All Categories
            </option>

            <option value="IT Equipment">
                IT Equipment
            </option>

            <option value="Furniture">
                Furniture
            </option>

            <option value="Construction">
                Construction
            </option>

            <option value="Office Supplies">
                Office Supplies
            </option>

            <option value="Services">
                Services
            </option>

        </select>

        <button type="submit">

            Search

        </button>

    </form>

    <div class="rfq-grid">

<?php

if(mysqli_num_rows($result) > 0)
{
    while($row = mysqli_fetch_assoc($result))
    {
?>

<div class="rfq-card">

    <div class="status">
        Open
    </div>

    <h2>
        <?php echo $row['rfq_title']; ?>
    </h2>

    <p class="category">
        <?php echo $row['category']; ?>
    </p>

    <p class="description">

        <?php

        echo substr(
            $row['description'],
            0,
            120
        );

        ?>...

    </p>

    <div class="details">

        <div>

            <strong>
                Quantity
            </strong>

            <span>
                <?php echo $row['quantity']; ?>
            </span>

        </div>

        <div>

            <strong>
                Budget
            </strong>

            <span>
                ₹<?php echo number_format($row['estimated_budget']); ?>
            </span>

        </div>

        <div>

            <strong>
                Deadline
            </strong>

            <span>
                <?php echo $row['deadline']; ?>
            </span>

        </div>

    </div>

    <div class="buttons">

        <a
        href="rfq_details.php?id=<?php echo $row['rfq_id']; ?>"
        class="view-btn">

        View Details

        </a>

        <a
        href="submit_quotation.php?rfq_id=<?php echo $row['rfq_id']; ?>"
        class="quote-btn">

        Submit Quote

        </a>

    </div>

</div>

<?php
    }
}
else
{
?>

<div class="no-data">

    No Open RFQs Available

</div>

<?php
}
?>

    </div>

</div>

</body>

</html>