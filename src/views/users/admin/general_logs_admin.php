<?php
session_start();
$CURRENT_PAGE = 'general-logs';

require_once(__DIR__ . '../../../../config/PathsHandler.php');
require_once(FILE_PATHS['DATABASE']);
require_once(FILE_PATHS['Functions']['SessionChecker']);
require_once(FILE_PATHS['Functions']['ToastLogger']);
checkUserAccess(['Admin']);

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
                    <!-- First row, first column -->
                    <div class="bg-white rounded p-3 shadow-sm border">
                        <!-- Headers -->
                        <div class="mb-3 row align-items-start">
                            <div class="col-4 d-flex gap-3">
                                <h5 class="ctxt-primary">General Logs</h5>
                            </div>
                            <div class="col-8 d-flex justify-content-end gap-2">
                                <!-- Tools -->

                                <!-- Reload Button -->
                                <button
                                    class="btn btn-outline-primary btn-sm rounded fs-6 px-2 c-primary d-flex gap-2 align-items-center">
                                    <i class="bi bi-arrow-clockwise"></i>
                                    Reload
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


                        <!-- Catalog View -->
                        <div id="data_view_catalog" class="d-flex justify-content-start align-items-start gap-2 flex-wrap">


                        </div>

                    </div>
                </div>
            </section>
        </section>

        <!-- FOOTER -->
        <?php require_once(FILE_PATHS['Partials']['User']['Footer']) ?>
    </div>
</body>

<script src="<?php echo asset('js/toast.js') ?>"></script>

<?php
// Show Toast
if (isset($message) && $message != null) {
    $type = $message[0];
    $text = $message[1];
    makeToast([
        'type' => $type,
        'message' => $message,
    ]);
    outputToasts(); // Execute toast on screen.
    $message = null; // Dispose
}

?>

</html>