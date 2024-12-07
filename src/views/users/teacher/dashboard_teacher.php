<?php
session_start();
$CURRENT_PAGE = "dashboard";

require_once(__DIR__ . '../../../../config/PathsHandler.php');
require_once(FILE_PATHS['DATABASE']);
require_once(FILE_PATHS['Controllers']['User']);
require_once(FILE_PATHS['Controllers']['SubjectSection']);
require_once(FILE_PATHS['Controllers']['Subject']);
require_once(FILE_PATHS['Controllers']['Section']);

require_once(FILE_PATHS['Functions']['ToastLogger']);
require_once(FILE_PATHS['Functions']['SessionChecker']);
checkUserAccess(['Teacher']);

// Create a new instance of the Database class
$database = new Database();
$db = $database->getConnection(); // Establish the database connection

// Create an instance of the UserController
$userController = new UserController();
$subjectSectionController = new SubjectSectionController($db);
$subjectController = new SubjectController();
$sectionController = new SectionController();

$myEnrolledSubjects = $subjectSectionController->getAllEnrolledSubjectsFromSectionByTeacherId($_SESSION['user_id']);

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
                    <!-- First row, first column -->
                    <div class="d-flex flex-column gap-2 flex-grow-1">
                        <!-- CAROUSEL -->
                        <?php require_once(FILE_PATHS['Partials']['User']['Carousel']) ?>

                        <!-- COURSES -->
                        <?php require_once(FILE_PATHS['Partials']['User']['Courses']) ?>
                    </div>
                </div>
                <!-- Load Widget Panel -->
                <?php require_once FILE_PATHS['Partials']['User']['WidgetPanel'] ?>
            </section>

        </section>

        <?php
        $user_requirePasswordReset = $userController->userRequiresPasswordReset($_SESSION['user_id']);
        // Password reset alert modal.
        if ($user_requirePasswordReset['data'] == true) {
            require_once(FILE_PATHS['Partials']['User']['UpdatePassword']); // Modal
        }
        ?>

        <!-- FOOTER -->
        <?php require_once(FILE_PATHS['Partials']['User']['Footer']) ?>
    </section>
</body>
<?php
include_once PARTIALS . 'user/toastHandler.php' ?>

</html>