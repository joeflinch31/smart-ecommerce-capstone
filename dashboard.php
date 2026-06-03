<?php
require_once 'includes/db.php';

// Fetch all products from database
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
</head>
<body>
    <header>
        <div class="logo"><a href="index.php">🛒 My E-Store</a></div>
        <nav>
            <a href="index.php">Home</a>
            <a href="cart.php">Cart</a>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
            <a href="admin/products.php">Admin</a>
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
                    <button onclick="addToCart(<?php echo $product['id']; ?>)">Add to Cart</button>
                </div>
            <?php endwhile; ?>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 My E-Store. All rights reserved.</p>
    </footer>

    <script>
        function addToCart(productId) {
            window.location.href = 'cart.php?add=' + productId;
        }
    </script>
</body>
</html>