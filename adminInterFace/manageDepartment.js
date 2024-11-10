// Function to load the Manage Department section 
function loadManageDepartment() {
    document.getElementById('main-content').innerHTML = `
        <h1>Manage Departments</h1>
        <div class="department-actions">
            <button id="createDepartmentButton" onclick="showCreateDepartmentForm()">Create Department</button>
            <button id="deleteDepartmentButton" onclick="showDeleteDepartmentForm()">Delete Department</button>
            <button id="updateDepartmentButton" onclick="showUpdateDepartmentForm()">Update Department</button>
        </div>
        <div id="department-form-container"></div>
        <div id="department-table-container"></div>
        <div id="toast" class="toast">Action completed successfully!</div>
    `;
    loadDepartmentTable(); // Load the department table initially
}

// Toast notification for feedback messages
function showToast(message, isSuccess = true) {
    const toast = document.getElementById("toast");
    toast.textContent = message;
    toast.style.backgroundColor = isSuccess ? "#4CAF50" : "#f44336"; // Green for success, red for error
    toast.classList.add("show");
    setTimeout(() => toast.classList.remove("show"), 3000);
}

// Highlights the active button for better UX
function setActiveButton(buttonId) {
    document.querySelectorAll('.department-actions button').forEach(button => button.classList.remove('active-button'));
    document.getElementById(buttonId).classList.add('active-button');
}

// Create Department Form with AJAX
function showCreateDepartmentForm() {
    setActiveButton("createDepartmentButton");
    document.getElementById("department-form-container").innerHTML = `
        <form id="createDepartmentForm" class="form-container">
            <label for="departmentName">Department Name:</label>
            <input type="text" id="departmentName" name="departmentName" required>
            <label for="managerId">Manager ID (not editable):</label>
            <input type="text" id="managerId" name="managerId" readonly style="background-color: #e9ecef; color: #6c757d; cursor: not-allowed;" title="This field is managed elsewhere.">
            <label for="description">Description (Optional):</label>
            <textarea id="description" name="description"></textarea>
            <label for="location">Location (Optional):</label>
            <input type="text" id="location" name="location">
            <button type="submit">Create</button>
        </form>
    `;
    document.getElementById("createDepartmentForm").onsubmit = async function(event) {
        event.preventDefault();
        const formData = new FormData(document.getElementById("createDepartmentForm"));
        const response = await fetch('php/create_department.php', { method: 'POST', body: formData });
        const result = await response.text();
        showToast(result.includes("successfully") ? result : "Error: " + result, result.includes("successfully"));
        loadDepartmentTable();
    };
}

// Delete Department Form with AJAX and table display
function showDeleteDepartmentForm() {
    setActiveButton("deleteDepartmentButton");
    document.getElementById("department-form-container").innerHTML = `
        <form id="deleteDepartmentForm">
            <label for="deleteDepartmentName">Department Name:</label>
            <input type="text" id="deleteDepartmentName" name="deleteDepartmentName" required>
            <button type="submit">Delete</button>
        </form>
    `;
    document.getElementById("deleteDepartmentForm").onsubmit = async function(event) {
        event.preventDefault();
        const departmentName = document.getElementById("deleteDepartmentName").value;
        const response = await fetch('php/delete_department.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ departmentName })
        });
        const result = await response.text();
        showToast(result.includes("successfully") ? result : "Error: " + result, result.includes("successfully"));
        loadDepartmentTable();
    };
}

// Update Department Form with AJAX, loading department data
function showUpdateDepartmentForm() {
    setActiveButton("updateDepartmentButton");
    document.getElementById("department-form-container").innerHTML = `
        <form id="searchUpdateDepartmentForm">
            <label for="searchDepartment">Search Department Name:</label>
            <input type="text" id="searchDepartment" name="searchDepartment" required>
            <button type="button" onclick="loadUpdateForm()">Search</button>
        </form>
        <div id="updateFormContainer"></div>
    `;
}

// Load Update Form with existing department data
async function loadUpdateForm() {
    const departmentName = document.getElementById("searchDepartment").value;
    const response = await fetch('php/get_department.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ departmentName })
    });
    const department = await response.json();

    if (!department) {
        showToast("Department not found.", false);
        return;
    }

    document.getElementById("updateFormContainer").innerHTML = `
        <form id="updateDepartmentForm" class="form-container">
            <label for="updateDepartmentName">Department Name:</label>
            <input type="text" id="updateDepartmentName" value="${department.Name}" required>
            <label for="updateManagerId">Manager ID (not editable):</label>
            <input type="text" id="updateManagerId" value="${department.Manager_Id || ''}" readonly style="background-color: #e9ecef; color: #6c757d; cursor: not-allowed;" title="This field is managed elsewhere.">
            <label for="updateDescription">Description (Optional):</label>
            <textarea id="updateDescription">${department.Description || ''}</textarea>
            <label for="updateLocation">Location (Optional):</label>
            <input type="text" id="updateLocation" value="${department.Location || ''}">
            <button type="submit">Update</button>
        </form>
    `;

    document.getElementById("updateDepartmentForm").onsubmit = async function(event) {
        event.preventDefault();
        const formData = new FormData(document.getElementById("updateDepartmentForm"));
        formData.append('departmentId', department.Department_Id);
        const response = await fetch('php/update_department.php', { method: 'POST', body: formData });
        const result = await response.text();
        showToast(result.includes("successfully") ? result : "Error: " + result, result.includes("successfully"));
        loadDepartmentTable();
    };
}

// Load all departments and display in a table
async function loadDepartmentTable() {
    const response = await fetch('php/get_all_departments.php');
    const departments = await response.json();
    const tableHtml = `
        <table border="1">
            <thead>
                <tr>
                    <th>Department ID</th>
                    <th>Name</th>
                    <th>Manager ID</th>
                    <th>Description</th>
                    <th>Location</th>
                </tr>
            </thead>
            <tbody>
                ${departments.map(department => `
                    <tr>
                        <td>${department.Department_Id}</td>
                        <td>${department.Name}</td>
                        <td>${department.Manager_Id || 'N/A'}</td>
                        <td>${department.Description || ''}</td>
                        <td>${department.Location || ''}</td>
                    </tr>
                `).join('')}
            </tbody>
        </table>
    `;
    document.getElementById("department-table-container").innerHTML = tableHtml;
}
