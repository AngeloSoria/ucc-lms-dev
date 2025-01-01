<?php
session_start();
$CURRENT_PAGE = "dashboard";

require_once(__DIR__ . '../../../../config/PathsHandler.php');
require_once(FILE_PATHS['DATABASE']);
require_once(FILE_PATHS['Controllers']['User']);
require_once(FILE_PATHS['Functions']['SessionChecker']);
require_once(FILE_PATHS['Functions']['ToastLogger']);
require_once(UTILS);
require_once CONTROLLERS . 'AnnouncementsController.php';

checkUserAccess(['Admin']);

// Create a new instance of the Database class
$database = new Database();
$db = $database->getConnection(); // Establish the database connection

// Create an instance of the UserController
$userController = new UserController();
$announcementController = new AnnouncementController();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<?php require_once(FILE_PATHS['Partials']['User']['Head']) ?>

<body data-theme="light">
    <div class="wrapper shadow-sm border">
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

                        <!-- USER OVERVIEW COUNT -->
                        <?php require_once(FILE_PATHS['Partials']['HighLevel']['LiveCount']) ?>

                        <!-- ACADEMIC OVERVIEW -->
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
    </div>
</body>

<?php include_once PARTIALS . 'user/toastHandler.php' ?>

</html>