<?php
session_start();
require_once 'includes/db.php';

$token = isset($_GET['token']) ? $_GET['token'] : '';
$message = "";

if (empty($token)) {
    $message = "No verification token provided.";
} else {
    $result = mysqli_query($conn, "SELECT * FROM users WHERE verification_token = '$token'");
    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        if ($user['verified'] == 1) {
            $message = "✅ Email already verified. <a href='login.php'>Login here</a>";
        } else {
            mysqli_query($conn, "UPDATE users SET verified = 1, verification_token = NULL WHERE id = {$user['id']}");
            $message = "✅ Email verified successfully! <a href='login.php'>Login here</a>";
        }
    } else {
        $message = "❌ Invalid verification token!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Email Verification</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .container { max-width: 500px; margin: 50px auto; background: white; padding: 30px; border-radius: 8px; text-align: center; }
        .message { font-size: 18px; margin: 20px 0; }
        .btn { background: #ff9900; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; }
    </style>
</head>
<body>
    <div class="container">
        <h2>📧 Email Verification</h2>
        <div class="message"><?php echo $message; ?></div>
        <a href="login.php" class="btn">Go to Login</a>
    </div>
</body>
</html>