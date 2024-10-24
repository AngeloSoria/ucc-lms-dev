<?php
session_start(); // Start the session at the top of your file

require_once(__DIR__ . '../../../../config/PathsHandler.php');
require_once(FILE_PATHS['DATABASE']);
require_once(FILE_PATHS['Controllers']['Subject']);


$CURRENT_PAGE = "subjects";
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
        <section class="row min-vh-100 w-100 m-0 p-1 d-flex justify-content-end align-items-start" id="contentSection">
            <div class="col box-sizing-border-box flex-grow-1">
                <!-- First row, first column -->
                <div class="bg-white rounded p-3 shadow-sm border">
                    <!-- Headers -->
                    <div class="mb-3 row align-items-start">
                        <div class="col-4 d-flex gap-3">
                            <h5 class="ctxt-primary">Subjects</h5>
                        </div>
                        <div class="col-8 d-flex justify-content-end gap-2">
                            <!-- Tools -->

                            <!-- Add New Button -->
                            <button class="btn btn-primary btn-sm rounded fs-6 px-3 c-primary d-flex gap-3 align-items-center" data-bs-toggle="modal" data-bs-target="#createSubjectModal">
                                <i class="bi bi-plus-circle"></i> Add Subject
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
                            ["Data Structures & Algorithms (TER)", 344],
                            ["Purpossive Communications (TER)", 455],
                            ["Oral Communications (SHS)", 233],
                            ["Entrepreneurship 1 (SHS)", 125],
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
                                            <p class="card-text fs-6"><?php echo number_format($fake_data[$i][1]); ?> Students</p>
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
                <!-- Second column spans both rows -->

                <!-- CALENDAR -->
                <?php require_once(FILE_PATHS['Partials']['User']['Calendar']) ?>

                <!-- TASKS -->
                <?php require_once(FILE_PATHS['Partials']['User']['Tasks']) ?>
            </div>
        </section>
    </section>

    <!-- ADD SUBJECT POPUP -->
    <?php require_once(FILE_PATHS['Partials']['HighLevel']['Modals']['Subject']['Add']) ?>

    <!-- FOOTER -->
    <?php require_once(FILE_PATHS['Partials']['User']['Footer']) ?>
</body>
<script src="../../../../src/assets/js/admin-main.js"></script>

</html>