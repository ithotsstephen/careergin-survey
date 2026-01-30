<?php 
include('includes/db.php');

// Check if user has started the survey
if(!isset($_SESSION['user_id'])){
    header("Location: user.php");
    exit;
}

$user_id = intval($_SESSION['user_id']);

// Get all questions and user's answers
$questions = $conn->query("SELECT * FROM questions WHERE survey_id=1 ORDER BY order_no");

// Handle edit
if(isset($_GET['edit'])){
    $edit_question = intval($_GET['edit']);
    // Find the question index
    $questions_temp = $conn->query("SELECT * FROM questions WHERE survey_id=1 ORDER BY order_no");
    $idx = 0;
    while($qt = $questions_temp->fetch_assoc()){
        if($qt['id'] == $edit_question){
            $_SESSION['q'] = $idx;
            header("Location: question.php");
            exit;
        }
        $idx++;
    }
}

// Handle final submit
if(isset($_POST['final_submit'])){
    // Mark survey as complete
    header("Location: submit.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Review Your Answers</title>
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    padding: 20px;
}
.container {
    background: white;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.2);
    max-width: 800px;
    margin: 0 auto;
    padding: 40px;
}
.logo {
    text-align: center;
    margin-bottom: 30px;
}
.logo img {
    max-width: 150px;
}
h1 {
    text-align: center;
    color: #333;
    margin-bottom: 10px;
    font-size: 28px;
}
.subtitle {
    text-align: center;
    color: #666;
    margin-bottom: 30px;
}
.review-item {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    border-left: 4px solid #4CAF50;
}
.review-item h3 {
    color: #333;
    font-size: 18px;
    margin-bottom: 15px;
}
.review-answer {
    background: white;
    padding: 12px 15px;
    border-radius: 6px;
    color: #2196F3;
    font-weight: 500;
    margin-bottom: 10px;
}
.review-answer:before {
    content: "✓ ";
    color: #4CAF50;
    font-weight: bold;
}
.edit-btn {
    padding: 8px 16px;
    background: #2196F3;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    text-decoration: none;
    display: inline-block;
}
.edit-btn:hover {
    background: #1976D2;
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
    background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
    color: white;
}
.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(76, 175, 80, 0.4);
}
.btn-secondary {
    background: #e0e0e0;
    color: #333;
}
.btn-secondary:hover {
    background: #d0d0d0;
}
.info-box {
    background: #fff3cd;
    border-left: 4px solid #ffc107;
    padding: 15px;
    border-radius: 4px;
    margin-bottom: 30px;
    color: #856404;
}
@media (max-width: 600px) {
    .container {
        padding: 25px;
    }
    h1 {
        font-size: 24px;
    }
    .buttons {
        flex-direction: column;
    }
}
</style>
</head>
<body>

<div class="container">
    <div class="logo">
        <img src="https://careerg.in/assets/CareergLogo-BvzyUmlH.png" alt="CareerG">
    </div>
    
    <h1>📋 Review Your Answers</h1>
    <p class="subtitle">Please review your responses before submitting</p>
    
    <div class="info-box">
        <strong>ℹ️ Note:</strong> You can edit any answer by clicking the "Edit" button below that question.
    </div>
    
    <?php 
    $question_num = 1;
    while($q = $questions->fetch_assoc()): 
        $qid = intval($q['id']);
        
        // Get user's answers for this question
        $user_answers = $conn->query("
            SELECT a.answer_text 
            FROM user_answers ua 
            JOIN answers a ON ua.answer_id = a.id 
            WHERE ua.user_id = $user_id AND ua.question_id = $qid
        ");
    ?>
    <div class="review-item">
        <h3>Q<?php echo $question_num; ?>: <?php echo htmlspecialchars($q['question_text']); ?></h3>
        
        <?php if($user_answers->num_rows > 0): ?>
            <?php while($ans = $user_answers->fetch_assoc()): ?>
            <div class="review-answer">
                <?php echo htmlspecialchars($ans['answer_text']); ?>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="review-answer" style="color:#f44336">
                ⚠️ No answer provided
            </div>
        <?php endif; ?>
        
        <a href="?edit=<?php echo $q['id']; ?>" class="edit-btn">✏️ Edit Answer</a>
    </div>
    <?php 
        $question_num++;
    endwhile; 
    ?>
    
    <form method="post">
        <div class="buttons">
            <a href="question.php" class="btn btn-secondary" style="text-align:center;line-height:inherit;text-decoration:none">← Back to Questions</a>
            <button type="submit" name="final_submit" class="btn btn-primary">
                ✓ Submit Survey
            </button>
        </div>
    </form>
</div>

</body>
</html>
