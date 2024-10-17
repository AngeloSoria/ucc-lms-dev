<?php
session_start();
require_once '../../../../src/config/connection.php'; // Include the controller
include_once "../../../../src/config/rootpath.php";
require_once '../../../../src/controllers/CourseController.php'; // Database connection

$database = new Database();
$db = $database->getConnection(); // Establish the database connection

$courseController = new CourseController($db);
$courses = $courseController->getAllCourses(); // Fetch all courses

$CURRENT_PAGE = "courses";
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
                            <h5 class="ctxt-primary">Courses</h5>
                        </div>
                        <div class="col-8 d-flex justify-content-end gap-2">
                            <!-- Tools -->
                            <button class="btn btn-primary btn-sm rounded fs-6 px-3 c-primary d-flex gap-3 align-items-center" data-bs-toggle="modal" data-bs-target="#sectionFormModal">
                                <i class="bi bi-plus-circle"></i> Add Course
                            </button>
                            <button class="btn btn-outline-primary btn-sm rounded fs-5 px-2 c-primary d-flex gap-2 align-items-center">
                                <i class="bi bi-arrow-clockwise"></i>
                            </button>
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
                        // Check if there are courses
                        if (!empty($courses)) {
                            // Loop through the $courses array
                            foreach ($courses as $course) {
                        ?>
                                <div class="c-card card cbg-primary text-white border-0 shadow-sm">
                                    <?php
                                    // Convert BLOB to Base64
                                    $base64Image = base64_encode($course['course_image']);
                                    ?>
                                    <div class="card-preview rounded position-relative w-100 bg-success d-flex overflow-hidden justify-content-center align-items-center" style="min-height: 200px; max-height: 200px;">
                                        <img src="data:image/jpeg;base64,<?php echo $base64Image; ?>" class="rounded card-img-top img-course position-absolute top-50 start-50 translate-middle object-fit-fill" alt="<?php echo htmlspecialchars($course['course_name']); ?>">
                                    </div>
                                    <div class="card-body p-2">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <h6 class="card-title w-100 fw-bold bg-transparent" style="height: 4rem;"><?php echo htmlspecialchars($course['course_name']); ?></h6>
                                                <p class="card-text fs-6"><?php echo htmlspecialchars($course['course_description']); ?></p>
                                                <p class="card-text fs-6">Level: <?php echo htmlspecialchars($course['course_level']); ?></p>
                                            </div>
                                            <div class="col-md-2 d-flex justify-content-end align-items-start">
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

                        <?php
                            }
                        } else {
                            echo '<p class="text-danger">No courses available.</p>';
                        }
                        ?>
                    </div>

                    <!-- Table View -->
                    <div id="data_view_table" class="d-none">
                        <table class="c-table table">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" name="checkbox_data_selectAll" id="checkbox_data_selectAll" class="form-check-input">
                                    </th>
                                    <th>Course Code</th>
                                    <th>Course Name</th>
                                    <th>Level</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($courses)) {
                                    foreach ($courses as $course) {
                                ?>
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="checkbox_data_<?php echo htmlspecialchars($course['course_code']); ?>" class="form-check-input">
                                            </td>
                                            <td><?php echo htmlspecialchars($course['course_code']); ?></td>
                                            <td><?php echo htmlspecialchars($course['course_name']); ?></td>
                                            <td><?php echo htmlspecialchars($course['level']); ?></td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
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
                                    echo '<tr><td colspan="5" class="text-danger text-center">No courses available.</td></tr>';
                                }
                                ?>
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

    <?php include_once "../../partials/admin/modal_addCourse.php" ?>

    <!-- FOOTER -->
    <?php include_once "../../partials/footer.php" ?>
</body>
<script src="../../../../src/assets/js/admin-main.js"></script>

</html>