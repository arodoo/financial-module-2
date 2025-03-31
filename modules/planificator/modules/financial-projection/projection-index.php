<?php
/**
 * Main template for financial projection module
 */
?>

<div class="container-fluid px-0">
    <!-- Header card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="fas fa-chart-line me-2"></i> Projection Financière</h4>
                </div>
                <div class="card-body">
                    <p class="lead">
                        Visualisez l'évolution future de votre situation financière basée sur vos revenus et dépenses fixes.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Settings Panel -->
    <div class="row mb-4">
        <div class="col-12">
            <?php include __DIR__ . '/projection-settings.php'; ?>
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="row mb-4">
        <div class="col-12" id="summary-container">
            <?php include __DIR__ . '/projection-summary.php'; ?>
        </div>
    </div>

    <!-- Charts -->
    <div class="row mb-4">
        <div class="col-12" id="chart-container">
            <?php include __DIR__ . '/projection-chart.php'; ?>
        </div>
    </div>

    <!-- Detailed Table -->
    <div class="row">
        <div class="col-12" id="table-container">
            <?php include __DIR__ . '/projection-table.php'; ?>
        </div>
    </div>
</div>

<!-- Include Chart.js from CDN before our scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js" integrity="sha512-ElRFoEQdI5Ht6kZvyzXhYG9NqjtkmlkfYk0wr6wHxU9JEHakS7UJZNeml5ALk+8IKlU6jDgMabC3vkumRokgJA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
// Global variables
let projectionData = <?php echo json_encode($viewData['default_projection']); ?>;
let chartInstances = {};

/**
 * Initialize the financial projection module
 */
function initFinancialProjection() {
    // Initialize components
    initSettings();
    updateSummary(<?php echo json_encode($projectionController->calculateSummary($viewData['default_projection'])); ?>);
    initCharts(projectionData);
    updateProjectionTable(projectionData);
    
    // Set up event listeners
    document.getElementById('generate-projection').addEventListener('click', generateProjection);
}

/**
 * Generate new projection based on current settings
 */
function generateProjection() {
    // Show loading indicators
    showLoading();
    
    // Get form values
    const form = document.getElementById('projection-settings-form');
    const formData = new FormData(form);
    
    // IMPORTANT: Use the exact path that matches .htaccess exclusion rule
    const timestamp = new Date().getTime();
    const ajaxUrl = `/Planificator/modules/financial-projection/ajax-handler.php?_=${timestamp}`;
    
    console.log('Using direct AJAX URL:', ajaxUrl);
    
    // Debug form data
    console.log('Form data:');
    for (let pair of formData.entries()) {
        console.log(pair[0] + ': ' + pair[1]);
    }
    
    // Add action parameter to form data
    formData.append('action', 'generate_projection');
    
    // Send AJAX request with proper headers
    fetch(ajaxUrl, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        credentials: 'same-origin'
    })
    .then(response => {
        // Debug response details
        console.log('Response status:', response.status, response.statusText);
        console.log('Response type:', response.type);
        console.log('Response URL:', response.url);
        
        // First check if response is OK
        if (!response.ok) {
            throw new Error(`Server returned ${response.status}: ${response.statusText}`);
        }
        
        // Get response content type
        const contentType = response.headers.get('content-type');
        console.log('Response content type:', contentType);
        
        // Get text first for debugging
        return response.text().then(text => {
            console.log('Response preview (first 100 chars):', text.substring(0, 100));
            
            try {
                // Try to parse as JSON
                if (!text.trim()) {
                    throw new Error('Empty response from server');
                }
                
                // Check for HTML in response
                if (text.includes('<!DOCTYPE html>') || text.includes('<html')) {
                    console.error('Server returned HTML instead of JSON. First 1000 chars:');
                    console.error(text.substring(0, 1000));
                    throw new Error('Server returned HTML instead of JSON. Check server logs.');
                }
                
                return JSON.parse(text);
            } catch (e) {
                console.error('Failed to parse JSON:', e);
                console.error('Raw response:', text.substring(0, 1000));
                throw new Error(`Invalid JSON response: ${e.message}`);
            }
        });
    })
    .then(data => {
        if (data.success) {
            // Update global variable
            projectionData = data.data.projection;
            
            // Update UI components
            updateSummary(data.data.summary);
            updateCharts(projectionData);
            updateProjectionTable(projectionData);
            
            // Hide loading indicators
            hideLoading();
            
            console.log('Projection updated successfully');
        } else {
            throw new Error(data.error || 'Unknown error occurred');
        }
    })
    .catch(error => {
        console.error('Projection error:', error);
        hideLoading();
        showError(`Failed to generate projection: ${error.message}`);
    });
}

/**
 * Check if a string is valid JSON
 * @param {string} str String to test
 * @return {boolean} True if valid JSON
 */
function isJsonString(str) {
    try {
        JSON.parse(str);
        return true;
    } catch (e) {
        return false;
    }
}

/**
 * Show loading indicators for all components
 */
function showLoading() {
    document.getElementById('summary-loading').classList.remove('d-none');
    document.getElementById('chart-loading').classList.remove('d-none');
    document.getElementById('table-loading').classList.remove('d-none');
}

/**
 * Hide loading indicators for all components
 */
function hideLoading() {
    document.getElementById('summary-loading').classList.add('d-none');
    document.getElementById('chart-loading').classList.add('d-none');
    document.getElementById('table-loading').classList.add('d-none');
}

/**
 * Show error message
 * @param {string} message Error message
 */
function showError(message) {
    const alert = document.createElement('div');
    alert.className = 'alert alert-danger alert-dismissible fade show';
    alert.innerHTML = `
        <strong>Erreur:</strong> ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    document.getElementById('error-container').append(alert);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        alert.classList.remove('show');
        setTimeout(() => alert.remove(), 150);
    }, 5000);
}

/**
 * Format currency values for display
 * @param {number} value Amount to format
 * @param {string} currency Currency code
 * @return {string} Formatted currency string
 */
function formatCurrency(value, currency = '€') {
    return new Intl.NumberFormat('fr-FR', { 
        style: 'currency', 
        currency: 'EUR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(value);
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', initFinancialProjection);
</script>
