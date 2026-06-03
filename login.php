<?php
session_start();
require_once 'includes/db.php';
$error = "";
if (isset($_SESSION['user_id'])) header("Location: dashboard.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    $result = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' OR email='$username'");
    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id']; $_SESSION['username'] = $user['username']; $_SESSION['fullname'] = $user['fullname'];
            header("Location: dashboard.php"); exit();
        } else $error = "Invalid password!";
    } else $error = "User not found!";
}
?>
<!DOCTYPE html>
<html>
<head><title>Login</title><link rel="stylesheet" href="css/style.css"></head>
<body>
    <header><div class="logo"><a href="index.html">🛒 My E-Store</a></div>
    <nav><a href="index.html">Home</a><a href="cart.html">Cart</a><a href="login.php">Login</a><a href="register.php">Register</a><a href="contact.php">Contact</a></nav></header>
    <div class="form-container"><h2>Login</h2><?php if($error) echo "<p style='color:red'>$error</p>"; ?>
    <form method="POST"><input type="text" name="username" placeholder="Username or Email" required><input type="password" name="password" placeholder="Password" required><button type="submit">Login</button></form>
    <p>Don't have an account? <a href="register.php">Register here</a></p></div>
    <footer><p>&copy; 2025 My E-Store</p></footer>
</body>
</html>