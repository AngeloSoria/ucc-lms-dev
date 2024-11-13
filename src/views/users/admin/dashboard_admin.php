<?php
session_start();
$CURRENT_PAGE = "dashboard";

require_once(__DIR__ . '../../../../config/PathsHandler.php');
require_once(FILE_PATHS['DATABASE']);
require_once(FILE_PATHS['Controllers']['User']);
require_once(FILE_PATHS['Functions']['SessionChecker']);
checkUserAccess(['Admin']);

// Create a new instance of the Database class
$database = new Database();
$db = $database->getConnection(); // Establish the database connection

// Create an instance of the UserController
$userController = new UserController($db);

?>

<!DOCTYPE html>
<html lang="en">
<?php require_once(FILE_PATHS['Partials']['User']['Head']) ?>

<body data-theme="light">
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
            <div id="widgetPanel">
                <!-- CALENDAR -->
                <?php require_once(FILE_PATHS['Partials']['User']['Calendar']) ?>
                <!-- TASKS -->
                <?php require_once(FILE_PATHS['Partials']['User']['Tasks']) ?>
            </div>
        </section>

    </section>

    <!-- FOOTER -->
    <?php require_once(FILE_PATHS['Partials']['User']['Footer']) ?>
</body>
<script src="<?php echo asset('js/admin-main.js') ?>"></script>

</html>