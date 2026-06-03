<?php
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST['username']);
    $message = "Welcome " . $username . "! Form submitted successfully.";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Form Processor</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .container { max-width: 500px; margin: 50px auto; text-align: center; }
        .message { background: #d4edda; padding: 15px; border-radius: 5px; color: #155724; }
        input { width: 100%; padding: 10px; margin: 10px 0; }
        button { background: #ff9900; padding: 10px 20px; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Server-Side Processing</h1>
        <?php if($message): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Enter your name" required>
            <button type="submit">Submit</button>
        </form>
        <a href="index.html">← Back to Home</a>
    </div>
</body>
</html>