
<?php include('../includes/db.php'); ?>
<!DOCTYPE html>
<html>
<head>
<title>User Information - Survey</title>
<style>
body{text-align:center;font-family:Arial;padding:20px}
input{padding:10px;margin:10px;width:300px}
button{padding:10px 30px;background:#4CAF50;color:white;border:none;cursor:pointer}
</style>
</head>
<body>
<img src="https://careerg.in/assets/CareergLogo-BvzyUmlH.png" width="200"><br>
<h2>Enter Your Details</h2>
<form method="post">
<input type="tel" name="phone" placeholder="Phone" required><br>
<input type="text" name="name" placeholder="Name" required><br>
<input type="number" name="age" placeholder="Age" required><br>
<input type="text" name="education" placeholder="Education" required><br>
<button type="submit">Next</button>
</form>

<?php
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $phone = $conn->real_escape_string($_POST['phone']);
    $name = $conn->real_escape_string($_POST['name']);
    $age = intval($_POST['age']);
    $education = $conn->real_escape_string($_POST['education']);
    
    $stmt = $conn->prepare("INSERT INTO users (phone,name,age,education) VALUES (?,?,?,?)");
    $stmt->bind_param("ssis", $phone, $name, $age, $education);
    
    if($stmt->execute()){
        $_SESSION['user_id'] = $conn->insert_id;
        $_SESSION['q'] = 0;
        header("Location: question.php");
        exit;
    } else {
        echo "<p style='color:red'>Error: " . $conn->error . "</p>";
    }
}
?>
</body>
</html>
