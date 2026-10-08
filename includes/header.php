<?php require_once __DIR__ . "/auth.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= isset($pageTitle) ? h($pageTitle) : 'CampusCart' ?></title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header class="site-header">
  <div class="container nav-wrap">
    <a class="logo" href="index.php">Campus<span>Cart</span></a>
    <nav>
      <a href="index.php">Home</a>
      <a href="products.php">Products</a>
      <a href="add_product.php">Sell</a>
      <a href="practicals/index.php">Practicals</a>
      <?php if (isset($_SESSION['user_id'])): ?>
        <a href="messages.php">Messages</a>
        <a href="my_products.php">My Products</a>
        <a href="logout.php">Logout</a>
      <?php else: ?>
        <a href="login.php">Login</a>
        <a class="nav-button" href="register.php">Register</a>
      <?php endif; ?>
    </nav>
  </div>
</header>
<main class="container page">
