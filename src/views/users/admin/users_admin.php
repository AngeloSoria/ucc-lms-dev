<?php
session_start();
$CURRENT_PAGE = 'users';

require_once(__DIR__ . '../../../../config/PathsHandler.php');
require_once(FILE_PATHS['DATABASE']);
require_once(FILE_PATHS['Controllers']['User']);
require_once(FILE_PATHS['Partials']['Widgets']['Card']);
require_once(FILE_PATHS['Functions']['ToastLogger']);

require_once(FILE_PATHS['Functions']['SessionChecker']);
checkUserAccess(['Admin']);

$widget_card = new Card();

// Create a new instance of the Database class
$database = new Database();
$db = $database->getConnection(); // Establish the database connection

// Create an instance of the UserController
$userController = new UserController($db);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'addUser') {
    // Collect user data from form inputs
    $userData = [
        'user_id' => $_POST['user_id'],
        'role' => $_POST['role'],
        'first_name' => $_POST['first_name'],
        'middle_name' => $_POST['middle_name'] ? $_POST['middle_name'] : NULL,
        'last_name' => $_POST['last_name'],
        'gender' => $_POST['gender'],
        'dob' => $_POST['dob'],
        'username' => $_POST['username'],
        'password' => password_hash($_POST['password'], PASSWORD_DEFAULT), // Hash the password
        'profile_pic' => isset($_FILES['profile_pic']) ? $_FILES['profile_pic'] : NULL,
        'educational_level' => $_POST['educational_level'] ?? null
    ];

    // Call the controller to add the user
    $_SESSION["_ResultMessage"] = $userController->addUser($userData);

    // Redirect to the same page to prevent resubmission
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit();
}

// View mode
$viewMode = false;
if (isset($_GET['view'])) {
    $viewMode = true;
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
                <?php if ($viewMode === false): ?>
                    <div class="bg-white rounded p-3 shadow-sm border">
                        <!-- Headers -->
                        <div class="mb-3 row align-items-start">
                            <div class="col-4 d-flex gap-3">
                                <h5 class="ctxt-primary">Users</h5>
                            </div>
                            <div class="col-8 d-flex justify-content-end gap-2">
                                <!-- Tools -->

                                <!-- Add New Button -->
                                <button
                                    class="btn btn-primary btn-sm rounded fs-6 px-3 c-primary d-flex gap-3 align-items-center"
                                    data-bs-toggle="modal" data-bs-target="#userFormModal"
                                    onclick="apply_section_modal(this);">
                                    <i class="bi bi-plus-circle"></i> Add User
                                </button>

                                <!-- Reload Button -->
                                <button
                                    class="btn btn-outline-primary btn-sm rounded fs-5 px-2 c-primary d-flex gap-2 align-items-center">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </button>

                                <!-- Preview Type -->
                                <div class="btn-group" id="previewTypeContainer">
                                    <a id="btnPreviewTypeCatalog" type="button" preview-btn-name="catalog"
                                        class="btn btn-sm btn-primary c-primary px-2 d-flex justify-content-center align-items-center">
                                        <i class="bi bi-card-heading fs-6"></i>
                                    </a>
                                    <a id="btnPreviewTypeTable" type="button" preview-btn-name="table"
                                        class="btn btn-sm btn-outline-primary c-primary px-2 d-flex justify-content-center align-items-center">
                                        <i class="bi bi-table fs-6"></i>
                                    </a>
                                </div>

                            </div>
                        </div>


                        <!-- Catalog View -->
                        <div id="data_view_catalog" preview-container-default-view preview-container-name="view_catalog"
                            class="d-flex justify-content-start align-items-start gap-2 flex-wrap">
                            <?php
                            $RETRIEVED_USERS = $userController->getAllUsers(); // will return dictionary of users from database
                            // [user_id, first_name, middle_name, last_name, role, gender, dob, status]

                            // Calculate the total number of users for "All"
                            $total_users = count($RETRIEVED_USERS);

                            // Group the users based on their roles.
                            // Initialize an empty array to hold groups
                            $ROLE_GROUPS = [];

                            foreach ($RETRIEVED_USERS as $single_user) {
                                // Get the role of the current item
                                $role = $single_user['role'];

                                // Check if the role is already a key in groups
                                if (!array_key_exists($role, $ROLE_GROUPS)) {
                                    // If not, initialize an empty array for this role
                                    $ROLE_GROUPS[$role] = [];
                                }

                                // Append the current item to the list for this role
                                $ROLE_GROUPS[$role][] = $single_user;
                            }

                            // Loop through each role in $ROLE_GROUPS and display the widget with user count
                            foreach ($ROLE_GROUPS as $role => $role_data) {
                                echo $widget_card->Create(
                                    'small',
                                    "rolegroup_" . $role,
                                    null,
                                    [
                                        "title" => $role,
                                        "others" => [
                                            [
                                                'hint' => 'Total ' . $role,
                                                'icon' => '<i class="bi bi-person-fill"></i>',
                                                'data' => 'Users: ' . number_format(count($role_data) ?? 0), // Display user count for this role
                                            ],
                                        ],
                                    ],
                                    true
                                );
                            }


                            ?>
                        </div>
                        <!-- Table View -->
                        <div id="data_view_table" preview-container-name="view_table"
                            class="d-none">
                            <table class="c-table table">
                                <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" name="checkbox_data_selectAll"
                                                id="checkbox_data_selectAll" class="form-check-input">
                                        </th>
                                        <th>Role</th>
                                        <th>User ID</th>
                                        <th>Username</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Age</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="<data_context>"
                                                id="checkbox_data_select-<data_context>" class="form-check-input">
                                        </td>
                                        <td>qwe</td>
                                        <td>qwe</td>
                                        <td>qwe</td>
                                    </tr>
                                </tbody>
                            </table>
                            <!-- Pagination -->
                            <div class="d-flex gap-2 align-items-center justify-content-start">
                                <div class="d-flex align-items-center gap-1">
                                    <span>Show:</span>
                                    <select id="data_view_table_show_per_page" class="form-select">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                    </select>
                                </div>
                                <nav aria-label="Page navigation example">
                                    <ul class="pagination">
                                        <li class="page-item">
                                            <a class="page-link" href="#" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                                        <li class="page-item">
                                            <a class="page-link" href="#" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>


                    </div>
                <?php else: ?>
                    <div class="bg-white rounded p-3 shadow-sm border">
                        <div class="mb-3 row align-items-start bg-transparent box-sizing-border-box">
                            <div class="col-4 d-flex gap-2 justify-content-start align-items-center box-sizing-border-box">
                                <!-- breadcrumbs -->
                                <h5 class="ctxt-primary p-0 m-0">
                                    <a class="ctxt-primary" href="users_admin.php">Users</a>
                                    /
                                    <a class="ctxt-primary" href="users_admin.php?view=admin">Admin</a>
                                </h5>
                            </div>
                            <div class="col-8 d-flex justify-content-end gap-2">
                                <!-- Tools -->

                                <!-- Add New Button -->
                                <!-- <button
                                    class="btn btn-primary btn-sm rounded fs-6 px-3 c-primary d-flex gap-3 align-items-center"
                                    data-bs-toggle="modal" data-bs-target="#userFormModal"
                                    onclick="apply_section_modal(this);">
                                    <i class="bi bi-plus-circle"></i> Add User
                                </button> -->

                                <!-- Reload Button -->
                                <button
                                    class="btn btn-outline-primary btn-sm rounded px-2 c-primary d-flex gap-2 align-items-center">
                                    <i class="bi bi-arrow-clockwise"></i>
                                    Refresh Data
                                </button>

                                <!-- View Type -->
                                <!-- <div class="btn-group" id="previewTypeContainer">
                                    <button id="btnPreviewTypeCatalog" type="button"
                                        class="btn btn-sm btn-primary c-primary px-2">
                                        <i class="bi bi-card-heading fs-6"></i>
                                    </button>
                                    <button id="btnPreviewTypeTable" type="button"
                                        class="btn btn-sm btn-outline-primary c-primary px-2">
                                        <i class="bi bi-table fs-6"></i>
                                    </button>
                                </div> -->

                            </div>
                        </div>

                        <!-- preview data -->
                        <!-- Table View -->
                        <div id="data_view_table" class="d-none">

                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <div id="widgetPanel">
                <!-- CALENDAR -->
                <?php require_once(FILE_PATHS['Partials']['User']['Calendar']) ?>
                <!-- TASKS -->
                <?php require_once(FILE_PATHS['Partials']['User']['Tasks']) ?>
            </div>
        </section>
    </section>

    <!-- ADD USER FORM POPUP -->
    <?php require_once(FILE_PATHS['Partials']['HighLevel']['Modals']['User']['Add']) ?>

    <!-- FOOTER -->
    <?php require_once(FILE_PATHS['Partials']['User']['Footer']) ?>
</body>
<script src="<?php echo asset('js/admin-main.js') ?>"></script>
<script src="<?php echo asset('js/toast.js') ?>"></script>

<?php
// Show Toast
if (isset($_SESSION["_ResultMessage"]) && $_SESSION["_ResultMessage"] != null) {
    $type = $_SESSION["_ResultMessage"][0];
    $text = $_SESSION["_ResultMessage"][1];
    makeToast([
        'type' => $type,
        'message' => $text,
    ]);
    outputToasts(); // Execute toast on screen.
    unset($_SESSION["_ResultMessage"]); // Dispose
}

?>

</html>