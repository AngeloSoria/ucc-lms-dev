<div class="modal fade" id="createRoleModal" tabindex="-1" aria-labelledby="createRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createRoleModalLabel">Create New Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <!-- Role Name Input -->
                    <div class="mb-3">
                        <label for="roleName" class="form-label">Role Name:</label>
                        <input type="text" class="form-control" id="roleName" placeholder="Enter Role Name" required>
                    </div>

                    <div class="mb-3">
                        <label for="roleName" class="form-label">
                            Permission Level:
                            <a href="#" target="_blank" rel="noopener noreferrer">
                                <i class="bi bi-info-circle-fill"></i>
                            </a>
                        </label>
                        <select class="form-select" name="perms_level" id="">
                            <option disabled selected>Select permission level</option>
                            <option value="1">Level 1</option>
                            <option value="2">Level 2</option>
                            <option value="3">Level 3</option>
                            <option value="4">Level 4</option>
                            <option value="5">Level 5</option>
                        </select>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary c-primary">Create Role</button>
                </form>
            </div>
        </div>
    </div>
</div>