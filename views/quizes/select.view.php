<link rel="stylesheet" href="/css/style.css">
<div class="quiz-container">
    <h1>Izvēlies testa tēmu</h1>
    
    <form action="/test" method="GET">
        <div class="dropdown-wrapper">
            <label for="topic-select">Tēma:</label>
            <select name="id" id="topic-select">
                <option value="" disabled selected>-- Lūdzu, izvēlies --</option>
                <?php foreach ($topics as $topic): ?>
                    <option value="<?= $topic['id'] ?>">
                        <?= htmlspecialchars($topic['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="submit-btn">Sākt testu</button>
    </form>
</div>
