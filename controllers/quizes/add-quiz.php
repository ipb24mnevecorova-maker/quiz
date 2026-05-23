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

// Iegūst visus esošos tematiskos
$topics = $db->query("SELECT * FROM topics ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);

$step = $_GET['step'] ?? 'topic';
$message = '';
$error = '';

// Apstrādā formu iesniegšanu
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
  
    if (isset($_POST['add_topic'])) {
        $topic_name = trim($_POST['topic_name'] ?? '');
        
        if (empty($topic_name)) {
            $error = "Thematic title is required";
        } else {
            // Pārbauda vai temats jau eksistē
            $existing = $db->query("SELECT id FROM topics WHERE name = :name", ['name' => $topic_name])->fetch();
            
            if ($existing) {
                $error = "Such a topic already exists!";
            } else {
                $db->query("INSERT INTO topics (name) VALUES (:name)", ['name' => $topic_name]);
                $_SESSION['success'] = "Topic '{$topic_name}' added successfully!";
                header('Location: /add-quiz?step=questions&topic_id=' . $db->query("SELECT LAST_INSERT_ID() as id")->fetch()['id']);
                exit();
            }
        }
    }
    
    
    if (isset($_POST['save_quiz'])) {
        $topic_id = (int)$_POST['topic_id'];
        $questions_data = $_POST['questions'] ?? [];
        
        if (empty($questions_data)) {
            $error = "No questions added";
        } else {
            $saved_count = 0;
            
            foreach ($questions_data as $q_data) {
                $question_text = trim($q_data['text'] ?? '');
                $answers = $q_data['answers'] ?? [];
                
                if (empty($question_text)) {
                    continue;
                }
                
                // Pārbauda vai ir vismaz viena pareizā atbilde
                $hasCorrectAnswer = false;
                $validAnswers = [];
                
                foreach ($answers as $answer_data) {
                    $answer_text = trim($answer_data['text'] ?? '');
                    $is_correct = isset($answer_data['is_correct']) ? 1 : 0;
                    
                    // Izlaiž tukšas atbildes
                    if (empty($answer_text)) {
                        continue;
                    }
                    
                    if ($is_correct) {
                        $hasCorrectAnswer = true;
                    }
                    
                    $validAnswers[] = [
                        'text' => $answer_text,
                        'is_correct' => $is_correct
                    ];
                }
                
                // Pārbauda vai ir vismaz viena atbilde un viena pareizā
                if (empty($validAnswers)) {
                    $error = "Each question must have at least one answer!";
                    break;
                }
                
                if (!$hasCorrectAnswer) {
                    $error = "Each question must have at least one correct answer marked!";
                    break;
                }
                
                // Ievieto jautājumu
                $db->query(
                    "INSERT INTO questions (topic_id, text) VALUES (:topic_id, :text)",
                    ['topic_id' => $topic_id, 'text' => $question_text]
                );
                
                $question_id = $db->query("SELECT LAST_INSERT_ID() as id")->fetch()['id'];
                
                // Ievieto atbildes (tikai validās)
                foreach ($validAnswers as $answer) {
                    $db->query(
                        "INSERT INTO answers (question_id, text, is_correct) VALUES (:question_id, :text, :is_correct)",
                        ['question_id' => $question_id, 'text' => $answer['text'], 'is_correct' => $answer['is_correct']]
                    );
                }
                
                $saved_count++;
            }
            
            if ($saved_count > 0 && empty($error)) {
                $_SESSION['success'] = "Added successfully {$saved_count} questions!";
                header('Location: /add-quiz');
                exit();
            }
        }
    }
   
    if (isset($_POST['select_topic'])) {
        $topic_id = (int)$_POST['topic_id_select'];
        
        if ($topic_id > 0) {
            header('Location: /add-quiz?step=questions&topic_id=' . $topic_id);
            exit();
        } else {
            $error = "Please select a topic";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Quiz - Quiz</title>
    <link rel="stylesheet" href="/css/admin.css">
    <link rel="stylesheet" href="/css/add-quiz.css">
    <link rel="stylesheet" href="/css/sidebar.css">
</head>
<body>

    

    <div class="container">
        <div class="admin-header">
            <h1>Add new quiz</h1>
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
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($step == 'topic'): ?>
            
            <div class="step-container">
                
                <div class="two-columns">
                    <!-- Izvēlēties esošu tematu -->
                    <div class="card">
                        <h3>Choose an existing topic</h3>
                        <form method="POST" action="">
                            <div class="form-group">
                                <label for="topic_id_select">Topic:</label>
                                <select name="topic_id_select" id="topic_id_select" required>
                                    <option value="">-- Choose a topic --</option>
                                    <?php foreach ($topics as $topic): ?>
                                        <option value="<?php echo $topic['id']; ?>">
                                            <?php echo htmlspecialchars($topic['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit" name="select_topic" class="btn btn-primary">Continue with this topic</button>
                        </form>
                    </div>
                    
                    <!-- Pievienot jaunu tematu -->
                    <div class="card">
                        <h3>Add a new topic</h3>
                        <form method="POST" action="">
                            <div class="form-group">
                                <label for="topic_name">Name of new topic:</label>
                                <input type="text" name="topic_name" id="topic_name" placeholder="Topic" required>
                            </div>
                            <button type="submit" name="add_topic" class="btn btn-success">Add topic</button>
                        </form>
                    </div>
                </div>
            </div>
            
        <?php elseif ($step == 'questions'): ?>
            
            <?php
            $topic_id = (int)($_GET['topic_id'] ?? 0);
            $topic_info = $db->query("SELECT * FROM topics WHERE id = :id", ['id' => $topic_id])->fetch();
            
            if (!$topic_info):
            ?>
                <div class="alert alert-error">Topic not found!</div>
                <a href="/add-quiz" class="btn btn-primary">Back</a>
            <?php else: ?>
                <div class="step-container">
                    <h2>Add questions to the topic: <strong><?php echo htmlspecialchars($topic_info['name']); ?></strong></h2>
                    
                    <form method="POST" action="" id="quizForm">
                        <input type="hidden" name="topic_id" value="<?php echo $topic_id; ?>">
                        <input type="hidden" name="save_quiz" value="1">
                        
                        <div id="questions-container">
                            <!-- Jautājumi tiks pievienoti šeit izmantojot JavaScript -->
                        </div>
                        
                        <button type="button" class="btn btn-secondary" id="addQuestionBtn">+ Add a question</button>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">Save all questions</button>
                            <a href="/add-quiz" class="btn btn-secondary">Start over</a>
                        </div>
                    </form>
                </div>
            <?php endif; ?>
            
        <?php endif; ?>
    </div>
    
    <script src="/js/sidebar.js"></script>
    <script>
        let questionCount = 0;
        
        function addQuestion() {
            questionCount++;
            const container = document.getElementById('questions-container');
            const questionDiv = document.createElement('div');
            questionDiv.className = 'question-card';
            questionDiv.setAttribute('data-question-id', questionCount);
            
            questionDiv.innerHTML = `
                <div class="question-header">
                    <h3>Question ${questionCount}</h3>
                    <button type="button" class="btn-remove" onclick="removeQuestion(this)">❌ Delete</button>
                </div>
                
                <div class="form-group">
                    <label>Question text:</label>
                    <input type="text" name="questions[${questionCount}][text]" class="question-text" placeholder="Enter a question..." required>
                </div>
                
                <div class="answers-section">
                    <label>Answers (mark one correct answer):</label>
                    <div class="answers-container" data-question="${questionCount}">
                        ${addAnswerFields(questionCount, 1)}
                        ${addAnswerFields(questionCount, 2)}
                        ${addAnswerFields(questionCount, 3)}
                        ${addAnswerFields(questionCount, 4)}
                    </div>
                    <button type="button" class="btn-small" onclick="addAnswer(${questionCount})">+ Add an answer</button>
                </div>
            `;
            
            container.appendChild(questionDiv);
        }
        
        function addAnswerFields(questionId, answerNum) {
            return `
                <div class="answer-row">
                    <input type="text" name="questions[${questionId}][answers][${answerNum}][text]" placeholder="Answer ${answerNum}" class="answer-input" required>
                    <label class="checkbox-label">
                        <input type="checkbox" name="questions[${questionId}][answers][${answerNum}][is_correct]" value="1"> Correct
                    </label>
                    <button type="button" class="btn-remove-answer" onclick="removeAnswer(this)">❌</button>
                </div>
            `;
        }
        
        function addAnswer(questionId) {
            const answersContainer = document.querySelector(`.answers-container[data-question="${questionId}"]`);
            const answerCount = answersContainer.children.length + 1;
            const newAnswer = document.createElement('div');
            newAnswer.className = 'answer-row';
            newAnswer.innerHTML = `
                <input type="text" name="questions[${questionId}][answers][${answerCount}][text]" placeholder="Answer ${answerCount}" class="answer-input" required>
                <label class="checkbox-label">
                    <input type="checkbox" name="questions[${questionId}][answers][${answerCount}][is_correct]" value="1"> Correct
                </label>
                <button type="button" class="btn-remove-answer" onclick="removeAnswer(this)">❌</button>
            `;
            answersContainer.appendChild(newAnswer);
        }
        
        function removeQuestion(btn) {
            const questionCard = btn.closest('.question-card');
            questionCard.remove();
            // Pārnumurē jautājumus
            renumberQuestions();
        }
        
        function removeAnswer(btn) {
            const answerRow = btn.closest('.answer-row');
            answerRow.remove();
        }
        
        function renumberQuestions() {
            const questions = document.querySelectorAll('.question-card');
            questions.forEach((question, index) => {
                const newNum = index + 1;
                const header = question.querySelector('.question-header h3');
                if (header) {
                    header.textContent = `Question ${newNum}`;
                }
                
                // Atjaunot name atribūtus
                const inputs = question.querySelectorAll('input[name^="questions["]');
                inputs.forEach(input => {
                    const name = input.getAttribute('name');
                    const newName = name.replace(/questions\[\d+\]/, `questions[${newNum}]`);
                    input.setAttribute('name', newName);
                });
                
                // Atjaunot data-question atribūtu
                const answersContainer = question.querySelector('.answers-container');
                if (answersContainer) {
                    answersContainer.setAttribute('data-question', newNum);
                }
            });
            questionCount = questions.length;
        }
        
        // Pievienot pirmo jautājumu automātiski
        document.addEventListener('DOMContentLoaded', function() {
            addQuestion();
        });
        
        document.getElementById('addQuestionBtn').addEventListener('click', addQuestion);
        
        // Pārbauda vai katrai atbildei ir vismaz viena pareizā atbilde UN vai nav tukšu atbilžu
        document.getElementById('quizForm').addEventListener('submit', function(e) {
            const questions = document.querySelectorAll('.question-card');
            let hasError = false;
            let errorMessage = '';
            
            questions.forEach((question, index) => {
                const answerRows = question.querySelectorAll('.answer-row');
                let hasCorrect = false;
                let hasEmptyAnswer = false;
                let answerCount = 0;
                
                answerRows.forEach(row => {
                    const answerInput = row.querySelector('.answer-input');
                    const answerText = answerInput.value.trim();
                    const checkbox = row.querySelector('input[type="checkbox"]');
                    
                    // Pārbauda vai atbilde nav tukša
                    if (answerText === '') {
                        hasEmptyAnswer = true;
                        errorMessage = `Question has an empty answer! Please fill in all answers.`;
                    }
                    
                    if (answerText !== '') {
                        answerCount++;
                    }
                    
                    if (checkbox && checkbox.checked) {
                        hasCorrect = true;
                    }
                });
                
                if (hasEmptyAnswer) {
                    hasError = true;
                    alert(errorMessage);
                    return;
                }
                
                if (answerCount === 0) {
                    hasError = true;
                    alert(`Question ${index + 1} must have at least one answer!`);
                    return;
                }
                
                if (!hasCorrect) {
                    hasError = true;
                    alert(`Question ${index + 1} has no correct answer marked!`);
                    return;
                }
            });
            
            if (hasError) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>