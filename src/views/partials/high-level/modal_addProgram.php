<div class="modal fade" id="programFormModal" tabindex="-1" aria-labelledby="programFormModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title ctxt-primary" id="programFormModalLabel">Add Program Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <form id="addProgramForm" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="addProgram">
                    <div class="mb-3 d-flex gap-2">
                        <div class="col-md-4">
                            <label for="program_code" class="form-label">Program Code</label>
                            <input type="text" class="form-control" id="program_code" name="program_code"
                                placeholder="Enter Course Code" required>
                        </div>

                        <div class="flex-grow-1">
                            <label for="program_name" class="form-label">Program Name</label>
                            <input type="text" class="form-control" id="program_name" name="program_name"
                                placeholder="Enter Course Name" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-floating">
                            <textarea class="form-control" placeholder="Add Description here" id="program_description"
                                name="program_description" style="height: 150px"></textarea>
                            <label for="program_description">Description</label>
                        </div>
                    </div>

                    <div class="mb-3 d-flex gap-2">
                        <div class="col-md-4">
                            <label for="educational_level" class="form-label">Level</label>
                            <select class="form-select" id="educational_level" name="educational_level" required>
                                <option value="" disabled selected>Select Academic</option>
                                <option value="SHS">Senior High School</option>
                                <option value="College">Tertiary (College)</option>
                            </select>
                        </div>

                        <div class="flex-grow-1">
                            <label for="program_image" class="form-label">Tile Picture</label>
                            <input type="file" class="form-control" id="program_image" name="program_image"
                                accept="image/*">
                        </div>
                    </div>
                </form>
                <div id="notification" class="alert" style="display:none;"></div>

            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary c-primary" form="addProgramForm">Add</button>
            </div>
        </div>
    </div>
</div>