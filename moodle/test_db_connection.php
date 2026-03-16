<?php
// Database connection test script for Moodle
// This will help diagnose the database connection issue

echo "<h2>Moodle Database Connection Test</h2>";

$configs = [
    ['host' => '127.0.0.1', 'port' => 3306, 'name' => '127.0.0.1:3306'],
    ['host' => 'localhost', 'port' => 3306, 'name' => 'localhost:3306'],
    ['host' => '127.0.0.1', 'port' => '', 'name' => '127.0.0.1 (default port)'],
    ['host' => 'localhost', 'port' => '', 'name' => 'localhost (default port)'],
];

$dbname = 'moodle';
$dbuser = 'root';
$dbpass = '';

foreach ($configs as $config) {
    echo "<h3>Testing: {$config['name']}</h3>";
    
    try {
        if (!empty($config['port'])) {
            $dsn = "mysql:host={$config['host']};port={$config['port']};charset=utf8mb4";
        } else {
            $dsn = "mysql:host={$config['host']};charset=utf8mb4";
        }
        
        $pdo = new PDO($dsn, $dbuser, $dbpass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        echo "<p style='color: green;'>✓ Connection successful!</p>";
        
        // Try to use the database
        try {
            $pdo->exec("USE `$dbname`");
            echo "<p style='color: green;'>✓ Database '$dbname' exists and is accessible!</p>";
            
            // Check if tables exist
            $stmt = $pdo->query("SHOW TABLES LIKE 'mdl_%'");
            $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
            echo "<p style='color: green;'>✓ Found " . count($tables) . " Moodle tables</p>";
            
        } catch (PDOException $e) {
            echo "<p style='color: orange;'>⚠ Database '$dbname' does not exist or cannot be accessed: " . $e->getMessage() . "</p>";
            echo "<p>You may need to create the database or import moodle.sql</p>";
        }
        
        $pdo = null;
        echo "<hr>";
        
    } catch (PDOException $e) {
        echo "<p style='color: red;'>✗ Connection failed: " . $e->getMessage() . "</p>";
        echo "<hr>";
    }
}

echo "<h3>System Information</h3>";
echo "<p>PHP Version: " . phpversion() . "</p>";
echo "<p>PDO MySQL Available: " . (extension_loaded('pdo_mysql') ? 'Yes' : 'No') . "</p>";
echo "<p>MySQLi Available: " . (extension_loaded('mysqli') ? 'Yes' : 'No') . "</p>";

echo "<h3>Recommendations</h3>";
echo "<ul>";
echo "<li>Make sure XAMPP MySQL/MariaDB service is running</li>";
echo "<li>Check if the database 'moodle' exists in phpMyAdmin</li>";
echo "<li>If database doesn't exist, import the moodle.sql file</li>";
echo "<li>Verify the database user 'root' has proper permissions</li>";
echo "</ul>";
?>
