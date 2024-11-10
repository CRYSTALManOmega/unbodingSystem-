// Function to load employee data into edit form
function editProfile(userId) {
    fetch(`getEmployee.php?User_Id=${userId}`)
        .then(response => response.json())
        .then(profile => {
            document.getElementById('profile-form-container').innerHTML = `
                <h2>Edit Profile</h2>
                <form id="editProfileForm">
                    <label for="firstName">First Name:</label>
                    <input type="text" id="firstName" name="First_Name" value="${profile.First_Name}" required>
                    
                    <label for="lastName">Last Name:</label>
                    <input type="text" id="lastName" name="Last_Name" value="${profile.Last_Name}" required>

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="Email" value="${profile.Email}" required>

                    <label for="jobTitle">Job Title:</label>
                    <input type="text" id="jobTitle" name="Job_Title" value="${profile.Job_Title}" required>

                    <label for="departmentId">Department ID:</label>
                    <input type="text" id="departmentId" name="Department_Id" value="${profile.Department_Id || ''}" placeholder="Optional">

                    <label for="nationalId">National ID:</label>
                    <input type="text" id="nationalId" name="National_Id" value="${profile.National_Id}" required>

                    <label for="birthDate">Birth Date:</label>
                    <input type="date" id="birthDate" name="BirthDate" value="${profile.BirthDate}" required>

                    <button type="submit">Save Changes</button>
                </form>
            `;
            document.getElementById("editProfileForm").addEventListener("submit", function (event) {
                event.preventDefault();
                const formData = new FormData(event.target);
                formData.append("User_Id", userId); // Include the user ID

                fetch("updateEmployee.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    showToast(data);
                    loadProfiles(); // Refresh profiles list after update
                    document.getElementById('profile-form-container').innerHTML = ''; // Hide the form
                })
                .catch(error => console.error("Error updating profile:", error));
            });
        })
        .catch(error => console.error("Error loading profile for editing:", error));
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
