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

                    <div class="mb-3">
                        <div class="form-floating">
                            <textarea class="form-control" placeholder="Add Description here" id="program_description" name="program_description" style="height: 100px"></textarea>
                            <label for="program_description">Description</label>
                        </div>
                    </div>

                    <div class="mb-3 d-flex gap-2">
                        <div class="flex-grow-1">
                            <label for="program_id" class="form-label">Program</label>
                            <select class="form-select" id="program_id" name="program_id" required>
                                <option value="" disabled selected>Select Program</option>
                                <!-- Program options will be dynamically populated here -->
                            </select>
                        </div>
                        <div class="flex-grow-1">
                            <label for="year_level" class="form-label">Year Level</label>
                            <select class="form-select" id="year_level" name="year_level" required>
                                <option value="" disabled selected>Select Year Level</option>
                                <!-- Year level options will be populated here -->
                            </select>
                        </div>
                        <div class="flex-grow-1">
                            <label for="semester" class="form-label">Semester</label>
                            <select class="form-select" id="semester" name="semester" required>
                                <option value="" disabled selected>Select Semester</option>
                                <option value="1">1st Semester</option>
                                <option value="2">2nd Semester</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="section_image" class="form-label">Tile Picture</label>
                        <input type="file" class="form-control" id="section_image" name="section_image" accept="image/*">
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