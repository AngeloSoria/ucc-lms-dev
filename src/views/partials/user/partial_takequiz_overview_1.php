<?php
ob_start();
require_once(__DIR__ . '../../../../config/PathsHandler.php');
require_once(FILE_PATHS['DATABASE']);
$database = new Database();
$db = $database->getConnection(); // Establish the database connection

$content_id = isset($_GET['content_id']) ? $_GET['content_id'] : null;
$user_id = $_SESSION['user_id'];

$stmt = $db->prepare("SELECT * FROM contents WHERE content_id = ? AND content_type ='quiz'");
$stmt->execute([$content_id]);
$quiz = $stmt->fetch(PDO::FETCH_ASSOC);

$questions = $quizController->fetchQuestions($content_id);
$total_questions = count($questions);

// Check if the user has reached the maximum number of max_attempts
$stmt = $db->prepare("SELECT COUNT(*) FROM student_submissions WHERE content_id = ? AND student_id = ?");
$stmt->execute([$content_id, $user_id]);
$attempt_count = $stmt->fetchColumn();

if (($quiz['max_attempts'] !== null && $attempt_count >= $quiz['max_attempts']) || ($quiz['max_attempts'] === null && $attempt_count > 0)) {
    $quiz_locked = true; // User has reached max max_attempts or quiz is locked
} else {
    $quiz_locked = false;
}



?>

<style>
    .question-navigation {
        position: fixed;
        right: 20px;
        top: 100px;
        width: 200px;
        background-color: #f8f9fa;
        border: 1px solid #ddd;
        padding: 15px;
        border-radius: 5px;
    }

    .question-navigation a {
        display: block;
        margin-bottom: 10px;
        text-decoration: none;
        padding: 5px;
        color: #007bff;
        text-align: center;
        border: 1px solid #ddd;
        border-radius: 3px;
    }

    .question-navigation a.active {
        background-color: #007bff;
        color: white;
    }

    .navigation-buttons {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
    }

    .navigation-buttons button {
        width: 48%;
    }

    #submitButton {
        width: 100%;
    }

    #preview-section {
        display: none;
    }
</style>

<div class="container mt-5">
    <h2>Take Quiz: <?= htmlspecialchars($quiz['content_title']) ?></h2>
    <div id="question-container">
        <form method="POST" id="quizForm">

            <div id="questions-section">
                <?php foreach ($questions as $index => $question): ?>
                    <div class="question" data-question-index="<?= $index ?>"
                        style="display: <?= $index === 0 ? 'block' : 'none'; ?>;">
                        <h5>Question <?= $index + 1 ?> (<?= $question['question_points'] ?> points)</h5>
                        <p><?= htmlspecialchars($question['question_text']) ?></p>

                        <?php if ($question['question_type'] === 'MCQ' || $question['question_type'] === 'TRUE_FALSE'): ?>
                            <?php
                            $stmt = $db->prepare("SELECT * FROM quiz_question_options WHERE quiz_question_id = ?");
                            $stmt->execute([$question['quiz_question_id']]);
                            $options = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            ?>
                            <?php foreach ($options as $option): ?>
                                <div class="form-check">
                                    <input class="form-check-input question-response" type="radio"
                                        name="quiz_question_<?= $question['quiz_question_id'] ?>"
                                        value="<?= $option['quiz_question_option_id'] ?>"
                                        data-question-id="<?= $question['quiz_question_id'] ?>">
                                    <label class="form-check-label"><?= htmlspecialchars($option['option_text']) ?></label>
                                </div>
                            <?php endforeach; ?>
                        <?php elseif (strtoupper($question['question_type']) === 'FILL_IN_THE_BLANKS'): ?>
                            <input type="text" class="form-control question-response"
                                name="quiz_question_<?= $question['quiz_question_id'] ?>" placeholder="Your answer here"
                                data-question-id="<?= $question['quiz_question_id'] ?>">
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Preview Section -->
            <div id="preview-section">
                <h5>Preview Your Answers</h5>
                <div id="preview-content">
                    <?php foreach ($questions as $index => $question): ?>
                        <div class="preview-question">
                            <h6>Question <?= $index + 1 ?> (<?= $question['question_points'] ?> points)</h6>
                            <p><?= htmlspecialchars($question['question_text']) ?></p>
                            <p><strong>Your Answer:</strong> <span
                                    id="preview-answer-<?= $question['quiz_question_id'] ?>">Not Answered</span></p>
                        </div>
                        <hr>
                    <?php endforeach; ?>
                </div>
            </div>



            <div class="navigation-buttons" id="questions-navigation">
                <button type="button" id="prevButton" class="btn btn-secondary">Previous</button>
                <button type="button" id="nextButton" class="btn btn-primary">Next</button>
            </div>
            <form method="POST">
                <button type="submit" class="btn btn-success mt-3" id="submitButton">Submit Quiz</button>
                <input type="hidden" name="action" value="submitQuiz">
                <input type="hidden" name="attemptCount" value="<?php echo $attempt_count ?>">
            </form>


        </form>
    </div>


    <div class="question-navigation" style="z-index: 9999999">
        <div class="mb-3">
            <a
                href="<?php echo updateUrlParams(['subject_section_id' => $_GET['subject_section_id'], 'module_id' => $_GET['module_id'], 'content_id' => $_GET['content_id']]) ?>">
                <button class="btn btn-sm btn-primary me-2">
                    Cancel
                </button>

            </a>
        </div>
        <h6>Questions</h6>
        <?php foreach ($questions as $index => $question): ?>
            <a href="#" class="question-link" data-question-index="<?= $index ?>">Question <?= $index + 1 ?>
                <span id="sidebar-answer-<?= $question['quiz_question_id'] ?>" class="text-muted">(Not Answered)</span>
            </a>
        <?php endforeach; ?>
        <a href="#" id="previewButton" class="btn btn-info mt-3">Preview</a>
    </div>

</div>

<script>
    document.getElementById('quizForm').addEventListener('submit', function() {

    });

    // JavaScript for question navigation
    const questions = document.querySelectorAll('.question');
    const prevButton = document.getElementById('prevButton');
    const nextButton = document.getElementById('nextButton');
    const submitButton = document.getElementById('submitButton');
    const questionLinks = document.querySelectorAll('.question-link');
    const previewSection = document.getElementById('preview-section');
    const questionsSection = document.getElementById('questions-section');
    const previewButton = document.getElementById('previewButton');
    let currentQuestionIndex = 0;

    function updatePreview() {
        document.querySelectorAll('.question-response').forEach(input => {
            const questionId = input.getAttribute('data-question-id');
            const answerPreview = document.getElementById(`preview-answer-${questionId}`);
            const sidebarPreview = document.getElementById(`sidebar-answer-${questionId}`);

            if (input.type === 'radio' && input.checked) {
                answerPreview.innerText = input.parentNode.textContent.trim();
                sidebarPreview.innerText = input.parentNode.textContent.trim();
            } else if (input.type === 'text') {
                answerPreview.innerText = input.value || 'Not Answered';
                sidebarPreview.innerText = input.value || 'Not Answered';
            }
        });
    }

    function showQuestion(index) {
        questionsSection.style.display = 'block';
        previewSection.style.display = 'none';
        questions[currentQuestionIndex].style.display = 'none';
        questions[index].style.display = 'block';
        currentQuestionIndex = index;
        prevButton.style.display = index === 0 ? 'none' : 'inline-block';
        nextButton.style.display = index === questions.length - 1 ? 'none' : 'inline-block';
        submitButton.style.display = 'none';
        previewButton.style.display = index === questions.length - 1 ? 'inline-block' : 'none';
        questionLinks.forEach(link => link.classList.remove('active'));
        questionLinks[index]?.classList.add('active');
    }

    prevButton.addEventListener('click', () => {
        if (currentQuestionIndex > 0) {
            showQuestion(currentQuestionIndex - 1);
        } else {
            previewSection.style.display = 'none';
            questionsSection.style.display = 'block';
            currentQuestionIndex = questions.length - 1;
            showQuestion(currentQuestionIndex);
        }
    });
    nextButton.addEventListener('click', () => {
        if (currentQuestionIndex < questions.length - 1) {
            showQuestion(currentQuestionIndex + 1);
        } else {
            nextButton.style.display = 'none';
            previewButton.click();
        }
    });

    previewButton.addEventListener('click', () => {
        updatePreview();
        questionsSection.style.display = 'none';
        previewSection.style.display = 'block';
        nextButton.style.display = 'none';
        prevButton.style.display = 'inline-block';
        submitButton.style.display = 'inline-block';
    });

    questionLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const index = parseInt(link.getAttribute('data-question-index'));
            showQuestion(index);
        });
    });

    // Update preview answers dynamically
    document.querySelectorAll('.question-response').forEach(input => {
        input.addEventListener('change', updatePreview);
    });

    // Initialize first question as active
    showQuestion(0);
</script>