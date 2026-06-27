<?php
session_start();
require_once 'includes/db.php';

// Check if 2fa user is set
if (!isset($_SESSION['2fa_user_id'])) {
    header("Location: login.php");
    exit();
}

$error = "";
$user_id = $_SESSION['2fa_user_id'];

// Generate a new code if not exists
if (!isset($_SESSION['2fa_code'])) {
    $_SESSION['2fa_code'] = rand(100000, 999999);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $code = $_POST['code'];
    if ($code == $_SESSION['2fa_code']) {
        // Complete login
        $user_result = mysqli_query($conn, "SELECT * FROM users WHERE id = $user_id");
        $user = mysqli_fetch_assoc($user_result);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['fullname'] = $user['fullname'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        unset($_SESSION['2fa_user_id']);
        unset($_SESSION['2fa_code']);
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "❌ Invalid verification code!";
        $_SESSION['2fa_code'] = rand(100000, 999999);
    }
}

// Enable 2FA (optional)
if (isset($_POST['enable_2fa'])) {
    mysqli_query($conn, "INSERT INTO user_2fa (user_id, secret_key) VALUES ($user_id, 'secret_key_placeholder')");
    header("Location: 2fa.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Two-Factor Authentication</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f5f5; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .container { max-width: 450px; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center; }
        h2 { color: #333; margin-bottom: 20px; }
        .code-display { font-size: 32px; letter-spacing: 8px; background: #f0f0f0; padding: 15px; border-radius: 8px; margin: 20px 0; font-weight: bold; color: #333; }
        .error { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 15px; }
        .form-group { margin-bottom: 15px; }
        input[type="text"] { width: 100%; padding: 12px; font-size: 24px; text-align: center; border: 2px solid #ccc; border-radius: 8px; letter-spacing: 4px; }
        input[type="text"]:focus { outline: none; border-color: #ff9900; }
        .btn { background: #ff9900; color: white; padding: 12px 20px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; width: 100%; }
        .btn:hover { background: #e68a00; }
        .btn-secondary { background: #6c757d; width: auto; padding: 10px 20px; font-size: 14px; }
        .btn-secondary:hover { background: #5a6268; }
        .links { margin-top: 15px; }
        .links a { color: #ff9900; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <h2>🔐 Two-Factor Authentication</h2>
        
        <?php 
        // Check if 2FA is enabled
        $check = mysqli_query($conn, "SELECT * FROM user_2fa WHERE user_id = $user_id");
        $has_2fa = mysqli_num_rows($check) > 0;
        ?>

        <?php if(!$has_2fa): ?>
            <p>🔒 Enable 2FA for extra security on your account.</p>
            <form method="POST">
                <button type="submit" name="enable_2fa" class="btn">Enable 2FA</button>
            </form>
            <div class="links">
                <a href="dashboard.php">⏭️ Skip for now</a>
            </div>
        <?php else: ?>
            <p>Enter the 6-digit verification code:</p>
            <div class="code-display"><?php echo $_SESSION['2fa_code']; ?></div>
            
            <?php if($error): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <input type="text" name="code" placeholder="Enter 6-digit code" maxlength="6" required>
                </div>
                <button type="submit" class="btn">✅ Verify</button>
            </form>
            <p style="margin-top: 10px; color: #888; font-size: 14px;">This code changes every time you refresh the page.</p>
        <?php endif; ?>
    </div>
</body>
</html>