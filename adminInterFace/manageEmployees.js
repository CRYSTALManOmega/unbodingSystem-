// Function to load the Manage Employees section
function loadManageEmployees() {
    document.getElementById('main-content').innerHTML = `
        <h1>Manage Employees</h1>
        <div class="employee-actions">
            <button id="addEmployeeButton" onclick="showAddEmployeeToDepartmentForm()">Add Employee to Department</button>
            <button id="deleteEmployeeButton" onclick="showDeleteEmployeeFromDepartmentForm()">Delete Employee from Department</button>
            <button id="assignManagerButton" onclick="showAssignManagerForm()">Assign Department Manager</button>
            <button id="unassignManagerButton" onclick="showUnassignManagerForm()">Unassign Department Manager</button>
        </div>
        <div id="employee-form-container"></div>
        <div id="toast" class="toast"></div>
    `;
}

// Function to set the active button indicator
function setActiveButton(buttonId) {
    document.querySelectorAll('.employee-actions button').forEach(button => {
        button.classList.remove('active-button');
    });
    document.getElementById(buttonId).classList.add('active-button');
}

// Add Employee to Department
function showAddEmployeeToDepartmentForm() {
    setActiveButton("addEmployeeButton");
    document.getElementById("employee-form-container").innerHTML = `
        <form id="addEmployeeForm">
            <label for="employeeInput">Employee Name or National ID:</label>
            <input type="text" id="employeeInput" name="employeeInput" required placeholder="Enter employee name or National ID">
            
            <label for="departmentInput">Department Name:</label>
            <input type="text" id="departmentInput" name="departmentInput" required placeholder="Enter department name">
            
            <button type="submit">Submit</button>
        </form>
    `;
    document.getElementById("addEmployeeForm").addEventListener("submit", async function(event) {
        event.preventDefault();
        const formData = new FormData(event.target);
        try {
            const response = await fetch('php/addEmployeeToDepartment.php', {
                method: 'POST',
                body: formData
            });
            const result = await response.text();
            showToast(result.includes("successfully") ? result : "Error: " + result, result.includes("successfully"));
        } catch (error) {
            showToast("An error occurred: " + error, false);
        }
    });
}

// Delete Employee from Department
function showDeleteEmployeeFromDepartmentForm() {
    setActiveButton("deleteEmployeeButton");
    document.getElementById("employee-form-container").innerHTML = `
        <form id="deleteEmployeeForm">
            <label for="employeeInput">Employee Name or National ID:</label>
            <input type="text" id="employeeInput" name="employeeInput" required placeholder="Enter employee name or National ID">
            
            <label for="departmentInput">Department Name:</label>
            <input type="text" id="departmentInput" name="departmentInput" required placeholder="Enter department name">
            
            <button type="submit">Submit</button>
        </form>
    `;
    document.getElementById("deleteEmployeeForm").addEventListener("submit", async function(event) {
        event.preventDefault();
        const formData = new FormData(event.target);
        try {
            const response = await fetch('php/deleteEmployeeFromDepartment.php', {
                method: 'POST',
                body: formData
            });
            const result = await response.text();
            showToast(result.includes("successfully") ? result : "Error: " + result, result.includes("successfully"));
        } catch (error) {
            showToast("An error occurred: " + error, false);
        }
    });
}

// Assign Department Manager
function showAssignManagerForm() {
    setActiveButton("assignManagerButton");
    document.getElementById("employee-form-container").innerHTML = `
        <form id="assignManagerForm">
            <label for="employeeInput">Employee Name or National ID:</label>
            <input type="text" id="employeeInput" name="employeeInput" required placeholder="Enter employee name or National ID">
            
            <label for="departmentInput">Department Name:</label>
            <input type="text" id="departmentInput" name="departmentInput" required placeholder="Enter department name">
            
            <button type="submit">Submit</button>
        </form>
    `;
    document.getElementById("assignManagerForm").addEventListener("submit", async function(event) {
        event.preventDefault();
        const formData = new FormData(event.target);
        try {
            const response = await fetch('php/assignManagerToDepartment.php', {
                method: 'POST',
                body: formData
            });
            const result = await response.text();
            showToast(result.includes("successfully") ? result : "Error: " + result, result.includes("successfully"));
        } catch (error) {
            showToast("An error occurred: " + error, false);
        }
    });
}

// Unassign Department Manager
function showUnassignManagerForm() {
    setActiveButton("unassignManagerButton");
    document.getElementById("employee-form-container").innerHTML = `
        <form id="unassignManagerForm">
            <label for="employeeInput">Manager Name or National ID:</label>
            <input type="text" id="employeeInput" name="employeeInput" required placeholder="Enter manager name or National ID">
            
            <label for="departmentInput">Department Name:</label>
            <input type="text" id="departmentInput" name="departmentInput" required placeholder="Enter department name">
            
            <button type="submit">Submit</button>
        </form>
    `;
    document.getElementById("unassignManagerForm").addEventListener("submit", async function(event) {
        event.preventDefault();
        const formData = new FormData(event.target);
        try {
            const response = await fetch('php/unassignManagerFromDepartment.php', {
                method: 'POST',
                body: formData
            });
            const result = await response.text();
            showToast(result.includes("successfully") ? result : "Error: " + result, result.includes("successfully"));
        } catch (error) {
            showToast("An error occurred: " + error, false);
        }
    });
}

// Show toast notification
function showToast(message, isSuccess = true) {
    const toast = document.getElementById("toast");
    toast.textContent = message;
    toast.style.backgroundColor = isSuccess ? "#4CAF50" : "#f44336"; // Green for success, red for error
    toast.classList.add("show");
    setTimeout(() => {
        toast.classList.remove("show");
    }, 3000);
}
