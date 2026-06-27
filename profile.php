<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";
$error = "";

// Fetch user data
$result = mysqli_query($conn, "SELECT * FROM users WHERE id = $user_id");
$user = mysqli_fetch_assoc($result);

// Update profile
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    $sql = "UPDATE users SET fullname = '$fullname', email = '$email' WHERE id = $user_id";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['fullname'] = $fullname;
        $message = "Profile updated successfully!";
        $user['fullname'] = $fullname;
        $user['email'] = $email;
    }
    
    // Update password
    if (!empty($_POST['new_password'])) {
        $current = $_POST['current_password'];
        $new = $_POST['new_password'];
        $confirm = $_POST['confirm_password'];
        
        if (password_verify($current, $user['password'])) {
            if ($new === $confirm) {
                $hashed = password_hash($new, PASSWORD_DEFAULT);
                mysqli_query($conn, "UPDATE users SET password = '$hashed' WHERE id = $user_id");
                $message = "Password updated successfully!";
            } else {
                $error = "New passwords do not match!";
            }
        } else {
            $error = "Current password is incorrect!";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Profile</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .container { max-width: 500px; margin: 30px auto; background: white; padding: 30px; border-radius: 8px; }
        .message { background: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin-bottom: 15px; }
        .error { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 15px; }
        .form-group { margin-bottom: 15px; }
        label { font-weight: bold; display: block; margin-bottom: 5px; }
        input { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; }
        .btn { background: #ff9900; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        .btn:hover { background: #e68a00; }
        .section { border-top: 1px solid #ddd; padding-top: 20px; margin-top: 20px; }
        .nav-links { margin-bottom: 20px; }
        .nav-links a { margin-right: 15px; color: #ff9900; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <h2>👤 My Profile</h2>
        <div class="nav-links">
            <a href="dashboard.php">← Dashboard</a>
            <a href="index.php">Home</a>
            <a href="logout.php">Logout</a>
        </div>

        <?php if($message): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>
        <?php if($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <h3>📝 Update Profile</h3>
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="fullname" value="<?php echo $user['fullname']; ?>" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="<?php echo $user['email']; ?>" required>
            </div>
            <button type="submit" class="btn">Update Profile</button>
        </form>

        <div class="section">
            <h3>🔑 Change Password</h3>
            <form method="POST">
                <div class="form-group">
                    <label>Current Password</label>
                    <input type="password" name="current_password" required>
                </div>
                <div class="form-group">
                    <label>New Password</label>
                    <input type="password" name="new_password" required>
                </div>
                <div class="form-group">
                    <label>Confirm New Password</label>
                    <input type="password" name="confirm_password" required>
                </div>
                <button type="submit" class="btn">Change Password</button>
            </form>
        </div>
    </div>
</body>
</html>