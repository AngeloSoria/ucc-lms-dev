<?php
require_once(__DIR__ . '../../../../config/PathsHandler.php');
require_once(FILE_PATHS['DATABASE']);

session_start();

$role = $_SESSION['role'];

// If session is not set, redirect to login
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_PATH);
    exit();
}

$CURRENT_PAGE = "dashboard";

?>

<!DOCTYPE html>
<html lang="en">
<?php require_once(FILE_PATHS['Partials']['User']['Head']) ?>

<body class="">
    <?php require_once(FILE_PATHS['Partials']['User']['Navbar']) ?>

    <section class="d-flex justify-content-between gap-2 box-sizing-border-box m-0 p-0">
        
        <!-- SIDEBAR -->
        <?php require_once(FILE_PATHS['Partials']['User']['Sidebar']) ?>

        <!-- content here -->
        <section class="row min-vh-100 w-100 m-0 p-2 d-flex justify-content-end align-items-start" id="contentSection">
            <div class="col box-sizing-border-box flex-grow-1">
                <!-- First row, first column -->
                <div class="d-flex flex-column gap-2 flex-grow-1">
                    <!-- CAROUSEL -->
                    <?php require_once(FILE_PATHS['Partials']['User']['Carousel']) ?>

                    <!-- LIVE COUNT -->
                    <?php require_once(FILE_PATHS['Partials']['HighLevel']['LiveCount']) ?>
                </div>
            </div>
            <div class="col bg-transparent d-flex flex-column justify-content-start align-items-center gap-2 px-1 box-sizing-border-box" id="widgetPanel">
                <!-- Second column spans both rows -->

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
<script src="../../../../src/assets/js/admin-main.js"></script>

</html>