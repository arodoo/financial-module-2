<?php
/**
 * Fixed Payments Module - Main entry point
 * 
 * Handles both fixed incomes (payments) and fixed expenses operations
 */

// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include dependencies
require_once __DIR__ . '/../../controllers/FixedPaymentController.php';
require_once __DIR__ . '/../../models/FixedPayment.php';
require_once __DIR__ . '/../../controllers/FixedDepenseController.php';
require_once __DIR__ . '/../../models/FixedDepense.php';

// Start output buffering to control what gets sent to the browser
ob_start();

// ----- AJAX REQUEST HANDLING -----
// Check if this is an AJAX request and route to dedicated handler
$isAjaxRequest = isset($_GET['ajax']);
if ($isAjaxRequest) {
    ob_end_clean(); // Discard buffered content for AJAX responses
    include __DIR__ . '/ajax-handler.php';
    exit;
}

// Clear buffer for normal page processing
ob_end_clean();

// ----- DETERMINE ACTIVE TAB -----
$activeTab = isset($_GET['tab']) ? $_GET['tab'] : 'incomes';
$validTabs = ['incomes', 'expenses'];
if (!in_array($activeTab, $validTabs)) $activeTab = 'incomes';

// ----- CONTROLLER INITIALIZATION -----
$paymentController = new FixedPaymentController();
$depenseController = new FixedDepenseController();

// ----- NOTIFICATION SYSTEM SETUP -----
// Initialize flash message system for user feedback
$flashMessage = null;
$flashType = null;

// Check for session-based flash messages
if (isset($_SESSION['flashMessage'])) {
    $flashMessage = $_SESSION['flashMessage'];
    $flashType = $_SESSION['flashType'] ?? 'info';
    unset($_SESSION['flashMessage'], $_SESSION['flashType']);
}

// ----- FORM SUBMISSION PROCESSING -----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle incomes (payments)
    if (isset($_POST['save_payment']) || isset($_POST['update_payment']) || isset($_POST['delete_payment'])) {
        // Set active tab to incomes when processing payment form
        $activeTab = 'incomes';
        
        // Create new payment
        if (isset($_POST['save_payment'])) {
            // Normalize form field names for consistency
            if (isset($_POST['payment_name']) && !isset($_POST['name'])) {
                $_POST['name'] = $_POST['payment_name'];
            }
            
            // Validate required fields before saving
            if (empty($_POST['name']) || empty($_POST['category_id']) || empty($_POST['amount'])) {
                $flashMessage = 'Erreur: Des champs requis sont manquants.';
                $flashType = 'danger';
            } else {
                // Process payment creation and capture result
                $result = $paymentController->savePayment($_POST);
                if ($result) {
                    $flashMessage = 'Paiement enregistré avec succès!';
                    $flashType = 'success';
                } else {
                    $flashMessage = 'Erreur lors de l\'enregistrement du paiement. Veuillez réessayer.';
                    $flashType = 'danger';
                }
            }
        } 
        // Update existing payment
        elseif (isset($_POST['update_payment'])) {
            $result = $paymentController->updatePayment($_POST);
            if ($result) {
                $flashMessage = 'Paiement mis à jour avec succès!';
                $flashType = 'success';
            } else {
                $flashMessage = 'Erreur lors de la mise à jour du paiement.';
                $flashType = 'danger';
            }
        } 
        // Delete a payment
        elseif (isset($_POST['delete_payment'])) {
            // Ensure payment ID is provided before deletion
            if (isset($_POST['payment_id'])) {
                $result = $paymentController->deletePayment($_POST['payment_id']);
                if ($result) {
                    $flashMessage = 'Paiement supprimé avec succès!';
                    $flashType = 'success';
                } else {
                    $flashMessage = 'Erreur lors de la suppression du paiement.';
                    $flashType = 'danger';
                }
            } else {
                $flashMessage = 'Erreur: ID de paiement manquant!';
                $flashType = 'danger';
            }
        }
    }
    // Handle expenses
    elseif (isset($_POST['save_expense']) || isset($_POST['update_expense']) || isset($_POST['delete_expense'])) {
        // Set active tab to expenses when processing expense form
        $activeTab = 'expenses';
        
        // Create new expense
        if (isset($_POST['save_expense'])) {
            // Normalize form field names for consistency
            if (isset($_POST['expense_name']) && !isset($_POST['name'])) {
                $_POST['name'] = $_POST['expense_name'];
            }
            
            // Validate required fields before saving
            if (empty($_POST['name']) || empty($_POST['category_id']) || empty($_POST['amount'])) {
                $flashMessage = 'Erreur: Des champs requis sont manquants.';
                $flashType = 'danger';
            } else {
                // Process expense creation and capture result
                $result = $depenseController->saveExpense($_POST);
                if ($result) {
                    $flashMessage = 'Dépense enregistrée avec succès!';
                    $flashType = 'success';
                } else {
                    $flashMessage = 'Erreur lors de l\'enregistrement de la dépense. Veuillez réessayer.';
                    $flashType = 'danger';
                }
            }
        } 
        // Update existing expense
        elseif (isset($_POST['update_expense'])) {
            $result = $depenseController->updateExpense($_POST);
            if ($result) {
                $flashMessage = 'Dépense mise à jour avec succès!';
                $flashType = 'success';
            } else {
                $flashMessage = 'Erreur lors de la mise à jour de la dépense.';
                $flashType = 'danger';
            }
        } 
        // Delete an expense
        elseif (isset($_POST['delete_expense'])) {
            // Ensure expense ID is provided before deletion
            if (isset($_POST['expense_id'])) {
                $result = $depenseController->deleteExpense($_POST['expense_id']);
                if ($result) {
                    $flashMessage = 'Dépense supprimée avec succès!';
                    $flashType = 'success';
                } else {
                    $flashMessage = 'Erreur lors de la suppression de la dépense.';
                    $flashType = 'danger';
                }
            } else {
                $flashMessage = 'Erreur: ID de dépense manquant!';
                $flashType = 'danger';
            }
        }
    }
}

// ----- DATA PREPARATION FOR VIEW -----
// Retrieve data for both incomes and expenses
$paymentData = $paymentController->getViewData();
$payments = $paymentData['payments'] ?? [];
$paymentCategories = $paymentData['categories'] ?? [];

$expenseData = $depenseController->getViewData();
$expenses = $expenseData['expenses'] ?? [];
$expenseCategories = $expenseData['categories'] ?? [];

// Common options
$frequencyOptions = $paymentController->getFrequencyOptions(); // Same for both
$statusOptions = $paymentController->getStatusOptions(); // Same for both

// Path to AJAX handler for JavaScript functions
$ajaxHandlerUrl = '/modules/planificator/modules/fixed-payments/ajax-handler.php';
?>

<!-- Notification banner for user feedback -->
<?php if ($flashMessage): ?>
    <div class="alert alert-<?php echo $flashType; ?> alert-dismissible fade show" role="alert">
        <?php echo $flashMessage; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- Tab navigation - Modified for SPA approach -->
<ul class="nav nav-tabs mb-4" id="financeTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link <?php echo $activeTab === 'incomes' ? 'active' : ''; ?>" 
           id="incomes-tab" 
           data-bs-toggle="tab"
           data-bs-target="#incomes"
           type="button"
           role="tab"
           aria-controls="incomes"
           aria-selected="<?php echo $activeTab === 'incomes' ? 'true' : 'false'; ?>">
            <i class="fas fa-arrow-down text-success"></i> Revenus Fixes
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link <?php echo $activeTab === 'expenses' ? 'active' : ''; ?>" 
           id="expenses-tab" 
           data-bs-toggle="tab"
           data-bs-target="#expenses"
           type="button"
           role="tab" 
           aria-controls="expenses"
           aria-selected="<?php echo $activeTab === 'expenses' ? 'true' : 'false'; ?>">
            <i class="fas fa-arrow-up text-danger"></i> Dépenses Fixes
        </button>
    </li>
</ul>

<!-- Tab content - Both tabs are present in DOM but hidden/shown via Bootstrap tabs -->
<div class="tab-content" id="financeTabContent">
    <!-- Income tab content -->
    <div class="tab-pane fade <?php echo $activeTab === 'incomes' ? 'show active' : ''; ?>" 
         id="incomes" role="tabpanel" aria-labelledby="incomes-tab">
        <div class="row">
            <!-- Left Column: Form for adding/editing payments -->
            <div class="col-md-5">
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Ajouter un Revenu Fixe</h5>
                    </div>
                    <?php 
                    $categories = $paymentCategories;
                    include __DIR__ . '/add-edit-payment.php'; 
                    ?>
                </div>
            </div>

            <!-- Right Column: Display payments list or welcome message -->
            <div class="col-md-7">
                <?php if (!empty($payments)): ?>
                    <?php include __DIR__ . '/list-payments.php'; ?>
                <?php else: ?>
                    <div class="card">
                        <div class="card-body">
                            <div class="text-center py-5">
                                <h4>Gérez vos revenus récurrents</h4>
                                <p class="text-muted">
                                    Utilisez le formulaire pour ajouter des revenus récurrents à votre planification.
                                </p>
                                <img src="https://via.placeholder.com/400x200?text=Fixed+Incomes" alt="Fixed Incomes"
                                    class="img-fluid mt-3 mb-3 rounded">
                                <p>
                                    Le module de revenus fixes vous permet de:
                                </p>
                                <ul class="text-start">
                                    <li>Suivre tous vos revenus récurrents</li>
                                    <li>Enregistrer la fréquence et les dates de paiement</li>
                                    <li>Différencier par catégorie (salaire, dividendes, etc.)</li>
                                    <li>Gérer le statut de chaque revenu</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Expense tab content -->
    <div class="tab-pane fade <?php echo $activeTab === 'expenses' ? 'show active' : ''; ?>" 
         id="expenses" role="tabpanel" aria-labelledby="expenses-tab">
        <div class="row">
            <!-- Left Column: Form for adding/editing expenses -->
            <div class="col-md-5">
                <div class="card mb-4">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0">Ajouter une Dépense Fixe</h5>
                    </div>
                    <?php 
                    $categories = $expenseCategories;
                    include __DIR__ . '/add-edit-expense.php'; 
                    ?>
                </div>
            </div>

            <!-- Right Column: Display expenses list or welcome message -->
            <div class="col-md-7">
                <?php if (!empty($expenses)): ?>
                    <?php include __DIR__ . '/list-expenses.php'; ?>
                <?php else: ?>
                    <div class="card">
                        <div class="card-body">
                            <div class="text-center py-5">
                                <h4>Gérez vos dépenses récurrentes</h4>
                                <p class="text-muted">
                                    Utilisez le formulaire pour ajouter des dépenses récurrentes à votre planification.
                                </p>
                                <img src="https://via.placeholder.com/400x200?text=Fixed+Expenses" alt="Fixed Expenses"
                                    class="img-fluid mt-3 mb-3 rounded">
                                <p>
                                    Le module de dépenses fixes vous permet de:
                                </p>
                                <ul class="text-start">
                                    <li>Suivre toutes vos dépenses récurrentes</li>
                                    <li>Enregistrer la fréquence et les dates de paiement</li>
                                    <li>Différencier par catégorie (loyer, abonnements, etc.)</li>
                                    <li>Gérer le statut de chaque dépense</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal dialog for editing payments -->
<div class="modal fade" id="editPaymentModal" tabindex="-1" aria-labelledby="editPaymentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="editPaymentModalLabel">Modifier Paiement</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="edit-payment-form-container"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal dialog for editing expenses -->
<div class="modal fade" id="editExpenseModal" tabindex="-1" aria-labelledby="editExpenseModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="editExpenseModalLabel">Modifier Dépense</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="edit-expense-form-container"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
      </div>
    </div>
  </div>
</div>

<!-- JavaScript for payment management functionality -->
<script>
/**
 * Dynamically builds a payment edit form with all required fields
 * @param {Object} data - Payment data to pre-populate the form
 * @return {String} HTML string containing the complete form
 */
function buildPaymentForm(data) {
    console.log("Payment data for edit:", data); // Debug logging
    
    // Create form with all fields and proper values
    let formHtml = `
        <form id="edit-payment-form" method="POST" onsubmit="submitPaymentForm(event)">
            <input type="hidden" name="payment_id" value="${data.id}">
            <!-- Hidden currency field with EUR as default -->
            <input type="hidden" id="currency" name="currency" value="EUR">
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="payment_name" class="form-label">Nom du paiement</label>
                    <input type="text" class="form-control" id="payment_name" name="name" 
                        value="${data.name || ''}" required>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="category_id" class="form-label">Catégorie</label>
                    <select id="category_id" name="category_id" class="form-select" required>
                        <option value="">Choisir une catégorie</option>`;
                    
    // Populate category dropdown and mark selected option
    const categories = <?php echo json_encode($paymentCategories); ?>;
    categories.forEach(category => {
        const selected = (category.id == data.category_id) ? 'selected="selected"' : '';
        formHtml += `<option value="${category.id}" ${selected}>${category.name}</option>`;
    });
    
    formHtml += `
                    </select>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="amount" class="form-label">Montant (EUR)</label>
                <div class="input-group">
                    <input type="text" class="form-control currency-input" id="amount" name="amount" 
                        value="${formatCurrency(data.amount || 0)}" required>
                    <span class="input-group-text">€</span>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="frequency" class="form-label">Fréquence</label>
                    <select class="form-select" id="frequency" name="frequency" required>`;
                    
    // Populate frequency options
    const frequencies = <?php echo json_encode($frequencyOptions); ?>;
    Object.entries(frequencies).forEach(([value, label]) => {
        const selected = (data.frequency === value) ? 'selected="selected"' : '';
        formHtml += `<option value="${value}" ${selected}>${label}</option>`;
    });
    
    formHtml += `
                    </select>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="payment_day" class="form-label">Jour de paiement</label>
                    <input type="number" class="form-control" id="payment_day" name="payment_day" 
                        min="1" max="31" value="${data.payment_day || '1'}" required>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="start_date" class="form-label">Date de début</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" 
                        value="${data.start_date || ''}" required>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="end_date" class="form-label">Date de fin (optionnelle)</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" 
                        value="${data.end_date || ''}">
                </div>
            </div>
            
            <div class="mb-3">
                <label for="status" class="form-label">Statut</label>
                <select class="form-select" id="status" name="status" required>`;
                
    // Populate status options
    const statuses = <?php echo json_encode($statusOptions); ?>;
    Object.entries(statuses).forEach(([value, label]) => {
        const selected = (data.status === value) ? 'selected="selected"' : '';
        formHtml += `<option value="${value}" ${selected}>${label}</option>`;
    });
    
    formHtml += `
                </select>
            </div>
            
            <div class="mb-3">
                <label for="notes" class="form-label">Notes</label>
                <textarea class="form-control" id="notes" name="notes" rows="3" 
                    placeholder="Informations supplémentaires (optionnel)">${data.notes || ''}</textarea>
            </div>
            
            <button type="submit" name="update_payment" class="btn btn-primary w-100">Mettre à jour</button>
        </form>
    `;
    
    return formHtml;
}

/**
 * Formats a number as French currency format
 * @param {Number|String} value - The value to format
 * @return {String} Formatted currency string
 */
function formatCurrency(value) {
    return value ? Number(value).toLocaleString('fr-FR') : '';
}

/**
 * Initializes all currency input fields with formatting behavior
 */
function initCurrencyInputs() {
    const currencyInputs = document.querySelectorAll('.currency-input');
    currencyInputs.forEach(input => {
        // Format on page load
        if (input.value) {
            let value = input.value.replace(/\D/g, '');
            input.value = new Intl.NumberFormat('fr-FR').format(value);
        }
        
        // Format currency while user types
        input.addEventListener('input', function(e) {
            let value = this.value.replace(/\D/g, '');
            this.value = new Intl.NumberFormat('fr-FR').format(value);
        });
        
        // Clean formatting before submission
        input.form?.addEventListener('submit', function() {
            currencyInputs.forEach(inp => {
                inp.value = inp.value.replace(/\s/g, '');
            });
        });
    });
}

/**
 * Handles payment form submission via AJAX
 * @param {Event} event - The form submission event
 */
window.submitPaymentForm = function(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    
    // Add the update_payment parameter that would normally be submitted with the button
    formData.append('update_payment', '1');
    
    // Clean currency inputs for proper backend processing
    const currencyInputs = form.querySelectorAll('.currency-input');
    currencyInputs.forEach(input => {
        const cleanValue = input.value.replace(/\s/g, '');
        formData.set(input.name, cleanValue);
    });
    
    // Extract the base URL without query parameters
    const baseUrl = window.location.href.split('?')[0];
    const submitUrl = baseUrl + '?action=fixed-payments';
    
    // Submit the form data
    fetch(submitUrl, {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.text();
    })
    .then(() => {
        // Close the modal after successful submission
        const editModal = bootstrap.Modal.getInstance(document.getElementById('editPaymentModal'));
        if (editModal) {
            editModal.hide();
        }
        
        // Store flash message in session for display after reload
        sessionStorage.setItem('flashMessage', 'Paiement mis à jour avec succès!');
        sessionStorage.setItem('flashType', 'success');
        
        // Reload the page
        location.reload();
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur lors de la mise à jour du paiement');
    });
};

/**
 * Handles expense form submission via AJAX
 * @param {Event} event - The form submission event
 */
window.submitExpenseForm = function(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    
    // Add the update_expense parameter that would normally be submitted with the button
    formData.append('update_expense', '1');
    
    // Clean currency inputs for proper backend processing
    const currencyInputs = form.querySelectorAll('.currency-input');
    currencyInputs.forEach(input => {
        const cleanValue = input.value.replace(/\s/g, '');
        formData.set(input.name, cleanValue);
    });
    
    // Extract the base URL without query parameters
    const baseUrl = window.location.href.split('?')[0];
    const submitUrl = baseUrl + '?action=fixed-payments';
    
    // Submit the form data
    fetch(submitUrl, {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.text();
    })
    .then(() => {
        // Close the modal after successful submission
        const editModal = bootstrap.Modal.getInstance(document.getElementById('editExpenseModal'));
        if (editModal) {
            editModal.hide();
        }
        
        // Store flash message in session for display after reload
        sessionStorage.setItem('flashMessage', 'Dépense mise à jour avec succès!');
        sessionStorage.setItem('flashType', 'success');
        
        // Reload the page
        location.reload();
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur lors de la mise à jour de la dépense');
    });
};

/**
 * Reload expenses data without page refresh
 */
function reloadExpensesData() {
    // Simple page reload to show banner notifications
    location.reload();
}

/**
 * Reload payment data without page refresh
 */
function reloadPaymentData() {
    location.reload();
}

// Initialize functionality when document is ready
document.addEventListener('DOMContentLoaded', function() {
    // Initialize currency inputs
    initCurrencyInputs();
    
    // Check for flash messages in session storage (from AJAX operations)
    const sessionFlashMessage = sessionStorage.getItem('flashMessage');
    const sessionFlashType = sessionStorage.getItem('flashType');
    
    if (sessionFlashMessage) {
        // Create and show notification banner
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${sessionFlashType} alert-dismissible fade show`;
        alertDiv.role = 'alert';
        
        alertDiv.innerHTML = `
            ${sessionFlashMessage}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        
        // Insert at the top of the content
        const firstChild = document.body.querySelector('.container').firstChild;
        document.body.querySelector('.container').insertBefore(alertDiv, firstChild);
        
        // Clear the session storage
        sessionStorage.removeItem('flashMessage');
        sessionStorage.removeItem('flashType');
    }
    
    // Set up tab switching behavior
    const triggerTabList = [].slice.call(document.querySelectorAll('#financeTab button'));
    triggerTabList.forEach(function (triggerEl) {
      const tabTrigger = new bootstrap.Tab(triggerEl);
      
      triggerEl.addEventListener('click', function (event) {
        event.preventDefault();
        tabTrigger.show();
      });
    });
    
    // If there's an activeTab URL parameter, switch to that tab
    const urlParams = new URLSearchParams(window.location.search);
    const tab = urlParams.get('tab');
    if (tab && (tab === 'expenses' || tab === 'incomes')) {
        const tabEl = document.querySelector(`#${tab}-tab`);
        if (tabEl) {
            const tabTrigger = new bootstrap.Tab(tabEl);
            tabTrigger.show();
        }
    }
});

// Clean up modal content when closed
$('#editPaymentModal').on('hidden.bs.modal', function() {
    $('#edit-payment-form-container').empty();
});

$('#editExpenseModal').on('hidden.bs.modal', function() {
    $('#edit-expense-form-container').empty();
});

/**
 * Dynamically builds an expense edit form with all required fields
 * @param {Object} data - Expense data to pre-populate the form
 * @return {String} HTML string containing the complete form
 */
function buildExpenseForm(data) {
    console.log("Expense data for edit:", data);
    
    // Create form with all fields and proper values
    let formHtml = `
        <form id="edit-expense-form" method="POST" onsubmit="submitExpenseForm(event)">
            <input type="hidden" name="expense_id" value="${data.id}">
            <input type="hidden" id="currency" name="currency" value="EUR">
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="expense_name" class="form-label">Nom de la dépense</label>
                    <input type="text" class="form-control" id="expense_name" name="name" 
                        value="${data.name || ''}" required>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="category_id" class="form-label">Catégorie</label>
                    <select id="category_id" name="category_id" class="form-select" required>
                        <option value="">Choisir une catégorie</option>`;
                    
    // Populate category dropdown and mark selected option
    const expenseCategories = <?php echo json_encode($expenseCategories); ?>;
    expenseCategories.forEach(category => {
        const selected = (category.id == data.category_id) ? 'selected="selected"' : '';
        formHtml += `<option value="${category.id}" ${selected}>${category.name}</option>`;
    });
    
    formHtml += `
                    </select>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="amount" class="form-label">Montant (EUR)</label>
                <div class="input-group">
                    <input type="text" class="form-control currency-input" id="amount" name="amount" 
                        value="${formatCurrency(data.amount || 0)}" required>
                    <span class="input-group-text">€</span>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="frequency" class="form-label">Fréquence</label>
                    <select class="form-select" id="frequency" name="frequency" required>`;
                    
    // Populate frequency options
    const frequencies = <?php echo json_encode($frequencyOptions); ?>;
    Object.entries(frequencies).forEach(([value, label]) => {
        const selected = (data.frequency === value) ? 'selected="selected"' : '';
        formHtml += `<option value="${value}" ${selected}>${label}</option>`;
    });
    
    formHtml += `
                    </select>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="payment_day" class="form-label">Jour de paiement</label>
                    <input type="number" class="form-control" id="payment_day" name="payment_day" 
                        min="1" max="31" value="${data.payment_day || '1'}" required>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="start_date" class="form-label">Date de début</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" 
                        value="${data.start_date || ''}" required>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="end_date" class="form-label">Date de fin (optionnelle)</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" 
                        value="${data.end_date || ''}">
                </div>
            </div>
            
            <div class="mb-3">
                <label for="status" class="form-label">Statut</label>
                <select class="form-select" id="status" name="status" required>`;
                
    // Populate status options
    const statuses = <?php echo json_encode($statusOptions); ?>;
    Object.entries(statuses).forEach(([value, label]) => {
        const selected = (data.status === value) ? 'selected="selected"' : '';
        formHtml += `<option value="${value}" ${selected}>${label}</option>`;
    });
    
    formHtml += `
                </select>
            </div>
            
            <div class="mb-3">
                <label for="notes" class="form-label">Notes</label>
                <textarea class="form-control" id="notes" name="notes" rows="3" 
                    placeholder="Informations supplémentaires (optionnel)">${data.notes || ''}</textarea>
            </div>
            
            <button type="submit" name="update_expense" class="btn btn-primary w-100">Mettre à jour</button>
        </form>
    `;
    
    return formHtml;
}
</script>
