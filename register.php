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
    $confirm = $_POST['confirm_password'];
    
    if (empty($fullname) || empty($email) || empty($username) || empty($password)) {
        $error = "All fields are required!";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match!";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters!";
    } else {
        $check = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' OR email='$email'");
        if (mysqli_num_rows($check) > 0) {
            $error = "Username or email already exists!";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            
            // generateToken() is now in db.php
            $verification_token = generateToken();
            
            $sql = "INSERT INTO users (fullname, email, username, password, verification_token, verified) 
                    VALUES ('$fullname', '$email', '$username', '$hashed', '$verification_token', 0)";
            
            if (mysqli_query($conn, $sql)) {
                $verify_link = "http://localhost/week5/verify_email.php?token=$verification_token";
                $success = "✅ Registration successful!<br>
                            Please verify your email:<br>
                            <a href='$verify_link' style='color:#ff9900;'>📧 Click here to verify your email</a><br>
                            <small>After verification, <a href='login.php'>login here</a></small>";
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
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f5f5; padding: 20px; }
        .container { max-width: 500px; margin: 50px auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #333; margin-bottom: 20px; }
        .error { background: #f8d7da; color: #721c24; padding: 12px; border-radius: 4px; margin-bottom: 15px; border: 1px solid #f5c6cb; text-align: center; }
        .success { background: #d4edda; color: #155724; padding: 12px; border-radius: 4px; margin-bottom: 15px; border: 1px solid #c3e6cb; text-align: center; }
        .success a { color: #ff9900; }
        .form-group { margin-bottom: 15px; }
        label { font-weight: bold; display: block; margin-bottom: 5px; color: #333; }
        input { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; font-size: 14px; }
        input:focus { outline: none; border-color: #ff9900; }
        .btn { width: 100%; background: #ff9900; color: white; padding: 12px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
        .btn:hover { background: #e68a00; }
        .links { text-align: center; margin-top: 15px; }
        .links a { color: #ff9900; text-decoration: none; }
        .links a:hover { text-decoration: underline; }
        .password-match { font-size: 0.85rem; margin-top: 5px; }
        .match { color: green; }
        .mismatch { color: red; }
        @media (max-width: 600px) { .container { margin: 20px; padding: 20px; } }
    </style>
</head>
<body>
    <div class="container">
        <h2>📝 Create New Account</h2>
        
        <?php if($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if(empty($success)): ?>
        <form method="POST">
            <div class="form-group">
                <label>Full Name *</label>
                <input type="text" name="fullname" placeholder="Enter your full name" required>
            </div>
            <div class="form-group">
                <label>Email Address *</label>
                <input type="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="form-group">
                <label>Username *</label>
                <input type="text" name="username" placeholder="Choose a username" required>
            </div>
            <div class="form-group">
                <label>Password (min 6 characters) *</label>
                <input type="password" id="password" name="password" placeholder="Enter password" required>
            </div>
            <div class="form-group">
                <label>Confirm Password *</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm password" required>
                <div id="password-match-message" class="password-match"></div>
            </div>
            <button type="submit" class="btn">Register</button>
        </form>
        <div class="links">
            Already have an account? <a href="login.php">Login here</a>
        </div>
        <?php endif; ?>
    </div>

    <script>
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('confirm_password');
        const message = document.getElementById('password-match-message');

        function checkPasswordMatch() {
            if (password.value === confirmPassword.value && password.value !== '') {
                message.innerHTML = '✅ Passwords match';
                message.className = 'password-match match';
            } else if (password.value !== '' && confirmPassword.value !== '') {
                message.innerHTML = '❌ Passwords do not match';
                message.className = 'password-match mismatch';
            } else {
                message.innerHTML = '';
            }
        }

        password.addEventListener('input', checkPasswordMatch);
        confirmPassword.addEventListener('input', checkPasswordMatch);
    </script>
</body>
</html>