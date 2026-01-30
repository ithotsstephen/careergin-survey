<?php 
include('../includes/db.php');
if(!isset($_SESSION['admin'])){ 
    header("Location: login.php");
    exit;
}

// Handle export
if(isset($_POST['export'])){
    $survey_id = isset($_POST['survey_id']) ? intval($_POST['survey_id']) : 0;
    $export_type = $_POST['export_type'];
    
    // Get data
    $where = $survey_id > 0 ? "WHERE ua.survey_id = $survey_id" : "";
    
    $query = "
        SELECT 
            u.id as user_id,
            u.name,
            u.phone,
            u.age,
            u.education,
            u.created_at,
            q.question_text,
            GROUP_CONCAT(a.answer_text SEPARATOR '; ') as answers
        FROM users u
        JOIN user_answers ua ON u.id = ua.user_id
        JOIN questions q ON ua.question_id = q.id
        JOIN answers a ON ua.answer_id = a.id
        $where
        GROUP BY u.id, q.id
        ORDER BY u.id, q.order_no";
    
    $result = $conn->query($query);
    
    if($export_type == 'csv'){
        // CSV Export
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="survey_responses_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        
        // Headers
        fputcsv($output, ['User ID', 'Name', 'Phone', 'Age', 'Education', 'Submission Date', 'Question', 'Answers']);
        
        // Data
        while($row = $result->fetch_assoc()){
            fputcsv($output, [
                $row['user_id'],
                $row['name'],
                $row['phone'],
                $row['age'],
                $row['education'],
                $row['created_at'],
                $row['question_text'],
                $row['answers']
            ]);
        }
        
        fclose($output);
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Export Data - Admin</title>
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
    max-width: 800px;
    margin: 20px auto;
    padding: 0 15px;
}
.card {
    background: white;
    border-radius: 8px;
    padding: 30px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
h1 { margin-bottom: 20px; }
.form-group {
    margin-bottom: 20px;
}
.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: #333;
}
.form-group select {
    width: 100%;
    padding: 12px;
    border: 2px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}
.export-options {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin: 20px 0;
}
.export-option {
    padding: 20px;
    border: 2px solid #ddd;
    border-radius: 8px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s;
}
.export-option:hover {
    border-color: #4CAF50;
    background: #f1f8f4;
}
.export-option input[type="radio"] {
    display: none;
}
.export-option input[type="radio"]:checked + label {
    color: #4CAF50;
    font-weight: bold;
}
.export-option label {
    cursor: pointer;
    font-size: 16px;
    display: block;
}
.export-option .icon {
    font-size: 48px;
    margin-bottom: 10px;
}
.btn {
    padding: 12px 30px;
    background: #4CAF50;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
    width: 100%;
}
.btn:hover { background: #45a049; }
.info {
    background: #e3f2fd;
    padding: 15px;
    border-radius: 4px;
    margin-top: 20px;
    border-left: 4px solid #2196F3;
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
    <div class="card">
        <h1>📊 Export Survey Data</h1>
        
        <form method="post">
            <div class="form-group">
                <label>Select Survey:</label>
                <select name="survey_id" required>
                    <option value="0">All Surveys</option>
                    <?php
                    $surveys = $conn->query("SELECT * FROM surveys ORDER BY id DESC");
                    while($s = $surveys->fetch_assoc()):
                    ?>
                    <option value="<?php echo $s['id']; ?>">
                        <?php echo htmlspecialchars($s['title']); ?>
                    </option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label>Export Format:</label>
                <div class="export-options">
                    <div class="export-option">
                        <input type="radio" name="export_type" value="csv" id="csv" checked>
                        <label for="csv">
                            <div class="icon">📄</div>
                            CSV File
                        </label>
                    </div>
                </div>
            </div>
            
            <button type="submit" name="export" class="btn">
                💾 Download Export
            </button>
        </form>
        
        <div class="info">
            <strong>ℹ️ Export Information:</strong><br>
            • CSV files can be opened in Excel, Google Sheets, or any spreadsheet application<br>
            • Includes all user details and their survey responses<br>
            • Data is organized with one row per question per user
        </div>
    </div>
</div>

</body>
</html>
