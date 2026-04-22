<?php
session_start();
require_once __DIR__ . '/../../Database.php';
require_once __DIR__ . '/../../functions.php';

$config = require __DIR__ . '/../../config.php';
$db = new Database($config['database']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    $errors = [];
    
    if (empty($username)) {
        $errors[] = "Username is required";
    }
    
    if (empty($password)) {
        $errors[] = "Password is required";
    }
    
    if (!empty($errors)) {
        $_SESSION['error'] = implode("<br>", $errors);
        header('Location: /');
        exit();
    }
    
    // Find user in database
    $stmt = $db->query("SELECT * FROM users WHERE username = :username", [
        'username' => $username
    ]);
    
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Verify password
    if ($user && password_verify($password, $user['password'])) {
        // Login successful
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        
        // Redirect to profile
        header('Location: /');
        exit();
    } else {
        $_SESSION['error'] = "Invalid username or password";
        header('Location: /');
        exit();
    }
}

header('Location: /');
exit();