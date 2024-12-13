<?php
$AssignmentColumnHeight = "60px";
$NameGradeColumnHeight = "45px";

$subject_section_id = $_GET['subject_section_id'];

$db = new Database();
$pdo = $db->getConnection();

// Fetch students
$studentsQuery = "SELECT u.user_id, u.profile_pic, CONCAT(u.last_name, ', ', u.middle_name, ' ', u.first_name) AS student_name
                  FROM student_subject_section sss
                  JOIN users u ON sss.user_id = u.user_id
                  WHERE sss.subject_section_id = ?";
$stmt = $pdo->prepare($studentsQuery);
$stmt->execute([$subject_section_id]);
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch students
$studentsQuery2 = "SELECT u.user_id, CONCAT(u.last_name, ', ', u.middle_name, ' ', u.first_name) AS student_name
                  FROM student_subject_section sss
                  JOIN users u ON sss.user_id = u.user_id
                  WHERE sss.subject_section_id = ?";
$stmt = $pdo->prepare($studentsQuery2);
$stmt->execute([$subject_section_id]);
$students2 = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch modules and their contents (assignments and quizzes)
$contentsQuery = "SELECT c.content_id, c.content_title, c.content_type, c.max_score, c.start_date, c.due_date
                  FROM modules m
                  JOIN contents c ON m.module_id = c.module_id
                  WHERE m.subject_section_id = ? AND c.content_type IN ('assignment', 'quiz')";
$stmt = $pdo->prepare($contentsQuery);
$stmt->execute([$subject_section_id]);
$contents = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch existing submissions
$submissionsQuery = "
    SELECT ss.*
    FROM student_submissions ss
    JOIN (
        SELECT 
            student_id, 
            content_id, 
            MAX(attempt_number) AS max_attempt_number
        FROM student_submissions
        WHERE content_id IN (
            SELECT c.content_id
            FROM modules m
            JOIN contents c ON m.module_id = c.module_id
            WHERE m.subject_section_id = ?
        )
        GROUP BY student_id, content_id
    ) AS latest_attempts
    ON ss.student_id = latest_attempts.student_id
    AND ss.content_id = latest_attempts.content_id
    AND ss.attempt_number = latest_attempts.max_attempt_number
    ORDER BY ss.attempt_number DESC";

$stmt = $pdo->prepare($submissionsQuery);
$stmt->execute([$subject_section_id]);
$submissions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Organize submissions for easy lookup
$submissionData = [];
foreach ($submissions as $submission) {
    $submissionData[$submission['student_id']][$submission['content_id']] = $submission;
}

$_SESSION['exportGradebookData'] = [
    "subject_section" => [
        "id" => $_GET['subject_section_id'],
        "subject_name" => $SUBJECT_INFO['data']['subject_name'],
    ],
    "gradebook_data" => [
        "contents" => $contents,
        "students" => $students2,
    ],
];
?>
<section class="container-fluid">
    <div class="d-flex justify-content-between align-items-center">
        <p class="fs-4 my-2">Gradebook</p>
        <div>
            <form method="POST">
                <input type="hidden" name="action" value="export_Gradebook">
                <button type="submit" class="btn btn-sm btn-secondary d-flex justify-content-center align-items-center gap-3">
                    <i class="bi bi-upload"></i>
                    Export
                </button>
            </form>

        </div>
    </div>
    <section id="gradebook-container" class="container-fluid row m-0 p-0 border">
        <div id="students_panel" class="col-3 p-0">
            <div class="p-2 border border-black-subtle border-bottom-0 d-flex justify-content-center align-items-center" style="height: <?php echo $AssignmentColumnHeight ?>;">Assignments</div>
            <div class="px-2 text-end border border-black-subtle border-bottom-0">Start</div>
            <div class="px-2 text-end border border-black-subtle border-bottom-0">Due</div>
            <div class="px-2 text-start border border-black-subtle">Students</div>
            <!-- Enrolled Students row -->
            <?php foreach ($students as $student): ?>
                <div class="p-2 border border-black-subtle border-bottom-0 d-flex justify-content-start align-items-center gap-2" style="height: <?php echo $NameGradeColumnHeight ?>;">
                    <img src="<?php echo convertImageBlobToSrc($student['profile_pic']) ?>" alt="student_profile" width="29" height="30" class="rounded-circle fit-content-cover">
                    <a href="#" class="text-truncate"><?php echo sanitizeInput($student['student_name']) ?></a>
                </div>
            <?php endforeach; ?>
        </div>
        <div id="contents_panel" class="col row m-0 p-0 overflow-x-auto flex-nowrap">
            <?php foreach ($contents as $content): ?>
                <div id="content_col" class="col-2 p-0">
                    <div id="content_name" class="p-2 fw-semibold text-center border border-black-subtle border-bottom-0 d-flex justify-content-center align-items-center" style="height: <?php echo $AssignmentColumnHeight ?>;">
                        <a href="#" class="text-success">
                            <?php echo sanitizeInput($content['content_title']) ?>
                        </a>
                    </div>
                    <div id="content_start-date" class="px-2 text-center border border-black-subtle border-bottom-0">
                        <?php echo convertProperDate(sanitizeInput($content['start_date']), 'M j') ?>
                    </div>
                    <div id="content_due-date" class="px-2 text-center border border-black-subtle border-bottom-0">
                        <?php echo convertProperDate(sanitizeInput($content['due_date']), 'M j') ?>
                    </div>
                    <div id="content_max-score" class="px-2 text-center border border-black-subtle">
                        <?php echo sanitizeInput($content['max_score']) ?>
                    </div>
                    <!-- Enrolled Students row -->
                    <?php foreach ($students as $student): ?>
                        <div
                            contenteditable
                            data-student-id="<?= $student['user_id'] ?>"
                            data-content-id="<?= $content['content_id'] ?>"
                            data-max-score="<?= $content['max_score'] ?>"
                            class="p-2 border border-black-subtle border-bottom-0 d-flex justify-content-center align-items-center gap-2"
                            style="height: <?php echo $NameGradeColumnHeight ?>;">
                            <?php echo $submissionData[$student['user_id']][$content['content_id']]['score'] ?? '' ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</section>
<script>
    $(document).ready(function() {
        // Store original value when focusing on the editable field
        $('#gradebook-container').on('focus', '[contenteditable]', function() {
            // Store the original value when the user starts editing
            $(this).data('original-value', $(this).text().trim());
        });

        // Handle blur event (when the user clicks away or presses enter)
        $('#gradebook-container').on('blur', '[contenteditable]', function() {
            let studentId = $(this).data('student-id');
            let contentId = $(this).data('content-id');
            let score = $(this).text().trim();
            let originalValue = $(this).data('original-value'); // Get the original value
            let maxScore = $(this).data('max-score'); // Get the max score for validation

            // Check if the score has changed
            if (score === originalValue) {
                // If the value has not changed, no need to update
                return;
            }

            // Check if the score is valid
            if (isNaN(score) || score === '' || parseFloat(score) < 0 || parseFloat(score) > parseFloat(maxScore)) {
                alert('Please enter a valid numeric score between 0 and ' + maxScore + '.');
                $(this).text(originalValue); // Restore original value if invalid
                return; // Exit the function without updating the server
            }

            // Send the updated score to the server
            $.ajax({
                url: '<?php echo BASE_PATH_LINK . 'src/models/Gradebook_SaveScore.php' ?>',
                type: 'POST',
                data: {
                    action: 'save_score',
                    student_id: studentId,
                    content_id: contentId,
                    score: score,
                    sender_id: <?php echo $_SESSION['user_id'] ?>,
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        makeToast('success', response.message);
                    } else {
                        makeToast('error', response.message);
                    }
                },
                error: function(xhr, status, error) {
                    makeToast('error', error);
                    console.error('Update failed: ', error);
                    console.error('Update failed 2: ', xhr.responseText);
                    $(this).text(originalValue); // Restore original value if there was an error
                }
            });

        });
    });
</script>