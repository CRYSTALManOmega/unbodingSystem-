document.addEventListener("DOMContentLoaded", function() {
    loadHome(); // Automatically load the Home page
});

async function loadHome() {
    try {
        // Fetch user data and department-specific content
        const [userData, departmentContent] = await Promise.all([
            fetch('getUserData.php').then(res => res.json()),
            fetch('getDepartmentContent.php').then(res => res.json())
        ]);

        // Display user's first name in the welcome message
        const username = userData.firstName || "User"; // Default to "User" if no name is available
        const videoPath = departmentContent.videoPath || "path-to-default-video.mp4";
        const taskUpdates = departmentContent.modules || [];

        document.getElementById('main-content').innerHTML = `
            <h1>Welcome, ${username}!</h1>
            <div class="video-box">
                <h2>Message from the Department Manager</h2>
                <video controls width="600">
                    <source src="${videoPath}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>
            <div class="task-updates-box">
                <h2>Task Updates</h2>
                <ul id="task-updates">
                    ${taskUpdates.map(task => `<li>${task.name} assigned on ${task.assignedDate}</li>`).join('')}
                </ul>
            </div>
        `;
    } catch (error) {
        console.error("Error loading home content:", error);
    }
}
