<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "week1db";

echo "<h2>Database Connection Test</h2>";

// Connect to MySQL
$conn = mysqli_connect($host, $user, $password);

if (!$conn) {
    die("<p style='color:red'>Connection failed: " . mysqli_connect_error() . "</p>");
}
echo "<p style='color:green'>✓ Connected to MySQL successfully!</p>";

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS week1db";
if (mysqli_query($conn, $sql)) {
    echo "<p style='color:green'>✓ Database 'week1db' created or already exists.</p>";
}

// Select the database
mysqli_select_db($conn, "week1db");

// Create table
$table_sql = "CREATE TABLE IF NOT EXISTS test_table (
    id INT AUTO_INCREMENT PRIMARY KEY,
    message VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (mysqli_query($conn, $table_sql)) {
    echo "<p style='color:green'>✓ Table 'test_table' created successfully.</p>";
    
    // Insert test record
    $insert_sql = "INSERT INTO test_table (message) VALUES ('Week 1 connection works!')";
    if (mysqli_query($conn, $insert_sql)) {
        echo "<p style='color:green'>✓ Test record inserted successfully.</p>";
    }
    
    // Read and display the record
    $result = mysqli_query($conn, "SELECT * FROM test_table ORDER BY id DESC LIMIT 1");
    if ($row = mysqli_fetch_assoc($result)) {
        echo "<p style='color:blue'>📦 Data from database: " . $row['message'] . "</p>";
        echo "<p><small>Created at: " . $row['created_at'] . "</small></p>";
    }
} else {
    echo "<p style='color:red'>Error creating table: " . mysqli_error($conn) . "</p>";
}

// Close connection
mysqli_close($conn);

echo "<hr>";
echo "<a href='index.php'>← Back to Home</a>";
?>