<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CareerG Survey</title>
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
h1 {
    font-size: 32px;
    color: #333;
    margin-bottom: 15px;
}
.subtitle {
    color: #666;
    font-size: 16px;
    margin-bottom: 40px;
    line-height: 1.5;
}
.btn-start {
    display: inline-block;
    padding: 18px 50px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    text-decoration: none;
    border-radius: 50px;
    font-size: 18px;
    font-weight: 600;
    transition: all 0.3s;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
}
.btn-start:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
}
.features {
    margin-top: 40px;
    padding-top: 30px;
    border-top: 1px solid #e0e0e0;
}
.feature {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    color: #666;
    font-size: 14px;
    margin: 10px 0;
}
.feature:before {
    content: "✓";
    color: #4CAF50;
    font-weight: bold;
    font-size: 18px;
}
@media (max-width: 600px) {
    .container {
        padding: 40px 25px;
    }
    h1 {
        font-size: 26px;
    }
    .btn-start {
        padding: 15px 40px;
        font-size: 16px;
    }
}
</style>
</head>
<body>
<div class="container">
    <div class="logo">
        <img src="https://careerg.in/assets/CareergLogo-BvzyUmlH.png" alt="CareerG">
    </div>
    <h1>Welcome to Our Survey</h1>
    <p class="subtitle">Help us understand your career preferences and interests</p>
    <a href="check.php" class="btn-start">Start Survey</a>
    <div class="features">
        <div class="feature">Takes only 2-3 minutes</div>
        <div class="feature">Your responses are confidential</div>
        <div class="feature">Mobile friendly</div>
    </div>
</div>
</body>
</html>
