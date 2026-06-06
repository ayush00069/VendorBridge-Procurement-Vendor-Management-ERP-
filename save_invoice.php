
<?php

session_start();

include 'db.php';

try
{

    if($_SERVER['REQUEST_METHOD'] != 'POST')
    {
        throw new Exception("Invalid Request");
    }

    $invoice_number =
    mysqli_real_escape_string(
    $conn,
    $_POST['invoice_number']
    );

    $po_id =
    (int)$_POST['po_id'];

    $vendor_id =
    (int)$_POST['vendor_id'];

    $subtotal =
    (float)$_POST['subtotal'];

    $gst_amount =
    (float)$_POST['gst_amount'];

    $grand_total =
    (float)$_POST['grand_total'];

    /*
    =================================
    CHECK DUPLICATE INVOICE
    =================================
    */

    $check =
    mysqli_query(
    $conn,
    "SELECT invoice_id
    FROM invoices
    WHERE po_id='$po_id'"
    );

    if(mysqli_num_rows($check) > 0)
    {
        echo "
        <script>
        alert('Invoice Already Exists');
        window.location='invoice.php';
        </script>";
        exit();
    }

    /*
    =================================
    SAVE INVOICE
    =================================
    */

    $save =
    mysqli_query(
    $conn,
    "INSERT INTO invoices
    (
        po_id,
        vendor_id,
        invoice_number,
        amount,
        gst_amount,
        total_amount,
        invoice_date,
        status
    )
    VALUES
    (
        '$po_id',
        '$vendor_id',
        '$invoice_number',
        '$subtotal',
        '$gst_amount',
        '$grand_total',
        CURDATE(),
        'generated'
    )"
    );

    if(!$save)
    {
        throw new Exception(
        mysqli_error($conn)
        );
    }

    /*
    =================================
    FETCH VENDOR DETAILS
    =================================
    */

    $vendorQuery =
    mysqli_query(
    $conn,
    "SELECT
        po.po_number,
        v.vendor_name,
        v.email,
        v.contact_person,
        v.gst_number
    FROM purchase_orders po
    LEFT JOIN vendors v
    ON po.vendor_id=v.vendor_id
    WHERE po.po_id='$po_id'"
    );

    $vendor =
    mysqli_fetch_assoc(
    $vendorQuery
    );

    /*
    =================================
    SEND EMAIL
    =================================
    */

    if(
    file_exists(
    'config/mail.php'
    ))
    {

        require_once
        'config/mail.php';

        if(
        function_exists(
        'sendEmail'
        ) && $vendor
        )
        {

            $subject =
            "Invoice Generated - "
            .$invoice_number;

            $body = "

            <h2 style='color:#2563eb'>
            VendorBridge ERP
            </h2>

            <p>
            Dear
            <strong>
            ".$vendor['vendor_name']."
            </strong>,
            </p>

            <p>
            Your invoice has been generated successfully.
            Below are the invoice details:
            </p>

            <table
            border='1'
            cellpadding='10'
            cellspacing='0'
            width='700'>

            <tr>
                <th align='left'>
                Invoice Number
                </th>
                <td>
                ".$invoice_number."
                </td>
            </tr>

            <tr>
                <th align='left'>
                PO Number
                </th>
                <td>
                ".$vendor['po_number']."
                </td>
            </tr>

            <tr>
                <th align='left'>
                Subtotal
                </th>
                <td>
                ₹".number_format(
                $subtotal,
                2
                )."
                </td>
            </tr>

            <tr>
                <th align='left'>
                GST (18%)
                </th>
                <td>
                ₹".number_format(
                $gst_amount,
                2
                )."
                </td>
            </tr>

            <tr>
                <th align='left'>
                Grand Total
                </th>
                <td>
                <strong>
                ₹".number_format(
                $grand_total,
                2
                )."
                </strong>
                </td>
            </tr>

            </table>

            <br>

            <h4>
            Terms & Conditions
            </h4>

            <ul>

                <li>
                Payment due within 30 days.
                </li>

                <li>
                GST charged as per government regulations.
                </li>

                <li>
                Late payment may attract penalties.
                </li>

                <li>
                This is a system generated invoice.
                </li>

            </ul>

            <br>

            Regards,
            <br>
            VendorBridge ERP Team

            ";

            sendEmail(
            $vendor['email'],
            $subject,
            $body,
            true
            );

        }
    }

    /*
    =================================
    SUCCESS
    =================================
    */

    echo "
    <script>
    alert('Invoice Generated Successfully');
    window.location='purchase_orders.php';
    </script>";

}
catch(Exception $e)
{

    echo "
    <script>
    alert('Error : ".$e->getMessage()."');
    window.history.back();
    </script>";

}
?>
