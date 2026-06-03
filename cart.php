<?php
session_start();
require_once 'includes/db.php';

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Get cart item count
function getCartCount() {
    $count = 0;
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $quantity) {
            $count += $quantity;
        }
    }
    return $count;
}

// Add to cart with stock check
if (isset($_GET['add'])) {
    $product_id = $_GET['add'];
    
    // Check stock
    $stock_sql = "SELECT stock FROM products WHERE id = $product_id";
    $stock_result = mysqli_query($conn, $stock_sql);
    $stock_row = mysqli_fetch_assoc($stock_result);
    $current_stock = $stock_row['stock'];
    
    $current_qty = isset($_SESSION['cart'][$product_id]) ? $_SESSION['cart'][$product_id] : 0;
    
    if ($current_qty + 1 <= $current_stock) {
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]++;
        } else {
            $_SESSION['cart'][$product_id] = 1;
        }
        $message = "Item added to cart!";
    } else {
        $message = "Not enough stock available! Only $current_stock left.";
    }
    
    header("Location: cart.php?msg=" . urlencode($message));
    exit();
}

// Remove from cart
if (isset($_GET['remove'])) {
    $product_id = $_GET['remove'];
    unset($_SESSION['cart'][$product_id]);
    header("Location: cart.php?msg=Item removed");
    exit();
}

// Update quantity
if (isset($_POST['update'])) {
    foreach ($_POST['quantity'] as $id => $qty) {
        // Check stock before updating
        $stock_sql = "SELECT stock FROM products WHERE id = $id";
        $stock_result = mysqli_query($conn, $stock_sql);
        $stock_row = mysqli_fetch_assoc($stock_result);
        $available = $stock_row['stock'];
        
        if ($qty <= $available && $qty >= 0) {
            if ($qty <= 0) {
                unset($_SESSION['cart'][$id]);
            } else {
                $_SESSION['cart'][$id] = $qty;
            }
        } else {
            $error = "Cannot update. Only $available items in stock.";
        }
    }
    header("Location: cart.php");
    exit();
}

// Process Checkout
if (isset($_POST['checkout'])) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php?redirect=cart");
        exit();
    }
    
    if (!empty($_SESSION['cart'])) {
        $user_id = $_SESSION['user_id'];
        $total = 0;
        
        // Calculate total and verify stock
        $ids = implode(',', array_keys($_SESSION['cart']));
        $sql = "SELECT * FROM products WHERE id IN ($ids)";
        $result = mysqli_query($conn, $sql);
        $insufficient = false;
        
        while ($row = mysqli_fetch_assoc($result)) {
            $qty = $_SESSION['cart'][$row['id']];
            if ($qty > $row['stock']) {
                $insufficient = true;
                $error = "Not enough stock for {$row['name']}. Only {$row['stock']} left.";
            }
            $total += $row['price'] * $qty;
        }
        
        if (!$insufficient) {
            // Insert order
            $order_sql = "INSERT INTO orders (user_id, total_amount, status) VALUES ($user_id, $total, 'pending')";
            mysqli_query($conn, $order_sql);
            $order_id = mysqli_insert_id($conn);
            
            // Insert order items and update stock
            $result = mysqli_query($conn, $sql);
            while ($row = mysqli_fetch_assoc($result)) {
                $product_id = $row['id'];
                $quantity = $_SESSION['cart'][$product_id];
                $price = $row['price'];
                $item_sql = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES ($order_id, $product_id, $quantity, $price)";
                mysqli_query($conn, $item_sql);
                
                // Update stock
                $new_stock = $row['stock'] - $quantity;
                $update_stock = "UPDATE products SET stock = $new_stock WHERE id = $product_id";
                mysqli_query($conn, $update_stock);
            }
            
            // Clear cart
            $_SESSION['cart'] = [];
            header("Location: order_confirmation.php?id=$order_id");
            exit();
        }
    }
}

// Get product details for items in cart
$cart_items = [];
$total = 0;

if (!empty($_SESSION['cart'])) {
    $ids = implode(',', array_keys($_SESSION['cart']));
    $sql = "SELECT * FROM products WHERE id IN ($ids)";
    $result = mysqli_query($conn, $sql);
    
    while ($row = mysqli_fetch_assoc($result)) {
        $row['quantity'] = $_SESSION['cart'][$row['id']];
        $row['subtotal'] = $row['price'] * $row['quantity'];
        $total += $row['subtotal'];
        $cart_items[] = $row;
    }
}

$message = isset($_GET['msg']) ? $_GET['msg'] : '';
$error = isset($error) ? $error : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - My E-Store</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .cart-table { width: 100%; border-collapse: collapse; background: white; }
        .cart-table th, .cart-table td { border: 1px solid #ddd; padding: 10px; text-align: center; }
        .cart-table th { background: #333; color: white; }
        .cart-summary { text-align: right; margin-top: 20px; }
        .checkout-btn { background: #28a745; color: white; padding: 10px 20px; border: none; cursor: pointer; border-radius: 4px; }
        .remove-btn { background: #dc3545; color: white; padding: 5px 10px; text-decoration: none; border-radius: 4px; }
        .quantity-input { width: 60px; padding: 5px; }
        .message { background: #d4edda; color: #155724; padding: 10px; margin-bottom: 20px; border-radius: 4px; }
        .error { background: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 20px; border-radius: 4px; }
        .cart-count { background: #ff9900; color: white; border-radius: 50%; padding: 2px 8px; font-size: 12px; margin-left: 5px; }
        .warning { background: #fff3cd; color: #856404; padding: 10px; border-radius: 4px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <header>
        <div class="logo"><a href="index.php">🛒 My E-Store</a></div>
        <nav>
            <a href="index.php">Home</a>
            <a href="cart.php">Cart <?php if(getCartCount() > 0): ?><span class="cart-count"><?php echo getCartCount(); ?></span><?php endif; ?></a>
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
        <h2>Your Shopping Cart</h2>
        
        <?php if($message): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        
        <?php if($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if(empty($cart_items)): ?>
            <p>Your cart is empty. <a href="index.php">Continue shopping</a></p>
        <?php else: ?>
            <form method="POST">
                <table class="cart-table">
                    <thead>
                        <tr><th>Product</th><th>Price</th><th>Quantity</th><th>Subtotal</th><th>Action</th</thead>
                    <tbody>
                        <?php foreach($cart_items as $item): ?>
                        <tr>
                            <td><?php echo $item['name']; ?></td>
                            <td>$<?php echo number_format($item['price'], 2); ?></td>
                            <td><input type="number" name="quantity[<?php echo $item['id']; ?>]" value="<?php echo $item['quantity']; ?>" min="0" max="<?php echo $item['stock']; ?>" class="quantity-input"></td>
                            <td>$<?php echo number_format($item['subtotal'], 2); ?></td>
                            <td><a href="?remove=<?php echo $item['id']; ?>" class="remove-btn" onclick="return confirm('Remove this item from cart?')">Remove</a></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <button type="submit" name="update">Update Cart</button>
            </form>
            
            <div class="cart-summary">
                <h3>Total: $<?php echo number_format($total, 2); ?></h3>
                <form method="POST" onsubmit="return confirm('Place your order?')">
                    <button type="submit" name="checkout" class="checkout-btn">Proceed to Checkout</button>
                </form>
            </div>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; 2025 My E-Store. All rights reserved.</p>
    </footer>
</body>
</html>