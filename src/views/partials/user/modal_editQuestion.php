<?php
$a_db = new Database();
$db = $a_db->getConnection();
if (isset($quiz_question_id)) {
    // Fetch the question details from the database
    $stmt = $db->prepare("SELECT * FROM quiz_questions WHERE quiz_question_id = ?");
    $stmt->execute([$quiz_question_id]);
    $question = $stmt->fetch(PDO::FETCH_ASSOC);


    $question_text = $question['question_text'];
    $question_type = $question['question_type'];
    $choicesContext = [];
    $correct_answer_text = '';

    // Fetch choices based on question type
    if ($question_type === 'MCQ' || $question_type === 'TRUE_FALSE') {
        $stmt = $db->prepare("SELECT * FROM quiz_question_options WHERE quiz_question_id = ?");
        $stmt->execute([$quiz_question_id]);
        $choicesContext = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } elseif ($question_type === 'FILL_IN_THE_BLANKS') {
        $stmt = $db->prepare("SELECT option_text FROM quiz_question_options WHERE quiz_question_id = ? AND is_correct = 1");
        $stmt->execute([$quiz_question_id]);
        $correct_answer = $stmt->fetch(PDO::FETCH_ASSOC);
        $correct_answer_text = $correct_answer ? $correct_answer['option_text'] : '';
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            if (isset($_POST['action']) && $_POST['action'] === 'editQuestion') {
                // Ensure quiz_question_id is available
                $quiz_question_id = $_POST['quiz_question_id'] ?? null;
                if (!$quiz_question_id) {
                    throw new Exception("Question ID is required.");
                }

                // Fetch updated question
                $stmt = $db->prepare("SELECT * FROM quiz_questions WHERE quiz_question_id = ?");
                $stmt->execute([$quiz_question_id]);
                $question = $stmt->fetch(PDO::FETCH_ASSOC);
                if (!$question) {
                    throw new Exception("Question not found.");
                }

                $updated_question_text = $_POST['question_text'] ?? null;
                $updated_score = $_POST['question_points'] ?? null;

                if (empty($updated_question_text) || empty($updated_score)) {
                    throw new Exception("Question text and score are required.");
                }

                // Update the question
                $stmt = $db->prepare("UPDATE quiz_questions SET question_text = ?, question_points = ? WHERE quiz_question_id = ?");
                $stmt->execute([$updated_question_text, $updated_score, $quiz_question_id]);

                if ($question_type === 'MCQ' || $question_type === 'TRUE_FALSE') {
                    // Handle MCQ/True/False choices update
                    $updated_choices = $_POST['choices'] ?? [];
                    $correct_choice_id = $_POST['correct_choice'] ?? null;

                    if (empty($correct_choice_id)) {
                        throw new Exception("Correct choice must be selected.");
                    }

                    $existing_choice_ids = array_column($choicesContext, 'quiz_question_option_id');
                    $submitted_choice_ids = array_keys($updated_choices);

                    // Remove choices no longer submitted
                    $removed_choices = array_diff($existing_choice_ids, $submitted_choice_ids);
                    foreach ($removed_choices as $removed_choice_id) {
                        $stmt = $db->prepare("DELETE FROM quiz_question_options WHERE quiz_question_option_id = ?");
                        $stmt->execute([$removed_choice_id]);
                    }

                    // Insert or update choices
                    foreach ($updated_choices as $choice_id => $choice_text) {
                        $is_correct = ($choice_id == $correct_choice_id) ? 1 : 0;

                        if (in_array($choice_id, $existing_choice_ids)) {
                            // Update existing choice
                            $stmt = $db->prepare("UPDATE quiz_question_options SET option_text = ?, is_correct = ? WHERE quiz_question_option_id = ?");
                            $stmt->execute([$choice_text, $is_correct, $choice_id]);
                        } else {
                            // Insert new choice
                            $stmt = $db->prepare("INSERT INTO quiz_question_options (quiz_question_id, option_text, is_correct) VALUES (?, ?, ?)");
                            $stmt->execute([$quiz_question_id, $choice_text, $is_correct]);
                        }
                    }
                } elseif ($question_type === 'FILL_IN_THE_BLANKS') {
                    // Handle Fill in the Blank answer update
                    $updated_answer_text = $_POST['choices'][$quiz_question_id] ?? null;

                    if (empty($updated_answer_text)) {
                        throw new Exception("Answer text is required.");
                    }

                    // Update the correct answer
                    $stmt = $db->prepare("UPDATE quiz_question_options SET option_text = ? WHERE quiz_question_id = ? AND is_correct = 1");
                    $stmt->execute([$updated_answer_text, $quiz_question_id]);
                }
            }

            // After successful form processing, redirect to avoid re-submission
            exit();
        } catch (Exception $e) {
            // Handle any errors that occur during the process
            echo "Error: " . $e->getMessage();
            exit();
        }
    }
}
?>

<!-- Modal for Editing Question -->
<div class="modal fade" id="editQuestionModal" tabindex="-1" data-bs-focus="false"
    aria-labelledby="editQuestionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editQuestionModalLabel">Edit Question</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form method="POST" enctype="multipart/form-data" id="editQuestionForm">
                <!-- Hidden Fields -->
                <input type="hidden" name="quiz_question_id" value="<?= $quiz_question_id ?>">
                <input type="hidden" name="action" value="editQuestion">

                <div class="modal-body">
                    <!-- Question Text -->
                    <div class="mb-3">
                        <label for="editQuestionModal_questionText" class="form-label">Question</label>
                        <textarea id="editQuestionModal_questionText" name="question_text" class="form-control"
                            required><?= htmlspecialchars($question_text) ?></textarea>
                    </div>

                    <!-- Question Type and Points -->
                    <div class="row mb-3">
                        <div class="col">
                            <label for="editQuestionModal_questionType" class="form-label">Question Type</label>
                            <input id="editQuestionModal_questionType" type="text" class="form-control"
                                value="<?= htmlspecialchars($question_type) ?>" readonly>
                        </div>
                        <div class="col">
                            <label for="editQuestionModal_questionPoints" class="form-label">Points</label>
                            <input id="editQuestionModal_questionPoints" type="number" name="question_points"
                                class="form-control" value="<?= $question['question_points'] ?>" required>
                        </div>
                    </div>

                    <!-- Conditional Fields Based on Question Type -->
                    <?php if ($question_type === 'MCQ'): ?>
                        <!-- MCQ Choices -->
                        <div class="mb-3" id="editQuestionModal_choicesDiv">
                            <label for="editQuestionModal_choicesContainer" class="form-label">Choices (for MCQ)</label>
                            <div id="editQuestionModal_choicesContainer">
                                <?php foreach ($choicesContext as $choice): ?>
                                    <div class="input-group mb-2 choice-group"
                                        data-choice-id="<?= $choice['quiz_question_option_id'] ?>">
                                        <input type="radio" name="correct_choice"
                                            value="<?= $choice['quiz_question_option_id'] ?>"
                                            <?= $choice['is_correct'] ? 'checked' : '' ?> class="me-2">
                                        <input type="text" name="choices[<?= $choice['quiz_question_option_id'] ?>]"
                                            class="form-control" value="<?= htmlspecialchars($choice['option_text']) ?>" required>
                                        <button type="button" class="btn btn-danger remove-choice">Remove</button>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <button type="button" class="btn btn-primary" id="editQuestionModal_addChoice">Add Choice</button>
                        </div>
                    <?php elseif ($question_type === 'TRUE_FALSE'): ?>
                        <!-- True/False Choices -->
                        <div class="mb-3" id="editQuestionModal_choicesDiv">
                            <label for="editQuestionModal_choicesContainer" class="form-label">Choices (True/False)</label>
                            <div id="editQuestionModal_choicesContainer">
                                <div class="input-group mb-2 choice-group" data-choice-id="1">
                                    <input type="radio" name="correct_choice" value="1"
                                        <?= $choicesContext[0]['is_correct'] ? 'checked' : '' ?> class="me-2">
                                    <input type="text" name="choices[1]" class="form-control" value="True" readonly>
                                </div>
                                <div class="input-group mb-2 choice-group" data-choice-id="2">
                                    <input type="radio" name="correct_choice" value="2"
                                        <?= $choicesContext[1]['is_correct'] ? 'checked' : '' ?> class="me-2">
                                    <input type="text" name="choices[2]" class="form-control" value="False" readonly>
                                </div>
                            </div>
                        </div>
                    <?php elseif ($question_type === 'FILL_IN_THE_BLANKS'): ?>
                        <!-- Fill in the Blanks -->
                        <div class="mb-3">
                            <label for="editQuestionModal_correctAnswer" class="form-label">Answer (Fill in the Blank)</label>
                            <input id="editQuestionModal_correctAnswer" type="text"
                                name="choices[<?= $quiz_question_id ?>]" class="form-control"
                                value="<?= htmlspecialchars($correct_answer_text) ?>" required>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editButtons = document.querySelectorAll('.edit-question-btn');
        const modal = document.querySelector('#editQuestionModal');
        const modalQuestionText = modal.querySelector('#editQuestionModal_questionText');
        const modalQuestionPoints = modal.querySelector('#editQuestionModal_questionPoints');
        const modalQuestionType = modal.querySelector('#editQuestionModal_questionType');
        const choicesContainer = modal.querySelector('#editQuestionModal_choicesContainer');
        const modalCorrectAnswer = modal.querySelector('#editQuestionModal_correctAnswer');

        editButtons.forEach(button => {
            button.addEventListener('click', async function() {
                const questionId = this.getAttribute('data-id');

                // Clear previous content
                if (choicesContainer) choicesContainer.innerHTML = '';
                if (modalCorrectAnswer) modalCorrectAnswer.value = '';

                // Fetch question details dynamically
                try {
                    const response = await fetch(`/fetch-question.php?quiz_question_id=${questionId}`);
                    if (!response.ok) throw new Error('Failed to fetch question data');
                    const data = await response.json();

                    // Populate modal fields
                    modal.querySelector('[name="quiz_question_id"]').value = data.quiz_question_id;
                    modalQuestionText.value = data.question_text;
                    modalQuestionPoints.value = data.question_points;
                    modalQuestionType.value = data.question_type;

                    if (data.question_type === 'MCQ' || data.question_type === 'TRUE_FALSE') {
                        data.choices.forEach(choice => {
                            const choiceGroup = document.createElement('div');
                            choiceGroup.classList.add('input-group', 'mb-2', 'choice-group');
                            choiceGroup.setAttribute('data-choice-id', choice.quiz_question_option_id);

                            const correctRadioButton = document.createElement('input');
                            correctRadioButton.type = 'radio';
                            correctRadioButton.name = 'correct_choice';
                            correctRadioButton.value = choice.quiz_question_option_id;
                            correctRadioButton.checked = choice.is_correct;
                            correctRadioButton.classList.add('me-2');

                            const choiceTextInput = document.createElement('input');
                            choiceTextInput.type = 'text';
                            choiceTextInput.name = `choices[${choice.quiz_question_option_id}]`;
                            choiceTextInput.classList.add('form-control');
                            choiceTextInput.value = choice.option_text;

                            const removeButton = document.createElement('button');
                            removeButton.type = 'button';
                            removeButton.classList.add('btn', 'btn-danger', 'remove-choice');
                            removeButton.textContent = 'Remove';

                            choiceGroup.append(correctRadioButton, choiceTextInput, removeButton);
                            choicesContainer.appendChild(choiceGroup);

                            // Add remove functionality
                            removeButton.addEventListener('click', () => choiceGroup.remove());
                        });
                    } else if (data.question_type === 'FILL_IN_THE_BLANKS') {
                        modalCorrectAnswer.value = data.correct_answer_text;
                    }
                } catch (error) {
                    console.error('Error fetching question data:', error);
                    alert('An error occurred while loading question details.');
                }
            });
        });
    });
</script>