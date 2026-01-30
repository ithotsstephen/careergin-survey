<?php
// Auto-fix script - checks and adds questions if missing
include('includes/db.php');

// Check if questions exist
$check = $conn->query("SELECT COUNT(*) as count FROM questions WHERE survey_id=1");
$count = $check->fetch_assoc()['count'];

if($count == 0) {
    // No questions - add them automatically
    
    // Ensure survey exists
    $survey_check = $conn->query("SELECT COUNT(*) as c FROM surveys WHERE id=1");
    if($survey_check->fetch_assoc()['c'] == 0){
        $conn->query("INSERT INTO surveys (id, title, status) VALUES (1, 'CareerG Survey 2026', 1)");
    }
    
    // Add questions
    $questions = [
        ['What is your preferred work mode?', 'radio', 1, ['Work from Home', 'Work from Office', 'Hybrid']],
        ['Which industries interest you? (Select all that apply)', 'checkbox', 2, ['Technology', 'Healthcare', 'Finance', 'Education', 'Marketing']],
        ['What is your experience level?', 'radio', 3, ['Fresher (0-1 years)', 'Junior (1-3 years)', 'Mid-level (3-5 years)', 'Senior (5+ years)']],
        ['What is your preferred job type?', 'radio', 4, ['Full-time', 'Part-time', 'Contract', 'Freelance']]
    ];
    
    foreach($questions as $q){
        $stmt = $conn->prepare("INSERT INTO questions (survey_id, question_text, answer_type, order_no) VALUES (1, ?, ?, ?)");
        $stmt->bind_param("ssi", $q[0], $q[1], $q[2]);
        $stmt->execute();
        $qid = $conn->insert_id;
        
        foreach($q[3] as $ans){
            $conn->query("INSERT INTO answers (question_id, answer_text) VALUES ($qid, '" . $conn->real_escape_string($ans) . "')");
        }
    }
    
    echo "<!DOCTYPE html>
    <html>
    <head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Survey Ready!</title>
    <style>
    body { font-family: Arial; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
    .box { background: white; padding: 40px; border-radius: 12px; text-align: center; max-width: 500px; }
    .success { color: #4CAF50; font-size: 64px; margin-bottom: 20px; }
    h1 { color: #333; margin-bottom: 15px; }
    p { color: #666; line-height: 1.6; }
    .btn { display: inline-block; margin: 20px 10px 0; padding: 15px 30px; background: #4CAF50; color: white; text-decoration: none; border-radius: 8px; font-weight: 600; }
    .btn:hover { background: #45a049; }
    </style>
    </head>
    <body>
    <div class='box'>
    <div class='success'>✓</div>
    <h1>Survey Setup Complete!</h1>
    <p>4 sample questions have been added automatically.</p>
    <p>Your survey is now ready to use!</p>
    <a href='user.php' class='btn'>Start Survey</a>
    <a href='../admin/manage_questions.php' class='btn' style='background:#2196F3'>Manage Questions</a>
    </div>
    </body>
    </html>";
} else {
    // Questions exist, redirect to user form
    header("Location: user.php");
    exit();
}
?>
