<?php
session_start(); // Start the session at the top of your file
$CURRENT_PAGE = "sections";

require_once(__DIR__ . '../../../../config/PathsHandler.php');
require_once(FILE_PATHS['DATABASE']);
require_once(FILE_PATHS['Controllers']['Section']);
require_once(FILE_PATHS['Partials']['Widgets']['Card']);
require_once(FILE_PATHS['Functions']['SessionChecker']);
checkUserAccess(['Admin']);

$widget_card = new Card();

// Create a new instance of the Database class
$database = new Database();
$db = $database->getConnection();// Establish the database connectio

$database = new Database();
$db = $database->getConnection(); // Establish the database connection

$sectionController = new SectionController();
$sectionList = $sectionController->getAllSections(); // Fetch all sections



// At the beginning of your main file
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
    $addSectionResult = $sectionController->addSection($sectionData);

    // Redirect to the same page or handle as needed
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}


$sql = "
    SELECT 
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
    LEFT JOIN programs p ON s.program_id = p.program_id;
";

try {
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
?>

<!DOCTYPE html>
<html lang="en">
<?php require_once(FILE_PATHS['Partials']['User']['Head']) ?>

<body>
    <?php require_once(FILE_PATHS['Partials']['User']['Navbar']) ?>

    <section class="d-flex justify-content-between gap-2 box-sizing-border-box m-0 p-0">
        <!-- SIDEBAR -->
        <?php require_once(FILE_PATHS['Partials']['User']['Sidebar']) ?>
        <!-- content here -->
        <section id="contentSection">
            <div class="col box-sizing-border-box flex-grow-1">
                <!-- First row, first column -->
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
                                    class="btn btn-primary btn-sm rounded fs-6 px-3 c-primary d-flex gap-3 align-items-center"
                                    data-bs-toggle="modal" data-bs-target="#sectionFormModal">
                                    <i class="bi bi-plus-circle"></i> Add Section
                                </button>

                                <!-- Reload Button -->
                                <button
                                    class="btn btn-outline-primary btn-sm rounded fs-5 px-2 c-primary d-flex gap-2 align-items-center">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </button>

                                <!-- View Type -->
                                <div class="btn-group" id="viewTypeContainer">
                                    <button id="btnViewTypeCatalog" type="button"
                                        class="btn btn-sm btn-primary c-primary px-2">
                                        <i class="bi bi-card-heading fs-6"></i>
                                    </button>
                                    <button id="btnViewTypeTable" type="button"
                                        class="btn btn-sm btn-outline-primary c-primary px-2">
                                        <i class="bi bi-table fs-6"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div>
                            <!-- Filter Tools -->
                        </div>
                    </div>

                    <!-- Catalog View -->
                    <div id="data_view_catalog"
                        class="d-flex flex-row justify-content-between align-items-start gap-2 flex-wrap">
                        <?php
                        foreach ($sectionList as $section) {
                            echo $widget_card->Create(
                                'small',
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
                                            'data' => 'Students: 4',
                                        ],
                                    ],
                                ]
                            );
                        }
                        ?>
                    </div>


                    <!-- Table View -->
                    <div id="data_view_table" class="d-none">
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


            </div>

            <div id="widgetPanel">
                <!-- CALENDAR -->
                <?php require_once(FILE_PATHS['Partials']['User']['Calendar']) ?>
                <!-- TASKS -->
                <?php require_once(FILE_PATHS['Partials']['User']['Tasks']) ?>
            </div>
        </section>

    </section>

    <section>

    </section>

    <?php require_once(FILE_PATHS['Partials']['HighLevel']['Modals']['Section']['Add']) ?>
    <?php require_once(FILE_PATHS['Partials']['HighLevel']['Modals']['Section']['Details']) ?>
    <?php require_once(FILE_PATHS['Partials']['HighLevel']['Modals']['Section']['Config']) ?>

    <!-- FOOTER -->
    <?php require_once(FILE_PATHS['Partials']['User']['Footer']) ?>
</body>
<script src="<?php asset('js/admin-main.js') ?>"></script>


</html>