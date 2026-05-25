<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Quiz - <?= htmlspecialchars($topic['name'] ?? 'Test') ?></title>
    <link rel="stylesheet" href="/css/test.css">
</head>
<body>
    <div class="quiz-container">
        <header>
            <h2><?= htmlspecialchars($topic['name'] ?? 'Quiz') ?></h2>
            <p>Question <?= ($currentStepIndex + 1) ?> of <?= $totalQuestions ?></p>
            
            <div class="progress-bar">
                <div class="progress" style="width: <?= (($currentStepIndex + 1) / $totalQuestions) * 100 ?>%;"></div>
            </div>
        </header>

        <form action="/test?id=<?= $topicId ?>" method="POST">
            <p><strong>Question:</strong> <?= htmlspecialchars($questionText) ?></p>

            <?php if (!empty($answers)): ?>
                <?php foreach ($answers as $answer) : ?>
                    <div class="answer-option">
                        <input type="radio" name="answer_id" id="ans-<?= $answer['id'] ?>" value="<?= $answer['id'] ?>" required>
                        <label for="ans-<?= $answer['id'] ?>"><?= htmlspecialchars($answer['text'] ?? 'No answer text') ?></label>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No answers found for this question.</p>
            <?php endif; ?>

            <button type="submit" class="submit-btn">Submit Answer</button>
        </form>
    </div>
</body>
</html>