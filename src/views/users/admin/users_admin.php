<?php
// src/views/admin/users_admin.php
include_once "../../../config/rootpath.php";
include_once "../../../config/connection.php";
include_once "../../../models/User.php";
include_once "../../../controllers/UserController.php";
include_once "../../../views/partials/public/alert_Toast.php";

session_start();

// Create a new instance of the Database class
$database = new Database();
$db = $database->getConnection(); // Establish the database connection

// Pass the connection to UserController
$userController = new UserController($db);
$User = new User($db);
// After the database connection
$latestUserId = $User->getLatestUserId($db); // Call the method to get the latest user ID
$CURRENT_PAGE = "users";

// If form is submitted, process the form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userData = [
        'user_id' => $_POST['user_id'],
        'role' => $_POST['role'],
        'first_name' => $_POST['first_name'],
        'middle_name' => $_POST['middle_name'],
        'last_name' => $_POST['last_name'],
        'gender' => $_POST['gender'],
        'dob' => $_POST['dob'],
        'username' => $_POST['username'],
        'password' => $_POST['password'],
        'email' => $_POST['email'],
        'profile_pic' => $_FILES['profile_pic'] // Make sure the input name matches
    ];

    // Call the method to add a user
    $addUserResult = $userController->addUser($userData);

    // Store the result in a session variable before redirecting
    $_SESSION['addUserResult'] = $addUserResult;

    // Redirect to the same page
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Retrieve and display the result after the redirection
if (isset($_SESSION['addUserResult'])) {
    $addUserResult = $_SESSION['addUserResult'];

    // Clear the session variable after use
    unset($_SESSION['addUserResult']);
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include_once "../../partials/head.php" ?>

<body>
    <?php include_once '../../users/navbar.php' ?>

    <section class="d-flex justify-content-between gap-2 box-sizing-border-box m-0 p-0">
        <!-- SIDEBAR -->
        <?php include_once '../../users/sidebar.php' ?>

        <!-- content here -->
        <section class="row min-vh-100 w-100 m-0 p-1 d-flex justify-content-end align-items-start" id="contentSection">
            <div class="col box-sizing-border-box flex-grow-1">
                <!-- First row, first column -->
                <div class="bg-white rounded p-3 shadow-sm border">
                    <!-- Headers -->
                    <div class="mb-3 row align-items-start">
                        <div class="col-4 d-flex gap-3">
                            <h5 class="ctxt-primary">Users</h5>
                        </div>
                        <div class="col-8 d-flex justify-content-end gap-2">
                            <!-- Tools -->

                            <!-- Add New Button -->
                            <button class="btn btn-primary btn-sm rounded fs-6 px-3 c-primary d-flex gap-3 align-items-center" data-bs-toggle="modal" data-bs-target="#userFormModal" onclick="apply_section_modal(this);">
                                <i class="bi bi-plus-circle"></i> Add User
                            </button>

                            <!-- Reload Button -->
                            <button class="btn btn-outline-primary btn-sm rounded fs-5 px-2 c-primary d-flex gap-2 align-items-center">
                                <i class="bi bi-arrow-clockwise"></i>
                            </button>

                            <!-- View Type -->
                            <div class="btn-group" id="viewTypeContainer">
                                <button id="btnViewTypeCatalog" type="button" class="btn btn-sm btn-primary c-primary px-2">
                                    <i class="bi bi-card-heading fs-6"></i>
                                </button>
                                <button id="btnViewTypeTable" type="button" class="btn btn-sm btn-outline-primary c-primary px-2">
                                    <i class="bi bi-table fs-6"></i>
                                </button>
                            </div>

                        </div>
                    </div>


                    <!-- Catalog View -->
                    <div id="data_view_catalog" class="d-flex justify-content-start align-items-start gap-2 flex-wrap">

                        <?php
                        // These are placeholders.
                        $fake_data = [
                            ["All", 0], // We'll update this later with the total count
                            ["Registrar", 10],
                            ["Students", 5232],
                            ["Teachers", 233],
                            ["Super Admin", 2],
                        ];

                        // Calculate the total number of users for "All"
                        $total_users = 0;
                        for ($i = 1; $i < count($fake_data); $i++) {
                            $total_users += $fake_data[$i][1];
                        }

                        // Update the "All" role with the total count
                        $fake_data[0][1] = $total_users;

                        // Loop through the $fake_data array
                        for ($i = 0; $i < count($fake_data); $i++) {
                        ?>
                            <div class="c-card card cbg-primary text-white border-0 shadow-sm">
                                <img src="https://via.placeholder.com/800x600" class="card-img-top" alt="...">
                                <div class="card-body p-2">
                                    <!-- Dynamically set card title and text -->
                                    <div class="row">
                                        <div class="col-md-10">
                                            <h6 class="card-title w-100 fw-bold bg-transparent" style="height: 4rem;"><?php echo $fake_data[$i][0]; ?></h6>
                                            <p class="card-text fs-6"><?php echo number_format($fake_data[$i][1]); ?> Users</p>
                                        </div>
                                        <div class="col-md-2 d-flex justify-content-end align-items-start">
                                            <!-- Config dialog -->
                                            <div class="dropdown">
                                                <button class="btn btn-lg c-primary p-0 text-white dropdown-toggle dropdown-no-icon" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="bi bi-three-dots-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                                                    <li><a class="dropdown-item" href="#" onclick="">Configure</a></li>
                                                    <li><a class="dropdown-item" href="#" onclick="">Delete</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>

                    <!-- Table View -->
                    <div id="data_view_table" class="d-none">
                        <table class="c-table table">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" name="checkbox_data_selectAll" id="checkbox_data_selectAll" class="form-check-input">
                                    </th>
                                    <th>Role</th>
                                    <th>Users</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <input type="checkbox" name="<data_context>" id="checkbox_data_select-<data_context>" class="form-check-input">
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
            </div>
            <div class="col bg-transparent d-flex flex-column justify-content-start align-items-center gap-2 px-1 box-sizing-border-box" id="widgetPanel">
                <!-- CALENDAR -->
                <?php include "../../partials/special/mycalendar.php" ?>

                <!-- TASKS -->
                <?php include "../../partials/special/mytasks.php" ?>
            </div>
        </section>
    </section>

    <!-- ADD USER FORM POPUP -->
    <?php include_once "../../partials/admin/modal_addUser.php" ?>

    <!-- FOOTER -->
    <?php include_once "../../partials/footer.php" ?>
</body>
<script src="../../../../src/assets/js/admin-main.js"></script>

<script>
    //DOM Content loaded
    document.addEventListener('DOMContentLoaded', function() {
        <?php
        if ($addUserResult === true) {
            echo 'showToast("default", "User created", "User successfully added.");';
        } else {
            echo 'showToast("danger", "Something went wrong","' . $addUserResult . '");';
        }
        ?>
    });
</script>

</html>