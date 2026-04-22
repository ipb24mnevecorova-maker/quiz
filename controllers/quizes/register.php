<?php
session_start();
require_once __DIR__ . '/../../Database.php';
require_once __DIR__ . '/../../functions.php';

$config = require __DIR__ . '/../../config.php';
$db = new Database($config['database']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Validation
    $errors = [];
    
    if (empty($username)) {
        $errors[] = "Username is required";
    } elseif (strlen($username) < 3) {
        $errors[] = "Username must be at least 3 characters";
    }
    
    if (empty($password)) {
        $errors[] = "Password is required";
    } elseif (strlen($password) < 4) {
        $errors[] = "Password must be at least 4 characters";
    }
    
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match";
    }
    
    // Check if username already exists
    $stmt = $db->query("SELECT id FROM users WHERE username = :username", [
        'username' => $username
    ]);
    
    if ($stmt->fetch()) {
        $errors[] = "Username already exists";
    }
    
    if (!empty($errors)) {
        $_SESSION['error'] = implode("<br>", $errors);
        header('Location: /');
        exit();
    }
    
    // Save user (with hashed password)
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    $db->query(
        "INSERT INTO users (username, password, role) VALUES (:username, :password, 'user')",
        [
            'username' => $username,
            'password' => $hashedPassword
        ]
    );
    
    $_SESSION['success'] = "Registration successful! You can now login.";
    header('Location: /');
    exit();
}

// If not a POST request, redirect to home
header('Location: /');
exit();