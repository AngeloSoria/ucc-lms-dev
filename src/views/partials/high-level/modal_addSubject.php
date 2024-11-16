<div class="modal fade" id="createSubjectModal" tabindex="-1" aria-labelledby="createSubjectModalLabel"
    aria-hidden="true" closing-confirmation="true"
    closing-confirmation-text="Are you sure closing this form? (You will lose all progress)">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createSubjectModalLabel">Create New Subject</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addSubjectForm" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="addSubject">
                    <!-- Educational Level and Semester (Side by Side) -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="educational_level" class="form-label">Educational Level</label>
                            <select class="form-select" id="educational_level" name="educational_level" required>
                                <option value="" disabled selected>Select educational level</option>
                                <option value="SHS">SHS</option>
                                <option value="College">College</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="semester" class="form-label">Semester</label>
                            <select class="form-select" id="semester" name="semester" required>
                                <option value="" disabled selected>Select Semester</option>
                                <option value="1">1st Semester</option>
                                <option value="2">2nd Semester</option>
                            </select>
                        </div>
                    </div>

                    <!-- Subject Code and Subject Name (Side by Side) -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="subject_code" class="form-label">Subject Code</label>
                            <input type="text" class="form-control" id="subject_code" placeholder="Enter Subject Code"
                                name="subject_code" required>
                        </div>
                        <div class="col-md-9">
                            <label for="subject_name" class="form-label">Subject Name</label>
                            <input type="text" class="form-control" id="subject_name" name="subject_name"
                                placeholder="Enter Subject Name" required>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Submit Button -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary c-primary" form="addSubjectForm">Save changes</button>
            </div>
        </div>
    </div>
</div>



<!-- <script src="../../../assets/js/modal-interceptor.js"></script> -->
<script src="../../../assets/js/root.js"></script>