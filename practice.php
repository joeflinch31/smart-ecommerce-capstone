<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Syntax Practice - Week 3</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 900px; margin: auto; }
        .card { background: white; padding: 20px; margin-bottom: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        h1 { text-align: center; color: #333; }
        h2 { color: #ff9900; border-bottom: 1px solid #ddd; padding-bottom: 10px; }
        code { background: #f4f4f4; padding: 2px 5px; border-radius: 3px; }
        pre { background: #2d2d2d; color: #f8f8f2; padding: 15px; border-radius: 5px; overflow-x: auto; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🐘 PHP Syntax Practice - Week 3</h1>

        <!-- 1. PHP Variables and Echo -->
        <div class="card">
            <h2>1. PHP Variables and Echo</h2>
            <?php
                $name = "Joseph Kinuthia";
                $course = "BIT3208 - Advanced Web Design";
                $year = 2026;
                $grade = 67;
                
                echo "<p><strong>Student Name:</strong> $name</p>";
                echo "<p><strong>Course:</strong> $course</p>";
                echo "<p><strong>Year:</strong> $year</p>";
                echo "<p><strong>Grade:</strong> $grade%</p>";
            ?>
        </div>

        <!-- 2. Conditional Statements -->
        <div class="card">
            <h2>2. Conditional Statements (If-Else)</h2>
            <?php
                $marks = 67;
                echo "<p>Marks: $marks</p>";
                
                if ($marks >= 70) {
                    echo "<p style='color: green;'>✅ Grade: A (Excellent!)</p>";
                } elseif ($marks >= 60) {
                    echo "<p style='color: blue;'>✅ Grade: B (Good!)</p>";
                } elseif ($marks >= 50) {
                    echo "<p style='color: orange;'>✅ Grade: C (Average)</p>";
                } else {
                    echo "<p style='color: red;'>❌ Grade: F (Fail)</p>";
                }
            ?>
        </div>

        <!-- 3. Loops -->
        <div class="card">
            <h2>3. Loops</h2>
            <h3>For Loop (1 to 5):</h3>
            <?php
                for ($i = 1; $i <= 5; $i++) {
                    echo "$i ";
                }
            ?>
            
            <h3>While Loop (Even numbers up to 10):</h3>
            <?php
                $i = 2;
                while ($i <= 10) {
                    echo "$i ";
                    $i += 2;
                }
            ?>
            
            <h3>Foreach Loop (Products Array):</h3>
            <?php
                $products = array("Wireless Mouse", "Mechanical Keyboard", "USB-C Hub", "Laptop Stand");
                echo "<ul>";
                foreach ($products as $product) {
                    echo "<li>$product</li>";
                }
                echo "</ul>";
            ?>
        </div>

        <!-- 4. Arrays -->
        <div class="card">
            <h2>4. Arrays</h2>
            <?php
                // Indexed array
                $colors = ["Red", "Green", "Blue", "Yellow"];
                echo "<p><strong>Indexed Array:</strong> " . implode(", ", $colors) . "</p>";
                
                // Associative array
                $user = [
                    "name" => "Joseph Kinuthia",
                    "email" => "joseph303@gmail.com",
                    "role" => "Developer"
                ];
                echo "<p><strong>Associative Array:</strong></p>";
                echo "<ul>";
                foreach ($user as $key => $value) {
                    echo "<li><strong>$key:</strong> $value</li>";
                }
                echo "</ul>";
            ?>
        </div>

        <!-- 5. Functions -->
        <div class="card">
            <h2>5. Functions</h2>
            <?php
                function calculateTotal($price, $quantity) {
                    $total = $price * $quantity;
                    $tax = $total * 0.17;
                    $grandTotal = $total + $tax;
                    return $grandTotal;
                }
                
                $itemPrice = 25.99;
                $itemQty = 3;
                $finalTotal = calculateTotal($itemPrice, $itemQty);
                
                echo "<p>Item Price: $$itemPrice</p>";
                echo "<p>Quantity: $itemQty</p>";
                echo "<p>Total with Tax (17%): $" . number_format($finalTotal, 2) . "</p>";
            ?>
        </div>

        <!-- 6. Simple Form Processor -->
        <div class="card">
            <h2>6. Simple Form Processor</h2>
            <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $submittedName = htmlspecialchars($_POST['name']);
                    $submittedEmail = htmlspecialchars($_POST['email']);
                    echo "<div style='background: #d4edda; padding: 10px; border-radius: 4px; margin-bottom: 15px;'>";
                    echo "<p>✅ Form submitted successfully!</p>";
                    echo "<p><strong>Name:</strong> $submittedName</p>";
                    echo "<p><strong>Email:</strong> $submittedEmail</p>";
                    echo "</div>";
                }
            ?>
            <form method="POST" action="">
                <div style="margin-bottom: 10px;">
                    <label>Name:</label><br>
                    <input type="text" name="name" required style="width: 100%; padding: 8px;">
                </div>
                <div style="margin-bottom: 10px;">
                    <label>Email:</label><br>
                    <input type="email" name="email" required style="width: 100%; padding: 8px;">
                </div>
                <button type="submit" style="background: #ff9900; color: white; border: none; padding: 10px 20px; cursor: pointer;">Submit</button>
            </form>
        </div>
    </div>
</body>
</html>