
<?php 
include('../includes/db.php');
if(!isset($_SESSION['admin'])){ 
    header("Location: login.php");
    exit;
}

// Get survey statistics
$total_users = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
$total_responses = $conn->query("SELECT COUNT(DISTINCT user_id) FROM user_answers")->fetch_assoc()['COUNT(DISTINCT user_id)'];

// Get parent and student counts
$parent_count = $conn->query("SELECT COUNT(*) as count FROM users WHERE role='Parent'")->fetch_assoc()['count'];
$student_count = $conn->query("SELECT COUNT(*) as count FROM users WHERE role='Student'")->fetch_assoc()['count'];

// Get questions and their answers with response counts
$questions_data = [];
$questions = $conn->query("SELECT * FROM questions WHERE survey_id=1 ORDER BY order_no");
while($q = $questions->fetch_assoc()) {
    $question_id = $q['id'];
    $question_text = $q['question_text'];
    $target_role = $q['target_role'];
    
    // Get answers for this question with parent/student breakdown
    $answers = $conn->query("SELECT a.id, a.answer_text FROM answers a WHERE a.question_id=$question_id ORDER BY a.order_no, a.id");
    
    $answer_data = [];
    while($ans = $answers->fetch_assoc()) {
        $answer_id = $ans['id'];
        
        // Count parent responses
        $parent_result = $conn->query("
            SELECT COUNT(*) as count 
            FROM user_answers ua 
            JOIN users u ON ua.user_id = u.id 
            WHERE ua.answer_id=$answer_id AND u.role='Parent'
        ");
        $parent_responses = $parent_result->fetch_assoc()['count'];
        
        // Count student responses
        $student_result = $conn->query("
            SELECT COUNT(*) as count 
            FROM user_answers ua 
            JOIN users u ON ua.user_id = u.id 
            WHERE ua.answer_id=$answer_id AND u.role='Student'
        ");
        $student_responses = $student_result->fetch_assoc()['count'];
        
        $answer_data[] = [
            'text' => $ans['answer_text'],
            'parent_count' => $parent_responses,
            'student_count' => $student_responses
        ];
    }
    
    $questions_data[] = [
        'id' => $question_id,
        'text' => $question_text,
        'target_role' => $target_role,
        'answers' => $answer_data
    ];
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard</title>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif;margin:0;padding:0;background:#f5f5f5}
.header{background:#4CAF50;color:white;padding:20px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap}
.container{padding:30px;max-width:1400px;margin:0 auto}
.stats{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:20px;margin:20px 0}
.stat-box{background:white;padding:20px;border-radius:8px;text-align:center;box-shadow:0 2px 4px rgba(0,0,0,0.1)}
.stat-box h3{margin:0;color:#666;font-size:14px;text-transform:uppercase}
.stat-box p{margin:10px 0 0;font-size:32px;font-weight:bold;color:#4CAF50}
.stat-box.parent p{color:#FF9800}
.stat-box.student p{color:#2196F3}
.menu{margin:30px 0}
.menu a{display:inline-block;padding:12px 24px;background:#4CAF50;color:white;text-decoration:none;border-radius:4px;margin:5px}
.menu a:hover{background:#45a049}
.logout{background:#f44336!important}
.logout:hover{background:#da190b!important}
.charts-section{margin-top:40px}
.chart-container{background:white;padding:20px;border-radius:8px;margin-bottom:30px;box-shadow:0 2px 4px rgba(0,0,0,0.1)}
.chart-container h3{margin:0 0 20px;color:#333;font-size:18px;border-bottom:2px solid #4CAF50;padding-bottom:10px}
.chart-canvas{max-height:400px;position:relative}
.role-badge{display:inline-block;padding:4px 12px;border-radius:4px;font-size:12px;margin-left:10px;color:white}
.role-badge.parent{background:#FF9800}
.role-badge.student{background:#2196F3}
.role-badge.both{background:#4CAF50}
@media (max-width:768px){
    .header{flex-direction:column;align-items:flex-start}
    .menu{width:100%}
    .chart-canvas{max-height:300px}
{
        $answer_id = $ans['id'];
        
        // Count parent responses
        $parent_result = $conn->query("
            SELECT COUNT(*) as count 
            FROM user_answers ua 
            JOIN users u ON ua.user_id = u.id 
            WHERE ua.answer_id=$answer_id AND u.role='Parent'
        ");
        $parent_responses = $parent_result->fetch_assoc()['count'];
        
        // Count student responses
        $student_result = $conn->query("
            SELECT COUNT(*) as count 
            FROM user_answers ua 
            JOIN users u ON ua.user_id = u.id 
            WHERE ua.answer_id=$answer_id AND u.role='Student'
        ");
        $student_responses = $student_result->fetch_assoc()['count'];
        
        $answer_data[] = [
            'text' => $ans['answer_text'],
            'parent_count' => $parent_responses,
            'student_count' => $student_responses
        ];
    }
    
    $questions_data[] = [
        'id' => $question_id,
        'text' => $question_text,
        'target_role' => $target_role,
        'answers' => $answer_data
    ];
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard</title>
<style>
body{font-family:Arial;margin:0;padding:0}
.header{background:#4CAF50;color:white;padding:20px;display:flex;justify-content:space-between;align-items:center}
.container{padding:30px}
.stats{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:20px;margin:20px 0}
.stat-box{background:#f4f4f4;padding:20px;border-radius:8px;text-align:center}
.stat-box h3{margin:0;color:#666;font-size:14px}
.stat-box p{margin:10px 0 0;font-size:32px;font-weight:bold;color:#4CAF50}
.menu{margin:30px 0}
.menu a{display:inline-block;padding:12px 24px;background:#4CAF50;color:white;text-decoration:none;border-radius:4px;margin:5px}
.menu a:hover{background:#45a049}
.logout{background:#f44336!important}
.logout:hover{background:#da190b!important}
</style>
</head>
<body>
<div class="header">
<div class="stat-box parent">
<h3>Parents</h3>
<p><?php echo $parent_count; ?></p>
</div>
<div class="stat-box student">
<h3>Students</h3>
<p><?php echo $student_count; ?></p>
</div>
</div>

<div class="menu">
<h3>Quick Actions</h3>
<a href="view_users.php">View Users</a>
<a href="view_responses.php">View Responses</a>
<a href="manage_questions.php">Manage Questions</a>
<a href="export_data.php">Export Data</a>
<a href="logout.php" class="logout">Logout</a>
</div>

<div class="charts-section">
<h2>Response Analysis</h2>
<?php if(empty($questions_data)): ?>
<div class="chart-container">
<p>No survey data available yet. Add questions and collect responses to see analytics.</p>
</div>
<?php else: ?>
<?php foreach($questions_data as $qdata): ?>
<div class="chart-container">
<h3>
<?php echo htmlspecialchars($qdata['text']); ?>
<span class="role-badge <?php echo strtolower($qdata['target_role']); ?>">
<?php echo $qdata['target_role']; ?>
</span>
</h3>
<div class="chart-canvas">
<canvas id="chart-<?php echo $qdata['id']; ?>"></canvas>
</div>
</div>
<?php endforeach; ?>
<?php endif; ?>
</div>
</div>

<script>
const chartsData = <?php echo json_encode($questions_data); ?>;

chartsData.forEach(question => {
    const ctx = document.getElementById('chart-' + question.id);
    if(!ctx) return;
    
    const labels = question.answers.map(a => a.text);
    const parentData = question.answers.map(a => a.parent_count);
    const studentData = question.answers.map(a => a.student_count);
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Parents',
                    data: parentData,
                    backgroundColor: 'rgba(255, 152, 0, 0.7)',
                    borderColor: 'rgba(255, 152, 0, 1)',
                    borderWidth: 2
                },
                {
                    label: 'Students',
                    data: studentData,
                    backgroundColor: 'rgba(33, 150, 243, 0.7)',
                    borderColor: 'rgba(33, 150, 243, 1)',
                    borderWidth: 2
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                },
                title: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    },
                    title: {
                        display: true,
                        text: 'Number of Responses'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Answers'
                    }
                }
            }
        }
    });
});
</script>
lass="stats">
<div class="stat-box">
<h3>Total Users</h3>
<p><?php echo $total_users; ?></p>
</div>
<div class="stat-box">
<h3>Survey Responses</h3>
<p><?php echo $total_responses; ?></p>
</div>
</div>

<div class="menu">
<h3>Quick Actions</h3>
<a href="view_responses.php">View Responses</a>
<a href="manage_questions.php">Manage Questions</a>
<a href="export_data.php">Export Data</a>
<a href="logout.php" class="logout">Logout</a>
</div>
</div>
</body>
</html>
