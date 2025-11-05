<?php
session_start();
include '../includes/connection.php';

// Clear previous session OTP
unset($_SESSION['otp_phone']);

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['generate_whatsapp'])) {
        $phone = trim($_POST['phone']);

        if (!empty($phone)) {
            $_SESSION['otp_phone'] = $phone;
            header("Location: otp.php"); // Redirect to OTP sender
            exit;
        } else {
            $error = "Please enter your phone number.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login | KCC Secure System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;700&family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: 'Poppins', sans-serif;
            letter-spacing: 0.4px;
        }

        body {
            margin: 0;
            height: 100vh;
            background: linear-gradient(120deg, #001f3f, #003366, #004d99, #0066cc);
            background-size: 400% 400%;
            animation: gradientMove 12s ease infinite;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            position: relative;
            color: #fff;
        }

        @keyframes gradientMove {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Floating light particles */
        .bg-bubbles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            overflow: hidden;
        }

        .bg-bubbles li {
            position: absolute;
            list-style: none;
            display: block;
            width: 20px;
            height: 20px;
            background: rgba(255, 255, 255, 0.15);
            bottom: -150px;
            animation: rise 25s infinite;
            transition-timing-function: linear;
        }

        .bg-bubbles li:nth-child(1) { left: 10%; animation-delay: 0s; }
        .bg-bubbles li:nth-child(2) { left: 20%; width: 25px; height: 25px; animation-delay: 2s; }
        .bg-bubbles li:nth-child(3) { left: 35%; width: 10px; height: 10px; animation-delay: 4s; }
        .bg-bubbles li:nth-child(4) { left: 50%; width: 30px; height: 30px; animation-delay: 1s; }
        .bg-bubbles li:nth-child(5) { left: 65%; width: 15px; height: 15px; animation-delay: 3s; }
        .bg-bubbles li:nth-child(6) { left: 80%; width: 25px; height: 25px; animation-delay: 5s; }

        @keyframes rise {
            0% { transform: translateY(0); opacity: 0.5; }
            100% { transform: translateY(-1000px); opacity: 0; }
        }

        .login-container {
            z-index: 1;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            padding: 50px 40px;
            border-radius: 25px;
            box-shadow: 0 0 25px rgba(255, 255, 255, 0.1);
            width: 100%;
            max-width: 420px;
            text-align: center;
            animation: fadeIn 2s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }

        h3 {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            margin-bottom: 25px;
            letter-spacing: 1px;
            color: #fff;
        }

        .form-control {
            border-radius: 12px;
            padding: 12px;
            background-color: rgba(255,255,255,0.85);
            border: none;
            text-align: center;
            font-weight: 500;
        }

        .form-control:focus {
            box-shadow: 0 0 10px rgba(0,255,255,0.7);
        }

        .btn {
            border-radius: 12px;
            padding: 12px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .btn-whatsapp {
            background: linear-gradient(90deg, #25D366, #128C7E);
            color: white;
        }

        .btn:hover {
            transform: scale(1.04);
            box-shadow: 0 4px 20px rgba(255,255,255,0.2);
        }

        .alert {
            border-radius: 10px;
        }

        .login-container img {
            width: 90px;
            margin-bottom: 15px;
            animation: floatLogo 4s ease-in-out infinite alternate;
        }

        @keyframes floatLogo {
            from { transform: translateY(0); }
            to { transform: translateY(-8px); }
        }

        .text-muted a {
            color: #fff;
            font-weight: 600;
        }

        .text-muted a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <ul class="bg-bubbles">
        <li></li><li></li><li></li><li></li><li></li><li></li>
    </ul>

    <div class="login-container">
        <img src="logo.png" alt="Logo">
        <h3>Welcome to <br>KCC SECURE SYSTEM</h3>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php elseif ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="form-group mb-3">
                <input type="text" name="phone" class="form-control" placeholder="📱 Enter your phone number (e.g. 60123456789)" required>
            </div>

            <button type="submit" name="generate_whatsapp" class="btn btn-whatsapp w-100 mb-3">
                <i class="fa-brands fa-whatsapp me-1"></i> Generate OTP via WhatsApp
            </button>

            <p class="text-light small">A one-time password will be sent to your WhatsApp. It will expire in 10 minutes.</p>
        </form>

        <p class="mt-3 text-muted small">Don’t have an account? <a href="userregister.php">Register here</a></p>
    </div>
</body>
</html>
