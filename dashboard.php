<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - My E-Store</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .welcome-box { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 2rem; border-radius: 8px; margin-bottom: 2rem; text-align: center; }
        .welcome-box h2 { color: white; margin-bottom: 0.5rem; }
        .logout-btn { background: #dc3545; color: white; padding: 0.5rem 1rem; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; margin-top: 1rem; }
        .logout-btn:hover { background: #c82333; }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <a href="index.html">🛒 My E-Store</a>
        </div>
        <nav>
            <a href="index.html">Home</a>
            <a href="cart.html">Cart</a>
            <a href="dashboard.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <main>
        <div class="welcome-box">
            <h2>Welcome, <?php echo $_SESSION['fullname']; ?>!</h2>
            <p>You are logged in as <strong><?php echo $_SESSION['username']; ?></strong></p>
            <p>Role: <?php echo ucfirst($_SESSION['role']); ?></p>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
        
        <h2>Your Dashboard</h2>
        <p>This is your account dashboard. You can view your orders and manage your profile here.</p>
        
        <div class="product-grid" style="margin-top: 2rem;">
            <div class="product-card">
                <img src="https://via.placeholder.com/250" alt="My Orders">
                <h3>My Orders</h3>
                <div class="price">View Order History</div>
                <button>View Orders</button>
            </div>
            <div class="product-card">
                <img src="https://via.placeholder.com/250" alt="Profile">
                <h3>My Profile</h3>
                <div class="price">Update Information</div>
                <button>Edit Profile</button>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 My E-Store. All rights reserved.</p>
    </footer>
</body>
</html>