<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../includes/config.php';

// Connect to database
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "<!DOCTYPE html>
<html>
<head>
<meta charset='UTF-8'>
<title>Database Setup</title>
<style>
body{font-family:Arial;max-width:900px;margin:30px auto;padding:20px;background:#f5f5f5}
.card{background:white;padding:30px;border-radius:8px;box-shadow:0 2px 10px rgba(0,0,0,0.1);margin-bottom:20px}
h1{color:#333;margin-bottom:20px}
.success{background:#d4edda;color:#155724;padding:15px;margin:10px 0;border-left:5px solid #28a745;border-radius:4px}
.error{background:#fee;color:#c33;padding:15px;margin:10px 0;border-left:5px solid #c33;border-radius:4px}
.info{background:#e3f2fd;color:#0c5460;padding:15px;margin:10px 0;border-left:5px solid #2196F3;border-radius:4px}
.step{background:#f8f9fa;padding:15px;margin:10px 0;border-radius:4px}
button{padding:12px 24px;background:#4CAF50;color:white;border:none;border-radius:4px;cursor:pointer;font-size:16px;margin:10px 5px 10px 0}
button:hover{background:#45a049}
.warning{background:#fff3cd;color:#856404;padding:15px;margin:10px 0;border-left:5px solid #ffc107;border-radius:4px}
table{width:100%;border-collapse:collapse;margin:15px 0}
th,td{padding:10px;text-align:left;border-bottom:1px solid #ddd}
th{background:#4CAF50;color:white}
</style>
</head>
<body>
<div class='card'>
<h1>📊 Database Setup Wizard</h1>";

// Check if setup is requested
if(isset($_POST['setup'])){
    echo "<div class='info'>🔄 Starting database setup...</div>";
    
    // SQL statements to create tables
    $sql_statements = [
        "DROP TABLE IF EXISTS user_answers",
        "DROP TABLE IF EXISTS users",
        "DROP TABLE IF EXISTS answers",
        "DROP TABLE IF EXISTS questions",
        "DROP TABLE IF EXISTS surveys",
        "DROP TABLE IF EXISTS admin_users",
        
        "CREATE TABLE admin_users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(100) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )",
        
        "CREATE TABLE surveys (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            status TINYINT DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )",
        
        "CREATE TABLE questions (
            id INT AUTO_INCREMENT PRIMARY KEY,
            survey_id INT NOT NULL,
            question_text TEXT NOT NULL,
            answer_type ENUM('radio','checkbox') DEFAULT 'radio',
            order_no INT DEFAULT 0,
            FOREIGN KEY (survey_id) REFERENCES surveys(id) ON DELETE CASCADE
        )",
        
        "CREATE TABLE answers (
            id INT AUTO_INCREMENT PRIMARY KEY,
            question_id INT NOT NULL,
            answer_text VARCHAR(255) NOT NULL,
            FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE
        )",
        
        "CREATE TABLE users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            phone VARCHAR(20),
            name VARCHAR(100),
            age INT,
            education VARCHAR(100),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )",
        
        "CREATE TABLE user_answers (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            survey_id INT NOT NULL,
            question_id INT NOT NULL,
            answer_id INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (survey_id) REFERENCES surveys(id) ON DELETE CASCADE,
            FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE,
            FOREIGN KEY (answer_id) REFERENCES answers(id) ON DELETE CASCADE
        )"
    ];
    
    $errors = 0;
    $success = 0;
    
    foreach($sql_statements as $sql){
        if($conn->query($sql)){
            $success++;
        } else {
            $errors++;
            echo "<div class='error'>Error: " . $conn->error . "</div>";
        }
    }
    
    if($errors == 0){
        echo "<div class='success'>✅ All tables created successfully!</div>";
        
        // Insert default admin user (password: admin123)
        $admin_password = password_hash('admin123', PASSWORD_DEFAULT);
        $insert_admin = "INSERT INTO admin_users (username, password) VALUES ('admin', '$admin_password')";
        
        if($conn->query($insert_admin)){
            echo "<div class='success'>✅ Default admin user created!<br>Username: <strong>admin</strong><br>Password: <strong>admin123</strong></div>";
        }
        
        // Insert a sample survey
        $conn->query("INSERT INTO surveys (title, status) VALUES ('CareerG Survey 2026', 1)");
        $survey_id = $conn->insert_id;
        
        // Add sample questions
        $questions = [
            ["What is your preferred work mode?", "radio", 1],
            ["Which industries interest you? (Select all that apply)", "checkbox", 2],
            ["What is your experience level?", "radio", 3]
        ];
        
        foreach($questions as $idx => $q){
            $conn->query("INSERT INTO questions (survey_id, question_text, answer_type, order_no) VALUES ($survey_id, '{$q[0]}', '{$q[1]}', {$q[2]})");
            $qid = $conn->insert_id;
            
            // Add answers for each question
            if($idx == 0){
                $conn->query("INSERT INTO answers (question_id, answer_text) VALUES ($qid, 'Work from Home'), ($qid, 'Work from Office'), ($qid, 'Hybrid')");
            } elseif($idx == 1){
                $conn->query("INSERT INTO answers (question_id, answer_text) VALUES ($qid, 'Technology'), ($qid, 'Healthcare'), ($qid, 'Finance'), ($qid, 'Education'), ($qid, 'Other')");
            } else {
                $conn->query("INSERT INTO answers (question_id, answer_text) VALUES ($qid, 'Fresher (0-1 years)'), ($qid, 'Junior (1-3 years)'), ($qid, 'Mid-level (3-5 years)'), ($qid, 'Senior (5+ years)')");
            }
        }
        
        echo "<div class='success'>✅ Sample survey created with 3 questions!</div>";
        
        echo "<div class='info'>
        <strong>🎉 Setup Complete!</strong><br><br>
        <strong>Next Steps:</strong><br>
        1. Admin Login: <a href='index.php' style='color:#2196F3;font-weight:bold'>Go to Admin Panel</a><br>
        2. Survey Page: <a href='../index.php' style='color:#2196F3;font-weight:bold'>View Survey</a><br>
        3. Test Database: <a href='test_db.php' style='color:#2196F3;font-weight:bold'>Run Tests</a>
        </div>";
        
        echo "<div class='warning'>
        <strong>⚠️ Security Reminder:</strong><br>
        • Change the admin password after first login<br>
        • Delete or rename this setup.php file<br>
        • Disable error display in production (config.php)
        </div>";
        
    } else {
        echo "<div class='error'>❌ Setup completed with $errors errors</div>";
    }
    
} else {
    // Show current status
    echo "<div class='info'>
    <strong>📋 Current Status:</strong><br>
    Connected to database: <strong>" . DB_NAME . "</strong>
    </div>";
    
    // Check existing tables
    $result = $conn->query("SHOW TABLES");
    $existing_tables = [];
    while($row = $result->fetch_array()){
        $existing_tables[] = $row[0];
    }
    
    if(count($existing_tables) > 0){
        echo "<div class='warning'>
        <strong>⚠️ Warning: Existing tables found!</strong><br>
        Running setup will DROP and recreate all tables. You will lose existing data.<br><br>
        Existing tables: " . implode(', ', $existing_tables) . "
        </div>";
    }
    
    echo "<div class='step'>
    <strong>This setup will:</strong><br>
    ✓ Create all required database tables<br>
    ✓ Set up foreign key relationships<br>
    ✓ Create default admin user (admin/admin123)<br>
    ✓ Add a sample survey with 3 questions<br>
    </div>";
    
    echo "<form method='post'>
    <button type='submit' name='setup' onclick='return confirm(\"Are you sure? This will recreate all tables!\")'>
    🚀 Run Database Setup
    </button>
    </form>";
    
    echo "<div class='info'>
    <strong>💡 Manual Setup:</strong><br>
    You can also import database.sql manually via phpMyAdmin if you prefer.
    </div>";
}

echo "</div></body></html>";

$conn->close();
?>
