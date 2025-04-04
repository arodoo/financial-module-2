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
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><?php echo $tableTitle; ?></h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="<?php echo $tableId; ?>" class="table table-striped table-hover">
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
                    <?php foreach ($items as $item):
                        $categoryName = '';
                        foreach ($categories as $category) {
                            if ($category['id'] == $item['category_id']) {
                                $categoryName = $category['name'];
                                break;
                            }
                        }

                        // Translate frequency to French
                        $frequencyLabel = '';
                        foreach ($frequencyOptions as $key => $label) {
                            if ($key == $item['frequency']) {
                                $frequencyLabel = $label;
                                break;
                            }
                        }

                        // Format dates
                        $startDate = !empty($item['start_date']) ? date('d/m/Y', strtotime($item['start_date'])) : 'N/A';

                        // Get status class and label
                        $statusClass = '';
                        $statusLabel = '';
                        if ($item['status'] === 'active') {
                            $statusClass = 'success';
                            $statusLabel = 'Actif';
                        } elseif ($item['status'] === 'inactive') {
                            $statusClass = 'warning';
                            $statusLabel = 'Inactif';
                        } else {
                            $statusClass = 'danger';
                            $statusLabel = 'Annulé';
                        }
                        ?>
                        <tr>
                            <td>
                                <?php echo htmlspecialchars($item['name']); ?>
                                <small class="d-block text-muted">Début: <?php echo $startDate; ?></small>
                            </td>
                            <td><?php echo htmlspecialchars($categoryName); ?></td>
                            <td>
                                <?php echo htmlspecialchars($frequencyLabel); ?>
                                <small class="d-block text-muted">Jour <?php echo $item['payment_day']; ?></small>
                            </td>
                            <td class="text-end"><?php echo number_format($item['amount'], 2, ',', ' '); ?> €</td>
                            <td class="text-center">
                                <span class="badge bg-<?php echo $statusClass; ?>"><?php echo $statusLabel; ?></span>
                            </td>
                            <td class="text-center action-column">
                                <button type="button" class="btn btn-sm btn-warning edit-item"
                                    data-id="<?php echo $item['id']; ?>" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger delete-item"
                                    data-id="<?php echo $item['id']; ?>" title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        // Initialize DataTables for items
        var itemDataTable = $('#<?php echo $tableId; ?>').DataTable({
            "order": [],
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

        // Add search functionality for each column
        $('#<?php echo $tableId; ?> tfoot .search_table').each(function () {
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

        // Edit item button handler
        $(document).on('click', '.edit-item', function (e) {
            e.preventDefault();
            const itemId = $(this).data('id');

            $.ajax({
                url: `<?php echo $ajaxHandlerUrl; ?>`,
                type: 'GET',
                data: {
                    action: 'get_' + '<?php echo $type; ?>',
                    id: itemId
                },
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        // Build the item edit form
                        const formHtml = buildItemForm(response);

                        // Insert the form into the container
                        $('#edit-item-form-container').html(formHtml);

                        // Initialize modal
                        const editModal = new bootstrap.Modal(document.getElementById('editItemModal'));
                        editModal.show();

                        // Initialize currency input formatter
                        if (typeof initCurrencyInputs === 'function') {
                            initCurrencyInputs();
                        }
                    } else {
                        alert('Erreur: ' + (response.error || 'Une erreur est survenue'));
                    }
                },
                error: function (xhr, status, error) {
                    alert('Erreur lors de la récupération des données: ' + error);
                }
            });
        });

        // Delete item
        $(document).on('click', '.delete-item', function () {
            const itemId = $(this).data('id');

            if (confirm('Êtes-vous sûr de vouloir supprimer cet élément?')) {
                // Submit form for deletion
                $('<form>')
                    .attr({
                        method: 'POST',
                        style: 'display: none;'
                    })
                    .append($('<input>').attr({
                        type: 'hidden',
                        name: 'item_id',
                        value: itemId
                    }))
                    .append($('<input>').attr({
                        type: 'hidden',
                        name: 'delete_item',
                        value: '1'
                    }))
                    .appendTo('body')
                    .submit();
            }
        });
    });
</script>