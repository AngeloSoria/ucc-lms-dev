<?php
// Assuming $pdo is your PDO database connection

// Query to get the latest start and end year based on the most recent academic year
$query = "SELECT academic_year_start, academic_year_end 
          FROM academic_period 
          ORDER BY academic_year_start DESC 
          LIMIT 1";

try {
    // Prepare and execute the query
    $stmt = $db->prepare($query);
    $stmt->execute();

    // Check if a result was returned
    if ($stmt->rowCount() > 0) {
        // Fetch the latest active term data
        $currentTerm = $stmt->fetch(PDO::FETCH_ASSOC);

        // Extract the start and end year of the latest active term
        $activeTermStartYear = $currentTerm['academic_year_start'];
        $activeTermEndYear = $currentTerm['academic_year_end'];

        // Set the next academic year
        $newStartYear = $activeTermEndYear;  // The next academic year starts after the current term ends
        $newEndYear = $newStartYear + 1;     // The next year's end year (one year after the new start year)

        // Output the next academic year (optional)
        echo "Next academic year: $newStartYear - $newEndYear";
    } else {
        // If no active academic year exists, default to the current year (2024)
        $activeTermStartYear = date("Y");  // Current year as start year (e.g. 2024)
        $activeTermEndYear = $activeTermStartYear; // Next year as end year (e.g. 2025)
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

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
                    <input type="hidden" name="action" value="addAcademicYearWithSemesters">

                    <!-- Academic Year Section -->
                    <div class="mb-3">
                        <h6>Academic Year</h6>
                        <div class="d-flex gap-2">
                            <div class="flex-grow-1">
                                <label for="start_year" class="form-label">Start Year</label>
                                <input type="number" class="form-control" id="start_year" name="academic_year_start"
                                    placeholder="e.g. 2024" required>
                                <div id="startYearError" class="text-danger error-text-small" style="display: none;">
                                    Start year cannot be in the past.</div>
                            </div>
                            <div class="flex-grow-1">
                                <label for="end_year" class="form-label">End Year</label>
                                <input type="number" class="form-control" id="end_year" name="academic_year_end"
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
    const currentYear = new Date().getFullYear(); // Get current year

    // Function to set the academic year fields dynamically
    function setAcademicYearFields() {
        // Calculate the new start year and end year
        const newStartYear = parseInt(activeTermEndYear); // New start year is after the current end year
        const newEndYear = newStartYear + 1; // End year is one year after the start year

        // Set the values in the form fields
        document.getElementById('start_year').value = newStartYear;
        document.getElementById('end_year').value = newEndYear;
    }

    // Call the function when the modal is shown
    $('#academicFormModal').on('shown.bs.modal', function() {
        setAcademicYearFields();
    });

    // Event Listeners for Validation
    document.querySelectorAll('input').forEach(input => {
        input.addEventListener('input', validateInputs);
    });

    function validateInputs() {
        const startYear = document.getElementById('start_year').value;
        const endYear = document.getElementById('end_year').value;
        const firstStart = document.getElementById('first_semester_start').value;
        const firstEnd = document.getElementById('first_semester_end').value;
        const secondStart = document.getElementById('second_semester_start').value;
        const secondEnd = document.getElementById('second_semester_end').value;

        // Start Year Validation
        if (startYear && parseInt(startYear) < currentYear) {
            showError('start_year', 'startYearError', 'Start year cannot be in the past.');
        } else if (startYear && parseInt(startYear) < parseInt(activeTermEndYear) && activeTermEndYear > 0) {
            // If there is a previous record and the user enters a start year that is less than the next expected year
            showError('start_year', 'startYearError', `Start year must be greater than or equal to ${parseInt(activeTermEndYear) + 1}.`);
        } else {
            resetError('start_year', 'startYearError');
        }

        // End Year Validation
        if (startYear && endYear && parseInt(endYear) <= parseInt(startYear)) {
            showError('end_year', 'endYearError', 'End year must be later than start year.');
        } else {
            resetError('end_year', 'endYearError');
        }

        // First Semester Validation
        if (firstStart) {
            const firstStartDate = new Date(firstStart);
            const startDateYear = firstStartDate.getFullYear();

            // Ensure first semester start date is within the academic year
            if (startDateYear < startYear || startDateYear > endYear) {
                showError('first_semester_start', 'firstSemesterStartError', 'Start date must be within the academic year.');
            } else {
                resetError('first_semester_start', 'firstSemesterStartError');
            }
        }

        if (firstStart && firstEnd && new Date(firstEnd) <= new Date(firstStart)) {
            showError('first_semester_end', 'firstSemesterEndError', 'End date cannot be equal or earlier than start date.');
        } else {
            resetError('first_semester_end', 'firstSemesterEndError');
        }

        // Second Semester Validation
        if (secondStart) {
            if (!firstEnd || new Date(secondStart) <= new Date(firstEnd)) {
                showError('second_semester_start', 'secondSemesterStartError', 'Start date must be after the end date of the first semester.');
            } else {
                resetError('second_semester_start', 'secondSemesterStartError');
            }
        }
        if (secondStart && secondEnd && new Date(secondEnd) <= new Date(secondStart)) {
            showError('second_semester_end', 'secondSemesterEndError', 'End date cannot be equal or earlier than start date.');
        } else {
            resetError('second_semester_end', 'secondSemesterEndError');
        }
    }

    // Show error message
    function showError(fieldId, errorId, message) {
        const errorElement = document.getElementById(errorId);
        const inputElement = document.getElementById(fieldId);

        errorElement.style.display = 'block';
        errorElement.innerText = message;
        inputElement.classList.add('is-invalid');
    }

    // Reset error message
    function resetError(fieldId, errorId) {
        const errorElement = document.getElementById(errorId);
        const inputElement = document.getElementById(fieldId);

        errorElement.style.display = 'none';
        inputElement.classList.remove('is-invalid');
    }
</script>