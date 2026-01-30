
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include('../includes/db.php');

// Check if tables exist, if not redirect to setup
$table_check = $conn->query("SHOW TABLES LIKE 'admin_users'");
if(!$table_check || $table_check->num_rows == 0){
    header("Location: setup.php");
    exit;
}

$error_message = "";

// Handle login
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(isset($_POST['username']) && isset($_POST['password'])){
        $username = $conn->real_escape_string($_POST['username']);
        
        $stmt = $conn->prepare("SELECT * FROM admin_users WHERE username=?");
        if($stmt){
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
            $stmt->close();
        } else {
            $error_message = "Database error: " . $conn->error;
        }
    } else {
        $error_message = "Please enter both username and password!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login</title>
<style>
body{text-align:center;font-family:Arial;padding:50px;background:#f4f4f4}
.login-box{background:white;padding:40px;max-width:400px;margin:auto;border-radius:8px;box-shadow:0 2px 10px rgba(0,0,0,0.1)}
input{padding:12px;margin:10px 0;width:100%;box-sizing:border-box;border:1px solid #ddd;border-radius:4px}
button{padding:12px;width:100%;background:#4CAF50;color:white;border:none;border-radius:4px;cursor:pointer;font-size:16px}
button:hover{background:#45a049}
.error{color:red;margin:10px 0;padding:10px;background:#fee;border-radius:4px}
.success{color:green;margin:10px 0;padding:10px;background:#efe;border-radius:4px}
</style>
</head>
<body>
<div class="login-box">
<img src="https://careerg.in/assets/CareergLogo-BvzyUmlH.png" width="150"><br><br>
<h2>Admin Login</h2>

<?php if(!empty($error_message)): ?>
<p class="error"><?php echo htmlspecialchars($error_message); ?></p>
<?php endif; ?>

<form method="post" action="">
<input type="text" name="username" placeholder="Username" required autofocus>
<input type="password" name="password" placeholder="Password" required>
<button type="submit">Login</button>
</form>

<p style="font-size:12px;color:#999;margin-top:20px">
Default: admin / admin123
</p>
</div>
</body>
</html>
