<?php 
include('../includes/db.php');
if(!isset($_SESSION['admin'])){ 
    header("Location: login.php");
    exit;
}

// Handle actions
if(isset($_POST['reorder_questions'])){
    $question_orders = json_decode($_POST['question_orders'], true);
    if($question_orders){
        foreach($question_orders as $question_id => $order){
            $qid = intval($question_id);
            $order_no = intval($order);
            $conn->query("UPDATE questions SET order_no=$order_no WHERE id=$qid");
        }
        echo json_encode(['success' => true]);
        exit;
    }
}

if(isset($_POST['reorder_answers'])){
    $answer_orders = json_decode($_POST['answer_orders'], true);
    if($answer_orders){
        foreach($answer_orders as $answer_id => $order){
            $aid = intval($answer_id);
            $order_no = intval($order);
            $conn->query("UPDATE answers SET order_no=$order_no WHERE id=$aid");
        }
        echo json_encode(['success' => true]);
        exit;
    }
}

if(isset($_POST['add_question'])){
    $survey_id = intval($_POST['survey_id']);
    $question_text = $conn->real_escape_string($_POST['question_text']);
    $answer_type = $_POST['answer_type'];
    $target_role = $_POST['target_role'] ?? 'Both';
    $order_no = intval($_POST['order_no']);
    
    $stmt = $conn->prepare("INSERT INTO questions (survey_id, question_text, answer_type, target_role, order_no) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isssi", $survey_id, $question_text, $answer_type, $target_role, $order_no);
    $stmt->execute();
    $question_id = $conn->insert_id;
    
    // Add answers
    if(isset($_POST['answers']) && is_array($_POST['answers'])){
        $order = 1;
        foreach($_POST['answers'] as $answer_text){
            if(!empty(trim($answer_text))){
                $answer_text = $conn->real_escape_string($answer_text);
                $conn->query("INSERT INTO answers (question_id, answer_text, order_no) VALUES ($question_id, '$answer_text', $order)");
                $order++;
            }
        }
    }
    
    $success = "Question added successfully!";
}

if(isset($_POST['delete_question'])){
    $qid = intval($_POST['question_id']);
    $conn->query("DELETE FROM questions WHERE id=$qid");
    $success = "Question deleted!";
}

if(isset($_POST['edit_question'])){
    $qid = intval($_POST['question_id']);
    $question_text = $conn->real_escape_string($_POST['question_text']);
    $answer_type = $_POST['answer_type'];
    $target_role = $_POST['target_role'] ?? 'Both';
    $order_no = intval($_POST['order_no']);
    
    $stmt = $conn->prepare("UPDATE questions SET question_text=?, answer_type=?, target_role=?, order_no=? WHERE id=?");
    $stmt->bind_param("sssii", $question_text, $answer_type, $target_role, $order_no, $qid);
    $stmt->execute();
    
    $success = "Question updated successfully!";
    header("Location: manage_questions.php?survey_id=$selected_survey");
    exit;
}

if(isset($_POST['delete_answer'])){
    $aid = intval($_POST['answer_id']);
    $conn->query("DELETE FROM answers WHERE id=$aid");
    $success = "Answer deleted!";
}

if(isset($_POST['add_answer_to_question'])){
    $qid = intval($_POST['question_id']);
    $answer_text = $conn->real_escape_string($_POST['answer_text']);
    
    // Get next order number
    $order_result = $conn->query("SELECT MAX(order_no) as max_order FROM answers WHERE question_id=$qid");
    $max_order = $order_result->fetch_assoc()['max_order'] ?? 0;
    $new_order = $max_order + 1;
    
    $conn->query("INSERT INTO answers (question_id, answer_text, order_no) VALUES ($qid, '$answer_text', $new_order)");
    
    $success = "Answer added successfully!";
    header("Location: manage_questions.php?survey_id=$selected_survey");
    exit;
}

if(isset($_POST['edit_answer'])){
    $aid = intval($_POST['answer_id']);
    $answer_text = $conn->real_escape_string($_POST['answer_text']);
    
    $stmt = $conn->prepare("UPDATE answers SET answer_text=? WHERE id=?");
    $stmt->bind_param("si", $answer_text, $aid);
    $stmt->execute();
    
    echo json_encode(['success' => true]);
    exit;
}

if(isset($_POST['add_answer_to_question'])){
    $qid = intval($_POST['question_id']);
    $answer_text = $conn->real_escape_string($_POST['answer_text']);
    
    // Get next order number
    $order_result = $conn->query("SELECT MAX(order_no) as max_order FROM answers WHERE question_id=$qid");
    $max_order = $order_result->fetch_assoc()['max_order'] ?? 0;
    $new_order = $max_order + 1;
    
    $conn->query("INSERT INTO answers (question_id, answer_text, order_no) VALUES ($qid, '$answer_text', $new_order)");
    
    $success = "Answer added successfully!";
    header("Location: manage_questions.php?survey_id=$selected_survey");
    exit;
}

// Get surveys
$surveys = $conn->query("SELECT * FROM surveys ORDER BY id DESC");
$selected_survey = isset($_GET['survey_id']) ? intval($_GET['survey_id']) : 1;
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Questions - Admin</title>
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif;
    background: #f5f5f5;
    color: #333;
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
    max-width: 1200px;
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
h1, h2 { margin-bottom: 20px; color: #333; }
h2 { font-size: 20px; border-bottom: 2px solid #4CAF50; padding-bottom: 10px; }
.success {
    background: #d4edda;
    color: #155724;
    padding: 15px;
    border-radius: 4px;
    margin-bottom: 20px;
    border-left: 4px solid #28a745;
}
.form-group {
    margin-bottom: 15px;
}
.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 500;
    color: #555;
}
.form-group input[type="text"],
.form-group input[type="number"],
.form-group textarea,
.form-group select {
    width: 100%;
    padding: 10px;
    border: 2px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}
.form-group textarea {
    min-height: 80px;
    resize: vertical;
}
.btn {
    padding: 10px 20px;
    background: #4CAF50;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    margin-right: 10px;
}
.btn:hover { background: #45a049; }
.btn-danger {
    background: #f44336;
}
.btn-danger:hover { background: #da190b; }
.btn-small {
    padding: 5px 10px;
    font-size: 12px;
}
.question-item {
    background: #f8f9fa;
    padding: 15px;
    margin-bottom: 15px;
    border-radius: 4px;
    border-left: 4px solid #4CAF50;
    cursor: move;
    transition: all 0.2s;
}
.question-item:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}
.question-item.dragging {
    opacity: 0.5;
}
.question-item[data-role="Parent"] {
    border-left-color: #FF9800;
    background: linear-gradient(to right, #fff3e0 0%, #f8f9fa 30px);
}
.question-item[data-role="Student"] {
    border-left-color: #2196F3;
    background: linear-gradient(to right, #e3f2fd 0%, #f8f9fa 30px);
}
.question-item[data-role="Both"] {
    border-left-color: #4CAF50;
    background: linear-gradient(to right, #e8f5e9 0%, #f8f9fa 30px);
}
.question-item h4 {
    margin-bottom: 10px;
    color: #333;
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
}
.question-item h4 .question-text {
    flex: 1;
    min-width: 200px;
}
.question-item .question-drag-handle {
    cursor: grab;
    padding: 0 10px;
    color: #999;
    font-size: 20px;
    margin-right: 10px;
}
.question-item .question-drag-handle:active {
    cursor: grabbing;
}
.answer-list {
    margin: 10px 0;
    padding-left: 20px;
}
.answer-item {
    padding: 8px;
    margin: 5px 0;
    background: white;
    border-radius: 4px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: move;
    transition: all 0.2s;
}
.answer-item:hover {
    background: #f0f0f0;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
.answer-item.dragging {
    opacity: 0.5;
}
.answer-item .drag-handle {
    cursor: grab;
    padding: 0 8px;
    color: #999;
    font-size: 18px;
}
.badge {
    display: inline-block;
    padding: 4px 8px;
    background: #2196F3;
    color: white;
    border-radius: 3px;
    font-size: 12px;
    margin-left: 10px;
}
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    overflow: auto;
}
.modal.active {
    display: flex;
    align-items: center;
    justify-content: center;
}
.modal-content {
    background: white;
    padding: 30px;
    border-radius: 8px;
    max-width: 600px;
    width: 90%;
    max-height: 90vh;
    overflow-y: auto;
}
.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}
.modal-header h3 {
    margin: 0;
}
.close-modal {
    font-size: 28px;
    font-weight: bold;
    color: #999;
    cursor: pointer;
    border: none;
    background: none;
    padding: 0;
    width: 30px;
    height: 30px;
    line-height: 1;
}
.close-modal:hover {
    color: #333;
}
.inline-edit {
    display: none;
    padding: 10px;
    background: #f0f0f0;
    border-radius: 4px;
    margin-top: 5px;
}
.inline-edit.active {
    display: block;
}
.inline-edit input {
    width: calc(100% - 180px);
    padding: 8px;
    border: 2px solid #ddd;
    border-radius: 4px;
    margin-right: 10px;
}
.answer-inputs {
    margin-top: 10px;
}
.answer-input-row {
    display: flex;
    gap: 10px;
    margin-bottom: 10px;
}
.answer-input-row input {
    flex: 1;
}
#answersList {
    margin-top: 10px;
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
    .answer-input-row {
        flex-direction: column;
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
        <a href="view_users.php">Users</a>
        <a href="manage_questions.php">Questions</a>
        <a href="view_responses.php">Responses</a>
        <a href="export_data.php">Export</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">
    <h1>Manage Survey Questions</h1>
    
    <?php if(isset($success)): ?>
    <div class="success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <div class="card">
        <h2>Select Survey</h2>
        <form method="get">
            <div class="form-group">
                <select name="survey_id" onchange="this.form.submit()">
                    <?php while($survey = $surveys->fetch_assoc()): ?>
                    <option value="<?php echo $survey['id']; ?>" <?php echo $selected_survey == $survey['id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($survey['title']); ?>
                    </option>
                    <?php endwhile; ?>
                </select>
            </div>
        </form>
    </div>
    
    <div class="card">
        <h2>Add New Question</h2>
        <form method="post" id="addQuestionForm">
            <input type="hidden" name="survey_id" value="<?php echo $selected_survey; ?>">
            
            <div class="form-group">
                <label>Question Text:</label>
                <textarea name="question_text" required></textarea>
            </div>
            
            <div class="form-group">
                <label>Answer Type:</label>
                <select name="answer_type" required>
                    <option value="radio">Radio Button (Single Choice)</option>
                    <option value="checkbox">Checkbox (Multiple Choice)</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>This question is for:</label>
                <div style="display: flex; gap: 20px; padding: 10px 0; flex-wrap: wrap;">
                    <label style="display: flex; align-items: center; cursor: pointer;">
                        <input type="radio" name="target_role" value="Parent" style="margin-right: 8px;">
                        <span>Parent</span>
                    </label>
                    <label style="display: flex; align-items: center; cursor: pointer;">
                        <input type="radio" name="target_role" value="School Student" style="margin-right: 8px;">
                        <span>School Student</span>
                    </label>
                    <label style="display: flex; align-items: center; cursor: pointer;">
                        <input type="radio" name="target_role" value="College Student" style="margin-right: 8px;">
                        <span>College Student</span>
                    </label>
                    <label style="display: flex; align-items: center; cursor: pointer;">
                        <input type="radio" name="target_role" value="Both" checked style="margin-right: 8px;">
                        <span>Both</span>
                    </label>
                </div>
            </div>
            
            <div class="form-group">
                <label>Order Number:</label>
                <input type="number" name="order_no" value="1" required>
            </div>
            
            <div class="form-group">
                <label>Answer Options:</label>
                <div id="answersList">
                    <div class="answer-input-row">
                        <input type="text" name="answers[]" placeholder="Answer option 1">
                    </div>
                    <div class="answer-input-row">
                        <input type="text" name="answers[]" placeholder="Answer option 2">
                    </div>
                </div>
                <button type="button" id="addMoreOptionsBtn" class="btn btn-small">+ Add More Options</button>
            </div>
            
            <button type="submit" name="add_question" class="btn">Add Question</button>
        </form>
    </div>
    
    <div class="card">
        <h2>Existing Questions <span style="font-size: 14px; color: #666; font-weight: normal;">(Drag to reorder)</span></h2>
        <div class="questions-container">
        <?php
        $questions = $conn->query("SELECT * FROM questions WHERE survey_id=$selected_survey ORDER BY order_no");
        if($questions->num_rows == 0){
            echo "<p>No questions yet. Add your first question above.</p>";
        }
        while($q = $questions->fetch_assoc()):
        $role = $q['target_role'] ?? 'Both';
        ?>
        <div class="question-item" draggable="true" data-question-id="<?php echo $q['id']; ?>" data-role="<?php echo $role; ?>">
            <h4>
                <span class="question-drag-handle">☰</span>
                <span class="question-text"><?php echo htmlspecialchars($q['question_text']); ?></span>
                <span class="badge"><?php echo strtoupper($q['answer_type']); ?></span>
                <span class="badge" style="background: <?php 
                    echo $role === 'Parent' ? '#FF9800' : ($role === 'School Student' ? '#2196F3' : ($role === 'College Student' ? '#9C27B0' : '#4CAF50')); 
                ?>;"><?php echo $role; ?></span>
                <span class="badge">Order: <?php echo $q['order_no']; ?></span>
            </h4>
            
            <div class="answer-list">
                <strong>Answers:</strong>
                <div class="answers-container" data-question-id="<?php echo $q['id']; ?>">
                <?php
                $answers = $conn->query("SELECT * FROM answers WHERE question_id=" . $q['id'] . " ORDER BY order_no, id");
                while($a = $answers->fetch_assoc()):
                ?>
                <div class="answer-item" draggable="true" data-answer-id="<?php echo $a['id']; ?>">
                    <span class="answer-display">
                        <span class="drag-handle">☰</span> 
                        <span class="answer-text"><?php echo htmlspecialchars($a['answer_text']); ?></span>
                    </span>
                    <div style="display: flex; gap: 5px;">
                        <button type="button" class="btn btn-small" onclick="editAnswer(<?php echo $a['id']; ?>)" style="background: #2196F3;">Edit</button>
                        <form method="post" style="display:inline">
                            <input type="hidden" name="answer_id" value="<?php echo $a['id']; ?>">
                            <button type="submit" name="delete_answer" class="btn btn-danger btn-small" onclick="return confirm('Delete this answer?')">Delete</button>
                        </form>
                    </div>
                    <div class="inline-edit" id="edit-answer-<?php echo $a['id']; ?>">
                        <input type="text" value="<?php echo htmlspecialchars($a['answer_text']); ?>" id="answer-input-<?php echo $a['id']; ?>">
                        <button type="button" class="btn btn-small" onclick="saveAnswer(<?php echo $a['id']; ?>)">Save</button>
                        <button type="button" class="btn btn-small" onclick="cancelEditAnswer(<?php echo $a['id']; ?>)" style="background: #999;">Cancel</button>
                    </div>
                </div>
                <?php endwhile; ?>
                </div>
                <button type="button" class="btn btn-small" onclick="addNewAnswer(<?php echo $q['id']; ?>)" style="margin-top: 10px; background: #2196F3;">+ Add Answer</button>
            </div>
            
            <form method="post" style="margin-top:10px; display: flex; gap: 10px;">
                <input type="hidden" name="question_id" value="<?php echo $q['id']; ?>">
                <button type="button" class="btn btn-small" onclick="editQuestion(<?php echo $q['id']; ?>)" style="background: #2196F3;">Edit Question</button>
                <button type="submit" name="delete_question" class="btn btn-danger btn-small" onclick="return confirm('Delete this question and all its answers?')">Delete Question</button>
            </form>
        </div>
        <?php endwhile; ?>
        </div>
    </div>
</div>

<!-- Edit Question Modal -->
<div id="editQuestionModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Edit Question</h3>
            <button class="close-modal" onclick="closeEditModal()">&times;</button>
        </div>
        <form method="post" id="editQuestionForm">
            <input type="hidden" name="question_id" id="edit_question_id">
            <input type="hidden" name="survey_id" value="<?php echo $selected_survey; ?>">
            
            <div class="form-group">
                <label>Question Text:</label>
                <textarea name="question_text" id="edit_question_text" required></textarea>
            </div>
            
            <div class="form-group">
                <label>Answer Type:</label>
                <select name="answer_type" id="edit_answer_type" required>
                    <option value="radio">Radio Button (Single Choice)</option>
                    <option value="checkbox">Checkbox (Multiple Choice)</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>This question is for:</label>
                <div style="display: flex; gap: 20px; padding: 10px 0;">
                    <label style="display: flex; align-items: center; cursor: pointer;">
                        <input type="radio" name="target_role" value="Parent" id="edit_role_parent" style="margin-right: 8px;">
                        <span>Parent</span>
                    </label>
                    <label style="display: flex; align-items: center; cursor: pointer;">
                        <input type="radio" name="target_role" value="Student" id="edit_role_student" style="margin-right: 8px;">
                        <span>Student</span>
                    </label>
                    <label style="display: flex; align-items: center; cursor: pointer;">
                        <input type="radio" name="target_role" value="Both" id="edit_role_both" style="margin-right: 8px;">
                        <span>Both</span>
                    </label>
                </div>
            </div>
            
            <div class="form-group">
                <label>Order Number:</label>
                <input type="number" name="order_no" id="edit_order_no" required>
            </div>
            
            <button type="submit" name="edit_question" class="btn">Update Question</button>
            <button type="button" class="btn" onclick="closeEditModal()" style="background: #999;">Cancel</button>
        </form>
    </div>
</div>

<!-- Add Answer Modal -->
<div id="addAnswerModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Add New Answer</h3>
            <button class="close-modal" onclick="closeAddAnswerModal()">&times;</button>
        </div>
        <form method="post">
            <input type="hidden" name="question_id" id="add_answer_question_id">
            
            <div class="form-group">
                <label>Answer Text:</label>
                <input type="text" name="answer_text" required>
            </div>
            
            <button type="submit" name="add_answer_to_question" class="btn">Add Answer</button>
            <button type="button" class="btn" onclick="closeAddAnswerModal()" style="background: #999;">Cancel</button>
        </form>
    </div>
</div>

<script>
// Test that script is loading
console.log('manage_questions.php script loaded');

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM ready');
    const container = document.getElementById('answersList');
    console.log('answersList found:', container !== null);
    
    // Test the button exists
    const addBtn = document.getElementById('addMoreOptionsBtn');
    console.log('Add More Options button found:', addBtn !== null);
    
    // Add event listener to the button
    if (addBtn) {
        addBtn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Button clicked via event listener');
            addAnswerField();
        });
    }
});

function addAnswerField() {
    console.log('addAnswerField called');
    try {
        const container = document.getElementById('answersList');
        if (!container) {
            console.error('answersList container not found');
            alert('Error: Container not found. Please refresh the page.');
            return;
        }
        const count = container.children.length + 1;
        const div = document.createElement('div');
        div.className = 'answer-input-row';
        const input = document.createElement('input');
        input.type = 'text';
        input.name = 'answers[]';
        input.placeholder = 'Answer option ' + count;
        div.appendChild(input);
        container.appendChild(div);
        console.log('Added answer field #' + count);
    } catch (error) {
        console.error('Error in addAnswerField:', error);
        alert('Error adding field: ' + error.message);
    }
}

// Edit Question
function editQuestion(questionId) {
    // Fetch question data
    fetch('?get_question=' + questionId)
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            
            // Find the question item
            const questionItem = document.querySelector(`[data-question-id="${questionId}"]`);
            const questionText = questionItem.querySelector('.question-text').textContent;
            const badges = questionItem.querySelectorAll('.badge');
            const answerType = badges[0].textContent.toLowerCase();
            const role = badges[1].textContent;
            const order = questionItem.querySelector('.badge:last-child').textContent.replace('Order: ', '');
            
            // Populate modal
            document.getElementById('edit_question_id').value = questionId;
            document.getElementById('edit_question_text').value = questionText;
            document.getElementById('edit_answer_type').value = answerType;
            document.getElementById('edit_order_no').value = order;
            
            // Set role radio
            if(role === 'Parent') {
                document.getElementById('edit_role_parent').checked = true;
            } else if(role === 'School Student') {
                document.getElementById('edit_role_school_student').checked = true;
            } else if(role === 'College Student') {
                document.getElementById('edit_role_college_student').checked = true;
            } else {
                document.getElementById('edit_role_both').checked = true;
            }
            
            // Show modal
            document.getElementById('editQuestionModal').classList.add('active');
        });
}

function closeEditModal() {
    document.getElementById('editQuestionModal').classList.remove('active');
}

// Edit Answer inline
function editAnswer(answerId) {
    const editDiv = document.getElementById('edit-answer-' + answerId);
    const answerItem = editDiv.closest('.answer-item');
    const displaySpan = answerItem.querySelector('.answer-display');
    
    displaySpan.style.display = 'none';
    editDiv.classList.add('active');
}

function cancelEditAnswer(answerId) {
    const editDiv = document.getElementById('edit-answer-' + answerId);
    const answerItem = editDiv.closest('.answer-item');
    const displaySpan = answerItem.querySelector('.answer-display');
    
    editDiv.classList.remove('active');
    displaySpan.style.display = 'flex';
}

function saveAnswer(answerId) {
    const input = document.getElementById('answer-input-' + answerId);
    const newText = input.value;
    
    fetch('manage_questions.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'edit_answer=1&answer_id=' + answerId + '&answer_text=' + encodeURIComponent(newText)
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            const answerItem = document.getElementById('edit-answer-' + answerId).closest('.answer-item');
            answerItem.querySelector('.answer-text').textContent = newText;
            cancelEditAnswer(answerId);
        }
    })
    .catch(error => console.error('Error:', error));
}

// Add new answer
function addNewAnswer(questionId) {
    document.getElementById('add_answer_question_id').value = questionId;
    document.getElementById('addAnswerModal').classList.add('active');
}

function closeAddAnswerModal() {
    document.getElementById('addAnswerModal').classList.remove('active');
}

// Drag and drop functionality
let draggedElement = null;
let draggedQuestion = null;

document.addEventListener('DOMContentLoaded', function() {
    // Answer drag and drop
    const answerContainers = document.querySelectorAll('.answers-container');
    
    answerContainers.forEach(container => {
        const items = container.querySelectorAll('.answer-item');
        
        items.forEach(item => {
            item.addEventListener('dragstart', handleDragStart);
            item.addEventListener('dragend', handleDragEnd);
            item.addEventListener('dragover', handleDragOver);
            item.addEventListener('drop', handleDrop);
            item.addEventListener('dragenter', handleDragEnter);
            item.addEventListener('dragleave', handleDragLeave);
        });
    });
    
    // Question drag and drop
    const questionItems = document.querySelectorAll('.question-item');
    
    questionItems.forEach(item => {
        item.addEventListener('dragstart', handleQuestionDragStart);
        item.addEventListener('dragend', handleQuestionDragEnd);
        item.addEventListener('dragover', handleDragOver);
        item.addEventListener('drop', handleQuestionDrop);
        item.addEventListener('dragenter', handleQuestionDragEnter);
        item.addEventListener('dragleave', handleQuestionDragLeave);
    });
});

// Question drag handlers
function handleQuestionDragStart(e) {
    draggedQuestion = this;
    this.classList.add('dragging');
    e.dataTransfer.effectAllowed = 'move';
    e.dataTransfer.setData('text/html', this.innerHTML);
}

function handleQuestionDragEnd(e) {
    this.classList.remove('dragging');
    
    // Save new order to database
    const container = document.querySelector('.questions-container');
    const items = container.querySelectorAll('.question-item');
    const orderData = {};
    
    items.forEach((item, index) => {
        const questionId = item.getAttribute('data-question-id');
        orderData[questionId] = index + 1;
    });
    
    // Send AJAX request to save order
    fetch('manage_questions.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'reorder_questions=1&question_orders=' + encodeURIComponent(JSON.stringify(orderData))
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            console.log('Question order saved successfully');
            // Update order badges
            items.forEach((item, index) => {
                const badge = item.querySelector('.badge:last-child');
                if(badge) badge.textContent = 'Order: ' + (index + 1);
            });
        }
    })
    .catch(error => console.error('Error:', error));
}

function handleQuestionDragEnter(e) {
    if (this !== draggedQuestion && this.classList.contains('question-item')) {
        this.style.borderTop = '3px solid #4CAF50';
    }
}

function handleQuestionDragLeave(e) {
    if (this.classList.contains('question-item')) {
        this.style.borderTop = '';
    }
}

function handleQuestionDrop(e) {
    if (e.stopPropagation) {
        e.stopPropagation();
    }
    
    this.style.borderTop = '';
    
    if (draggedQuestion && draggedQuestion !== this && this.classList.contains('question-item')) {
        const container = this.closest('.questions-container');
        const allItems = [...container.querySelectorAll('.question-item')];
        const draggedIndex = allItems.indexOf(draggedQuestion);
        const targetIndex = allItems.indexOf(this);
        
        if (draggedIndex < targetIndex) {
            this.parentNode.insertBefore(draggedQuestion, this.nextSibling);
        } else {
            this.parentNode.insertBefore(draggedQuestion, this);
        }
    }
    
    return false;
}

// Answer drag handlers
function handleDragStart(e) {
    draggedElement = this;
    this.classList.add('dragging');
    e.dataTransfer.effectAllowed = 'move';
    e.dataTransfer.setData('text/html', this.innerHTML);
}

function handleDragEnd(e) {
    this.classList.remove('dragging');
    
    // Save new order to database
    const container = this.closest('.answers-container');
    const items = container.querySelectorAll('.answer-item');
    const orderData = {};
    
    items.forEach((item, index) => {
        const answerId = item.getAttribute('data-answer-id');
        orderData[answerId] = index + 1;
    });
    
    // Send AJAX request to save order
    fetch('manage_questions.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'reorder_answers=1&answer_orders=' + encodeURIComponent(JSON.stringify(orderData))
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            console.log('Order saved successfully');
        }
    })
    .catch(error => console.error('Error:', error));
}

function handleDragOver(e) {
    if (e.preventDefault) {
        e.preventDefault();
    }
    e.dataTransfer.dropEffect = 'move';
    return false;
}

function handleDragEnter(e) {
    if (this !== draggedElement) {
        this.style.borderTop = '2px solid #4CAF50';
    }
}

function handleDragLeave(e) {
    this.style.borderTop = '';
}

function handleDrop(e) {
    if (e.stopPropagation) {
        e.stopPropagation();
    }
    
    this.style.borderTop = '';
    
    if (draggedElement !== this) {
        const container = this.closest('.answers-container');
        const allItems = [...container.querySelectorAll('.answer-item')];
        const draggedIndex = allItems.indexOf(draggedElement);
        const targetIndex = allItems.indexOf(this);
        
        if (draggedIndex < targetIndex) {
            this.parentNode.insertBefore(draggedElement, this.nextSibling);
        } else {
            this.parentNode.insertBefore(draggedElement, this);
        }
    }
    
    return false;
}
</script>

</body>
</html>
