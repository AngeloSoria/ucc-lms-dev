<?php
session_start();
$CURRENT_PAGE = "dashboard";

require_once(__DIR__ . '../../../../config/PathsHandler.php');
require_once(FILE_PATHS['DATABASE']);
require_once(FILE_PATHS['Controllers']['User']);
require_once(FILE_PATHS['Functions']['SessionChecker']);
require_once(FILE_PATHS['Functions']['ToastLogger']);

checkUserAccess(['Level Coordinator']);

// Create a new instance of the Database class
$database = new Database();
$db = $database->getConnection(); // Establish the database connection

// Create an instance of the UserController
$userController = new UserController();

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

                        <!-- LIVE COUNT -->
                        <?php require_once(FILE_PATHS['Partials']['HighLevel']['LiveCount']) ?>
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