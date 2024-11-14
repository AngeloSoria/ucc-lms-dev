<?php
$latestUserId = $userController->getLatestUserId();
?>
<div class="modal fade" id="userFormModal" tabindex="-1" aria-labelledby="userFormModalLabel" aria-hidden="true"
    section-counter-show="true" closing-confirmation="true"
    closing-confirmation-text="Are you sure closing this form? (You will lose all progress)">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header d-flex row w-100 m-auto">
                <div class="mb-3 d-flex align-items-center">
                    <h5 class="modal-title ctxt-primary" id="userFormModalLabel">Add User Form</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="px-2">
                    <div id="sectionProgressBar" class="progress progress-sm px-0 w-35" role="progressbar"
                        aria-label="Success example" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar bg-success" style="width: 25%" id="progressBar"></div>
                    </div>
                </div>
            </div>

            <!-- Modal Body (Multi-Step) -->
            <div class="section-modal modal-body">
                <form id="addUserForm" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="addUser">
                    <!-- Step 1: Personal Information -->
                    <div class="form-step active">
                        <div class="mb-3 d-flex gap-2">
                            <h5>Personal Information:</h5>
                        </div>
                        <div class="mb-3 d-flex gap-2">
                            <div class="flex-grow-1">
                                <label for="user_id" class="form-label">User ID <i style="font-size:0.75rem;"
                                        class="text-danger">(Auto Generated)</i></label>
                                <input type="text" class="form-control" id="user_id" name="user_id"
                                    value="<?php echo $latestUserId; ?>" readonly>
                            </div>

                            <div class="flex-grow-1">
                                <label for="role" class="form-label">Role</label>
                                <select class="form-select" id="role" name="role" required>
                                    <option value="" disabled selected>Select Role</option>
                                    <option value="Admin">Admin</option>
                                    <option value="Level Coordinator">Level Coordinator</option>
                                    <option value="Student">Student</option>
                                    <option value="Teacher">Teacher</option>
                                </select>
                            </div>

                            <div class="flex-grow-1 d-none" id="role_type_container">
                                <label for="educational_level" class="form-label">Educational level</label>
                                <select class="form-select" id="educational_level" name="educational_level">
                                    <option value="" disabled selected>Select educational level</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3 d-flex gap-2">
                            <div class="flex-grow-1">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name"
                                    placeholder="Enter First Name" required>
                            </div>

                            <div class="flex-grow-1">
                                <label for="middle_name" class="form-label">Middle Name</label>
                                <input type="text" class="form-control" id="middle_name" placeholder="Enter Middle Name"
                                    name="middle_name">
                            </div>

                            <div class="flex-grow-1">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="last_name" placeholder="Enter Last Name"
                                    name="last_name" required>
                            </div>
                        </div>

                        <div class="mb-3 d-flex gap-2">
                            <div class="flex-grow-1">
                                <label for="gender" class="form-label">Gender</label>
                                <select class="form-select" id="gender" name="gender" required>
                                    <option value="" disabled selected>Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>

                            <div class="flex-grow-1">
                                <label for="dob" class="form-label">Date of Birth</label>
                                <input type="date" class="form-control" id="dob" name="dob" min="1979-12-31" max="2025-1-31" required>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Account Information -->
                    <div class="form-step">
                        <div class="mb-2 d-flex gap-2">
                            <h5>Account Information:</h5>
                        </div>
                        <div class="mb-0 d-flex gap-2 justify-content-center align-items-start">
                            <div class="flex-grow-1">
                                <label for="username" class="form-label">Username <i style="font-size:0.75rem;"
                                        class="text-danger">(Auto Generated)</i></label>
                                <input type="text" class="form-control" id="username" name="username"
                                    placeholder="Generated Username" readonly>
                            </div>

                            <div class="flex-grow-1">
                                <label for="password" class="form-label">Password <i style="font-size:0.75rem;"
                                        class="text-danger">(Auto Generated)</i></label>
                                <div class="position-relative" id="inputPasswordContainer">
                                    <input type="password" class="form-control" name="password" id="password"
                                        placeholder="Generated Password" readonly>
                                    <i class="bi bi-eye-slash-fill me-3 fs-5 position-absolute top-50 end-0 translate-middle-y"
                                        id="togglePassword" role="button" onclick="togglePasswordInputText(this);"></i>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 pt-2 ">
                            <label for="profile_pic" class="form-label">Profile Picture</label>
                            <input type="file" class="form-control" id="profile_pic" name="profile_pic"
                                accept="image/*">
                        </div>
                    </div>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btnPrevious">Previous</button>
                <button type="button" class="btn btn-primary" id="btnNext">Next</button>
                <button type="submit" class="btn btn-success c-primary" id="submit" form="addUserForm">Register</button>
            </div>
        </div>
    </div>
</div>

<script>
    // JavaScript for dynamic role type requirement
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('role');
        const roleTypeContainer = document.getElementById('role_type_container');
        const teacherTypeSelect = document.getElementById('educational_level');

        roleSelect.addEventListener('change', function() {
            if (roleSelect.value === 'Teacher') {
                roleTypeContainer.classList.remove('d-none'); // Show the Role Type container
                teacherTypeSelect.required = true; // Make Role Type required
            } else {
                roleTypeContainer.classList.add('d-none'); // Hide the Role Type container
                teacherTypeSelect.required = false; // Remove the required attribute
                teacherTypeSelect.value = ''; // Reset the Role Type select
            }
        });
    });
</script>

<script src="../../../assets/js/modal_userRegistration.js"></script>
<script src="../../../assets/js/section-modals.js"></script>
<script src="../../../assets/js/modal-interceptor.js"></script>
<script src="../../../assets/js/root.js"></script>