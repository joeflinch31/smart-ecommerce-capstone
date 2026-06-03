<?php
session_start();
require_once 'includes/db.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    
    // Check if user exists
    $sql = "SELECT * FROM users WHERE username = '$username' OR email = '$username'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['fullname'] = $user['fullname'];
            $_SESSION['role'] = $user['role'];
            
            // Redirect to dashboard
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - My E-Store</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .error { color: red; text-align: center; margin-bottom: 1rem; }
        .form-container { max-width: 400px; margin: 2rem auto; background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; margin-bottom: 0.3rem; font-weight: bold; }
        .form-group input { width: 100%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 4px; }
        button[type="submit"] { width: 100%; background: #333; color: white; padding: 0.7rem; border: none; border-radius: 4px; cursor: pointer; font-size: 1rem; }
        button[type="submit"]:hover { background: #555; }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <a href="index.html">🛒 My E-Store</a>
        </div>
        <nav>
            <a href="index.html">Home</a>
            <a href="cart.html">Cart</a>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        </nav>
    </header>

    <div class="form-container">
        <h2>Login to Your Account</h2>
        
        <?php if($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label>Username or Email</label>
                <input type="text" name="username" required>
            </div>
            
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            
            <button type="submit">Login</button>
        </form>
        
        <p style="text-align:center; margin-top:1rem;">
            Don't have an account? <a href="register.php">Register here</a>
        </p>
    </div>

    <footer>
        <p>&copy; 2025 My E-Store. All rights reserved.</p>
    </footer>
</body>
</html>