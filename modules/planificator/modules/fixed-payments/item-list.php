<?php
/**
 * Shared Item List Template for Payments and Expenses
 * 
 * This file generates the list view for both payments and expenses.
 */

// Ensure this file is included, not accessed directly
if (!defined('MODULE_LOADED')) {
    die('Direct access to this file is not allowed.');
}

// Set table ID and title based on type
$tableId = $type === 'payment' ? 'fixedPaymentsTable' : 'fixedExpensesTable';
$tableTitle = $type === 'payment' ? 'Liste des Paiements Fixes' : 'Liste des Dépenses Fixes';
$filename = $tableTitle . '-' . date('d-m-Y', time());
?>
<div class="card">
    <div class="card-header bg-light d-flex justify-content-between align-items-center" data-section-type="<?php echo $type; ?>">
        <h5 class="mb-0"><?php echo $tableTitle; ?></h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="<?php echo $tableId; ?>" class="table table-striped table-hover" data-item-type="<?php echo $type; ?>">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Catégorie</th>
                        <th>Fréquence</th>
                        <th class="text-end">Montant</th>
                        <th class="text-center">Statut</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th class="search_table">Nom</th>
                        <th class="search_table">Catégorie</th>
                        <th class="search_table">Fréquence</th>
                        <th class="search_table">Montant</th>
                        <th class="search_table">Statut</th>
                        <th></th>
                    </tr>
                </tfoot>
                <tbody>
                    <!-- Table rows will be loaded via AJAX -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    /* Force DataTables to use full width and respect container */
    table.dataTable {
        width: 100% !important;
    }
    
    /* Fix for table cells having fixed widths */
    .dataTable th, .dataTable td {
        width: auto !important;
    }
    
    /* Fix for action buttons spacing */
    .action-column {
        white-space: nowrap;
        width: 1%;
    }
</style>

<script>
    // Use IIFE to avoid polluting global namespace and prevent duplicate bindings
    (function() {
        // Modify the form builder function to use the explicit type passed
        function buildItemForm(response, explicitType) {
            const item = response.item;
            const categories = response.categories || [];
            
            // Use the explicit type passed rather than trying to detect it
            const itemType = explicitType || 'payment';
            const itemId = item.id;
            const itemColor = itemType === 'payment' ? 'success' : 'danger';
            
            // Format date values properly
            const startDate = item.start_date ? item.start_date.split(' ')[0] : '';
            const endDate = item.end_date ? item.end_date.split(' ')[0] : '';
            
            // Build category options
            let categoryOptions = '<option value="">Choisir une catégorie</option>';
            if (categories && categories.length) {
                categories.forEach(cat => {
                    const selected = item.category_id == cat.id ? 'selected' : '';
                    categoryOptions += `<option value="${cat.id}" ${selected}>${cat.name}</option>`;
                });
            }
            
            let html = `
            <form id="edit-item-form" class="needs-validation" novalidate>
                <input type="hidden" name="action" value="update_${itemType}">
                <input type="hidden" name="item_id" value="${itemId}">
                <input type="hidden" name="${itemType}_id" value="${itemId}">
                <input type="hidden" name="membre_id" value="${item.membre_id || ''}">
                <input type="hidden" name="type" value="${itemType}">
                
                <div class="mb-3">
                    <label for="edit_item_name" class="form-label">Nom</label>
                    <input type="text" class="form-control" id="edit_item_name" name="name" 
                        value="${item.name || ''}" required>
                </div>
                
                <div class="mb-3">
                    <label for="edit_category_id" class="form-label">Catégorie</label>
                    <select id="edit_category_id" name="category_id" class="form-select" required>
                        ${categoryOptions}
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="edit_amount" class="form-label">Montant</label>
                    <div class="input-group">
                        <span class="input-group-text">€</span>
                        <input type="text" class="form-control currency-input" id="edit_amount" name="amount"
                            value="${item.amount || ''}" required>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="edit_frequency" class="form-label">Fréquence</label>
                        <select id="edit_frequency" name="frequency" class="form-select" required>
                            <option value="monthly" ${item.frequency === 'monthly' ? 'selected' : ''}>Mensuel</option>
                            <option value="quarterly" ${item.frequency === 'quarterly' ? 'selected' : ''}>Trimestriel</option>
                            <option value="biannual" ${item.frequency === 'biannual' ? 'selected' : ''}>Semestriel</option>
                            <option value="annual" ${item.frequency === 'annual' ? 'selected' : ''}>Annuel</option>
                        </select>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="edit_payment_day" class="form-label">Jour de paiement</label>
                        <input type="number" class="form-control" id="edit_payment_day" name="payment_day" 
                            min="1" max="31" value="${item.payment_day || '1'}" required>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="edit_start_date" class="form-label">Date de début</label>
                        <input type="date" class="form-control" id="edit_start_date" name="start_date"
                            value="${startDate}" required>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="edit_end_date" class="form-label">Date de fin (optionnelle)</label>
                        <input type="date" class="form-control" id="edit_end_date" name="end_date"
                            value="${endDate}">
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="edit_status" class="form-label">Statut</label>
                    <select id="edit_status" name="status" class="form-select">
                        <option value="active" ${item.status === 'active' ? 'selected' : ''}>Actif</option>
                        <option value="inactive" ${item.status === 'inactive' ? 'selected' : ''}>Inactif</option>
                        <option value="cancelled" ${item.status === 'cancelled' ? 'selected' : ''}>Annulé</option>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="edit_notes" class="form-label">Notes</label>
                    <textarea class="form-control" id="edit_notes" name="notes" rows="3">${item.notes || ''}</textarea>
                </div>
                
                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-${itemColor}">
                        Mettre à jour
                        <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>
                </div>
            </form>`;
            
            return html;
        }
        
        /**
         * Initialize currency input formatting
         */
        function initCurrencyInputs() {
            document.querySelectorAll('.currency-input').forEach(inp => {
                // Remove existing event listeners
                inp.removeEventListener('input', validateCurrency);
                inp.removeEventListener('blur', formatCurrency);
                
                // Add input validation to limit to 8 digits before decimal + 2 after (10,2)
                inp.addEventListener('input', validateCurrency);
                
                // Only format on blur (when focus is lost)
                inp.addEventListener('blur', formatCurrency);
                
                // Initial formatting
                if (inp.value) {
                    formatValue(inp);
                }
            });
            
            // Validation function to limit input length
            function validateCurrency() {
                // Remove non-numeric characters except decimal separator
                let value = this.value.replace(/[^\d.,]/g, '');
                value = value.replace(',', '.');
                
                // Split into integer and decimal parts
                const parts = value.split('.');
                
                // Limit integer part to 8 digits (for decimal(10,2) field)
                if (parts[0] && parts[0].length > 8) {
                    parts[0] = parts[0].substring(0, 8);
                }
                
                // Limit decimal part to 2 digits
                if (parts[1] && parts[1].length > 2) {
                    parts[1] = parts[1].substring(0, 2);
                }
                
                // Reconstruct the value
                this.value = parts.join('.');
            }
            
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
                        // Ensure the number doesn't exceed database limits
                        const maxValue = 99999999.99;
                        const safeValue = Math.min(numericValue, maxValue);
                        
                        input.value = safeValue.toLocaleString('fr-FR', { 
                            minimumFractionDigits: 2, 
                            maximumFractionDigits: 2 
                        });
                    }
                }
            }
        }
        
        // Store the initialized DataTable instance in a global variable 
        window['<?php echo $tableId; ?>'] = null;
        
        // Function to initialize table with AJAX loading
        function init<?php echo ucfirst($type); ?>TableScripts(tableId, ajaxUrl) {
            // Only proceed if the table exists on this page
            if ($('#' + tableId).length === 0) {
                return;
            }
            
            // Get item type from the table's data attribute
            const itemType = $('#' + tableId).data('item-type');
            
            // Initialize DataTables for items with AJAX data source
            var itemDataTable = $('#' + tableId).DataTable({
                "processing": true,
                "serverSide": false,
                "ajax": {
                    "url": ajaxUrl,
                    "type": "GET",
                    "data": {
                        "action": "get_" + itemType + "s_list", 
                        "type": itemType  // Use the explicit type from data attribute
                    },
                    "dataSrc": function(json) {
                        return json.items || [];
                    }
                },
                "order": [[0, 'desc']], // Sort by start date (descending order - newest first)
                "columns": [
                    { 
                        "data": null,
                        "render": function(data, type, row) {
                            // For sorting, return the raw date
                            if (type === 'sort' || type === 'type') {
                                return row.start_date || ''; // YYYY-MM-DD format is naturally sortable
                            }
                            
                            // For display, format the date using string operations
                            let startDate = 'N/A';
                            if (row.start_date) {
                                const parts = row.start_date.split('-');
                                if (parts.length === 3) {
                                    // Format as DD/MM/YYYY (French format)
                                    startDate = `${parts[2]}/${parts[1]}/${parts[0]}`;
                                }
                            }
                            return `${row.name}<small class="d-block text-muted">Début: ${startDate}</small>`;
                        }
                    },
                    { 
                        "data": "category_name" 
                    },
                    { 
                        "data": null,
                        "render": function(data, type, row) {
                            let frequencyLabels = {
                                'monthly': 'Mensuel',
                                'quarterly': 'Trimestriel',
                                'biannual': 'Semestriel',
                                'annual': 'Annuel'
                            };
                            let freqLabel = frequencyLabels[row.frequency] || row.frequency;
                            return `${freqLabel}<small class="d-block text-muted">Jour ${row.payment_day}</small>`;
                        }
                    },
                    { 
                        "data": "amount",
                        "className": "text-end",
                        "render": function(data) {
                            return parseFloat(data).toLocaleString('fr-FR', { minimumFractionDigits: 2 }) + ' €';
                        }
                    },
                    { 
                        "data": "status",
                        "className": "text-center",
                        "render": function(data) {
                            let statusClass = 'secondary';
                            let statusLabel = 'Inconnu';
                            
                            if (data === 'active') {
                                statusClass = 'success';
                                statusLabel = 'Actif';
                            } else if (data === 'inactive') {
                                statusClass = 'warning';
                                statusLabel = 'Inactif';
                            } else if (data === 'cancelled') {
                                statusClass = 'danger';
                                statusLabel = 'Annulé';
                            }
                            
                            return `<span class="badge bg-${statusClass}">${statusLabel}</span>`;
                        }
                    },
                    { 
                        "data": null,
                        "className": "text-center action-column",
                        "orderable": false,
                        "render": function(data, type, row) {
                            return `
                                <button type="button" class="btn btn-sm btn-warning edit-item" 
                                    data-id="${row.id}" data-item-type="${itemType}" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger delete-item" 
                                    data-id="${row.id}" data-item-type="${itemType}" title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            `;
                        }
                    }
                ],
                responsive: false,
                stateSave: false,
                dom: 'Bftipr',
                pageLength: 10,
                lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Tous"]],
                buttons: [
                    {
                        extend: 'print',
                        text: "Imprimer",
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        }
                    },
                    {
                        extend: 'pdf',
                        filename: "<?php echo $filename; ?>",
                        title: "<?php echo $tableTitle; ?>",
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        }
                    },
                    {
                        extend: 'csv',
                        filename: "<?php echo $filename; ?>",
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        }
                    },
                    {
                        extend: 'colvis',
                        text: "Colonnes visibles",
                        columns: [0, 1, 2, 3, 4]
                    }
                ],
                columnDefs: [
                    { targets: 0, responsivePriority: 1 },
                    { targets: 3, responsivePriority: 2 },
                    { targets: 4, responsivePriority: 3 },
                    {
                        targets: 5,
                        orderable: false,
                        searchable: false,
                        className: 'action-column',
                        responsivePriority: 1
                    }
                ],
                "language": {
                    "sProcessing": "Traitement en cours...",
                    "sSearch": "Rechercher&nbsp;:",
                    "sLengthMenu": "Afficher _MENU_ &eacute;l&eacute;ments",
                    "sInfo": "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
                    "sInfoEmpty": "Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ments",
                    "sInfoFiltered": "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
                    "sInfoPostFix": "",
                    "sLoadingRecords": "Chargement en cours...",
                    "sZeroRecords": "Aucun &eacute;l&eacute;ment &agrave; afficher",
                    "sEmptyTable": "Aucune donn&eacute;e disponible dans le tableau",
                    "oPaginate": {
                        "sFirst": "Premier",
                        "sPrevious": "Pr&eacute;c&eacute;dent",
                        "sNext": "Suivant",
                        "sLast": "Dernier"
                    },
                    "oAria": {
                        "sSortAscending": ": activer pour trier la colonne par ordre croissant",
                        "sSortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"
                    }
                }
            });

            // Store the DataTable instance globally for access from other scripts
            window[tableId] = itemDataTable;

            // Fix for tables in hidden tabs - ensure proper width for expense table
            if (tableId === 'fixedExpensesTable') {
                // When the expense tab is shown, force columns to adjust
                $(document).on('shown.bs.tab', 'button[data-bs-target="#expenses"]', function() {
                    setTimeout(function() {
                        if (window.fixedExpensesTable) {
                            // Remove any inline width from the table
                            $('#fixedExpensesTable').css('width', '100%');
                            // Force DataTable to recalculate all column widths
                            window.fixedExpensesTable.columns.adjust();
                        }
                    }, 10);
                });
            }

            // Add search functionality for each column
            $('#' + tableId + ' tfoot .search_table').each(function () {
                var title = $(this).text();
                $(this).html('<input type="text" class="form-control" placeholder="' + title + '" style="width:100%; font-weight: normal;"/>'
                );
            });

            // Apply search when typing
            itemDataTable.columns().every(function () {
                var that = this;
                $('input', this.footer()).on('keyup change', function () {
                    if (that.search() !== this.value) {
                        that.search(this.value).draw();
                    }
                });
            });

            // Make sure action buttons are visible after DataTable initialization
            setTimeout(function () {
                $('.action-column button, .action-column a').css('display', 'inline-block');
            }, 100);

            // Remove any existing handlers before adding new ones to prevent duplicates
            $(document).off('click', '.edit-item').on('click', '.edit-item', function (e) {
                e.preventDefault();
                const itemId = $(this).data('id');
                const itemType = $(this).data('item-type'); // Get the item type from the button
                
                // Prevent multiple clicks
                $(this).prop('disabled', true);
                
                // Show loading indicator
                $('#edit-item-form-container').html('<div class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div><p class="mt-2">Chargement en cours...</p></div>');
                
                // First, get categories
                $.ajax({
                    url: '<?php echo $ajaxHandlerUrl; ?>',
                    type: 'GET',
                    data: {
                        action: 'get_categories',
                        type: itemType, // Use the button's type, not the PHP variable
                        _: new Date().getTime()
                    },
                    dataType: 'json',
                    success: function(catResponse) {
                        // Then get the item data with explicit type
                        $.ajax({
                            url: '<?php echo $ajaxHandlerUrl; ?>',
                            type: 'GET',
                            data: {
                                action: 'get_' + itemType,
                                id: itemId,
                                type: itemType, // Use the button's type, not the PHP variable
                                _: new Date().getTime()
                            },
                            success: function(itemResponse) {
                                if (itemResponse.success) {
                                    // Combine data
                                    const combinedResponse = {
                                        success: true,
                                        item: itemResponse.item,
                                        categories: catResponse.success ? catResponse.categories : []
                                    };
                                    
                                    // Build the item edit form
                                    const formHtml = buildItemForm(combinedResponse, itemType);

                                    // Insert the form into the container
                                    $('#edit-item-form-container').html(formHtml);

                                    // Initialize modal if not already shown
                                    const editModal = document.getElementById('editItemModal');
                                    if (!$(editModal).hasClass('show')) {
                                        const modalInstance = new bootstrap.Modal(editModal);
                                        modalInstance.show();
                                    }

                                    // Initialize currency input formatter
                                    initCurrencyInputs();
                                    
                                    // Add AJAX submission handling for the edit form
                                    const editForm = document.getElementById('edit-item-form');
                                    if (editForm) {
                                        editForm.addEventListener('submit', function(e) {
                                            e.preventDefault();
                                            e.stopPropagation();
                                            
                                            const submitBtn = this.querySelector('button[type="submit"]');
                                            const spinner = submitBtn.querySelector('.spinner-border');
                                            submitBtn.disabled = true;
                                            spinner.classList.remove('d-none');
                                            
                                            // Clean currency inputs
                                            const currencyInputs = this.querySelectorAll('.currency-input');
                                            currencyInputs.forEach(input => {
                                                input.value = input.value.replace(/\s/g, '');
                                                input.value = input.value.replace(',', '.');
                                            });
                                            
                                            // Submit via AJAX
                                            const formData = new FormData(this);
                                            
                                            fetch(ajaxUrl, {
                                                method: 'POST',
                                                body: formData
                                            })
                                            .then(response => response.json())
                                            .then(data => {
                                                if (data.success) {
                                                    // Show success message
                                                    popup_alert(data.message, "green filledlight", "#009900", "uk-icon-check");
                                                    
                                                    // Close modal
                                                    const modal = document.getElementById('editItemModal');
                                                    if (modal && typeof bootstrap !== 'undefined') {
                                                        const modalInstance = bootstrap.Modal.getInstance(modal);
                                                        if (modalInstance) {
                                                            modalInstance.hide();
                                                        }
                                                    }
                                                    
                                                    // Get explicit item type from form data
                                                    const itemType = formData.get('type') || 'payment';
                                                    
                                                    // Correctly identify which table to refresh based on item type
                                                    const tableId = itemType === 'payment' ? 'fixedPaymentsTable' : 'fixedExpensesTable';
                                                    
                                                    // Reload the specific table that needs updating
                                                    if (window[tableId]) {
                                                        window[tableId].ajax.reload();
                                                    } else {
                                                        // Fallback: reload both tables
                                                        if (window['fixedPaymentsTable']) window['fixedPaymentsTable'].ajax.reload();
                                                        if (window['fixedExpensesTable']) window['fixedExpensesTable'].ajax.reload();
                                                    }
                                                } else {
                                                    popup_alert(data.error || 'Une erreur est survenue', "#ff0000", "uk-icon-close");
                                                }
                                            })
                                            .catch(error => {
                                                console.error('Error:', error);
                                                popup_alert('Erreur de communication avec le serveur', "#ff0000", "uk-icon-close");
                                            })
                                            .finally(() => {
                                                submitBtn.disabled = false;
                                                spinner.classList.add('d-none');
                                            });
                                            
                                            return false;
                                        });
                                    }
                                } else {
                                    alert('Erreur: ' + (itemResponse.error || 'Une erreur est survenue'));
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error('AJAX Error:', xhr.responseText);
                                popup_alert('Erreur lors de la récupération des données: ' + error, "#ff0000", "#FFFFFF", "uk-icon-close");
                            },
                            complete: function() {
                                // Re-enable the button
                                $('.edit-item').prop('disabled', false);
                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error in categories:', xhr.responseText);
                        popup_alert('Erreur lors de la récupération des catégories: ' + error, "#ff0000", "uk-icon-close");
                        $('.edit-item').prop('disabled', false);
                    }
                });
            });

            // Convert delete item handler to use AJAX
            $(document).off('click', '.delete-item').on('click', '.delete-item', function (e) {
                e.preventDefault();
                const itemId = $(this).data('id');
                const itemType = $(this).data('item-type'); // Get the item type from the button
                const button = $(this);  // Store reference to the clicked button

                if (confirm('Êtes-vous sûr de vouloir supprimer cet élément?')) {
                    // Disable button to prevent multiple clicks
                    button.prop('disabled', true);
                    
                    // Send AJAX request to delete the item
                    $.ajax({
                        url: '<?php echo $ajaxHandlerUrl; ?>',
                        type: 'POST',
                        data: {
                            action: 'delete_' + itemType, // Use the button's type
                            id: itemId,
                            type: itemType // Add explicit type parameter
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                // Show success message with correct parameters
                                popup_alert(response.message, "green filledlight", "#009900", "uk-icon-check");
                                
                                // Correctly identify which table to refresh based on item type
                                const tableId = itemType === 'payment' ? 'fixedPaymentsTable' : 'fixedExpensesTable';
                                
                                // Reload the specific table that needs updating
                                if (window[tableId]) {
                                    window[tableId].ajax.reload();
                                } else {
                                    // Fallback: reload both tables
                                    if (window['fixedPaymentsTable']) window['fixedPaymentsTable'].ajax.reload();
                                    if (window['fixedExpensesTable']) window['fixedExpensesTable'].ajax.reload();
                                }
                            } else {
                                // Show error message with correct parameters
                                popup_alert(response.error || 'Une erreur est survenue', "#ff0000", "uk-icon-close");
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX Error:', xhr.responseText);
                            popup_alert('Erreur lors de la suppression: ' + error, "#ff0000", "uk-icon-close");
                        },
                        complete: function() {
                            // Re-enable the button
                            button.prop('disabled', false);
                        }
                    });
                }
            });
        }

        // Expose initialization function globally
        window['init<?php echo ucfirst($type); ?>TableScripts'] = init<?php echo ucfirst($type); ?>TableScripts;
    })();
</script>