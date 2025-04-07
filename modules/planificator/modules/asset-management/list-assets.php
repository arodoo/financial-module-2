<?php
// This file displays the list of all assets in a table view
$tableId = 'assetManagementTable';
$tableTitle = 'Liste des Actifs';
$nom_fichier_datatable = $tableTitle . "-" . date('d-m-Y', time());
?>
<div class="card">
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Liste des Actifs</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="<?php echo $tableId; ?>" class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Catégorie</th>
                        <th class="text-end">Valeur</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th class="search_table">Nom</th>
                        <th class="search_table">Catégorie</th>
                        <th class="search_table">Valeur</th>
                        <th></th>
                    </tr>
                </tfoot>
                <tbody>
                    <!-- Data will be loaded via AJAX -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Initialize DataTables
    var dataTable = $('#<?php echo $tableId; ?>').DataTable({
        "order": [],
        responsive: false,
        stateSave: false, 
        dom: 'Bftipr',
        pageLength: 5,
        lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Tous"]],
        "processing": true,
        "serverSide": false,
        "ajax": {
            "url": `<?php echo $ajaxHandlerUrl; ?>`,
            "type": "GET",
            "data": {
                "action": "get_assets_list"
            },
            "dataSrc": function(json) {
                return json.data || [];
            }
        },
        "columns": [
            { 
                "data": null,
                "render": function(data, type, row) {
                    // Format the acquisition date
                    // Fix: Check for purchase_date (database field) instead of acquisition_date
                    let acquisitionDate = row.purchase_date ? 
                        new Date(row.purchase_date).toLocaleDateString('fr-FR') : 'N/A';
                    return `${row.name}<small class="d-block text-muted">Acquis: ${acquisitionDate}</small>`;
                }
            },
            { "data": "category_name" },
            { 
                "data": "current_value",
                "className": "text-end",
                "render": function(data, type, row) {
                    return new Intl.NumberFormat('fr-FR').format(data) + '€';
                }
            },
            {
                "data": null,
                "className": "text-center action-column",
                "orderable": false,
                "searchable": false,
                "render": function(data, type, row) {
                    return `
                        <button type="button" class="btn btn-sm btn-warning edit-asset" data-id="${row.id}" title="Modifier">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-danger delete-asset" data-id="${row.id}" title="Supprimer">
                            <i class="fas fa-trash"></i>
                        </button>
                    `;
                }
            }
        ],
        buttons: [
            {
                extend: 'print',
                text: "Imprimer",
                exportOptions: {
                    columns: [0, 1, 2]
                }
            },
            {
                extend: 'pdf',
                filename: "<?php echo $nom_fichier_datatable; ?>",
                title: "<?php echo $tableTitle; ?>",
                exportOptions: {
                    columns: [0, 1, 2]
                }
            }, 
            {
                extend: 'csv',
                filename: "<?php echo $nom_fichier_datatable; ?>",
                exportOptions: {
                    columns: [0, 1, 2]
                }
            }, 
            {
                extend: 'colvis',
                text: "Colonnes visibles",
                columns: [0, 1, 2]
            }
        ],
        columnDefs: [
            { targets: 0, responsivePriority: 2 },
            { targets: 2, responsivePriority: 3 },
            { 
                targets: 3,
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
    
    // Store the DataTable instance globally for access from other scripts
    window['assetManagementTable'] = dataTable;
    
    // Add search functionality for each column
    $('#<?php echo $tableId; ?> tfoot .search_table').each(function() {
        var title = $(this).text();
        $(this).html('<input type="text" class="form-control" placeholder="' + title + '" style="width:100%; font-weight: normal;"/>');
    });
    
    // Apply search when typing
    dataTable.columns().every(function() {
        var that = this;
        $('input', this.footer()).on('keyup change', function() {
            if (that.search() !== this.value) {
                that.search(this.value).draw();
            }
        });
    });
    
    // Make sure action buttons are visible after DataTable initialization
    setTimeout(function() {
        $('.action-column button, .action-column a').css('display', 'inline-block');
    }, 100);
    
    // Edit asset button handler - standardized to match income-expense pattern
    $(document).on('click', '.edit-asset', function(e) {
        e.preventDefault();
        const assetId = $(this).data('id');
        
        // Show loading indicator
        $('#edit-asset-form-container').html('<div class="text-center"><div class="spinner-border text-primary" role="status"></div><p>Chargement...</p></div>');
        
        $.ajax({
            url: `<?php echo $ajaxHandlerUrl; ?>`,
            type: 'GET',
            data: {
                action: 'get_asset',
                asset_id: assetId
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Build the asset edit form
                    const formHtml = buildAssetForm(response);
                    
                    // Insert the form into the container
                    $('#edit-asset-form-container').html(formHtml);
                    
                    // Load categories AFTER form is rendered
                    $.ajax({
                        url: `<?php echo $ajaxHandlerUrl; ?>`,
                        type: 'GET',
                        data: {
                            action: 'get_categories'
                        },
                        dataType: 'json',
                        success: function(categoriesResponse) {
                            if (categoriesResponse.success && categoriesResponse.data) {
                                const categoriesSelect = document.getElementById('category_id');
                                if (categoriesSelect) {
                                    // First clear any existing options except the default
                                    while (categoriesSelect.options.length > 1) {
                                        categoriesSelect.remove(1);
                                    }
                                    
                                    // Then add category options and select the matching one
                                    categoriesResponse.data.forEach(category => {
                                        const option = document.createElement('option');
                                        option.value = category.id;
                                        option.textContent = category.name;
                                        
                                        // Use strict equality to make sure the comparison works correctly
                                        if (parseInt(category.id) === parseInt(response.category_id)) {
                                            option.selected = true;
                                        }
                                        
                                        categoriesSelect.appendChild(option);
                                    });
                                }
                            }
                        },
                        error: function(xhr) {
                            popup_alert('Erreur lors du chargement des catégories', "#ff0000", "#FFFFFF", "uk-icon-close");
                        }
                    });
                    
                    // Initialize modal using bootstrap standard approach
                    const editModal = new bootstrap.Modal(document.getElementById('editAssetModal'));
                    editModal.show();
                    
                    // Initialize currency input formatter
                    if (typeof initCurrencyInputs === 'function') {
                        initCurrencyInputs();
                    }
                } else {
                    popup_alert('Erreur: ' + (response.error || 'Une erreur est survenue'), "#ff0000", "#FFFFFF", "uk-icon-close");
                }
            },
            error: function(xhr, status, error) {
                popup_alert('Erreur lors de la récupération des données: ' + error, "#ff0000", "#FFFFFF", "uk-icon-close");
            }
        });
    });
    
    // Delete asset - using AJAX instead of form submission
    $(document).on('click', '.delete-asset', function() {
        const assetId = $(this).data('id');
        
        if (confirm('Êtes-vous sûr de vouloir supprimer cet actif?')) {
            $.ajax({
                url: `<?php echo $ajaxHandlerUrl; ?>`,
                type: 'POST',
                data: {
                    action: 'delete_asset',
                    asset_id: assetId
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // Show success message
                        popup_alert('Actif supprimé avec succès', "green filledlight", "#009900", "uk-icon-check");
                        
                        // Reload the assets table
                        dataTable.ajax.reload();
                    } else {
                        // Show error message
                        popup_alert('Erreur: ' + (response.error || 'Une erreur est survenue'), "#ff0000", "#FFFFFF", "uk-icon-close");
                    }
                },
                error: function(xhr, status, error) {
                    // Show network error
                    popup_alert('Erreur lors de la suppression: ' + error, "#ff0000", "#FFFFFF", "uk-icon-close");
                }
            });
        }
    });
});
</script>
