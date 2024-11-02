<?php
include('query.php'); // Include your PDO connection

if (isset($_GET['invoice_id'])) {
    $invoice_id = $_GET['invoice_id'];

    // Query to get the invoice details
    $stmt = $pdo->prepare("SELECT * FROM invoice WHERE invoice_id = :invoice_id");
    $stmt->bindParam(':invoice_id', $invoice_id);
    $stmt->execute();
    $invoice = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$invoice) {
        echo "Invoice not found!";
        exit;
    }
} else {
    echo "No invoice ID provided!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #<?php echo $invoice['invoice_id']; ?></title>
    <style>
        body { font-family: Arial, sans-serif; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; }
        .invoice-box h2 { text-align: center; }
        table { width: 100%; line-height: inherit; text-align: left; }
        table td { padding: 5px; vertical-align: top; }
        table tr td:nth-child(2) { text-align: right; }
        table th { background: #eee; border-bottom: 1px solid #ddd; font-weight: bold; }
    </style>
</head>
<body>

<div class="invoice-box">
    <h2>Invoice</h2>
    <p>Invoice ID: <?php echo $invoice['invoice_id']; ?></p>
    <p>Date: <?php echo $invoice['dateTime']; ?></p>
    <p>Status: <?php echo ucfirst($invoice['status']); ?></p>

    <hr>

    <table cellpadding="0" cellspacing="0">
        <tr class="heading">
            <th>Customer Name</th>
            <th>Email</th>
        </tr>
        <tr>
            <td><?php echo $invoice['u_name']; ?></td>
            <td><?php echo $invoice['u_email']; ?></td>
        </tr>
    </table>

    <br>

    <table cellpadding="0" cellspacing="0">
        <tr class="heading">
            <th>Quantity</th>
            <th>Total Amount</th>
        </tr>
        <tr>
            <td><?php echo $invoice['total_Qty']; ?></td>
            <td><?php echo number_format($invoice['total_amount'], 2); ?> USD</td>
        </tr>
    </table>
</div>

</body>
</html>
