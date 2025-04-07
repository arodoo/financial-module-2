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

<!-- Hidden fields for date range to be used by DataTables -->
<input type="hidden" id="periodStartDate" value="<?php echo $startDate; ?>">
<input type="hidden" id="periodEndDate" value="<?php echo $endDate; ?>">

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
    
    // Period dates for AJAX requests
    const startDate = $('#periodStartDate').val();
    const endDate = $('#periodEndDate').val();
    
    // Function to build a complete form from scratch
    function buildTransactionForm(type, data, categories) {
        const formId = `edit-${type}-form`;
        const action = `update_${type}`;
        const buttonText = type === 'income' ? 'Revenu' : 'Dépense';
        const buttonClass = type === 'income' ? 'primary' : 'danger';
        
        // Create a complete form with all elements
        let formHtml = `
            <form id="${formId}">
                <div class="mb-3">
                    <label for="edit_${type}_category" class="form-label">Catégorie</label>
                    <select name="category_id" id="edit_${type}_category" class="form-select" required>
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
                <input type="hidden" name="action" value="${action}">
                <input type="hidden" name="start_date" value="${startDate}">
                <input type="hidden" name="end_date" value="${endDate}">
                <button type="submit" class="btn btn-${buttonClass} w-100">Mettre à jour ${buttonText}</button>
            </form>
        `;
        
        return formHtml;
    }
    
    // Fix DataTables when switching tabs
    $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
        const targetId = $(e.target).attr('data-bs-target');
        
        // Force a layout recalculation for any DataTables in the newly visible tab
        setTimeout(function() {
            $(targetId).find('table.dataTable').each(function() {
                try {
                    const dt = $(this).DataTable();
                    if (dt) {
                        dt.columns.adjust();
                    }
                } catch (e) {
                    // Silent error handling
                }
            });
            
            // Make sure buttons are visible
            $(targetId).find('.action-column button').css('display', 'inline-block');
        }, 50);
    });

    // Helper function to update summary data
    function updateSummary(summary) {
        if (summary) {
            $('#totalIncomeValue').text('€' + parseFloat(summary.totalIncome).toFixed(2));
            $('#totalExpenseValue').text('€' + parseFloat(summary.totalExpense).toFixed(2));
            $('#netBalanceValue').text('€' + parseFloat(summary.netBalance).toFixed(2));
            
            // Update net balance color
            const netBalanceCard = $('#netBalanceCard');
            if (summary.netBalance >= 0) {
                netBalanceCard.removeClass('border-warning').addClass('border-success');
                $('#netBalanceLabel').removeClass('text-warning').addClass('text-success');
            } else {
                netBalanceCard.removeClass('border-success').addClass('border-warning');
                $('#netBalanceLabel').removeClass('text-success').addClass('text-warning');
            }
        }
    }

    // Add Income Form Submission via AJAX
    $('#income-form').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        formData.append('start_date', startDate);
        formData.append('end_date', endDate);
        
        $.ajax({
            url: ajaxHandlerUrl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Show success message
                    popup_alert(response.message, "green filledlight", "#009900", "uk-icon-check");
                    
                    // Reset form
                    $('#income-form')[0].reset();
                    
                    // Reload table data
                    if (window.incomeTransactionsTable) {
                        window.incomeTransactionsTable.ajax.reload();
                    }
                    
                    // Update summary
                    updateSummary(response.summary);
                } else {
                    // Show error message
                    popup_alert(response.error, "red filledlight", "#990000", "uk-icon-warning");
                }
            },
            error: function() {
                popup_alert('Erreur de communication avec le serveur', "red filledlight", "#990000", "uk-icon-warning");
            }
        });
    });
    
    // Add Expense Form Submission via AJAX
    $('#expense-form').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        formData.append('start_date', startDate);
        formData.append('end_date', endDate);
        
        $.ajax({
            url: ajaxHandlerUrl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Show success message
                    popup_alert(response.message, "green filledlight", "#009900", "uk-icon-check");
                    
                    // Reset form
                    $('#expense-form')[0].reset();
                    
                    // Reload table data
                    if (window.expenseTransactionsTable) {
                        window.expenseTransactionsTable.ajax.reload();
                    }
                    
                    // Update summary
                    updateSummary(response.summary);
                } else {
                    // Show error message
                    popup_alert(response.error, "red filledlight", "#990000", "uk-icon-warning");
                }
            },
            error: function() {
                popup_alert('Erreur de communication avec le serveur', "red filledlight", "#990000", "uk-icon-warning");
            }
        });
    });

    // Handle edit transaction click
    $(document).on('click', 'table .edit-transaction', function(e) {
        e.preventDefault();
        
        // Find the closest tab pane to determine if we're in income or expense
        const $tabPane = $(this).closest('.tab-pane');
        const isIncome = $tabPane.attr('id') === 'income';
        const transactionId = $(this).data('id');
        
        if (!transactionId) {
            return;
        }
        
        // Set proper parameters based on transaction type
        const action = isIncome ? 'get_income_transaction' : 'get_expense_transaction';
        const categories = isIncome ? incomeCategories : expenseCategories;
        const modal = isIncome ? incomeModal : expenseModal;
        const type = isIncome ? 'income' : 'expense';
        const formContainerId = isIncome ? 'edit-income-form-container' : 'edit-expense-form-container';
        
        $.ajax({
            url: `${ajaxHandlerUrl}?action=${action}&id=${transactionId}`,
            type: 'GET',
            dataType: 'json',
            cache: false,
            success: function(data) {
                if (data && data.success) {
                    // Build form and show modal
                    const formHtml = buildTransactionForm(type, data, categories);
                    $(`#${formContainerId}`).html(formHtml);
                    modal.show();
                    
                    // Set up the edit form submission
                    const formId = `#edit-${type}-form`;
                    $(document).on('submit', formId, function(e) {
                        e.preventDefault();
                        
                        const formData = new FormData(this);
                        
                        $.ajax({
                            url: ajaxHandlerUrl,
                            type: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            dataType: 'json',
                            success: function(response) {
                                if (response.success) {
                                    // Close modal
                                    modal.hide();
                                    
                                    // Show success message
                                    popup_alert(response.message, "green filledlight", "#009900", "uk-icon-check");
                                    
                                    // Reload table data
                                    if (isIncome && window.incomeTransactionsTable) {
                                        window.incomeTransactionsTable.ajax.reload();
                                    } else if (!isIncome && window.expenseTransactionsTable) {
                                        window.expenseTransactionsTable.ajax.reload();
                                    }
                                    
                                    // Update summary
                                    updateSummary(response.summary);
                                } else {
                                    // Show error message
                                    popup_alert(response.error, "red filledlight", "#990000", "uk-icon-warning");
                                }
                            },
                            error: function() {
                                popup_alert('Erreur de communication avec le serveur', "red filledlight", "#990000", "uk-icon-warning");
                            }
                        });
                    });
                } else {
                    alert(`Erreur: ${data && data.error ? data.error : 'Impossible de récupérer les données'}`);
                }
            },
            error: function() {
                alert('Erreur lors de la récupération des données. Veuillez réessayer.');
            }
        });
    });
    
    // Handle delete transaction click
    $(document).on('click', 'table .delete-transaction', function(e) {
        e.preventDefault();
        
        // Find the closest tab pane to determine if we're in income or expense
        const $tabPane = $(this).closest('.tab-pane');
        const isIncome = $tabPane.attr('id') === 'income';
        const transactionId = $(this).data('id');
        
        if (!transactionId) {
            return;
        }
        
        // Set confirmation message based on transaction type
        const confirmMessage = isIncome 
            ? 'Êtes-vous sûr de vouloir supprimer ce revenu ?'
            : 'Êtes-vous sûr de vouloir supprimer cette dépense ?';
        
        const action = isIncome ? 'delete_income' : 'delete_expense';
        
        if (confirm(confirmMessage)) {
            $.ajax({
                url: ajaxHandlerUrl,
                type: 'POST',
                data: {
                    action: action,
                    transaction_id: transactionId,
                    start_date: startDate,
                    end_date: endDate
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // Show success message
                        popup_alert(response.message, "green filledlight", "#009900", "uk-icon-check");
                        
                        // Reload table data
                        if (isIncome && window.incomeTransactionsTable) {
                            window.incomeTransactionsTable.ajax.reload();
                        } else if (!isIncome && window.expenseTransactionsTable) {
                            window.expenseTransactionsTable.ajax.reload();
                        }
                        
                        // Update summary
                        updateSummary(response.summary);
                    } else {
                        // Show error message
                        popup_alert(response.error, "red filledlight", "#990000", "uk-icon-warning");
                    }
                },
                error: function() {
                    popup_alert('Erreur de communication avec le serveur', "red filledlight", "#990000", "uk-icon-warning");
                }
            });
        }
    });
    
    // Clean up modals when closed
    $('#editIncomeModal').on('hidden.bs.modal', function() {
        $('#edit-income-form-container').empty();
        // Remove event handler to prevent duplicates
        $(document).off('submit', '#edit-income-form');
    });
    
    $('#editExpenseModal').on('hidden.bs.modal', function() {
        $('#edit-expense-form-container').empty();
        // Remove event handler to prevent duplicates
        $(document).off('submit', '#edit-expense-form');
    });
});
</script>
