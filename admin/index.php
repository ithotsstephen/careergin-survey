<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include configuration
require_once __DIR__ . '/../includes/config.php';

// Start session first
session_start();

// Database connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("<div style='padding:20px;background:#fee;color:#c33;font-family:Arial'>Database Connection Failed: " . $conn->connect_error . "<br><br>Please update your database credentials in <strong>includes/config.php</strong></div>");
}

$conn->set_charset("utf8mb4");

// Check if tables exist, if not redirect to setup
$table_check = $conn->query("SHOW TABLES LIKE 'admin_users'");
if(!$table_check || $table_check->num_rows == 0){
    header("Location: setup.php");
    exit;
}

$error_message = "";

// Handle login
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $username = $conn->real_escape_string($_POST['username']);
    
    $stmt = $conn->prepare("SELECT * FROM admin_users WHERE username=?");
    if(!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $res = $stmt->get_result();
    
    if($res->num_rows > 0){
        $row = $res->fetch_assoc();
        if(password_verify($_POST['password'], $row['password'])){
            $_SESSION['admin'] = true;
            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['admin_username'] = $row['username'];
            header("Location: dashboard.php");
            exit;
        } else {
            $error_message = "Invalid username or password!";
        }
    } else {
        $error_message = "Invalid username or password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login - CareerG Survey</title>
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
}
.login-container {
    background: white;
    padding: 40px;
    border-radius: 10px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.2);
    width: 100%;
    max-width: 400px;
    text-align: center;
}
.logo {
    margin-bottom: 20px;
}
.logo img {
    max-width: 180px;
    height: auto;
}
h2 {
    color: #333;
    margin-bottom: 30px;
    font-size: 24px;
}
.form-group {
    margin-bottom: 20px;
    text-align: left;
}
.form-group label {
    display: block;
    margin-bottom: 5px;
    color: #555;
    font-weight: 500;
}
.form-group input {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #e0e0e0;
    border-radius: 5px;
    font-size: 14px;
    transition: border-color 0.3s;
}
.form-group input:focus {
    outline: none;
    border-color: #667eea;
}
.btn-login {
    width: 100%;
    padding: 12px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: transform 0.2s;
}
.btn-login:hover {
    transform: translateY(-2px);
}
.error {
    background: #fee;
    color: #c33;
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 20px;
    border-left: 4px solid #c33;
}
.info {
    margin-top: 20px;
    padding: 10px;
    background: #e3f2fd;
    border-radius: 5px;
    font-size: 12px;
    color: #666;
}
</style>
</head>
<body>
<div class="login-container">
    <div class="logo">
        <img src="https://careerg.in/assets/CareergLogo-BvzyUmlH.png" alt="CareerG Logo">
    </div>
    <h2>Admin Login</h2>
    
    <?php if($error_message): ?>
    <div class="error"><?php echo htmlspecialchars($error_message); ?></div>
    <?php endif; ?>
    
    <form method="POST" action="">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required autofocus>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" class="btn-login">Login</button>
    </form>
    
    <div class="info">
        Default: admin / admin123
    </div>
</div>
</body>
</html>
