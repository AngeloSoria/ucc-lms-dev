// All public usable js goes here.


/**
 * Toggles the visibility of a password input field and updates the associated icon.
 * 
 * @param {HTMLElement} toggler - The element that triggers the toggle (typically a button or icon wrapper).
 * 
 * The function:
 * 1. Switches the input field between "password" and "text" types.
 * 2. Updates the icon to reflect the new state:
 *    - Shows "bi-eye-slash-fill" when the password is visible.
 *    - Shows "bi-eye-fill" when the password is hidden.
 * 
 * The template:
 * <div class="flex-grow-1">
        <label for="password" class="form-label">Password</label>
        <div class="position-relative" id="inputPasswordContainer">
            <input type="password" class="form-control" id="password" placeholder="Enter Password">
            <i class="bi bi-eye-slash-fill me-3 fs-5 position-absolute top-50 end-0 translate-middle-y" id="togglePassword" role="button" onclick="togglePasswordInputText(this);"></i>
        </div>

    </div>
 */
function togglePasswordInputText(toggler) {
    // Find the password input field (assuming it's close to the icon)
    let inputPassword = $(toggler).closest("#inputPasswordContainer").find('input[type="password"], input[type="text"]');

    // Check if the current input type is 'password' and toggle it
    let isPassword = inputPassword.attr("type") === "password";
    inputPassword.attr("type", isPassword ? "text" : "password");

    // Toggle the icon class directly (since 'toggler' is the icon itself)
    $(toggler)
        .removeClass(isPassword ? "bi-eye-slash-fill" : "bi-eye-fill")
        .addClass(isPassword ? "bi-eye-fill" : "bi-eye-slash-fill");
}

$('.modal').on('hide.bs.modal', function (e) {
    // Check if the modal has the closing-confirmation attribute
    if ($(this).attr('closing-confirmation') !== undefined) {
        // Get the custom confirmation text
        var confirmationText = $(this).attr('closing-confirmation-text') || "Are you sure you want to close this modal?";

        // Show confirmation dialog with the custom text
        var confirmation = confirm(confirmationText);

        // If the user clicks "Cancel", prevent the modal from closing
        if (!confirmation) {
            e.preventDefault(); // Prevent the modal from closing
        }
    }
});

function showToast(toastType = 'default', title = 'Notification', message, delay = 3000) {
    // Create the toast container if it doesn't exist
    let toastContainer = document.querySelector('.toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.className = 'toast-container position-fixed bottom-0 end-0 p-3';
        document.body.appendChild(toastContainer);
    }

    // Create the toast element
    const toastElement = document.createElement('div');
    toastElement.className = 'toast';
    toastElement.setAttribute('role', 'alert');
    toastElement.setAttribute('aria-live', 'assertive');
    toastElement.setAttribute('aria-atomic', 'true');

    if(toastType === 'default') {
        toastElement.classList.add('bg-success');
        toastElement.classList.add('text-light');
    }else if(toastType === 'warning') {
        toastElement.classList.add('bg-warning');
        toastElement.classList.add('text-dark');
    } else if(toastType === 'danger') {
        toastElement.classList.add('bg-danger');
        toastElement.classList.add('text-white');
    }

    // Set the toast's inner HTML
    toastElement.innerHTML = `
      <div class="toast-header">
        <strong class="me-auto">${title}</strong>
        <small>Just now</small>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
      <div class="toast-body">
        ${message}
      </div>
    `;

    // Append the toast to the container
    toastContainer.appendChild(toastElement);

    // Initialize the toast with custom options
    const toast = new bootstrap.Toast(toastElement, {
        autohide: true,  // Automatic hiding
        delay: delay     // Custom delay (e.g., 10000 ms = 10 seconds)
    });

    // Show the toast
    toast.show();

    // Optionally remove the toast element after it hides
    toastElement.addEventListener('hidden.bs.toast', function () {
        toastElement.remove();
    });
}

