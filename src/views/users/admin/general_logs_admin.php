<?php
session_start();
$CURRENT_PAGE = 'general-logs';

require_once(__DIR__ . '../../../../config/PathsHandler.php');
require_once(FILE_PATHS['DATABASE']);
require_once(FILE_PATHS['Controllers']['GeneralLogs']);
require_once(FILE_PATHS['Functions']['SessionChecker']);
require_once(FILE_PATHS['Functions']['ToastLogger']);
checkUserAccess(['Admin']);

$GeneralLogsController = new GeneralLogsController();

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
                <div class="container-fluid box-sizing-border-box flex-grow-1">
                    <!-- First row, first column -->
                    <div class="bg-white rounded p-3 shadow-sm border">
                        <!-- Headers -->
                        <div class="mb-3 row align-items-start">
                            <div class="col d-flex gap-3">
                                <h5 class="ctxt-primary">General Logs</h5>
                            </div>
                        </div>
                        <hr>

                        <section>
                            <!-- Tab Content -->
                            <div class="tab-content mt-3" id="myTabContent">
                                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                    <!-- DATA TABLE -->
                                    <div class="container-fluid">

                                        <!-- Filter Section -->
                                        <div class="row mb-3">
                                            <div class="col-md-3">
                                                <label for="filterType" class="form-label">Filter by Type</label>
                                                <select id="filterType" class="form-select">
                                                    <option value="">All Types</option>
                                                    <option value="LOGIN">LOGIN</option>
                                                    <option value="LOGOUT">LOGOUT</option>
                                                    <option value="CREATE">CREATE</option>
                                                    <option value="READ">READ</option>
                                                    <option value="UPDATE">UPDATE</option>
                                                    <option value="DELETE">DELETE</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="filterRole" class="form-label">Filter by Role</label>
                                                <select id="filterRole" class="form-select">
                                                    <option value="">All Roles</option>
                                                    <option value="Admin">Admin</option>
                                                    <option value="Level Coordinator">Level Coordinator</option>
                                                    <option value="Teacher">Teacher</option>
                                                    <option value="Student">Student</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Table -->
                                        <div class="table-responsive">
                                            <table id="logsTable" class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Log ID</th>
                                                        <th>Type</th>
                                                        <th>User ID</th>
                                                        <th>Role</th>
                                                        <th>Description</th>
                                                        <th>Log Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $logs = $GeneralLogsController->getAllLatestLogs(200);
                                                    if ($logs['success']) {
                                                        foreach ($logs['data'] as $log) {
                                                            $userLink = "users_admin.php?viewRole=" . $log['role'] . "&user_id=" . $log['user_id'];
                                                            // Define type-specific classes
                                                            $typeClass = '';
                                                            switch ($log['type']) {
                                                                case 'LOGIN':
                                                                case 'LOGOUT':
                                                                    $typeClass = 'badge-primary';
                                                                    break;
                                                                case 'CREATE':
                                                                    $typeClass = 'badge-success';
                                                                    break;
                                                                case 'READ':
                                                                    $typeClass = 'badge-secondary';
                                                                    break;
                                                                case 'UPDATE':
                                                                    $typeClass = 'badge-warning';
                                                                    break;
                                                                case 'DELETE':
                                                                    $typeClass = 'badge-danger';
                                                                    break;
                                                            }

                                                            echo "<tr>
                                                                <td>{$log['log_id']}</td>
                                                                <td><span class='badge {$typeClass}'>{$log['type']}</span></td>
                                                                <td><a href='{$userLink}' class='user-link'>{$log['user_id']}</a></td>
                                                                <td>{$log['role']}</td>
                                                                <td>{$log['description']}</td>
                                                                <td>{$log['log_date']}</td>
                                                            </tr>";
                                                        }
                                                    } else {
                                                        $_SESSION['_ResultMessage'] = $logs;
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <script>
                                        $(document).ready(function() {
                                            // Initialize DataTable
                                            var table = $('#logsTable').DataTable({
                                                paging: true,
                                                searching: true,
                                                ordering: true,
                                                order: [],
                                            });

                                            // Filter by Type
                                            $('#filterType').on('change', function() {
                                                var selectedValue = $(this).val();
                                                table.column(1) // Type column (index 1)
                                                    .search(selectedValue)
                                                    .draw();
                                            });

                                            // Filter by Role
                                            $('#filterRole').on('change', function() {
                                                var selectedValue = $(this).val();
                                                table.column(3) // Role column (index 3)
                                                    .search(selectedValue)
                                                    .draw();
                                            });
                                        });
                                    </script>



                                    <!-- END OF DATA TABLE -->

                                </div>
                            </div>

                        </section>

                    </div>
                </div>
            </section>
        </section>

        <!-- FOOTER -->
        <?php require_once(FILE_PATHS['Partials']['User']['Footer']) ?>
    </div>
</body>

<script src="<?php echo asset('js/toast.js') ?>"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

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