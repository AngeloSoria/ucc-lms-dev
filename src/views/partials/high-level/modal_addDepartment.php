<div class="modal fade" id="addDepartmentFormModal" tabindex="-1" aria-labelledby="addDepartmentFormModalLabel" aria-hidden="true" section-counter-show="true" closing-confirmation="true" closing-confirmation-text="Are you sure closing this form? (You will lose all progress)">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header d-flex row w-100 m-auto">
                <div class="d-flex align-items-center">
                    <h5 class="modal-title ctxt-primary" id="addDepartmentFormModalLabel">Add Department Form</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>

            <div class="section-modal modal-body">
                <form id="userForm" method="POST">
                    <div>
                        <div class="mb-3 d-flex gap-2">
                            <div class="flex-grow-1">
                                <label for="department_name" class="form-label">Department Name</label>
                                <input type="text" class="form-control" id="department_name" name="department_name" placeholder="Enter department name">
                            </div>
                        </div>

                        <div class="mb-3 d-flex gap-2">
                            <div class="flex-grow-1">
                                <label for="department_head" class="form-label">Department Head (0/1)</label>
                                <?php
                                echo $widget_searchUser->getWidget('department_head');
                                ?>

                                <div class="userlist-container rounded-top" id="container_department">
                                    <div class="userlist-controls rounded-top">
                                        <div class="profile-checkbox">
                                            <input class="form-check-input" type="checkbox" id="userlist-checkbox-all_department">
                                        </div>
                                        <p>(?) Selected</p>
                                        <div class="control-btn control-view-remove px-2 py-1 m-0" role="button" title="Remove Selected" onclick="removeSelected('department')">
                                            <i class="bi bi-trash-fill"></i>
                                        </div>
                                    </div>
                                    <div class="userlist-contents border" id="userlist_contents-department_head" target-container-id="department_head" target-container-max-content="1">
                                        <!-- User items will be dynamically added here -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 d-flex gap-2">
                            <div class="flex-grow-1">
                                <div>
                                    <label for="members_container" class="form-label">Members:</label>
                                    <?php
                                        echo $widget_searchUser->getWidget('department_members');
                                    ?>
                                </div>
                                <div class="userlist-container rounded-top" id="container_department">
                                    <div class="userlist-controls rounded-top">
                                        <div class="profile-checkbox">
                                            <input class="form-check-input" type="checkbox" id="userlist-checkbox-all_department">
                                        </div>
                                        <p>(?) Selected</p>
                                        <div class="control-btn control-view-remove px-2 py-1 m-0" role="button" title="Remove Selected">
                                            <i class="bi bi-trash-fill"></i>
                                        </div>
                                    </div>
                                    <div class="userlist-contents border" id="userlist_contents-department_members" target-container-id="department_members" target-container-max-content="-1">
                                        <!-- User items will be dynamically added here -->
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </form>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success c-primary" id="submit" form="userForm">Submit</button>
            </div>
        </div>
    </div>
</div>

<script src="../../../assets/js/modal-interceptor.js"></script>
<script src="../../../assets/js/root.js"></script>