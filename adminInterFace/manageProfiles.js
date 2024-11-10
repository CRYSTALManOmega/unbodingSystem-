// Automatically load the Manage Profiles section
document.addEventListener("DOMContentLoaded", function () {
    loadManageProfiles();
});

// Function to load Manage Profiles section
function loadManageProfiles() {
    document.getElementById('main-content').innerHTML = `
        <h1>Manage Profiles</h1>
        <button onclick="showAddProfileForm()">Add New Profile</button>
        <div id="profile-form-container"></div>
        <div id="profiles">
            <table border="1">
                <thead>
                    <tr>
                        <th>Employee Name</th>
                        <th>Department</th>
                        <th>Email</th>
                        <th>National ID</th>
                        <th>Job Title</th>
                        <th>User Type</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="profiles-table-body">
                    <!-- Table rows will be populated here -->
                </tbody>
            </table>
        </div>
        <div id="toast" class="toast">Profile created successfully!</div>
    `;
    loadProfiles(); // Load existing profiles from the database
}

// Load existing profiles from the database
function loadProfiles() {
    fetch('fetchProfiles.php')
        .then(response => response.json())
        .then(data => {
            const profilesTableBody = document.getElementById('profiles-table-body');
            profilesTableBody.innerHTML = '';

            data.forEach(profile => {
                profilesTableBody.innerHTML += `
                    <tr>
                        <td>${profile.First_Name} ${profile.Last_Name}</td>
                        <td>${profile.Department_Name || 'N/A'}</td>
                        <td>${profile.Email}</td>
                        <td>${profile.National_Id}</td>
                        <td>${profile.Job_Title}</td>
                        <td>${profile.Type}</td>
                        <td>
                            <button onclick="editProfile(${profile.User_Id})">Edit</button>
                            <button onclick="deleteProfile(${profile.User_Id})">Delete</button>
                        </td>
                    </tr>
                `;
            });
        })
        .catch(error => console.error('Error loading profiles:', error));
}

// Function to open the Add Employee form in a new tab
function showAddProfileForm() {
    window.open("file:///C:/unbordingSystem/addEmployee/addEmployee.html", "_blank");
}

// Toast notification function
function showToast(message) {
    const toast = document.getElementById("toast");
    toast.textContent = message;
    toast.classList.add("show");
    setTimeout(() => {
        toast.classList.remove("show");
    }, 3000);
}

// Function to delete a profile
function deleteProfile(userId) {
    if (confirm("Are you sure you want to delete this profile?")) {
        fetch(`deleteEmployee.php?User_Id=${userId}`)
            .then(response => response.text())
            .then(data => {
                showToast(data.includes("successfully") ? "Profile deleted successfully!" : "Error deleting profile");
                loadProfiles(); // Reload profiles after deletion
            })
            .catch(error => console.error('Error deleting profile:', error));
    }
}

// Function to edit a profile by opening the addEmployee form pre-filled with the user's data
function editProfile(userId) {
    window.location.href = `file:///C:/unbordingSystem/addEmployee/addEmployee.html?User_Id=${userId}`;
}
