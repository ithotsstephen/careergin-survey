<?php 
session_start();

// Store completion data before destroying session
$completed = isset($_SESSION['user_id']);

session_destroy();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Thank You - CareerG Survey</title>
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
    border-radius: 16px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    max-width: 500px;
    width: 100%;
    padding: 50px 40px;
    text-align: center;
}
.logo {
    margin-bottom: 30px;
}
.logo img {
    max-width: 200px;
    width: 100%;
}
.success-icon {
    font-size: 80px;
    margin-bottom: 20px;
    animation: bounceIn 0.6s;
}
@keyframes bounceIn {
    0% { transform: scale(0); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}
h1 {
    font-size: 32px;
    color: #333;
    margin-bottom: 15px;
}
.message {
    color: #666;
    font-size: 16px;
    margin-bottom: 30px;
    line-height: 1.6;
}
.btn-home {
    display: inline-block;
    padding: 15px 40px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    text-decoration: none;
    border-radius: 50px;
    font-size: 16px;
    font-weight: 600;
    transition: all 0.3s;
}
.btn-home:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
}
.info-box {
    background: #e8f5e9;
    padding: 20px;
    border-radius: 8px;
    margin: 30px 0;
    color: #2e7d32;
    font-size: 14px;
}
@media (max-width: 600px) {
    .container {
        padding: 40px 25px;
    }
    h1 {
        font-size: 26px;
    }
    .success-icon {
        font-size: 60px;
    }
}
</style>
</head>
<body>
<div class="container">
    <div class="logo">
        <img src="https://careerg.in/assets/CareergLogo-BvzyUmlH.png" alt="CareerG">
    </div>
    
    <?php if($completed): ?>
    <div class="success-icon">✓</div>
    <h1>Thank You!</h1>
    <p class="message">
        Your survey has been submitted successfully. We appreciate you taking the time to share your valuable feedback with us.
    </p>
    
    <div class="info-box">
        <strong>What's Next?</strong><br>
        Our team will review your responses and may reach out if needed.
    </div>
    <?php else: ?>
    <h1>Session Expired</h1>
    <p class="message">
        Your session has expired. Please start the survey again.
    </p>
    <?php endif; ?>
    
    <a href="index.php" class="btn-home">Return to Home</a>
</div>
</body>
</html>