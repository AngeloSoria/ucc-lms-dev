<div class="modal fade" id="modal_LoginForm" tabindex="-1" aria-labelledby="modal_LoginForm" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header c-header">
                <h5 class="modal-title fs-5 text-center" id="modal_LoginForm">
                    SIGN IN
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <!-- Form inside the modal -->
                <form method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username:</label>
                        <input type="text" class="form-control px-3 py-2" id="username" name="username"
                            placeholder="Enter your username" required />
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password:</label>
                        <div class="position-relative" id="inputPasswordContainer">
                            <input type="password" class="form-control px-3 py-2" name="password" id="password"
                                placeholder="Enter your password" required>
                            <i class="bi bi-eye-slash-fill me-3 fs-5 position-absolute top-50 end-0 translate-middle-y"
                                id="togglePassword" role="button" onclick="togglePasswordInputText(this);"></i>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="rememberMe" name="remember_me" />
                            <label class="form-check-label" for="rememberMe">
                                Remember Me
                            </label>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="modal-footer">
                        <input type="submit" value="LOGIN" class="btn btn-primary c-primary px-4 py-2 fs-6 w-100">
                    </div>

                    <?php
                    // Display the invalid credentials message if login failed
                    if (isset($_SESSION['LOGIN_INVALID'])) {
                        echo '<p id="invalid-feedback" class="invalid-feedback d-block text-center fw-semibold">Invalid userid or password. Please try again.</p>';
                    } else if (isset($_SESSION['SESSION_LOCK_ERR'])) {
                        echo '<p id="invalid-feedback" class="invalid-feedback d-block text-center fw-semibold">User has been already logged in to different device.</p>';
                    } else if (isset($_SESSION['SESSION_EXPIRED_ERR'])) {
                        echo '<p id="invalid-feedback" class="invalid-feedback d-block text-center fw-semibold">User session has been expired, try to sign in again.</p>';
                    }
                    ?>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo asset('js/save_cookie_rememberme.js'); ?>"></script>