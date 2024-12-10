<?php
if (userHasPerms(['Student', 'Teacher'])) {
    $getSubmissionInfo = $moduleContentController->getSubmissionsByContent($_GET['content_id'], $_SESSION['user_id']);
    $totalSubmissionAttemps = count($getSubmissionInfo['data']);
}

?>
<div class="mt-sm-2 mt-md-0 widget-card p-3 shadow-sm rounded border" id="myTasks">
    <div class="d-flex justify-content-between align-items-center">
        <p class="fs-6 fw-semibold text-success m-0"><?php echo sanitizeInput("Submission") ?></p>
    </div>
    <hr class="opacity-90 mx-0 my-2">
    <div>
        <!-- Your task list goes here -->
        <ul class="list-group list-group-flush">

            <li class="list-group-item px-0 d-flex flex-column justify-content-start align-items-start gap-2">
                <p class="fs-6">
                    Attempts: <strong><?php echo $totalSubmissionAttemps ?></strong>
                </p>
                <p class="fs-6">
                    <?php
                    $maxAttempts = $module_contentInfo['data'][0]['max_attempts'] > 1000 ? 'Unlimited' : $module_contentInfo['data'][0]['max_attempts'];
                    ?>
                    Max attempts: <strong><?php echo $maxAttempts ?></strong>
                </p>
                <p class="fs-6">
                    <?php
                    $allowLate = $module_contentInfo['data'][0]['allow_late'] == 1 ? "Yes" : "No";
                    ?>
                    Allow late submissions: <strong><?php echo sanitizeInput(ucfirst($allowLate)) ?></strong>
                </p>
            </li>

        </ul>
    </div>
</div>