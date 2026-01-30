<?php 
include('../includes/db.php');
if(!isset($_SESSION['admin'])){ 
    header("Location: login.php");
    exit;
}

// Get filter
$survey_filter = isset($_GET['survey_id']) ? intval($_GET['survey_id']) : 0;

// Get surveys for filter
$surveys = $conn->query("SELECT * FROM surveys ORDER BY id DESC");

// Build query
$where = $survey_filter > 0 ? "WHERE survey_id = $survey_filter" : "";
$users_query = "SELECT DISTINCT u.*, 
    (SELECT COUNT(DISTINCT ua.question_id) FROM user_answers ua WHERE ua.user_id = u.id $where) as answered_questions
    FROM users u
    WHERE EXISTS (SELECT 1 FROM user_answers ua WHERE ua.user_id = u.id $where)
    ORDER BY u.created_at DESC";

$users = $conn->query($users_query);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Survey Responses - Admin</title>
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif;
    background: #f5f5f5;
}
.header {
    background: #4CAF50;
    color: white;
    padding: 15px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
}
.header img { max-width: 100px; }
.nav {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}
.nav a {
    color: white;
    text-decoration: none;
    padding: 8px 15px;
    background: rgba(255,255,255,0.2);
    border-radius: 4px;
    font-size: 14px;
}
.nav a:hover { background: rgba(255,255,255,0.3); }
.container {
    max-width: 1400px;
    margin: 20px auto;
    padding: 0 15px;
}
.card {
    background: white;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
h1 { margin-bottom: 20px; }
h2 { font-size: 20px; margin-bottom: 15px; }
.filter-section {
    display: flex;
    gap: 15px;
    align-items: center;
    margin-bottom: 20px;
    flex-wrap: wrap;
}
.filter-section select {
    padding: 10px;
    border: 2px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}
.stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-bottom: 20px;
}
.stat-box {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    text-align: center;
    border-left: 4px solid #4CAF50;
}
.stat-box h3 {
    font-size: 32px;
    color: #4CAF50;
    margin-bottom: 5px;
}
.stat-box p {
    color: #666;
    font-size: 14px;
}
.user-card {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 15px;
    border-left: 4px solid #2196F3;
}
.user-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
    flex-wrap: wrap;
    gap: 10px;
}
.user-info {
    flex: 1;
}
.user-info h3 {
    margin-bottom: 5px;
    color: #333;
}
.user-meta {
    color: #666;
    font-size: 14px;
}
.btn {
    padding: 8px 16px;
    background: #4CAF50;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    font-size: 14px;
    border: none;
    cursor: pointer;
    display: inline-block;
}
.btn:hover { background: #45a049; }
.btn-small {
    padding: 6px 12px;
    font-size: 12px;
}
.answers-section {
    margin-top: 15px;
    padding-top: 15px;
    border-top: 1px solid #ddd;
}
.answer-item {
    margin: 10px 0;
    padding: 10px;
    background: white;
    border-radius: 4px;
}
.answer-item strong {
    display: block;
    margin-bottom: 5px;
    color: #333;
}
.answer-item span {
    color: #2196F3;
    font-weight: 500;
}
.no-data {
    text-align: center;
    padding: 40px;
    color: #999;
}
@media (max-width: 768px) {
    .header {
        flex-direction: column;
        align-items: flex-start;
    }
    .nav {
        margin-top: 10px;
        width: 100%;
    }
    .user-header {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>
</head>
<body>

<div class="header">
    <div>
        <img src="https://careerg.in/assets/CareergLogo-BvzyUmlH.png" alt="CareerG">
    </div>
    <div class="nav">
        <a href="dashboard.php">Dashboard</a>
        <a href="manage_questions.php">Questions</a>
        <a href="view_responses.php">Responses</a>
        <a href="export_data.php">Export</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">
    <h1>Survey Responses</h1>
    
    <div class="card">
        <div class="filter-section">
            <label><strong>Filter by Survey:</strong></label>
            <select onchange="window.location.href='?survey_id=' + this.value">
                <option value="0">All Surveys</option>
                <?php 
                $surveys_temp = $conn->query("SELECT * FROM surveys ORDER BY id DESC");
                while($s = $surveys_temp->fetch_assoc()): 
                ?>
                <option value="<?php echo $s['id']; ?>" <?php echo $survey_filter == $s['id'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($s['title']); ?>
                </option>
                <?php endwhile; ?>
            </select>
        </div>
        
        <div class="stats">
            <div class="stat-box">
                <h3><?php echo $users->num_rows; ?></h3>
                <p>Total Responses</p>
            </div>
            <div class="stat-box">
                <h3><?php echo $conn->query("SELECT COUNT(*) as c FROM users")->fetch_assoc()['c']; ?></h3>
                <p>Total Users</p>
            </div>
        </div>
    </div>
    
    <?php if($users->num_rows == 0): ?>
    <div class="card">
        <div class="no-data">
            <h3>No responses yet</h3>
            <p>Responses will appear here once users complete the survey.</p>
        </div>
    </div>
    <?php else: ?>
    
    <?php while($user = $users->fetch_assoc()): ?>
    <div class="user-card">
        <div class="user-header">
            <div class="user-info">
                <h3><?php echo htmlspecialchars($user['name']); ?></h3>
                <div class="user-meta">
                    📱 <?php echo htmlspecialchars($user['phone']); ?> | 
                    🎂 <?php echo $user['age']; ?> years | 
                    🎓 <?php echo htmlspecialchars($user['education']); ?> | 
                    📅 <?php echo date('M d, Y H:i', strtotime($user['created_at'])); ?>
                </div>
            </div>
            <button class="btn btn-small" onclick="toggleAnswers(<?php echo $user['id']; ?>)">
                View Answers
            </button>
        </div>
        
        <div id="answers-<?php echo $user['id']; ?>" class="answers-section" style="display:none;">
            <h4>Survey Answers:</h4>
            <?php
            // Get user's answers
            $answers_query = "
                SELECT q.question_text, q.answer_type, GROUP_CONCAT(a.answer_text SEPARATOR ', ') as answers
                FROM user_answers ua
                JOIN questions q ON ua.question_id = q.id
                JOIN answers a ON ua.answer_id = a.id
                WHERE ua.user_id = {$user['id']}
                GROUP BY q.id, q.question_text, q.answer_type
                ORDER BY q.order_no";
            
            $user_answers = $conn->query($answers_query);
            
            if($user_answers && $user_answers->num_rows > 0):
                while($ans = $user_answers->fetch_assoc()):
            ?>
            <div class="answer-item">
                <strong>Q: <?php echo htmlspecialchars($ans['question_text']); ?></strong>
                <span><?php echo htmlspecialchars($ans['answers']); ?></span>
            </div>
            <?php 
                endwhile;
            else:
            ?>
            <p>No answers recorded.</p>
            <?php endif; ?>
        </div>
    </div>
    <?php endwhile; ?>
    
    <?php endif; ?>
</div>

<script>
function toggleAnswers(userId) {
    const answersDiv = document.getElementById('answers-' + userId);
    if(answersDiv.style.display === 'none') {
        answersDiv.style.display = 'block';
    } else {
        answersDiv.style.display = 'none';
    }
}
</script>

</body>
</html>
