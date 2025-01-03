<?php

// Fetch module content information
$module_contentInfo = $moduleContentController->getContentById($_GET['content_id']);
if (!$module_contentInfo['success'] || empty($module_contentInfo['data'])) {
    $_SESSION['_ResultMessage'] = ["success" => false, "message" => "No content id found inside the module."];
    echo redirectViaJS(BASE_PATH_LINK);
    exit;
}

// Fetch student submissions
$getStudentSubmissionsInfo = $moduleContentController->getStudentSubmission($_GET['content_id'], $_GET['student_id']);
$getStudentLatestSubmissionInfo = $moduleContentController->getStudentSubmission($_GET['content_id'], $_GET['student_id'], true);

if (!$getStudentSubmissionsInfo['success'] || !$getStudentLatestSubmissionInfo['success']) {
    $_SESSION['_ResultMessage'] = ["success" => false, "message" => "Error retrieving student's submission data."];
    echo redirectViaJS(BASE_PATH_LINK);
    exit;
}
?>
<div>
    <p class="fs-4 text-center"><?php echo sanitizeInput($module_contentInfo['data'][0]["content_title"]); ?></p>
</div>

<div class="p-2">
    <section class="p-2 border position-relative">
        <!-- Current Submission View -->
        <div>
            <?php
            if ($getStudentLatestSubmissionInfo['data']) {
                // Display the latest submission by default
                $latestSubmission = $getStudentLatestSubmissionInfo['data'][0];
                $getStudentLatestSubmissionFilesInfo = $moduleContentController->getSubmittedFilesByContentIdStudentId($_GET['content_id'], $_GET['student_id'], $getStudentLatestSubmissionInfo['data'][0]['submission_id']);
            ?>
                <div class="container-fluid">
                    <?php if ($getStudentLatestSubmissionFilesInfo['data']): ?>
                        <div>
                            <p class="text-success mb-2"><strong>Submission Files</strong></p>
                            <div class=" p-2 row">
                                <?php foreach ($getStudentLatestSubmissionFilesInfo['data'] as $submittedFile): ?>
                                    <?php if (in_array($submittedFile['mime_type'], ['video/mp4'])): ?>
                                        <div class="col-md-4 mb-4">
                                            <div class="card">
                                                <video class="card-img-top" controls>
                                                    <source src="<?php echo $base64Video ?>" type="<?php echo $submittedFile['mime_type'] ?>">
                                                    Your browser does not support the video tag.
                                                </video>
                                                <div class="card-body">
                                                    <h5 class="card-title"><?php echo sanitizeInput($submittedFile['file_name']) ?></h5>
                                                </div>
                                            </div>
                                        </div>
                                    <?php elseif (in_array($submittedFile['mime_type'], ["image/jpeg", "image/png", "image/gif"])): ?>
                                        <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
                                            <a href="<?php echo BASE_PATH_LINK . 'src/models/DownloadFile.php?submission_files_id=' . $submittedFile['submission_files_id'] ?>" target="_blank" class="card-link text-decoration-none">
                                                <div class="card hover-shadow p-0">
                                                    <div class="card-body text-center p-0">
                                                        <img src="data:<?php echo convertImageBlobToSrc($submittedFile['file_data']) ?>" class="card-img-top" alt="<?php echo sanitizeInput($submittedFile['file_name']) ?>">
                                                        <div class="card-body">
                                                            <h5 class="card-title"><?php echo sanitizeInput($submittedFile['file_name']) ?></h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    <?php else: ?>
                                        <a class="col-lg-3 py-4 btn btn-sm btn-light text-truncate" target="_blank" href="<?php echo BASE_PATH_LINK . 'src/models/DownloadFile.php?submission_files_id=' . $submittedFile['submission_files_id'] ?>">
                                            <i class="bi <?php echo getBootstrapIcon($submittedFile['mime_type']) ?>"></i>
                                            <?php echo $submittedFile['file_name'] ?>
                                        </a>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <hr>
                    <?php endif; ?>
                    <div class="mt-3">
                        <p class="text-success mb-2"><strong>Submission Text</strong></p>
                        <?php echo  $latestSubmission['submission_text'] ?>
                    </div>
                </div>
            <?php } else {
                echo "<p class='opacity-50'>No submission</p>";
            } ?>
        </div>

    </section>
</div>