<?php
require_once 'includes/db.php';
echo "<h2>Database Connection Test</h2>";
if ($conn) {
    echo "<p style='color:green'>✅ Connected successfully to database: $database</p>";
} else {
    echo "<p style='color:red'>❌ Connection failed</p>";
}
?>
<a href="index.html">← Back to Home</a>