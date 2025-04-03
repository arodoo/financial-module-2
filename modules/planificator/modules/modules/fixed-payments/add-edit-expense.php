<?php
// This file contains the form for adding or editing a fixed expense
$isEditMode = isset($editExpense) && $editExpense;
$formTitle = $isEditMode ? 'Modifier la Dépense' : 'Ajouter une Dépense';
$expense = $isEditMode ? $editExpense : null;

// Get current member ID for the form
$membre_id = $id_oo ?? 1; // Default to 1 if not set
?>

<div class="card-body">
    <form method="POST" id="expense-form">
        <?php if ($isEditMode): ?>
            <input type="hidden" name="expense_id" value="<?php echo $expense['id']; ?>">
        <?php endif; ?>
        
        <!-- Add membre_id hidden field - critical for database operations -->
        <input type="hidden" name="membre_id" value="<?php echo $membre_id; ?>">
        <!-- Hidden currency field with EUR as default -->
        <input type="hidden" id="currency" name="currency" value="EUR">
        <!-- Hidden field to maintain active tab after form submission -->
        <input type="hidden" name="active_tab" value="expenses">
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="expense_name" class="form-label">Nom de la dépense</label>
                <input type="text" class="form-control" id="expense_name" name="expense_name" 
                    value="<?php echo $isEditMode ? htmlspecialchars($expense['name']) : ''; ?>" required>
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="category_id" class="form-label">Catégorie</label>
                <select class="form-select" id="category_id" name="category_id" required>
                    <option value="">Choisir une catégorie</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>" <?php echo ($isEditMode && $expense['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        
        <div class="mb-3">
            <label for="amount" class="form-label">Montant (EUR)</label>
            <div class="input-group">
                <input type="text" class="form-control currency-input" id="amount" name="amount" 
                    value="<?php echo $isEditMode ? number_format($expense['amount'], 2, ',', ' ') : ''; ?>" required>
                <span class="input-group-text">€</span>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="frequency" class="form-label">Fréquence</label>
                <select class="form-select" id="frequency" name="frequency" required>
                    <?php foreach ($frequencyOptions as $key => $label): ?>
                        <option value="<?php echo $key; ?>" <?php echo ($isEditMode && $expense['frequency'] == $key) ? 'selected' : ''; ?>>
                            <?php echo $label; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="payment_day" class="form-label">Jour de paiement</label>
                <input type="number" class="form-control" id="payment_day" name="payment_day" 
                    min="1" max="31" value="<?php echo $isEditMode ? $expense['payment_day'] : '1'; ?>" required>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="start_date" class="form-label">Date de début</label>
                <input type="date" class="form-control" id="start_date" name="start_date" 
                    value="<?php echo $isEditMode && !empty($expense['start_date']) ? $expense['start_date'] : date('Y-m-d'); ?>" required>
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="end_date" class="form-label">Date de fin (optionnelle)</label>
                <input type="date" class="form-control" id="end_date" name="end_date" 
                    value="<?php echo $isEditMode && !empty($expense['end_date']) ? $expense['end_date'] : ''; ?>">
            </div>
        </div>
        
        <div class="mb-3">
            <label for="status" class="form-label">Statut</label>
            <select class="form-select" id="status" name="status" required>
                <?php foreach ($statusOptions as $key => $label): ?>
                    <option value="<?php echo $key; ?>" <?php echo ($isEditMode && $expense['status'] == $key) ? 'selected' : ($key == 'active' && !$isEditMode ? 'selected' : ''); ?>>
                        <?php echo $label; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="mb-3">
            <label for="notes" class="form-label">Notes</label>
            <textarea class="form-control" id="notes" name="notes" rows="3" 
                placeholder="Informations supplémentaires (optionnel)"><?php echo $isEditMode && !empty($expense['notes']) ? htmlspecialchars($expense['notes']) : ''; ?></textarea>
        </div>
        
        <div class="d-flex justify-content-between">
            <?php if ($isEditMode): ?>
                <button type="submit" name="update_expense" class="btn btn-warning">Mettre à jour</button>
                <a href="?action=fixed-payments&tab=expenses" class="btn btn-secondary">Annuler</a>
            <?php else: ?>
                <button type="submit" name="save_expense" class="btn btn-primary">Enregistrer</button>
            <?php endif; ?>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Format currency inputs
    const currencyInputs = document.querySelectorAll('.currency-input');
    currencyInputs.forEach(input => {
        // Format on page load
        if (input.value) {
            let value = input.value.replace(/\D/g, '');
            input.value = new Intl.NumberFormat('fr-FR').format(value);
        }
        
        // Format when typing
        input.addEventListener('input', function(e) {
            let value = this.value.replace(/\D/g, '');
            this.value = new Intl.NumberFormat('fr-FR').format(value);
        });
        
        // Clean before form submission
        input.form.addEventListener('submit', function() {
            currencyInputs.forEach(inp => {
                inp.value = inp.value.replace(/\s/g, '');
            });
        });
    });
    
    // Update payment day based on frequency
    const frequencySelect = document.getElementById('frequency');
    const paymentDayInput = document.getElementById('payment_day');
    
    if (frequencySelect && paymentDayInput) {
        frequencySelect.addEventListener('change', function() {
            const frequency = this.value;
            // Set appropriate max value for payment day based on frequency
            if (frequency === 'monthly') {
                paymentDayInput.max = 31;
            } else if (frequency === 'quarterly') {
                paymentDayInput.max = 90;
            } else if (frequency === 'biannual') {
                paymentDayInput.max = 180;
            } else if (frequency === 'annual') {
                paymentDayInput.max = 365;
            }
        });
    }
});
</script>