document.addEventListener("DOMContentLoaded", function() {
    const urlParams = new URLSearchParams(window.location.search);
    const moduleId = urlParams.get("moduleId");
    loadModuleDetails(moduleId);
});

function loadModuleDetails(moduleId) {
    fetch(`getModuleDetails.php?moduleId=${moduleId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById("module-title").textContent = data.title;
            document.getElementById("module-description").textContent = data.description;

            const questionsHtml = data.questions.map(q => `
                <div class="question">
                    <h3>${q.title}</h3>
                    ${q.files.map(f => `<a href="${f.path}" download>${f.name}</a>`).join('')}
                    ${q.video ? `<video controls src="${q.video}"></video>` : ""}
                    <textarea id="answer-${q.id}"></textarea>
                </div>
            `).join('');
            document.getElementById("module-questions").innerHTML = questionsHtml;
        })
        .catch(error => console.error("Error loading module details:", error));
}

function submitModule() {
    const urlParams = new URLSearchParams(window.location.search);
    const moduleId = urlParams.get("moduleId");
    const answers = [...document.querySelectorAll("[id^=answer-]")].map(textarea => ({
        questionId: textarea.id.split("-")[1],
        answer: textarea.value
    }));

    fetch('submitModule.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ moduleId, answers })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) alert("Module submitted successfully");
    })
    .catch(error => console.error("Error submitting module:", error));
}
