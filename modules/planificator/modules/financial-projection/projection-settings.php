<div class="card">
    <div class="card-header bg-light">
        <h5 class="mb-0">Paramètres de Projection</h5>
    </div>
    <div class="card-body">
        <form id="projection-settings-form">
            <div id="error-container"></div>

            <div class="row">
                <!-- Time Range -->
                <div class="col-md-4 mb-3">
                    <label for="years" class="form-label">Période de projection</label>
                    <select id="years" name="years" class="form-select">
                        <?php foreach ($yearOptions as $value => $label): ?>
                            <option value="<?php echo $value; ?>"<?php echo $value == 5 ? 'selected' : ''; ?>>
                                <?php echo $label; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- View Mode -->
                <div class="col-md-4 mb-3">
                    <label for="view_mode" class="form-label">Mode d'affichage</label>
                    <select id="view_mode" name="view_mode" class="form-select">
                        <?php foreach ($viewModeOptions as $value => $label): ?>
                            <option value="<?php echo $value; ?>">
                                <?php echo $label; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Start Date -->
                <div class="col-md-4 mb-3">
                    <label for="start_date" class="form-label">Date de début</label>
                    <input type="date" class="form-control" id="start_date" name="start_date"
                           value="<?php echo date('Y-m-d'); ?>">
                </div>
            </div>

            <div class="row">
                <!-- Initial Balance -->
                <div class="col-md-4 mb-3">
                    <label for="initial_balance" class="form-label">Solde initial</label>
                    <div class="input-group">
                        <input type="number" class="form-control" id="initial_balance" name="initial_balance"
                               value="<?php echo $viewData['current_balance']; ?>" step="100">
                        <span class="input-group-text">€</span>
                    </div>
                    <div class="form-text">Solde des ressources et dépenses à la date de début.</div>
                </div>

                <!-- Income Growth Rate -->
                <div class="col-md-4 mb-3">
                    <label for="income_growth_rate" class="form-label">Taux de croissance des revenus</label>
                    <div class="input-group">
                        <input type="number" class="form-control" id="income_growth_rate" name="income_growth_rate"
                               value="2.0" step="0.1">
                        <span class="input-group-text">%</span>
                    </div>
                    <div class="form-text">Augmentation annuelle moyenne des revenus</div>
                </div>

                <!-- Expense Inflation Rate -->
                <div class="col-md-4 mb-3">
                    <label for="expense_inflation_rate" class="form-label">Taux d'inflation des dépenses</label>
                    <div class="input-group">
                        <input type="number" class="form-control" id="expense_inflation_rate" name="expense_inflation_rate"
                               value="2.5" step="0.1">
                        <span class="input-group-text">%</span>
                    </div>
                    <div class="form-text">Augmentation annuelle moyenne des dépenses</div>
                </div>
            </div>

            <!-- Add Asset Toggle -->
            <div class="row mt-2">
                <div class="col-md-12">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="include_assets" name="include_assets" value="1">
                        <label class="form-check-label" for="include_assets">
                            Inclure les actifs financiers dans la projection
                        </label>
                        <div class="form-text">
                            Lorsque activé: Inclut la valeur de vos actifs financiers dans le solde initial
                            Lorsque désactivé: Utilise uniquement le solde des revenus et dépenses (liquidités)
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-3">
                <button type="button" id="generate-projection" class="btn btn-primary">
                    <i class="fas fa-calculator me-2"></i> Générer la projection
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Add CSS for visual feedback
const style = document.createElement('style');
style.textContent = `
    #initial_balance.loading {
        background-color: #f8f9fa;
        transition: background-color 0.3s;
    }
    
    #initial_balance.updating {
        background-color: #e2f0ff;
        transition: background-color 0.6s, color 0.3s;
    }
    
    @keyframes highlight {
        0% { background-color: #ffffff; }
        50% { background-color: #e2f0ff; }
        100% { background-color: #ffffff; }
    }
    
    .form-text.updated {
        animation: highlight 1s ease;
    }
`;
document.head.appendChild(style);

/**
 * Initialize settings form
 */
function initSettings() {
    // Add event listener to the include assets checkbox
    document.getElementById('include_assets').addEventListener('change', function() {
        updateInitialBalanceField(false); // Manual toggle - show visual feedback
    });
    
    // Store original balance value for toggle functionality
    fetchBalanceData();
}

// Global variables to store values
let originalBalance = 0;
let totalAssetsValue = 0;

/**
 * Fetch balance data from server
 */
function fetchBalanceData() {
    const timestamp = new Date().getTime();
    const ajaxUrl = `/Planificator/modules/financial-projection/ajax-handler.php?_=${timestamp}`;
    
    // Show loading indicator
    const balanceField = document.getElementById('initial_balance');
    const originalValue = balanceField.value;
    balanceField.classList.add('loading');
    
    // Prepare form data
    const formData = new FormData();
    formData.append('action', 'get_balance_data');
    
    // Send AJAX request
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
        if (!response.ok) {
            throw new Error(`Server returned ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            console.log('Balance data received:', data.data);
            // Store values for later use - ensure they're valid numbers
            originalBalance = parseFloat(data.data.current_balance) || 0;
            totalAssetsValue = parseFloat(data.data.total_assets) || 0;
            
            console.log('Parsed values - Balance:', originalBalance, 'Assets:', totalAssetsValue);
            
            // Initialize the field with current values
            updateInitialBalanceField(true);
        } else {
            console.error('Error in AJAX response:', data);
            balanceField.value = originalValue;
        }
    })
    .catch(error => {
        console.error('Error fetching balance data:', error);
        balanceField.value = originalValue;
    })
    .finally(() => {
        // Remove loading indicator
        balanceField.classList.remove('loading');
    });
}

/**
 * Update the initial balance field based on checkbox state
 * @param {boolean} isInitial - Whether this is the initial update
 */
function updateInitialBalanceField(isInitial = false) {
    const includeAssets = document.getElementById('include_assets').checked;
    const balanceField = document.getElementById('initial_balance');
    
    // Find the specific form-text element for this field
    const balanceSource = balanceField.closest('.mb-3').querySelector('.form-text');
    
    if (!isInitial) {
        // Add transition effect when manually toggled
        balanceField.classList.add('updating');
        setTimeout(() => balanceField.classList.remove('updating'), 600);
        
        // Add highlight effect to the explanation text
        balanceSource.classList.add('updated');
        setTimeout(() => balanceSource.classList.remove('updated'), 1000);
    }
    
    console.log('Updating balance - Include assets:', includeAssets);
    console.log('Current values - Original balance:', originalBalance, 'Assets value:', totalAssetsValue);
    
    // Calculate new balance based on checkbox state
    const newBalance = includeAssets ? 
        originalBalance + totalAssetsValue : 
        originalBalance;
    
    console.log('New balance calculated:', newBalance);
    
    // Update the field - ensure it's a valid number
    balanceField.value = isNaN(newBalance) ? 0 : newBalance.toFixed(0);
    
    // Update the description text
    if (includeAssets) {
        balanceSource.innerHTML = `Solde (${originalBalance.toFixed(0)}€) + Actifs (${totalAssetsValue.toFixed(0)}€)`;
    } else {
        balanceSource.innerHTML = 'Solde des ressources et dépenses à la date de début.';
    }
}
</script>
