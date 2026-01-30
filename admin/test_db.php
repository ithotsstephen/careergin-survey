<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<div style='font-family:Arial;max-width:800px;margin:50px auto;padding:20px'>";
echo "<h2 style='color:#333'>🔍 Database Connection Test</h2>";

// Check if config file exists
if(!file_exists('../includes/config.php')){
    echo "<div style='background:#fee;color:#c33;padding:15px;border-left:4px solid #c33'>";
    echo "<strong>ERROR:</strong> Config file not found!<br>";
    echo "Please create <strong>includes/config.php</strong> first.";
    echo "</div>";
    exit;
}

require_once '../includes/config.php';

echo "<div style='background:#e3f2fd;padding:15px;margin:20px 0;border-left:4px solid #2196F3'>";
echo "<strong>Configuration Loaded:</strong><br>";
echo "Host: " . DB_HOST . "<br>";
echo "User: " . DB_USER . "<br>";
echo "Database: " . DB_NAME . "<br>";
echo "Password: " . str_repeat('*', strlen(DB_PASS)) . "<br>";
echo "</div>";

echo "<p>Attempting to connect to database...</p>";

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    echo "<div style='background:#fee;color:#c33;padding:20px;border-left:4px solid #c33'>";
    echo "<strong>❌ Connection Failed!</strong><br><br>";
    echo "<strong>Error:</strong> " . $conn->connect_error . "<br><br>";
    echo "<strong>Common Solutions:</strong><br>";
    echo "<ol>";
    echo "<li>Verify your database username in cPanel/hPanel</li>";
    echo "<li>Check that the password is correct</li>";
    echo "<li>Ensure the database exists</li>";
    echo "<li>Make sure the user has permissions for this database</li>";
    echo "</ol>";
    echo "<a href='setup_help.html' style='display:inline-block;padding:10px 20px;background:#4CAF50;color:white;text-decoration:none;border-radius:4px;margin-top:10px'>View Setup Guide</a>";
    echo "</div>";
} else {
    echo "<div style='background:#d4edda;color:#155724;padding:20px;border-left:4px solid #28a745'>";
    echo "<strong>✅ Connected Successfully!</strong><br>";
    echo "Connection to database established.<br>";
    echo "</div>";
    
    // Check if admin_users table exists
    $result = $conn->query("SHOW TABLES LIKE 'admin_users'");
    if($result && $result->num_rows > 0) {
        echo "<div style='background:#d4edda;padding:15px;margin:10px 0'>";
        echo "✓ admin_users table exists<br>";
        
        // Check for admin records
        $admin_check = $conn->query("SELECT COUNT(*) as count FROM admin_users");
        if($admin_check){
            $count = $admin_check->fetch_assoc()['count'];
            echo "✓ Number of admin users: " . $count . "<br>";
        }
        echo "</div>";
    } else {
        echo "<div style='background:#fff3cd;padding:15px;margin:10px 0;border-left:4px solid #ffc107'>";
        echo "⚠ Warning: admin_users table does NOT exist<br>";
        echo "You need to run the database.sql file to create tables.";
        echo "</div>";
    }
    
    // Check other tables
    $tables = ['surveys', 'questions', 'answers', 'users', 'user_answers'];
    echo "<div style='background:#f8f9fa;padding:15px;margin:20px 0;border-radius:4px'>";
    echo "<strong>Database Tables Status:</strong><br><br>";
    foreach($tables as $table){
        $result = $conn->query("SHOW TABLES LIKE '$table'");
        if($result && $result->num_rows > 0){
            echo "✅ $table - exists<br>";
        } else {
            echo "❌ $table - missing<br>";
        }
    }
    echo "</div>";
    
    echo "<div style='margin-top:20px'>";
    echo "<a href='index.php' style='display:inline-block;padding:10px 20px;background:#4CAF50;color:white;text-decoration:none;border-radius:4px'>Go to Admin Login</a>";
    echo "</div>";
    
    $conn->close();
}

echo "</div>";
?>
