<?php
session_start();
$CURRENT_PAGE = "departments";

require_once(__DIR__ . '../../../../config/PathsHandler.php');
require_once(FILE_PATHS['DATABASE']);
require_once(FILE_PATHS["Partials"]["Widgets"]["SearchUser"]);
require_once(FILE_PATHS['Functions']['SessionChecker']);
checkUserAccess(['Admin']);

$widget_searchUser = new SearchUser();

// Create a new instance of the Database class
$database = new Database();
$db = $database->getConnection(); // Establish the database connection



?>

<!DOCTYPE html>
<html lang="en">
<?php require_once(FILE_PATHS['Partials']['User']['Head']) ?>

<body class="">
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
                            <h5 class="ctxt-primary">Departments</h5>
                        </div>
                        <div class="col-8 d-flex justify-content-end gap-2">
                            <!-- Tools -->

                            <!-- Add New Button -->
                            <button
                                class="btn btn-primary btn-sm rounded fs-6 px-3 c-primary d-flex gap-3 align-items-center"
                                data-bs-toggle="modal" data-bs-target="#addDepartmentFormModal">
                                <i class="bi bi-plus-circle"></i> Add Department
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

                    <!-- Table View -->
                    <div id="data_view_table" class="d-none">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" name="checkbox_data_selectAll"
                                            id="checkbox_data_selectAll" class="form-check-input">
                                    </th>
                                    <th>Role</th>
                                    <th>Users</th>
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

                    <section>
                        <!-- <h4>Data Table</h4> -->
                        <div class="border rounded table-responsive">
                            <table class="table table-striped table-sm table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Department Name</th>
                                        <th>Department Head</th>
                                        <th># of Members</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="table-group-divider">
                                    <tr>
                                        <td>IT Security</td>
                                        <td>Juan Dela Cruz</td>
                                        <td>44</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>IT Security</td>
                                        <td>Juan Dela Cruz</td>
                                        <td>44</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>IT Security</td>
                                        <td>Juan Dela Cruz</td>
                                        <td>44</td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </section>
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

    <!-- ADD USER FORM POPUP -->
    <?php require_once(FILE_PATHS['Partials']['HighLevel']['Modals']['Department']['Add']) ?>

    <!-- FOOTER -->
    <?php require_once(FILE_PATHS['Partials']['User']['Footer']) ?>
</body>
<script src="<?php asset('js/admin-main.js') ?>"></script>

</html>