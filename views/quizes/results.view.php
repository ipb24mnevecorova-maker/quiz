<link rel="stylesheet" href="/css/result.css">
<form action="/test" method="GET">
    <input type="hidden" name="id" value="<?= $topicId ?>">
    <input type="hidden" name="step" value="<?= $nextStep ?>">
    <input type="hidden" name="score" value="<?= $score ?>">


</form>

<div class="result-container">
    <h1>Your Quiz Results</h1>
    
    <p class="result-text">
        Jūs atbildējāt <strong><?= $score ?></strong> no 
        <strong><?= $totalQuestions ?></strong> jautājumiem pareizi!
    </p>

    <div class="button-group">
        <a href="/test?id=<?= $topicId ?>" class="btn retake-btn">Atkārtot Testu</a>
        <a href="/select" class="btn select-btn">Izvēlēties Citu Tēmu</a>
    </div>
</div>
