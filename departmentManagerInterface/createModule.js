// Function to show the "Create Module" form
function showCreateModuleForm() {
    document.getElementById('task-form-container').innerHTML = `
        <h2>Create Module</h2>
        <form id="create-module-form">
            <label for="module-name">Module Name:</label>
            <input type="text" id="module-name" name="module-name" placeholder="Module Name" required>
            <label for="module-description">Description:</label>
            <textarea id="module-description" name="module-description" placeholder="Module Description"></textarea>
            <button type="submit">Create Module</button>
        </form>
    `;
    document.getElementById('create-module-form').addEventListener('submit', createModule);
}

// Function to handle creating a module
function createModule(event) {
    event.preventDefault();
    const moduleName = document.getElementById('module-name').value;
    const moduleDescription = document.getElementById('module-description').value;

    // Send module data to the server
    fetch('createModule.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ moduleName, moduleDescription })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Module Created Successfully!');
            document.getElementById('create-module-form').reset(); // Clears the form after successful submission
        } else {
            alert('Failed to create module. Error: ' + data.error);
        }
    })
    .catch(error => console.error('Error:', error));
}
