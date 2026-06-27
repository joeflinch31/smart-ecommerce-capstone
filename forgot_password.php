<?php
session_start();
require_once 'includes/db.php';

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    $result = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        
        // Generate token
        $token = generateToken();
        
        // Set expiry to 1 HOUR (not 1 second)
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        // Update user with token and expiry
        $update = "UPDATE users SET reset_token = '$token', reset_expiry = '$expiry' WHERE id = {$user['id']}";
        mysqli_query($conn, $update);
        
        // Create reset link
        $reset_link = "http://localhost/week5/reset_password.php?token=$token";
        $success = "✅ Password reset link generated!<br>
                    <a href='$reset_link' style='color:#ff9900;'>🔑 Click here to reset your password</a><br>
                    <small>Link expires in 1 hour.</small>";
    } else {
        $error = "❌ Email not found!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
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
        .links a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <h2>🔑 Forgot Password</h2>
        
        <?php if($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if(empty($success)): ?>
        <form method="POST">
            <div class="form-group">
                <label>Enter Your Email Address</label>
                <input type="email" name="email" placeholder="e.g., youremail@example.com" required>
            </div>
            <button type="submit" class="btn">Send Reset Link</button>
        </form>
        <?php endif; ?>
        
        <div class="links">
            <a href="login.php">← Back to Login</a>
        </div>
    </div>
</body>
</html>