<div class="modal fade" id="addStudentEnrollmentModals" tabindex="-1" aria-labelledby="addStudentEnrollmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="addStudentEnrollmentModalLabel">Student Enrollment Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Modal Body (form) -->
            <div class="modal-body">
                <section id="enrollmentForm">
                    <div class="mb-3">
                        <label for="input_modalAddToEnrollStudents" class="form-label">Search & Add Student</label>
                        <select class="form-control col-md-12" id="input_amodalAddToEnrollStudents" name="input_modalAddToEnrollStudents[]" multiple required></select>
                    </div>
                </section>
            </div>
            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success" form="enrollmentForm">Submit</button>
            </div>
        </div>
    </div>
</div>
<script>
    $()
</script>