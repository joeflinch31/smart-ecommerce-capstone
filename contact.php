<?php
$success = $error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $msg = htmlspecialchars($_POST['message']);
    
    if (empty($name) || empty($email) || empty($msg)) {
        $error = "All fields are required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format!";
    } else {
        $success = "Thank you $name! Your message has been sent.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Contact Us</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .container { max-width: 500px; margin: 50px auto; background: white; padding: 30px; border-radius: 8px; }
        .form-group { margin-bottom: 15px; }
        label { font-weight: bold; }
        input, textarea { width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px; }
        button { background: #ff9900; padding: 10px 20px; border: none; cursor: pointer; }
        .success { background: #d4edda; color: #155724; padding: 10px; margin-bottom: 15px; border-radius: 4px; }
        .error { background: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 15px; border-radius: 4px; }
    </style>
</head>
<body>
    <header>
        <div class="logo"><a href="index.html">🛒 My E-Store</a></div>
        <nav>
            <a href="index.html">Home</a>
            <a href="cart.html">Cart</a>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
            <a href="contact.php">Contact</a>
        </nav>
    </header>
    <div class="container">
        <h2>Contact Us</h2>
        <?php if($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        <?php if($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label>Name *</label>
                <input type="text" name="name" required>
            </div>
            <div class="form-group">
                <label>Email *</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Message *</label>
                <textarea name="message" rows="5" required></textarea>
            </div>
            <button type="submit">Send Message</button>
        </form>
    </div>
    <footer><p>&copy; 2025 My E-Store</p></footer>
</body>
</html>