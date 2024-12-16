<?php
session_start();

require_once(__DIR__ . '../../../../config/PathsHandler.php');
require_once PARTIALS . 'user/partial_head_SubjectView.php';

?>

<!DOCTYPE html>
<html lang="en">
<?php require_once PARTIALS . 'user/head.php' ?>

<body>
    <div class="wrapper shadow-sm border overflow-x-auto">
        <?php require_once PARTIALS . 'user/navbar.php' ?>

        <section class="d-flex justify-content-between gap-2 box-sizing-border-box m-0 p-0">
            <!-- SIDEBAR -->
            <?php require_once PARTIALS . 'user/sidebar-2.php' ?>

            <!-- content here -->
            <section id="contentSection" class="row d-flex justify-content-start align-items-start">
                <div class="col p-0 box-sizing-border-box">
                    <?php require_once PARTIALS . 'user/subject_overview.php' ?>
                </div>
                <?php if (isset($_GET['subject_section_id'], $_GET['module_id'], $_GET['content_id'])): ?>
                    <?php
                    $getContentInfo = $moduleContentController->getContentById($_GET['content_id']);
                    if (!in_array($getContentInfo['data'][0]['content_type'], ['handout', 'information'])):
                    ?>
                        <div class="col-lg-3 mt-md-2 mt-lg-0 px-sm-0 px-md-1 d-flex flex-column gap-1">
                            <?php if (in_array($getContentInfo['data'][0]['content_type'], ['quiz']) && isset($_GET['take_quiz'])): ?>
                                <?php require_once PARTIALS . 'user/widgetCard_QuizTakingPreview.php' ?>
                            <?php else: ?>
                                <?php require_once PARTIALS . 'user/widgetCard_ContentInfo_ContentAttr.php' ?>
                                <?php require_once PARTIALS . 'user/widgetCard_ContentInfo_Score.php' ?>
                                <?php require_once PARTIALS . 'user/widgetCard_ContentInfo_Submission.php' ?>
                            <?php endif; ?>

                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <?php if (!isset($_GET['assignments']) && (!isset($_GET['subject_section_id']) || !isset($_GET['gradebook']))): ?>
                        <div class="col-lg-3 mt-md-2 mt-lg-0 px-sm-0 px-md-1 d-flex flex-column gap-1">
                            <?php require_once PARTIALS . 'user/mytasks.php' ?>
                            <?php require_once PARTIALS . 'user/announcements.php' ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </section>

        </section>

        <!-- FOOTER -->
        <?php require_once PARTIALS . 'user/footer.php' ?>
    </div>
</body>
<?php include_once PARTIALS . 'user/toastHandler.php' ?>

</html>