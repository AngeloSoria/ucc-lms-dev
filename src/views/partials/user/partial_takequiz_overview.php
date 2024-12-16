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


            <div class="mt-3 d-flex gap-2 justify-content-start align-items-center ">
                <div class="navigation-buttons" id="questions-navigation" class="d-flex gap-2 justify-content-start align-items-center mt-2">
                    <button type="button" id="prevButton" class="btn btn-sm btn-secondary">Previous</button>
                    <button type="button" id="nextButton" class="btn btn-sm btn-primary">Next</button>
                </div>

                <form method="POST">
                    <button type="submit" class="btn btn-sm btn-success" id="submitButton">Submit Quiz</button>
                    <input type="hidden" name="action" value="submitQuiz">
                    <input type="hidden" name="attemptCount" value="<?php echo $attempt_count ?>">
                </form>
            </div>

        </form>
    </div>
</div>