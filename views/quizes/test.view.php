<link rel="stylesheet" href="/css/test.css">
<div class="quiz-container">
    <header>
    <h2><?= htmlspecialchars($topic['name']) ?> Quiz</h2>
    <p>Question <?= $currentStep + 1 ?> of <?= $totalQuestions ?></p>
    
    <div class="progress-bar">

        <div class="progress" style="width: <?= (($currentStep + 1) / $totalQuestions) * 100 ?>%;"></div>
    </div>
</header>

    <form action="/test" method="GET">

    <input type="hidden" name="id" value="<?= $topicId ?>">
    <input type="hidden" name="step" value="<?= $nextStep ?>">
    <input type="hidden" name="score" value="<?= $score ?>">

    <p><strong>Question:</strong> <?= htmlspecialchars($question['text']) ?></p>

    <?php foreach ($answers as $answer) : ?>
        <div class="answer-option">
            <input type="radio" name="answer" id="ans-<?= $answer['id'] ?>" value="<?= $answer['id'] ?>" required>
            <label for="ans-<?= $answer['id'] ?>"><?= htmlspecialchars($answer['text']) ?></label>
        </div>
    <?php endforeach; ?>

    <button type="submit" class="submit-btn">Submit Answer</button>
</form>
</div>

