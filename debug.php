<?php
include('includes/db.php');

echo "<div style='font-family:Arial;max-width:800px;margin:50px auto;padding:20px'>";
echo "<h1>Survey Debug Information</h1>";

// Check surveys
echo "<h2>Surveys:</h2>";
$surveys = $conn->query("SELECT * FROM surveys");
if($surveys && $surveys->num_rows > 0){
    echo "<table border='1' cellpadding='10' style='border-collapse:collapse;width:100%'>";
    echo "<tr><th>ID</th><th>Title</th><th>Status</th></tr>";
    while($s = $surveys->fetch_assoc()){
        echo "<tr><td>{$s['id']}</td><td>{$s['title']}</td><td>{$s['status']}</td></tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color:red'>No surveys found!</p>";
}

// Check questions
echo "<h2>Questions (Survey ID = 1):</h2>";
$questions = $conn->query("SELECT * FROM questions WHERE survey_id=1 ORDER BY order_no");
if($questions && $questions->num_rows > 0){
    echo "<table border='1' cellpadding='10' style='border-collapse:collapse;width:100%'>";
    echo "<tr><th>ID</th><th>Question</th><th>Type</th><th>Order</th><th>Answers</th></tr>";
    while($q = $questions->fetch_assoc()){
        $answers = $conn->query("SELECT * FROM answers WHERE question_id={$q['id']}");
        $answer_count = $answers ? $answers->num_rows : 0;
        echo "<tr><td>{$q['id']}</td><td>{$q['question_text']}</td><td>{$q['answer_type']}</td><td>{$q['order_no']}</td><td>{$answer_count} answers</td></tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color:red'>No questions found for Survey ID 1!</p>";
    echo "<p><strong>Action needed:</strong> Add questions via <a href='admin/manage_questions.php'>Admin Panel</a></p>";
}

// Check users
echo "<h2>Users:</h2>";
$users = $conn->query("SELECT COUNT(*) as count FROM users");
$user_count = $users->fetch_assoc()['count'];
echo "<p>Total users: <strong>{$user_count}</strong></p>";

// Check user answers
echo "<h2>User Answers:</h2>";
$answers = $conn->query("SELECT COUNT(*) as count FROM user_answers");
$answer_count = $answers->fetch_assoc()['count'];
echo "<p>Total answers: <strong>{$answer_count}</strong></p>";

echo "<hr>";
echo "<h3>Quick Links:</h3>";
echo "<p><a href='admin/manage_questions.php'>Manage Questions</a> | ";
echo "<a href='admin/setup.php'>Re-run Setup</a> | ";
echo "<a href='index.php'>Survey Home</a></p>";

echo "</div>";
?>
