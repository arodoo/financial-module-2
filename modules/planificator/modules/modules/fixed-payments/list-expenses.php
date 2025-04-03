<?php
// This file displays the list of all fixed expenses in a table view
$expenseTableId = 'fixedExpensesTable';
$expenseTableTitle = 'Liste des Dépenses Fixes';
$nom_fichier_datatable_expense = $expenseTableTitle . "-" . date('d-m-Y', time());
?>
<div class="card">
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Liste des Dépenses Fixes</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="<?php echo $expenseTableId; ?>" class="table table-striped table-hover">
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
                    <?php foreach ($expenses as $expense):
                        $categoryName = '';
                        foreach ($categories as $category) {
                            if ($category['id'] == $expense['category_id']) {
                                $categoryName = $category['name'];
                                break;
                            }
                        }

                        // Translate frequency to French
                        $frequencyLabel = '';
                        foreach ($frequencyOptions as $key => $label) {
                            if ($key == $expense['frequency']) {
                                $frequencyLabel = $label;
                                break;
                            }
                        }

                        // Format dates
                        $startDate = !empty($expense['start_date']) ? date('d/m/Y', strtotime($expense['start_date'])) : 'N/A';

                        // Get status class and label
                        $statusClass = '';
                        $statusLabel = '';
                        if ($expense['status'] === 'active') {
                            $statusClass = 'success';
                            $statusLabel = 'Actif';
                        } elseif ($expense['status'] === 'inactive') {
                            $statusClass = 'warning';
                            $statusLabel = 'Inactif';
                        } else {
                            $statusClass = 'danger';
                            $statusLabel = 'Annulé';
                        }
                        ?>
                        <tr>
                            <td>
                                <?php echo htmlspecialchars($expense['name']); ?>
                                <small class="d-block text-muted">Début: <?php echo $startDate; ?></small>
                            </td>
                            <td><?php echo htmlspecialchars($categoryName); ?></td>
                            <td>
                                <?php echo htmlspecialchars($frequencyLabel); ?>
                                <small class="d-block text-muted">Jour <?php echo $expense['payment_day']; ?></small>
                            </td>
                            <td class="text-end"><?php echo number_format($expense['amount'], 2, ',', ' '); ?> €</td>
                            <td class="text-center">
                                <span class="badge bg-<?php echo $statusClass; ?>"><?php echo $statusLabel; ?></span>
                            </td>
                            <td class="text-center action-column">
                                <button type="button" class="btn btn-sm btn-warning edit-expense"
                                    data-id="<?php echo $expense['id']; ?>" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger delete-expense"
                                    data-id="<?php echo $expense['id']; ?>" title="Supprimer">
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
        // Initialize DataTables for expenses
        var expenseDataTable = $('#<?php echo $expenseTableId; ?>').DataTable({
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
                    filename: "<?php echo $nom_fichier_datatable_expense; ?>",
                    title: "<?php echo $expenseTableTitle; ?>",
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'csv',
                    filename: "<?php echo $nom_fichier_datatable_expense; ?>",
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
            }
        });

        // Add search functionality for each column
        $('#<?php echo $expenseTableId; ?> tfoot .search_table').each(function () {
            var title = $(this).text();
            $(this).html('<input type="text" class="form-control" placeholder="' + title + '" style="width:100%; font-weight: normal;"/>');
        });

        // Apply search when typing
        expenseDataTable.columns().every(function () {
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

        // Edit expense button handler
        $(document).on('click', '.edit-expense', function (e) {
            e.preventDefault();
            const expenseId = $(this).data('id');

            $.ajax({
                url: `<?php echo $ajaxHandlerUrl; ?>`,
                type: 'GET',
                data: {
                    action: 'get_depense',
                    depense_id: expenseId
                },
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        // Build the expense edit form
                        const formHtml = buildExpenseForm(response);

                        // Insert the form into the container
                        $('#edit-expense-form-container').html(formHtml);

                        // Initialize modal
                        const editModal = new bootstrap.Modal(document.getElementById('editExpenseModal'));
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

        // Delete expense
        $(document).on('click', '.delete-expense', function () {
            const expenseId = $(this).data('id');

            if (confirm('Êtes-vous sûr de vouloir supprimer cette dépense?')) {
                // Submit form for deletion
                $('<form>')
                    .attr({
                        method: 'POST',
                        style: 'display: none;'
                    })
                    .append($('<input>').attr({
                        type: 'hidden',
                        name: 'expense_id',
                        value: expenseId
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
    });
</script>
