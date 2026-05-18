<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Please login first";
    header('Location: /');
    exit();
}

require_once __DIR__ . '/../../Database.php';
$config = require __DIR__ . '/../../config.php';
$db = new Database($config['database']);

// Get user's high scores with topic names
$highScores = $db->query(
    "SELECT hs.*, t.name as topic_name 
     FROM high_scores hs 
     JOIN topics t ON hs.topic_id = t.id 
     WHERE hs.user_id = :user_id 
     ORDER BY hs.percentage DESC",
    ['user_id' => $_SESSION['user_id']]
)->fetchAll(PDO::FETCH_ASSOC);

// Get overall statistics
$stats = $db->query(
    "SELECT 
        COUNT(*) as total_quizzes,
        SUM(score) as total_points,
        SUM(total_questions) as total_questions,
        AVG(percentage) as avg_percentage
     FROM high_scores 
     WHERE user_id = :user_id",
    ['user_id' => $_SESSION['user_id']]
)->fetch(PDO::FETCH_ASSOC);

// Get best overall score
$bestScore = $db->query(
    "SELECT hs.*, t.name as topic_name 
     FROM high_scores hs 
     JOIN topics t ON hs.topic_id = t.id 
     WHERE hs.user_id = :user_id 
     ORDER BY hs.percentage DESC 
     LIMIT 1",
    ['user_id' => $_SESSION['user_id']]
)->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>High Scores - Quiz</title>
    <link rel="stylesheet" href="/css/highscores.css">
    <link rel="stylesheet" href="/css/sidebar.css">
</head>
<body>

    

    <div class="container">
        <div class="highscores-header">
            <h1>My High Scores🏆</h1>
            <div class="header-actions">
                <a href="/" class="btn-home">⬅ Back to Home</a>
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

        <!-- Statistics Cards -->
        <div class="stats-cards">
            <div class="stat-card">
                <div class="stat-icon">📊</div>
                <div class="stat-info">
                    <h3><?php echo $stats['total_quizzes'] ?? 0; ?></h3>
                    <p>Quizzes Taken</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">🎯</div>
                <div class="stat-info">
                    <h3><?php echo round($stats['avg_percentage'] ?? 0); ?>%</h3>
                    <p>Average Score</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">⭐</div>
                <div class="stat-info">
                    <h3><?php echo $stats['total_points'] ?? 0; ?>/<?php echo $stats['total_questions'] ?? 0; ?></h3>
                    <p>Total Points</p>
                </div>
            </div>
        </div>

        <!-- Best Score Highlight -->
        <?php if ($bestScore): ?>
            <div class="best-score-card">
                <div class="best-icon">🏆</div>
                <div class="best-info">
                    <h2>Best Performance</h2>
                    <p class="best-topic"><?php echo htmlspecialchars($bestScore['topic_name']); ?></p>
                    <p class="best-score"><?php echo $bestScore['score']; ?>/<?php echo $bestScore['total_questions']; ?> 
                        (<?php echo round($bestScore['percentage']); ?>%)</p>
                </div>
            </div>
        <?php endif; ?>

        <!-- High Scores Table -->
        <div class="scores-table-container">
            <h2>Your High Scores by Topic</h2>
            
            <?php if (empty($highScores)): ?>
                <div class="empty-state">
                    <div class="empty-icon">📝</div>
                    <p>You haven't completed any quizzes yet!</p>
                    <a href="/" class="btn-start">Start Your First Quiz</a>
                </div>
            <?php else: ?>
                <table class="scores-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Topic</th>
                            <th>Score</th>
                            <th>Percentage</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($highScores as $index => $score): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td class="topic-name"><?php echo htmlspecialchars($score['topic_name']); ?></td>
                                <td class="score-value"><?php echo $score['score']; ?>/<?php echo $score['total_questions']; ?></td>
                                <td>
                                    <div class="percentage-bar">
                                        <div class="percentage-fill" style="width: <?php echo $score['percentage']; ?>%">
                                            <span><?php echo round($score['percentage']); ?>%</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="date"><?php echo date('M d, Y', strtotime($score['created_at'])); ?></td>
                                <td>
                                    <a href="/test?id=<?php echo $score['topic_id']; ?>" class="btn-retake">Retake</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

    <script src="/js/sidebar.js"></script>
</body>
</html>