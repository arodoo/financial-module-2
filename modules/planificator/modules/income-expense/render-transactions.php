<?php
/**
 * Transaction Table Renderer
 * 
 * This file renders a DataTable for income or expense transactions.
 * It takes a $transactionType parameter ('income' or 'expense') and displays
 * the corresponding transactions with filtering, sorting, and export options.
 * The file also includes JavaScript for DataTable initialization and column search.
 */

// Unique identifier for the table based on transaction type
$tableId = $transactionType === 'income' ? 'incomeTransactionsTable' : 'expenseTransactionsTable';
$tableTitle = $transactionType === 'income' ? 'Revenus' : 'Dépenses';
$nom_fichier_datatable = $tableTitle . "-" . date('d-m-Y', time());
$ajaxAction = $transactionType === 'income' ? 'get_income_list' : 'get_expense_list';
?>

<div class="card">
    <div class="card-header bg-light">
        <h5 class="mb-0">Transactions de <?php echo $tableTitle; ?></h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="<?php echo $tableId; ?>" class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Catégorie</th>
                        <th>Description</th>
                        <th>Montant</th>
                        <th class="text-center" style="min-width: 100px;">Actions</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th class="search_table">Date</th>
                        <th class="search_table">Catégorie</th>
                        <th class="search_table">Description</th>
                        <th class="search_table">Montant</th>
                        <th></th>
                    </tr>
                </tfoot>
                <tbody>
                    <!-- Data will be loaded via AJAX -->
                </tbody>            </table>
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
</style>

<script>    $(document).ready(function () {
        // First, store our tableId
        var tableId = '<?php echo $tableId; ?>';
        var $table = $('#' + tableId);
        
        // Check if this table is already initialized - prevent duplicate initialization
        if ($.fn.DataTable.isDataTable('#' + tableId)) {
            console.log('Table ' + tableId + ' already initialized, skipping initialization');
            return;
        }
        
        var transactionType = '<?php echo $transactionType; ?>';
        var ajaxHandlerUrl = '<?php echo $ajaxHandlerUrl; ?>';
        var startDate = $('#periodStartDate').val() || '<?php echo date('Y-m-01'); ?>';
        var endDate = $('#periodEndDate').val() || '<?php echo date('Y-m-t'); ?>';

        // Define language settings
        var languageSettings = {
            "sProcessing": "Traitement en cours...",
            "sSearch": "Rechercher&nbsp;:",
            "sLengthMenu": "Afficher _MENU_ &eacute;l&eacute;ments",
            "sInfo": "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
            "sInfoEmpty": "Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;léments",
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
        };

        // Initialize DataTable with AJAX
        try {
            var dataTable = $table.DataTable({
                "processing": true,
                "serverSide": false,
                "ajax": {
                    "url": ajaxHandlerUrl,
                    "type": "GET",
                    "data": {
                        "action": '<?php echo $ajaxAction; ?>',
                        "start_date": startDate,
                        "end_date": endDate
                    },
                    "dataSrc": function (json) {
                        return json.data || [];
                    }
                },
                "columns": [
                    {
                        "data": "transaction_date",
                        "render": function (data, type, row) {
                            // For sorting or filtering, return the raw date
                            if (type === 'sort' || type === 'type') {
                                return data || ''; // YYYY-MM-DD format is naturally sortable
                            }

                            // For display, format the date using string operations
                            if (data) {
                                const parts = data.split('-');
                                if (parts.length === 3) {
                                    // Format as DD/MM/YYYY
                                    return `${parts[2]}/${parts[1]}/${parts[0]}`;
                                }
                            }
                            return 'N/A';
                        }
                    },
                    { "data": "category_name" },
                    {
                        "data": "description",
                        "render": function (data) {
                            return data || 'N/A';
                        }
                    },
                    {
                        "data": "amount",
                        "className": "text-end",
                        "render": function (data) {
                            return '€' + parseFloat(data).toFixed(2);
                        }
                    },
                    {
                        "data": "id",
                        "orderable": false,
                        "searchable": false,
                        "className": "text-center action-column",
                        "render": function (data) {
                            return `
                            <button type="button" class="btn btn-sm btn-primary edit-transaction" data-id="${data}" title="Modifier">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-danger delete-transaction" data-id="${data}" title="Supprimer">
                                <i class="fas fa-trash"></i>
                            </button>
                        `;
                        }
                    }
                ],
                "order": [[0, 'desc']], // Sort by transaction date in descending order (newest first)
                "responsive": false,
                "stateSave": false,
                "dom": 'Bftipr',
                "pageLength": 5,
                "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Tous"]],
                "buttons": [
                    {
                        extend: 'print',
                        text: "Imprimer",
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        }
                    },
                    {
                        extend: 'pdf',
                        filename: "<?php echo $nom_fichier_datatable; ?>",
                        title: "<?php echo $tableTitle; ?>",
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        }
                    },
                    {
                        extend: 'csv',
                        filename: "<?php echo $nom_fichier_datatable; ?>",
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        }
                    },
                    {
                        extend: 'colvis',
                        text: "Colonnes visibles",
                        columns: [0, 1, 2, 3]
                    }
                ],
                "columnDefs": [
                    {
                        targets: 0,
                        responsivePriority: 2
                    },
                    {
                        targets: 3,
                        responsivePriority: 3
                    },
                    {
                        targets: 4,
                        responsivePriority: 1
                    }
                ],
                "language": languageSettings
            });            // Store the DataTable instance in a global variable for later access
            window[tableId] = dataTable;
            $table.data('hasData', true);
            $table.data('dataTablesInitialized', true);

            // Fix for tables in hidden tabs - apply specific handling for expense tab
            if (tableId === 'expenseTransactionsTable') {
                // When the expense tab is shown, force columns to adjust
                $(document).on('shown.bs.tab', 'button[data-bs-target="#expense"]', function() {
                    setTimeout(function() {
                        if (window.expenseTransactionsTable) {
                            // Remove any inline width from the table
                            $('#expenseTransactionsTable').css('width', '100%');
                            // Force DataTable to recalculate all column widths
                            window.expenseTransactionsTable.columns.adjust();
                        }
                    }, 10);
                });
            }

            // Add search inputs
            $table.find('tfoot .search_table').each(function () {
                var title = $(this).text();
                $(this).html('<input type="text" class="form-control" placeholder="' + title + '" style="width:100%; font-weight: normal;"/>');
            });

            // Set up column searching
            dataTable.columns().every(function () {
                var that = this;
                $('input', this.footer()).on('keyup change', function () {
                    if (that.search() !== this.value) {
                        that.search(this.value).draw();
                    }
                });
            });
        } catch (error) {
            console.error("Error initializing DataTable:", error);
            // If DataTables fails, fall back to showing an error message
            $table.html('<div class="alert alert-danger">Erreur de chargement du tableau. Veuillez rafraîchir la page.</div>');
        }
    });
</script>