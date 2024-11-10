// Function to display modules created by the manager in the same department
function loadModules() {
    fetch('get_manager_modules.php')
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('module-list');
            container.innerHTML = data.modules
                .map(module => `
                    <div class="module-item">
                        <h3>${module.Name}</h3>
                        <p>${module.Description}</p>
                        <p>Status: ${module.Module_Status}</p>
                        <button onclick="toggleModuleStatus(${module.Module_Id}, '${module.Module_Status}')">
                            ${module.Module_Status === 'active' ? 'Deactivate' : 'Activate'}
                        </button>
                    </div>
                `).join('');
        })
        .catch(error => console.error('Error fetching modules:', error));
}

// Toggle module status between 'active' and 'inactive'
function toggleModuleStatus(moduleId, currentStatus) {
    const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
    fetch('toggle_module_status.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `module_id=${moduleId}&new_status=${newStatus}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            loadModules(); // Refresh the module list
        } else {
            alert('Error updating status: ' + data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}
