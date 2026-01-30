<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Database Credential Finder</title>
<style>
body{font-family:Arial;max-width:900px;margin:30px auto;padding:20px;background:#f5f5f5}
.card{background:white;padding:30px;border-radius:8px;box-shadow:0 2px 10px rgba(0,0,0,0.1);margin-bottom:20px}
h1{color:#333;margin-bottom:20px}
.error{background:#fee;color:#c33;padding:20px;border-left:5px solid #c33;margin:20px 0;border-radius:4px}
.step{background:#e8f5e9;padding:20px;margin:15px 0;border-radius:4px;border-left:5px solid #4CAF50}
.step h3{margin-top:0;color:#2e7d32}
.info{background:#e3f2fd;padding:15px;margin:15px 0;border-left:4px solid #2196F3}
.warning{background:#fff8e1;padding:15px;margin:15px 0;border-left:4px solid #ffa726}
input{padding:12px;width:100%;box-sizing:border-box;border:2px solid #ddd;border-radius:4px;font-size:14px;margin:10px 0;font-family:monospace}
button{padding:12px 24px;background:#4CAF50;color:white;border:none;border-radius:4px;cursor:pointer;font-size:16px;margin-top:10px}
button:hover{background:#45a049}
.code{background:#263238;color:#aed581;padding:20px;border-radius:5px;font-family:monospace;white-space:pre;overflow-x:auto;margin:15px 0}
table{width:100%;border-collapse:collapse;margin:20px 0}
th,td{padding:12px;text-align:left;border-bottom:1px solid #ddd}
th{background:#4CAF50;color:white}
ol{line-height:2}
</style>
</head>
<body>

<div class="card">
<h1>🔍 Database Credential Finder</h1>

<div class="error">
<strong>❌ Current Error:</strong><br>
Access denied for user 'u433951778_careerg'@'localhost' (using password: YES)
<br><br>
<strong>This means:</strong> The username exists, but the PASSWORD is incorrect!
</div>

<div class="step">
<h3>🔐 Step 1: Find Your Correct Database Password</h3>
<ol>
<li>Login to your <strong>Hostinger Control Panel</strong></li>
<li>Go to <strong>Websites</strong> → Select your website</li>
<li>Click on <strong>Databases</strong> in the left menu</li>
<li>You'll see a list of databases. Look for one that starts with <strong>u433951778_</strong></li>
<li>Click on the database to see details</li>
<li>You should see:
<ul>
<li>Database name (e.g., u433951778_careerg)</li>
<li>Username (e.g., u433951778_careerg)</li>
<li>Option to reset/view password</li>
</ul>
</li>
</ol>
</div>

<div class="warning">
<strong>⚠️ Important:</strong> If you can't see the password, you'll need to RESET it:
<ol>
<li>Click on your database in Hostinger</li>
<li>Click <strong>"Reset Password"</strong> or <strong>"Change Password"</strong></li>
<li>Enter a new password (save it somewhere safe!)</li>
<li>Use this new password in the config file</li>
</ol>
</div>

<div class="step">
<h3>📝 Step 2: Test Your Credentials Here First</h3>
<p>Before updating the config file, test your credentials here:</p>

<form method="post" action="">
<label><strong>Database Host:</strong></label>
<input type="text" name="host" value="localhost" required>

<label><strong>Database Username:</strong></label>
<input type="text" name="user" value="u433951778_careerg" required>

<label><strong>Database Password:</strong></label>
<input type="password" name="pass" placeholder="Enter your database password" required>

<label><strong>Database Name:</strong></label>
<input type="text" name="dbname" value="u433951778_careerg" required>

<button type="submit" name="test">🔍 Test Connection</button>
</form>

<?php
if(isset($_POST['test'])){
    $host = $_POST['host'];
    $user = $_POST['user'];
    $pass = $_POST['pass'];
    $dbname = $_POST['dbname'];
    
    echo "<div style='margin-top:20px;padding:20px;border-radius:4px;'>";
    
    $conn = @new mysqli($host, $user, $pass, $dbname);
    
    if ($conn->connect_error) {
        echo "<div class='error'>";
        echo "<strong>❌ Connection Failed!</strong><br><br>";
        echo "<strong>Error:</strong> " . $conn->connect_error . "<br><br>";
        
        if(strpos($conn->connect_error, 'Access denied') !== false){
            echo "<strong>Possible Issues:</strong><br>";
            echo "• Password is incorrect<br>";
            echo "• Username doesn't exist<br>";
            echo "• User doesn't have permission to access this database<br>";
            echo "<br><strong>Next Steps:</strong><br>";
            echo "1. Double-check your password (try copy-paste to avoid typos)<br>";
            echo "2. Reset your database password in Hostinger<br>";
            echo "3. Verify the username matches exactly (case-sensitive)<br>";
        } elseif(strpos($conn->connect_error, 'Unknown database') !== false){
            echo "<strong>Database name is incorrect!</strong><br>";
            echo "The database '$dbname' doesn't exist.<br>";
            echo "Check your Hostinger panel for the correct database name.";
        }
        echo "</div>";
    } else {
        echo "<div style='background:#d4edda;color:#155724;padding:20px;border-left:5px solid #28a745;border-radius:4px'>";
        echo "<strong>✅ SUCCESS! Connection established!</strong><br><br>";
        echo "Your credentials are correct. Now update your config file:<br><br>";
        echo "<div class='code'>";
        echo "define('DB_HOST', '" . htmlspecialchars($host) . "');\n";
        echo "define('DB_USER', '" . htmlspecialchars($user) . "');\n";
        echo "define('DB_PASS', '" . htmlspecialchars($pass) . "');\n";
        echo "define('DB_NAME', '" . htmlspecialchars($dbname) . "');";
        echo "</div>";
        echo "<strong>Copy the above lines to:</strong> includes/config.php<br>";
        echo "<a href='test_db.php' style='display:inline-block;margin-top:15px;padding:12px 24px;background:#4CAF50;color:white;text-decoration:none;border-radius:4px'>Verify Setup</a>";
        echo "</div>";
        
        $conn->close();
    }
    echo "</div>";
}
?>
</div>

<div class="info">
<strong>💡 Quick Tip:</strong> Your database credentials are usually available in:
<ul>
<li>Hostinger Panel → Databases</li>
<li>phpMyAdmin login page</li>
<li>Your hosting welcome email</li>
</ul>
</div>

<div class="step">
<h3>📋 Common Database Name Patterns</h3>
<p>Your database and username typically follow these patterns:</p>
<table>
<tr>
<th>Type</th>
<th>Example</th>
</tr>
<tr>
<td>Account ID</td>
<td>u433951778</td>
</tr>
<tr>
<td>Database Name</td>
<td>u433951778_careerg<br>u433951778_survey<br>u433951778_db</td>
</tr>
<tr>
<td>Database User</td>
<td>u433951778_careerg<br>u433951778_survey<br>u433951778_user</td>
</tr>
</table>
<p><strong>Note:</strong> The username and database name might be slightly different!</p>
</div>

</div>

</body>
</html>
