<!-- Force Update Password -->
<div class="modal fade" id="updatePasswordModal" tabindex="-1" aria-labelledby="updatePasswordModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="updatePasswordModalLabel">Update Password</h1>
            </div>
            <form id="updatePasswordForm" method="POST" action="<?php echo BASE_PATH_LINK . 'src/functions/updatePasswordRequest.php' ?>">
                <input type="hidden" name="action" value="updatePassword">
                <div class="modal-body">
                    <div class="">
                        Welcome <code class="fs-5"><?php echo $_SESSION['first_name'] ?>,</code><br>For security purposes, please update your password to proceed
                    </div>
                    <hr>
                    <!-- Form -->
                    <input type="hidden" name="updatePassword">
                    <div class="col mb-3">
                        <label for="inputNewPass" class="fw-thin">New Password:</label>
                        <div class="position-relative" id="inputPasswordContainer">
                            <input type="password" class="form-control px-3 py-2" name="inputNewPassword" id="inputNewPassword"
                                placeholder="Enter your password" required>
                            <i class="bi bi-eye-slash-fill me-3 fs-5 position-absolute top-50 end-0 translate-middle-y"
                                id="togglePassword" role="button" onclick="togglePasswordInputText(this);"></i>
                        </div>
                    </div>
                    <div class="col mb-3">
                        <label for="inputConfirmPass" class="fw-thin">Confirm Password:</label>
                        <div class="position-relative" id="inputPasswordContainer">
                            <input type="password" class="form-control px-3 py-2" name="inputConfirmPassword" id="inputConfirmPassword"
                                placeholder="Enter your password" required>
                            <i class="bi bi-eye-slash-fill me-3 fs-5 position-absolute top-50 end-0 translate-middle-y"
                                id="togglePassword" role="button" onclick="togglePasswordInputText(this);"></i>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" id="btnSubmit">Save changes</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Later</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#updatePasswordModal').modal('show');

        let inputPass1 = $('#inputNewPassword');
        let inputPass2 = $('#inputConfirmPassword');

        inputPass1.on('keyup', function() {
            let pass = $(this).val();
            if (pass.length >= 8) {
                inputPass1.removeClass('is-invalid');
            } else {
                inputPass1.addClass('is-invalid');
            }
        });

        inputPass2.on('keyup', function() {
            let pass1 = inputPass1.val();
            let pass2 = $(this).val();
            if (pass1 === pass2) {
                inputPass2.removeClass('is-invalid');
            } else {
                inputPass2.addClass('is-invalid');
            }
        });

        $('#btnSubmit').on('click', function(e) {
            let pass1 = inputPass1.val();
            let pass2 = inputPass2.val();
            let hasErrors = false;

            if (!pass1 || pass1.length < 8) {
                inputPass1.addClass('is-invalid');
                hasErrors = true;
            } else {
                inputPass1.removeClass('is-invalid');
            }

            if (!pass2 || pass1 !== pass2) {
                inputPass2.addClass('is-invalid');
                hasErrors = true;
            } else {
                inputPass2.removeClass('is-invalid');
            }

            if (hasErrors) {
                alert('Please provide valid password and confirm password');
                return;
            }

            // Change button text to 'Submitting...'
            let submitButton = $('#btnSubmit');
            submitButton.prop('disabled', false).text('Submitting...'); // Disable button and change text
        });
    });
</script>