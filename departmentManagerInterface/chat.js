// Function to load Department Chat section
function loadChat() {
    document.getElementById('main-content').innerHTML = `
        <div class="chat-header">
            <img src="https://cdn-icons-png.flaticon.com/512/552/552721.png" alt="User Icon" class="user-icon">
            <div class="chat-info">
                <h3>Department Chat</h3>
                <p>Select for group info</p>
            </div>
        </div>
        <div class="chat-messages" id="chat-messages">
            <!-- Messages will be dynamically loaded here -->
        </div>
        <div class="chat-input">
            <input type="file" id="file-upload" class="file-upload" accept="image/*,video/*,application/pdf">
            <input type="text" id="message-input" class="message-input" placeholder="Type a message">
            <button id="send-message" class="send-message">Send</button>
        </div>
    `;

    setupChat();
}

// Chat setup function for sending messages
function setupChat() {
    const messageInput = document.getElementById('message-input');
    const sendMessageButton = document.getElementById('send-message');
    const chatMessages = document.getElementById('chat-messages');
    const fileUpload = document.getElementById('file-upload');

    // Function to add a message to the chat
    function addMessage(username, userType, message, timestamp, isEdited = false, isDeleted = false) {
        const messageElement = document.createElement('div');
        messageElement.classList.add('chat-message');

        // Display user info, message content, and timestamp
        messageElement.innerHTML = `
            <div class="message-user-info">
                <strong>${username}</strong> (${userType}) - <span class="timestamp">${timestamp}</span>
            </div>
            <div class="message-content ${isDeleted ? 'deleted' : ''}">
                ${isDeleted ? 'Deleted message' : message}${isEdited ? ' <i>(edited)</i>' : ''}
            </div>
            <div class="message-options">
                <button onclick="editMessage(this)" ${isDeleted ? 'disabled' : ''}>Edit</button>
                <button onclick="deleteMessage(this)">Delete</button>
            </div>
        `;
        chatMessages.appendChild(messageElement);
        chatMessages.scrollTop = chatMessages.scrollHeight; // Auto-scroll
        showToast('Message sent');
    }

    // Event listener for the send message button
    sendMessageButton.addEventListener('click', () => {
        const message = messageInput.value.trim();
        const username = "User Name"; // Replace with actual username from session data
        const userType = "User Type"; // Replace with actual user type from session data
        const timestamp = new Date().toLocaleTimeString();

        if (message) {
            addMessage(username, userType, message, timestamp);
            messageInput.value = ''; // Clear input after sending
        }
    });

    // Event listener for file uploads (images, videos, PDFs)
    fileUpload.addEventListener('change', (event) => {
        const file = event.target.files[0];
        if (file) {
            addFileMessage(file.name);
        }
    });
}

// Function to add a file message to the chat
function addFileMessage(fileName) {
    const fileElement = document.createElement('div');
    fileElement.classList.add('file-message');
    fileElement.innerHTML = `
        <img src="https://upload.wikimedia.org/wikipedia/commons/8/87/PDF_file_icon.svg" alt="File Icon">
        <span>${fileName}</span>
        <button onclick="openFile('${fileName}')">Open</button>
        <button onclick="saveFile('${fileName}')">Save as...</button>
    `;
    chatMessages.appendChild(fileElement);
    chatMessages.scrollTop = chatMessages.scrollHeight;
    showToast('File sent');
}

// Function to show a toast notification
function showToast(message) {
    const toast = document.createElement('div');
    toast.className = 'toast';
    toast.textContent = message;
    document.body.appendChild(toast);

    setTimeout(() => {
        toast.remove();
    }, 3000);
}

// Function to delete a message
function deleteMessage(button) {
    const messageElement = button.closest('.chat-message');
    const messageContent = messageElement.querySelector('.message-content');
    messageContent.textContent = 'Deleted message';
    messageContent.classList.add('deleted');
    showToast('Message deleted');
}

// Function to edit a message
function editMessage(button) {
    const messageElement = button.closest('.chat-message');
    const messageContent = messageElement.querySelector('.message-content');
    
    // Prevent editing deleted messages
    if (messageContent.classList.contains('deleted')) {
        showToast('Cannot edit a deleted message');
        return;
    }

    const newMessage = prompt('Edit your message:', messageContent.textContent.replace(' (edited)', ''));
    
    if (newMessage !== null && newMessage.trim() !== '') {
        messageContent.textContent = newMessage + ' (edited)';
        showToast('Message edited');
    }
}

// Placeholder functions for file interactions
function openFile(fileName) {
    showToast(`Opening file: ${fileName}`);
}

function saveFile(fileName) {
    showToast(`Saving file: ${fileName}`);
}
