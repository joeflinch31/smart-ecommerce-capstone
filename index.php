<?php
session_start();
require_once 'includes/db.php';

// Get cart count
function getCartCount() {
    $count = 0;
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $quantity) {
            $count += $quantity;
        }
    }
    return $count;
}

$sql = "SELECT * FROM products ORDER BY id";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My E-Store | Home</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .cart-count {
            background: #ff9900;
            color: white;
            border-radius: 50%;
            padding: 2px 8px;
            font-size: 12px;
            margin-left: 5px;
        }
        .toast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #28a745;
            color: white;
            padding: 12px 20px;
            border-radius: 4px;
            display: none;
            z-index: 1000;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo"><a href="index.php">🛒 My E-Store</a></div>
        <nav>
            <a href="index.php">🏠 Home</a>
            <a href="cart.php">🛒 Cart <?php if(getCartCount() > 0): ?><span class="cart-count"><?php echo getCartCount(); ?></span><?php endif; ?></a>
            
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="dashboard.php">📊 Dashboard</a>
                <?php if($_SESSION['role'] == 'admin'): ?>
                    <a href="admin/admin_dashboard.php">👑 Admin</a>
                <?php endif; ?>
                <a href="logout.php">🚪 Logout</a>
            <?php else: ?>
                <a href="login.php">🔐 Login</a>
                <a href="register.php">📝 Register</a>
            <?php endif; ?>
        </nav>
    </header>

    <main>
        <h2>Our Products</h2>
        <div class="product-grid">
            <?php while($product = mysqli_fetch_assoc($result)): ?>
                <div class="product-card">
                    <img src="images/<?php echo $product['image_url']; ?>" alt="<?php echo $product['name']; ?>">
                    <h3><?php echo $product['name']; ?></h3>
                    <div class="price">$<?php echo number_format($product['price'], 2); ?></div>
                    <p><small>Stock: <?php echo $product['stock']; ?></small></p>
                    <button onclick="addToCart(<?php echo $product['id']; ?>, <?php echo $product['stock']; ?>)">Add to Cart</button>
                </div>
            <?php endwhile; ?>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 My E-Store. All rights reserved.</p>
    </footer>

    <div id="toast" class="toast">✓ Item added to cart!</div>

    <script>
        function addToCart(productId, stock) {
            let currentQty = localStorage.getItem('cart_' + productId) || 0;
            
            if (parseInt(currentQty) + 1 <= stock) {
                localStorage.setItem('cart_' + productId, parseInt(currentQty) + 1);
                
                const toast = document.getElementById('toast');
                toast.style.display = 'block';
                setTimeout(() => {
                    toast.style.display = 'none';
                }, 1500);
                
                setTimeout(() => {
                    window.location.href = 'cart.php?add=' + productId;
                }, 500);
            } else {
                alert('Not enough stock! Only ' + stock + ' available.');
            }
        }
    </script>
</body>
</html>