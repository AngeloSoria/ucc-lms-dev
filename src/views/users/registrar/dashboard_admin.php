<?php
include_once "../../../../src/config/rootpath.php";

$CURRENT_PAGE = "dashboard";
?>

<!DOCTYPE html>
<html lang="en">
<?php include_once "../../partials/head.php" ?>

<body class="">
    <?php include_once '../../users/navbar.php' ?>

    <section class="d-flex justify-content-between gap-2 box-sizing-border-box m-0 p-0">
        <!-- SIDEBAR -->
        <?php include_once '../sidebar.php' ?>

        <!-- content here -->
        <section class="row min-vh-100 w-100 m-0 p-2 d-flex justify-content-end align-items-start" id="contentSection">
            <div class="col box-sizing-border-box flex-grow-1">
                <!-- First row, first column -->
                <div class="d-flex flex-column gap-2 flex-grow-1">
                    <!-- CAROUSEL -->
                    <?php include_once "../../partials/special/usercarousel.php" ?>

                    <!-- LIVE COUNT -->
                    <?php include_once "../../partials/admin/livecount.php" ?>
                </div>
            </div>
            <div class="col bg-transparent d-flex flex-column justify-content-start align-items-center gap-2 px-1 box-sizing-border-box" id="widgetPanel">
                <!-- Second column spans both rows -->

                <!-- CALENDAR -->
                <?php include "../../partials/special/mycalendar.php" ?>

                <!-- TASKS -->
                <?php include "../../partials/special/mytasks.php" ?>
            </div>
        </section>

    </section>

    <!-- FOOTER -->
    <?php include_once "../../partials/footer.php" ?>
</body>
<script src="../../../../src/assets/js/admin-main.js"></script>

</html>