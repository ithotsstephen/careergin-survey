<?php
include('../includes/db.php');

echo "<!DOCTYPE html>
<html>
<head>
<meta charset='UTF-8'>
<meta name='viewport' content='width=device-width, initial-scale=1.0'>
<title>Quick Fix - Add Sample Questions</title>
<style>
body { font-family: Arial; max-width: 800px; margin: 50px auto; padding: 20px; }
.success { background: #d4edda; color: #155724; padding: 15px; border-radius: 4px; margin: 10px 0; }
.error { background: #fee; color: #c33; padding: 15px; border-radius: 4px; margin: 10px 0; }
.info { background: #e3f2fd; padding: 15px; border-radius: 4px; margin: 10px 0; }
.btn { padding: 12px 24px; background: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; margin: 10px 5px; text-decoration: none; display: inline-block; }
</style>
</head>
<body>";

echo "<h1>Quick Question Setup</h1>";

// Check current status
$question_count = $conn->query("SELECT COUNT(*) as count FROM questions WHERE survey_id=1")->fetch_assoc()['count'];
$survey_count = $conn->query("SELECT COUNT(*) as count FROM surveys")->fetch_assoc()['count'];

echo "<div class='info'>";
echo "<strong>Current Status:</strong><br>";
echo "Surveys: $survey_count<br>";
echo "Questions for Survey ID 1: $question_count<br>";
echo "</div>";

if(isset($_POST['add_questions'])){
    // Ensure survey exists
    if($survey_count == 0){
        $conn->query("INSERT INTO surveys (id, title, status) VALUES (1, 'CareerG Survey 2026', 1)");
        echo "<div class='success'>✓ Survey created</div>";
    }
    
    // Delete existing questions to avoid duplicates
    $conn->query("DELETE FROM user_answers WHERE survey_id=1");
    $conn->query("DELETE FROM answers WHERE question_id IN (SELECT id FROM questions WHERE survey_id=1)");
    $conn->query("DELETE FROM questions WHERE survey_id=1");
    
    // Add sample questions
    $questions = [
        [
            'text' => 'What is your preferred work mode?',
            'type' => 'radio',
            'order' => 1,
            'answers' => ['Work from Home', 'Work from Office', 'Hybrid']
        ],
        [
            'text' => 'Which industries interest you? (Select all that apply)',
            'type' => 'checkbox',
            'order' => 2,
            'answers' => ['Technology', 'Healthcare', 'Finance', 'Education', 'Marketing', 'Other']
        ],
        [
            'text' => 'What is your experience level?',
            'type' => 'radio',
            'order' => 3,
            'answers' => ['Fresher (0-1 years)', 'Junior (1-3 years)', 'Mid-level (3-5 years)', 'Senior (5+ years)']
        ],
        [
            'text' => 'What is your preferred job type?',
            'type' => 'radio',
            'order' => 4,
            'answers' => ['Full-time', 'Part-time', 'Contract', 'Freelance', 'Internship']
        ]
    ];
    
    foreach($questions as $q){
        $stmt = $conn->prepare("INSERT INTO questions (survey_id, question_text, answer_type, order_no) VALUES (1, ?, ?, ?)");
        $stmt->bind_param("ssi", $q['text'], $q['type'], $q['order']);
        $stmt->execute();
        $qid = $conn->insert_id;
        
        foreach($q['answers'] as $ans){
            $stmt2 = $conn->prepare("INSERT INTO answers (question_id, answer_text) VALUES (?, ?)");
            $stmt2->bind_param("is", $qid, $ans);
            $stmt2->execute();
        }
    }
    
    echo "<div class='success'>";
    echo "<h3>✓ Success!</h3>";
    echo "<p>" . count($questions) . " questions added successfully!</p>";
    echo "</div>";
    
    echo "<div class='info'>";
    echo "<strong>Next Steps:</strong><br>";
    echo "1. <a href='../user.php'>Test the survey</a><br>";
    echo "2. <a href='../admin/manage_questions.php'>Manage questions in admin panel</a><br>";
    echo "</div>";
    
} else {
    if($question_count == 0){
        echo "<div class='error'>";
        echo "<strong>⚠ No questions found!</strong><br>";
        echo "This is why users can't proceed after entering their details.";
        echo "</div>";
        
        echo "<form method='post'>";
        echo "<button type='submit' name='add_questions' class='btn'>Add Sample Questions Now</button>";
        echo "</form>";
    } else {
        echo "<div class='success'>";
        echo "✓ Questions exist! The survey should work.";
        echo "</div>";
        
        echo "<p><a href='../user.php' class='btn'>Test Survey</a></p>";
        echo "<p><a href='../admin/manage_questions.php' class='btn'>Manage Questions</a></p>";
    }
}

echo "</body></html>";
?>
