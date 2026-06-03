<?php
session_start();
require_once '../includes/db.php';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM products WHERE id = $id");
    header("Location: products.php");
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
        // Update product
        $id = $_POST['edit_id'];
        $sql = "UPDATE products SET name='$name', price=$price, description='$description', image_url='$image_url', stock=$stock WHERE id=$id";
        mysqli_query($conn, $sql);
    } else {
        // Insert new product
        $sql = "INSERT INTO products (name, price, description, image_url, stock) VALUES ('$name', $price, '$description', '$image_url', $stock)";
        mysqli_query($conn, $sql);
    }
    header("Location: products.php");
    exit();
}

// Fetch all products
$products = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Product Management</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .admin-container { max-width: 1200px; margin: 20px auto; padding: 20px; }
        .form-card { background: white; padding: 20px; border-radius: 8px; margin-bottom: 30px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        label { font-weight: bold; display: block; margin-bottom: 5px; }
        input, textarea { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
        button { background: #ff9900; color: white; padding: 10px 20px; border: none; cursor: pointer; border-radius: 4px; }
        .product-table { width: 100%; border-collapse: collapse; background: white; }
        .product-table th, .product-table td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        .product-table th { background: #333; color: white; }
        .edit-btn { background: #28a745; color: white; padding: 5px 10px; border: none; cursor: pointer; border-radius: 4px; }
        .delete-btn { background: #dc3545; color: white; padding: 5px 10px; text-decoration: none; border-radius: 4px; display: inline-block; }
        img { width: 50px; height: 50px; object-fit: cover; }
        h1 { color: #333; }
        .back-link { display: inline-block; margin-bottom: 20px; color: #ff9900; }
    </style>
</head>
<body>
    <div class="admin-container">
        <h1>🛒 Admin Panel - Product Management</h1>
        <a href="../index.php" class="back-link">← Back to Store</a>

        <!-- Add/Edit Form -->
        <div class="form-card">
            <h2 id="formTitle">Add New Product</h2>
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
                <button type="submit">Save Product</button>
                <button type="button" onclick="resetForm()" style="background: #666;">Cancel</button>
            </form>
        </div>

        <!-- Products List -->
        <h2>All Products</h2>
        <table class="product-table">
            <thead>
                <tr><th>ID</th><th>Image</th><th>Name</th><th>Price</th><th>Stock</th><th>Actions</th</thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($products)): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><img src="../images/<?php echo $row['image_url']; ?>" alt=""></td>
                    <td><?php echo $row['name']; ?></td>
                    <td>$<?php echo number_format($row['price'], 2); ?></td>
                    <td><?php echo $row['stock']; ?></td>
                    <td>
                        <button class="edit-btn" onclick='editProduct(<?php echo json_encode($row); ?>)'>Edit</button>
                        <a href="?delete=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Delete this product?')">Delete</a>
                    </span>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
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
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
        
        function resetForm() {
            document.getElementById('edit_id').value = 0;
            document.getElementById('name').value = '';
            document.getElementById('price').value = '';
            document.getElementById('description').value = '';
            document.getElementById('image_url').value = '';
            document.getElementById('stock').value = '';
            document.getElementById('formTitle').innerHTML = 'Add New Product';
        }
    </script>
</body>
</html>