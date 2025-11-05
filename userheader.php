<?php ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Portal</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    body { font-family: Arial, sans-serif; }
    .navbar { background-color: #1e1e2f; }
    .navbar a, .navbar-brand { color: #fff !important; }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg">
  <a class="navbar-brand" href="userindex.php">🧾 KCC User Panel</a>
  <div class="collapse navbar-collapse">
    <ul class="navbar-nav ml-auto">
      <?php if (!isset($_SESSION['user'])): ?>
        <li class="nav-item"><a class="nav-link" href="userlogin.php">Login</a></li>
        <li class="nav-item"><a class="nav-link" href="userregister.php">Register</a></li>
      <?php else: ?>
        <li class="nav-item"><a class="nav-link" href="userindex.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="userlogout.php">Logout</a></li>
      <?php endif; ?>
    </ul>
  </div>
</nav>
