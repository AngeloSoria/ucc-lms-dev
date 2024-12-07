<div class="my-4">
    <h4 class="fw-bolder text-success">Edit Subject</h4>
    <div class="card shadow-sm position-relative">
        <div
            class="card-header position-relative d-flex justify-content-start align-items-center gap-3 bg-success bg-opacity-75">
            <div class="text-white p-0 pb-2">
                <h3 class="mt-3 p-0 m-0">
                    <?= htmlspecialchars($SELECTED_SUBJECT['subject_name']) ?>
                </h3>
                <p class="text-white p-0 m-0">
                    <?= htmlspecialchars($SELECTED_SUBJECT['subject_code']) ?>
                </p>
            </div>
        </div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="updateSectionInfo">

                <section class="mb-4">
                    <div class="row mb-3">
                        <h5>Subject Information</h5>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-6 col-md-5 col-lg-3 mb-2">
                            <h6 class="pt-sm-3 pt-md-0">Subject Code</h6>
                            <input name="subject_code" updateEnabled class="form-control"
                                type="text"
                                value="<?= htmlspecialchars($SELECTED_SUBJECT['subject_code']) ?>">
                        </div>
                        <div class="col-sm-12 col-md-7 col-lg-7">
                            <h6>Subject Name</h6>
                            <input name="subject_name" updateEnabled class="form-control"
                                type="text"
                                value="<?= htmlspecialchars($SELECTED_SUBJECT['subject_name']) ?>">
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-2">
                            <h6 class="pt-sm-3 pt-md-0">Semester</h6>
                            <select name="" id="" class="form-select" disabled>
                                <?php if (isset($SELECTED_SUBJECT['semester'])): ?>
                                    <option
                                        value="<?= htmlspecialchars($SELECTED_SUBJECT['semester']) ?>">
                                        <?= htmlspecialchars($SELECTED_SUBJECT['semester']) ?>
                                    </option>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <h6 class="">Educational Level</h6>
                            <select name="" id="" class="form-select" disabled>
                                <?php if (isset($SELECTED_SUBJECT['educational_level'])): ?>
                                    <option
                                        value="<?= htmlspecialchars($SELECTED_SUBJECT['educational_level']) ?>">
                                        <?= htmlspecialchars($SELECTED_SUBJECT['educational_level']) ?>
                                    </option>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                </section>
                <div class="d-flex gap-2 justify-content-end">
                    <button type="submit" class="btn btn-success d-flex gap-2">
                        <i class="bi bi-floppy-fill"></i>
                        Update
                    </button>
                    <span type="button" class="btn btn-danger d-flex gap-2" id="btnDelete">
                        <i class="bi bi-trash-fill"></i>
                        Delete
                    </span>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        // Attach click event to the delete button
        $('#btnDelete').on('click', function() {
            // Insert confirmation modal into the DOM dynamically
            const modalHtml = `
            <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Deletion</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete this item? This action cannot be undone.
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        `;

            // Append the modal to the body
            $('body').append(modalHtml);

            // Show the modal
            $('#deleteConfirmationModal').modal('show');

            // Handle confirm delete action
            $('#confirmDelete').on('click', function() {
                // Close the modal
                $('#deleteConfirmationModal').modal('hide');

                // Perform AJAX request
                console.log('Performing AJAX delete action...');

                $.ajax({
                    url: "", // Replace with your endpoint
                    method: 'POST',
                    data: {
                        action: "deleteSubject",
                        subject_code: "<?php echo $SELECTED_SUBJECT['subject_code'] ?>",
                        subject_name: "<?php echo $SELECTED_SUBJECT['subject_name'] ?>"
                    }, // Replace with your data
                    contentType: 'application/json',
                    success: function(response) {
                        console.log('Delete successful', response);
                        // Handle success, e.g., show a success message or update the UI
                        if (response.redirect) {
                            window.location = response.redirect;
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error deleting item:', error);
                        // Handle error, e.g., show an error message
                    }
                });
            });

            // Remove the modal from the DOM after it's hidden
            $('#deleteConfirmationModal').on('hidden.bs.modal', function() {
                $(this).remove();
            });
        });
    });
</script>