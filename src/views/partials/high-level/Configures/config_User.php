<?php if (!empty($retrieved_user['data'])) {
    // prepare the data.
    $user_profileImage = base64_encode($retrieved_user['data']['profile_pic']);
    $user_userid = $retrieved_user['data']['user_id'];
    $user_firstName = $retrieved_user['data']['first_name'];
    $user_middleName = $retrieved_user['data']['middle_name'];
    $user_lastName = $retrieved_user['data']['last_name'];
    $user_fullName = $retrieved_user['data']['first_name'] . ' ' . $retrieved_user['data']['middle_name'] . ' ' . $retrieved_user['data']['last_name'];
    $user_dob = $retrieved_user['data']['dob'];
    $user_gender = $retrieved_user['data']['gender'];
    $user_username = $retrieved_user['data']['username'];
    $user_status = $retrieved_user['data']['status'];
    $user_createdDate = $retrieved_user['data']['created_at'];
    $user_lastUpdate = $retrieved_user['data']['updated_at'];
    $user_requirePasswordReset = $retrieved_user['data']['requirePasswordReset'];
    $user_lastLogin = timeElapsedSince($retrieved_user['data']['last_login']);
} ?>
<div class="container-fluid my-4">
    <h4 class="fw-bolder text-success">Edit Profile</h4>
    <div class="card shadow-sm position-relative">
        <div class="card-header position-relative d-flex justify-content-start align-items-center gap-3 bg-success bg-opacity-75">
            <img src="<?= isset($user_profileImage) && !empty($user_profileImage)
                            ? 'data:image/jpeg;base64,' . $user_profileImage
                            : 'https://via.placeholder.com/200?text=No+Image' ?>"
                alt="Profile Picture"
                class="rounded-circle img-fluid border border-3 border-success"
                style="width: 120px; height: 120px; object-fit: cover;">
            <div class="text-white p-0">
                <h3 class="mt-3 p-0 m-0"><?= htmlspecialchars($user_fullName) ?></h3>
                <p class="text-white p-0 m-0">@<?= htmlspecialchars($user_username) ?></p>
            </div>
        </div>
        <form method="POST" enctype="multipart/form-data" id="formAccountInfo">
            <input type="hidden" name="user_id" value="<?php echo $user_userid ?>">
            <input type="hidden" name="action" value="updateUserInfo" id="formAction">
            <div class="card-body">
                <section class="mb-4">
                    <div class="row mb-3">
                        <h5>Account Information</h5>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <h6 class="">User Name</h6>
                            <input updateEnabled class="form-control" type="text" disabled
                                value="<?= htmlspecialchars($user_username) ?>">
                        </div>
                        <div class="col-md-4">
                            <h6 class="">
                                Password
                                <span role="button" id="editPasswordToggle">
                                    <i class="bi bi-pencil-square"></i>
                                </span>
                            </h6>
                            <input disabled id="inputPassword" name="password" class="form-control" type="text" value="">
                            <script>
                                $("#editPasswordToggle").on('click', function() {
                                    // Toggle the 'disabled' attribute
                                    if ($("#inputPassword").prop("disabled")) {
                                        $("#inputPassword").prop("disabled", false); // Enable the input
                                        $("#inputPassword").attr("type", "password"); // Enable the input
                                    } else {
                                        $("#inputPassword").attr("type", "text"); // Enable the input
                                        $("#inputPassword").prop("disabled", true); // Disable the input
                                    }
                                });
                            </script>
                        </div>
                        <div class="col-md-4">
                            <h6 class="">Requires Password Change</h6>
                            <select name="requirePasswordReset" class="form-select">
                                <option value="1" <?php echo ($user_requirePasswordReset) ? 'selected' : ''; ?>>Yes</option>
                                <option value="0" <?php echo (!$user_requirePasswordReset) ? 'selected' : ''; ?>>No</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <h6 class="">Status</h6>
                            <select name="userStatus" class="form-select">
                                <option value="<?php echo ($user_status) ?>" <?php echo ($user_status) ? 'selected' : ''; ?>><?php echo ucfirst($user_status) ?></option>
                                <option value="<?php echo ($user_status == 'active' ? 'inactive' : 'active') ?>"><?php echo ucfirst($user_status == 'active' ? 'inactive' : 'active') ?></option>
                            </select>
                        </div>
                    </div>
                </section>

                <hr>

                <section class="mb-4">
                    <div class="row mb-3">
                        <h5>Personal Information</h5>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <h6 class="">First Name</h6>
                            <input name="first_name" updateEnabled class="form-control"
                                type="text" value="<?= htmlspecialchars($user_firstName) ?>">
                        </div>
                        <div class="col-md-4">
                            <h6 class="">Middle Name</h6>
                            <input name="middle_name" updateEnabled class="form-control"
                                type="text" value="<?= htmlspecialchars($user_middleName) ?>">
                        </div>
                        <div class="col-md-4">
                            <h6 class="">Last Name</h6>
                            <input name="last_name" updateEnabled class="form-control"
                                type="text" value="<?= htmlspecialchars($user_lastName) ?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <h6 class="">Date of Birth</h6>
                            <input name="dob" updateEnabled class="form-control" type="date"
                                value="<?= htmlspecialchars($user_dob) ?>">
                        </div>
                        <div class="col-md-4">
                            <h6 class="">Gender</h6>
                            <select name="gender" class="form-select">
                                <option value="male" <?php echo ($user_gender == 'male') ? 'selected' : ''; ?>>Male</option>
                                <option value="female" <?php echo ($user_gender == 'female') ? 'selected' : ''; ?>>Female</option>
                            </select>
                        </div>

                    </div>
                </section>
                <div class="d-flex gap-2 justify-content-end align-items-center p-2">
                    <button type="submit" class="btn btn-success d-flex gap-2">
                        <i class="bi bi-floppy-fill"></i>
                        Update
                    </button>
                    <span type="button" class="btn btn-danger d-flex gap-2" id="btnDelete" role="button">
                        <i class="bi bi-trash-fill"></i>
                        Delete
                    </span>
                    <script>
                        $(document).ready(function() {
                            // Handle the Delete button click
                            $("#btnDelete").on("click", function() {
                                // Dynamically create and insert the modal into the DOM
                                const modalHTML = `
                                                <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="confirmationModalLabel">Confirm Action</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Are you sure you want to delete this user? This action cannot be undone.
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>`;

                                // Append the modal to the body
                                $("body").append(modalHTML);

                                // Show the modal
                                $("#confirmationModal").modal("show");

                                // Handle the Confirm Delete button click
                                $("#confirmDelete").on("click", function() {
                                    $("#formAction").val("deleteUser");
                                    $("#confirmationModal").modal("hide");

                                    // Submit the form
                                    $("#formAccountInfo").submit();
                                });

                                // Remove the modal from the DOM after it is hidden
                                $("#confirmationModal").on("hidden.bs.modal", function() {
                                    $(this).remove();
                                });
                            });
                        });
                    </script>
                </div>
        </form>
        <hr>

        <section class="mb-4">
            <div class="row mb-3">
                <h5>Miscellaneous</h5>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <h6 class="">Created Date</h6>
                    <span class=""><?= htmlspecialchars($user_createdDate) ?></span>
                </div>
                <div class="col-md-4">
                    <h6 class="">Last Update Date</h6>
                    <span class=""><?= htmlspecialchars($user_lastUpdate) ?></span>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <h6 class="">Last Login:</h6>
                    <span
                        class=""><?= !empty($user_lastLogin) ? htmlspecialchars($user_lastLogin) : 'No activity yet.' ?></span>
                </div>
            </div>
        </section>

    </div>
</div>