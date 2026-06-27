<?php
session_start();
require_once '../includes/db.php';

// Admin only
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Count statistics
$product_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM products"))['count'];
$user_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM users"))['count'];
$order_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM orders"))['count'];
$pending_orders = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM orders WHERE status='pending'"))['count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f5f5; padding: 20px; }
        .container { max-width: 1200px; margin: auto; }
        .header { background: #333; color: white; padding: 15px 25px; border-radius: 8px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; }
        .header a { color: #ff9900; text-decoration: none; margin-left: 15px; }
        .header a:hover { text-decoration: underline; }
        .logout-btn { background: #dc3545; color: white; padding: 8px 15px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; }
        .logout-btn:hover { background: #c82333; }
        @media (max-width: 768px) { .header { flex-direction: column; text-align: center; } .header div { margin-top: 10px; } }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>👑 Admin Dashboard</h2>
            <div>
                <span>Welcome, <?php echo $_SESSION['fullname']; ?></span>
                <a href="../index.php">🏠 View Store</a>
                <a href="../logout.php" class="logout-btn">🚪 Logout</a>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="number"><?php echo $product_count; ?></div>
                <div class="label">📦 Total Products</div>
            </div>
            <div class="stat-card">
                <div class="number"><?php echo $user_count; ?></div>
                <div class="label">👥 Total Users</div>
            </div>
            <div class="stat-card">
                <div class="number"><?php echo $order_count; ?></div>
                <div class="label">📋 Total Orders</div>
            </div>
            <div class="stat-card">
                <div class="number pending"><?php echo $pending_orders; ?></div>
                <div class="label">⏳ Pending Orders</div>
            </div>
        </div>

        <div class="menu-grid">
            <div class="menu-card">
                <div class="icon">📦</div>
                <h3>Products</h3>
                <p>Add, edit, or delete products</p>
                <a href="products.php">Manage Products</a>
            </div>
            <div class="menu-card">
                <div class="icon">👥</div>
                <h3>Users</h3>
                <p>View and manage user accounts</p>
                <a href="users.php">Manage Users</a>
            </div>
            <div class="menu-card">
                <div class="icon">📋</div>
                <h3>Orders</h3>
                <p>View and update order status</p>
                <a href="orders.php">Manage Orders</a>
            </div>
        </div>
    </div>
</body>
</html>