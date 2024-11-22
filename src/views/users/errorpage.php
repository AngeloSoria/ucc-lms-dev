<?php
session_start();

require_once(__DIR__ . '../../../config/PathsHandler.php');
require_once(FILE_PATHS['DATABASE']);
require_once(FILE_PATHS['Controllers']['User']);
require_once(FILE_PATHS['Functions']['SessionChecker']);
require_once(FILE_PATHS['Functions']['UpdateURLParams']);

checkUserAccess(['Student', 'Admin', 'Level Coordinator', 'Teacher']);

// Create a new instance of the Database class
$database = new Database();
$db = $database->getConnection(); // Establish the database connection

// Create an instance of the UserController
$userController = new UserController();

if (isset($_GET['access_denied'])) {
}

?>

<!DOCTYPE html>
<html lang="en">
<?php require_once(FILE_PATHS['Partials']['User']['Head']) ?>

<body data-theme="light">
    <div class="wrapper shadow-sm border">
        <?php require_once(FILE_PATHS['Partials']['User']['Navbar']) ?>

        <section class="d-flex justify-content-between gap-2 box-sizing-border-box m-0">
            <!-- SIDEBAR -->
            <?php require_once(FILE_PATHS['Partials']['User']['Sidebar']) ?>

            <!-- content here -->
            <section id="contentSection">
                <div class="w-100">
                    <div class="bg-white rounded shadow-sm overflow-hidden">
                        <div id="banner" class="bg-success bg-gradient bg-opacity-100 p-2" style="height: 10px;">
                        </div>
                        <div class="row p-3">
                            <h1>ERROR</h1>
                        </div>
                    </div>
                </div>
            </section>
        </section>

        <!-- FOOTER -->
        <?php require_once(FILE_PATHS['Partials']['User']['Footer']) ?>
    </div>
</body>

<?php
// Show Toast
if (isset($_SESSION["_ResultMessage"])) {
    // print_r($_SESSION["_ResultMessage"]);
    makeToast([
        'type' => $_SESSION["_ResultMessage"]['success'] ? 'success' : 'error',
        'message' => $_SESSION["_ResultMessage"]['message'],
    ]);
    outputToasts(); // Execute toast on screen.
    unset($_SESSION["_ResultMessage"]); // Dispose
}

?>


</html>