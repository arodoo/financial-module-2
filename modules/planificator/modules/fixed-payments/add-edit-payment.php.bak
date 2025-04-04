<?php
// This file contains the form for adding or editing a fixed payment
$isEditMode = isset($editPayment) && $editPayment;
$formTitle = $isEditMode ? 'Modifier le Paiement' : 'Ajouter un Paiement';
$payment = $isEditMode ? $editPayment : null;

// Get current member ID for the form
$membre_id = $id_oo ?? 1; // Default to 1 if not set
?>

<div class="card-body">
    <form method="POST" id="payment-form">
        <?php if ($isEditMode): ?>
            <input type="hidden" name="payment_id" value="<?php echo $payment['id']; ?>">
        <?php endif; ?>
        
        <!-- Add membre_id hidden field - critical for database operations -->
        <input type="hidden" name="membre_id" value="<?php echo $membre_id; ?>">
        
        <div class="mb-3">
            <label for="payment_name" class="form-label">Nom du paiement</label>
            <input type="text" class="form-control" id="payment_name" name="payment_name" 
                value="<?php echo $isEditMode ? htmlspecialchars($payment['name']) : ''; ?>" required>
        </div>
        
        <div class="mb-3">
            <label for="category_id" class="form-label">Catégorie</label>
            <select class="form-select" id="category_id" name="category_id" required>
                <option value="">Choisir une catégorie</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category['id']; ?>" <?php echo ($isEditMode && $payment['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($category['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="amount" class="form-label">Montant</label>
                <input type="text" class="form-control currency-input" id="amount" name="amount" 
                    value="<?php echo $isEditMode ? number_format($payment['amount'], 2, ',', ' ') : ''; ?>" required>
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="currency" class="form-label">Devise</label>
                <input type="text" class="form-control" id="currency" name="currency" 
                    value="<?php echo $isEditMode ? htmlspecialchars($payment['currency']) : 'USD'; ?>" required>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="frequency" class="form-label">Fréquence</label>
                <select class="form-select" id="frequency" name="frequency" required>
                    <?php foreach ($frequencyOptions as $key => $label): ?>
                        <option value="<?php echo $key; ?>" <?php echo ($isEditMode && $payment['frequency'] == $key) ? 'selected' : ''; ?>>
                            <?php echo $label; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="payment_day" class="form-label">Jour de paiement</label>
                <input type="number" class="form-control" id="payment_day" name="payment_day" 
                    min="1" max="31" value="<?php echo $isEditMode ? $payment['payment_day'] : '1'; ?>" required>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="start_date" class="form-label">Date de début</label>
                <input type="date" class="form-control" id="start_date" name="start_date" 
                    value="<?php echo $isEditMode && !empty($payment['start_date']) ? $payment['start_date'] : date('Y-m-d'); ?>" required>
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="end_date" class="form-label">Date de fin (optionnelle)</label>
                <input type="date" class="form-control" id="end_date" name="end_date" 
                    value="<?php echo $isEditMode && !empty($payment['end_date']) ? $payment['end_date'] : ''; ?>">
            </div>
        </div>
        
        <div class="mb-3">
            <label for="status" class="form-label">Statut</label>
            <select class="form-select" id="status" name="status" required>
                <?php foreach ($statusOptions as $key => $label): ?>
                    <option value="<?php echo $key; ?>" <?php echo ($isEditMode && $payment['status'] == $key) ? 'selected' : ($key == 'active' && !$isEditMode ? 'selected' : ''); ?>>
                        <?php echo $label; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="mb-3">
            <label for="notes" class="form-label">Notes</label>
            <textarea class="form-control" id="notes" name="notes" rows="3" 
                placeholder="Informations supplémentaires (optionnel)"><?php echo $isEditMode && !empty($payment['notes']) ? htmlspecialchars($payment['notes']) : ''; ?></textarea>
        </div>
        
        <div class="d-flex justify-content-between">
            <?php if ($isEditMode): ?>
                <button type="submit" name="update_payment" class="btn btn-warning">Mettre à jour</button>
                <a href="?action=fixed-payments" class="btn btn-secondary">Annuler</a>
            <?php else: ?>
                <button type="submit" name="save_payment" class="btn btn-primary">Enregistrer</button>
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
    
    // Add form submission preparation
    const form = document.getElementById('payment-form');
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
