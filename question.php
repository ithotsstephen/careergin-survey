
<?php 
include('includes/db.php');

// Check if user has started the survey
if(!isset($_SESSION['user_id'])){
    header("Location: user.php");
    exit;
}

// Handle back button
if(isset($_POST['back']) && isset($_SESSION['q']) && $_SESSION['q'] > 0){
    $_SESSION['q']--;
    header("Location: question.php");
    exit;
}

// Get user's role
$user_result = $conn->query("SELECT role FROM users WHERE id=" . intval($_SESSION['user_id']));
if(!$user_result){
    die("Error fetching user role: " . $conn->error);
}
$user_data = $user_result->fetch_assoc();
$user_role = $user_data['role'] ?? 'Student';

// Fetch questions filtered by user role
$qs = $conn->query("SELECT * FROM questions WHERE survey_id=1 AND (target_role='Both' OR target_role='$user_role') ORDER BY order_no");
if(!$qs){
    die("Error fetching questions: " . $conn->error);
}
$questions = $qs->fetch_all(MYSQLI_ASSOC);
$i = isset($_SESSION['q']) ? $_SESSION['q'] : 0;
$total_questions = count($questions);

// Debug: Check if there are any questions
if($total_questions == 0){
    die("
    <div style='font-family:Arial;max-width:600px;margin:50px auto;padding:20px;background:white;border-radius:8px'>
        <h2 style='color:#f44336'>No Questions Found</h2>
        <p>There are no questions in the survey yet. Please ask the admin to:</p>
        <ol>
            <li>Login to admin panel</li>
            <li>Go to 'Manage Questions'</li>
            <li>Add questions and answers</li>
        </ol>
        <p><a href='admin/manage_questions.php' style='color:#4CAF50'>Go to Admin Panel</a></p>
    </div>
    ");
}

if(!isset($questions[$i])){ 
    header("Location: submit.php"); 
    exit; 
}
$q = $questions[$i];

// Handle next/submit
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ans']) && !isset($_POST['back'])){
    $user_id = intval($_SESSION['user_id']);
    $question_id = intval($q['id']);
    
    // Delete existing answers for this question (in case user is editing)
    $conn->query("DELETE FROM user_answers WHERE user_id=$user_id AND question_id=$question_id");
    
    $stmt = $conn->prepare("INSERT INTO user_answers (user_id,survey_id,question_id,answer_id) VALUES (?,1,?,?)");
    
    foreach($_POST['ans'] as $aid){
        $answer_id = intval($aid);
        $stmt->bind_param("iii", $user_id, $question_id, $answer_id);
        $stmt->execute();
    }
    
    $_SESSION['q']++;
    header("Location: question.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Survey Question</title>
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
}
.container {
    background: white;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.2);
    max-width: 600px;
    width: 100%;
    padding: 40px;
}
.logo {
    text-align: center;
    margin-bottom: 30px;
}
.logo img {
    max-width: 150px;
}
.progress-bar {
    background: #e0e0e0;
    height: 8px;
    border-radius: 4px;
    margin-bottom: 20px;
    overflow: hidden;
}
.progress-fill {
    background: linear-gradient(90deg, #4CAF50, #45a049);
    height: 100%;
    transition: width 0.3s ease;
}
.progress-text {
    text-align: center;
    color: #666;
    font-size: 14px;
    margin-bottom: 30px;
}
.question-box {
    margin-bottom: 30px;
}
.question-box h2 {
    color: #333;
    font-size: 22px;
    margin-bottom: 20px;
    line-height: 1.5;
}
.answer-option {
    display: block;
    padding: 15px;
    margin: 12px 0;
    background: #f8f9fa;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s;
}
.answer-option:hover {
    border-color: #4CAF50;
    background: #f1f8f4;
}
.answer-option input {
    margin-right: 12px;
    transform: scale(1.2);
    cursor: pointer;
}
.answer-option label {
    cursor: pointer;
    font-size: 16px;
    color: #333;
    display: inline;
}
.buttons {
    display: flex;
    gap: 15px;
    margin-top: 30px;
}
.btn {
    flex: 1;
    padding: 15px;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}
.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}
.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
}
.btn-secondary {
    background: #e0e0e0;
    color: #333;
}
.btn-secondary:hover {
    background: #d0d0d0;
}
.btn-secondary:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}
.error-msg {
    color: #f44336;
    font-size: 14px;
    margin-top: 10px;
    display: none;
}
@media (max-width: 600px) {
    .container {
        padding: 25px;
    }
    .question-box h2 {
        font-size: 18px;
    }
    .buttons {
        flex-direction: column-reverse;
    }
}
</style>
</head>
<body>

<div class="container">
    <div class="logo">
        <img src="https://careerg.in/assets/CareergLogo-BvzyUmlH.png" alt="CareerG">
    </div>
    
    <div class="progress-bar">
        <div class="progress-fill" style="width: <?php echo (($i + 1) / $total_questions) * 100; ?>%"></div>
    </div>
    <div class="progress-text">
        Question <?php echo $i + 1; ?> of <?php echo $total_questions; ?>
    </div>
    
    <form method="post" id="questionForm">
        <div class="question-box">
            <h2><?php echo htmlspecialchars($q['question_text']); ?></h2>
            
            <?php
            $qid = intval($q['id']);
            $ans = $conn->query("SELECT * FROM answers WHERE question_id=$qid");
            
            // Get previously selected answers
            $user_id = intval($_SESSION['user_id']);
            $selected_answers = [];
            $prev_answers = $conn->query("SELECT answer_id FROM user_answers WHERE user_id=$user_id AND question_id=$qid");
            while($pa = $prev_answers->fetch_assoc()){
                $selected_answers[] = $pa['answer_id'];
            }
            
            if($ans){
                while($a = $ans->fetch_assoc()){
                    $type = $q['answer_type'];
                    $checked = in_array($a['id'], $selected_answers) ? 'checked' : '';
                    $answer_id = intval($a['id']);
                    echo "<label class='answer-option'>";
                    echo "<input type='$type' name='ans[]' value='$answer_id' $checked>";
                    echo "<label>" . htmlspecialchars($a['answer_text']) . "</label>";
                    echo "</label>";
                }
            }
            ?>
        </div>
        
        <p class="error-msg" id="errorMsg">Please select at least one answer</p>
        
        <div class="buttons">
            <?php if($i > 0): ?>
            <button type="submit" name="back" class="btn btn-secondary">← Back</button>
            <?php else: ?>
            <button type="button" class="btn btn-secondary" disabled>← Back</button>
            <?php endif; ?>
            
            <button type="submit" class="btn btn-primary" onclick="return validateForm()">
                <?php echo ($i == $total_questions - 1) ? 'Submit Survey →' : 'Next →'; ?>
            </button>
        </div>
    </form>
</div>

<script>
function validateForm() {
    const form = document.getElementById('questionForm');
    const checked = form.querySelectorAll('input[name="ans[]"]:checked');
    const errorMsg = document.getElementById('errorMsg');
    
    if(checked.length === 0) {
        errorMsg.style.display = 'block';
        return false;
    }
    errorMsg.style.display = 'none';
    return true;
}
</script>

</body>
</html>
