<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('includes/db.php');
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Survey Diagnostics</title>
<style>
body { font-family: Arial; max-width: 1000px; margin: 30px auto; padding: 20px; background: #f5f5f5; }
.card { background: white; padding: 20px; margin: 15px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
h1 { color: #333; }
h2 { color: #4CAF50; border-bottom: 2px solid #4CAF50; padding-bottom: 10px; }
table { width: 100%; border-collapse: collapse; margin: 15px 0; }
th, td { padding: 12px; text-align: left; border: 1px solid #ddd; }
th { background: #4CAF50; color: white; }
.success { background: #d4edda; color: #155724; padding: 15px; border-radius: 4px; margin: 10px 0; }
.error { background: #fee; color: #c33; padding: 15px; border-radius: 4px; margin: 10px 0; }
.warning { background: #fff3cd; color: #856404; padding: 15px; border-radius: 4px; margin: 10px 0; }
.btn { padding: 10px 20px; background: #4CAF50; color: white; text-decoration: none; border-radius: 4px; display: inline-block; margin: 5px; }
.info { background: #e3f2fd; padding: 15px; border-radius: 4px; margin: 10px 0; }
</style>
</head>
<body>

<h1>🔍 Survey System Diagnostics</h1>

<div class="card">
<h2>Session Information</h2>
<?php
echo "<p><strong>User ID in session:</strong> " . (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'Not set') . "</p>";
echo "<p><strong>Question index (q):</strong> " . (isset($_SESSION['q']) ? $_SESSION['q'] : 'Not set') . "</p>";
?>
</div>

<div class="card">
<h2>Database: Surveys</h2>
<?php
$surveys = $conn->query("SELECT * FROM surveys");
if($surveys && $surveys->num_rows > 0){
    echo "<table><tr><th>ID</th><th>Title</th><th>Status</th><th>Created</th></tr>";
    while($s = $surveys->fetch_assoc()){
        echo "<tr><td>{$s['id']}</td><td>{$s['title']}</td><td>" . ($s['status'] ? 'Active' : 'Inactive') . "</td><td>{$s['created_at']}</td></tr>";
    }
    echo "</table>";
} else {
    echo "<div class='error'>❌ No surveys found!</div>";
}
?>
</div>

<div class="card">
<h2>Database: Questions (Survey ID = 1)</h2>
<?php
$questions = $conn->query("SELECT q.*, COUNT(a.id) as answer_count FROM questions q LEFT JOIN answers a ON q.id = a.question_id WHERE q.survey_id=1 GROUP BY q.id ORDER BY q.order_no");
if($questions && $questions->num_rows > 0){
    echo "<div class='success'>✓ Found " . $questions->num_rows . " question(s)</div>";
    echo "<table><tr><th>ID</th><th>Question</th><th>Type</th><th>Order</th><th>Answers</th></tr>";
    while($q = $questions->fetch_assoc()){
        echo "<tr>";
        echo "<td>{$q['id']}</td>";
        echo "<td>" . htmlspecialchars($q['question_text']) . "</td>";
        echo "<td>{$q['answer_type']}</td>";
        echo "<td>{$q['order_no']}</td>";
        echo "<td>{$q['answer_count']} answers</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<div class='error'>❌ No questions found for Survey ID 1!</div>";
    echo "<p><a href='admin/quick_fix.php' class='btn'>Add Sample Questions</a></p>";
}
?>
</div>

<div class="card">
<h2>Database: Answers</h2>
<?php
$all_questions = $conn->query("SELECT * FROM questions WHERE survey_id=1 ORDER BY order_no");
if($all_questions && $all_questions->num_rows > 0){
    while($q = $all_questions->fetch_assoc()){
        echo "<h3>Q{$q['order_no']}: " . htmlspecialchars($q['question_text']) . "</h3>";
        $answers = $conn->query("SELECT * FROM answers WHERE question_id={$q['id']}");
        if($answers && $answers->num_rows > 0){
            echo "<ul>";
            while($a = $answers->fetch_assoc()){
                echo "<li>{$a['id']}. " . htmlspecialchars($a['answer_text']) . "</li>";
            }
            echo "</ul>";
        } else {
            echo "<div class='warning'>⚠️ No answers for this question!</div>";
        }
    }
} else {
    echo "<div class='error'>No questions to show answers for.</div>";
}
?>
</div>

<div class="card">
<h2>Database: Users</h2>
<?php
$users = $conn->query("SELECT * FROM users ORDER BY id DESC LIMIT 10");
if($users && $users->num_rows > 0){
    echo "<p><strong>Total users:</strong> " . $conn->query("SELECT COUNT(*) as c FROM users")->fetch_assoc()['c'] . "</p>";
    echo "<p><strong>Latest 10 users:</strong></p>";
    echo "<table><tr><th>ID</th><th>Name</th><th>Phone</th><th>Age</th><th>Education</th><th>Created</th></tr>";
    while($u = $users->fetch_assoc()){
        echo "<tr><td>{$u['id']}</td><td>{$u['name']}</td><td>{$u['phone']}</td><td>{$u['age']}</td><td>{$u['education']}</td><td>{$u['created_at']}</td></tr>";
    }
    echo "</table>";
} else {
    echo "<div class='warning'>No users yet.</div>";
}
?>
</div>

<div class="card">
<h2>Test Survey Flow</h2>
<div class='info'>
<strong>Follow these steps to test:</strong><br><br>
1. <a href='index.php' class='btn'>Start Fresh Survey</a><br>
2. Enter user details (Phone, Name, Age, Education)<br>
3. Submit and check if questions appear<br><br>
<strong>Or test directly:</strong><br>
<a href='user.php' class='btn'>User Details Form</a>
<a href='question.php' class='btn'>Question Page (requires session)</a>
</div>
</div>

<div class="card">
<h2>Quick Actions</h2>
<a href='admin/quick_fix.php' class='btn'>Add Sample Questions</a>
<a href='admin/manage_questions.php' class='btn' style='background:#2196F3'>Manage Questions</a>
<a href='debug.php' class='btn' style='background:#FF9800'>Simpler Debug</a>
<a href='check.php' class='btn' style='background:#9C27B0'>Auto-Setup Check</a>
</div>

</body>
</html>
