<?php

$topicId = $_GET['id'] ?? 1;
$currentStep = (int)($_GET['step'] ?? 0);
$score = (int)($_GET['score'] ?? 0);

$topic = $db->query("SELECT * FROM topics WHERE id = :id", [
    'id' => $topicId
])->fetch(PDO::FETCH_ASSOC);

$allQuestions = $db->query("SELECT * FROM questions WHERE topic_id = :id", [
    'id' => $topicId
])->fetchAll(PDO::FETCH_ASSOC);

$totalQuestions = count($allQuestions);

// Check if an answer was submitted from the PREVIOUS question[cite: 14]
if (isset($_GET['answer'])) {
    $lastAnswerId = $_GET['answer'];
    $correctCheck = $db->query("SELECT is_correct FROM answers WHERE id = :id", [
        'id' => $lastAnswerId
    ])->fetch(PDO::FETCH_ASSOC);

    if ($correctCheck && $correctCheck['is_correct']) {
        $score++;
    }
}

// Only show results if we have gone past the last question index[cite: 14]
if ($currentStep >= $totalQuestions) {
    require "views/quizes/results.view.php";
    exit;
}

$question = $allQuestions[$currentStep];
$answers = $db->query("SELECT * FROM answers WHERE question_id = :id", [
    'id' => $question['id']
])->fetchAll(PDO::FETCH_ASSOC);

shuffle($answers);

$nextStep = $currentStep + 1;

require "views/quizes/test.view.php";