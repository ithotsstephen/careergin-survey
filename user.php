
<?php 
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('includes/db.php'); 

$error_message = "";
$success = false;

// Process form submission BEFORE any HTML output
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $phone = trim($_POST['phone'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $age = intval($_POST['age'] ?? 0);
    $education = trim($_POST['education'] ?? '');
    $role = trim($_POST['role'] ?? '');
    
    // Debug log
    error_log("Survey User Form Submitted: Name=$name, Phone=$phone, Email=$email, Age=$age, Role=$role");
    
    // If Parent is selected, Age and Education are not required
    $isParent = ($role === 'Parent');
    
    // Validate based on role
    if(empty($phone) || empty($name) || empty($email) || empty($role)){
        $error_message = 'Please fill all required fields correctly.';
        error_log("Survey User Form Validation Failed");
    } elseif(!$isParent && ($age < 1 || empty($education))){
        $error_message = 'Please provide your age and education.';
        error_log("Survey User Form Validation Failed - Student missing age/education");
    } else {
        // Set default values for parents
        if($isParent){
            $age = 0;
            $education = 'N/A';
        }
        // Sanitize inputs
        $phone = $conn->real_escape_string($phone);
        $name = $conn->real_escape_string($name);
        $email = $conn->real_escape_string($email);
        $education = $conn->real_escape_string($education);
        $role = $conn->real_escape_string($role);
        
        $stmt = $conn->prepare("INSERT INTO users (phone,name,email,age,education,role) VALUES (?,?,?,?,?,?)");
        
        if($stmt){
            $stmt->bind_param("sssiss", $phone, $name, $email, $age, $education, $role);
            
            if($stmt->execute()){
                $_SESSION['user_id'] = $conn->insert_id;
                $_SESSION['q'] = 0;
                
                error_log("Survey User Saved: ID=" . $_SESSION['user_id']);
                
                // Check if questions exist before redirecting
                $check_questions = $conn->query("SELECT COUNT(*) as count FROM questions WHERE survey_id=1");
                if($check_questions){
                    $q_count = $check_questions->fetch_assoc()['count'];
                    
                    error_log("Survey Questions Check: Found $q_count questions");
                    
                    if($q_count > 0){
                        // Questions exist, redirect to question page
                        error_log("Survey Redirecting to question.php");
                        header("Location: question.php");
                        exit();
                    } else {
                        // No questions found
                        $error_message = 'No survey questions available yet. <a href="admin/quick_fix.php" style="color:#2196F3;text-decoration:underline">Click here to add questions</a>';
                        error_log("Survey Error: No questions found");
                    }
                } else {
                    $error_message = 'Error checking questions: ' . $conn->error;
                    error_log("Survey Error checking questions: " . $conn->error);
                }
            } else {
                $error_message = 'Error saving your information: ' . $stmt->error;
                error_log("Survey Error saving user: " . $stmt->error);
            }
            $stmt->close();
        } else {
            $error_message = 'Database error: ' . $conn->error;
            error_log("Survey Database error: " . $conn->error);
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Your Details - CareerG Survey</title>
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
    max-width: 500px;
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
h2 {
    text-align: center;
    color: #333;
    margin-bottom: 10px;
    font-size: 24px;
}
.subtitle {
    text-align: center;
    color: #666;
    margin-bottom: 30px;
    font-size: 14px;
}
.form-group {
    margin-bottom: 20px;
}
.form-group label {
    display: block;
    margin-bottom: 8px;
    color: #555;
    font-weight: 500;
    font-size: 14px;
}
.form-group input {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: 16px;
    transition: border-color 0.3s;
}
.form-group input:focus {
    outline: none;
    border-color: #667eea;
}
.btn-submit {
    width: 100%;
    padding: 15px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}
.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
}
.error {
    background: #fee;
    color: #c33;
    padding: 12px;
    border-radius: 6px;
    margin-bottom: 20px;
    font-size: 14px;
    border-left: 4px solid #c33;
}
@media (max-width: 600px) {
    .container {
        padding: 30px 25px;
    }
    h2 {
        font-size: 22px;
    }
}
</style>
</head>
<body>
<div class="container">
    <div class="logo">
        <img src="https://careerg.in/assets/CareergLogo-BvzyUmlH.png" alt="CareerG">
    </div>
    <h2>Your Details</h2>
    <p class="subtitle">Please provide your information to continue</p>
    
    <?php if(!empty($error_message)): ?>
    <div class="error"><?php echo htmlspecialchars($error_message); ?></div>
    <?php endif; ?>
    
    <form method="post">
        <div class="form-group">
            <label for="phone">📱 Phone Number *</label>
            <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" required>
        </div>
        
        <div class="form-group">
            <label for="name">👤 Full Name *</label>
            <input type="text" id="name" name="name" placeholder="Enter your full name" required>
        </div>
        
        <div class="form-group">
            <label for="email">📧 Email *</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>
        </div>
        
        <div class="form-group">
            <label>👥 I am a *</label>
            <div style="display: flex; gap: 20px; padding: 10px 0;">
                <label style="display: flex; align-items: center; cursor: pointer;">
                    <input type="radio" id="parent" name="role" value="Parent" required style="width: auto; margin-right: 8px;">
                    <span>Parent</span>
                </label>
                <label style="display: flex; align-items: center; cursor: pointer;">
                    <input type="radio" id="student" name="role" value="Student" required style="width: auto; margin-right: 8px;">
                    <span>Student</span>
                </label>
            </div>
        </div>
        
        <div class="form-group" id="age-group">
            <label for="age">🎂 Age *</label>
            <input type="number" id="age" name="age" placeholder="Enter your age" min="1" max="120" required>
        </div>
        
        <div class="form-group" id="education-group">
            <label for="education">🎓 Education *</label>
            <input type="text" id="education" name="education" placeholder="e.g., Bachelor's, Master's" required>
        </div>
        
        <button type="submit" class="btn-submit">Continue to Survey →</button>
    </form>
    
    <script>
        const parentRadio = document.getElementById('parent');
        const studentRadio = document.getElementById('student');
        const ageGroup = document.getElementById('age-group');
        const educationGroup = document.getElementById('education-group');
        const ageInput = document.getElementById('age');
        const educationInput = document.getElementById('education');
        
        function toggleFields() {
            if (parentRadio.checked) {
                // Hide age and education fields when Parent is selected
                ageGroup.style.display = 'none';
                educationGroup.style.display = 'none';
                // Remove required attribute and clear values
                ageInput.removeAttribute('required');
                educationInput.removeAttribute('required');
                ageInput.value = '0';
                educationInput.value = 'N/A';
            } else if (studentRadio.checked) {
                // Show age and education fields when Student is selected
                ageGroup.style.display = 'block';
                educationGroup.style.display = 'block';
                // Add required attribute back
                ageInput.setAttribute('required', 'required');
                educationInput.setAttribute('required', 'required');
                // Clear the default values if they were set
                if (ageInput.value === '0') ageInput.value = '';
                if (educationInput.value === 'N/A') educationInput.value = '';
            }
        }
        
        // Add event listeners
        parentRadio.addEventListener('change', toggleFields);
        studentRadio.addEventListener('change', toggleFields);
        
        // Check on page load in case there's a pre-selected value
        toggleFields();
    </script>
</div>
</body>
</html>
