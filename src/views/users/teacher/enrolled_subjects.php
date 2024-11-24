<?php
session_start();
$CURRENT_PAGE = "enrolled-subjects";

require_once(__DIR__ . '../../../../config/PathsHandler.php');
require_once(FILE_PATHS['DATABASE']);
require_once(FILE_PATHS['Controllers']['User']);
require_once(FILE_PATHS['Functions']['SessionChecker']);
require_once(FILE_PATHS['Functions']['ToastLogger']);
checkUserAccess(['Teacher']);

// Create a new instance of the Database class
$database = new Database();
$db = $database->getConnection(); // Establish the database connection

// Create an instance of the UserController
$userController = new UserController();
$fakedata_enrolled_subjects2 = [
    [
        'subject_id' => 3001,
        'subject_code' => 'ITMA1223',
        'subject_name' => 'Data Structures & Algorithms',
        'subject_section' => 'BSIT701P',
        'subject_image' => 'img/client-images/program_Tech.jpg'
    ],
    [
        'subject_id' => 3002,
        'subject_code' => 'PHYS4412',
        'subject_name' => 'Physical Education 2',
        'subject_section' => 'BSIT701P',
        'subject_image' => 'img/client-images/program_PE.jpg'
    ],
    [
        'subject_id' => 3003,
        'subject_code' => 'LERP1337',
        'subject_name' => 'Information Assurance and Security (Data Privacy)',
        'subject_section' => 'BSIT701P',
        'subject_image' => 'img/client-images/program_Tech.jpg'
    ],
    [
        'subject_id' => 3004,
        'subject_code' => 'MATH1124',
        'subject_name' => 'Calculus II',
        'subject_section' => 'BSIT701P',
        'subject_image' => 'img/client-images/program_Math.jpg'
    ],
    [
        'subject_id' => 3005,
        'subject_code' => 'CHEM2011',
        'subject_name' => 'Organic Chemistry',
        'subject_section' => 'BSIT701P',
        'subject_image' => 'img/client-images/program_Chem.jpg'
    ],
    [
        'subject_id' => 3006,
        'subject_code' => 'HIST3010',
        'subject_name' => 'World History',
        'subject_section' => 'BSIT701P',
        'subject_image' => 'img/client-images/program_History.jpg'
    ],
    [
        'subject_id' => 3007,
        'subject_code' => 'PSYC2210',
        'subject_name' => 'Introduction to Psychology',
        'subject_section' => 'BSIT701P',
        'subject_image' => 'img/client-images/program_Psychology.jpg'
    ],
    [
        'subject_id' => 3008,
        'subject_code' => 'CSCI1101',
        'subject_name' => 'Introduction to Computer Science',
        'subject_section' => 'BSIT701P',
        'subject_image' => 'img/client-images/program_Tech.jpg'
    ],
];
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

                        <!-- SUBJECTS -->
                        <div class="bg-white shadow-sm rounded p-4 border border-box mb-sm-2 d-flex flex-column" id="main-container" style="max-height: none;">
                            <div class="d-flex justify-content-between align-items-center">
                                <p class="fs-4 fw-semibold text-success m-0">My Subjects (Sample)</p>
                                <div class="container-controls">
                                    <button class="btn btn-transparent" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical fs-5"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="mycourses_dropdown">
                                        <li><a class="dropdown-item" href="javascript:void(0);" onclick="toggleCoursesView(this);">Tile View</a></li>
                                    </ul>
                                </div>
                            </div>
                            <hr class="opacity-80 mx-0 my-2">

                            <!-- Inner Content -->
                            <div class="bg-transparent h-100">
                                <div id="container_tileview" class="h-100 row d-flex overflow-y-auto" style="max-height: 1000px;">
                                    <?php foreach ($fakedata_enrolled_subjects2 as $subject) { ?>
                                        <div class="col-md-6 col-lg-4 p-1" style="height: 250px;">
                                            <a href="subject_view.php">
                                                <div id="item_card" class="h-100 w-100 bg-success bg-opacity-75 shadow-sm border rounded overflow-hidden d-flex flex-column">
                                                    <div>
                                                        <img src="<?php echo asset($subject['subject_image']) ?>" class="w-100 object-fit-cover" style="height: 120px;">
                                                    </div>
                                                    <div class="px-2 flex-grow-1 position-relative">
                                                        <p class="fs-6 text-white pt-2 fw-semibold">
                                                            <?php echo $subject['subject_name'] . ' (' . $subject['subject_code'] . ')' ?>
                                                        </p>
                                                        <p class="fs-7 text-white position-absolute bottom-0 start-0 ms-2 mb-2">
                                                            <?php echo $subject['subject_section'] ?>
                                                        </p>
                                                        <div class="d-flex position-absolute bottom-0 end-0 me-2 mb-2">
                                                            <div class="d-flex gap-1 fs-7 align-items-center bg-primary bg-opacity-75 px-2 rounded-pill text-white" title="Grades">
                                                                <p>87</p>
                                                                <div class="icon"><i class="bi bi-percent"></i></div>
                                                            </div>
                                                            <div class="d-flex gap-1 fs-7 align-items-center bg-danger bg-opacity-75 px-2 rounded-pill text-white" title="Number of Modules">
                                                                <p>1</p>
                                                                <div class="icon"><i class="bi bi-file-earmark-text-fill"></i></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Load Widget Panel -->
                <?php require_once FILE_PATHS['Partials']['User']['WidgetPanel'] ?>
            </section>
        </section>

        <!-- FOOTER -->
        <?php require_once(FILE_PATHS['Partials']['User']['Footer']) ?>
    </div>
</body>

<?php
// Show Toast
if (isset($_SESSION["_ResultMessage"])) {

    makeToast([
        'type' => $_SESSION["_ResultMessage"]['success'] ? 'success' : 'error',
        'message' => $_SESSION["_ResultMessage"]['message'],
    ]);
    outputToasts(); // Execute toast on screen.
    unset($_SESSION["_ResultMessage"]); // Dispose
}

?>

</html>