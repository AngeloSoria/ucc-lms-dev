<div class="modal fade" id="sectionFormModal" tabindex="-1" aria-labelledby="sectionFormModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title ctxt-primary" id="sectionFormModalLabel">Add Section Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form id="sectionForm" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="addSection">
                    <!-- Section Name and Academic Level -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="educational_level" class="form-label">Academic Level</label>
                            <select class="form-select" id="educational_level" name="educational_level" required>
                                <option value="" disabled selected>Select Academic Level</option>
                                <option value="SHS">Senior High School</option>
                                <option value="College">Tertiary (College)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="program_id" class="form-label">Program</label>
                            <select class="form-select" id="program_id" name="program_id" required>
                                <option value="" disabled selected>Select Program</option>
                                <!-- Program options will be dynamically populated here -->
                            </select>
                        </div>
                    </div>

                    <!-- Program, Year Level, and Semester -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="year_level" class="form-label">Year Level</label>
                            <select class="form-select" id="year_level" name="year_level" required>
                                <option value="" disabled selected>Select Year Level</option>
                                <!-- Year level options will be populated here -->
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="semester" class="form-label">Semester</label>
                            <select class="form-select" id="semester" name="semester" required>
                                <option value="" disabled selected>Select Semester</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="section_name" class="form-label">Section Name</label>
                            <input type="text" class="form-control" id="section_name" name="section_name"
                                placeholder="Enter Section Name" required>
                        </div>

                        <!-- Class Adviser -->
                        <div class="col-md-6 d-flex flex-column">
                            <label for="adviser_id" class="form-label">Class Adviser</label>
                            <select class="form-select form-control" id="adviser_id" name="adviser_id">
                            </select>
                        </div>
                    </div>
                    <input type="hidden" id="period_id" name="period_id">


                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary c-primary" form="sectionForm">Add</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Update programs and year levels based on educational level selection
    $('#educational_level').change(function () {
        educationalLevel = $(this).val();
        $('#program_id').html('<option value="" disabled selected>Select Program</option>'); // Clear previous options
        $('#year_level').html('<option value="" disabled selected>Select Year Level</option>'); // Clear year level options
        $('#adviser_id').html('<option value="" disabled selected>Select Adviser</option><option value="NA">N/A</option>'); // Clear and add N/A option

        if (!educationalLevel) {
            return; // Do nothing if no educational level is selected
        }

        // Fetch programs based on selected educational level
        $.ajax({
            url: '../../../views/partials/high-level/fetch_programs.php',
            type: 'POST',
            data: {
                educational_level: educationalLevel
            },
            success: function (data) {
                console.log('Programs fetched:', data);
                $('#program_id').html(data); // Populate the program dropdown

                // Set year levels based on academic level
                const yearOptions = (educationalLevel === 'College') ?
                    '<option value="1">1st Year</option><option value="2">2nd Year</option><option value="3">3rd Year</option><option value="4">4th Year</option>' :
                    '<option value="11">Grade 11</option><option value="12">Grade 12</option>';
                $('#year_level').html(yearOptions); // Populate year level dropdown

                // Fetch advisers based on the selected academic level
                // fetchAdvisers(educationalLevel);
            },
            error: function (xhr, status, error) {
                console.error('AJAX Error:', status, error);
                alert('Failed to fetch programs. Please try again.');
            }
        });
    });

    $(document).ready(function () {
        // Fetch and populate active semesters
        function fetchActiveSemesters() {
            $.ajax({
                url: '../../../views/partials/high-level/fetch_semesters.php', // PHP script to fetch active semesters
                type: 'POST',
                success: function (data) {
                    // Populate the semester dropdown with the fetched options
                    $('#semester').html('<option value="" disabled selected>Select Semester</option>'); // Clear previous options
                    $('#semester').append(data); // Add new options
                },
                error: function (xhr, status, error) {
                    console.error("Error fetching semesters:", error);
                    alert("Failed to fetch active semesters. Please try again.");
                }
            });
        }

        // Capture the selected semester and store its period_id in a hidden input
        $('#semester').change(function () {
            const periodId = $(this).val(); // Get the selected period_id from the dropdown
            $('#period_id').val(periodId); // Set the hidden input field's value to period_id
        });

        // Call the function to load semesters when the modal is shown
        $('#sectionFormModal').on('show.bs.modal', function () {
            fetchActiveSemesters();
        });
    });



    $(document).ready(function () {
        // Function to initialize Select2 for the adviser dropdown
        function initializeSelect2() {
            $('#adviser_id').select2({
                dropdownParent: $('#sectionFormModal'),
                width: '100%',
                placeholder: "Search and select adviser",
                allowClear: true,
                ajax: {
                    url: "../../../views/partials/high-level/fetch_advisers.php",
                    type: "POST",
                    dataType: "json",
                    delay: 250,
                    data: function (params) {
                        // Get the educational level from the dropdown
                        const educationalLevel = $('#educational_level').val();

                        if (!educationalLevel) {
                            console.error("Educational level is not selected");
                            return { query: "" }; // If no educational level, return no data
                        }

                        return {
                            search_type: "teacher",
                            query: params.term, // The search query from the user
                            educational_level: educationalLevel // Send the educational level
                        };
                    },
                    processResults: function (data) {
                        // Map the returned data to Select2 format
                        return {
                            results: data.map(teacher => ({
                                id: teacher.user_id,
                                text: `${teacher.first_name} ${teacher.last_name} (${teacher.user_id})`
                            }))
                        };
                    },
                    error: function (xhr, status, error) {
                        console.error("Error fetching data:", error);
                        alert("Failed to fetch advisers. Please try again.");
                    }
                }
            });
        }

        // Reinitialize Select2 whenever the educational level dropdown changes
        $('#educational_level').change(function () {
            $('#adviser_id').val(null).trigger('change'); // Reset adviser selection
            initializeSelect2(); // Reinitialize with the updated educational level
        });

        // Initialize Select2 when the modal is shown
        $('#sectionFormModal').on('show.bs.modal', function () {
            initializeSelect2(); // Ensure Select2 is initialized
        });

        // Form submission handling
        $('#sectionForm').submit(function (e) {
            e.preventDefault();
            // If N/A is selected for adviser, we handle it as null or a special case when submitting.
            const adviserValue = $('#adviser_id').val();
            if (adviserValue === "NA") {
                $('#adviser_id').val(null); // Set null if N/A is selected
            }

            // Submit the form via AJAX or regular form submission
            this.submit(); // Remove this if submitting via AJAX
        });

    });
</script>