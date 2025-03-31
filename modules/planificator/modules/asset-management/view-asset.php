<?php
/**
 * Asset Management Module - Asset Detail View
 * 
 * This file displays detailed information about a specific asset.
 * It now focuses on displaying the asset details with direct edit functionality
 * without additional view modals.
 */

$categoryName = '';
foreach ($categories as $category) {
    if ($category['id'] == $viewAsset['category_id']) {
        $categoryName = $category['name'];
        break;
    }
}

// Find linked loan if exists
$linkedLoan = null;
if (!empty($viewAsset['loan_id'])) {
    $loanController = new LoanController();
    $linkedLoan = $loanController->getLoanById($viewAsset['loan_id']);
}

// Format acquisition and value dates
$acquisitionDate = !empty($viewAsset['acquisition_date']) ? date('d/m/Y', strtotime($viewAsset['acquisition_date'])) : 'N/A';
$lastValuationDate = !empty($viewAsset['valuation_date']) ? date('d/m/Y', strtotime($viewAsset['valuation_date'])) : 'N/A';
?>

<div class="card mb-4 view-container">
    <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Détails de l'Actif: <?php echo htmlspecialchars($viewAsset['name']); ?></h5>
        <button type="button" class="btn btn-sm btn-light" onclick="window.location.href='?action=asset-management'">Retour</button>
    </div>
    <div class="card-body">
        <!-- Asset Details Section -->
        <div class="row mb-4">
            <div class="col-md-6">
                <p><strong>Nom:</strong> <?php echo htmlspecialchars($viewAsset['name']); ?></p>
                <p><strong>Catégorie:</strong> <?php echo htmlspecialchars($categoryName); ?></p>
                <p><strong>Date d'acquisition:</strong> <?php echo $acquisitionDate; ?></p>
                <p><strong>Prix d'acquisition:</strong> <?php echo number_format($viewAsset['acquisition_value'], 0, ',', ' '); ?>€</p>
            </div>
            <div class="col-md-6">
                <p><strong>Valeur actuelle:</strong> <?php echo number_format($viewAsset['current_value'], 0, ',', ' '); ?>€</p>
                <p><strong>Dernière évaluation:</strong> <?php echo $lastValuationDate; ?></p>
                <?php if ($viewAsset['acquisition_value'] > 0): ?>
                    <?php $valueChange = (($viewAsset['current_value'] - $viewAsset['acquisition_value']) / $viewAsset['acquisition_value']) * 100; ?>
                    <p><strong>Évolution:</strong> 
                        <span class="<?php echo $valueChange >= 0 ? 'text-success' : 'text-danger'; ?>">
                            <?php echo ($valueChange >= 0 ? '+' : '') . number_format($valueChange, 2); ?>%
                        </span>
                    </p>
                <?php endif; ?>
                <?php if (!empty($viewAsset['location'])): ?>
                    <p><strong>Emplacement:</strong> <?php echo htmlspecialchars($viewAsset['location']); ?></p>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Linked Loan Section (if applicable) -->
        <?php if ($linkedLoan): ?>
        <hr>
        <h6 class="mb-3">Prêt Associé</h6>
        <div class="row mb-4">
            <div class="col-md-6">
                <p><strong>Nom du Prêt:</strong> <?php echo htmlspecialchars($linkedLoan['name']); ?></p>
                <p><strong>Montant Initial:</strong> <?php echo number_format($linkedLoan['amount'], 0, ',', ' '); ?>€</p>
                <p><strong>Taux d'Intérêt:</strong> <?php echo $linkedLoan['interest_rate']; ?>%</p>
            </div>
            <div class="col-md-6">
                <p><strong>Mensualité:</strong> <?php echo number_format($linkedLoan['monthly_payment'], 2, ',', ' '); ?>€</p>
                <p><strong>Date de Début:</strong> <?php echo date('d/m/Y', strtotime($linkedLoan['start_date'])); ?></p>
                <p><strong>Durée:</strong> <?php echo $linkedLoan['term'] * 12; ?> mois (<?php echo $linkedLoan['term']; ?> ans)</p>
            </div>
            <div class="col-12 mt-2">
                <a href="?action=loan-simulator&view_loan=<?php echo $linkedLoan['id']; ?>" class="btn btn-sm btn-primary">
                    Voir les détails du prêt
                </a>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Notes Section -->
        <?php if (!empty($viewAsset['notes'])): ?>
        <hr>
        <h6 class="mb-3">Notes</h6>
        <div class="p-3 border rounded bg-light">
            <?php echo nl2br(htmlspecialchars($viewAsset['notes'])); ?>
        </div>
        <?php endif; ?>
        
        <!-- Value Evolution Chart -->
        <hr>
        <h6 class="mb-3">Évolution de la Valeur</h6>
        <div class="mb-4">
            <canvas id="assetValueChart" width="400" height="200"></canvas>
        </div>
        
        <!-- Action Buttons -->
        <div class="mt-3 d-flex gap-2">
            <button type="button" class="btn btn-warning edit-asset" data-id="<?php echo $viewAsset['id']; ?>">Modifier</button>
            <button type="button" class="btn btn-danger delete-asset" data-id="<?php echo $viewAsset['id']; ?>">Supprimer</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize chart for current view
    const ctx = document.getElementById('assetValueChart').getContext('2d');
    
    // Sample data with acquisition and current value
    const labels = ['Acquisition', 'Aujourd\'hui'];
    const values = [<?php echo $viewAsset['acquisition_value']; ?>, <?php echo $viewAsset['current_value']; ?>];
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Valeur de l\'actif',
                data: values,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2,
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: false,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString('fr-FR') + '€';
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed.y !== null) {
                                label += new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR' }).format(context.parsed.y);
                            }
                            return label;
                        }
                    }
                }
            }
        }
    });
    
    // Store AJAX handler URL
    const ajaxHandlerUrl = '<?php echo $ajaxHandlerUrl; ?>';
    
    // Edit asset button handler - standardized to match income-expense pattern
    $(document).on('click', '.edit-asset', function(e) {
        e.preventDefault();
        const assetId = $(this).data('id');
        
        $.ajax({
            url: ajaxHandlerUrl,
            type: 'GET',
            data: {
                action: 'get_asset',
                asset_id: assetId
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Build form using the global function - standardized approach
                    const formHtml = buildAssetForm(response);
                    
                    // Insert the form into the container
                    $('#edit-asset-form-container').html(formHtml);
                    
                    // Show the modal using standard Bootstrap approach
                    const editModal = new bootstrap.Modal(document.getElementById('editAssetModal'));
                    editModal.show();
                    
                    // Initialize currency inputs
                    if (typeof initCurrencyInputs === 'function') {
                        initCurrencyInputs();
                    }
                } else {
                    alert('Erreur: ' + (response.error || 'Une erreur est survenue'));
                }
            },
            error: function(xhr, status, error) {
                alert('Erreur lors de la récupération des données: ' + error);
            }
        });
    });
    
    // Delete asset functionality - fixed to use correct field names
    $(document).on('click', '.delete-asset', function(e) {
        e.preventDefault();
        if (confirm('Êtes-vous sûr de vouloir supprimer cet actif ?')) {
            const assetId = $(this).data('id');
            
            $('<form>')
                .attr({
                    method: 'POST',
                    style: 'display: none;'
                })
                .append($('<input>').attr({
                    type: 'hidden',
                    name: 'asset_id',  // Keep the correct field name
                    value: assetId
                }))
                .append($('<input>').attr({
                    type: 'hidden',
                    name: 'delete_asset',
                    value: '1'
                }))
                .appendTo('body')
                .submit();
        }
    });
});
</script>
