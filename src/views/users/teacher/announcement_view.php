<?php
session_start();
$CURRENT_PAGE = "announcements";

require_once __DIR__ . '../../../../config/PathsHandler.php';
require_once CONTROLLERS . 'AnnouncementsController.php';
require_once CONTROLLERS . 'UserController.php';
require_once CONTROLLERS . 'SubjectSectionController.php';
require_once CONTROLLERS . 'SubjectController.php';
require_once CONTROLLERS . 'SectionController.php';

require_once FUNCTIONS . 'updateURLParams.php';
require_once FUNCTIONS . 'ToastLogger.php';
require_once UTILS;

$announcementController = new AnnouncementController();
// Create a new instance of the Database class
$database = new Database();
$db = $database->getConnection(); // Establish the database connection

// Create an instance of the UserController
$userController = new UserController();
$subjectSectionController = new SubjectSectionController($db);
$subjectController = new SubjectController();
$sectionController = new SectionController();

$announcementController = new AnnouncementController();

$myEnrolledSubjects = $subjectSectionController->getAllEnrolledSubjectsFromSectionByTeacherId($_SESSION['user_id']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case "addAnnouncement_global":
                //Prevent other role except Student from submitting.
                if (!userHasPerms(['Admin'])) {
                    $_SESSION['_ResultMessage'] = ['success' => false, 'message' => 'You don\'t have perms to do this action.'];
                    // Redirect to the same page to prevent resubmission
                    header("Location: " . $_SERVER['REQUEST_URI']);
                    exit();
                }

                $announcementData = [
                    'announcer_id' => $_SESSION['user_id'],
                    'title' => $_POST['input_announcementTitle'],
                    'message' => $_POST['input_announcementMessage'],
                    'is_global' => 1
                ];

                $_SESSION["_ResultMessage"] = $announcementController->addAnnouncement($announcementData);
                // Redirect to the same page to prevent resubmission
                header("Location: " . $_SERVER['REQUEST_URI']);
                exit();
            case "deleteAnnouncement_global":
                //Prevent other role except Student from submitting.
                if (!userHasPerms(['Admin'])) {
                    $_SESSION['_ResultMessage'] = ['success' => false, 'message' => 'You don\'t have perms to do this action.'];
                    // Redirect to the same page to prevent resubmission
                    header("Location: " . $_SERVER['REQUEST_URI']);
                    exit();
                }

                $announcement_id = $_POST['announcement_id'];
                if (!isset($announcement_id)) {
                    $_SESSION['_ResultMessage'] = ['success' => false, 'message' => 'No announcement id passed.'];
                    // Redirect to the same page to prevent resubmission
                    header("Location: " . $_SERVER['REQUEST_URI']);
                    exit();
                }

                $_SESSION["_ResultMessage"] = $announcementController->deleteAnnouncement($announcement_id);
                // Redirect to the same page to prevent resubmission
                header("Location: " . $_SERVER['REQUEST_URI']);
                exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<?php require_once(FILE_PATHS['Partials']['User']['Head']) ?>

<body data-theme="light">
    <section class="wrapper">
        <?php require_once(FILE_PATHS['Partials']['User']['Navbar']) ?>

        <section class="d-flex justify-content-between gap-2 box-sizing-border-box m-0 p-0">
            <!-- SIDEBAR -->
            <?php require_once(FILE_PATHS['Partials']['User']['Sidebar']) ?>

            <!-- content here -->
            <section id="contentSection">
                <div class="col p-0 box-sizing-border-box flex-grow-1">
                    <div class="d-flex flex-column gap-2 flex-grow-1">

                        <?php

                        $getGlobalAnnouncements = $announcementController->getAnnouncements(); // Global

                        ?>

                        <section class="px-2 mb-4">
                            <div>
                                <p class="fs-5">Announcements</p>
                            </div>
                            <section id="announcement-container" class="container w-90">
                                <div id="announcement-controls" class="d-flex justify-content-end align-items-center">
                                    <div class="btn-group mt-2" role="group" aria-label="Basic example">
                                        <?php if (userHasPerms(['Admin'])): ?>
                                            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#announcementFormModal_2">
                                                <i class="bi bi-plus-lg"></i>
                                                Post
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div id="announcement-content" class="mt-2 d-flex flex-column gap-3 justify-content-start align-items-center">
                                    <?php if ($getGlobalAnnouncements['success']): ?>
                                        <?php foreach ($getGlobalAnnouncements['data'] as $announcement): ?>
                                            <div id="announcement_<?php echo $announcement['id'] ?>" class="border border-1 border-success rounded container-fluid px-0 py-3 bg-white shadow-sm">
                                                <div class="row px-4">
                                                    <div class="col d-flex justify-content-start align-items-center gap-2">
                                                        <i class="bi bi-megaphone-fill fs-3 ctxt-secondary"></i>
                                                        <a href="<?php echo BASE_PATH_LINK . 'src/views/users/viewProfile.php?viewProfile=' . $announcement['announcer_id'] ?>" title="Click to view profile">
                                                            <?php echo sanitizeInput($announcement['announcer_name']) ?>
                                                        </a>
                                                    </div>
                                                    <div class="col d-flex justify-content-end align-items-center gap-3">
                                                        <p>
                                                            <?php echo convertProperDate($announcement['created_at'], "M d, h:i a") ?>
                                                        </p>
                                                        <?php if (userHasPerms(['Admin'])): ?>
                                                            <div>
                                                                <form method="POST" onsubmit="deleteAnnouncement(event, this);">
                                                                    <input type="hidden" name="action" value="deleteAnnouncement_global">
                                                                    <input type="hidden" name="announcement_id" value="<?php echo $announcement['id'] ?>">
                                                                    <button type="submit" class="btn btn-outline-danger">
                                                                        <i class="bi bi-trash-fill"></i>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="px-4">
                                                    <h5 id="announcement-title" class="mb-3">
                                                        <?php echo sanitizeInput($announcement['title']) ?>
                                                    </h5>
                                                    <p id="announcement-desc">
                                                        <?php echo $announcement['message'] ?>
                                                    </p>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                        <script>
                                            function deleteAnnouncement(e, o) {
                                                e.preventDefault();
                                                if (confirm("Do you want to delete this announcement?")) {
                                                    o.submit();
                                                }
                                            }
                                        </script>
                                    <?php else: ?>
                                        <p class="fs-6 text-muted"><?php echo sanitizeInput($getGlobalAnnouncements['message']); ?></p>
                                    <?php endif; ?>
                                </div>
                            </section>
                        </section>
                        <?php if (userHasPerms(['Admin'])): ?>
                            <div class="modal fade" id="announcementFormModal_2" tabindex="-1" aria-labelledby="announcementFormModal_2Label" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="announcementFormModal_2Label">Announcement Form (Global)</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form method="POST">
                                            <input type="hidden" name="action" value="addAnnouncement_global">
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="#input_announcementTitle">Title</label>
                                                    <input required type="text" name="input_announcementTitle" id="input_announcementTitle" placeholder="Enter announcement title" class="form-control">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="#input_announcementMessage">Description</label>
                                                    <textarea name="input_announcementMessage" id="input_announcementMessage" class="tinyMCE" placeholder="Enter announcement description."></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-success">Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
                <!-- Load Widget Panel -->
                <?php require_once FILE_PATHS['Partials']['User']['WidgetPanel'] ?>
            </section>

        </section>

        <!-- FOOTER -->
        <?php require_once(FILE_PATHS['Partials']['User']['Footer']) ?>
    </section>
</body>
<?php
include_once PARTIALS . 'user/toastHandler.php' ?>

</html>