<?php
session_start();
$CURRENT_PAGE = "programs";

require_once(__DIR__ . '../../../../config/PathsHandler.php');
require_once(FILE_PATHS['DATABASE']);
require_once(FILE_PATHS['Controllers']['Program']);
require_once(FILE_PATHS['Controllers']['CardImages']);

require_once(FILE_PATHS['Partials']['Widgets']['Card']);
require_once(FILE_PATHS['Functions']['SessionChecker']);
require_once(FILE_PATHS['Functions']['ToastLogger']);
require_once(FILE_PATHS['Functions']['UpdateURLParams']);

checkUserAccess(['Admin', 'Level Coordinator']);

$widget_card = new Card();

// Create a new instance of the Database class
$database = new Database();
$db = $database->getConnection(); // Establish the database connection

$programController = new ProgramController($db); // Create UserController instance


// Handle user addition request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'addProgram') {
    // Collect user data from form inputs
    $programData = [
        'program_code' => $_POST['program_code'],
        'program_name' => $_POST['program_name'],
        'program_description' => $_POST['program_description'],
        'educational_level' => $_POST['educational_level'],
        'program_image' => isset($_FILES['program_image']) ? $_FILES['program_image'] : NULL
    ];

    $addProgramResult = $programController->addProgram($programData);
    $_SESSION["_ResultMessage"] = $addProgramResult;

    // Redirect to the same page to prevent resubmission
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit();
}
$programList = $programController->getAllPrograms();  // This will return the programs to the view

if ($programList['success'] == false) {
    $_SESSION["_ResultMessage"] = $programList;
}
?>

<!DOCTYPE html>
<html lang="en">
<?php require_once(FILE_PATHS['Partials']['User']['Head']) ?>

<body>
    <div class="wrapper shadow-sm border">
        <?php require_once(FILE_PATHS['Partials']['User']['Navbar']) ?>

        <section class="d-flex justify-content-between gap-2 box-sizing-border-box m-0 p-0">
            <!-- SIDEBAR -->
            <?php require_once(FILE_PATHS['Partials']['User']['Sidebar']) ?>

            <!-- content here -->
            <section id="contentSection">
                <div class="col box-sizing-border-box flex-grow-1">
                    <?php if (!isset($_GET['viewProgram'])): ?>
                        <div class="bg-white rounded p-3 shadow-sm border">
                            <!-- Headers -->
                            <div class="mb-3 row align-items-start">
                                <div class="col-4 d-flex gap-3">
                                    <h5 class="ctxt-primary">Programs</h5>
                                </div>
                                <div class="col-8 d-flex justify-content-end gap-2">
                                    <!-- Tools -->
                                    <button
                                        class="btn btn-primary btn-lg rounded fs-6 px-3 c-primary d-flex gap-3 align-items-center"
                                        data-bs-toggle="modal" data-bs-target="#programFormModal">
                                        <i class="bi bi-plus-circle"></i> Add Program
                                    </button>

                                    <!-- Preview Type -->
                                    <div class="btn-group" id="previewTypeContainer">
                                        <a id="btnPreviewTypeCatalog" type="button" preview-container-target="view_catalog"
                                            class="btn btn-sm btn-primary c-primary px-2 d-flex justify-content-center align-items-center">
                                            <i class="bi bi-card-heading fs-6"></i>
                                        </a>
                                        <a id="btnPreviewTypeTable" type="button" preview-container-target="view_table"
                                            class="btn btn-sm btn-outline-primary c-primary px-2 d-flex justify-content-center align-items-center">
                                            <i class="bi bi-table fs-6"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Catalog View -->
                            <div preview-container-name="view_catalog" preview-container-default class="d-flex justify-content-start align-items-start gap-2 flex-wrap">
                                <?php
                                if ($programList['success'] && !empty($programList['data'])) {
                                    foreach ($programList['data'] as $program) {
                                        echo $widget_card->Create(
                                            2,
                                            "program_" . $program['program_id'],
                                            !empty($program['program_image']) ? 'data:image/jpeg;base64,' . base64_encode($program['program_image']) : null,
                                            [
                                                "title" => $program['program_name'],
                                                "others" => [
                                                    [
                                                        'hint' => 'Program Description',
                                                        'icon' => '',
                                                        'data' => htmlspecialchars($program['program_code']),
                                                    ],
                                                    [
                                                        'hint' => 'Educational Level',
                                                        'icon' => '',
                                                        'data' => 'Level: ' . htmlspecialchars($program['educational_level']),
                                                    ],
                                                ],
                                            ],
                                            true,
                                            true,
                                            updateUrlParams(['viewProgram' => $program['program_id']])
                                        );
                                    }
                                } else {
                                    echo '<p class="text-danger">No programs available.</p>';
                                }
                                ?>
                            </div>

                            <!-- Table View -->
                            <div preview-container-name="view_table" class="d-none">
                                <table class="c-table table">
                                    <thead>
                                        <tr>
                                            <th>
                                                <input type="checkbox" name="checkbox_data_selectAll"
                                                    id="checkbox_data_selectAll" class="form-check-input">
                                            </th>
                                            <th>Code</th>
                                            <th>Name</th>
                                            <th>Description</th>
                                            <th>Level</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (!empty($programList['data'])) {
                                            foreach ($programList['data'] as $program) {
                                        ?>
                                                <tr>
                                                    <td>
                                                        <input type="checkbox"
                                                            name="checkbox_data_<?php echo htmlspecialchars($program['program_code']); ?>"
                                                            class="form-check-input">
                                                    </td>
                                                    <td><?php echo htmlspecialchars($program['program_code']); ?></td>
                                                    <td><?php echo htmlspecialchars($program['program_name']); ?></td>
                                                    <td><?php echo htmlspecialchars($program['program_description']); ?></td>
                                                    <td><?php echo htmlspecialchars($program['educational_level']); ?></td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <button class="btn btn-sm btn-primary dropdown-toggle" type="button"
                                                                id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                                Actions
                                                            </button>
                                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                <li><a class="dropdown-item" href="#">Configure</a></li>
                                                                <li><a class="dropdown-item" href="#">Delete</a></li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                        <?php
                                            }
                                        } else {
                                            echo '<tr><td colspan="6" class="text-danger text-center">No programs available.</td></tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="bg-white rounded p-3 shadow-sm border">
                            <div class="mb-3 row align-items-start bg-transparent box-sizing-border-box">
                                <div class="col-4 d-flex gap-2 justify-content-start align-items-center box-sizing-border-box">
                                    <!-- breadcrumbs -->
                                    <h5 class="ctxt-primary p-0 m-0">
                                        <a class="ctxt-primary" href="<?= clearUrlParams(); ?>">Programs</a>
                                        <span><i class="bi bi-caret-right-fill"></i></span>
                                        <a class="ctxt-primary" href="<?= updateUrlParams(['viewProgram' => $_GET['viewProgram']]) ?>">
                                            <?php if (isset($_GET['viewProgram'])) {
                                                $retrieved_program_ss = $programController->getProgramById($_GET['viewProgram']);

                                                if ($retrieved_program_ss['success'] == false) {
                                                    $_SESSION["_ResultMessage"] = $retrieved_program_ss;
                                                    echo "<script>window.location = '" . clearUrlParams() . "';</script>";
                                                    exit();
                                                } else {
                                                    echo $retrieved_program_ss['data'][0]['program_code'];
                                                }
                                            ?>
                                            <?php } ?>
                                        </a>
                                    </h5>
                                    <!-- end of breadcrumbs -->
                                </div>
                                <div class="col-8 d-flex justify-content-end gap-2">
                                    <button class="btn btn-success" disabled data-bs-toggle="modal" data-bs-target="#modal_updateCardModal_program">
                                        <i class="bi bi-card-image"></i>
                                        Set Image
                                    </button>
                                </div>
                            </div>

                            <!-- Headers -->
                            <div class="mb-3 row align-items-start">
                                <hr>
                                <!-- generated -->
                                <div class="container my-4">
                                    <h4 class="fw-bolder text-success"></h4>
                                    <div class="card shadow-sm position-relative">
                                        <div class="card-header position-relative d-flex justify-content-start align-items-center gap-3 bg-success bg-opacity-75">
                                            <div class="position-absolute top-0 end-0 mt-3 me-4">
                                                <button class="btn cbtn-secondary px-4">
                                                    Edit
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <section class="mb-4">
                                                <div class="row mb-3">
                                                    <h5>Program Information</h5>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-md-6">
                                                        <h6 class="">Program Image</h6>
                                                        <div class="">
                                                            <img style="width: 200px;" src="<?= !empty($retrieved_program_ss['data'][0]['program_image']) ? 'data:image/jpeg;base64,' . base64_encode($retrieved_program_ss['data'][0]['program_image']) : 'https://via.placeholder.com/200?text=No+Image' ?>" alt="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-xxl-6">
                                                        <h6 class="">Program Name</h6>
                                                        <input updateEnabled class="form-control" type="text" disabled value="<?= htmlspecialchars($retrieved_program_ss['data'][0]['program_name']) ?>">
                                                    </div>
                                                    <div class="col-xxl-4">
                                                        <h6 class="">Program Code</h6>
                                                        <input updateEnabled class="form-control" type="text" disabled value="<?= htmlspecialchars($retrieved_program_ss['data'][0]['program_code']) ?>">
                                                    </div>
                                                    <div class="col-xxl-2">
                                                        <h6 class="">Educational Level</h6>
                                                        <input updateEnabled class="form-control" type="text" disabled value="<?= htmlspecialchars($retrieved_program_ss['data'][0]['educational_level']) ?>">
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-md-12">
                                                        <h6 class="">Description</h6>
                                                        <textarea updateEnabled class="form-control" rows="8" disabled><?= htmlspecialchars($retrieved_program_ss['data'][0]['program_description']) ?></textarea>
                                                    </div>
                                                </div>
                                            </section>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
        </section>


        <?php require_once(FILE_PATHS['Partials']['HighLevel']['Modals']['Program']['Add']) ?>

        <?php
        require_once(FILE_PATHS['Partials']['User']['UpdateCardImage']);
        if (isset($_GET['viewProgram'])) {
            echo create_UpdateCardImage('programs', $_GET['viewProgram']);
        }
        ?>

        <!-- FOOTER -->
        <?php require_once(FILE_PATHS['Partials']['User']['Footer']) ?>
    </div>
</body>
<script src="<?php echo asset('js/preview-handler.js') ?>"></script>
<script src="<?php echo asset('js/toast.js') ?>"></script>

<?php
// Show Toast
if (isset($_SESSION["_ResultMessage"])) {
    makeToast([
        'type' => $_SESSION["_ResultMessage"]["success"] ? 'success' : 'error',
        'message' => $_SESSION["_ResultMessage"]["message"],
    ]);
    outputToasts(); // Execute toast on screen.
    unset($_SESSION["_ResultMessage"]); // Dispose
}

?>

</html>