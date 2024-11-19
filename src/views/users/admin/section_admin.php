<?php
session_start(); // Start the session at the top of your file
$CURRENT_PAGE = "sections";

require_once(__DIR__ . '../../../../config/PathsHandler.php');
require_once(FILE_PATHS['DATABASE']);
require_once(FILE_PATHS['Controllers']['Section']);
require_once(FILE_PATHS['Controllers']['Program']);
require_once(FILE_PATHS['Controllers']['StudentSection']);
require_once(FILE_PATHS['Controllers']['SubjectSection']);
require_once(FILE_PATHS['Controllers']['User']);

require_once(FILE_PATHS['Partials']['Widgets']['Card']);
require_once(FILE_PATHS['Partials']['Widgets']['DataTable']);

require_once(FILE_PATHS['Functions']['SessionChecker']);
require_once(FILE_PATHS['Functions']['UpdateURLParams']);
require_once(FILE_PATHS['Functions']['ToastLogger']);
require_once(FILE_PATHS['Functions']['PHPLogger']);


checkUserAccess(['Admin', 'Level Coordinator', 'Teacher']);

$widget_card = new Card();

$database = new Database();
$db = $database->getConnection(); // Establish the database connection

$sectionController = new SectionController();
$sectionList = $sectionController->getAllSections(); // Fetch all sections
$sectionList = $sectionController->updateAcademicPeriod();

$programController = new ProgramController($db);
$studentSectionController = new StudentSectionController($db);
$subjectSectionController = new SubjectSectionController($db);
$userController = new UserController($db);

// At the beginning of your main file
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'addSection') {
    // Collect and sanitize form inputs
    $sectionData = [
        'section_name' => $_POST['section_name'],
        'educational_level' => $_POST['educational_level'],
        'program_id' => $_POST['program_id'],
        'year_level' => $_POST['year_level'],
        'semester' => $_POST['semester'],
        'adviser_id' => $_POST['adviser_id'],
        // Handle file upload for section_image if needed
        'section_image' => $_FILES['section_image'] ?? null // Adjust as necessary for file handling
    ];

    // Call the method in your SectionController to handle the insert
    $_SESSION["_ResultMessage"] = $sectionController->addSection();

    // Redirect to the same page to prevent resubmissions of forms.
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit();
} elseif (isset($_POST['search_type'])) {
    // AJAX handling for search functionality
    $searchType = $_POST['search_type'];
    $searchQuery = $_POST['query'];
    $educationalLevel = $_POST['educational_level'] ?? '';
    $response = $userController->fetchSearchTeacher($searchType, $searchQuery, $educationalLevel);
    echo json_encode($response);
    exit();
}

try {
    $sql = "
    SELECT 
		s.section_id,
        s.section_name, 
        s.year_level, 
        s.semester,
        s.section_image, 
        u.first_name, 
        u.last_name, 
        p.program_code, 
        p.educational_level
    FROM 
        section s
    LEFT JOIN users u ON s.adviser_id = u.user_id
    LEFT JOIN programs p ON s.program_id = p.program_id;";

    // Prepare and execute the SQL statement
    $stmt = $db->prepare($sql);
    $stmt->execute();

    // Fetch the results into an associative array
    $sectionList = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Handle error (you can also log the error message)
    echo json_encode(['error' => 'Query failed: ' . $e->getMessage()]);
    exit();
}

if (isset($_GET['viewSection'])) {
    $retrievedSection = $sectionController->getSectionById($_GET['viewSection']);
    if (!$retrievedSection['success']) {
        $_SESSION["_ResultMessage"] = $retrievedSection;
        header("Location: " . clearUrlParams());
        exit();
    }

    $enrolledProgramsOfSection = $programController->getProgramById($retrievedSection['data']['program_id']);
    if (!$enrolledProgramsOfSection['success']) {
        $_SESSION["_ResultMessage"] = $enrolledProgramsOfSection;
        header("Location: " . clearUrlParams());
        exit();
    }

    $enrolledAdviserToSection = $userController->getUserById($retrievedSection['data']['adviser_id']);
    $enrolledStudentIdsFromSection = $studentSectionController->getAllEnrolledStudentIdBySectionId($_GET['viewSection']); // [student id ...]
    $enrolledStudentInfoFromSection = [];
    if ($enrolledStudentIdsFromSection['success']) {
        foreach ($enrolledStudentIdsFromSection['data'][0] as $key => $user_id) {
            $getUserInfoResult = $userController->getUserById($user_id);
            if ($getUserInfoResult['success']) {
                $enrolledStudentInfoFromSection[] = $getUserInfoResult['data'];
            }
        }
    }
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
                    <?php if (!isset($_GET['viewSection'])): ?>
                        <div class="bg-white rounded p-3 shadow-sm border">
                            <!-- Headers -->
                            <div>
                                <div class="mb-3 row align-items-start">
                                    <div class="col-4 d-flex gap-3">
                                        <h5 class="ctxt-primary">Sections</h5>
                                    </div>
                                    <div class="col-8 d-flex justify-content-end gap-2">
                                        <!-- Tools -->

                                        <!-- Add New Button -->
                                        <button
                                            class="btn btn-primary btn-lg rounded fs-6 px-3 c-primary d-flex gap-3 align-items-center"
                                            data-bs-toggle="modal" data-bs-target="#sectionFormModal">
                                            <i class="bi bi-plus-circle"></i> Add Section
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
                                <div>
                                    <!-- Filter Tools -->
                                </div>
                            </div>

                            <!-- Catalog View -->
                            <div preview-container-name="view_catalog" preview-container-default id="data_view_catalog">
                                <div class="mb-3 pe-2 box-sizing-border-box">
                                    <div class="fs-6 fst-italic text-danger text-start">
                                        <i class="bi bi-question-circle-fill"></i>
                                        Only active sections are shown in catalog view.
                                    </div>
                                </div>
                                <section class="d-flex flex-row justify-content-between align-items-start gap-2 flex-wrap">
                                    <?php
                                    foreach ($sectionList as $section) {
                                        $getTotalEnrollees = $studentSectionController->getTotalEnrolleesInSection($section['section_id']);
                                        $finalTotalEnrollees = $getTotalEnrollees['success'] ? $getTotalEnrollees['data'] : 0;
                                        echo $widget_card->Create(
                                            3,
                                            "section_" . $section['section_name'],
                                            !empty($section['section_image']) ? 'data:image/jpeg;base64,' . base64_encode($section['section_image']) : null,
                                            [
                                                "title" => $section['section_name'],
                                                "others" => [
                                                    [
                                                        'hint' => 'Section Adviser',
                                                        'icon' => '<i class="bi bi-person-fill"></i>',
                                                        'data' => $section['first_name'] . ' ' . $section['last_name'],
                                                    ],
                                                    [
                                                        'hint' => 'Section Program',
                                                        'icon' => '<i class="bi bi-archive-fill"></i>',
                                                        'data' => $section['educational_level'] . ' - ' . $section['year_level'],
                                                    ],
                                                    [
                                                        'hint' => 'Number of Students',
                                                        'icon' => '<i class="bi bi-people-fill"></i>',
                                                        'data' => "$finalTotalEnrollees Students Enrolled",
                                                    ],
                                                ],
                                            ],
                                            false,
                                            true,
                                            updateUrlParams(['viewSection' => $section['section_id']])
                                        );
                                    }
                                    ?>
                                </section>
                            </div>


                            <!-- Table View -->
                            <div preview-container-name="view_table" id="data_view_table" class="d-none">
                                <table class="c-table table">
                                    <thead>
                                        <tr>
                                            <th>Section Name</th>
                                            <th>Adviser</th>
                                            <th>Program</th>
                                            <th>Year Level</th>
                                            <th>Semester</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($sectionList as $section) { ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($section['section_name']); ?></td>
                                                <td><?php echo htmlspecialchars($section['first_name'] . ' ' . $section['last_name']); ?>
                                                </td>
                                                <td><?php echo htmlspecialchars($section['program_name']); ?></td>
                                                <td><?php echo htmlspecialchars($section['year_level']); ?></td>
                                                <td><?php echo htmlspecialchars($section['semester']); ?></td>
                                                <td>
                                                    <a href="#" class="btn btn-sm btn-primary">Configure</a>
                                                    <a href="#" class="btn btn-sm btn-danger">Delete</a>
                                                </td>
                                            </tr>
                                        <?php } ?>
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
                                        <a class="ctxt-primary" href="<?= clearUrlParams(); ?>">Sections</a>
                                        <span><i class="bi bi-caret-right-fill"></i></span>
                                        <a class="ctxt-primary" href="<?= updateUrlParams(['viewSection' => $_GET['viewSection']]) ?>">
                                            <?php echo $retrievedSection['data']['section_name']; ?>
                                        </a>
                                    </h5>
                                    <!-- end of breadcrumbs -->
                                </div>
                                <!-- <div class="col-8 d-flex justify-content-end gap-2">
                                    <button class="btn btn-success" disabled data-bs-toggle="modal" data-bs-target="#modal_updateCardModal_program">
                                        <i class="bi bi-card-image"></i>
                                        Set Image
                                    </button>
                                </div> -->
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
                                                    <h5>Section Information</h5>
                                                </div>

                                                <div class="row mb-3">
                                                    <div class="col-md-4 mb-3">
                                                        <h6>Section Name</h6>
                                                        <input updateEnabled class="form-control" type="text" disabled value="<?= htmlspecialchars($retrievedSection['data']['section_name']) ?>">
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <h6>Program Code</h6>
                                                        <input updateEnabled class="form-control" type="text" disabled value="<?= htmlspecialchars($enrolledProgramsOfSection['data'][0]['program_code']) ?>">
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <h6>Educational Level</h6>
                                                        <input updateEnabled class="form-control" type="text" disabled value="<?= htmlspecialchars($enrolledProgramsOfSection['data'][0]['educational_level']) ?>">
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-md-12 mb-3">
                                                        <h6>Description</h6>
                                                        <textarea updateEnabled class="form-control" rows="5" disabled><?= htmlspecialchars($enrolledProgramsOfSection['data'][0]['program_description']) ?></textarea>
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <div class="col-md-4 mb-3">
                                                        <h6>Semester</h6>
                                                        <input updateEnabled class="form-control" type="text" disabled value="<?= htmlspecialchars($enrolledProgramsOfSection['data'][0]['educational_level']) ?>">
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <h6>Academic Year</h6>
                                                        <input updateEnabled class="form-control" type="text" disabled value="<?= htmlspecialchars($enrolledProgramsOfSection['data'][0]['educational_level']) ?>">
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <div class="col-md-6">
                                                        <h6 class="">Class Adviser</h6>
                                                        <select class="form-select" name="" id="" disabled="disabled">
                                                            <?php if ($enrolledAdviserToSection['success']): ?>
                                                                <option value=""><?php echo $enrolledAdviserToSection['data']['first_name'] . ' ' . $enrolledAdviserToSection['data']['last_name'] ?></option>
                                                            <?php endif; ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <div class="col-md-12">
                                                        <h6 class="">Enrolled Students</h6>

                                                        <section class="role_table">
                                                            <!-- =============================================== -->
                                                            <!-- DATA TABLE BY STUDENTS -->
                                                            <div class="actionControls mb-2 p-1 bg-transparent d-flex gap-2 justify-content-end align-items-center">
                                                                <button class="btn btn-success">
                                                                    <i class="bi bi-plus-circle"></i>
                                                                    Enroll New Student
                                                                </button>
                                                                <button class="btn btn-danger" disabled>
                                                                    <i class="bi bi-trash"></i>
                                                                    Remove Selection
                                                                </button>
                                                            </div>
                                                            <table id="dataTable_enrolledStudents" class="table table-responsive table-striped border display compact" style="width: 100%">
                                                                <thead>
                                                                    <tr>
                                                                        <th><input type="checkbox" id="checkbox_selectAll" class="form-check-input" value="enrolledStudentsID_<?php echo $_GET['viewSection'] ?>"></th>
                                                                        <th>User Id</th>
                                                                        <th>Full Name</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php if (empty($enrolledStudentInfoFromSection)) { ?>
                                                                        <tr>
                                                                            <td colspan="4" class="text-center">No Enrolled Students</td>
                                                                        </tr>
                                                                    <?php } else { ?>
                                                                        <?php foreach ($enrolledStudentInfoFromSection as $userData) { ?>
                                                                            <tr>
                                                                                <td class="col-md-1"><input type="checkbox" class="form-check-input" value="<?php htmlspecialchars($userData['user_id'] ?? '') ?>"></td>
                                                                                <td class="col-md-2"><?php echo $userData['user_id'] ?></td>
                                                                                <td class="col-md-6"><?php echo $userData['first_name'] . ' ' . $userData['middle_name'] . ' ' . $userData['last_name'] ?></td>
                                                                                <td class="col-md-3">
                                                                                    <a href="users_admin.php<?php echo htmlspecialchars('?viewRole=' . $userData['role'] . '&user_id=' . $userData['user_id']) ?>" title="View" class="btn btn-success m-auto">
                                                                                        <i class="bi bi-eye-fill"></i>
                                                                                        View
                                                                                    </a>
                                                                                    <a href="#" title="Remove" class="btn btn-danger m-auto disabled">
                                                                                        <i class="bi bi-x"></i>
                                                                                        Remove
                                                                                    </a>
                                                                                </td>
                                                                            </tr>
                                                                    <?php }
                                                                    } ?>
                                                                </tbody>
                                                                <tfoot>
                                                                    <tr>
                                                                        <th></th>
                                                                        <th></th>
                                                                        <th></th>
                                                                        <th></th>
                                                                    </tr>
                                                                </tfoot>
                                                            </table>
                                                            <script>
                                                                $(document).ready(function() {
                                                                    $('#dataTable_allUsers').DataTable({
                                                                        columnDefs: [{
                                                                            "orderable": false,
                                                                            "targets": [0, 4]
                                                                        }],
                                                                        language: {
                                                                            "paginate": {
                                                                                previous: '<span class="bi bi-chevron-left"></span>',
                                                                                next: '<span class="bi bi-chevron-right"></span>'
                                                                            },
                                                                            "lengthMenu": '<select class="form-control input-sm">' +
                                                                                '<option value="5">5</option>' +
                                                                                '<option value="10">10</option>' +
                                                                                '<option value="20">20</option>' +
                                                                                '<option value="30">30</option>' +
                                                                                '<option value="40">40</option>' +
                                                                                '<option value="50">50</option>' +
                                                                                '<option value="-1">All</option>' +
                                                                                '</select> Entries per page',
                                                                        },
                                                                        initComplete: function() {
                                                                            this.api()
                                                                                .columns()
                                                                                .every(function() {
                                                                                    var column = this;

                                                                                    // Create select element and listener
                                                                                    var select = $('<select class="form-select"><option value=""></option></select>')
                                                                                        .appendTo($(column.footer()).empty())
                                                                                        .on('change', function() {
                                                                                            var val = $.fn.dataTable.util.escapeRegex($(this).val()); // Escape regex for exact matching
                                                                                            column
                                                                                                .search(val ? '^' + val + '$' : '', true, false) // Exact match with regex
                                                                                                .draw();
                                                                                        });

                                                                                    // Add list of options
                                                                                    column
                                                                                        .data()
                                                                                        .unique()
                                                                                        .sort()
                                                                                        .each(function(d, j) {
                                                                                            select.append(
                                                                                                '<option value="' + d + '">' + d + '</option>'
                                                                                            );
                                                                                        });
                                                                                });
                                                                        }
                                                                    });

                                                                    // Select All functionality
                                                                    $('#checkbox_selectAll').on('change', function() {
                                                                        const isChecked = $(this).is(':checked');
                                                                        $('#dataTable_allUsers tbody input[type="checkbox"]').prop('checked', isChecked);
                                                                    });

                                                                    // Ensure "Select All" reflects individual checkbox changes
                                                                    $('#dataTable_allUsers tbody').on('change', 'input[type="checkbox"]', function() {
                                                                        const totalCheckboxes = $('#dataTable_allUsers tbody input[type="checkbox"]').length;
                                                                        const checkedCheckboxes = $('#dataTable_allUsers tbody input[type="checkbox"]:checked').length;

                                                                        $('#checkbox_selectAll').prop('checked', totalCheckboxes === checkedCheckboxes);
                                                                    });
                                                                });
                                                            </script>


                                                            <!-- END OF DATA TABLE -->
                                                            <!-- =============================================== -->
                                                        </section>
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

        <section>

        </section>

        <?php require_once(FILE_PATHS['Partials']['HighLevel']['Modals']['Section']['Add']) ?>


        <!-- FOOTER -->
        <?php require_once(FILE_PATHS['Partials']['User']['Footer']) ?>

        <?php
        // Show Toast
        if (isset($_SESSION["_ResultMessage"])) {
            print_r($_SESSION["_ResultMessage"]);
            makeToast([
                'type' => $_SESSION["_ResultMessage"]["success"] ? 'success' : 'error',
                'message' => $_SESSION["_ResultMessage"]["message"],
            ]);
            outputToasts(); // Execute toast on screen.
            unset($_SESSION["_ResultMessage"]); // Dispose
        }

        ?>
    </div>
</body>
<script src="<?php echo asset('js/preview-handler.js') ?>"></script>
<script src="<?php echo asset('js/toast.js') ?>"></script>


</html>