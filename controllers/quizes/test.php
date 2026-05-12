<?php
if (!isset($_SESSION['quiz'])) {
    header('location: /');
    exit();
}

$quiz = &$_SESSION['quiz']; 
$topic_id = $quiz['topic_id'];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $answer_id = $_POST['answer_id'];
    
    
    $result = $db->query("SELECT is_correct FROM answers WHERE id = ?", [$answer_id])->fetch();

    if ($result && $result['is_correct']) {
        $quiz['score']++;
    }

    $quiz['current_step']++; 
    header('location: /test');
    exit();
}


$questions = $db->query("SELECT * FROM questions WHERE topic_id = ?", [$topic_id])->fetchAll();
$current_question = $questions[$quiz['current_step']] ?? null;


if (!$current_question) {

    $score = $quiz['score']; 
    $totalQuestions = count($questions); 

    unset($_SESSION['quiz']); 
    

    require "views/quizes/results.view.php"; 
    die();
}


$answers = $db->query("SELECT * FROM answers WHERE question_id = ?", [$current_question['id']])->fetchAll();

$topic = $db->query("SELECT name FROM topics WHERE id = ?", [$topic_id])->fetch();


$currentStep = $quiz['current_step'];
$totalQuestions = count($questions);

require "views/quizes/test.view.php";