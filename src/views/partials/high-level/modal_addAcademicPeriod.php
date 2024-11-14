<?php
// Extract start and end year from the current active term
$activeTermStartYear = explode('-', $currentTerm['academic_year'])[0] ?? '';
$activeTermEndYear = explode('-', $currentTerm['academic_year'])[1] ?? '';

// Set the next academic year
$newStartYear = $activeTermEndYear; // The next year
$newEndYear = $newStartYear; // The next year after the new start year
?>
<style>
    .error-text-small {
        font-size: 0.8rem;
    }
</style>

<!-- View (Modal HTML) -->
<div class="modal fade" id="academicFormModal" tabindex="-1" aria-labelledby="academicFormModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title ctxt-primary" id="academicFormModalLabel">Add Academic Period</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addAcademicForm" method="POST">
                    <input type="hidden" name="action" value="addTerm">

                    <!-- Academic Year Section -->
                    <div class="mb-3">
                        <h6>Academic Year</h6>
                        <div class="d-flex gap-2">
                            <div class="flex-grow-1">
                                <label for="start_year" class="form-label">Start Year</label>
                                <input type="number" class="form-control" id="start_year" name="start_year"
                                    placeholder="e.g. 2024" required>
                                <div id="startYearError" class="text-danger error-text-small" style="display: none;">
                                    Start year cannot be in the past.</div>
                            </div>
                            <div class="flex-grow-1">
                                <label for="end_year" class="form-label">End Year</label>
                                <input type="number" class="form-control" id="end_year" name="end_year"
                                    placeholder="e.g. 2025">
                                <div id="endYearError" class="text-danger error-text-small" style="display: none;">
                                    End year cannot be earlier than start year, and cannot be the same as start year.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- First Semester Section -->
                    <div class="mb-3">
                        <h6>First Semester</h6>
                        <div class="d-flex gap-2">
                            <div class="flex-grow-1">
                                <label for="first_semester_start" class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="first_semester_start"
                                    name="first_semester_start">
                                <div id="firstSemesterStartError" class="text-danger error-text-small"
                                    style="display: none;">
                                    Start date must be within the academic year.</div>
                            </div>
                            <div class="flex-grow-1">
                                <label for="first_semester_end" class="form-label">End Date</label>
                                <input type="date" class="form-control" id="first_semester_end"
                                    name="first_semester_end">
                                <div id="firstSemesterEndError" class="text-danger error-text-small"
                                    style="display: none;">
                                    End date cannot be earlier than start date.</div>
                            </div>
                        </div>
                    </div>

                    <!-- Second Semester Section -->
                    <div class="mb-3">
                        <h6>Second Semester</h6>
                        <div class="d-flex gap-2">
                            <div class="flex-grow-1">
                                <label for="second_semester_start" class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="second_semester_start"
                                    name="second_semester_start">
                                <div id="secondSemesterStartError" class="text-danger error-text-small"
                                    style="display: none;">
                                    Start date must be later than first semester end date and within the academic year.
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <label for="second_semester_end" class="form-label">End Date</label>
                                <input type="date" class="form-control" id="second_semester_end"
                                    name="second_semester_end">
                                <div id="secondSemesterEndError" class="text-danger error-text-small"
                                    style="display: none;">
                                    End date cannot be earlier than start date.</div>
                            </div>
                        </div>
                    </div>

                    <!-- Other sections remain the same -->
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success" form="addAcademicForm" id="submit">Submit</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Fetch PHP values for activeTermStartYear and activeTermEndYear
    const activeTermStartYear = <?php echo json_encode($activeTermStartYear); ?>;
    const activeTermEndYear = <?php echo json_encode($activeTermEndYear); ?>;

    // Function to set the academic year fields dynamically
    function setAcademicYearFields() {
        // Set new start year and end year based on the current active year
        const newStartYear = parseInt(activeTermEndYear);
        const newEndYear = newStartYear + 1;

        // Set the start and end year fields
        document.getElementById('start_year').value = newStartYear;
        document.getElementById('end_year').value = newEndYear;
    }

    // Call the function when the modal is shown
    $('#academicFormModal').on('shown.bs.modal', function () {
        setAcademicYearFields();
    });

    // Existing validation function for year and date inputs
    document.getElementById('start_year').addEventListener('input', validateDateInput);
    document.getElementById('end_year').addEventListener('input', validateDateInput);
    document.getElementById('first_semester_start').addEventListener('input', validateDateInput);
    document.getElementById('first_semester_end').addEventListener('input', validateDateInput);
    document.getElementById('second_semester_start').addEventListener('input', validateDateInput);
    document.getElementById('second_semester_end').addEventListener('input', validateDateInput);

    function validateDateInput(event) {
        const startYear = document.getElementById('start_year').value;
        const endYear = document.getElementById('end_year').value;
        const currentYear = new Date().getFullYear();
        const startYearNum = parseInt(startYear);
        const endYearNum = parseInt(endYear);

        // Validate start year
        if (startYear !== '' && startYear < currentYear) {
            document.getElementById('startYearError').style.display = 'block';
            document.getElementById('start_year').classList.add('is-invalid');
        } else {
            resetError('start_year', 'startYearError');
        }

        // Prevent setting the same start year as the current active term's start year
        if (startYear === activeTermStartYear) {
            document.getElementById('startYearError').style.display = 'block';
            document.getElementById('start_year').classList.add('is-invalid');
            document.getElementById('startYearError').innerHTML = 'Start year cannot be the same as the current active year.';
        }

        // Validate end year
        if (startYear !== '' && endYear !== '') {
            if (endYear <= startYear) {
                document.getElementById('endYearError').style.display = 'block';
                document.getElementById('end_year').classList.add('is-invalid');
            } else {
                resetError('end_year', 'endYearError');
            }
        }

        // Validate First Semester Dates
        const firstStartDate = document.getElementById('first_semester_start').value;
        const firstEndDate = document.getElementById('first_semester_end').value;

        if (firstStartDate) {
            const firstStartYear = new Date(firstStartDate).getFullYear();
            if (firstStartYear < startYearNum || firstStartYear > endYearNum) {
                document.getElementById('firstSemesterStartError').style.display = 'block';
                document.getElementById('first_semester_start').classList.add('is-invalid');
            } else {
                resetError('first_semester_start', 'firstSemesterStartError');
            }
        }
        if (firstStartDate && firstEndDate && firstEndDate < firstStartDate) {
            document.getElementById('firstSemesterEndError').style.display = 'block';
            document.getElementById('first_semester_end').classList.add('is-invalid');
        } else {
            resetError('first_semester_end', 'firstSemesterEndError');
        }

        // Validate Second Semester Dates
        const secondStartDate = document.getElementById('second_semester_start').value;
        const secondEndDate = document.getElementById('second_semester_end').value;

        if (secondStartDate) {
            const secondStartYear = new Date(secondStartDate).getFullYear();
            if (secondStartYear < firstEndDate || secondStartYear > endYearNum) {
                document.getElementById('secondSemesterStartError').style.display = 'block';
                document.getElementById('second_semester_start').classList.add('is-invalid');
            } else {
                resetError('second_semester_start', 'secondSemesterStartError');
            }
        }
        if (secondStartDate && secondEndDate && secondEndDate < secondStartDate) {
            document.getElementById('secondSemesterEndError').style.display = 'block';
            document.getElementById('second_semester_end').classList.add('is-invalid');
        } else {
            resetError('second_semester_end', 'secondSemesterEndError');
        }
    }

    function resetError(fieldId, errorId) {
        document.getElementById(errorId).style.display = 'none';
        document.getElementById(fieldId).classList.remove('is-invalid');
    }
</script>