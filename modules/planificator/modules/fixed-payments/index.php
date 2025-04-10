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
    <div class="row">
      <!-- Left Column: Form for adding/editing payments -->
      <div class="col-md-5">
        <div class="card mb-4">
          <div class="card-header bg-success text-white">
            <h5 class="mb-0">Ajouter un Revenu Fixe</h5>
          </div>
          <?php
          $type = 'payment';
          $editItem = null; // Not in edit mode
          
          // Create and initialize controller with the right type
          require_once __DIR__ . '/../../controllers/FixedItemController.php';
          $paymentController = new FixedItemController('payment');
          $categories = $paymentController->getCategories();

          include __DIR__ . '/item-form.php';
          ?>
        </div>
      </div>

      <!-- Right Column: Display payments list -->
      <div class="col-md-7">
        <?php include __DIR__ . '/item-list.php'; ?>
      </div>
    </div>
  </div>

  <!-- Expense tab content -->
  <div class="tab-pane fade <?php echo $activeTab === 'expenses' ? 'show active' : ''; ?>" id="expenses" role="tabpanel"
    aria-labelledby="expenses-tab">
    <div class="row">
      <!-- Left Column: Form for adding/editing expenses -->
      <div class="col-md-5">
        <div class="card mb-4">
          <div class="card-header bg-danger text-white">
            <h5 class="mb-0">Ajouter une Dépense Fixe</h5>
          </div>
          <?php
          $type = 'expense';
          $editItem = null; // Not in edit mode 
          
          // Create and initialize controller with the right type
          require_once __DIR__ . '/../../controllers/FixedItemController.php';
          $expenseController = new FixedItemController('expense');
          $categories = $expenseController->getCategories();

          include __DIR__ . '/item-form.php';
          ?>
        </div>
      </div>

      <!-- Right Column: Display expenses list -->
      <div class="col-md-7">
        <?php include __DIR__ . '/item-list.php'; ?>
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
  });
</script>