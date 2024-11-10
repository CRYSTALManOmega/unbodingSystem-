// Automatically load the Reports section when the department manager interface is opened
document.addEventListener("DOMContentLoaded", function() {
    loadReports(); // Automatically load the Reports page
});

// Function to load the Reports section
function loadReports() {
    document.getElementById('main-content').innerHTML = `
        <h1>Reports</h1>

        <!-- Report Generation Options -->
        <div class="report-section">
            <h2>Generate Reports</h2>
            <button onclick="generatePerformanceReport()">Department Performance</button>
            <button onclick="generateEmployeeMetricsReport()">Employee Metrics</button>
            <button onclick="generateTurnoverRateReport()">Turnover Rate</button>
            <button onclick="generateTrainingEffectivenessReport()">Training Effectiveness</button>
        </div>

        <!-- Placeholder for displaying generated reports -->
        <div id="report-output" class="report-output">
            <h2>Report Output</h2>
            <p>Select a report to generate and view details here.</p>
        </div>
    `;
}

// Functions to generate various reports
function generatePerformanceReport() {
    document.getElementById('report-output').innerHTML = `
        <h2>Department Performance Report</h2>
        <p>This report includes key metrics related to department performance, including completed projects, deadlines met, and overall efficiency scores.</p>
    `;
}

function generateEmployeeMetricsReport() {
    document.getElementById('report-output').innerHTML = `
        <h2>Employee Metrics Report</h2>
        <p>This report covers employee performance metrics, including attendance, task completion rates, and overall productivity.</p>
    `;
}

function generateTurnoverRateReport() {
    document.getElementById('report-output').innerHTML = `
        <h2>Turnover Rate Report</h2>
        <p>This report highlights employee turnover rates, identifying trends and providing insights into employee retention.</p>
    `;
}

function generateTrainingEffectivenessReport() {
    document.getElementById('report-output').innerHTML = `
        <h2>Training Effectiveness Report</h2>
        <p>This report includes data such as participant feedback, quiz scores, and completion rates to assess training program effectiveness.</p>
    `;
}
