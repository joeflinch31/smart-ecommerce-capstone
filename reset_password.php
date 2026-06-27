<?php
session_start();
require_once 'includes/db.php';

$error = "";
$success = "";
$token = isset($_GET['token']) ? $_GET['token'] : '';

if (empty($token)) {
    header("Location: login.php");
    exit();
}

// Verify token - checks if token exists and is NOT expired
$result = mysqli_query($conn, "SELECT * FROM users WHERE reset_token = '$token' AND reset_expiry > NOW()");
if (mysqli_num_rows($result) != 1) {
    $error = "❌ Invalid or expired token! Please request a new reset link.";
} else {
    $user = mysqli_fetch_assoc($result);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($error)) {
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];
    
    if ($password === $confirm) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        mysqli_query($conn, "UPDATE users SET password = '$hashed', reset_token = NULL, reset_expiry = NULL WHERE id = {$user['id']}");
        $success = "✅ Password reset successfully! <a href='login.php'>Login here</a>";
    } else {
        $error = "❌ Passwords do not match!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f5f5; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .container { max-width: 450px; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #333; margin-bottom: 20px; }
        .error { background: #f8d7da; color: #721c24; padding: 12px; border-radius: 4px; margin-bottom: 15px; text-align: center; }
        .success { background: #d4edda; color: #155724; padding: 12px; border-radius: 4px; margin-bottom: 15px; text-align: center; }
        .success a { color: #ff9900; }
        .form-group { margin-bottom: 15px; }
        label { font-weight: bold; display: block; margin-bottom: 5px; }
        input { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px; }
        input:focus { outline: none; border-color: #ff9900; }
        .btn { width: 100%; background: #ff9900; color: white; padding: 12px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        .btn:hover { background: #e68a00; }
        .links { text-align: center; margin-top: 15px; }
        .links a { color: #ff9900; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <h2>🔑 Reset Password</h2>
        
        <?php if($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if(empty($error) && empty($success)): ?>
        <form method="POST">
            <div class="form-group">
                <label>New Password</label>
                <input type="password" name="password" placeholder="Enter new password" required>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" placeholder="Confirm new password" required>
            </div>
            <button type="submit" class="btn">Reset Password</button>
        </form>
        <?php endif; ?>
        
        <div class="links">
            <a href="login.php">← Back to Login</a>
        </div>
    </div>
</body>
</html>