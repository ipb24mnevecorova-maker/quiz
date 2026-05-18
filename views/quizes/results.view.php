<link rel="stylesheet" href="/css/result.css">

<div class="result-container">
    <h1>Your Quiz Results</h1>
    
    <p class="result-text">
        You answered <strong><?= $finalScore ?></strong> out of 
        <strong><?= $totalQuestions ?></strong> questions correctly!
    </p>

    <div class="button-group">
        <a href="/test?id=<?= $topicId ?>" class="btn retake-btn">Retake Quiz</a>
        <a href="/" class="btn select-btn">Back to Home</a>
    </div>
</div>