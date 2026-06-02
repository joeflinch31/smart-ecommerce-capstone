<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "week2db";

echo "<h2>Database Connection Test - Week 2</h2>";
echo "<hr>";

// Step 1: Connect to MySQL server
$conn = mysqli_connect($host, $user, $password);

if (!$conn) {
    die("<p style='color:red'>❌ Connection failed: " . mysqli_connect_error() . "</p>");
}
echo "<p style='color:green'>✅ Connected to MySQL successfully!</p>";

// Step 2: Create database if not exists
$sql = "CREATE DATABASE IF NOT EXISTS week2db";
if (mysqli_query($conn, $sql)) {
    echo "<p style='color:green'>✅ Database 'week2db' created or already exists.</p>";
} else {
    echo "<p style='color:red'>❌ Error: " . mysqli_error($conn) . "</p>";
}

// Step 3: Select the database
mysqli_select_db($conn, "week2db");

// Step 4: Create test table
$table_sql = "CREATE TABLE IF NOT EXISTS test_table (
    id INT AUTO_INCREMENT PRIMARY KEY,
    message VARCHAR(200),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (mysqli_query($conn, $table_sql)) {
    echo "<p style='color:green'>✅ Table 'test_table' created successfully.</p>";
} else {
    echo "<p style='color:red'>❌ Table error: " . mysqli_error($conn) . "</p>";
}

// Step 5: Insert test record
$insert_sql = "INSERT INTO test_table (message) VALUES ('Week 2 connection works!')";
if (mysqli_query($conn, $insert_sql)) {
    echo "<p style='color:green'>✅ Test record inserted successfully.</p>";
} else {
    echo "<p>Record may already exist.</p>";
}

// Step 6: Read data back
$result = mysqli_query($conn, "SELECT * FROM test_table ORDER BY id DESC LIMIT 1");
if ($row = mysqli_fetch_assoc($result)) {
    echo "<p style='color:blue'>📦 Data from database: " . $row['message'] . "</p>";
    echo "<p><small>Created at: " . $row['created_at'] . "</small></p>";
}

// Close connection
mysqli_close($conn);

echo "<hr>";
echo "<a href='index.html'>← Back to Homepage</a> | ";
echo "<a href='index.php'>← Back to PHP Hello World</a>";
?>