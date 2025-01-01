<?php
$module_contentInfo = $moduleContentController->getContentById($_GET['content_id']);
if (!$module_contentInfo['success'] || empty($module_contentInfo['data'])) {
    $_SESSION['_ResultMessage'] = ["success" => false, "message" => "No content id found inside the module."];
    echo '<script>window.location = \'' . BASE_PATH_LINK . '\'</script>';
    exit;
}

if ($module_contentInfo['data'][0]['visibility'] == 'hidden' && userHasPerms(['Student'])) {
    echo redirectViaJS(BASE_PATH_LINK);
    $_SESSION['_ResultMessage'] = ['success' => false, 'message' => 'You don\'t have permission to view this page.'];
    exit();
}
?>

<div class="w-100 px-2 pb-4">
    <div>
        <p class="fs-5 text-center"><?php echo sanitizeInput($module_contentInfo['data'][0]["content_title"]) ?></p>
    </div>
    <ul class="nav nav-underline" id="contentTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="instructions-tab" data-bs-toggle="tab" data-bs-target="#instructions"
                type="button" role="tab" aria-controls="instructions" aria-selected="true">
                Instructions
            </button>
        </li>
        <?php
        if (userHasPerms(['Student', 'Teacher'])) {
            $getSubmissionInfo = $moduleContentController->getSubmissionsByContent($_GET['content_id'], $_SESSION['user_id']);
            $totalSubmissionAttemps = count($getSubmissionInfo['data']);
        }
        ?>
        <?php if ($totalSubmissionAttemps > 0 && userHasPerms(['Student'])): ?>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="submission-tab" data-bs-toggle="tab" data-bs-target="#submission" type="button"
                    role="tab" aria-controls="submission" aria-selected="false">
                    Submission
                </button>
            </li>
        <?php endif; ?>
    </ul>
    <div class="tab-content" id="contentTabsContent">
        <div class="tab-pane fade show active" id="instructions" role="tabpanel" aria-labelledby="instructions-tab">
            <div class="p-1">

                <div class="mt-3 mb-3">
                    <section id="content_description">
                        <?php echo $module_contentInfo['data'][0]['description'] ?>
                    </section>
                    <hr>
                    <?php if (!in_array($module_contentInfo['data'][0]['content_type'], ['information', 'handout'])): ?>

                        <?php if (!in_array($module_contentInfo['data'][0]['content_type'], ['quiz'])): ?>
                            <div class="mt-4">
                                <?php if ($totalSubmissionAttemps >= 1 && $totalSubmissionAttemps < $module_contentInfo['data'][0]['max_attempts']): ?>
                                    <button class="btn btn-sm btn-success" data-bs-toggle="modal"
                                        data-bs-target="#uploadSubmissionModal">
                                        <i class="bi bi-plus"></i>
                                        Prepare Another Answer
                                    </button>
                                <?php elseif ($totalSubmissionAttemps < $module_contentInfo['data'][0]['max_attempts']): ?>
                                    <button class="btn btn-sm btn-success" data-bs-toggle="modal"
                                        data-bs-target="#uploadSubmissionModal">
                                        <i class="bi bi-plus"></i>
                                        Prepare Answer
                                    </button>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <?php if (isset($_GET['take_quiz'])): ?>
                                <?php require_once PARTIALS . 'user/partial_takequiz_overview.php'; ?>
                            <?php else: ?>
                                <div class="mt-4">
                                    <?php $questions = $quizController->getQuestionsByContentID($_GET['content_id']); ?>
                                    <?php if ($questions['success']): ?>
                                        <?php if ($totalSubmissionAttemps >= 1 && $totalSubmissionAttemps < $module_contentInfo['data'][0]['max_attempts']): ?>
                                            <a
                                                href="<?php echo updateUrlParams(['subject_section_id' => $_GET['subject_section_id'], 'module_id' => $_GET['module_id'], 'content_id' => $_GET['content_id'], 'take_quiz' => '']) ?>">
                                                <button class="btn btn-sm btn-success">
                                                    <i class="bi bi-plus"></i>
                                                    Take Another Quiz
                                                </button>
                                            </a>
                                        <?php elseif ($totalSubmissionAttemps < $module_contentInfo['data'][0]['max_attempts']): ?>
                                            <a
                                                href="<?php echo updateUrlParams(['subject_section_id' => $_GET['subject_section_id'], 'module_id' => $_GET['module_id'], 'content_id' => $_GET['content_id'], 'take_quiz' => '']) ?>">
                                                <button class="btn btn-sm btn-success">
                                                    <i class="bi bi-plus"></i>
                                                    Take Quiz
                                                </button>
                                            </a>
                                        <?php endif; ?>
                                    <?php endif; ?>

                                    <?php if (userHasPerms(['Teacher'])): ?>
                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#addNewQuestionModal">
                                            <i class="bi bi-plus"></i>
                                            Add question
                                        </button>
                                    <?php endif; ?>

                                </div>

                                <hr>

                                <h5>Questions</h5>
                                <ul class="list-group">
                                    <?php
                                    // Fetch questions dynamically based on content ID
                                    $contentID = $_GET['content_id'] ?? null; // Safeguard against missing content ID
                                    if ($contentID) {
                                        $questions = $quizController->getQuestionsByContentID($contentID);
                                    }
                                    ?>

                                    <?php if ($questions['success'] && !empty($questions['data'])): ?>
                                        <?php foreach ($questions['data'] as $index => $question): ?>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <div>
                                                    <strong><?= $index + 1 ?>. </strong>
                                                    <?= htmlspecialchars($question['question_text']) ?>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <!-- Badge for Question Type -->
                                                    <span
                                                        class="badge bg-secondary me-3"><?= htmlspecialchars($question['question_type']) ?></span>

                                                    <!-- Edit Button -->
                                                    <button class="btn btn-sm btn-primary me-2" data-bs-toggle="modal"
                                                        data-bs-target="#editQuestionModal"
                                                        data-question-id="<?= htmlspecialchars($question['quiz_question_id']) ?>">
                                                        <i class="bi bi-pencil-square"></i> Edit
                                                    </button>

                                                    <!-- Delete Form -->
                                                    <form method="post"
                                                        onsubmit="return confirm('Are you sure you want to delete this question?');">
                                                        <input type="hidden" name="action" value="deleteQuestion">
                                                        <input type="hidden" name="quiz_question_id"
                                                            value="<?= htmlspecialchars($question['quiz_question_id']) ?>">
                                                        <input type="hidden" name="content_id" value="<?= htmlspecialchars($contentID) ?>">
                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                            <i class="bi bi-trash"></i> Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </li>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="alert alert-warning">
                                            <?= $contentID ? 'No questions yet.' : 'Invalid Content ID.' ?>
                                        </div>
                                    <?php endif; ?>
                                </ul>

                            <?php endif; ?>
                            <?php require_once PARTIALS . 'user/modal_addNewQuestion.php' ?>
                            <?php require_once PARTIALS . 'user/modal_editQuestion.php' ?>
                        <?php endif; ?>



                    <?php endif; ?>
                </div>
                <br>
                <?php if (!in_array($module_contentInfo['data'][0]['content_type'], ['quiz'])): ?>
                    <div class="mt-4">
                        <p class="fw-semibold">Files</p>
                        <hr>
                        <section class="bg-light">
                            <div class="row justify-content-start">
                                <?php
                                $getAllContentFilesByContentId = $moduleContentController->getContentFiles($_GET['content_id']);
                                if (!$getAllContentFilesByContentId['success']) {
                                    $_SESSION['_ResultMessage'] = $getAllContentFilesByContentId;
                                    echo '<script>window.location = \'' . BASE_PATH_LINK . '\'</script>';
                                    exit;
                                } else {
                                    if ($getAllContentFilesByContentId['data']) {
                                        foreach ($getAllContentFilesByContentId['data'] as $contentFile):
                                            $previewFileMimeTypes = ["image/jpeg", "image/png", "image/gif", "audio/mpeg", "audio/wav", "video/mp4"];
                                            ?>
                                            <!-- video preview -->
                                            <?php if (in_array($contentFile['mime_type'], ['video/mp4'])):
                                                $base64Video = "data:" . $contentFile['mime_type'] . ";base64," . base64_encode($contentFile['file_data']);
                                                ?>
                                                <div class="col-md-4 mb-4">
                                                    <div class="card">
                                                        <video class="card-img-top" controls>
                                                            <source src="<?php echo $base64Video ?>"
                                                                type="<?php echo $contentFile['mime_type'] ?>">
                                                            Your browser does not support the video tag.
                                                        </video>
                                                        <div class="card-body">
                                                            <h5 class="card-title"><?php echo sanitizeInput($contentFile['file_name']) ?>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php else: ?>
                                                <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
                                                    <a href="<?php echo BASE_PATH_LINK . 'src/models/DownloadFile.php?content_id=' . $contentFile['content_id'] . '&content_file_id=' . $contentFile['content_file_id'] ?>"
                                                        target="_blank" class="card-link text-decoration-none">
                                                        <div class="card hover-shadow">
                                                            <div class="card-body text-center">
                                                                <!-- Bootstrap Icon -->
                                                                <i class="bi <?php echo getBootstrapIcon($contentFile['mime_type']) ?>"
                                                                    style="font-size: 30px;"></i>
                                                                <h6 class="card-title">
                                                                    <?php echo sanitizeInput($contentFile['file_name']) ?>
                                                                </h6>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                        <?php endforeach;
                                    } else {
                                        echo '<p class="text-center fw-semibold opacity-50">No File Added.</p>';
                                    }
                                } ?>
                            </div>
                        </section>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if (userHasPerms(['Student'])): ?>
            <div class="tab-pane fade" id="submission" role="tabpanel" aria-labelledby="submission-tab">
                <div class="p-2">
                    <?php
                    $getLatestSubmissionInfo = $moduleContentController->getLatestSubmission($_GET['content_id'], $_SESSION['user_id']);
                    if ($getLatestSubmissionInfo['success']) {
                        $getLatestSubmissionFiles = $moduleContentController->getFilesBySubmission($getLatestSubmissionInfo['data']["submission_id"]);
                    }
                    ?>

                    <div>
                        <?php echo $getLatestSubmissionInfo['data']['submission_text'] ?>
                    </div>

                    <div class="mt-4">
                        <p class="fw-semibold">Submitted Files</p>
                        <hr>
                        <section class="bg-light">
                            <div class="row justify-content-start">
                                <?php
                                if (!$getLatestSubmissionFiles['success']) {
                                    $_SESSION['_ResultMessage'] = $getLatestSubmissionFiles;
                                    echo '<script>window.location = \'' . BASE_PATH_LINK . '\'</script>';
                                    exit;
                                } else {
                                    if ($getLatestSubmissionFiles['data']) {
                                        foreach ($getLatestSubmissionFiles['data'] as $contentFile):
                                            $previewFileMimeTypes = ["image/jpeg", "image/png", "image/gif", "audio/mpeg", "audio/wav", "video/mp4"];
                                            ?>
                                            <!-- video preview -->
                                            <?php if (in_array($contentFile['mime_type'], ['video/mp4'])):
                                                $base64Video = "data:" . $contentFile['mime_type'] . ";base64," . base64_encode($contentFile['file_data']);
                                                ?>
                                                <div class="col-md-4 mb-4">
                                                    <div class="card">
                                                        <video class="card-img-top" controls>
                                                            <source src="<?php echo $base64Video ?>"
                                                                type="<?php echo $contentFile['mime_type'] ?>">
                                                            Your browser does not support the video tag.
                                                        </video>
                                                        <div class="card-body">
                                                            <h5 class="card-title"><?php echo sanitizeInput($contentFile['file_name']) ?>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php elseif (in_array($contentFile['mime_type'], ["image/jpeg", "image/png", "image/gif"])): ?>
                                                <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
                                                    <a href="<?php echo BASE_PATH_LINK . 'src/models/DownloadFile.php?submission_files_id=' . $contentFile['submission_files_id'] ?>"
                                                        target="_blank" class="card-link text-decoration-none">
                                                        <div class="card">
                                                            <img src="data:<?php echo "data:" . $contentFile['mime_type'] . ";base64," . base64_encode($contentFile['file_data']) ?>"
                                                                class="card-img-top"
                                                                alt="<?php echo sanitizeInput($contentFile['file_name']) ?>">
                                                            <div class="card-body">
                                                                <h5 class="card-title">
                                                                    <?php echo sanitizeInput($contentFile['file_name']) ?>
                                                                </h5>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                            <?php else: ?>
                                                <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
                                                    <a href="<?php echo BASE_PATH_LINK . 'src/models/DownloadFile.php?submission_files_id=' . $contentFile['submission_files_id'] ?>"
                                                        target="_blank" class="card-link text-decoration-none">
                                                        <div class="card hover-shadow">
                                                            <div class="card-body text-center">
                                                                <!-- Bootstrap Icon -->
                                                                <i class="bi <?php echo getBootstrapIcon($contentFile['mime_type']) ?>"
                                                                    style="font-size: 30px;"></i>
                                                                <h6 class="card-title">
                                                                    <?php echo sanitizeInput($contentFile['file_name']) ?>
                                                                </h6>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                        <?php endforeach;
                                    } else {
                                        echo '<p class="text-center fw-semibold opacity-50">No File Added.</p>';
                                    }
                                } ?>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <?php require_once PARTIALS . 'user/modal_uploadSubmission.php' ?>
</div>