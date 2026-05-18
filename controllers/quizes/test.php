<?php
session_start();

require_once __DIR__ . '/../../Database.php';
$config = require __DIR__ . '/../../config.php';
$db = new Database($config['database']);

// Get topic ID from URL
$topicId = (int)($_GET['id'] ?? 0);

// If no topic selected, redirect to home
if ($topicId === 0) {
    header('Location: /');
    exit();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Please login to take quiz";
    header('Location: /');
    exit();
}

// Initialize new quiz session if topic changed or no quiz exists
if (!isset($_SESSION['quiz']) || $_SESSION['quiz']['topic_id'] != $topicId) {
    $_SESSION['quiz'] = [
        'topic_id' => $topicId,
        'current_step' => 0,
        'score' => 0
    ];
}

$quiz = &$_SESSION['quiz'];
$currentStep = $quiz['current_step'];
$score = $quiz['score'];

// Get topic info
$topic = $db->query("SELECT * FROM topics WHERE id = :id", [
    'id' => $topicId
])->fetch(PDO::FETCH_ASSOC);

if (!$topic) {
    header('Location: /');
    exit();
}

// Get all questions for this topic
$questions = $db->query("SELECT * FROM questions WHERE topic_id = :id", [
    'id' => $topicId
])->fetchAll(PDO::FETCH_ASSOC);

$totalQuestions = count($questions);

// DEBUG: Check if questions are loaded
if (empty($questions)) {
    die("No questions found for topic ID: " . $topicId . " - Please check your database");
}

// Handle form submission (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $answer_id = (int)($_POST['answer_id'] ?? 0);
    
    if ($answer_id > 0) {
        // Check if answer is correct
        $result = $db->query("SELECT is_correct FROM answers WHERE id = :id", [
            'id' => $answer_id
        ])->fetch(PDO::FETCH_ASSOC);
        
        if ($result && $result['is_correct']) {
            $quiz['score']++;
        }
    }
    
    // Move to next question
    $quiz['current_step']++;
    
    // Redirect to refresh the page (avoid form resubmission)
    header('Location: /test?id=' . $topicId);
    exit();
}

// Check if quiz is completed
if ($currentStep >= $totalQuestions) {
    $finalScore = $quiz['score'];
    
    // Save high score to database
    $percentage = ($finalScore / $totalQuestions) * 100;
    
    // Check if user already has a score for this topic
    $existing = $db->query(
        "SELECT id, score FROM high_scores WHERE user_id = :user_id AND topic_id = :topic_id",
        ['user_id' => $_SESSION['user_id'], 'topic_id' => $topicId]
    )->fetch();
    
    if ($existing) {
        // Update only if new score is better
        if ($finalScore > $existing['score']) {
            $db->query(
                "UPDATE high_scores SET score = :score, total_questions = :total, percentage = :percentage, created_at = NOW() WHERE id = :id",
                [
                    'score' => $finalScore,
                    'total' => $totalQuestions,
                    'percentage' => $percentage,
                    'id' => $existing['id']
                ]
            );
            $_SESSION['success'] = "New high score! You scored $finalScore/$totalQuestions!";
        }
    } else {
        // Insert new record
        $db->query(
            "INSERT INTO high_scores (user_id, topic_id, score, total_questions, percentage) VALUES (:user_id, :topic_id, :score, :total, :percentage)",
            [
                'user_id' => $_SESSION['user_id'],
                'topic_id' => $topicId,
                'score' => $finalScore,
                'total' => $totalQuestions,
                'percentage' => $percentage
            ]
        );
        $_SESSION['success'] = "Quiz completed! You scored $finalScore/$totalQuestions!";
    }
    
    // Clear quiz session
    unset($_SESSION['quiz']);
    
    // Show results
    require __DIR__ . "/../../views/quizes/results.view.php";
    die();
}

// Get current question - ADDED SAFETY CHECK
$currentQuestion = $questions[$currentStep] ?? null;

// DEBUG: Check if current question exists
if (!$currentQuestion) {
    die("Question not found at index: " . $currentStep . " - Total questions: " . $totalQuestions);
}

// Get answers for current question
$answers = $db->query("SELECT * FROM answers WHERE question_id = :id", [
    'id' => $currentQuestion['id']
])->fetchAll(PDO::FETCH_ASSOC);

// Shuffle answers so they appear in random order each time
shuffle($answers);

// Pass variables to view
$currentStepIndex = $currentStep;
$questionText = $currentQuestion['text'] ?? 'No question text found';
$questionId = $currentQuestion['id'];

require __DIR__ . "/../../views/quizes/test.view.php";
?>