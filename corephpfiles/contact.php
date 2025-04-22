<?php
// 1) ONE session_start() at the very top:
session_start();

require_once __DIR__ . '/../includes/DatabaseConnection.php';
require __DIR__ . '/../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// 2) Pull “flash” messages from session, then clear them.
$status = $_SESSION['status'] ?? '';
$error  = $_SESSION['error']  ?? '';
unset($_SESSION['status'], $_SESSION['error']);

// 3) Pre‑fill your name/email if you’re logged in:
$name  = $_SESSION['username'] ?? '';
$email = $_SESSION['email']    ?? '';

// 4) Handle POST submission:
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Override with whatever was POSTed:
    $name     = trim($_POST['name']     ?? $name);
    $email    = trim($_POST['email']    ?? $email);
    $to_email = trim($_POST['to_email'] ?? '');
    $message  = trim($_POST['message']  ?? '');

    // Validate
    if (empty($name) || empty($email) || empty($to_email) || empty($message)) {
        $error = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)
           || !filter_var($to_email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter valid email addresses.';
    } else {
        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'ngthtrungogjz@gmail.com';    // your Gmail
            $mail->Password   = 'vbpu njol wdvx easp';       // Gmail App Password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            $mail->SMTPDebug  = 2; // Show detailed errors while debugging
            
            // Disable SSL certificate verification (not for production)
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            
            $mail->setFrom('ngthtrungogjz@gmail.com', $name);
            $mail->addAddress($to_email);
            
            $mail->isHTML(false);
            $mail->Subject = "Message from $name";
            $mail->Body    = "Name: $name\nEmail: $email\n\n$message";
            
            // Try sending the message
            $mail->send();
            $status = 'Message sent successfully!';
        } catch (Exception $e) {
            $error = 'Mailer Error: ' . $mail->ErrorInfo;
        }
    } // End of else

    // Flash and redirect back to controller:
    $_SESSION['status'] = $status;
    $_SESSION['error']  = $error;
    header('Location: ' . basename(__FILE__));
    exit;
}

// 5) Render the inner form into your layout:
ob_start();
include __DIR__ . '/../templates/contact.html.php';
$content = ob_get_clean();
include __DIR__ . '/../templates/layout.html.php';
