<?php
include '../includes/connection.php';
session_start();

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $captcha_input = trim($_POST['captcha_input']);

    // Check CAPTCHA
    if ($captcha_input !== $_SESSION['captcha_code']) {
        $error = "Invalid CAPTCHA. Please try again.";
    } else {
        // Check if phone already exists
        $check = mysqli_query($db, "SELECT * FROM users WHERE phone = '$phone'");
        if (mysqli_num_rows($check) > 0) {
            $error = "Phone number already registered!";
        } else {
            // Insert new user
            mysqli_query($db, "INSERT INTO users (username, phone) VALUES ('$name', '$phone')");
            $success = "✅ Registration successful! You can now <a href='userlogin.php'>login here</a>.";
        }
    }
}

// Generate new CAPTCHA
$captcha_code = substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 5);
$_SESSION['captcha_code'] = $captcha_code;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

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

        /* Floating particles */
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
            animation: rise 25s infinite linear;
        }

        .bg-bubbles li:nth-child(1) { left: 10%; animation-delay: 0s; }
        .bg-bubbles li:nth-child(2) { left: 25%; width: 25px; height: 25px; animation-delay: 2s; }
        .bg-bubbles li:nth-child(3) { left: 40%; width: 10px; height: 10px; animation-delay: 4s; }
        .bg-bubbles li:nth-child(4) { left: 55%; width: 30px; height: 30px; animation-delay: 1s; }
        .bg-bubbles li:nth-child(5) { left: 70%; width: 15px; height: 15px; animation-delay: 3s; }
        .bg-bubbles li:nth-child(6) { left: 85%; width: 25px; height: 25px; animation-delay: 5s; }

        @keyframes rise {
            0% { transform: translateY(0); opacity: 0.6; }
            100% { transform: translateY(-1000px); opacity: 0; }
        }

        .register-container {
            z-index: 1;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            padding: 50px 40px;
            border-radius: 25px;
            box-shadow: 0 0 25px rgba(255, 255, 255, 0.15);
            width: 100%;
            max-width: 420px;
            text-align: center;
            animation: fadeIn 2s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }

        h2 {
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
            font-weight: 500;
            text-align: center;
        }

        .form-control:focus {
            box-shadow: 0 0 10px rgba(0,255,255,0.7);
        }

        .btn-register {
            background: linear-gradient(90deg, #00C853, #00796B);
            color: white;
            border-radius: 12px;
            padding: 12px;
            font-weight: 600;
            transition: 0.3s;
            width: 100%;
            letter-spacing: 0.5px;
        }

        .btn-register:hover {
            transform: scale(1.04);
            box-shadow: 0 4px 20px rgba(255,255,255,0.2);
        }

        .alert {
            border-radius: 10px;
        }

        .register-container img {
            width: 90px;
            margin-bottom: 15px;
            animation: floatLogo 4s ease-in-out infinite alternate;
        }

        @keyframes floatLogo {
            from { transform: translateY(0); }
            to { transform: translateY(-8px); }
        }

        .captcha-box {
            background: #000;
            color: #fff;
            font-weight: bold;
            letter-spacing: 3px;
            padding: 10px 20px;
            border-radius: 8px;
            user-select: none;
            font-size: 1.2rem;
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

    <div class="register-container">
        <img src="logo.png" alt="Logo">
        <h2>Create Your Account</h2>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php elseif ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="post" autocomplete="off">
            <div class="form-group mb-3">
                <input type="text" name="name" class="form-control" required placeholder="👤 Enter your full name">
            </div>

            <div class="form-group mb-3">
                <input type="text" name="phone" class="form-control" required placeholder="📱 Enter your phone number">
            </div>

            <div class="form-group mb-3">
                <div class="d-flex align-items-center justify-content-center">
                    <div class="captcha-box me-3"><?= $_SESSION['captcha_code']; ?></div>
                    <input type="text" name="captcha_input" class="form-control" placeholder="Enter CAPTCHA" required>
                </div>
            </div>

            <button class="btn-register">Register</button>

            <p class="mt-3 text-muted small">
                Already have an account? <a href="userlogin.php">Login here</a>
            </p>
        </form>
    </div>
</body>
</html>
