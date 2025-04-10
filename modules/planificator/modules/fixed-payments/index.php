<?php
/**
 * Fixed Payments Module - Main entry point
 * 
 * This file acts as a router that includes the appropriate file based on the
 * tab/action the user is viewing. It keeps the large module split into maintainable
 * files while preserving functionality.
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Flag to indicate this file is properly included
define('MODULE_LOADED', true);

// Path to AJAX handler for JavaScript functions - Define this before including item-handler.php
$ajaxHandlerUrl = '/modules/planificator/modules/fixed-payments/ajax-handler.php';

// Include shared handler
require_once __DIR__ . '/item-handler.php';

// Determine active tab
$activeTab = isset($_GET['tab']) ? $_GET['tab'] : 'incomes';
$validTabs = ['incomes', 'expenses'];
if (!in_array($activeTab, $validTabs))
  $activeTab = 'incomes';
?>

<!-- Notification banner for user feedback -->
<?php if (isset($flashMessage)): ?>
  <div class="alert alert-<?php echo $flashType; ?> alert-dismissible fade show" role="alert">
    <?php echo $flashMessage; ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
<?php endif; ?>

<!-- Tab navigation -->
<ul class="nav nav-tabs mb-4" id="financeTab" role="tablist">
  <li class="nav-item" role="presentation">
    <button class="nav-link <?php echo $activeTab === 'incomes' ? 'active' : ''; ?>" id="incomes-tab"
      data-bs-toggle="tab" data-bs-target="#incomes" type="button" role="tab" aria-controls="incomes"
      aria-selected="<?php echo $activeTab === 'incomes' ? 'true' : 'false'; ?>">
      <i class="fas fa-arrow-down text-success"></i> Revenus Fixes
    </button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link <?php echo $activeTab === 'expenses' ? 'active' : ''; ?>" id="expenses-tab"
      data-bs-toggle="tab" data-bs-target="#expenses" type="button" role="tab" aria-controls="expenses"
      aria-selected="<?php echo $activeTab === 'expenses' ? 'true' : 'false'; ?>">
      <i class="fas fa-arrow-up text-danger"></i> Dépenses Fixes
    </button>
  </li>
</ul>

<!-- Tab content -->
<div class="tab-content" id="financeTabContent">
  <!-- Income tab content -->
  <div class="tab-pane fade <?php echo $activeTab === 'incomes' ? 'show active' : ''; ?>" id="incomes" role="tabpanel"
    aria-labelledby="incomes-tab">
    <div class="row mb-3">
      <div class="col-12 text-end">
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addIncomeModal">
          <i class="fas fa-plus"></i> Ajouter un Revenu Fixe
        </button>
      </div>
    </div>
    <div class="row">
      <!-- Full width for the list -->
      <div class="col-md-12">
        <?php 
        $type = 'payment';
        include __DIR__ . '/item-list.php'; 
        ?>
      </div>
    </div>
  </div>

  <!-- Expense tab content -->
  <div class="tab-pane fade <?php echo $activeTab === 'expenses' ? 'show active' : ''; ?>" id="expenses" role="tabpanel"
    aria-labelledby="expenses-tab">
    <div class="row mb-3">
      <div class="col-12 text-end">
        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#addExpenseModal">
          <i class="fas fa-plus"></i> Ajouter une Dépense Fixe
        </button>
      </div>
    </div>
    <div class="row">
      <!-- Full width for the list -->
      <div class="col-md-12">
        <?php 
        $type = 'expense';
        include __DIR__ . '/item-list.php'; 
        ?>
      </div>
    </div>
  </div>
</div>

<!-- Modal for adding income -->
<div class="modal fade" id="addIncomeModal" tabindex="-1" aria-labelledby="addIncomeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="addIncomeModalLabel">Ajouter un Revenu Fixe</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <?php
        $type = 'payment';
        $editItem = null; // Not in edit mode
        
        // Create controller if not already done
        if (!isset($paymentController)) {
          require_once __DIR__ . '/../../controllers/FixedItemController.php';
          $paymentController = new FixedItemController('payment');
          $categories = $paymentController->getCategories();
        }

        include __DIR__ . '/item-form.php';
        ?>
      </div>
    </div>
  </div>
</div>

<!-- Modal for adding expense -->
<div class="modal fade" id="addExpenseModal" tabindex="-1" aria-labelledby="addExpenseModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="addExpenseModalLabel">Ajouter une Dépense Fixe</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <?php
        $type = 'expense';
        $editItem = null; // Not in edit mode
        
        // Create controller if not already done
        if (!isset($expenseController)) {
          require_once __DIR__ . '/../../controllers/FixedItemController.php';
          $expenseController = new FixedItemController('expense');
          $categories = $expenseController->getCategories();
        }

        include __DIR__ . '/item-form.php';
        ?>
      </div>
    </div>
  </div>
</div>

<!-- Modal dialog for editing items -->
<div class="modal fade" id="editItemModal" tabindex="-1" aria-labelledby="editItemModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="editItemModalLabel">Modifier</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="edit-item-form-container"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
      </div>
    </div>
  </div>
</div>

<script>
  // Initialize functionality when document is ready
  document.addEventListener('DOMContentLoaded', function () {
    // Prevent multiple initializations by adding a flag
    if (window.fixedItemsInitialized) return;
    window.fixedItemsInitialized = true;

    // Initialize DataTables for both tabs regardless of which tab is active
    if (typeof initPaymentTableScripts === 'function') {
      initPaymentTableScripts('fixedPaymentsTable', '<?php echo $ajaxHandlerUrl; ?>');
    }

    if (typeof initExpenseTableScripts === 'function') {
      initExpenseTableScripts('fixedExpensesTable', '<?php echo $ajaxHandlerUrl; ?>');
    }

    // Add event listener for tab changes to ensure tables are properly rendered when switching tabs
    const tabs = document.querySelectorAll('button[data-bs-toggle="tab"]');
    tabs.forEach(tab => {
      tab.addEventListener('shown.bs.tab', function (event) {
        // Adjust tables when tab is shown to fix layout issues
        if (window['fixedPaymentsTable']) window['fixedPaymentsTable'].columns.adjust();
        if (window['fixedExpensesTable']) window['fixedExpensesTable'].columns.adjust();
      });
    });

    // Handle form submission in modals and close modal after successful submission
    const handleFormSuccess = function(formId, modalId) {
      const form = document.getElementById(formId);
      if (!form) return;
      
      // Listen for the success message in the DOM
      form.addEventListener('submit', function() {
        // Wait a reasonable amount of time for the form to be processed
        setTimeout(() => {
          // Look for success message in the DOM
          const successMessages = document.querySelectorAll('.popupalert');
          
          // If we found a success message and it contains text indicating success
          for (const msgContainer of successMessages) {
            const msgContent = msgContainer.querySelector('.popupalert-content');
            if (msgContent && (
                msgContent.textContent.includes('succès') || 
                msgContent.textContent.includes('enregistré')
            )) {
              // Close the modal
              const modal = document.getElementById(modalId);
              if (modal) {
                const modalInstance = bootstrap.Modal.getInstance(modal);
                if (modalInstance) modalInstance.hide();
              }
              return;
            }
          }
        }, 500); // Give enough time for the form submission to complete
      });
    };
    
    // Apply to both forms
    handleFormSuccess('item-form-payment', 'addIncomeModal');
    handleFormSuccess('item-form-expense', 'addExpenseModal');
  });
</script>