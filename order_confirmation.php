<?php
session_start();
require_once 'includes/db.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$order_id = $_GET['id'];
$user_id = $_SESSION['user_id'] ?? 0;

// Get order details
$order_sql = "SELECT * FROM orders WHERE id = $order_id";
$order_result = mysqli_query($conn, $order_sql);
$order = mysqli_fetch_assoc($order_result);

// Get order items
$items_sql = "SELECT oi.*, p.name FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = $order_id";
$items_result = mysqli_query($conn, $items_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - My E-Store</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .confirmation { background: #d4edda; color: #155724; padding: 20px; border-radius: 8px; margin-bottom: 20px; text-align: center; }
        .order-details { background: white; padding: 20px; border-radius: 8px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #333; color: white; }
        .total { text-align: right; margin-top: 20px; font-size: 18px; }
        .continue-btn { background: #ff9900; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; display: inline-block; margin-top: 20px; }
    </style>
</head>
<body>
    <header>
        <div class="logo"><a href="index.php">🛒 My E-Store</a></div>
        <nav>
            <a href="index.php">Home</a>
            <a href="cart.php">Cart</a>
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="dashboard.php">Dashboard</a>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="register.php">Register</a>
            <?php endif; ?>
        </nav>
    </header>

    <main>
        <div class="confirmation">
            <h2>✅ Order Confirmed!</h2>
            <p>Thank you for your purchase. Your order has been received.</p>
            <p><strong>Order Number: #<?php echo $order_id; ?></strong></p>
            <p><strong>Date:</strong> <?php echo $order['order_date']; ?></p>
        </div>

        <div class="order-details">
            <h3>Order Summary</h3>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($item = mysqli_fetch_assoc($items_result)): ?>
                    <tr>
                        <td><?php echo $item['name']; ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td>$<?php echo number_format($item['price'], 2); ?></td>
                        <td>$<?php echo number_format($item['quantity'] * $item['price'], 2); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <div class="total">
                <h3>Total Amount: $<?php echo number_format($order['total_amount'], 2); ?></h3>
            </div>
            <a href="index.php" class="continue-btn">Continue Shopping →</a>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 My E-Store. All rights reserved.</p>
    </footer>
</body>
</html>