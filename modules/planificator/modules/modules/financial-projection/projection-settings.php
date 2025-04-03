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
                            <option value="<?php echo $value; ?>" <?php echo $value == 5 ? 'selected' : ''; ?>>
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
                               value="<?php echo $viewData['total_assets']; ?>" step="100">
                        <span class="input-group-text">€</span>
                    </div>
                    <div class="form-text">Laissez vide pour utiliser la valeur totale de vos actifs.</div>
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
            
            <div class="d-flex justify-content-end">
                <button type="button" id="generate-projection" class="btn btn-primary">
                    <i class="fas fa-calculator me-2"></i> Générer la projection
                </button>
            </div>
        </form>
    </div>
</div>

<script>
/**
 * Initialize settings form
 */
function initSettings() {
    // Optional: Add any specific settings initialization logic here
}
</script>
