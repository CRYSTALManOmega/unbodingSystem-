// Function to load Manage Tasks section with additional buttons 
function loadManageTasks() {
    document.getElementById('main-content').innerHTML = `
        <h1>Manage Tasks</h1>
        <button onclick="showCreateModuleForm()">Create Module</button>
        <button onclick="showAddQuestionForm()">Add Question</button>
        <button onclick="showAssignModuleForm()">Assign Module to Department</button>
        <button onclick="showViewModuleForm()">View Module</button>
        <button onclick="showViewAssignedModules()">View Assigned Modules</button>
        <button onclick="showGradeTasksForm()">Grade Tasks</button> <!-- Grade Tasks Button -->
        <button onclick="showAssignWelcomeVideoForm()">Assign Department Welcome Video</button> <!-- Assign Welcome Video Button -->
        <div id="task-form-container"></div>
    `;
}

// Function to show the "View Module" form
function showViewModuleForm() {
    document.getElementById('task-form-container').innerHTML = `
        <h2>View Module</h2>
        <form id="view-module-form">
            <label for="module-select">Select Module:</label>
            <select id="module-select" name="module-select" required></select>
            <button type="submit">View Questions</button>
        </form>
        <div id="questions-container"></div>
    `;

    fetchModules();

    document.getElementById('view-module-form').addEventListener('submit', loadModuleQuestions);
}

// Fetch modules and populate the dropdown, using the renamed file
function fetchModules() {
    fetch('getManagerModules.php')
        .then(response => response.json())
        .then(data => {
            const moduleSelect = document.getElementById('module-select');
            moduleSelect.innerHTML = data.modules
                .map(module => `<option value="${module.Module_Id}">${module.Name}</option>`)
                .join('');
        })
        .catch(error => console.error('Error fetching modules:', error));
}

// Function to load questions within a selected module
function loadModuleQuestions(event) {
    event.preventDefault();
    const moduleSelect = document.getElementById('module-select').value;

    // Placeholder: Fetch questions for the selected module
    const questions = [
        { id: 1, text: "What type of variable is game_title?", type: "multiple-choice", options: ["Numerical: Continuous", "Numerical: Discrete", "Categorical: Nominal", "Categorical: Ordinal"] },
        { id: 2, text: "What is the maximum number of players?", type: "written" }
    ];

    const questionsContainer = document.getElementById('questions-container');
    questionsContainer.innerHTML = questions.map(question => {
        if (question.type === "multiple-choice") {
            return `
                <div class="question multiple-choice" style="padding: 10px; border: 1px solid #ccc; margin-bottom: 10px;">
                    <p>${question.text}</p>
                    ${question.options.map(option => `
                        <label>
                            <input type="radio" name="question-${question.id}" value="${option}">
                            ${option}
                        </label><br>
                    `).join('')}
                    <button onclick="editQuestion(${question.id})">Edit Question</button>
                </div>
            `;
        } else {
            return `
                <div class="question written" style="padding: 10px; border: 1px solid #ccc; margin-bottom: 10px;">
                    <p>${question.text}</p>
                    <textarea placeholder="Enter your answer"></textarea>
                    <button onclick="editQuestion(${question.id})">Edit Question</button>
                </div>
            `;
        }
    }).join('');
}

// Function to show assigned modules and allow inactivation
function showViewAssignedModules() {
    document.getElementById('task-form-container').innerHTML = `
        <h2>Assigned Modules</h2>
        <div id="assigned-modules-container"></div>
    `;

    fetchAssignedModules();
}

// Fetch assigned modules from the server
function fetchAssignedModules() {
    fetch('getAssignedModules.php')
        .then(response => response.json())
        .then(data => {
            const assignedModulesContainer = document.getElementById('assigned-modules-container');
            if (data.success) {
                assignedModulesContainer.innerHTML = data.modules
                    .map(module => `
                        <div class="assigned-module-item">
                            <p><strong>Module Name:</strong> ${module.Name}</p>
                            <p><strong>Description:</strong> ${module.Description}</p>
                            <p><strong>Status:</strong> ${module.Module_Status}</p>
                            <button onclick="toggleModuleStatus(${module.Module_Id}, '${module.Module_Status}')">
                                ${module.Module_Status === 'active' ? 'Inactivate' : 'Activate'}
                            </button>
                        </div>
                    `).join('');
            } else {
                assignedModulesContainer.innerHTML = `<p>No modules assigned to this department.</p>`;
            }
        })
        .catch(error => console.error('Error fetching assigned modules:', error));
}

// Toggle module status
function toggleModuleStatus(moduleId, currentStatus) {
    fetch('toggleModuleStatus.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `module_id=${moduleId}&current_status=${currentStatus}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            showViewAssignedModules();
        } else {
            alert('Failed to update module status: ' + data.message);
        }
    })
    .catch(error => console.error('Error toggling module status:', error));
}

// Function to show Grade Tasks form
function showGradeTasksForm() {
    document.getElementById('task-form-container').innerHTML = `
        <h2>Grade Tasks</h2>
        <div id="submitted-modules-container"></div>
    `;

    fetchSubmittedModules();
}

// Fetch submitted modules for grading
function fetchSubmittedModules() {
    fetch('getSubmittedModules.php')
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('submitted-modules-container');
            if (data.success) {
                container.innerHTML = data.modules.map(module => `
                    <div class="submitted-module-item">
                        <p><strong>User:</strong> ${module.User_Name}</p>
                        <p><strong>Module:</strong> ${module.Module_Name}</p>
                        <p><strong>Status:</strong> ${module.Status}</p>
                        <button onclick="toggleSubmissionStatus(${module.Submission_Id}, '${module.Status}')">
                            ${module.Status === 'complete' ? 'Mark as Incomplete' : 'Mark as Complete'}
                        </button>
                    </div>
                `).join('');
            } else {
                container.innerHTML = `<p>No submitted modules found for grading.</p>`;
            }
        })
        .catch(error => console.error('Error fetching submitted modules:', error));
}

// Toggle submission status
function toggleSubmissionStatus(submissionId, currentStatus) {
    fetch('toggleSubmissionStatus.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `submission_id=${submissionId}&current_status=${currentStatus}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            showGradeTasksForm();
        } else {
            alert('Failed to update submission status: ' + data.message);
        }
    })
    .catch(error => console.error('Error toggling submission status:', error));
}

// Function to show form for assigning a welcome video
function showAssignWelcomeVideoForm() {
    document.getElementById('task-form-container').innerHTML = `
        <h2>Assign Welcome Video</h2>
        <form id="welcome-video-form">
            <label for="welcome-video">Select Video:</label>
            <input type="file" id="welcome-video" name="welcome-video" accept="video/*" required>
            <button type="submit">Assign Video</button>
        </form>
    `;

    document.getElementById('welcome-video-form').addEventListener('submit', assignWelcomeVideo);
}

// Assign welcome video
function assignWelcomeVideo(event) {
    event.preventDefault();
    const videoFile = document.getElementById('welcome-video').files[0];
    const formData = new FormData();
    formData.append('video', videoFile);

    fetch('assignWelcomeVideo.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
        } else {
            alert('Failed to assign welcome video: ' + data.error);
        }
    })
    .catch(error => console.error('Error assigning welcome video:', error));
}

// Placeholder for editing a question
function editQuestion(questionId) {
    alert(`Editing question ID: ${questionId}`);
}
