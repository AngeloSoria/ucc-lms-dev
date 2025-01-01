<?php

$module_contentInfo = $moduleContentController->getContentById($_GET['content_id']);
if (!$module_contentInfo['success'] || empty($module_contentInfo['data'])) {
    $_SESSION['_ResultMessage'] = ["success" => false, "message" => "No content id found inside the module."];
    echo '<script>window.location = \'' . BASE_PATH_LINK . '\'</script>';
    exit;
}

$getAllStudentSubmissionInfo = $moduleContentController->getStudentsSubmission($_GET['content_id']);
if (!$getAllStudentSubmissionInfo['success']) {
    $_SESSION['_ResultMessage'] = $getAllStudentSubmissionInfo;
    echo redirectViaJS(BASE_PATH_LINK);
    exit;
}
?>
<div>
    <p class="fs-4 text-center"><?php echo sanitizeInput($module_contentInfo['data'][0]["content_title"]) ?></p>
</div>
<div class="p-2">
    <!-- Header -->
    <section class="row bg-light-2 py-2 border-bottom border-dark">
        <div class="col">Student (<?php echo count($getAllStudentSubmissionInfo['data']) ?>)</div>
        <div class="col-sm-2 col-md-2 col-lg-1 text-center">Submitted</div>
        <div class="col-sm-2 col-md-2 col-lg-1 text-center">Graded</div>
        <div class="col-sm-2 col-md-2 col-lg-1 text-center">Score</div>
        <div class="col-sm-2 col-md-2 col-lg-1 text-center">Action</div>
    </section>
    <?php if ($getAllStudentSubmissionInfo['data']): ?>
        <ul class="list-group list-group-flush">
            <?php foreach ($getAllStudentSubmissionInfo['data'] as $studentSubmissionInfo): ?>
                <?php
                // Prepare Data
                $isSubmitted = in_array($studentSubmissionInfo['status'], ['submitted', 'graded']) ? "bi-check-circle-fill text-success" : "bi-x-circle-fill text-critical";
                $isGraded = in_array($studentSubmissionInfo['status'], ['graded']) ? "bi-check-circle-fill text-success" : "bi-x-circle-fill text-critical";
                $submissionScore = $isGraded ? ($studentSubmissionInfo['score'] ? $studentSubmissionInfo['score'] : "?") . '/' . $studentSubmissionInfo['max_score'] : "-";
                ?>
                <!-- Content -->
                <li class="list-group-item px-0">
                    <div class="row align-items-center">
                        <div class="col d-flex gap-2 align-items-center justify-content-start">
                            <img src="<?php echo convertImageBlobToSrc($studentSubmissionInfo['profile_pic']) ?>" class="rounded-circle" width="30" height="30">
                            <a href="<?php echo VIEWS . 'users/viewprofile.php?viewProfile=' . $studentSubmissionInfo['user_id'] ?>">
                                <p class="mb-0"><?php echo $studentSubmissionInfo['first_name'] . ' ' . $studentSubmissionInfo['last_name'] ?></p>
                            </a>
                        </div>
                        <div class="col-sm-2 col-md-2 col-lg-1 text-center">
                            <i class="fs-5 bi <?php echo $isSubmitted ?>"></i>
                        </div>
                        <div class="col-sm-2 col-md-2 col-lg-1 text-center">
                            <i class="fs-5 bi <?php echo $isGraded ?>"></i>
                        </div>
                        <div class="col-sm-2 col-md-2 col-lg-1 text-center">
                            <p class="mb-0"><?php echo $submissionScore ?></p>
                        </div>
                        <div class="col-sm-2 col-md-2 col-lg-1 text-center">
                            <a href="<?php echo updateUrlParams([
                                            "subject_section_id" => $_GET['subject_section_id'],
                                            "module_id" => $_GET['module_id'],
                                            "content_id" => $_GET['content_id'],
                                            "students_submission" => '1',
                                            "student_id" => $studentSubmissionInfo['user_id']
                                        ]) ?>">
                                <button class="btn btn-sm btn-primary">View</button>
                            </a>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <section class="col text-center p-2">
            <p>No Enrolled Students...</p>
        </section>
    <?php endif; ?>
</div>