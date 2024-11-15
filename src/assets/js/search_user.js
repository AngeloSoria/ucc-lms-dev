document.addEventListener("DOMContentLoaded", async () => {
  const widgetSearchboxes = document.querySelectorAll("#widget_searchUser");
  const userlistContainer = document.querySelectorAll(".userlist-container");

  // Function to fetch users from the server (fetch_users.php)
  async function retrieveUsers() {
    try {
      const response = await fetch(
        "../../../views/partials/high-level/fetch_teachers.php"
      );
      const data = await response.json();

      if (data.teachers) {
        return data.teachers; // Return the list of teachers from the backend response
      } else {
        console.error("Error fetching users:", data.error);
        return [];
      }
    } catch (error) {
      console.error("Error:", error);
      return [];
    }
  }

  // Wait for the users to be retrieved
  const USERS = await retrieveUsers();
  console.log(USERS); // Now you can use USERS after it's fetched.

  // Define the function to add users to the container
  function addToContainer(containerID, data) {
    const container = document.querySelector(
      "#userlist_contents-" + containerID
    );
    if (container) {
      const maxContentCount = container.getAttribute(
        "target-container-max-content"
      );

      // Check if maxContentCount is -1, which means no limit
      if (maxContentCount !== "-1") {
        if (container.children.length >= maxContentCount) {
          makeToast(
            "warning",
            "Maximum Content Reached",
            `(${container.children.length}/${maxContentCount}) Maximum number of content reached. (${containerID})`
          ); // Show warning toast
          return; // Do not add more users if the container is full
        }
      }

      // Check if the user is already in the list to avoid duplicates
      const existingUserItem = Array.from(container.children).some((item) => {
        return item
          .querySelector(".profile-text")
          .textContent.includes(data.userid);
      });

      if (existingUserItem) {
        makeToast(
          "warning",
          "Duplicate User",
          `User already added: ${data.username} (${data.userid})`
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
      profileContext.innerHTML = `<p class="profile-text">${data.username}</p><p class="profile-text">(${data.userid})</p>`;

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
      container.appendChild(userItem); // Add new user to the user list
    }
  }

  // Define the function to create a searched item in the dropdown
  function createSearchedItem(inputSearch, dropContainer, containerID, data) {
    const item = document.createElement("div");
    item.classList.add("search-item", "border");
    item.setAttribute("role", "button");
    item.setAttribute("title", "Add");
    item.onclick = function () {
      setTimeout(() => {
        inputSearch.blur();
        dropContainer.classList.remove("active");
        dropContainer.innerHTML = "";
        inputSearch.value = "";
        addToContainer(containerID, {
          username: data.username,
          userid: data.userid,
        });
      }, 100);
    };

    const nameElement = document.createElement("p");
    nameElement.textContent = `${data.username} (${data.userid})`;
    item.appendChild(nameElement);

    const iconElement = document.createElement("i");
    iconElement.classList.add("bi", "bi-plus-lg");
    item.appendChild(iconElement);

    dropContainer.appendChild(item); // Append to dropdown
  }

  // Load input box for search
  widgetSearchboxes.forEach((element) => {
    const inputSearch = element.querySelector(".system_input-box");
    const dropContainer = element.querySelector(".drop-container");
    const containerID = element.getAttribute("data-container-id");

    if (!containerID) {
      makeToast(
        "warning",
        "Attribute Missing",
        "Data Container ID not specified"
      );
      return;
    }

    if (inputSearch) {
      inputSearch.addEventListener("input", function (event) {
        dropContainer.innerHTML = ""; // Clear existing dropdown items

        const searchQuery = event.target.value.toLowerCase();
        if (searchQuery.length > 0) {
          dropContainer.classList.add("active");

          const filteredUsers = USERS.filter((user) => {
            return (
              user.first_name.toLowerCase().includes(searchQuery) ||
              user.last_name.toLowerCase().includes(searchQuery) ||
              String(user.id).includes(searchQuery)
            );
          });

          if (filteredUsers.length > 0) {
            filteredUsers.forEach((thisUser) => {
              createSearchedItem(inputSearch, dropContainer, containerID, {
                username: thisUser.name,
                userid: thisUser.user_id,
              });
            });
          } else {
            console.log("No matching users found");
          }
        } else {
          dropContainer.classList.remove("active");
        }
      });
    }
  });
});
