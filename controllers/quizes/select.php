<?php

$id = $_GET['id'] ?? null;


if (!$id) {
    header('location: /'); 
    exit();
}

$_SESSION['quiz'] = [
    'topic_id' => $id,
    'current_step' => 0,
    'score' => 0
];

header('location: /test'); 
exit();