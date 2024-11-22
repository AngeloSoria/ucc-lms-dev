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

if (isset($_GET['viewProfile'])) {
    $retrieveUserProfile = $userController->getUserById($_GET['viewProfile']);
    if ($retrieveUserProfile['success'] == true) {
        $myUserProfile = $retrieveUserProfile['data'];

        // Prepare user profile:
        $profile_image = isset($myUserProfile['profile_pic']) ? 'data:image/jpeg;base64,' . base64_encode($myUserProfile['profile_pic']) : asset('img/avatars/default-profile.png');
        $profile_userid = $myUserProfile['user_id'];
        $profile_username = $myUserProfile['username'];

        $profile_firstname = $myUserProfile['first_name'];
        $profile_middlename = $myUserProfile['middle_name'];
        $profile_lastname = $myUserProfile['last_name'];

        $profile_fullname = "$profile_firstname $profile_middlename $profile_lastname";

        $profile_dob = $myUserProfile['dob'];
        $profile_gender = $myUserProfile['gender'];
        $profile_role = $myUserProfile['role'];
        $profile_login = $myUserProfile['last_login'];
    } else {
        $_SESSION["_ResultMessage"] = $retrieveUserProfile;
        header("Location: " . BASE_PATH_LINK);
        exit();
    }
} else {
    $viewProfileID = $_GET['viewProfile'];
    $_SESSION["_ResultMessage"] = ['success' => false, 'message' => "Invalid profile viewing of user_id ($viewProfileID)"];
    header("Location: " . clearUrlParams());
    exit();
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
                        <div class="row p-3 position-relative">
                            <span class="col-1 position-absolute top-0 end-0 bg-success bg-opacity-25 me-5 mt-3 text-center px-0 rounded-pill"><?php echo $profile_role ?></span>

                            <div class="col-md-12">
                                <section id="profileImage" class="border border-3 border-success-subtle rounded-circle overflow-hidden m-auto shadow-sm" style="height: 200px; width: 200px;">
                                    <img src="<?php echo $profile_image ?>" alt="my profile" class="object-fit-cover w-100 h-100">
                                </section>
                                <section id="bio" class="text-center bg-transparent p-2">
                                    <p class="fs-3 fw-semibold"><?php echo $profile_fullname ?></p>
                                    <p class="fs-6 fw-thin">@<?php echo $profile_username ?></p>
                                </section>
                            </div>
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