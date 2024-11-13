<div class="modal fade" id="sectionFormModal" tabindex="-1" aria-labelledby="sectionFormModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title ctxt-primary" id="sectionFormModalLabel">Add Section Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form id="sectionForm" method="POST" enctype="multipart/form-data">
                    <!-- Section Name and Academic Level -->
                    <div class="mb-3 d-flex gap-2">
                        <div class="flex-grow-1">
                            <label for="section_name" class="form-label">Section Name</label>
                            <input type="text" class="form-control" id="section_name" name="section_name" placeholder="Enter Section Name" required>
                        </div>
                        <div class="flex-grow-1">
                            <label for="educational_level" class="form-label">Academic Level</label>
                            <select class="form-select" id="educational_level" name="educational_level" required>
                                <option value="" disabled selected>Select Academic Level</option>
                                <option value="SHS">Senior High School</option>
                                <option value="TER">Tertiary (College)</option>
                            </select>
                        </div>
                    </div>

                    <!-- Program, Year Level, and Semester -->
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

                    <!-- Class Adviser -->
                    <div class="mb-3 d-flex gap-2">
                        <div class="flex-grow-1">
                            <label for="adviser_id" class="form-label">Class Adviser</label>
                            <select class="form-select" id="adviser_id" name="adviser_id" required>
                                <option value="" disabled selected>Select Adviser</option>
                                <!-- Adviser options will be populated here -->
                            </select>
                        </div>
                    </div>

                    <!-- Section Image -->
                    <div class="mb-3">
                        <label for="section_image" class="form-label">Tile Picture</label>
                        <input type="file" class="form-control" id="section_image" name="section_image" accept="image/*">
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary c-primary" form="sectionForm">Add Section</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        // Update programs and year levels based on educational level selection
        $('#educational_level').change(function() {
            const educationalLevel = $(this).val();
            $('#program_id').html('<option value="" disabled selected>Select Program</option>'); // Clear previous options

            // Fetch programs based on selected educational level
            $.ajax({
                url: '../../partials/high-level/fetch_programs.php',
                type: 'POST',
                data: {
                    educational_level: educationalLevel
                },
                success: function(data) {
                    $('#program_id').html(data);
                    console.log(data);

                    // Set year levels based on academic level
                    const yearOptions = (educationalLevel === 'TER') ?
                        '<option value="1">1st Year</option><option value="2">2nd Year</option><option value="3">3rd Year</option><option value="4">4th Year</option>' :
                        '<option value="11">Grade 11</option><option value="12">Grade 12</option>';
                    $('#year_level').html(yearOptions);

                    // Fetch advisers based on the selected academic level
                    fetchAdvisers(educationalLevel);
                },
                error: function(xhr, status, error) {
                    alert('Failed to fetch programs. Please try again.');
                    console.error('AJAX Error:', status, error);
                }
            });
        });

        // Function to fetch advisers based on educational level
        function fetchAdvisers(educationalLevel) {
            $.ajax({
                url: '../../partials/high-level/fetch_advisers.php',
                type: 'POST', // Use POST to send the educational level
                data: {
                    educational_level: educationalLevel
                },
                success: function(data) {
                    $('#adviser_id').html(data);
                },
                error: function(xhr, status, error) {
                    alert('Failed to fetch advisers. Please try again.');
                    console.error('AJAX Error:', status, error);
                }
            });
        }

        // Load advisers when the modal is shown (if necessary)
        $('#sectionFormModal').on('show.bs.modal', function() {
            const educationalLevel = $('#educational_level').val(); // Get current value
            if (educationalLevel) {
                fetchAdvisers(educationalLevel); // Fetch advisers based on current selection
            }
        });

        // Form submission handling
        $('#sectionForm').submit(function(e) {
            e.preventDefault();
            // Add further custom form validation here if needed
            this.submit(); // Remove this if submitting via AJAX
        });
    });
</script>