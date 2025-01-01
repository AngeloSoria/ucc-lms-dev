<?php
$getAllSubmittableContents = $moduleContentController->getAllContentsFromSubjectSection($_GET['subject_section_id']);
// echo json_encode($getAllSubmittableContents);

?>

<div class="w-100 px-2 pb-4">
    <p class="fs-5 fw-semibold text-center">Assignments</p>
    <!-- <hr> -->
    <section>
        <!-- Nav Tabs -->
        <ul class="nav nav-underline" id="underlineTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="px-3 nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab" aria-controls="all" aria-selected="true">All</button>
            </li>
            <?php if (userHasPerms(['Teacher'])): ?>
                <li class="nav-item" role="presentation">
                    <button class="px-3 nav-link" id="toGrade-tab" data-bs-toggle="tab" data-bs-target="#toGrade" type="button" role="tab" aria-controls="toGrade" aria-selected="false">To Grade</button>
                </li>
            <?php endif; ?>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content mt-3" id="underlineTabContent">
            <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
                <section>
                    <!-- Header Columns -->
                    <div class="row m-0 bg-dark-subtle">
                        <div class="col px-0 py-2 d-flex gap-2 justify-content-start align-items-center">
                            <div class="ps-2">
                                Assignment
                            </div>
                        </div>
                        <?php if (userHasPerms(['Student'])): ?>
                            <div class="col p-0 d-flex align-items-center justify-content-start">
                                <div class="col text-center">Start</div>
                                <div class="col text-center">Due</div>
                                <div class="col text-center">Submitted</div>
                                <div class="col text-center">Graded</div>
                                <div class="col text-center">Score</div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($getAllSubmittableContents['data'] as $submittableContents): ?>
                            <?php
                            if (userHasPerms(['Student'])) {
                                if ($submittableContents['visibility'] == 'hidden') {
                                    continue;
                                } else {
                                    // Get Submission Data (e.g. Scores, Grades, States)
                                    $getLatestSubmissionInfo = $moduleContentController->getLatestSubmission($submittableContents['content_id'], $_SESSION['user_id']);
                                    if ($getLatestSubmissionInfo['success']) {
                                        // echo json_encode($getLatestSubmissionInfo['data']);
                                        // $getLatestSubmissionFiles = $moduleContentController->getFilesBySubmission($getLatestSubmissionInfo['data']["submission_id"]);
                                        if ($getLatestSubmissionInfo['data']) {
                                            $isSubmitted = in_array($getLatestSubmissionInfo['data']['status'], ['submitted', 'graded']) ? "bi-check-circle-fill text-success" : "bi-x-circle-fill text-critical";
                                            $isGraded = in_array($getLatestSubmissionInfo['data']['status'], ['graded']) ? "bi-check-circle-fill text-success" : "bi-x-circle-fill text-critical";
                                            $submissionScore = $isGraded ? ($getLatestSubmissionInfo['data']['score'] ? $getLatestSubmissionInfo['data']['score'] : "?") . '/' . $submittableContents['max_score'] : "-";
                                        } else {
                                            $isSubmitted = "bi-x-circle-fill text-critical";
                                            $isGraded = "bi-x-circle-fill text-critical";
                                            $submissionScore = "-";
                                        }
                                    }
                                }
                            }

                            $content_type = $submittableContents['content_type'];
                            $assignment_type = $submittableContents['assignment_type'];
                            $content_icon = getBootstrapIcon($content_type) . ' ' . ($content_type == 'assignment' ? 'text-primary' : 'text-warning');
                            $content_name = $submittableContents['content_title'];

                            $addedContentURL = in_array($_SESSION['role'], ['Teacher']) ? 'students_submission' : '';
                            $content_url = updateUrlParams([
                                'subject_section_id' => $_GET['subject_section_id'],
                                'module_id' => $submittableContents['module_id'],
                                'content_id' => $submittableContents['content_id']
                            ]);

                            if (!empty($addedContentURL)) {
                                $content_url .= (strpos($content_url, '?') === false ? '?' : '&') . $addedContentURL;
                            }

                            $content_module_name = $submittableContents['module_title'];
                            $content_startDate = [convertProperDate($submittableContents['start_date'], 'M j'), convertProperDate($submittableContents['start_date'], 'g:i a')];
                            $content_dueDate = [convertProperDate($submittableContents['due_date'], 'M j'), convertProperDate($submittableContents['due_date'], 'g:i a')];

                            ?>
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col p-0 d-flex gap-2 justify-content-start align-items-center">
                                        <i class="bi <?php echo $content_icon ?> fs-4" title="<?php echo ucfirst($content_type) ?>"></i>
                                        <div>
                                            <a href="<?php echo $content_url; ?>" title="<?php echo $content_name ?>" class="text-decoration-none">
                                                <p><?php echo $content_name ?></p>
                                            </a>
                                            <a href="<?php echo $content_url; ?>" title="<?php echo $content_module_name ?>" class="text-decoration-none fs-7">
                                                <p><?php echo $content_module_name ?></p>
                                            </a>
                                        </div>

                                    </div>
                                    <?php if (userHasPerms(['Student'])): ?>
                                        <div class="col p-0 d-flex align-items-center justify-content-start">
                                            <div class="col text-center">
                                                <p>
                                                    <?php echo $content_startDate[0] ?>
                                                </p>
                                                <p class="fs-7">
                                                    <?php echo $content_startDate[1] ?>
                                                </p>
                                            </div>
                                            <div class="col text-center">
                                                <p>
                                                    <?php echo $content_dueDate[0] ?>
                                                </p>
                                                <p class="fs-7">
                                                    <?php echo $content_dueDate[1] ?>
                                                </p>
                                            </div>
                                            <div class="col text-center">
                                                <i class="fs-5 bi <?php echo $isSubmitted ?>"></i>
                                            </div>
                                            <div class="col text-center">
                                                <i class="fs-5 bi <?php echo $isGraded ?>"></i>
                                            </div>
                                            <div class="col text-center"><?php echo $submissionScore ?></div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </li>

                        <?php endforeach; ?>
                    </ul>
                </section>
            </div>
            <div class="tab-pane fade" id="toGrade" role="tabpanel" aria-labelledby="toGrade-tab">
                <p>This is the content for the "To Grade" tab.</p>
            </div>
        </div>
    </section>
</div>