<?php
/**
 * Income/Expense Module Main View
 * 
 * This file serves as the main template for the income and expense management module.
 * It displays flash messages, includes calculation results, and contains JavaScript 
 * for handling AJAX-based edit and delete functionality for both income and expense
 * transactions. The file works in conjunction with the ajax-handler.php to process
 * transaction updates.
 */

// Use the ajax-handler.php file with a new approach that doesn't hit rewrite rules
$ajaxHandlerUrl = '/modules/planificator/modules/income-expense/ajax-handler.php';
?>

<!-- Include the shared CSS for animations and styling -->
<link rel="stylesheet" href="/modules/planificator/modules/modules.css">

<?php if (isset($flashMessage) && isset($flashType)): ?>
    <div class="alert alert-<?php echo $flashType; ?> alert-dismissible fade show" role="alert">
        <?php echo $flashMessage; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php elseif (isset($_GET['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php 
        if ($_GET['success'] === 'income_added') {
            echo "Revenu ajouté avec succès!";
        } elseif ($_GET['success'] === 'expense_added') {
            echo "Dépense ajoutée avec succès!";
        }
        ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php include 'calculation-results.php'; ?>

<!-- Income Edit Modal -->
<div class="modal fade" id="editIncomeModal" tabindex="-1" aria-labelledby="editIncomeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="editIncomeModalLabel">Modifier Revenu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="edit-income-form-container"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
      </div>
    </div>
  </div>
</div>

<!-- Expense Edit Modal -->
<div class="modal fade" id="editExpenseModal" tabindex="-1" aria-labelledby="editExpenseModalLabel" aria-hidden="true">
  <div class="modal-dialog">
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

<!-- JavaScript for handling edit and delete actions -->
<script>
$(document).ready(function() {
    // Store AJAX handler URL and category data
    const ajaxHandlerUrl = '<?php echo $ajaxHandlerUrl; ?>';
    
    // Cache category data for rebuilding forms
    const incomeCategories = <?php echo json_encode($incomeCategories); ?>;
    const expenseCategories = <?php echo json_encode($expenseCategories); ?>;
    
    // Initialize Bootstrap modals
    const incomeModal = new bootstrap.Modal(document.getElementById('editIncomeModal'));
    const expenseModal = new bootstrap.Modal(document.getElementById('editExpenseModal'));
    
    // Function to build a complete form from scratch
    function buildTransactionForm(type, data, categories) {
        const formId = `edit-${type}-form`;
        const submitName = `update_${type}`;
        const buttonText = type === 'income' ? 'Revenu' : 'Dépense';
        const buttonClass = type === 'income' ? 'primary' : 'danger';
        
        // Create a complete form with all elements
        let formHtml = `
            <form id="${formId}" method="POST">
                <div class="mb-3">
                    <label for="edit_${type}_category" class="form-label">Catégorie</label>
                    <select name="category_id" id="edit_${type}_category" style="width: 100%; padding: 8px; border: 1px solid #ced4da; border-radius: 4px;" required>
                        <option value="">Sélectionner Catégorie</option>`;
                        
        // Add all options and mark the selected one
        categories.forEach(category => {
            const selected = (category.id == data.category_id) ? 'selected="selected"' : '';
            formHtml += `<option value="${category.id}" ${selected}>${category.name}</option>`;
        });
        
        // Complete the form
        formHtml += `
                    </select>
                </div>
                <div class="mb-3">
                    <label for="edit_${type}_amount" class="form-label">Montant (€)</label>
                    <input type="number" class="form-control" id="edit_${type}_amount" name="amount" value="${data.amount}" step="0.01" min="0" required>
                </div>
                <div class="mb-3">
                    <label for="edit_${type}_date" class="form-label">Date</label>
                    <input type="date" class="form-control" id="edit_${type}_date" name="transaction_date" value="${data.transaction_date}" required>
                </div>
                <div class="mb-3">
                    <label for="edit_${type}_description" class="form-label">Description</label>
                    <textarea class="form-control" id="edit_${type}_description" name="description" rows="3">${data.description || ''}</textarea>
                </div>
                <input type="hidden" name="transaction_id" value="${data.id}">
                <button type="submit" name="${submitName}" class="btn btn-${buttonClass} w-100">Mettre à jour ${buttonText}</button>
            </form>
        `;
        
        return formHtml;
    }
    
    // Edit income transaction - complete form rebuild approach
    $('#income').on('click', '.edit-transaction', function(e) {
        e.preventDefault();
        const transactionId = $(this).data('id');
        
        $.ajax({
            url: `${ajaxHandlerUrl}?action=get_income_transaction&id=${transactionId}`,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                if (data.success) {
                    // Build a completely new form
                    const formHtml = buildTransactionForm('income', data, incomeCategories);
                    
                    // Insert the form into the container
                    $('#edit-income-form-container').html(formHtml);
                    
                    // Show the modal with the fresh form
                    incomeModal.show();
                } else {
                    alert('Erreur: ' + data.error);
                }
            },
            error: function(xhr, status, error) {
                alert('Erreur lors de la récupération des données: ' + error);
            }
        });
    });
    
    // Edit expense transaction - complete form rebuild approach
    $('#expense').on('click', '.edit-transaction', function(e) {
        e.preventDefault();
        const transactionId = $(this).data('id');
        
        $.ajax({
            url: `${ajaxHandlerUrl}?action=get_expense_transaction&id=${transactionId}`,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                if (data.success) {
                    // Build a completely new form
                    const formHtml = buildTransactionForm('expense', data, expenseCategories);
                    
                    // Insert the form into the container
                    $('#edit-expense-form-container').html(formHtml);
                    
                    // Show the modal with the fresh form
                    expenseModal.show();
                } else {
                    alert('Erreur: ' + data.error);
                }
            },
            error: function(xhr, status, error) {
                alert('Erreur lors de la récupération des données: ' + error);
            }
        });
    });
    
    // Delete income transaction
    $('#income').on('click', '.delete-transaction', function(e) {
        e.preventDefault();
        if (confirm('Êtes-vous sûr de vouloir supprimer ce revenu ?')) {
            const transactionId = $(this).data('id');
            
            $('<form>')
                .attr({
                    method: 'POST',
                    style: 'display: none;'
                })
                .append($('<input>').attr({
                    type: 'hidden',
                    name: 'transaction_id',
                    value: transactionId
                }))
                .append($('<input>').attr({
                    type: 'hidden',
                    name: 'delete_income',
                    value: '1'
                }))
                .appendTo('body')
                .submit();
        }
    });
    
    // Delete expense transaction
    $('#expense').on('click', '.delete-transaction', function(e) {
        e.preventDefault();
        if (confirm('Êtes-vous sûr de vouloir supprimer cette dépense ?')) {
            const transactionId = $(this).data('id');
            
            $('<form>')
                .attr({
                    method: 'POST',
                    style: 'display: none;'
                })
                .append($('<input>').attr({
                    type: 'hidden',
                    name: 'transaction_id',
                    value: transactionId
                }))
                .append($('<input>').attr({
                    type: 'hidden',
                    name: 'delete_expense',
                    value: '1'
                }))
                .appendTo('body')
                .submit();
        }
    });
    
    // Clean up modals when closed
    $('#editIncomeModal').on('hidden.bs.modal', function() {
        $('#edit-income-form-container').empty();
    });
    
    $('#editExpenseModal').on('hidden.bs.modal', function() {
        $('#edit-expense-form-container').empty();
    });
});
</script>
