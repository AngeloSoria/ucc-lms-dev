<?php
if (userHasPerms(['Student', 'Teacher'])) {
    $getSubmissionInfo = $moduleContentController->getSubmissionsByContent($_GET['content_id'], $_SESSION['user_id']);
}
?>
<div class="mt-sm-2 mt-md-0 widget-card p-3 shadow-sm rounded border" id="myTasks">
    <div class="d-flex justify-content-between align-items-center">
        <p class="fs-6 fw-semibold text-success m-0"><?php echo sanitizeInput("Score") ?></p>
    </div>
    <hr class="opacity-90 mx-0 my-2">
    <div>
        <!-- Your task list goes here -->
        <ul class="list-group list-group-flush">
            <?php if ($getSubmissionInfo['success']): ?>
                <?php if (!$getSubmissionInfo['data']): ?>
                    <li class="list-group-item px-0 d-flex justify-content-center align-items-center gap-2">
                        <p class="fs-7 fw-semibold opacity-75 text-center">
                            Nothing submitted yet
                        </p>
                    </li>
                <?php else: ?>
                    <?php
                    $maxScore = $module_contentInfo['data'][0]['max_score'];

                    // Filter only graded rows
                    $gradedSubmissions = array_filter($getSubmissionInfo['data'], function ($submission) {
                        return $submission['status'] === 'graded';
                    });

                    // Determine what to display
                    if (!empty($gradedSubmissions)) {
                        // Sort the graded submissions by their submission date or ID (assuming 'submission_date' exists)
                        usort($gradedSubmissions, function ($a, $b) {
                            return strtotime($b['submission_date']) - strtotime($a['submission_date']); // Newest first
                        });

                        // Get the score from the latest graded submission
                        $submissionScore = $gradedSubmissions[0]['score'];
                        $displayText = sprintf('%d / %d', $submissionScore, $maxScore);
                        $displayClass = 'fs-1 text-success fw-semibold';
                    } else {
                        // No graded submissions yet
                        $displayText = "Waiting to be graded";
                        $displayClass = 'fs-7 text-center text-secondary fw-semibold';
                    }
                    ?>
                    <li class="list-group-item px-0 d-flex justify-content-center align-items-center gap-2">
                        <p class="<?php echo $displayClass; ?>">
                            <?php echo $displayText; ?>
                        </p>
                    </li>
                <?php endif; ?>
            <?php else: ?>
                <!-- Handle error if submissions couldn't be retrieved -->
                <li class="list-group-item px-0 d-flex justify-content-center align-items-center gap-2">
                    <p class="fs-7 fw-semibold text-danger text-center">
                        Error retrieving submissions
                    </p>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</div>