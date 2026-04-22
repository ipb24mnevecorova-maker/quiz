<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Please login first";
    header('Location: /');
    exit();
}

require_once __DIR__ . '/../../Database.php';
$config = require __DIR__ . '/../../config.php';
$db = new Database($config['database']);

// Get user information
$stmt = $db->query("SELECT * FROM users WHERE id = :id", [
    'id' => $_SESSION['user_id']
]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Quiz</title>
    <link rel="stylesheet" href="/css/profile.css">
    <link rel="stylesheet" href="/css/sidebar.css">
</head>
<body>
      
    <div class="container">
        <div class="profile-card">
            <h1>My Profile</h1>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?php 
                    echo $_SESSION['success']; 
                    unset($_SESSION['success']);
                    ?>
                </div>
            <?php endif; ?>
            
            <div class="profile-info">
                <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
                <p><strong>Role:</strong> 
                    <?php 
                    if ($user['role'] == 'admin') {
                        echo '<span class="role-admin">Admin</span>';
                    } else {
                        echo '<span class="role-user">User</span>';
                    }
                    ?>
                </p>
                <p><strong>Registered:</strong> <?php echo $user['created_at']; ?></p>
            </div>
            
            <div class="profile-actions">
                <?php if ($user['role'] == 'admin'): ?>
                    <a href="/admin" class="btn btn-admin">Admin Panel</a>
                <?php endif; ?>
                <a href="/" class="btn btn-home">Home</a>
                <a href="/logout" class="btn btn-logout">Logout</a>
            </div>
        </div>
    </div>
    
</body>
</html>