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
  let inputPassword = $(toggler)
    .closest("#inputPasswordContainer")
    .find('input[type="password"], input[type="text"]');

  // Check if the current input type is 'password' and toggle it
  let isPassword = inputPassword.attr("type") === "password";
  inputPassword.attr("type", isPassword ? "text" : "password");

  // Toggle the icon class directly (since 'toggler' is the icon itself)
  $(toggler)
    .removeClass(isPassword ? "bi-eye-slash-fill" : "bi-eye-fill")
    .addClass(isPassword ? "bi-eye-fill" : "bi-eye-slash-fill");
}


// Hide.bs.modal: Runs before the modal starts to hide
$(".modal").on("hide.bs.modal", function (e) {
  if ($(this).attr("closing-confirmation") === "true") {
    let confirmationText = $(this).attr("closing-confirmation-text") || "Are you sure you want to close this modal?";
    if (!confirm(confirmationText)) {
      e.preventDefault();
      return;
    }
  }

  // Clear fields only if confirmation passed
  // Clear text and password inputs if not disabled, readonly, or hidden
  $(this).find('input[type="text"]:not([disabled]):not([readonly]):not(:hidden), input[type="password"]:not([disabled]):not([readonly]):not(:hidden)').val("");

  // Reset dropdowns if not disabled, readonly, or hidden
  $(this).find("select:not([disabled]):not([readonly]):not(:hidden)").val(function () {
    return $(this).find("option:first").val();
  });

  // Clear date inputs if not disabled, readonly, or hidden
  $(this).find('input[type="date"]:not([disabled]):not([readonly]):not(:hidden)').val("");

  // Uncheck radio and checkbox inputs if not disabled, readonly, or hidden
  $(this).find('input[type="radio"]:not([disabled]):not([readonly]):not(:hidden), input[type="checkbox"]:not([disabled]):not([readonly]):not(:hidden)').prop("checked", false);
});



function runFunctionByStringName(functionName) {
  if (typeof window[functionName] === "function") {
    window[functionName]();
  } else {
    console.error(`${functionName} is not a function.`);
  }
}
