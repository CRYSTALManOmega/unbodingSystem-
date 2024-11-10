// Function to show the "Assign Module" form with dynamic module names
function showAssignModuleForm() {
    document.getElementById('task-form-container').innerHTML = `
        <h2>Assign Module to Department</h2>
        <form id="assign-module-form">
            <label for="module-select">Select Module:</label>
            <select id="module-select" name="module-select" required></select>
            <button type="submit">Assign Module</button>
        </form>
    `;

    // Fetch and populate modules from the database
    fetchModules();

    document.getElementById('assign-module-form').addEventListener('submit', assignModuleToDepartment);
}

// Fetch modules created by the manager and populate the dropdown
function fetchModules() {
    fetch('get_manager_modules.php')
        .then(response => response.json())
        .then(data => {
            const moduleSelect = document.getElementById('module-select');
            moduleSelect.innerHTML = data.modules
                .map(module => `<option value="${module.Module_Id}">${module.Name}</option>`)
                .join('');
        })
        .catch(error => console.error('Error fetching modules:', error));
}

// Function to handle assigning the selected module to all department users
function assignModuleToDepartment(event) {
    event.preventDefault();
    const moduleId = document.getElementById('module-select').value;

    fetch('assign_module.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `module_id=${moduleId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
        } else {
            alert('Failed to assign module: ' + data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}
