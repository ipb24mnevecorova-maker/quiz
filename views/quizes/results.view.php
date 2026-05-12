<link rel="stylesheet" href="/css/result.css">
<form action="/test" method="GET">
    <input type="hidden" name="id" value="<?= $topicId ?>">
    <input type="hidden" name="step" value="<?= $nextStep ?>">
    <input type="hidden" name="score" value="<?= $score ?>">


</form>

<div class="result-container">
    <h1>Your Quiz Results</h1>
    
    <p class="result-text">
        You answered <strong><?= $score ?></strong> out of 
        <strong><?= $totalQuestions ?></strong> questions correctly!
    </p>

    <div class="button-group">
        <a href="/test?id=<?= $topicId ?>" class="btn retake-btn">Retake Quiz</a>
        <a href="/" class="btn select-btn">Choose Another Topic</a>
    </div>
</div>