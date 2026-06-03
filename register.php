<?php
session_start();
require_once 'includes/db.php';

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Check if passwords match
    if ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters!";
    } else {
        // Check if username or email already exists
        $check_sql = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
        $check_result = mysqli_query($conn, $check_sql);
        
        if (mysqli_num_rows($check_result) > 0) {
            $error = "Username or email already exists!";
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert user into database
            $insert_sql = "INSERT INTO users (fullname, email, username, password) 
                           VALUES ('$fullname', '$email', '$username', '$hashed_password')";
            
            if (mysqli_query($conn, $insert_sql)) {
                $success = "Registration successful! Redirecting to login...";
                header("refresh:2; url=login.php");
            } else {
                $error = "Registration failed: " . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - My E-Store</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .error { color: red; text-align: center; margin-bottom: 1rem; }
        .success { color: green; text-align: center; margin-bottom: 1rem; }
        .form-container { max-width: 500px; margin: 2rem auto; background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; margin-bottom: 0.3rem; font-weight: bold; }
        .form-group input { width: 100%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 4px; }
        button[type="submit"] { width: 100%; background: #333; color: white; padding: 0.7rem; border: none; border-radius: 4px; cursor: pointer; font-size: 1rem; }
        button[type="submit"]:hover { background: #555; }
        .password-match { font-size: 0.8rem; margin-top: 0.3rem; }
        .match { color: green; }
        .mismatch { color: red; }
    </style>
    <script>
        function checkPasswordMatch() {
            var password = document.getElementById('password').value;
            var confirm = document.getElementById('confirm_password').value;
            var message = document.getElementById('password-match-message');
            
            if (password === confirm && password !== '') {
                message.innerHTML = '✓ Passwords match';
                message.className = 'password-match match';
                return true;
            } else if (password !== '' && confirm !== '') {
                message.innerHTML = '✗ Passwords do not match';
                message.className = 'password-match mismatch';
                return false;
            } else {
                message.innerHTML = '';
                return false;
            }
        }
    </script>
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
        <h2>Create New Account</h2>
        
        <?php if($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="fullname" required>
            </div>
            
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required>
            </div>
            
            <div class="form-group">
                <label>Password (min 6 characters)</label>
                <input type="password" id="password" name="password" onkeyup="checkPasswordMatch()" required>
            </div>
            
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" onkeyup="checkPasswordMatch()" required>
                <div id="password-match-message" class="password-match"></div>
            </div>
            
            <button type="submit">Register</button>
        </form>
        
        <p style="text-align:center; margin-top:1rem;">
            Already have an account? <a href="login.php">Login here</a>
        </p>
    </div>

    <footer>
        <p>&copy; 2025 My E-Store. All rights reserved.</p>
    </footer>
</body>
</html>