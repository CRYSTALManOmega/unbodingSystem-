// Show toast notification function
function showToast(message, isSuccess = true) {
    const toast = document.getElementById("toast");
    toast.textContent = message;
    toast.style.backgroundColor = isSuccess ? "#4CAF50" : "#f44336"; // Green for success, red for error
    toast.classList.add("show");
    setTimeout(() => {
        toast.classList.remove("show");
    }, 3000);
}

// Fetch departments and populate the dropdown
document.addEventListener("DOMContentLoaded", function() {
    fetchDepartments();
});

function fetchDepartments() {
    fetch("addEmployee.php?fetch_departments=true")
        .then(response => response.json())
        .then(data => {
            const departmentDropdown = document.getElementById("departmentName");
            const departmentIdField = document.getElementById("departmentId");

            // Clear existing options and add new ones
            departmentDropdown.innerHTML = "";
            data.forEach(department => {
                let option = document.createElement("option");
                option.value = department.Department_Id;
                option.textContent = department.Name;
                departmentDropdown.appendChild(option);
            });

            // Set department ID initially
            departmentIdField.value = departmentDropdown.value;

            // Update hidden department ID field when selection changes
            departmentDropdown.addEventListener("change", function() {
                departmentIdField.value = departmentDropdown.value;
            });
        })
        .catch(error => console.error("Error fetching departments:", error));
}

// Handle form submission for adding an employee
document.getElementById("addEmployeeForm").addEventListener("submit", function(event) {
    event.preventDefault();
    const formData = new FormData(event.target);

    // Send data to the server via POST
    fetch("addEmployee.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        if (data.toLowerCase().includes("success")) {
            showToast("Employee added successfully!");
            setTimeout(() => {
                window.location.href = "C:/unbordingSystem/adminInterface/adminInterface.html"; // Redirect to Manage Profiles
            }, 3000);
        } else {
            showToast("Error: " + data, false);
        }
    })
    .catch(error => {
        console.error("Error:", error);
        showToast("An error occurred. Please try again.", false);
    });
});
