<?php

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
<div class="mt-sm-2 mt-md-0 widget-card p-3 shadow-sm rounded border" id="quiz-nav">
    <div class="question-navigation">
        <h6>Questions</h6>
        <div class="d-flex flex-column">
            <?php foreach ($questions as $index => $question): ?>
                <a href="#" class="question-link" data-question-index="<?= $index ?>">Question <?= $index + 1 ?>
                    <span id="sidebar-answer-<?= $question['quiz_question_id'] ?>" class="text-muted">(Not Answered)</span>
                </a>
            <?php endforeach; ?>
        </div>
        <div class="d-flex justify-content-end align-items-center gap-2 mt-3">
            <a href="#" id="previewButton" class="btn btn-sm btn-success">Preview Answers</a>
            <a
                class="btn btn-sm btn-danger"
                href="<?php echo updateUrlParams(['subject_section_id' => $_GET['subject_section_id'], 'module_id' => $_GET['module_id'], 'content_id' => $_GET['content_id']]) ?>">
                Cancel Quiz
            </a>
        </div>
    </div>
</div>

<script>
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