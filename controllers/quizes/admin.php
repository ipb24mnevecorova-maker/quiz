<?php
session_start();

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['error'] = "You don't have access to this page";
    header('Location: /');
    exit();
}

require_once __DIR__ . '/../../Database.php';
$config = require __DIR__ . '/../../config.php';
$db = new Database($config['database']);

// Get user info for sidebar
$stmt = $db->query("SELECT username, role FROM users WHERE id = :id", [
    'id' => $_SESSION['user_id']
]);
$currentUser = $stmt->fetch(PDO::FETCH_ASSOC);

// Get all users
$stmt = $db->query("SELECT id, username, role, created_at FROM users ORDER BY created_at DESC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle user role change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_role'])) {
    $user_id = $_POST['user_id'];
    $new_role = $_POST['new_role'];
    
    if ($user_id != $_SESSION['user_id']) { // Prevent admin from changing their own role
        $db->query("UPDATE users SET role = :role WHERE id = :id", [
            'role' => $new_role,
            'id' => $user_id
        ]);
        $_SESSION['success'] = "User role changed successfully";
    } else {
        $_SESSION['error'] = "Cannot change your own role";
    }
    
    header('Location: /admin');
    exit();
}

// Handle user deletion
if (isset($_GET['delete'])) {
    $user_id = $_GET['delete'];
    
    if ($user_id != $_SESSION['user_id']) {
        $db->query("DELETE FROM users WHERE id = :id", ['id' => $user_id]);
        $_SESSION['success'] = "User deleted successfully";
    } else {
        $_SESSION['error'] = "Cannot delete yourself";
    }
    
    header('Location: /admin');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Quiz</title>
    <link rel="stylesheet" href="/css/admin.css">
    <link rel="stylesheet" href="/css/sidebar.css">
</head>
<body>
            
        

    <div class="container">
        <div class="admin-header">
            <h1>Admin Panel</h1>
            <div class="admin-nav">
                <a href="/profile" class="nav-link">My Profile</a>
                <a href="/logout" class="nav-link logout">Logout</a>
            </div>
        </div>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?php 
                echo $_SESSION['success']; 
                unset($_SESSION['success']);
                ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <?php 
                echo $_SESSION['error']; 
                unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>
        
        <div class="users-table">
            <h2>User List</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Registered</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                <select name="new_role" onchange="this.form.submit()" <?php echo $user['id'] == $_SESSION['user_id'] ? 'disabled' : ''; ?>>
                                    <option value="user" <?php echo $user['role'] == 'user' ? 'selected' : ''; ?>>User</option>
                                    <option value="admin" <?php echo $user['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                                </select>
                                <input type="hidden" name="change_role" value="1">
                            </form>
                        </td>
                        <td><?php echo $user['created_at']; ?></td>
                        <td>
                            <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                <a href="/admin?delete=<?php echo $user['id']; ?>" 
                                   onclick="return confirm('Are you sure you want to delete this user?')" 
                                   class="btn-delete">Delete</a>
                            <?php else: ?>
                                <span class="text-muted">You</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>