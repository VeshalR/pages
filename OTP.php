<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';
include '../includes/connection.php';

use Twilio\Rest\Client;

// Twilio credentials (use your own)
$account_sid = 'ACd7d3612c262bd0044c63532c34d9b246';
$auth_token  = '6711e32487f5fc586ee176ce97074bed';
$twilio_from = 'whatsapp:+14155238886';

// Ensure phone number exists
$phone = $_SESSION['otp_phone'] ?? '';
if (empty($phone)) {
    echo "<script>alert('Phone number missing. Please try again.'); window.location='userlogin.php';</script>";
    exit;
}

// Generate OTP and expiry
$otp = rand(100000, 999999);
$expiry = date('Y-m-d H:i:s', strtotime('+10 minutes'));

// Find or create user
$user_q = mysqli_query($db, "SELECT * FROM users WHERE phone='$phone'");
if (mysqli_num_rows($user_q) == 0) {
    mysqli_query($db, "INSERT INTO users (phone) VALUES ('$phone')");
    $user_id = mysqli_insert_id($db);
} else {
    $user = mysqli_fetch_assoc($user_q);
    $user_id = $user['id'];
}

// Save OTP to table (make sure users_otp table exists)
mysqli_query($db, "INSERT INTO users_otp (user_id, otp, expiry) VALUES ('$user_id', '$otp', '$expiry')");

// Send OTP via Twilio WhatsApp
try {
    $client = new Client($account_sid, $auth_token);
    $to = 'whatsapp:+6' . $phone; // Format must be e.g. whatsapp:+60123456789

    $client->messages->create($to, [
        'from' => $twilio_from,
        'body' => "🔐 *KCC Secure System*\n\nYour login OTP is: *$otp*\n\nThis code expires in 10 minutes.\nDo not share this with anyone."
    ]);

    echo "<script>alert('OTP sent successfully to WhatsApp +$phone'); window.location='verify_otp.php';</script>";
} catch (Exception $e) {
    $err = addslashes($e->getMessage());
    echo "<script>alert('Failed to send WhatsApp message: $err'); window.location='userlogin.php';</script>";
}
?>
