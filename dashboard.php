<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user data
$result = mysqli_query($conn, "SELECT * FROM users WHERE id = $user_id");
$user = mysqli_fetch_assoc($result);

// Check if user is verified
$verified_status = $user['verified'] == 1 ? '✅ Verified' : '⚠️ Not Verified';
$verified_color = $user['verified'] == 1 ? 'green' : 'orange';

// Check if 2FA is enabled
$check_2fa = mysqli_query($conn, "SELECT * FROM user_2fa WHERE user_id = $user_id");
$has_2fa = mysqli_num_rows($check_2fa) > 0;
$_2fa_status = $has_2fa ? '✅ Enabled' : '❌ Disabled';
$_2fa_color = $has_2fa ? 'green' : 'red';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - My E-Store</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f5f5; padding: 20px; }
        .container { max-width: 1100px; margin: 0 auto; }
        .header { background: #333; color: white; padding: 15px 25px; border-radius: 8px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; margin-bottom: 30px; }
        .header a { color: #ff9900; text-decoration: none; margin-left: 15px; }
        .header a:hover { text-decoration: underline; }
        .logout-btn { background: #dc3545; color: white; padding: 8px 15px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; }
        .logout-btn:hover { background: #c82333; }
        .welcome-card { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; border-radius: 8px; margin-bottom: 30px; text-align: center; }
        .welcome-card h2 { color: white; margin-bottom: 10px; }
        .welcome-card .role { background: rgba(255,255,255,0.2); padding: 5px 15px; border-radius: 20px; display: inline-block; margin-top: 5px; }
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: white; padding: 20px; border-radius: 8px; text-align: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .stat-card .number { font-size: 28px; font-weight: bold; color: #333; }
        .stat-card .label { color: #666; font-size: 14px; margin-top: 5px; }
        .quick-links { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-top: 20px; }
        .quick-link { background: white; padding: 20px; border-radius: 8px; text-align: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1); transition: transform 0.2s; text-decoration: none; color: #333; }
        .quick-link:hover { transform: translateY(-3px); }
        .quick-link .icon { font-size: 32px; display: block; margin-bottom: 10px; }
        .quick-link .title { font-weight: bold; }
        .quick-link .desc { font-size: 12px; color: #888; margin-top: 5px; }
        @media (max-width: 768px) { .header { flex-direction: column; text-align: center; } .header div { margin-top: 10px; } }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>📊 Dashboard</h2>
            <div>
                <a href="index.php">🏠 Home</a>
                <a href="cart.php">🛒 Cart</a>
                <a href="profile.php">👤 Profile</a>
                <a href="#" onclick="confirmLogout(event)" class="logout-btn">🚪 Logout</a>
            </div>
        </div>

        <div class="welcome-card">
            <h2>Welcome, <?php echo $_SESSION['fullname']; ?>!</h2>
            <p>You are logged in as <strong><?php echo $_SESSION['username']; ?></strong></p>
            <span class="role"><?php echo ucfirst($_SESSION['role']); ?></span>
        </div>

        <div class="stats">
            <div class="stat-card">
                <div class="number"><?php echo date('Y-m-d'); ?></div>
                <div class="label">Today's Date</div>
            </div>
            <div class="stat-card">
                <div class="number" style="color: <?php echo $verified_color; ?>;"><?php echo $verified_status; ?></div>
                <div class="label">Email Verification</div>
            </div>
            <div class="stat-card">
                <div class="number" style="color: <?php echo $_2fa_color; ?>;"><?php echo $_2fa_status; ?></div>
                <div class="label">2FA Status</div>
            </div>
        </div>

        <h3>⚡ Quick Actions</h3>
        <div class="quick-links">
            <a href="index.php" class="quick-link">
                <span class="icon">🛍️</span>
                <span class="title">Browse Products</span>
                <span class="desc">Shop our catalog</span>
            </a>
            <a href="profile.php" class="quick-link">
                <span class="icon">👤</span>
                <span class="title">My Profile</span>
                <span class="desc">Update your information</span>
            </a>
            <a href="2fa.php" class="quick-link">
                <span class="icon">🔐</span>
                <span class="title">Two-Factor Auth</span>
                <span class="desc">Secure your account</span>
            </a>
            <a href="cart.php" class="quick-link">
                <span class="icon">🛒</span>
                <span class="title">My Cart</span>
                <span class="desc">View your cart</span>
            </a>
            <a href="orders.php" class="quick-link">
                <span class="icon">📦</span>
                <span class="title">My Orders</span>
                <span class="desc">Order history</span>
            </a>
            <a href="#" onclick="confirmLogout(event)" class="quick-link" style="border: 1px solid #dc3545;">
                <span class="icon">🚪</span>
                <span class="title">Logout</span>
                <span class="desc">Sign out of your account</span>
            </a>
        </div>
    </div>
    
<div id="logoutModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; justify-content:center; align-items:center;">
    <div style="background:white; padding:30px; border-radius:8px; text-align:center; max-width:400px; margin:auto; margin-top:20%;">
        <h3>🚪 Confirm Logout</h3>
        <p>Are you sure you want to logout?</p>
        <button onclick="window.location.href='logout.php'" style="background:#dc3545; color:white; padding:10px 20px; border:none; border-radius:4px; cursor:pointer; margin:5px;">Yes, Logout</button>
        <button onclick="closeModal()" style="background:#6c757d; color:white; padding:10px 20px; border:none; border-radius:4px; cursor:pointer; margin:5px;">Cancel</button>
    </div>
</div>

<script>
function confirmLogout(event) {
    event.preventDefault();
    document.getElementById('logoutModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('logoutModal').style.display = 'none';
}
</script>
    
</body>
</html>