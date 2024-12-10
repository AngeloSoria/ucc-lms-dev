<?php
$getAllStudentSubmissionInfo = $moduleContentController->getStudentsSubmission($_GET['content_id']);
?>
<div class="p-2">
    <!-- Header -->
    <section class="row bg-dark-subtle py-2">
        <div class="col">Student</div>
        <div class="col-2 text-center">Submitted</div>
        <div class="col-2 text-center">Graded</div>
        <div class="col-2 text-center">Score</div>
        <div class="col-1 text-center">Action</div>
    </section>
    <?php foreach ($getAllStudentSubmissionInfo['data'] as $studentSubmissionInfo): ?>
        <?php
        // Prepare Data
        $isSubmitted = in_array($studentSubmissionInfo['status'], ['submitted', 'graded']) ? "bi-check-circle-fill text-success" : "bi-x-circle-fill text-critical";
        $isGraded = in_array($studentSubmissionInfo['status'], ['graded']) ? "bi-check-circle-fill text-success" : "bi-x-circle-fill text-critical";
        $submissionScore = $isGraded ? ($studentSubmissionInfo['score'] ? $studentSubmissionInfo['score'] : "?") . '/' . $studentSubmissionInfo['max_score'] : "-";
        ?>
        <!-- Content -->
        <section class="row py-2">
            <div class="col d-flex gap-2 align-items-center justify-content-start">
                <img src="<?php echo convertImageBlobToSrc($studentSubmissionInfo['profile_pic']) ?>" class="rounded-circle" width="30" height="30">
                <p><?php echo $studentSubmissionInfo['first_name'] . ' ' . $studentSubmissionInfo['last_name']  ?></p>
            </div>
            <div class="col-2 text-center">
                <i class="fs-5 bi <?php echo $isSubmitted ?>"></i>
            </div>
            <div class="col-2 text-center">
                <i class="fs-5 bi <?php echo $isGraded ?>"></i>
            </div>
            <div class="col-2 text-center">
                <p><?php echo $submissionScore ?></p>
            </div>
            <div class="col-1 text-center">
                <button class="btn btn-sm btn-primary" disabled>View</button>
            </div>
        </section>
    <?php endforeach; ?>
</div>