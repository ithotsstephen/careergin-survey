
<?php 
include('../includes/db.php');

// Check if user has started the survey
if(!isset($_SESSION['user_id'])){
    header("Location: user.php");
    exit;
}

$qs = $conn->query("SELECT * FROM questions WHERE survey_id=1 ORDER BY order_no");
if(!$qs){
    die("Error fetching questions: " . $conn->error);
}
$questions = $qs->fetch_all(MYSQLI_ASSOC);
$i = isset($_SESSION['q']) ? $_SESSION['q'] : 0;

if(!isset($questions[$i])){ 
    header("Location: submit.php"); 
    exit; 
}
$q = $questions[$i];
?>
<!DOCTYPE html>
<html>
<head>
<title>Survey Question</title>
<style>
body{text-align:center;font-family:Arial;padding:20px}
label{display:block;margin:10px;text-align:left;max-width:400px;margin-left:auto;margin-right:auto}
input{margin-right:10px}
button{padding:10px 30px;background:#4CAF50;color:white;border:none;cursor:pointer;margin-top:20px}
</style>
</head>
<body>
<img src="https://careerg.in/assets/CareergLogo-BvzyUmlH.png" width="200"><br>
<form method="post">
<h3><?php echo htmlspecialchars($q['question_text']); ?></h3>
<?php
$qid = intval($q['id']);
$ans = $conn->query("SELECT * FROM answers WHERE question_id=$qid");
if($ans){
    while($a = $ans->fetch_assoc()){
        $type = $q['answer_type'];
        echo "<label><input type='$type' name='ans[]' value='" . intval($a['id']) . "' required> " . htmlspecialchars($a['answer_text']) . "</label>";
    }
}
?>
<button type="submit">Next</button>
</form>

<?php
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ans'])){
    $user_id = intval($_SESSION['user_id']);
    $question_id = intval($q['id']);
    
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
</body>
</html>
