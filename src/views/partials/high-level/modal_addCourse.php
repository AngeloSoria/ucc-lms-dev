<div class="modal fade" id="sectionFormModal" tabindex="-1" aria-labelledby="sectionFormModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title ctxt-primary" id="sectionFormModalLabel">Add Course Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <form id="sectionForm" method="POST" enctype="multipart/form-data">

                    <div class="mb-3 d-flex gap-2">
                        <div class="col-md-4">
                            <label for="course_code" class="form-label">Course Code</label>
                            <input type="text" class="form-control" id="course_code" name="course_code" placeholder="Enter Couse Name" required>
                        </div>

                        <div class="flex-grow-1">
                            <label for="course_name" class="form-label">Course Name</label>
                            <input type="text" class="form-control" id="course_name" name="course_name" placeholder="Enter Couse Name" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-floating">
                            <textarea class="form-control" placeholder="Add Description here" id="description" name="course_description" style="height: 150px"></textarea>
                            <label for="description">Description</label>
                        </div>
                    </div>

                    <div class="mb-3 d-flex gap-2">
                        <div class="col-md-4">
                            <label for="course_level" class="form-label">Academic Level</label>
                            <select class="form-select" id="course_level" name="course_level" required>
                                <option value="" disabled selected>Select Academic</option>
                                <option value="SHS">Senior High School</option>
                                <option value="TER">Tertiary (College)</option>
                            </select>
                        </div>

                        <div class="flex-grow-1">
                            <label for="course_image" class="form-label">Tile Picture</label>
                            <input type="file" class="form-control" id="course_image" name="course_image" accept="image/*">
                        </div>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary c-primary" form="sectionForm">Save changes</button>
            </div>
        </div>
    </div>
</div>