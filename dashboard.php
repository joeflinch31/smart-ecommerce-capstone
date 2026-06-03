<?php
session_start();
if (!isset($_SESSION['user_id'])) header("Location: login.php");
?>
<!DOCTYPE html>
<html>
<head><title>Dashboard</title><link rel="stylesheet" href="css/style.css"></head>
<body>
    <header><div class="logo"><a href="index.html">🛒 My E-Store</a></div>
    <nav><a href="index.html">Home</a><a href="cart.html">Cart</a><a href="dashboard.php">Dashboard</a><a href="logout.php">Logout</a></nav></header>
    <main><h2>Welcome, <?php echo $_SESSION['fullname']; ?>!</h2><p>You are logged in as <?php echo $_SESSION['username']; ?></p><a href="logout.php">Logout</a></main>
    <footer><p>&copy; 2025 My E-Store</p></footer>
</body>
</html>