// Function to show the "Add Question" form and populate modules dynamically
function showAddQuestionForm() {
    document.getElementById('task-form-container').innerHTML = `
        <h2>Add Question</h2>
        <form id="add-question-form">
            <label for="module-select">Choose Module:</label>
            <select id="module-select" name="module-select" required>
                <option value="">Loading modules...</option>
            </select>
            <label for="question-text">Question:</label>
            <textarea id="question-text" name="question-text" placeholder="Enter your question here" required></textarea>
            <label for="question-type">Type:</label>
            <select id="question-type" name="question-type" required onchange="showChoiceOptions()">
                <option value="written">Written</option>
                <option value="multiple-choice">Multiple Choice</option>
            </select>
            <div id="choice-options-container"></div>
            
            <!-- File Upload Section -->
            <div id="file-upload-container">
                <label>Upload Files:</label>
                <div class="file-upload-item">
                    <input type="file" name="file-upload" accept=".pdf,.doc,.docx">
                </div>
            </div>
            <button type="button" onclick="addMoreFiles()">Add More Files</button>

            <!-- Video Upload Section -->
            <div id="video-upload-container">
                <label>Upload Videos:</label>
                <div class="video-upload-item">
                    <input type="file" name="video-upload" accept="video/*">
                </div>
            </div>
            <button type="button" onclick="addMoreVideos()">Add More Videos</button>

            <button type="submit">Add Question</button>
        </form>
    `;

    fetchModules(); // Fetch and populate modules
    document.getElementById('add-question-form').addEventListener('submit', addQuestion);
}

// Fetch modules dynamically from the backend
function fetchModules() {
    fetch('getModules.php')
        .then(response => response.json())
        .then(modules => {
            const moduleSelect = document.getElementById('module-select');
            moduleSelect.innerHTML = ''; // Clear placeholder
            modules.forEach(module => {
                const option = document.createElement('option');
                option.value = module.Module_Id;
                option.textContent = module.Name;
                moduleSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error fetching modules:', error);
            alert('Failed to load modules.');
        });
}

// Function to show choice options when "multiple-choice" is selected
function showChoiceOptions() {
    const questionType = document.getElementById('question-type').value;
    const choiceOptionsContainer = document.getElementById('choice-options-container');

    if (questionType === "multiple-choice") {
        choiceOptionsContainer.innerHTML = `
            <label>Choose Option Type:</label>
            <div>
                <label>
                    <input type="radio" name="option-type" value="radio" checked>
                    Single Selection (Radio Button)
                </label>
                <label>
                    <input type="radio" name="option-type" value="checkbox">
                    Multiple Selections (Checkbox)
                </label>
            </div>
            <div id="options-container">
                <div class="option-item" id="option-1-container">
                    <label for="option-1">Option 1:</label>
                    <input type="text" id="option-1" name="option-1" placeholder="Enter option">
                    <button type="button" onclick="deleteOption('option-1-container')">Delete</button>
                </div>
                <div class="option-item" id="option-2-container">
                    <label for="option-2">Option 2:</label>
                    <input type="text" id="option-2" name="option-2" placeholder="Enter option">
                    <button type="button" onclick="deleteOption('option-2-container')">Delete</button>
                </div>
            </div>
            <button type="button" onclick="addMoreOptions()">Add More Options</button>
        `;
    } else {
        choiceOptionsContainer.innerHTML = ''; // Clear options if not multiple-choice
    }
}

// Additional functions for dynamic options, file and video handling
function addMoreOptions() { /* Add code as previously shown */ }
function deleteOption(optionContainerId) { /* Add code as previously shown */ }
function addMoreFiles() { /* Add code as previously shown */ }
function addMoreVideos() { /* Add code as previously shown */ }

// Function to handle adding a question with validation and submission
function addQuestion(event) {
    event.preventDefault();
    const moduleSelect = document.getElementById('module-select').value;
    const questionText = document.getElementById('question-text').value.trim();
    const questionType = document.getElementById('question-type').value;
    const optionType = document.querySelector('input[name="option-type"]:checked')?.value;
    const options = Array.from(document.querySelectorAll('#options-container input[type="text"]')).map(input => input.value.trim());
    const files = Array.from(document.querySelectorAll('input[name="file-upload"]')).map(input => input.files[0]);
    const videos = Array.from(document.querySelectorAll('input[name="video-upload"]')).map(input => input.files[0]);

    // Validation logic...

    // AJAX or backend call to save question data (including attachments)
    const formData = new FormData();
    formData.append('moduleSelect', moduleSelect);
    formData.append('questionText', questionText);
    formData.append('questionType', questionType);
    formData.append('optionType', optionType);
    options.forEach((option, index) => formData.append(`options[${index}]`, option));
    files.forEach((file, index) => formData.append(`files[${index}]`, file));
    videos.forEach((video, index) => formData.append(`videos[${index}]`, video));

    fetch('/saveQuestion.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => alert('Question Added Successfully!'))
    .catch(error => console.error('Error:', error));
}
