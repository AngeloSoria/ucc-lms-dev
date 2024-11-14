// userFormModal.js

document.addEventListener("DOMContentLoaded", function () {
  const firstNameInput = document.getElementById("first_name");
  const lastNameInput = document.getElementById("last_name");
  const dobInput = document.getElementById("dob");
  const usernameInput = document.getElementById("username");
  const passwordInput = document.getElementById("password");
  const userForm = document.getElementById("userForm");
  const container_RoleType = document.getElementById("role_type_container");
  const dropdown_RoleType = document.getElementById("educational_level");

  // Function to generate username and password
  function generateCredentials() {
    const firstName = firstNameInput.value.trim();
    const lastName = lastNameInput.value.trim().replace(/\s+/g, "");
    const dob = dobInput.value;

    // Generate Username: first initial + last name (formatted) + user ID
    const userId = document.getElementById("user_id").value;
    const formattedFirstName = firstName.charAt(0).toUpperCase(); // First initial of first name
    const formattedLastName =
      lastName.charAt(0).toUpperCase() + lastName.slice(1).toLowerCase(); // Capitalize first letter of last name
    const username = `${formattedFirstName}${formattedLastName}.${userId}`; // Concatenate with a period
    usernameInput.value = username;

    // Generate Password: last name + MMDDYYYY format
    if (dob) {
      const date = new Date(dob);
      const formattedDob = `${(date.getMonth() + 1)
        .toString()
        .padStart(2, "0")}${date
          .getDate()
          .toString()
          .padStart(2, "0")}${date.getFullYear()}`; // MMDDYYYY format
      const password = `${formattedLastName}.${formattedDob}`; // Password is last name + formatted date
      passwordInput.value = password;
    } else {
      passwordInput.value = ""; // Clear the password if no DOB is provided
    }
  }

  // Add event listeners to inputs
  firstNameInput.addEventListener("input", generateCredentials);
  lastNameInput.addEventListener("input", generateCredentials);
  dobInput.addEventListener("change", generateCredentials);

  // Reset form fields when modal is hidden
  const userFormModal = document.getElementById("userFormModal");
  userFormModal.addEventListener("hidden.bs.modal", function () {
    console.log('ZZZZZZ');

    usernameInput.value = ""; // Clear generated username field
    passwordInput.value = ""; // Clear generated password field
    container_RoleType.classList.add("d-none");
  });

  // Function to add role type options to dropdown.
  function addRoleTypeOption(optionsArray) {
    for (let index = 0; index < optionsArray.length; index++) {
      const option = document.createElement("option");
      option.value = optionsArray[index];
      option.textContent = optionsArray[index];
      dropdown_RoleType.appendChild(option);
    }
  }

  // Clear role type options except the disabled option.
  function clearRoleTypeOptions() {
    Array.from(dropdown_RoleType.options).forEach(function (option) {
      if (!option.hasAttribute("disabled")) {
        dropdown_RoleType.removeChild(option);
      }
    });
  }

  // Event Listener for Role onselect dropdown.
  const roleSelect = document.getElementById("role");
  roleSelect.addEventListener("change", function () {
    const selectedRole = roleSelect.value;
    clearRoleTypeOptions();
    switch (selectedRole) {
      case "Teacher":
        container_RoleType.classList.remove("d-none");
        // Add options to role_type dropdown
        addRoleTypeOption(["SHS", "College"]);
        break;
      default:
        container_RoleType.classList.add("d-none");
        break;
    }
  });
});
