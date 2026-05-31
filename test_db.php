<?php
$conn = mysqli_connect("localhost", "root", "");
if ($conn) {
    echo "<h1>Connected to MySQL!</h1>";
} else {
    echo "Connection failed!";
}
?>