<?php
session_start();
$CURRENT_PAGE = "programs";

require_once(__DIR__ . '../../../../config/PathsHandler.php');
require_once(FILE_PATHS['DATABASE']);
require_once(FILE_PATHS['Controllers']['Program']);
require_once(FILE_PATHS['Partials']['Widgets']['Card']);
require_once(FILE_PATHS['Functions']['SessionChecker']);
checkUserAccess(['Admin']);

$widget_card = new Card();

// Create a new instance of the Database class
$database = new Database();
$db = $database->getConnection(); // Establish the database connection

$programController = new ProgramController($db); // Create UserController instance
// Fetch the list of programs
$programList = $programController->showPrograms();


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

    $message = $programController->addProgram($programData);
    $programList = $programController->showPrograms();  // This will return the programs to the view

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
                    <div class="mb-3 row align-items-start">
                        <div class="col-4 d-flex gap-3">
                            <h5 class="ctxt-primary">Programs</h5>
                        </div>
                        <div class="col-8 d-flex justify-content-end gap-2">
                            <!-- Tools -->
                            <button
                                class="btn btn-primary btn-sm rounded fs-6 px-3 c-primary d-flex gap-3 align-items-center"
                                data-bs-toggle="modal" data-bs-target="#programFormModal">
                                <i class="bi bi-plus-circle"></i> Add Program
                            </button>
                            <button
                                class="btn btn-outline-primary btn-sm rounded fs-5 px-2 c-primary d-flex gap-2 align-items-center">
                                <i class="bi bi-arrow-clockwise"></i>
                            </button>
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

                    <!-- Catalog View -->
                    <div id="data_view_catalog" class="d-flex justify-content-start align-items-start gap-2 flex-wrap">
                        <?php
                        if (!empty($programList)) {
                            foreach ($programList as $program) {
                                echo $widget_card->Create(
                                    'small',
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
                                    ]
                                );
                            }
                        } else {
                            echo '<p class="text-danger">No programs available.</p>';
                        }
                        ?>
                    </div>

                    <!-- Table View -->
                    <div id="data_view_table" class="d-none">
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
                                if (!empty($programList)) {
                                    foreach ($programList as $program) {
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
            </div>
        </section>
    </section>


</body>

</html>

<?php require_once(FILE_PATHS['Partials']['HighLevel']['Modals']['Program']['Add']) ?>

<!-- FOOTER -->
<?php require_once(FILE_PATHS['Partials']['User']['Footer']) ?>
</body>
<script src="<?php echo asset('js/admin-main.js') ?>"></script>

</html>

<script>
    $(document).ready(function() {
        // Check for error or success messages from the server
        <?php if (isset($_SESSION['message'])): ?>
            $('#notification').addClass('alert-success').text("<?php echo $_SESSION['message']; ?>").fadeIn().delay(3000).fadeOut();
            <?php unset($_SESSION['message']); ?>
        <?php elseif (isset($_SESSION['error'])): ?>
            $('#notification').addClass('alert-danger').text("<?php echo $_SESSION['error']; ?>").fadeIn().delay(3000).fadeOut();
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
    });
</script>