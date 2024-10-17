// userFormModal.js

document.addEventListener("DOMContentLoaded", function () {
  const firstNameInput = document.getElementById("first_name");
  const lastNameInput = document.getElementById("last_name");
  const dobInput = document.getElementById("dob");
  const usernameInput = document.getElementById("username");
  const passwordInput = document.getElementById("password");
  const userForm = document.getElementById("userForm");

  // Function to generate username and password
  function generateCredentials() {
    const firstName = firstNameInput.value.trim().toLowerCase();
    const lastName = lastNameInput.value
      .trim()
      .toLowerCase()
      .replace(/\s+/g, "");
    const dob = dobInput.value;

    // Generate Username: first initial + last name + user ID
    const userId = document.getElementById("user_id").value;
    const username = `${firstName.charAt(0)}${lastName}${userId}`;
    usernameInput.value = username;

    // Generate Password: last name + dob (YYYYMMDD)
    if (dob) {
      const date = new Date(dob);
      const formattedDob = date.toISOString().split("T")[0].replace(/-/g, ""); // YYYYMMDD format
      const password = `${lastName}${formattedDob}`;
      passwordInput.value = password;
    } else {
      passwordInput.value = "";
    }
  }

  // Add event listeners to inputs
  firstNameInput.addEventListener("input", generateCredentials);
  lastNameInput.addEventListener("input", generateCredentials);
  dobInput.addEventListener("change", generateCredentials);

  // Reset form fields when modal is hidden
  const userFormModal = document.getElementById("userFormModal");
  userFormModal.addEventListener("hidden.bs.modal", function () {
    userForm.reset();
    usernameInput.value = ""; // Clear generated username field
    passwordInput.value = ""; // Clear generated password field
  });
});
