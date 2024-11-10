// Function to load the logged-in user's profile data
function loadUserProfile() {
    fetch('php/getUserInfo.php')
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
            } else {
                document.getElementById('main-content').innerHTML = `
                    <h1>User Profile</h1>
                    <table border="1">
                        <tr><td><strong>Username</strong></td><td>${data.Username}</td></tr>
                        <tr><td><strong>Email</strong></td><td>${data.Email}</td></tr>
                        <tr><td><strong>First Name</strong></td><td>${data.First_Name}</td></tr>
                        <tr><td><strong>Last Name</strong></td><td>${data.Last_Name}</td></tr>
                        <tr><td><strong>Job Title</strong></td><td>${data.Job_Title}</td></tr>
                        <tr><td><strong>Department</strong></td><td>${data.Department_Id || 'Not Assigned'}</td></tr>
                    </table>
                `;
            }
        })
        .catch(error => console.error("Error loading user profile:", error));
}
