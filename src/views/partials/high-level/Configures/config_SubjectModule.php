<div class="my-4">
    <h4 class="fw-bolder text-success">Edit Subject</h4>
    <div class="card shadow-sm position-relative">
        <div
            class="card-header position-relative d-flex justify-content-start align-items-center gap-3 bg-success bg-opacity-75">
            <!-- <div class="position-absolute top-0 end-0 mt-3 me-4">
                                                <button class="btn cbtn-secondary px-4">
                                                    Edit
                                                </button>
                                            </div> -->
            <div class="text-white p-0 pb-2">
                <h3 class="mt-3 p-0 m-0">
                    <?= htmlspecialchars($SELECTED_SUBJECT['subject_name']) ?>
                </h3>
                <p class="text-white p-0 m-0">
                    <?= htmlspecialchars($SELECTED_SUBJECT['subject_code']) ?>
                </p>
            </div>
        </div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="updateSectionInfo">

                <section class="mb-4">
                    <div class="row mb-3">
                        <h5>Subject Information</h5>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-6 col-md-5 col-lg-3 mb-2">
                            <h6 class="pt-sm-3 pt-md-0">Subject Code</h6>
                            <input name="subject_code" updateEnabled class="form-control"
                                type="text"
                                value="<?= htmlspecialchars($SELECTED_SUBJECT['subject_code']) ?>">
                        </div>
                        <div class="col-sm-12 col-md-7 col-lg-7">
                            <h6>Subject Name</h6>
                            <input name="subject_name" updateEnabled class="form-control"
                                type="text"
                                value="<?= htmlspecialchars($SELECTED_SUBJECT['subject_name']) ?>">
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-2">
                            <h6 class="pt-sm-3 pt-md-0">Semester</h6>
                            <select name="" id="" class="form-select" disabled>
                                <?php if (isset($SELECTED_SUBJECT['semester'])): ?>
                                    <option
                                        value="<?= htmlspecialchars($SELECTED_SUBJECT['semester']) ?>">
                                        <?= htmlspecialchars($SELECTED_SUBJECT['semester']) ?>
                                    </option>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <h6 class="">Educational Level</h6>
                            <select name="" id="" class="form-select" disabled>
                                <?php if (isset($SELECTED_SUBJECT['educational_level'])): ?>
                                    <option
                                        value="<?= htmlspecialchars($SELECTED_SUBJECT['educational_level']) ?>">
                                        <?= htmlspecialchars($SELECTED_SUBJECT['educational_level']) ?>
                                    </option>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                </section>
                <div class="d-flex gap-2 justify-content-end">
                    <button type="submit" class="btn btn-success d-flex gap-2">
                        <i class="bi bi-floppy-fill"></i>
                        Update
                    </button>
                    <span type="button" class="btn btn-danger d-flex gap-2" id="btnDelete" onclick="alert('Work in progress...')">
                        <i class="bi bi-trash-fill"></i>
                        Delete
                    </span>
                </div>
            </form>
        </div>
    </div>
</div>