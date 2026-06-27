<?php
session_start();
require_once '../includes/db.php';

// Admin only
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Delete user
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM users WHERE id = $id");
    header("Location: users.php?msg=User deleted successfully");
    exit();
}

// Update user role
if (isset($_GET['role']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $role = $_GET['role'];
    mysqli_query($conn, "UPDATE users SET role='$role' WHERE id=$id");
    header("Location: users.php?msg=User role updated successfully");
    exit();
}

$users = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Users</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f5f5; padding: 20px; }
        .container { max-width: 1100px; margin: auto; background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        h1 { color: #333; border-bottom: 2px solid #ff9900; padding-bottom: 10px; }
        .msg { background: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin-bottom: 15px; border: 1px solid #c3e6cb; }
        .nav-links { margin-bottom: 20px; }
        .nav-links a { margin-right: 15px; color: #ff9900; text-decoration: none; }
        .nav-links a:hover { text-decoration: underline; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #333; color: white; }
        .btn { padding: 5px 12px; border: none; cursor: pointer; border-radius: 4px; text-decoration: none; display: inline-block; font-size: 12px; }
        .btn-danger { background: #dc3545; color: white; }
        .btn-success { background: #28a745; color: white; }
        .btn-warning { background: #ffc107; color: #333; }
        .you { color: #666; font-style: italic; }
    </style>
</head>
<body>
    <div class="container">
        <h1>👥 Manage Users</h1>
        <div class="nav-links">
            <a href="admin_dashboard.php">← Dashboard</a>
            <a href="products.php">📦 Products</a>
            <a href="orders.php">📋 Orders</a>
        </div>

        <?php if($msg): ?>
            <div class="msg"><?php echo htmlspecialchars($msg); ?></div>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Date Registered</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($users)): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['fullname']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                    <td>
                        <?php if($row['role'] == 'admin'): ?>
                            <span class="badge-admin">Admin</span>
                        <?php else: ?>
                            <span class="badge-customer">Customer</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>
                    <td>
                        <?php if($row['id'] != $_SESSION['user_id']): ?>
                            <?php if($row['role'] == 'customer'): ?>
                                <a href="?id=<?php echo $row['id']; ?>&role=admin" class="btn btn-warning">⬆ Make Admin</a>
                            <?php else: ?>
                                <a href="?id=<?php echo $row['id']; ?>&role=customer" class="btn btn-success">⬇ Make Customer</a>
                            <?php endif; ?>
                            <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Delete this user?')">🗑 Delete</a>
                        <?php else: ?>
                            <span class="you">(You)</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>