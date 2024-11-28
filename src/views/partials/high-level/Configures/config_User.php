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
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="updateUserInfo">
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
                            <h6 class="">Password</h6>
                            <input name="password" updateEnabled class="form-control"
                                type="password" value="<?= htmlspecialchars($user_username) ?>">
                        </div>
                        <div class="col-md-4">
                            <h6 class="">Requires Password Change</h6>
                            <select name="requirePasswordReset" class="form-select">
                                <option value="1" <?php echo ($user_requirePasswordReset) ? 'selected' : ''; ?>>Yes</option>
                                <option value="0" <?php echo (!$user_requirePasswordReset) ? 'selected' : ''; ?>>No</option>
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
                    <span type="button" class="btn btn-danger d-flex gap-2" id="btnDelete" onclick="alert('Work in progress...')">
                        <i class="bi bi-trash-fill"></i>
                        Delete
                    </span>
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