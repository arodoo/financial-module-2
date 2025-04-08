<?php

/**
 * Shared Item Form Template for Payments and Expenses
 *
 * This file generates the form for adding or editing both payments and expenses.
 */

// Ensure this file is included, not accessed directly
if (! defined('MODULE_LOADED')) {
    die('Direct access to this file is not allowed.');
}

// Determine if we are in edit mode
$isEditMode = isset($editItem) && $editItem;
$item       = $isEditMode ? $editItem : null;
?>

<div class="card-body">
    <form method="POST" id="item-form-<?php echo $type; ?>" class="needs-validation item-form" novalidate>
        <!-- Add action type for AJAX processing -->
        <input type="hidden" name="action" value="<?php echo $isEditMode ? 'update_' . $type : 'save_' . $type; ?>">
        <input type="hidden" name="type" value="<?php echo $type; ?>">

        <?php if ($isEditMode): ?>
            <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
        <?php endif; ?>

        <!-- Add membre_id hidden field - critical for database operations -->
        <input type="hidden" name="membre_id" value="<?php echo $id_oo ?? 1; ?>">

        <div class="mb-3">
            <label for="item_name" class="form-label">Nom du
                <?php echo $type === 'payment' ? 'paiement' : 'dépense'; ?></label>
            <input type="text" class="form-control" id="item_name" name="name"
                value="<?php echo $isEditMode ? htmlspecialchars($item['name']) : ''; ?>" required>
        </div>

        <div class="mb-3">
            <label for="category_id" class="form-label">Catégorie</label>
            <select id="category_id" name="category_id" class="form-select" required>
                <option value="">Choisir une catégorie</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category['id']; ?>" <?php echo ($isEditMode && $item['category_id'] == $category['id']) ? 'selected' : ''; ?>>
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
                        <option value="<?php echo $key; ?>" <?php echo ($isEditMode && $item['frequency'] == $key) ? 'selected' : ''; ?>>
                            <?php echo $label; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label for="payment_day" class="form-label">Jour de
                    <?php echo $type === 'payment' ? 'paiement' : 'prélèvement'; ?></label>
                <input type="number" class="form-control" id="payment_day" name="payment_day" min="1" max="31"
                    value="<?php echo $isEditMode ? $item['payment_day'] : '1'; ?>" required>
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
                    value="<?php echo $isEditMode && ! empty($item['end_date']) ? $item['end_date'] : ''; ?>">
            </div>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Statut</label>
            <select id="status" name="status" class="form-select">
                <?php foreach ($statusOptions as $key => $label): ?>
                    <option value="<?php echo $key; ?>" <?php echo ((($isEditMode && $item['status'] == $key) ? 'selected' : '') ?: ((! $isEditMode && $key == 'active') ? 'selected' : '')); ?>>
                        <?php echo $label; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="notes" class="form-label">Notes</label>
            <textarea class="form-control" id="notes" name="notes"
                rows="3"><?php echo $isEditMode ? htmlspecialchars($item['notes']) : ''; ?></textarea>
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-<?php echo $type === 'payment' ? 'success' : 'danger'; ?>">
                <?php echo $isEditMode ? 'Mettre à jour' : 'Enregistrer'; ?>
                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Format currency inputs within this specific form only
        document.querySelectorAll('#item-form-<?php echo $type; ?> .currency-input').forEach(inp => {
            // Remove existing event listeners
            inp.removeEventListener('input', formatCurrency);
            inp.removeEventListener('blur', formatCurrency);

            // Add input validation to limit to 8 digits before decimal + 2 after (10,2)
            inp.addEventListener('input', function() {
                // Remove non-numeric characters except decimal separator
                let value = this.value.replace(/[^\d.,]/g, '');
                value = value.replace(',', '.');

                // Split into integer and decimal parts
                const parts = value.split('.');

                // Limit integer part to 8 digits
                if (parts[0] && parts[0].length > 8) {
                    parts[0] = parts[0].substring(0, 8);
                }

                // Limit decimal part to 2 digits
                if (parts[1] && parts[1].length > 2) {
                    parts[1] = parts[1].substring(0, 2);
                }

                // Reconstruct the value
                this.value = parts.join('.');
            });

            // Only format on blur (when focus is lost)
            inp.addEventListener('blur', formatCurrency);

            // Initial formatting
            if (inp.value) {
                formatValue(inp);
            }
        });

        // Currency formatting function
        function formatCurrency() {
            formatValue(this);
        }

        // Helper function to format value
        function formatValue(input) {
            if (input.value) {
                let value = input.value.replace(/[^\d.,]/g, '');
                value = value.replace(',', '.');

                const numericValue = parseFloat(value);
                if (!isNaN(numericValue)) {
                    input.value = numericValue.toLocaleString('fr-FR', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                }
            }
        }

        // Add AJAX form submission - use specific form ID to avoid duplicate bindings
        const form = document.getElementById('item-form-<?php echo $type; ?>');
        if (form && !form.hasAttribute('data-initialized')) {
            // Mark form as initialized to prevent duplicate bindings
            form.setAttribute('data-initialized', 'true');

            form.addEventListener('submit', function(e) {
                // Prevent default form submission
                e.preventDefault();
                e.stopPropagation();

                if (this.checkValidity() === false) {
                    this.classList.add('was-validated');
                    return false;
                }

                // Show spinner and disable button
                const submitBtn = this.querySelector('button[type="submit"]');
                const spinner = submitBtn.querySelector('.spinner-border');
                submitBtn.disabled = true;
                spinner.classList.remove('d-none');

                // Clean currency inputs before submission
                const currencyInputs = this.querySelectorAll('.currency-input');
                currencyInputs.forEach(input => {
                    input.value = input.value.replace(/\s/g, '');
                    input.value = input.value.replace(',', '.');
                });

                // Get form data for AJAX submission
                const formData = new FormData(this);
                const itemType = formData.get('type');

                // Log what we're submitting - for debugging
                console.log(`Submitting ${itemType} form via AJAX:`, Object.fromEntries(formData.entries()));

                // Submit via AJAX to the handler
                fetch('<?php echo $ajaxHandlerUrl; ?>', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => {
                        // Check if we got a JSON response
                        const contentType = response.headers.get('content-type');
                        if (!contentType || !contentType.includes('application/json')) {
                            return Promise.reject('Server returned non-JSON response');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // Show success message
                            popup_alert(data.message || `${itemType === 'payment' ? 'Revenu' : 'Dépense'} enregistré(e) avec succès`, "green filledlight", "#009900", "uk-icon-check");

                            // Reset the form for new entries (but not for edit form)
                            if (!formData.get('item_id')) {
                                console.log('Resetting form (keeping dropdown defaults)...');

                                // Don't use this.reset() as it resets everything
                                // Instead, clear only specific fields

                                // Clear text inputs except hidden fields
                                this.querySelectorAll('input:not([type="hidden"])').forEach(input => {
                                    if (input.type === 'text' || input.type === 'number') {
                                        input.value = '';
                                    } else if (input.type === 'date') {
                                        input.value = input.name === 'start_date' ? new Date().toISOString().split('T')[0] : '';
                                    }
                                });

                                // Clear any textareas
                                this.querySelectorAll('textarea').forEach(textarea => {
                                    textarea.value = '';
                                });

                                // Reset currency inputs
                                this.querySelectorAll('.currency-input').forEach(input => {
                                    input.value = '';
                                });

                                // Reset validation UI
                                this.classList.remove('was-validated');

                                console.log('Form inputs cleared, dropdowns preserved');
                            }

                            // Refresh the appropriate data table
                            const tableId = itemType === 'payment' ? 'fixedPaymentsTable' : 'fixedExpensesTable';
                            if (window[tableId]) {
                                window[tableId].ajax.reload();
                            } else {
                                // Fallback: reload both tables
                                if (window['fixedPaymentsTable']) window['fixedPaymentsTable'].ajax.reload();
                                if (window['fixedExpensesTable']) window['fixedExpensesTable'].ajax.reload();
                            }
                        } else {
                            // Show error message
                            popup_alert(data.error || "Une erreur est survenue", "#ff0000", "#FFFFFF", "uk-icon-close");
                        }
                    })
                    .catch(error => {
                        popup_alert('Erreur de communication avec le serveur', "#ff0000", "#FFFFFF", "uk-icon-close");
                    })
                    .finally(() => {
                        // Re-enable the submit button and hide spinner
                        submitBtn.disabled = false;
                        spinner.classList.add('d-none');
                    });

                return false;
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