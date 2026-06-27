<?php
session_start();
require_once 'includes/db.php';

$email = isset($_GET['email']) ? $_GET['email'] : '';
$message = "";

if (!empty($email)) {
    $result = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
    if ($row = mysqli_fetch_assoc($result)) {
        $token = generateToken();
        mysqli_query($conn, "UPDATE users SET verification_token = '$token' WHERE id = {$row['id']}");
        $verify_link = "http://localhost/week5/verify_email.php?token=$token";
        $message = "📧 Verification link sent! <a href='$verify_link'>Click here to verify</a>";
    } else {
        $message = "❌ Email not found!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Resend Verification</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div style="max-width:500px; margin:50px auto; background:white; padding:30px; border-radius:8px; text-align:center;">
        <h2>📧 Resend Verification</h2>
        <p><?php echo $message; ?></p>
        <p><a href="login.php">← Back to Login</a></p>
    </div>
</body>
</html>