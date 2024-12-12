<?php
ob_start();

$content_id = isset($_GET['content_id']) ? $_GET['content_id'] : null;
$user_id = $_SESSION['user_id'];


$stmt = $db->prepare("SELECT * FROM contents WHERE content_id = ? AND content_type ='quiz'");
$stmt->execute([$content_id]);
$quiz = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch quiz questions
$stmt = $db->prepare("SELECT * FROM quiz_questions WHERE content_id = ?");
$stmt->execute([$content_id]);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
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

// If a form is submitted and the quiz is not locked, process the responses
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] === 'submitQuiz' && !$quiz_locked) {

    $responses = $_POST;

    $total_score = 0;

    // Insert into student_submissions and get the submission_id
    $stmt = $db->prepare("INSERT INTO student_submissions (content_id, student_id, score, submission_date, status, attempt_number, graded_date)
                           VALUES (?, ?, ?, NOW(), ?, ?, NOW())");
    $stmt->execute([$content_id, $user_id, $total_score, 'graded', $attempt_count + 1]);
    $submission_id = $db->lastInsertId(); // Get the submission_id of the current attempt

    // Process each question
    foreach ($questions as $question) {
        $quiz_question_id = $question['quiz_question_id'];
        $response_key = "quiz_question_$quiz_question_id";

        // Skip if no response is provided for this question
        if (!isset($responses[$response_key])) {
            continue;
        }

        $quiz_question_option_id = null;
        $response_text = null;
        $is_correct = 0;

        // For MCQ and TRUE_FALSE questions
        if ($question['question_type'] === 'MCQ' || $question['question_type'] === 'TRUE_FALSE') {
            $quiz_question_option_id = $responses[$response_key]; // Get the selected option ID

            // Check if the selected option is correct
            $stmt = $db->prepare("SELECT is_correct FROM quiz_question_options WHERE quiz_question_id = ? AND quiz_question_option_id = ?");
            $stmt->execute([$quiz_question_id, $quiz_question_option_id]);
            $is_correct = $stmt->fetchColumn(); // This will be 1 if correct, 0 if incorrect
        }
        // For FILL_IN_THE_BLANKS questions
        elseif ($question['question_type'] === 'FILL_IN_THE_BLANKS') {
            $response_text = $responses[$response_key]; // Get the user's response text

            // Fetch the correct answer(s) from the quiz_question_options table
            $stmt = $db->prepare("SELECT option_text FROM quiz_question_options WHERE quiz_question_id = ? AND is_correct = 1");
            $stmt->execute([$quiz_question_id]);
            $correct_answers = $stmt->fetchAll(PDO::FETCH_COLUMN); // Fetch all correct options as an array

            // Match the response_text with any correct answer (case-insensitive comparison)
            foreach ($correct_answers as $correct_answer) {
                if (strtolower(trim($response_text)) === strtolower(trim($correct_answer))) {
                    $is_correct = 1;
                    break;
                }
            }
        }

        // Insert into quiz_responses table with the current submission_id
        $stmt = $db->prepare("INSERT INTO quiz_responses (content_id, user_id, quiz_question_id, quiz_question_option_id, response_text, is_correct, submission_id)
                               VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $content_id,
            $user_id,
            $quiz_question_id,
            $quiz_question_option_id,
            $response_text,
            $is_correct,
            $submission_id // Use the current submission_id
        ]);

        // Add score if the answer is correct
        if ($is_correct) {
            $total_score += $question['question_points'];
        }
    }

    // Update total_score in student_submissions after all responses
    $stmt = $db->prepare("UPDATE student_submissions SET score = ? WHERE submission_id = ?");
    $stmt->execute([$total_score, $submission_id]);


    ob_end_flush();
    exit();
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
            <form method="POST"
                action="<?php echo updateUrlParams(['subject_section_id' => $_GET['subject_section_id'], 'module_id' => $_GET['module_id'], 'content_id' => $_GET['content_id']]); ?>">
                <button type="submit" class="btn btn-success mt-3" id="submitButton">Submit Quiz</button>
                <input type="hidden" name="action" value="submitQuiz">
            </form>


        </form>
    </div>


    <div class="question-navigation">
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