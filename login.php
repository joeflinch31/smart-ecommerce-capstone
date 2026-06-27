<?php
session_start();
require_once 'includes/db.php';

$error = "";

// Check if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

// Check Remember Me cookie
if (isset($_COOKIE['remember_token'])) {
    $token = $_COOKIE['remember_token'];
    $sql = "SELECT * FROM user_tokens WHERE token = '$token' AND expires_at > NOW()";
    $result = mysqli_query($conn, $sql);
    if ($row = mysqli_fetch_assoc($result)) {
        $user_id = $row['user_id'];
        $user_result = mysqli_query($conn, "SELECT * FROM users WHERE id = $user_id");
        $user = mysqli_fetch_assoc($user_result);
        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['fullname'] = $user['fullname'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            header("Location: dashboard.php");
            exit();
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    $remember = isset($_POST['remember']) ? true : false;
    
    $sql = "SELECT * FROM users WHERE username = '$username' OR email = '$username'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        
        // Check if email is verified
        if ($user['verified'] == 0) {
            $error = "Please verify your email before logging in.";
        } elseif (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['fullname'] = $user['fullname'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            
            // Remember Me
            if ($remember) {
                $token = generateToken();
                $expiry = date('Y-m-d H:i:s', strtotime('+30 days'));
                mysqli_query($conn, "INSERT INTO user_tokens (user_id, token, expires_at) VALUES ({$user['id']}, '$token', '$expiry')");
                setcookie('remember_token', $token, time() + (86400 * 30), '/');
            }
            
            // Check if 2FA is enabled
            $check_2fa = mysqli_query($conn, "SELECT * FROM user_2fa WHERE user_id = {$user['id']}");
            if (mysqli_num_rows($check_2fa) > 0) {
                $_SESSION['2fa_user_id'] = $user['id'];
                header("Location: 2fa.php");
                exit();
            }
            
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "User not found!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .container { max-width: 400px; margin: 50px auto; background: white; padding: 30px; border-radius: 8px; }
        .error { color: red; text-align: center; margin-bottom: 15px; }
        .form-group { margin-bottom: 15px; }
        label { font-weight: bold; display: block; margin-bottom: 5px; }
        input[type="text"], input[type="password"] { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; }
        .btn { width: 100%; background: #ff9900; color: white; padding: 10px; border: none; border-radius: 4px; cursor: pointer; }
        .btn:hover { background: #e68a00; }
        .links { text-align: center; margin-top: 15px; }
        .links a { color: #ff9900; text-decoration: none; }
        .links a:hover { text-decoration: underline; }
        .remember { display: flex; align-items: center; gap: 8px; margin-bottom: 15px; }
        .remember input { width: auto; }
    </style>
</head>
<body>
    <div class="container">
        <h2>🔐 Login</h2>
        <?php if($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label>Username or Email</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <div class="remember">
                <input type="checkbox" name="remember" id="remember">
                <label for="remember" style="font-weight: normal;">Remember Me</label>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>
        <div class="links">
            <a href="forgot_password.php">Forgot Password?</a> |
            <a href="register.php">Register</a>
        </div>
    </div>
</body>
</html>