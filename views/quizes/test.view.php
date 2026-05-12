<link rel="stylesheet" href="/css/test.css">
<div class="quiz-container">
    <header>
    <h2><?= htmlspecialchars($topic['name']) ?> Quiz</h2>
    <p>Question <?= $currentStep + 1 ?> of <?= $totalQuestions ?></p>
    
    <div class="progress-bar">

        <div class="progress" style="width: <?= (($currentStep + 1) / $totalQuestions) * 100 ?>%;"></div>
    </div>
</header>

<main>
    <h1>Question <?= $_SESSION['quiz']['current_step'] + 1 ?></h1>
    <p><?= htmlspecialchars($current_question['text']) ?></p>

    <form method="POST" action="/test">
    <div class="answers-option">
        <?php foreach ($answers as $answer) : ?>
            <label class="answer-option">
                <input type="radio" name="answer" value="<?= $answer['id'] ?>" required>
                <span><?= htmlspecialchars($answer['text']) ?></span>
            </label>
        <?php endforeach; ?>
    </div>

    <button type="submit" class="submit-btn">Submit Answer</button>
</form>
</main>
</div>

