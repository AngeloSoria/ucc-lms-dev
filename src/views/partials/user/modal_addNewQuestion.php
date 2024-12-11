<div class="modal fade" id="addNewQuestionModal" tabindex="-1" data-bs-focus="false"
    aria-labelledby="addNewQuestionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addNewQuestionModalLabel">Add Question</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form method="POST">
                <input type="hidden" name="action" value="addNewQuestion">
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="question_type" class="form-label">Question Type</label>
                            <select name="question_type" class="form-select" id="question_type" required>
                                <option value="MCQ" <?= isset($_POST['question_type']) && $_POST['question_type'] == 'MCQ' ? 'selected' : '' ?>>Multiple Choice</option>
                                <option value="TRUE_FALSE" <?= isset($_POST['question_type']) && $_POST['question_type'] == 'TRUE_FALSE' ? 'selected' : '' ?>>True/False</option>
                                <option value="FILL_IN_THE_BLANKS" <?= isset($_POST['question_type']) && $_POST['question_type'] == 'FILL_IN_THE_BLANKS' ? 'selected' : '' ?>>Fill in the Blank
                                </option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="score" class="form-label">Score</label>
                            <input type="number" name="question_points" class="form-control"
                                value="<?= isset($_POST['score']) ? htmlspecialchars($_POST['score']) : '' ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="input_question" class="form-label">Question: </label>
                        <textarea class="form-control" id="question_text" name="question_text"
                            style="height: 100px"></textarea>
                    </div>

                    <!-- For MCQ options -->
                    <div class="mb-3" id="mcq_choices_div" style="display: block;">
                        <label for="choices" class="form-label">Choices (for MCQ)</label>
                        <div id="choices_container">
                            <?php
                            $initialChoicesCount = 2;
                            for ($index = 0; $index < $initialChoicesCount; $index++): ?>
                                <div class="mb-2 d-flex align-items-center">
                                    <input type="radio" name="mcq_item[]" value="<?php echo $index ?>" class="ms-2">
                                    <input type="text" name="choices[]" class="form-control ms-2"
                                        placeholder="Enter choice">
                                </div>
                            <?php endfor; ?>
                        </div>
                        <button type="button" class="btn btn-primary" id="add_choice">Add Choice</button>
                    </div>

                    <!-- For True/False options -->
                    <div class="mb-3" id="true_false_choices_div" style="display: none;">
                        <p class="mt-2 mb-1">Correct Answer</p>
                        <fieldset id="tf_fieldset">
                            <div>
                                <input id="tf_1" type="radio" name="tf_choice" value="TRUE">
                                <label for="tf_1">TRUE</label>
                                <input id="tf_2" type="radio" name="tf_choice" value="FALSE">
                                <label for="tf_2">FALSE</label>
                            </div>
                        </fieldset>
                    </div>

                    <!-- For Fill in the Blank -->
                    <div class="mb-3" id="fib_answer_div" style="display: none;">
                        <label for="correct_answer" class="form-label">Correct Answer</label>
                        <input type="text" name="correct_answer" class="form-control">
                    </div>

                    <!-- Submit Button -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <input type="submit" value="Submit" class="btn btn-success">
                    </div>
                </div>
            </form>
            <script>
                document.getElementById('question_type').addEventListener('change', function () {
                    const questionType = this.value;

                    // Show the correct fields based on selected question type
                    document.getElementById('mcq_choices_div').style.display = questionType === 'MCQ' ? 'block' : 'none';
                    document.getElementById('true_false_choices_div').style.display = questionType === 'TRUE_FALSE' ? 'block' : 'none';
                    document.getElementById('fib_answer_div').style.display = questionType === 'FILL_IN_THE_BLANKS' ? 'block' : 'none';
                });

                // Initialize choice counter based on existing choices
                let choiceCounter = <?php echo $initialChoicesCount; ?>;

                document.getElementById('add_choice').addEventListener('click', function () {
                    const container = document.getElementById('choices_container');

                    // Create a new choice div with a unique index for name attributes
                    const newChoice = document.createElement('div');
                    newChoice.classList.add('mb-2', 'd-flex', 'align-items-center');  // Add Bootstrap flex classes

                    newChoice.innerHTML = `
        <input type="radio" name="mcq_item[]" class="ms-2" value="${choiceCounter}">
        <input type="text" name="choices[${choiceCounter}]" class="form-control ms-2" placeholder="Enter choice">`;

                    // Append the new choice to the container
                    container.appendChild(newChoice);

                    // Increment the choice counter for next addition
                    choiceCounter++;
                });

            </script>

        </div>
    </div>
</div>