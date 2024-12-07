<div class="modal fade" id="modal_addModule" tabindex="-1" aria-labelledby="modal_addModule" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fs-5 text-start">
                    Add Module Form
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <!-- Form inside the modal -->
                <form method="POST">
                    <input type="hidden" name="action" value="addSubjectModule">
                    <div class="mb-3">
                        <label for="input_moduleName" class="form-label">Module Name</label>
                        <input type="text" class="form-control px-3 py-2" id="input_moduleName"
                            name="input_moduleName" placeholder="" required />
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch"
                                id="input_moduleVisibility" name="input_moduleVisibility">
                            <label class="form-check-label" for="input_moduleVisibility">Module
                                Visibility</label>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <input type="submit" value="Submit" class="btn btn-success">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>