<?php
session_start();
require_once '../includes/db.php';

// Admin only
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM products WHERE id = $id");
    header("Location: products.php?msg=Product deleted successfully");
    exit();
}

// Handle Add/Edit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = $_POST['price'];
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $image_url = mysqli_real_escape_string($conn, $_POST['image_url']);
    $stock = $_POST['stock'];
    
    if (isset($_POST['edit_id']) && $_POST['edit_id'] > 0) {
        $id = $_POST['edit_id'];
        $sql = "UPDATE products SET name='$name', price=$price, description='$description', image_url='$image_url', stock=$stock WHERE id=$id";
        mysqli_query($conn, $sql);
        $msg = "Product updated successfully!";
    } else {
        $sql = "INSERT INTO products (name, price, description, image_url, stock) VALUES ('$name', $price, '$description', '$image_url', $stock)";
        mysqli_query($conn, $sql);
        $msg = "Product added successfully!";
    }
    header("Location: products.php?msg=" . urlencode($msg));
    exit();
}

$products = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC");
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Product Management</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f5f5; padding: 20px; }
        .admin-container { max-width: 1200px; margin: 20px auto; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        h1 { color: #333; border-bottom: 2px solid #ff9900; padding-bottom: 10px; }
        
        /* NAVIGATION LINKS */
        .nav-links { 
            margin: 15px 0 25px 0; 
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        .nav-links a { 
            margin-right: 15px; 
            color: #ff9900; 
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 4px;
        }
        .nav-links a:hover { 
            background: #f0f0f0;
            text-decoration: underline;
        }
        .back-link { color: #666 !important; }
        
        .msg { background: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin-bottom: 15px; border: 1px solid #c3e6cb; }
        .form-card { background: #f9f9f9; padding: 20px; border-radius: 8px; margin-bottom: 30px; }
        .form-group { margin-bottom: 15px; }
        label { font-weight: bold; display: block; margin-bottom: 5px; }
        input, textarea { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; }
        input:focus, textarea:focus { outline: none; border-color: #ff9900; }
        .btn { padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        .btn-primary { background: #ff9900; color: white; }
        .btn-primary:hover { background: #e68a00; }
        .btn-secondary { background: #6c757d; color: white; }
        .btn-secondary:hover { background: #5a6268; }
        .btn-success { background: #28a745; color: white; }
        .btn-success:hover { background: #218838; }
        .btn-danger { background: #dc3545; color: white; }
        .btn-danger:hover { background: #c82333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #333; color: white; }
        img { width: 50px; height: 50px; object-fit: cover; border-radius: 4px; }
        .actions { display: flex; gap: 5px; flex-wrap: wrap; }
        .empty { text-align: center; padding: 30px; color: #888; font-style: italic; }
    </style>
</head>
<body>
    <div class="admin-container">
        <h1>🛒 Admin Panel - Product Management</h1>
        
        <!-- NAVIGATION LINKS (UPDATED) -->
        <div class="nav-links">
            <a href="admin_dashboard.php">📊 Dashboard</a>
            <a href="users.php">👥 Users</a>
            <a href="orders.php">📋 Orders</a>
            <a href="../index.php" class="back-link">← Back to Store</a>
        </div>

        <?php if($msg): ?>
            <div class="msg"><?php echo htmlspecialchars($msg); ?></div>
        <?php endif; ?>

        <!-- Add/Edit Form -->
        <div class="form-card">
            <h2 id="formTitle">➕ Add New Product</h2>
            <form method="POST">
                <input type="hidden" name="edit_id" id="edit_id" value="0">
                <div class="form-group">
                    <label>Product Name</label>
                    <input type="text" name="name" id="name" required>
                </div>
                <div class="form-group">
                    <label>Price ($)</label>
                    <input type="number" step="0.01" name="price" id="price" required>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" id="description" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label>Image Filename (e.g., mouse.jpg)</label>
                    <input type="text" name="image_url" id="image_url" required>
                </div>
                <div class="form-group">
                    <label>Stock Quantity</label>
                    <input type="number" name="stock" id="stock" required>
                </div>
                <button type="submit" class="btn btn-primary">💾 Save Product</button>
                <button type="button" class="btn btn-secondary" onclick="resetForm()">❌ Cancel</button>
            </form>
        </div>

        <!-- Products List -->
        <h2>📋 All Products</h2>
        <?php if(mysqli_num_rows($products) > 0): ?>
            <table>
                <thead>
                    <tr><th>ID</th><th>Image</th><th>Name</th><th>Price</th><th>Stock</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($products)): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><img src="../images/<?php echo $row['image_url']; ?>" alt=""></td>
                        <td><?php echo $row['name']; ?></td>
                        <td>$<?php echo number_format($row['price'], 2); ?></td>
                        <td><?php echo $row['stock']; ?></td>
                        <td>
                            <div class="actions">
                                <button class="btn btn-success" onclick='editProduct(<?php echo json_encode($row); ?>)'>✏️ Edit</button>
                                <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Delete this product?')">🗑 Delete</a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty">No products found. Add your first product above!</div>
        <?php endif; ?>
    </div>

    <script>
        function editProduct(product) {
            document.getElementById('edit_id').value = product.id;
            document.getElementById('name').value = product.name;
            document.getElementById('price').value = product.price;
            document.getElementById('description').value = product.description;
            document.getElementById('image_url').value = product.image_url;
            document.getElementById('stock').value = product.stock;
            document.getElementById('formTitle').innerHTML = '✏️ Edit Product';
            document.querySelector('.btn-primary').textContent = '💾 Update Product';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
        
        function resetForm() {
            document.getElementById('edit_id').value = 0;
            document.getElementById('name').value = '';
            document.getElementById('price').value = '';
            document.getElementById('description').value = '';
            document.getElementById('image_url').value = '';
            document.getElementById('stock').value = '';
            document.getElementById('formTitle').innerHTML = '➕ Add New Product';
            document.querySelector('.btn-primary').textContent = '💾 Save Product';
        }
    </script>
</body>
</html>