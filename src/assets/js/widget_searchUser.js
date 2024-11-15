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
                    makeToast(
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