<?php
session_start();
require_once 'includes/db.php';
$error = $success = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];
    
    if ($password !== $confirm) $error = "Passwords do not match!";
    elseif (strlen($password) < 6) $error = "Password must be at least 6 characters!";
    else {
        $check = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' OR email='$email'");
        if (mysqli_num_rows($check) > 0) $error = "Username or email already exists!";
        else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $insert = "INSERT INTO users (fullname, email, username, password) VALUES ('$fullname', '$email', '$username', '$hashed')";
            if (mysqli_query($conn, $insert)) { $success = "Registration successful! Redirecting..."; header("refresh:2; url=login.php"); }
            else $error = "Registration failed!";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Register</title><link rel="stylesheet" href="css/style.css"></head>
<body>
    <header><div class="logo"><a href="index.html">🛒 My E-Store</a></div>
    <nav><a href="index.html">Home</a><a href="cart.html">Cart</a><a href="login.php">Login</a><a href="register.php">Register</a><a href="contact.php">Contact</a></nav></header>
    <div class="form-container"><h2>Create Account</h2>
    <?php if($error) echo "<p style='color:red'>$error</p>"; if($success) echo "<p style='color:green'>$success</p>"; ?>
    <form method="POST"><input type="text" name="fullname" placeholder="Full Name" required><input type="email" name="email" placeholder="Email" required><input type="text" name="username" placeholder="Username" required><input type="password" name="password" placeholder="Password" required><input type="password" name="confirm_password" placeholder="Confirm Password" required><button type="submit">Register</button></form>
    <p>Already have an account? <a href="login.php">Login here</a></p></div>
    <footer><p>&copy; 2025 My E-Store</p></footer>
</body>
</html>