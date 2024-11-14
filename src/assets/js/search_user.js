function retrieveUsers() {
    // AJAX Requests for users retrieval from database.

    // Placeholder Data
    const sample_retrieved_users = [
        {
            id: 1001,
            name: 'John Doe',
            email: 'john.doe@example.com',
            role: 'Admin',
            department: 'Marketing'
        },
        {
            id: 1002,
            name: 'Jane Smith',
            email: 'jane.smith@example.com',
            role: 'Developer',
            department: 'Engineering'
        }
    ];
    return sample_retrieved_users;
}

document.addEventListener('DOMContentLoaded', () => {
    const widgetSearchboxes = document.querySelectorAll('#widget_searchUser');
    const userlistContainer = document.querySelectorAll('.userlist-container');

    // Data
    const USERS = retrieveUsers();

    function addToContainer(containerID, data) {
        const container = document.querySelector('#userlist_contents-' + containerID);
        if (container) {
            const maxContentCount = container.getAttribute('target-container-max-content');
            // Check if maxContentCount is -1, which means no limit, or if the current count is less than the limit
            
            if (maxContentCount !== "-1") {
                if (container.children.length >= maxContentCount) {
                    showToast('warning', 'Maximum Content Reached', `(${container.children.length}/${maxContentCount}) Maximum number of content reached. (${containerID})`); // Show warning toast
                    return; // Do not add more users if the container is full and maxContentCount is not -1
                }
            }
            

            // Check if the user is already in the list to avoid duplicates
            const existingUserItem = Array.from(container.children).some(item => {
                let isExisting = false;
                item.querySelectorAll('.profile-text').forEach(element => {
                    if (element.textContent.includes(data.userid)) {
                        isExisting = true;
                        return;
                    }
                });
                return isExisting;
            });

            if (existingUserItem) {
                showToast('warning', 'Duplicate User', `User already added: ${data.username} (${data.userid})`); // Show warning toast
                return; // Do not add the user if they already exist
            }

            const userItem = document.createElement('div');
            userItem.classList.add('userlist-item');

            const checkbox = document.createElement('div');
            checkbox.classList.add('profile-checkbox');
            checkbox.innerHTML = `<input class="form-check-input" type="checkbox" name="" id="">`;

            const profileContext = document.createElement('div');
            profileContext.classList.add('profile-context');
            profileContext.innerHTML = `<p class="profile-text">${data.username}</p><p class="profile-text">(${data.userid})</p>`;

            const profileControls = document.createElement('div');
            profileControls.classList.add('profile-controls');
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

    function createSearchedItem(inputSearch, dropContainer, containerID, data) {
        const item = document.createElement('div');
        item.classList.add('search-item', 'border');
        item.setAttribute('role', 'button');
        item.setAttribute('title', 'Add');
        item.onclick = function () {
            // addUserToList(user, userListContainer); // Call function to add user to list
            setTimeout(() => {
                inputSearch.blur();
                dropContainer.classList.remove('active');
                dropContainer.innerHTML = '';
                inputSearch.value = '';
                addToContainer(containerID, {
                    username: data.username,
                    userid: data.userid
                })
            }, 100);
        };

        // Create content for search-item
        const nameElement = document.createElement('p');
        nameElement.textContent = `${data.username} (${data.userid})`;
        item.appendChild(nameElement);

        const iconElement = document.createElement('i');
        iconElement.classList.add('bi', 'bi-plus-lg');
        item.appendChild(iconElement);

        dropContainer.appendChild(item); // Append to dropdown
    }

    // Load input box
    widgetSearchboxes.forEach(element => {
        const inputSearch = element.querySelector('.system_input-box'); // Get the input element with id 'input_search'
        const dropContainer = element.querySelector('.drop-container');
        const containerID = element.getAttribute('data-container-id');

        if (!containerID) {
            showToast('warning', 'Attribute Missing', 'Data Container ID not specified');
            return;
        }

        if (inputSearch) { // Check if the input element exists
            inputSearch.addEventListener('input', function (event) {

                // Clear all existing child of dropContainer.
                dropContainer.innerHTML = '';

                let inputLength = event.target.value.length;
                if (inputLength > 0) {
                    dropContainer.classList.add('active');
                    let searchQuery = event.target.value.toLowerCase();
                    let filteredUsers = USERS.filter(user => {
                        return user.name.toLowerCase().includes(searchQuery) ||
                            user.email.toLowerCase().includes(searchQuery) ||
                            String(user.id).includes(searchQuery);
                    });
                    if (filteredUsers.length > 0) {
                        filteredUsers.forEach(thisUser => {
                            createSearchedItem(
                                inputSearch,
                                dropContainer,
                                containerID,
                                {
                                    username: thisUser.name,
                                    userid: thisUser.id
                                },
                            );
                        });
                    } else {
                        console.log('No matching users found');
                    }
                } else {
                    dropContainer.classList.remove('active');
                }
            });
        }
    });

})