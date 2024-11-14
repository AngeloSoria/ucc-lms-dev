function makeToast(toastType = "default", message, delay = 5000) {
  // Create the toast container if it doesn't exist
  let toastContainer = document.querySelector(".toast-container");
  if (!toastContainer) {
    toastContainer = document.createElement("div");
    toastContainer.className =
      "toast-container position-fixed bottom-0 end-0 p-3";
    document.body.appendChild(toastContainer);
  }

  // Create the toast element
  const toastElement = document.createElement("div");
  toastElement.className = "toast";
  toastElement.setAttribute("role", "alert");
  toastElement.setAttribute("aria-live", "assertive");
  toastElement.setAttribute("aria-atomic", "true");

  if (toastType === "default") {
    toastElement.classList.add("bg-success", "text-light");
  } else if (toastType === "warning") {
    toastElement.classList.add("bg-warning", "text-dark");
  } else if (toastType === "error") {
    toastElement.classList.add("bg-danger", "text-white");
  } else if (toastType === "success") {
    toastElement.classList.add("cbg-primary", "text-white"); // Fixed class name
  } else {
    return console.error(`Invalid toast type: ${toastType}`); // Invalid toast type.
  }

  // Set the toast's inner HTML
  toastElement.innerHTML = `
      <div class="toast-header">
        <strong class="me-auto">${toastType.toUpperCase()}</strong>
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
    autohide: true, // Automatic hiding
    delay: delay, // Custom delay (e.g., 10000 ms = 10 seconds)
  });

  // Show the toast
  toast.show();

  // Optionally remove the toast element after it hides
  toastElement.addEventListener("hidden.bs.toast", function () {
    toastElement.remove();
  });
}
