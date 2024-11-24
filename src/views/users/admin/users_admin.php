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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'addUser') {
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
    }

    // Save user data to the database using a controller method
    $_SESSION["_ResultMessage"] = $userController->addUser($userData);

    // Redirect to the same page to prevent resubmission
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit();
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
            <section id="contentSection">
                <div class="col box-sizing-border-box flex-grow-1">
                    <?php if (!isset($_GET['viewRole'])): ?>
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
                                        class="btn btn-primary btn-lg rounded fs-6 px-3 c-primary d-flex gap-3 align-items-center"
                                        data-bs-toggle="modal" data-bs-target="#userFormModal"
                                        apply_section_modal>
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
                            <div preview-container-name="view_table" class="d-none">
                                <?php
                                $getAllUsers = $userController->getAllUsers();
                                if ($getAllUsers['success'] == true) { ?>
                                    <!-- =============================================== -->
                                    <!-- DATA TABLE -->
                                    <div class="actionControls mb-2 p-1 bg-transparent d-flex gap-2 justify-content-end align-items-center">
                                        <button class="btn btn-danger" onclick="javascript:alert(1)">
                                            <i class="bi bi-trash"></i>
                                            Remove Selection
                                        </button>
                                    </div>
                                    <table id="dataTable_allUsers" class="table table-responsive-sm border display compact" style="width: 100%">
                                        <thead style="background-color: var(--c-brand-primary-a0) !important;">
                                            <tr>
                                                <th>
                                                    <input type="checkbox" class="form-check-input" value="<?php htmlspecialchars($row[$uniqueIDTarget] ?? '') ?>">
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
                                            <?php foreach ($getAllUsers['data'] as $userData) { ?>
                                                <?php if (empty($getAllUsers['data'])) { ?>
                                                    <tr>
                                                        <td colspan="9">No data available</td>
                                                    </tr>
                                                <?php } else { ?>
                                                    <tr>
                                                        <td>
                                                            <input type="checkbox" class="form-check-input" value="<?php htmlspecialchars($userData['user_id'] ?? '') ?>">
                                                        </td>
                                                        <td><?php echo $userData['user_id'] ?></td>
                                                        <td><?php echo $userData['role'] ?></td>
                                                        <td><?php echo $userData['username'] ?></td>
                                                        <td><?php echo $userData['first_name'] . ' ' . $userData['last_name'] ?></td>
                                                        <td><?php echo ucfirst($userData['gender']) ?></td>
                                                        <td class="fw-bold <?php echo $userData['status'] == 'active' ? 'ctxt-primary' : 'text-danger' ?>"><?php echo ucfirst($userData['status']) ?></td>
                                                        <td>
                                                            <a href="<?php echo htmlspecialchars(updateUrlParams(['viewRole' => $userData['role'], 'user_id' => $userData['user_id']])) ?>" title="Configure" class="btn btn-primary m-auto">
                                                                <i class="bi bi-pencil-square"></i>
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
                                                    "targets": [0, 7]
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
                                                        .columns([2, 6, 7])
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
                                <?php } else {
                                    $_SESSION["_ResultMessage"] = $getAllUsers['message'];
                                } ?>
                            </div>
                        </div>
                    <?php elseif (isset($_GET['viewRole']) && !isset($_GET['user_id'])): ?>
                        <div class="bg-white rounded p-3 shadow-sm border">
                            <div class="mb-3 row align-items-start bg-transparent box-sizing-border-box">
                                <div class="col-4 d-flex gap-2 justify-content-start align-items-center box-sizing-border-box">
                                    <!-- breadcrumbs -->
                                    <h5 class="ctxt-primary p-0 m-0">
                                        <a class="ctxt-primary" href="<?= clearUrlParams(); ?>">Users</a>
                                        <?php if (isset($_GET['viewRole'])) { ?>
                                            <span><i class="bi bi-caret-right-fill"></i></span>
                                            <a class="ctxt-primary" href="<?= updateUrlParams(['viewRole' => $_GET['viewRole']]) ?>"><?= ucfirst($_GET['viewRole']) ?></a>
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
                                            <a class="ctxt-primary" href="<?= updateUrlParams(['viewRole' => $_GET['viewRole'], 'user_id' => $_GET['user_id']]) ?>">
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
                                    <div class="actionControls mb-2 p-1 bg-transparent d-flex gap-2 justify-content-end align-items-center">
                                        <button class="btn btn-danger" onclick="" disabled>
                                            <i class="bi bi-trash"></i>
                                            Remove Selection
                                        </button>
                                    </div>
                                    <table id="dataTable_allUsers" class="table table-responsive-sm border display compact" style="width: 100%">
                                        <thead style="background-color: var(--c-brand-primary-a0) !important;">
                                            <tr>
                                                <th>
                                                    <input type="checkbox" id="checkbox_selectAll" class="form-check-input" value="<?php htmlspecialchars($row[$uniqueIDTarget] ?? '') ?>">
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
                                            <?php foreach ($getAllUsers['data'] as $userData) { ?>
                                                <?php if (empty($getAllUsers['data'])) { ?>
                                                    <tr>
                                                        <td colspan="9">No data available</td>
                                                    </tr>
                                                <?php } else { ?>
                                                    <tr>
                                                        <td>
                                                            <input type="checkbox" class="form-check-input" value="<?php htmlspecialchars($userData['user_id'] ?? '') ?>">
                                                        </td>
                                                        <td><?php echo $userData['user_id'] ?></td>
                                                        <td><?php echo $userData['role'] ?></td>
                                                        <td><?php echo $userData['username'] ?></td>
                                                        <td><?php echo $userData['first_name'] . ' ' . $userData['last_name'] ?></td>
                                                        <td><?php echo ucfirst($userData['gender']) ?></td>
                                                        <td class="fw-bold <?php echo $userData['status'] == 'active' ? 'ctxt-primary' : 'text-danger' ?>"><?php echo ucfirst($userData['status']) ?></td>
                                                        <td>
                                                            <a href="<?php echo htmlspecialchars(updateUrlParams(['viewRole' => $userData['role'], 'user_id' => $userData['user_id']])) ?>" title="Configure" class="btn btn-primary m-auto">
                                                                <i class="bi bi-pencil-square"></i>
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
                                                    "targets": [0, 7]
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
                                                        .columns([5, 6])
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
                                <?php } else {
                                    $_SESSION["_ResultMessage"] = $getAllUsers['message'];
                                } ?>
                            </section>
                        </div>
                    <?php elseif (isset($_GET['viewRole']) && isset($_GET['user_id'])): ?>
                        <div class="bg-white rounded p-3 shadow-sm border">
                            <div class="mb-3 row align-items-start bg-transparent box-sizing-border-box">
                                <div class="col-md-8 d-flex gap-2 justify-content-start align-items-center box-sizing-border-box">
                                    <!-- breadcrumbs -->
                                    <h5 class="ctxt-primary p-0 m-0">
                                        <a class="ctxt-primary" href="<?= clearUrlParams(); ?>">Users</a>
                                        <?php if (isset($_GET['viewRole'])) { ?>
                                            <span><i class="bi bi-caret-right-fill"></i></span>
                                            <a class="ctxt-primary" href="<?= updateUrlParams(['viewRole' => $_GET['viewRole']]) ?>"><?= ucfirst($_GET['viewRole']) ?></a>
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
                                                <a class="ctxt-primary" href="<?= updateUrlParams(['viewRole' => $_GET['viewRole'], 'user_id' => $_GET['user_id']]) ?>">
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
                            <?php if (!empty($retrieved_user['data'])):
                                // prepare the data.
                                $user_profileImage = base64_encode($retrieved_user['data']['profile_pic']);
                                $user_userid = $retrieved_user['data']['user_id'];
                                $user_firstName = $retrieved_user['data']['first_name'];
                                $user_middleName = $retrieved_user['data']['middle_name'];
                                $user_lastName = $retrieved_user['data']['last_name'];
                                $user_fullName = $retrieved_user['data']['first_name'] . ' ' . $retrieved_user['data']['middle_name'] . ' ' . $retrieved_user['data']['last_name'];
                                $user_dob = $retrieved_user['data']['dob'];
                                $user_gender = $retrieved_user['data']['gender'];
                                $user_username = $retrieved_user['data']['username'];
                                $user_status = $retrieved_user['data']['status'];
                                $user_createdDate = $retrieved_user['data']['created_at'];
                                $user_lastUpdate = $retrieved_user['data']['updated_at'];
                                $user_requirePasswordReset = $retrieved_user['data']['requirePasswordReset'];
                                $user_lastLogin = timeElapsedSince($retrieved_user['data']['last_login']);
                            ?>
                                <hr>
                                <!-- generated -->
                                <div class="container my-4">
                                    <h4 class="fw-bolder text-success">Edit Profile</h4>
                                    <div class="card shadow-sm position-relative">
                                        <div class="card-header position-relative d-flex justify-content-start align-items-center gap-3 bg-success bg-opacity-75">
                                            <div class="position-absolute top-0 end-0 mt-3 me-4">
                                                <button class="btn cbtn-secondary px-4" disabled>
                                                    Edit
                                                </button>
                                            </div>
                                            <img src="<?= isset($user_profileImage) && !empty($user_profileImage)
                                                            ? 'data:image/jpeg;base64,' . $user_profileImage
                                                            : 'https://via.placeholder.com/200?text=No+Image' ?>"
                                                alt="Profile Picture"
                                                class="rounded-circle img-fluid border border-3 border-success"
                                                style="width: 120px; height: 120px; object-fit: cover;">
                                            <div class="text-white p-0">
                                                <h3 class="mt-3 p-0 m-0"><?= htmlspecialchars($user_fullName) ?></h3>
                                                <p class="text-white p-0 m-0">@<?= htmlspecialchars($user_username) ?></p>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <section class="mb-4">
                                                <div class="row mb-3">
                                                    <h5>Account Information</h5>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-md-4">
                                                        <h6 class="">User Name</h6>
                                                        <input updateEnabled class="form-control" type="text" disabled value="<?= htmlspecialchars($user_username) ?>">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <h6 class="">Password</h6>
                                                        <input updateEnabled class="form-control" type="password" disabled value="<?= htmlspecialchars($user_username) ?>">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <h6 class="">Requires Password Change</h6>
                                                        <select class="form-select" disabled>
                                                            <?php if ($user_requirePasswordReset) { ?>
                                                                <option value="1" selected>Yes</option>
                                                            <?php } else { ?>
                                                                <option value="0" selected>No</option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </section>

                                            <hr>

                                            <section class="mb-4">
                                                <div class="row mb-3">
                                                    <h5>Personal Information</h5>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-md-4">
                                                        <h6 class="">First Name</h6>
                                                        <input updateEnabled class="form-control" type="text" disabled value="<?= htmlspecialchars($user_firstName) ?>">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <h6 class="">Middle Name</h6>
                                                        <input updateEnabled class="form-control" type="text" disabled value="<?= htmlspecialchars($user_middleName) ?>">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <h6 class="">Last Name</h6>
                                                        <input updateEnabled class="form-control" type="text" disabled value="<?= htmlspecialchars($user_lastName) ?>">
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-md-4">
                                                        <h6 class="">Date of Birth</h6>
                                                        <input updateEnabled class="form-control" type="date" disabled value="<?= htmlspecialchars($user_dob) ?>">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <h6 class="">Gender</h6>
                                                        <select class="form-select" disabled>
                                                            <?php if ($user_gender == 'male') { ?>
                                                                <option value="male" selected>Male</option>
                                                            <?php } else { ?>
                                                                <option value="female" selected>Female</option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </section>

                                            <hr>

                                            <section class="mb-4">
                                                <div class="row mb-3">
                                                    <h5>Miscellaneous</h5>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-md-4">
                                                        <h6 class="">Created Date</h6>
                                                        <span class=""><?= htmlspecialchars($user_createdDate) ?></span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <h6 class="">Last Update Date</h6>
                                                        <span class=""><?= htmlspecialchars($user_lastUpdate) ?></span>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-md-6">
                                                        <h6 class="">Last Login:</h6>
                                                        <span class=""><?= !empty($user_lastLogin) ? htmlspecialchars($user_lastLogin) : 'No activity yet.' ?></span>
                                                    </div>
                                                </div>
                                            </section>

                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <h3 class="text-danger">No Information Shown.</h3>
                            <?php endif; ?>
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