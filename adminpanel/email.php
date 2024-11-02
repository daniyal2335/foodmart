<?php
include('query.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';

if (isset($_POST['sendEmail'])) {
    $invoice_id = $_POST['invoice_id']; // Retrieve the invoice ID from the form

    // Fetch the user's email based on the invoice ID
    $query = $pdo->prepare("SELECT u.email 
                            FROM users u 
                            INNER JOIN invoice i ON u.id = i.u_id 
                            WHERE i.invoice_id = :invoice_id");
    $query->bindParam(':invoice_id', $invoice_id);
    $query->execute();
    $user = $query->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Use the correct field name 'email' since that's what your query is selecting
        $userEmail = $user['email'];

        // Update the invoice status to 'approved'
        $updateQuery = $pdo->prepare("UPDATE invoice 
                                      SET status = 'approved' 
                                      WHERE invoice_id = :invoice_id");
        $updateQuery->bindParam(':invoice_id', $invoice_id);

        if ($updateQuery->execute()) {
            // Prepare to send email
            $mail = new PHPMailer(true);
            try {
                // SMTP server configuration
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'daniyalrizwankhan2332@gmail.com';
                $mail->Password   = 'ttwebxlauefjjaim';  // Use app-specific password for Gmail
                $mail->SMTPSecure = 'tls';
                $mail->Port       = 587;

                // Email content settings
                $mail->setFrom('daniyalrizwankhan2332@gmail.com', 'Daniyal');
                $mail->addAddress($userEmail);  // Send to the user's email
                $mail->isHTML(true);
                $mail->Subject = 'Invoice Approved';
                $mail->Body = 'Thank you for your purchase. Your invoice has been approved.';

                // Send the email
                $mail->send();
                echo '<script>alert("Invoice approved and email sent!");location.assign("invoice.php");</script>';
            } catch (Exception $e) {
                echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            echo '<script>alert("Error updating invoice status.");</script>';
        }
    } else {
        echo '<script>alert("User not found for the given invoice.");</script>';
    }
}
?>
