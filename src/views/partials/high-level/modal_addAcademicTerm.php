<div class="modal fade" id="academicFormModal" tabindex="-1" aria-labelledby="academicFormModalLabel" aria-hidden="true" closing-confirmation="true" closing-confirmation-text="Are you sure closing this form? (You will lose all progress)">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header d-flex row w-100 m-auto">
                <div class="mb-3 d-flex align-items-center">
                    <h5 class="modal-title ctxt-primary" id="academicFormModalLabel">Add Academic Term</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>

            <!-- Modal Body (Multi-Step) -->
            <div class="section-modal modal-body">
                <form id="academicForm" method="POST">
                    <!-- Step 1: Personal Information -->
                    <div class="form-step active">
                        <div class="mb-3 d-flex gap-2">
                            <div class="flex-grow-1">
                                <label for="year_name" class="form-label">Year Name</label>
                                <input type="text" class="form-control" id="year_name" name="year_name" placeholder="e.g. 2024 - 2025">
                            </div>
                        </div>

                        <div class="mb-3 d-flex gap-2">
                            <div class="flex-grow-1">
                                <label for="semester" class="form-label">Semester</label>
                                <select class="form-select" id="semester" name="semester" required>
                                    <option value="" disabled selected>Select Semester</option>
                                    <!-- <option value="Male">Male</option>
                                    <option value="Female">Female</option> -->
                                </select>
                            </div>
                        </div>

                        <div class="mb-3 d-flex gap-2">
                            <div class="flex-grow-1">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" placeholder="Enter First Name" required>
                            </div>

                            <div class="flex-grow-1">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="date" class="form-control" id="end_date" placeholder="Enter Middle Name" name="end_date">
                            </div>
                        </div>

                        <div class="mb-3 d-flex gap-2">
                            <div class="flex-grow-1">
                                <input type="checkbox" class="form-check-input" id="dob" name="dob">
                                <label for="dob" class="form-label">Set as Active</label>
                            </div>
                        </div>
                    </div>

                </form>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success c-primary" id="submit" form="academicForm">Submit</button>
            </div>
        </div>
    </div>
</div>

<script src="../../../assets/js/modal-interceptor.js"></script>
<script src="../../../assets/js/root.js"></script>