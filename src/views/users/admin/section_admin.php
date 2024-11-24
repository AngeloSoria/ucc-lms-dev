<?php
session_start(); // Start the session at the top of your file
$CURRENT_PAGE = "sections";

require_once(__DIR__ . '../../../../config/PathsHandler.php');
require_once(FILE_PATHS['DATABASE']);
require_once(FILE_PATHS['Controllers']['User']);
require_once(FILE_PATHS['Controllers']['Section']);
require_once(FILE_PATHS['Controllers']['Program']);
require_once(FILE_PATHS['Controllers']['StudentSection']);
require_once(FILE_PATHS['Controllers']['Subject']);
require_once(FILE_PATHS['Controllers']['SubjectSection']);
require_once(FILE_PATHS['Controllers']['AcademicPeriod']);

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


$userController = new UserController();
$sectionController = new SectionController();
$programController = new ProgramController();
$subjectController = new SubjectController();
$academicYearController = new AcademicPeriodController($db);
$studentSectionController = new StudentSectionController($db);
$subjectSectionController = new SubjectSectionController($db);

$sectionList = $sectionController->getAllSections(); // Fetch all sections
$sectionList = $sectionController->updateAcademicPeriod();


// At the beginning of your main file
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case "addSection":
                $sectionData = [
                    'section_name' => $_POST['section_name'],
                    'educational_level' => $_POST['educational_level'],
                    'program_id' => $_POST['program_id'],
                    'year_level' => $_POST['year_level'],
                    'semester' => $_POST['semester'],
                ];

                $addProgramResult = $sectionController->addSection($sectionData);
                $_SESSION["_ResultMessage"] = $addProgramResult;

                // Redirect to the same page to prevent resubmissions of forms.
                header("Location: " . $_SERVER['REQUEST_URI']);
                exit();
            case "updateSection":
                $sectionData = [
                    'section_id' => $_POST['section_id'],
                    'section_name' => $_POST['input_sectionName'],
                    'program_id' => $_POST['input_sectionProgram'],
                    'year_level' => $_POST['input_sectionYearLevel'],
                    'adviser_id' => $_POST['input_sectionAdviser'],
                    'teacher_id' => $_POST['input_sectionAdviser'],
                    'student_ids' => $_POST['input_modalAddToEnrollStudents'],
                    'enrollment_type' => $_POST['input_sectionEnrollmentType'],
                    'subject_ids' => $_POST['input_addToEnrollSubjects'],
                ];

                // enroll student
                $studentSectionController->addStudentsToSection($sectionData);

                // enroll subjects.
                $result2 = $subjectSectionController->addSubjectSection($sectionData);

                // update section
                $result1 = $sectionController->updateSectionById($sectionData['section_id'], $sectionData);

                $_SESSION['_ResultMessage'] = $result1;
                $_SESSION['_ResultMessage'] = $result2;


                // Redirect to the same page to prevent resubmissions of forms.
                header("Location: " . $_SERVER['REQUEST_URI']);
                exit();
            case 'updateSectionInfo':

                $sectionInfoData = [
                    'section_id' => $_GET['viewSection'],
                    'section_name' => $_POST['input_sectionName'],
                    'program_id' => $_POST['input_sectionProgram'],
                    'year_level' => $_POST['input_sectionYearLevel'],
                    'adviser_id' => $_POST['input_sectionTeacherAdviser'],
                ];

                // Check for empty required fields.
                $updateTestFailed = false;
                foreach ($sectionInfoData as $inputField => $inputValue) {
                    if (empty($inputValue) && $inputField !== 'adviser_id') {
                        $_SESSION['_ResultMessage'] = ['success' => false, 'message' => "Update section failed, no value found for $inputField."];
                        $updateTestFailed = true;
                        break;
                    }
                }

                if (!$updateTestFailed) {
                    // Submit to Controller
                    $_SESSION['_ResultMessage'] = $sectionController->updateSectionById($sectionInfoData['section_id'], $sectionInfoData);
                }

                // Redirect to the same page to prevent resubmissions of forms.
                header("Location: " . $_SERVER['REQUEST_URI']);
                exit();
            case 'updateEnrolledStudentsFromSection':
                $studentsEnrollmentToSectionData = [
                    'user_ids' => $_POST['input_AddStudentsToEnrollFromSection'],
                    'section_id' => $_GET['viewSection'],
                    'enroll'
                ];

                // TODO:
                // Redirect to the same page to prevent resubmissions of forms.
                header("Location: " . $_SERVER['REQUEST_URI']);
                exit();
        }
    } elseif (isset($_POST['search_type'])) {
        $searchType = $_POST['search_type'];
        $searchQuery = $_POST['query'];
        $additionalFilters = $_POST['additional_filters'] ?? [];

        $response = $subjectSectionController->fetchSearchResults($searchType, $searchQuery, $additionalFilters);
        echo json_encode($response);
        exit();
    }
}

try {
    $sql = "
    SELECT 
		s.section_id,
        s.section_name, 
        s.year_level, 
        s.semester,
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

    $enrolledProgramToSection = $programController->getProgramById($retrievedSection['data']['program_id']);
    if (!$enrolledProgramToSection['success']) {
        $_SESSION["_ResultMessage"] = $enrolledProgramToSection;
        header("Location: " . clearUrlParams());
        exit();
    }

    $enrolledAdviserToSection = $userController->getUserById($retrievedSection['data']['adviser_id']);
    $enrolledStudentsFromSection = $studentSectionController->getAllEnrolledStudentsBySectionId($_GET['viewSection']); // [student id ...]

    $enrolledStudentInfoFromSection = [];
    if ($enrolledStudentsFromSection['success']) {
        foreach ($enrolledStudentsFromSection['data'] as $student_info) {
            $getUserInfoResult = $userController->getUserById($student_info['student_id']); // Retrieve user info
            if ($getUserInfoResult['success']) {
                // Add enrollment_type from $student_info to the result
                $userInfoWithEnrollmentType = $getUserInfoResult['data'];
                $userInfoWithEnrollmentType['enrollment_type'] = $student_info['enrollment_type']; // Add the key-value pair

                $enrolledStudentInfoFromSection[] = $userInfoWithEnrollmentType;
            }
        }
    }

    $retrieveAcademicYearFromSection = $academicYearController->getAcademicPeriodById($retrievedSection['data']['period_id']);
    if (!$retrieveAcademicYearFromSection['success']) {
        $_SESSION["_ResultMessage"] = $retrieveAcademicYearFromSection;
        header("Location: " . clearUrlParams());
        exit();
    }

    $enrolledSubjectsFromSection = $subjectSectionController->getAllEnrolledSubjectsFromSectionBySectionId($_GET['viewSection']);
    // print_r($enrolledSubjectsFromSection);
    if (!$enrolledSubjectsFromSection['success']) {
        $_SESSION["_ResultMessage"] = $enrolledSubjectsFromSection;
        header("Location: " . clearUrlParams());
        exit();
    }

    $retrievedAllPrograms = $programController->getAllPrograms();
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
                <div class="col flex-grow-1">
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
                                        <div>
                                            <button
                                                class="btn btn-lg btn-primary rounded fs-6 px-3 c-primary d-flex gap-3 align-items-center"
                                                data-bs-toggle="modal" data-bs-target="#sectionFormModal">
                                                <i class="bi bi-plus-circle"></i> Add Section
                                            </button>
                                        </div>

                                        <!-- Preview Type -->
                                        <!-- <div class="btn-group" id="previewTypeContainer">
                                            <a id="btnPreviewTypeCatalog" type="button"
                                                preview-container-target="view_catalog"
                                                class="btn btn-sm btn-sm btn-primary c-primary px-2 d-flex justify-content-center align-items-center">
                                                <i class="bi bi-card-heading fs-6"></i>
                                            </a>
                                            <a id="btnPreviewTypeTable" type="button" preview-container-target="view_table"
                                                class="btn btn-sm btn-sm btn-outline-primary c-primary px-2 d-flex justify-content-center align-items-center">
                                                <i class="bi bi-table fs-6"></i>
                                            </a>
                                        </div> -->
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
                                <section class="row">
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
                                                    <a href="#" class="btn btn-sm btn-sm btn-primary">Configure</a>
                                                    <a href="#" class="btn btn-sm btn-sm btn-danger">Delete</a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    <?php else: ?>
                        <div class="bg-white rounded p-3 shadow-sm border w-100">
                            <div class="mb-3 row align-items-start bg-transparent box-sizing-border-box">
                                <div class="d-flex gap-2 justify-content-start align-items-center box-sizing-border-box">
                                    <!-- breadcrumbs -->
                                    <h5 class="ctxt-primary p-0 m-0">
                                        <a class="ctxt-primary" href="<?= clearUrlParams(); ?>">Sections</a>
                                        <span><i class="bi bi-caret-right-fill"></i></span>
                                        <a class="ctxt-primary"
                                            href="<?= updateUrlParams(['viewSection' => $_GET['viewSection']]) ?>">
                                            <?php echo $retrievedSection['data']['section_name']; ?>
                                        </a>
                                    </h5>
                                    <!-- end of breadcrumbs -->
                                </div>
                                <!-- <div class="col-8 d-flex justify-content-end gap-2">
                                    <button class="btn btn-sm btn-success" disabled data-bs-toggle="modal" data-bs-target="#modal_updateCardModal_program">
                                        <i class="bi bi-card-image"></i>
                                        Set Image
                                    </button>
                                </div> -->
                            </div>

                            <!-- Configure View -->
                            <?php require_once(FILE_PATHS['Partials']['HighLevel']['Configures'] . 'config_Section.php') ?>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
        </section>

        <?php require_once(FILE_PATHS['Partials']['HighLevel']['Modals']['Section']['Add']) ?>


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