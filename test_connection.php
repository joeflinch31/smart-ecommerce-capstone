<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "week3db";

echo "<h2>Database Connection Test - Week 3</h2>";

// Create connection
$conn = mysqli_connect($host, $user, $password, $database);

// Check connection
if (!$conn) {
    die("<p style='color:red'>❌ Connection failed: " . mysqli_connect_error() . "</p>");
}

echo "<p style='color:green'>✅ Connected Successfully to MySQL database!</p>";
echo "<p><strong>Database:</strong> $database</p>";
echo "<p><strong>Server:</strong> $host</p>";
echo "<p><strong>Username:</strong> $user</p>";

// Show all tables in the database
$sql = "SHOW TABLES";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    echo "<p><strong>Tables in $database:</strong></p>";
    echo "<ul>";
    while ($row = mysqli_fetch_array($result)) {
        echo "<li>" . $row[0] . "</li>";
    }
    echo "</ul>";
}

mysqli_close($conn);

echo "<hr>";
echo "<p><a href='validation.html'>← Back to JavaScript Validation</a> | ";
echo "<a href='practice.php'>PHP Practice →</a></p>";
?>