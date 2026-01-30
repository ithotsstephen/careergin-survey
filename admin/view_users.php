<?php 
include('../includes/db.php');
if(!isset($_SESSION['admin'])){ 
    header("Location: login.php");
    exit;
}

// Get all users with their details
$users = $conn->query("SELECT * FROM users ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>View Users - Admin</title>
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
.header h1 { font-size: 20px; }
.menu {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}
.menu a {
    padding: 8px 15px;
    background: rgba(255,255,255,0.2);
    color: white;
    text-decoration: none;
    border-radius: 4px;
    font-size: 14px;
}
.menu a:hover { background: rgba(255,255,255,0.3); }
.logout { background: #f44336 !important; }
.logout:hover { background: #da190b !important; }
.container {
    max-width: 1400px;
    margin: 30px auto;
    padding: 0 20px;
}
.card {
    background: white;
    border-radius: 8px;
    padding: 25px;
    margin-bottom: 25px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
.card h2 {
    color: #4CAF50;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #f0f0f0;
}
.stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-bottom: 25px;
}
.stat-box {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 20px;
    border-radius: 8px;
    text-align: center;
}
.stat-box h3 {
    font-size: 14px;
    font-weight: 500;
    margin-bottom: 10px;
    opacity: 0.9;
}
.stat-box p {
    font-size: 32px;
    font-weight: bold;
}
table {
    width: 100%;
    border-collapse: collapse;
}
thead {
    background: #f8f9fa;
}
th {
    text-align: left;
    padding: 12px;
    font-weight: 600;
    color: #555;
    border-bottom: 2px solid #e0e0e0;
}
td {
    padding: 12px;
    border-bottom: 1px solid #f0f0f0;
}
tr:hover {
    background: #f8f9fa;
}
.badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 500;
    color: white;
}
.badge-parent { background: #FF9800; }
.badge-school { background: #2196F3; }
.badge-college { background: #9C27B0; }
.btn {
    padding: 6px 12px;
    background: #4CAF50;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    font-size: 12px;
    border: none;
    cursor: pointer;
}
.btn:hover { background: #45a049; }
.btn-danger {
    background: #f44336;
}
.btn-danger:hover { background: #da190b; }
.search-box {
    margin-bottom: 20px;
}
.search-box input {
    width: 100%;
    max-width: 400px;
    padding: 10px 15px;
    border: 2px solid #e0e0e0;
    border-radius: 4px;
    font-size: 14px;
}
.search-box input:focus {
    outline: none;
    border-color: #4CAF50;
}
@media (max-width: 768px) {
    table {
        font-size: 12px;
    }
    th, td {
        padding: 8px;
    }
}
</style>
</head>
<body>
<div class="header">
    <div>
        <img src="https://careerg.in/assets/CareergLogo-BvzyUmlH.png" alt="CareerG">
    </div>
    <h1>User Management</h1>
    <div class="menu">
        <a href="dashboard.php">Dashboard</a>
        <a href="view_responses.php">Responses</a>
        <a href="manage_questions.php">Questions</a>
        <a href="view_users.php">Users</a>
        <a href="export_data.php">Export</a>
        <a href="logout.php" class="logout">Logout</a>
    </div>
</div>

<div class="container">
    <?php
    // Calculate statistics
    $total_users = $users->num_rows;
    $parent_count = $conn->query("SELECT COUNT(*) as count FROM users WHERE role='Parent'")->fetch_assoc()['count'];
    $school_student_count = $conn->query("SELECT COUNT(*) as count FROM users WHERE role='School Student'")->fetch_assoc()['count'];
    $college_student_count = $conn->query("SELECT COUNT(*) as count FROM users WHERE role='College Student'")->fetch_assoc()['count'];
    ?>
    
    <div class="stats">
        <div class="stat-box">
            <h3>Total Users</h3>
            <p><?php echo $total_users; ?></p>
        </div>
        <div class="stat-box">
            <h3>Parents</h3>
            <p><?php echo $parent_count; ?></p>
        </div>
        <div class="stat-box">
            <h3>School Students</h3>
            <p><?php echo $school_student_count; ?></p>
        </div>
        <div class="stat-box">
            <h3>College Students</h3>
            <p><?php echo $college_student_count; ?></p>
        </div>
    </div>

    <div class="card">
        <h2>All Registered Users</h2>
        
        <div class="search-box">
            <input type="text" id="searchInput" placeholder="🔍 Search by name, email, phone, or role..." onkeyup="filterTable()">
        </div>
        
        <div style="overflow-x: auto;">
            <table id="usersTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Age</th>
                        <th>Education</th>
                        <th>Role</th>
                        <th>Registered</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Reset the result pointer to iterate again
                    $users->data_seek(0);
                    
                    if($total_users == 0) {
                        echo '<tr><td colspan="9" style="text-align: center; padding: 40px; color: #999;">No users registered yet.</td></tr>';
                    } else {
                        while($user = $users->fetch_assoc()):
                            $badge_class = 'badge-parent';
                            if($user['role'] == 'School Student') $badge_class = 'badge-school';
                            elseif($user['role'] == 'College Student') $badge_class = 'badge-college';
                            
                            // Format date if it exists
                            $registered_date = isset($user['created_at']) ? date('M d, Y', strtotime($user['created_at'])) : 'N/A';
                    ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><strong><?php echo htmlspecialchars($user['name']); ?></strong></td>
                        <td><?php echo htmlspecialchars($user['phone']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo $user['age'] > 0 ? $user['age'] : 'N/A'; ?></td>
                        <td><?php echo htmlspecialchars($user['education']); ?></td>
                        <td><span class="badge <?php echo $badge_class; ?>"><?php echo htmlspecialchars($user['role']); ?></span></td>
                        <td><?php echo $registered_date; ?></td>
                        <td>
                            <a href="view_responses.php?user_id=<?php echo $user['id']; ?>" class="btn" title="View Responses">View</a>
                        </td>
                    </tr>
                    <?php endwhile; } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function filterTable() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const table = document.getElementById('usersTable');
    const rows = table.getElementsByTagName('tr');
    
    for (let i = 1; i < rows.length; i++) {
        const row = rows[i];
        const text = row.textContent.toLowerCase();
        
        if (text.includes(filter)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    }
}
</script>

</body>
</html>
