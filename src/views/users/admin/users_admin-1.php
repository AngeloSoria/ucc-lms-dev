<?php
session_start();
$CURRENT_PAGE = 'users';

require_once(__DIR__ . '../../../../config/PathsHandler.php');
require_once(FILE_PATHS['DATABASE']);
require_once(FILE_PATHS['Controllers']['User']);
require_once(FILE_PATHS['Controllers']['CardImages']);

require_once(FILE_PATHS['Partials']['Widgets']['Card']);
require_once(FILE_PATHS['Partials']['Widgets']['DataTable']);

require_once(FILE_PATHS['Functions']['ToastLogger']);
require_once(FILE_PATHS['Functions']['SessionChecker']);
require_once(FILE_PATHS['Functions']['UpdateURLParams']);
require_once(FILE_PATHS['Functions']['CalculateElapsedTime']);

checkUserAccess(['Admin', 'Level Coordinator']);


$widget_card = new Card();

// Create a new instance of the Database class
$database = new Database();
$db = $database->getConnection(); // Establish the database connection

// Create an instance of the UserController
$userController = new UserController();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'addUser':
                // Collect user data from form inputs
                $userData = [
                    'user_id' => $_POST['user_id'],
                    'role' => $_POST['role'],
                    'first_name' => $_POST['first_name'],
                    'middle_name' => $_POST['middle_name'] ?? null,
                    'last_name' => $_POST['last_name'],
                    'gender' => $_POST['gender'],
                    'dob' => $_POST['dob'],
                    'username' => $_POST['username'],
                    'password' => password_hash($_POST['password'], PASSWORD_DEFAULT), // Hash the password
                    'educational_level' => $_POST['educational_level'] ?? null,
                    'profile_pic' => null, // Placeholder for the binary image
                ];

                // Handle file upload
                if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
                    $imageTmpPath = $_FILES['profile_pic']['tmp_name'];
                    $imageData = file_get_contents($imageTmpPath); // Read the binary data
                    $userData['profile_pic'] = $imageData; // Add the binary data to the user data
                    $userData['temp_img_pic'] = $_FILES['profile_pic'];
                }

                // Save user data to the database using a controller method
                $_SESSION["_ResultMessage"] = $userController->addUser($userData);

                // Redirect to the same page to prevent resubmission
                header("Location: " . $_SERVER['REQUEST_URI']);
                exit();

            case "updateUserInfo":
                $userData = [
                    'user_id' => $_GET['user_id'],
                    'first_name' => $_POST['first_name'],
                    'middle_name' => $_POST['middle_name'] ?? null,
                    'last_name' => $_POST['last_name'],
                    'gender' => $_POST['gender'],
                    'dob' => $_POST['dob'],
                    'status' => $_POST['userStatus'],
                    'requirePasswordReset' => $_POST['requirePasswordReset'],
                ];

                // Password update check
                if (isset($_POST['password'])) {
                    $userData['password'] = $_POST['password'];
                }

                $_SESSION["_ResultMessage"] = $userController->updateUserProfile($userData['user_id'], $userData);
                header("Location: " . $_SERVER['REQUEST_URI']);
                exit();

            case "deleteUser":
                $_SESSION["_ResultMessage"] = $userController->deleteUser($_POST['user_id']);
                header("Location: " . clearUrlParams());
                exit();
            case 'importUser':
                if (isset($_FILES['userFile']['tmp_name'])) {
                    $filePath = $_FILES['userFile']['tmp_name'];
                    $result = $userController->uploadUsersFromExcel($filePath);

                    // Log the result or display a success/failure message
                    $_SESSION["_ResultMessage"] = $result;
                    msgLog('result message', $_SESSION["_ResultMessage"][0]['message']);
                    // Redirect to prevent form resubmission
                    header("Location: " . $_SERVER['REQUEST_URI']);
                    exit();
                }
        }
    }
}

// Check if the requested view is valid
if (isset($_GET['viewRole']) && isset($_GET['user_id'])) {
    if (!in_array(strtolower($_GET['viewRole']), $userController->getValidRoles())) {
        if (!is_int($_GET['user_id'])) {
            header('Location: ' . clearUrlParams());
            exit();
        }
    }
} elseif (isset($_GET['viewRole'])) {
    if (!in_array(strtolower($_GET['viewRole']), $userController->getValidRoles())) {
        header('Location: ' . clearUrlParams());
        exit();
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
            <section class="container-fluid mt-2">
                <div class="box-sizing-border-box flex-grow-1">
                    <?php if (!isset($_GET['viewRole'])): ?>
                        <div class="container-fluid bg-white rounded p-3 shadow-sm border">
                            <!-- Headers -->
                            <div class="mb-3 row align-items-start">
                                <div class="col-4 d-flex gap-3">
                                    <h5 class="ctxt-primary">Users</h5>
                                </div>
                                <div class="col-8 d-flex justify-content-end gap-2">
                                    <!-- Tools -->

                                    <!-- Import Button -->
                                    <button
                                        class="btn btn-secondary btn-lg rounded fs-6 px-3 c-primary d-flex gap-3 align-items-center"
                                        data-bs-toggle="modal" data-bs-target="#importModal">
                                        <i class="bi bi-upload"></i> Import
                                    </button>
                                    <div class="modal fade" id="importModal" tabindex="-1"
                                        aria-labelledby="importModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form method="POST" enctype="multipart/form-data" action="">
                                                    <!-- Modal Header -->
                                                    <input type="hidden" name="action" value="importUser">

                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="importModalLabel">Import Data</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>

                                                    <!-- Modal Body -->
                                                    <div class="modal-body">
                                                        <input type="file" class="form-control" id="importFile"
                                                            name="userFile" required />
                                                        <small class="text-muted">Upload a CSV or Excel file to import
                                                            users.</small>
                                                    </div>

                                                    <!-- Modal Footer -->
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Import</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <button
                                        class="btn btn-success btn-lg rounded fs-6 px-3 c-primary d-flex gap-3 align-items-center"
                                        onclick="exportData()">
                                        <i class="bi bi-download"></i> Export
                                    </button>

                                    <!-- Add New Button -->
                                    <button
                                        class="btn btn-primary btn-lg rounded fs-6 px-3 c-primary d-flex gap-3 align-items-center"
                                        data-bs-toggle="modal" data-bs-target="#userFormModal" apply_section_modal>
                                        <i class="bi bi-plus-circle"></i> Add User
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
                            <div preview-container-name="view_catalog" preview-container-default class="row">
                                <?php
                                $RETRIEVED_USERS = $userController->getAllUsers(); // will return dictionary of users from database
                                // [user_id, first_name, middle_name, last_name, role, gender, dob, status]
                                if ($RETRIEVED_USERS['success'] == false) {
                                    $_SESSION['_ResultMessage'] = $RETRIEVED_USERS['message'];
                                } else {
                                    $RETRIEVED_USERS = $RETRIEVED_USERS['data'];
                                }

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
                                        2,
                                        $role,
                                        null,
                                        [
                                            "title" => $role,
                                            "others" => [
                                                [
                                                    'hint' => 'Total ' . $role,
                                                    'icon' => '<i class="bi bi-person-fill"></i>',
                                                    'data' => number_format(count($role_data) ?? 0) . ' Users', // Display user count for this role
                                                ],
                                            ],
                                        ],
                                        false,
                                        true,
                                        updateUrlParams(['viewRole' => $role])
                                    );
                                }


                                ?>
                            </div>
                            <!-- Table View -->
                            <div preview-container-name="view_table" class="d-none container-fluid table-responsive">
                                <?php
                                $getAllUsers = $userController->getAllUsers();
                                if ($getAllUsers['success'] == true) { ?>
                                    <!-- =============================================== -->
                                    <!-- DATA TABLE -->
                                    <div
                                        class="actionControls mb-2 p-1 bg-transparent d-flex gap-2 justify-content-end align-items-center">
                                        <button class="btn btn-danger" onclick="javascript:alert(1)">
                                            <i class="bi bi-trash"></i>
                                            Remove Selection
                                        </button>
                                    </div>

                                    <!-- Custom Filters Above the Table -->
                                    <div class="filter-controls my-3">
                                        <div class="row">
                                            <div class="col-sm-4 col-md-3">
                                                <label for="filterRole">Role</label>
                                                <select class="form-select" id="filterRole">
                                                    <option value="">Select Role</option>
                                                    <?php foreach (array_unique(array_column($getAllUsers['data'], 'role')) as $role) { ?>
                                                        <option value="<?php echo $role; ?>"><?php echo $role; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="col-sm-4 col-md-3">
                                                <label for="filterGender">Gender</label>
                                                <select class="form-select" id="filterGender">
                                                    <option value="">Select Gender</option>
                                                    <option value="male">Male</option>
                                                    <option value="female">Female</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-4 col-md-3">
                                                <label for="filterStatus">Status</label>
                                                <select class="form-select" id="filterStatus">
                                                    <option value="">Select Status</option>
                                                    <option value="active">Active</option>
                                                    <option value="inactive">Inactive</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- DataTable -->
                                    <table id="userTable" class="table table-bordered table-hover">
                                        <thead style="background-color: var(--c-brand-primary-a0) !important;">
                                            <tr>
                                                <th>
                                                    <input type="checkbox" id="checkbox_selectAll" class="form-check-input">
                                                </th>
                                                <th>User Id</th>
                                                <th>Role</th>
                                                <th>Username</th>
                                                <th>Full Name</th>
                                                <th>Gender</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (empty($getAllUsers['data'])) { ?>
                                                <tr>
                                                    <td colspan="8" class="text-center">No data available</td>
                                                </tr>
                                            <?php } else {
                                                foreach ($getAllUsers['data'] as $userData) { ?>
                                                    <tr>
                                                        <td>
                                                            <input type="checkbox" class="form-check-input"
                                                                value="<?php echo htmlspecialchars($userData['user_id'] ?? '') ?>">
                                                        </td>
                                                        <td><?php echo $userData['user_id'] ?></td>
                                                        <td><?php echo $userData['role'] ?></td>
                                                        <td><?php echo $userData['username'] ?></td>
                                                        <td><?php echo $userData['first_name'] . ' ' . $userData['last_name'] ?></td>
                                                        <td><?php echo ucfirst($userData['gender']) ?></td>
                                                        <td>
                                                            <span
                                                                class="badge <?php echo $userData['status'] == 'active' ? 'badge-primary' : 'badge-danger' ?>">
                                                                <?php echo ucfirst($userData['status']) ?>
                                                            </span>
                                                        </td>
                                                        <td class="text-center">
                                                            <a href="<?php echo htmlspecialchars(updateUrlParams(['viewRole' => $userData['role'], 'user_id' => $userData['user_id']])) ?>"
                                                                title="Configure" class="btn btn-primary btn-sm">
                                                                <i class="bi bi-pencil-square"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php }
                                            } ?>
                                        </tbody>
                                    </table>
                                    <script>
                                        $(document).ready(function () {
                                            // Initialize DataTable with no sorting by default
                                            var table = $('#userTable').DataTable({
                                                paging: true,
                                                searching: true,
                                                ordering: true,
                                                order: [],
                                            });

                                            // Filter by Role
                                            $('#filterRole').on('change', function () {
                                                var selectedValue = $(this).val();
                                                if (selectedValue) {
                                                    table.column(2) // Role column (index 2)
                                                        .search(selectedValue)
                                                        .draw();
                                                } else {
                                                    table.column(2).search('').draw(); // Clear filter
                                                }
                                            });

                                            // Filter by Gender
                                            $('#filterGender').on('change', function () {
                                                var selectedValue = $(this).val();
                                                if (selectedValue) {
                                                    table.column(5) // Gender column (index 5)
                                                        .search('^' + selectedValue + '$', true, false) // Use regular expression for exact match
                                                        .draw();
                                                } else {
                                                    table.column(5).search('').draw(); // Clear filter
                                                }
                                            });

                                            // Filter by Status
                                            $('#filterStatus').on('change', function () {
                                                var selectedValue = $(this).val();
                                                if (selectedValue) {
                                                    table.column(6) // Status column (index 6)
                                                        .search(selectedValue)
                                                        .draw();
                                                } else {
                                                    table.column(6).search('').draw(); // Clear filter
                                                }
                                            });

                                            // Select All functionality
                                            $('#checkbox_selectAll').on('change', function () {
                                                const isChecked = $(this).is(':checked');
                                                $('#userTable tbody input[type="checkbox"]').prop('checked', isChecked);
                                            });

                                            // Ensure "Select All" reflects individual checkbox changes
                                            $('#userTable tbody').on('change', 'input[type="checkbox"]', function () {
                                                const totalCheckboxes = $('#userTable tbody input[type="checkbox"]').length;
                                                const checkedCheckboxes = $('#userTable tbody input[type="checkbox"]:checked').length;

                                                $('#checkbox_selectAll').prop('checked', totalCheckboxes === checkedCheckboxes);
                                            });
                                        });
                                    </script>
                                    <!-- END OF DATA TABLE -->
                                    <!-- =============================================== -->
                                <?php } else {
                                    $_SESSION["_ResultMessage"] = $getAllUsers['message'];
                                } ?>
                            </div>



                        </div>
                    <?php elseif (isset($_GET['viewRole']) && !isset($_GET['user_id'])): ?>
                        <div class="bg-white rounded p-3 shadow-sm border">
                            <div class="mb-3 row align-items-start bg-transparent box-sizing-border-box">
                                <div
                                    class="col-4 d-flex gap-2 justify-content-start align-items-center box-sizing-border-box">
                                    <!-- breadcrumbs -->
                                    <h5 class="ctxt-primary p-0 m-0">
                                        <a class="ctxt-primary" href="<?= clearUrlParams(); ?>">Users</a>
                                        <?php if (isset($_GET['viewRole'])) { ?>
                                            <span><i class="bi bi-caret-right-fill"></i></span>
                                            <a class="ctxt-primary"
                                                href="<?= updateUrlParams(['viewRole' => $_GET['viewRole']]) ?>"><?= ucfirst($_GET['viewRole']) ?></a>
                                        <?php } ?>
                                        <?php if (isset($_GET['viewRole']) && isset($_GET['user_id'])) { ?>
                                            <?php
                                            $retrieved_user = $userController->getUserById($_GET['user_id']);
                                            if (!isset($_SESSION["_ResultMessage"])) {
                                                if ($retrieved_user['success'] == false) {
                                                    $_SESSION["_ResultMessage"] = $retrieved_user['message'];
                                                }
                                            }
                                            ?>
                                            <span><i class="bi bi-caret-right-fill"></i></span>
                                            <a class="ctxt-primary"
                                                href="<?= updateUrlParams(['viewRole' => $_GET['viewRole'], 'user_id' => $_GET['user_id']]) ?>">
                                                <?php ucfirst($retrieved_user['data']['user_id']) ?>
                                            </a>
                                        <?php } ?>
                                    </h5>
                                </div>
                                <div class="col-8 d-flex justify-content-end gap-2">
                                    <!-- <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modal_updateCardModal_role">
                                        <i class="bi bi-card-image"></i>
                                        Set Image
                                    </button> -->
                                </div>
                            </div>
                            <hr>
                            <section class="role_table container">
                                <?php
                                $getAllUsers = $userController->getAllUsersByRole(strtolower($_GET['viewRole']));
                                if ($getAllUsers['success'] == true) { ?>
                                    <!-- =============================================== -->
                                    <!-- DATA TABLE BY ROLES -->
                                    <div
                                        class="actionControls mb-2 p-1 bg-transparent d-flex gap-2 justify-content-end align-items-center">
                                        <button class="btn btn-danger" onclick="">
                                            <i class="bi bi-trash"></i>
                                            Remove Selection
                                        </button>
                                    </div>

                                    <div class="container mt-4">
                                        <!-- Filters -->
                                        <div class="filters mb-3">
                                            <select id="filterRole" class="form-select d-inline w-auto">
                                                <option value="">Select Role</option>
                                                <option value="Admin">Admin</option>
                                                <option value="Level Coordinator">Level Coordinator</option>
                                                <option value="Teacher">Teacher</option>
                                                <option value="Student">Student</option>
                                            </select>

                                            <select id="filterGender" class="form-select d-inline w-auto">
                                                <option value="">Select Gender</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                            </select>

                                            <select id="filterStatus" class="form-select d-inline w-auto">
                                                <option value="">Select Status</option>
                                                <option value="Active">Active</option>
                                                <option value="Inactive">Inactive</option>
                                            </select>
                                        </div>

                                        <!-- Table -->
                                        <table id="userTable" class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th><input class="form-check-input" type="checkbox" id="checkAll"></th>
                                                    <th>User Id</th>
                                                    <th>Role</th>
                                                    <th>Username</th>
                                                    <th>Full Name</th>
                                                    <th>Gender</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($getAllUsers['data'] as $user): ?>
                                                    <tr>
                                                        <td><input type="checkbox" class="form-check-input"></td>
                                                        <td><?php echo htmlspecialchars($user['user_id']); ?></td>
                                                        <td><?php echo htmlspecialchars($user['role']); ?></td>
                                                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                                                        <td><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['middle_name'] . ' ' . $user['last_name']); ?>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($user['gender']); ?></td>
                                                        <td><?php echo htmlspecialchars($user['status']); ?></td>
                                                        <td><a
                                                                href="<?php echo updateUrlParams(['viewRole' => $_GET['viewRole'], 'user_id' => $user['user_id']]) ?>"><button
                                                                    class="btn btn-primary">Edit</button></a></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>

                                    <script>
                                        $(document).ready(function () {
                                            // Initialize DataTable with no sorting by default
                                            var table = $('#userTable').DataTable({
                                                paging: true,
                                                searching: true, // Disable global searching by default
                                                ordering: true, // Enable ordering but set default order to none
                                                order: [], // Prevent default ordering (no initial sort)
                                            });

                                            // Filter by Role
                                            $('#filterRole').on('change', function () {
                                                var selectedValue = $(this).val();
                                                if (selectedValue) {
                                                    table.column(2) // Role column (index 2)
                                                        .search(selectedValue)
                                                        .draw();
                                                } else {
                                                    table.column(2).search('').draw(); // Clear filter
                                                }
                                            });

                                            // Filter by Gender
                                            $('#filterGender').on('change', function () {
                                                var selectedValue = $(this).val();
                                                if (selectedValue) {
                                                    table.column(5) // Gender column (index 5)
                                                        .search(selectedValue)
                                                        .draw();
                                                } else {
                                                    table.column(5).search('').draw(); // Clear filter
                                                }
                                            });

                                            // Filter by Status
                                            $('#filterStatus').on('change', function () {
                                                var selectedValue = $(this).val();
                                                if (selectedValue) {
                                                    table.column(6) // Status column (index 6)
                                                        .search(selectedValue)
                                                        .draw();
                                                } else {
                                                    table.column(6).search('').draw(); // Clear filter
                                                }
                                            });
                                        });

                                        
                                    </script>

                                    <!-- END OF DATA TABLE -->
                                    <!-- =============================================== -->
                                <?php } else {
                                    $_SESSION["_ResultMessage"] = $getAllUsers['message'];
                                } ?>
                            </section>
                        </div>
                    <?php elseif (isset($_GET['viewRole']) && isset($_GET['user_id'])): ?>
                        <div class="bg-white rounded p-3 shadow-sm border">
                            <div class="mb-3 row align-items-start bg-transparent box-sizing-border-box">
                                <div
                                    class="col-md-8 d-flex gap-2 justify-content-start align-items-center box-sizing-border-box">
                                    <!-- breadcrumbs -->
                                    <h5 class="ctxt-primary p-0 m-0">
                                        <a class="ctxt-primary" href="<?= clearUrlParams(); ?>">Users</a>
                                        <?php if (isset($_GET['viewRole'])) { ?>
                                            <span><i class="bi bi-caret-right-fill"></i></span>
                                            <a class="ctxt-primary"
                                                href="<?= updateUrlParams(['viewRole' => $_GET['viewRole']]) ?>"><?= ucfirst($_GET['viewRole']) ?></a>
                                        <?php } ?>
                                        <?php if (isset($_GET['viewRole']) && isset($_GET['user_id'])) { ?>
                                            <?php
                                            $retrieved_user = $userController->getUserById($_GET['user_id']);
                                            if ($retrieved_user['success'] == false) {
                                                $_SESSION["_ResultMessage"] = $retrieved_user['message'];
                                            } elseif (empty($retrieved_user['data'])) {
                                                $_SESSION["_ResultMessage"] = ['success' => false, 'message' => 'No user_id with a value (' . $_GET['user_id'] . ') found.'];
                                            }
                                            ?>
                                            <?php if (!empty($retrieved_user['data'])) { ?>
                                                <span><i class="bi bi-caret-right-fill"></i></span>
                                                <a class="ctxt-primary"
                                                    href="<?= updateUrlParams(['viewRole' => $_GET['viewRole'], 'user_id' => $_GET['user_id']]) ?>">
                                                    <?php echo $retrieved_user['data']['first_name'] . ' ' . $retrieved_user['data']['middle_name'] . ' ' . $retrieved_user['data']['last_name'] . ' (' . $retrieved_user['data']['user_id'] . ')' ?>
                                                </a>
                                            <?php } else {
                                                $_SESSION["_ResultMessage"] = ['success' => false, 'message' => 'No user_id with a value (' . $_GET['user_id'] . ') found.'];
                                                echo "<script>window.location = '" . updateUrlParams(['viewRole' => $_GET['viewRole']]) . "';</script>";
                                                exit();
                                            } ?>
                                            <?php
                                            // prevent bypassing viewRole while having specific user_id
                                            if ($retrieved_user['data']['role'] != $_GET['viewRole']) {
                                                $_SESSION["_ResultMessage"] = ['success' => false, 'message' => 'Role does match with user id.'];
                                                echo "<script>window.location = '" . clearUrlParams() . "';</script>";
                                                exit();
                                            }
                                            ?>
                                        <?php } ?>
                                    </h5>
                                    <!-- end of breadcrumbs -->
                                </div>
                                <!-- <div class="col-8 d-flex justify-content-end gap-2"></div> -->
                            </div>
                            <!-- Content View -->
                            <hr>
                            <?php require_once(FILE_PATHS['Partials']['HighLevel']['Configures'] . "config_User.php") ?>
                        </div>
                    </div>
                <?php endif; ?>
    </div>
    </section>
    </section>

    <?php
    require_once(FILE_PATHS['Partials']['User']['UpdateCardImage']);
    if (isset($_GET['viewRole'])) {
        echo create_UpdateCardImage('roles', $_GET['viewRole']);
    }
    ?>

    <?php require_once(FILE_PATHS['Partials']['HighLevel']['Modals']['User']['Add']) ?>

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
        'type' => $_SESSION["_ResultMessage"]['success'] ? 'success' : 'error',
        'message' => $_SESSION["_ResultMessage"]['message'],
    ]);
    outputToasts(); // Execute toast on screen.
    unset($_SESSION["_ResultMessage"]); // Dispose
}

?>

</html>