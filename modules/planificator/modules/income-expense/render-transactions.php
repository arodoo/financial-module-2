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
                    <?php 
                    $transactions = $transactionType === 'income' ? $incomeTransactions : $expenseTransactions;
                    if (empty($transactions)): 
                    ?>
                        <tr class="no-data-row">
                            <td colspan="5" class="text-center">
                                Aucune transaction <?php echo $transactionType === 'income' ? 'de revenu' : 'de dépense'; ?> trouvée pour cette période.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($transactions as $transaction): ?>
                            <tr>
                                <td><?php echo date('d M Y', strtotime($transaction['transaction_date'])); ?></td>
                                <td><?php echo $transaction['category_name']; ?></td>
                                <td><?php echo $transaction['description'] ?: 'N/A'; ?></td>
                                <td class="text-end">€<?php echo number_format($transaction['amount'], 2); ?></td>
                                <td class="text-center action-column">
                                    <button type="button" class="btn btn-sm btn-primary edit-transaction" data-id="<?php echo $transaction['id']; ?>" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger delete-transaction" data-id="<?php echo $transaction['id']; ?>" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // First, store our tableId and check for data
    var tableId = '<?php echo $tableId; ?>';
    var $table = $('#' + tableId);
    var hasData = $table.find('tbody tr').not('.no-data-row').length > 0;
    
    // Skip DataTables completely for empty tables to avoid the error
    if (!hasData) {
        $table.addClass('empty-data-table');
        
        // Add basic styling to make it look similar to DataTables
        $table.addClass('display');
        $table.find('thead th').css('padding', '8px');
        $table.find('tbody td').css('padding', '8px');
        
        // No DataTables, but set a flag for reference
        $table.data('hasData', false);
        $table.data('dataTablesInitialized', false);
        
        // Mark the table so our edit/delete handlers know this is not a DataTable
        $table.attr('data-no-datatable', 'true');
    } else {
        // Remove the no-data row for tables with data
        $table.find('.no-data-row').remove();
        
        // For tables with data, use standard DataTable initialization with error catching
        try {
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
            
            // Full-featured settings for table with data
            var dataTable = $table.DataTable({
                "order": [],
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
                        orderable: false,
                        searchable: false,
                        className: 'action-column',
                        responsivePriority: 1
                    }
                ],
                "language": languageSettings
            });
            
            $table.data('datatable', dataTable);
            $table.data('hasData', true);
            $table.data('dataTablesInitialized', true);
            
            // Add search inputs
            $table.find('tfoot .search_table').each(function() {
                var title = $(this).text();
                $(this).html('<input type="text" class="form-control" placeholder="' + title + '" style="width:100%; font-weight: normal;"/>');
            });
            
            // Set up column searching
            dataTable.columns().every(function() {
                var that = this;
                $('input', this.footer()).on('keyup change', function() {
                    if (that.search() !== this.value) {
                        that.search(this.value).draw();
                    }
                });
            });
        } catch (error) {
            // If DataTables fails, fall back to basic styling
            $table.addClass('table-error-fallback display');
            $table.find('thead th').css('padding', '8px');
            $table.find('tbody td').css('padding', '8px');
            $table.data('dataTablesInitialized', false);
            $table.attr('data-datatable-failed', 'true');
        }
    }
    
    // Make sure action buttons are visible, even for non-DataTable tables
    setTimeout(function() {
        $('.action-column button').css('display', 'inline-block');
    }, 100);
});
</script>
