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

$(".modal").on("hide.bs.modal", function (e) {
  // Check if the modal has the closing-confirmation attribute
  if ($(this).attr("closing-confirmation") !== undefined) {
    // Get the custom confirmation text
    var confirmationText =
      $(this).attr("closing-confirmation-text") ||
      "Are you sure you want to close this modal?";

    // Show confirmation dialog with the custom text
    var confirmation = confirm(confirmationText);

    // If the user clicks "Cancel", prevent the modal from closing
    if (!confirmation) {
      e.preventDefault(); // Prevent the modal from closing
    } else {
      // Clear all inputs or textboxes contents.
      $(this).find('input[type="text"], input[type="password"]').val("");
    }
  }
});

// FOR search input element.
document.addEventListener("DOMContentLoaded", function () {
  // Function to initialize the search input and user list functionality
  function initializeUserSearch(inputContainerId) {
    const inputContainer = document.getElementById(inputContainerId);

    if (inputContainer) {
      // Check if the container exists
      const inputBox = inputContainer.querySelector(".system_input-box");
      const dropContainer = inputContainer.querySelector(".drop-container");
      const userListContainer = inputContainer.querySelector(
        ".userlist-container"
      );

      // Sample data representing users
      const fetchedUsers = [
        { id: 1001, name: "John Doe" },
        { id: 1002, name: "Alice Smith" },
        { id: 1003, name: "Bob Johnson" },
        { id: 1004, name: "Charlie Brown" },
        { id: 1005, name: "Diana Prince" },
        { id: 1006, name: "Ethan Hunt" },
      ];

      inputBox.addEventListener("focus", function () {
        dropContainer.classList.add("active");
        populateDropdown(fetchedUsers, dropContainer); // Populate dropdown on focus
      });

      inputBox.addEventListener("blur", function () {
        dropContainer.classList.remove("active");
      });

      // Prevent hiding on click inside dropContainer
      dropContainer.addEventListener("mousedown", function (event) {
        event.preventDefault(); // Prevents blur on input
      });

      // Function to populate dropdown with user data
      function populateDropdown(users, dropContainer) {
        dropContainer.innerHTML = ""; // Clear existing items
        users.forEach((user) => {
          const item = document.createElement("div");
          item.classList.add("search-item", "border");
          item.setAttribute("role", "button");
          item.setAttribute("title", "Add");
          item.onclick = function () {
            addUserToList(user, userListContainer); // Call function to add user to list
          };

          // Create content for search-item
          const nameElement = document.createElement("p");
          nameElement.textContent = `${user.name} (${user.id})`;
          item.appendChild(nameElement);

          const iconElement = document.createElement("i");
          iconElement.classList.add("bi", "bi-plus-lg");
          item.appendChild(iconElement);

          dropContainer.appendChild(item); // Append to dropdown
        });
      }

      // Function to filter users based on input value
      inputBox.addEventListener("input", function () {
        const query = inputBox.value.toLowerCase();
        const filteredUsers = fetchedUsers.filter(
          (user) =>
            user.name.toLowerCase().includes(query) ||
            user.id.toString().includes(query)
        );
        populateDropdown(filteredUsers, dropContainer); // Update dropdown based on filter
      });

      // Function to add user to the user list
      function addUserToList(user, userListContainer) {
        const userListContents =
          userListContainer.querySelector(".userlist-contents");

        // Check if the user is already in the list to avoid duplicates
        const existingUserItem = Array.from(userListContents.children).some(
          (item) => {
            return (
              item
                .querySelector(".profile-text")
                .textContent.includes(user.name) &&
              item.querySelector(".profile-text").textContent.includes(user.id)
            );
          }
        );

        if (existingUserItem) {
          showToast(
            "warning",
            "Duplicate User",
            `User already added: ${user.name} (${user.id})`
          ); // Show warning toast
          return; // Do not add the user if they already exist
        }

        const userItem = document.createElement("div");
        userItem.classList.add("userlist-item");

        const checkbox = document.createElement("div");
        checkbox.classList.add("profile-checkbox");
        checkbox.innerHTML = `<input class="form-check-input" type="checkbox" name="" id="">`;

        const profileContext = document.createElement("div");
        profileContext.classList.add("profile-context");
        profileContext.innerHTML = `<p class="profile-text">${user.name}</p><p class="profile-text">(${user.id})</p>`;

        const profileControls = document.createElement("div");
        profileControls.classList.add("profile-controls");
        profileControls.innerHTML = `
                        <div class="control-view-profile" role="button" title="View Profile">
                            <i class="bi bi-info-circle-fill"></i>
                        </div>
                        <div class="control-view-remove" role="button" title="Remove" onclick="removeUserFromList(this)">
                            <i class="bi bi-person-fill-dash"></i>
                        </div>
                    `;

        userItem.appendChild(checkbox);
        userItem.appendChild(profileContext);
        userItem.appendChild(profileControls);
        userListContents.appendChild(userItem); // Add new user to the user list
        dropContainer.classList.remove("active"); // Hide dropdown after adding user
        inputBox.value = ""; // Clear input box after adding
      }
    }
  }

  // Initialize search functionality for both containers
  initializeUserSearch("system_input-box-container");
  initializeUserSearch("user_1");
});

// Function to remove user from the list
function removeUserFromList(element) {
  const userItem = element.closest(".userlist-item");
  if (userItem) {
    userItem.remove(); // Remove user item from the list
  }
}

function runFunctionByStringName(functionName) {
  if (typeof window[functionName] === "function") {
    window[functionName]();
  } else {
    console.error(`${functionName} is not a function.`);
  }
}
