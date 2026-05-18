<?php
session_start();

// Pārbauda vai lietotājs ir administrators
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['error'] = "Jums nav piekļuves šai lapai";
    header('Location: /');
    exit();
}

require_once __DIR__ . '/../../Database.php';
$config = require __DIR__ . '/../../config.php';
$db = new Database($config['database']);

$message = '';
$error = '';

// Apstrādā dzēšanas pieprasījumus
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Dzēst tematu
    if (isset($_POST['delete_topic'])) {
        $topic_id = (int)$_POST['topic_id'];
        
        // Pārbauda vai temats eksistē
        $topic = $db->query("SELECT name FROM topics WHERE id = :id", ['id' => $topic_id])->fetch();
        
        if ($topic) {
            $db->query("DELETE FROM topics WHERE id = :id", ['id' => $topic_id]);
            $_SESSION['success'] = "Temats '{$topic['name']}' successfully deleted!";
        } else {
            $_SESSION['error'] = "Topic not found!";
        }
        
        header('Location: /manage-quiz');
        exit();
    }
    
    // Dzēst jautājumu
    if (isset($_POST['delete_question'])) {
        $question_id = (int)$_POST['question_id'];
        $topic_id = (int)$_POST['topic_id'];
        
        $question = $db->query("SELECT text FROM questions WHERE id = :id", ['id' => $question_id])->fetch();
        
        if ($question) {
            $db->query("DELETE FROM questions WHERE id = :id", ['id' => $question_id]);
            $_SESSION['success'] = "Jautājums veiksmīgi dzēsts!";
        } else {
            $_SESSION['error'] = "Jautājums nav atrasts!";
        }
        
        header('Location: /manage-quiz?topic_id=' . $topic_id);
        exit();
    }
}

// Iegūst visus tematus
$topics = $db->query("SELECT t.*, COUNT(q.id) as question_count 
                      FROM topics t 
                      LEFT JOIN questions q ON t.id = q.topic_id 
                      GROUP BY t.id 
                      ORDER BY t.name")->fetchAll(PDO::FETCH_ASSOC);

// Iegūst izvēlētā temata jautājumus
$selected_topic_id = (int)($_GET['topic_id'] ?? 0);
$selected_topic = null;
$questions = [];

if ($selected_topic_id > 0) {
    $selected_topic = $db->query("SELECT * FROM topics WHERE id = :id", ['id' => $selected_topic_id])->fetch();
    
    if ($selected_topic) {
        $questions = $db->query("SELECT q.*, COUNT(a.id) as answer_count 
                                 FROM questions q 
                                 LEFT JOIN answers a ON q.id = a.question_id 
                                 WHERE q.topic_id = :topic_id 
                                 GROUP BY q.id 
                                 ORDER BY q.id", 
                                 ['topic_id' => $selected_topic_id])->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage tests - Quiz</title>
    <link rel="stylesheet" href="/css/admin.css">
    <link rel="stylesheet" href="/css/manage-quiz.css">
    <link rel="stylesheet" href="/css/sidebar.css">
</head>
<body>

    

    <div class="container">
        <div class="admin-header">
            <h1>Manage Questions</h1>
            <div class="admin-nav">
                <a href="/" class="nav-link">⬅ Home</a>
                <a href="/logout" class="nav-link logout">Logout</a>
            </div>
        </div>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?php 
                echo $_SESSION['success']; 
                unset($_SESSION['success']);
                ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <?php 
                echo $_SESSION['error']; 
                unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>
        
        <!-- Tematu saraksts -->
        <div class="topics-section">
            <h2>All topics</h2>
            <div class="topics-grid">
                <?php foreach ($topics as $topic): ?>
                    <div class="topic-card <?php echo ($selected_topic_id == $topic['id']) ? 'active' : ''; ?>">
                        <div class="topic-info">
                            <h3><?php echo htmlspecialchars($topic['name']); ?></h3>
                            <p><?php echo $topic['question_count']; ?> Questions</p>
                        </div>
                        <div class="topic-actions">
                            <a href="/manage-quiz?topic_id=<?php echo $topic['id']; ?>" class="btn-view">View</a>
                            <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this topic and all its questions? This action cannot be undone!');">
                                <input type="hidden" name="topic_id" value="<?php echo $topic['id']; ?>">
                                <button type="submit" name="delete_topic" class="btn-delete-topic">Delete</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <!-- Izvēlētā temata jautājumi -->
        <?php if ($selected_topic): ?>
            <div class="questions-section">
                <div class="section-header">
                    <h2>Questions: <?php echo htmlspecialchars($selected_topic['name']); ?></h2>
                    <a href="/add-quiz?step=questions&topic_id=<?php echo $selected_topic_id; ?>" class="btn-add">+ Add Quiz</a>
                </div>
                
                <?php if (empty($questions)): ?>
                    <div class="empty-state">
                        <p>There are no questions added to this topic yet.</p>
                        <a href="/add-quiz?step=questions&topic_id=<?php echo $selected_topic_id; ?>" class="btn-primary">Add first quiz</a>
                    </div>
                <?php else: ?>
                    <div class="questions-list">
                        <?php foreach ($questions as $index => $question): ?>
                            <div class="question-item">
                                <div class="question-header">
                                    <span class="question-number"><?php echo $index + 1; ?>.</span>
                                    <span class="question-text"><?php echo htmlspecialchars($question['text']); ?></span>
                                    <span class="answer-count">(<?php echo $question['answer_count']; ?> answers)</span>
                                </div>
                                <div class="question-actions">
                                    <button type="button" class="btn-view-answers" onclick="toggleAnswers(<?php echo $question['id']; ?>)">Show answers</button>
                                    <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this question and all its answers?');">
                                        <input type="hidden" name="question_id" value="<?php echo $question['id']; ?>">
                                        <input type="hidden" name="topic_id" value="<?php echo $selected_topic_id; ?>">
                                        <button type="submit" name="delete_question" class="btn-delete-question">Delete</button>
                                    </form>
                                </div>
                                <div id="answers-<?php echo $question['id']; ?>" class="answers-list" style="display: none;">
                                    <?php
                                    $answers = $db->query("SELECT * FROM answers WHERE question_id = :id", ['id' => $question['id']])->fetchAll();
                                    ?>
                                    <h4>Atbildes:</h4>
                                    <ul>
                                        <?php foreach ($answers as $answer): ?>
                                            <li class="<?php echo $answer['is_correct'] ? 'correct-answer' : ''; ?>">
                                                <?php echo htmlspecialchars($answer['text']); ?>
                                                <?php if ($answer['is_correct']): ?>
                                                    <span class="correct-badge">✓ Correct</span>
                                                <?php endif; ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <script src="/js/sidebar.js"></script>
    <script>
        function toggleAnswers(questionId) {
            const answersDiv = document.getElementById('answers-' + questionId);
            if (answersDiv.style.display === 'none') {
                answersDiv.style.display = 'block';
            } else {
                answersDiv.style.display = 'none';
            }
        }
    </script>
</body>
</html>