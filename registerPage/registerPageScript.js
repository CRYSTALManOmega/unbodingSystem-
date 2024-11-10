// Show toast notification function
function showToast(message) {
    const toast = document.getElementById("toast");
    toast.textContent = message;
    toast.classList.add("show");
    setTimeout(() => {
        toast.classList.remove("show");
    }, 3000);
}

// Handle form submission for registration
document.getElementById("registerForm").addEventListener("submit", function(event) {
    event.preventDefault();
    const formData = new FormData(event.target);

    // Send data to the server via POST
    fetch("registerProcess.php", {
        method: "POST",
        body: formData,
    })
    .then(response => {
        if (!response.ok) {
            throw new Error("Network response was not ok");
        }
        return response.text();
    })
    .then(data => {
        showToast(data);
        event.target.reset();  // Clear form after submission
    })
    .catch(error => {
        console.error("Error:", error);
        showToast("An error occurred. Please try again.");
    });
});
