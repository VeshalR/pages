<?php
session_start();
include '../includes/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $otp = trim($_POST['otp']);
    $phone = $_SESSION['otp_phone'];

    $user = mysqli_fetch_assoc(mysqli_query($db, "SELECT * FROM users WHERE phone='$phone'"));
    $user_id = $user['id'];

    $result = mysqli_query($db, "SELECT * FROM users_otp WHERE user_id='$user_id' ORDER BY id DESC LIMIT 1");
    $otp_data = mysqli_fetch_assoc($result);

    if ($otp_data) {
        if (strtotime($otp_data['expiry']) < time()) {
            $error = "❌ OTP expired. Please request a new one.";
        } elseif ($otp_data['otp'] === $otp) {
            $_SESSION['user'] = $user;
            header("Location: userindex.php");
            exit;
        } else {
            $error = "❌ Invalid OTP. Please try again.";
        }
    } else {
        $error = "No OTP found for this number.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Verify OTP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-light d-flex align-items-center justify-content-center vh-100">
    <div class="card p-4 text-center" style="width: 350px;">
        <h5>Enter OTP</h5>
        <?php if (!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
        <form method="post">
            <input type="text" name="otp" class="form-control mb-3 text-center" placeholder="Enter 6-digit OTP" required>
            <button type="submit" class="btn btn-primary w-100">Verify</button>
        </form>
    </div>
</body>
</html>
