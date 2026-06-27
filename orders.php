<?php
session_start();
require_once '../includes/db.php';

// Admin only
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Update order status
if (isset($_GET['status']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $status = $_GET['status'];
    mysqli_query($conn, "UPDATE orders SET status='$status' WHERE id=$id");
    header("Location: orders.php?msg=Order status updated successfully");
    exit();
}

// Delete order
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM orders WHERE id=$id");
    header("Location: orders.php?msg=Order deleted successfully");
    exit();
}

$orders = mysqli_query($conn, "SELECT orders.*, users.fullname, users.email FROM orders JOIN users ON orders.user_id = users.id ORDER BY order_date DESC");
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Orders</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f5f5; padding: 20px; }
        .container { max-width: 1200px; margin: auto; background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        h1 { color: #333; border-bottom: 2px solid #ff9900; padding-bottom: 10px; }
        .msg { background: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin-bottom: 15px; border: 1px solid #c3e6cb; }
        .nav-links { margin-bottom: 20px; }
        .nav-links a { margin-right: 15px; color: #ff9900; text-decoration: none; }
        .nav-links a:hover { text-decoration: underline; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 14px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #333; color: white; }
        .btn { padding: 5px 12px; border: none; cursor: pointer; border-radius: 4px; text-decoration: none; display: inline-block; font-size: 12px; }
        .btn-success { background: #28a745; color: white; }
        .btn-danger { background: #dc3545; color: white; }
        .btn-info { background: #17a2b8; color: white; }
        .no-orders { text-align: center; padding: 30px; color: #888; }
    </style>
</head>
<body>
    <div class="container">
        <h1>📦 Manage Orders</h1>
        <div class="nav-links">
            <a href="admin_dashboard.php">← Dashboard</a>
            <a href="products.php">📦 Products</a>
            <a href="users.php">👥 Users</a>
        </div>

        <?php if($msg): ?>
            <div class="msg"><?php echo htmlspecialchars($msg); ?></div>
        <?php endif; ?>

        <?php if(mysqli_num_rows($orders) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Email</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($orders)): ?>
                    <tr>
                        <td><strong>#<?php echo $row['id']; ?></strong></td>
                        <td><?php echo htmlspecialchars($row['fullname']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo date('d M Y H:i', strtotime($row['order_date'])); ?></td>
                        <td><strong>$<?php echo number_format($row['total_amount'], 2); ?></strong></td>
                        <td>
                            <span class="status-<?php echo $row['status']; ?>"><?php echo ucfirst($row['status']); ?></span>
                        </td>
                        <td>
                            <?php if($row['status'] == 'pending'): ?>
                                <a href="?id=<?php echo $row['id']; ?>&status=shipped" class="btn btn-success">🚚 Ship</a>
                            <?php elseif($row['status'] == 'shipped'): ?>
                                <a href="?id=<?php echo $row['id']; ?>&status=delivered" class="btn btn-info">✅ Deliver</a>
                            <?php endif; ?>
                            <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Delete this order?')">🗑 Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-orders">📭 No orders found.</div>
        <?php endif; ?>
    </div>
</body>
</html>