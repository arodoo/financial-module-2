<?php
/**
 * Shared Item Form Template for Payments and Expenses
 * 
 * This file generates the form for adding or editing both payments and expenses.
 */

// Ensure this file is included, not accessed directly
if (!defined('MODULE_LOADED')) {
    die('Direct access to this file is not allowed.');
}

// Determine if we are in edit mode
$isEditMode = isset($editItem) && $editItem;
$item = $isEditMode ? $editItem : null;
?>

<div class="card-body">
    <form method="POST" id="item-form">
        <?php if ($isEditMode): ?>
            <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
        <?php endif; ?>
        
        <!-- Add membre_id hidden field - critical for database operations -->
        <input type="hidden" name="membre_id" value="<?php echo $id_oo ?? 1; ?>">
        
        <div class="mb-3">
            <label for="item_name" class="form-label">Nom du <?php echo $type === 'payment' ? 'paiement' : 'dépense'; ?></label>
            <input type="text" class="form-control" id="item_name" name="name" 
                   value="<?php echo $isEditMode ? htmlspecialchars($item['name']) : ''; ?>" required>
        </div>
        
        <div class="mb-3">
            <label for="category_id" class="form-label">Catégorie</label>
            <select id="category_id" name="category_id" class="form-select" required>
                <option value="">Choisir une catégorie</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category['id']; ?>" 
                            <?php echo ($isEditMode && $item['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($category['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="mb-3">
            <label for="amount" class="form-label">Montant</label>
            <div class="input-group">
                <span class="input-group-text">€</span>
                <input type="text" class="form-control currency-input" id="amount" name="amount" 
                       value="<?php echo $isEditMode ? $item['amount'] : ''; ?>" required>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="frequency" class="form-label">Fréquence</label>
                <select id="frequency" name="frequency" class="form-select" required>
                    <?php foreach ($frequencyOptions as $key => $label): ?>
                        <option value="<?php echo $key; ?>" 
                                <?php echo ($isEditMode && $item['frequency'] == $key) ? 'selected' : ''; ?>>
                            <?php echo $label; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="payment_day" class="form-label">Jour de <?php echo $type === 'payment' ? 'paiement' : 'prélèvement'; ?></label>
                <input type="number" class="form-control" id="payment_day" name="payment_day" 
                       min="1" max="31" value="<?php echo $isEditMode ? $item['payment_day'] : '1'; ?>" required>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="start_date" class="form-label">Date de début</label>
                <input type="date" class="form-control" id="start_date" name="start_date" 
                       value="<?php echo $isEditMode ? $item['start_date'] : date('Y-m-d'); ?>" required>
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="end_date" class="form-label">Date de fin (optionnelle)</label>
                <input type="date" class="form-control" id="end_date" name="end_date" 
                       value="<?php echo $isEditMode && !empty($item['end_date']) ? $item['end_date'] : ''; ?>">
            </div>
        </div>
        
        <div class="mb-3">
            <label for="status" class="form-label">Statut</label>
            <select id="status" name="status" class="form-select">
                <?php foreach ($statusOptions as $key => $label): ?>
                    <option value="<?php echo $key; ?>" 
                            <?php echo ((($isEditMode && $item['status'] == $key) ? 'selected' : '') ?: 
                                  ((!$isEditMode && $key == 'active') ? 'selected' : '')); ?>>
                        <?php echo $label; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="mb-3">
            <label for="notes" class="form-label">Notes</label>
            <textarea class="form-control" id="notes" name="notes" rows="3"><?php echo $isEditMode ? htmlspecialchars($item['notes']) : ''; ?></textarea>
        </div>
        
        <div class="d-grid">
            <button type="submit" class="btn btn-<?php echo $type === 'payment' ? 'success' : 'danger'; ?>" 
                    name="<?php echo $isEditMode ? 'update_item' : 'save_item'; ?>">
                <?php echo $isEditMode ? 'Mettre à jour' : 'Enregistrer'; ?>
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Format currency inputs
    document.querySelectorAll('.currency-input').forEach(inp => {
        // Initial formatting
        if (inp.value) {
            let value = inp.value.replace(/[^\d,.-]/g, '');
            value = value.replace(/,/g, '.');
            inp.value = Number(value).toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }
        
        // Format on input
        inp.addEventListener('input', function() {
            let value = this.value.replace(/[^\d,.-]/g, '');
            value = value.replace(/,/g, '.');
            
            if (value) {
                this.value = Number(value).toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            }
        });
    });
    
    // Add form submission preparation
    const form = document.getElementById('item-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Clean currency inputs before submission 
            const currencyInputs = document.querySelectorAll('.currency-input');
            currencyInputs.forEach(input => {
                input.value = input.value.replace(/\s/g, '');
            });
        });
    }
    
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