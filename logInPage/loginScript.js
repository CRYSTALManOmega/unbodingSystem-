document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault();
    checkLogin();
});

function checkLogin() {
    const usernameInput = document.getElementById('userName').value;
    const passwordInput = document.getElementById('password').value;
    const statusMessage = document.getElementById('statusMessage');

    const formData = new FormData();
    formData.append('username', usernameInput);
    formData.append('password', passwordInput);

    fetch('login.php', {
        method: 'POST',
        body: formData,
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            statusMessage.textContent = 'Login successful! Redirecting...';
            statusMessage.style.color = 'green';
            setTimeout(() => {
                window.location.href = data.redirect;
            }, 2000);
        } else {
            statusMessage.textContent = data.message;
            statusMessage.style.color = 'red';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        statusMessage.textContent = 'An error occurred. Please try again.';
        statusMessage.style.color = 'red';
    });
}
