<div class="modal fade" id="createSubjectModal" tabindex="-1" aria-labelledby="createSubjectModalLabel" aria-hidden="true" closing-confirmation="true" closing-confirmation-text="Are you sure closing this form? (You will lose all progress)">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createSubjectModalLabel">Create New Subject</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <!-- 
                        Subject Code
                        Subject Name
                        Semester
                        Level
                        Profile Image
                    -->
                    <div class="mb-3 d-flex gap-2">
                        <div class="flex-grow-1">
                            <label for="subjectCode" class="form-label">Subject Code:</label>
                            <input type="text" class="form-control" id="subjectCode" placeholder="Enter Subject Code" required>
                        </div>

                        <div class="flex-grow-1 col-md-7">
                            <label for="subjectname" class="form-label">Subject Name</label>
                            <input type="text" class="form-control" id="subjectname" placeholder="Enter Section Name" required>
                        </div>
                    </div>
                    
                    <div class="mb-3 d-flex gap-2">
                        <div class="flex-grow-1">
                            <label for="role" class="form-label">Academic Level</label>
                            <select class="form-select" id="role" required>
                                <option value="" disabled selected>Select Academic</option>
                                <option value="First">Senior High School</option>
                                <option value="Second">Tertiary (College)</option>
                            </select>
                        </div>

                        <div class="flex-grow-1">
                            <label for="role" class="form-label">Academic Term</label>
                            <select class="form-select" id="role" required>
                                <option value="" disabled selected>Select Semester</option>
                                <option value="First">First</option>
                                <option value="Second">Second</option>
                                <option value="Third">Third</option>
                            </select>
                        </div>
                    </div>



                    <!-- Submit Button -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary c-primary" form="sectionForm">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- <script src="../../../assets/js/modal-interceptor.js"></script> -->
<script src="../../../assets/js/root.js"></script>