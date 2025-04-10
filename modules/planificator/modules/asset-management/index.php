<?php
// Include necessary controllers and models
require_once __DIR__ . '/../../controllers/AssetController.php';
require_once __DIR__ . '/../../models/Asset.php';
require_once __DIR__ . '/../../models/Membre.php';

// Start output buffering at the beginning
ob_start();

// Check for AJAX request before anything else
$isAjaxRequest = isset($_GET['ajax']);
if ($isAjaxRequest) {
    // For AJAX requests, we should redirect to the dedicated ajax-handler.php
    ob_end_clean();
    include __DIR__ . '/ajax-handler.php';
    exit;
}

// If not an AJAX request, continue with normal page processing
ob_end_clean(); // Clear buffer but continue with normal page load

// Initialize controller
$assetController = new AssetController();

// Handle AJAX partials for view and list
if (isset($_GET['ajax_view']) && isset($_GET['asset_id'])) {
    // For AJAX view requests, we'll just include the view-asset.php file directly
    $viewAsset = $assetController->getAssetById($_GET['asset_id']);
    if ($viewAsset) {
        // Fetch categories for the view
        $categories = $assetController->getCategories();
        include __DIR__ . '/view-asset.php';
        exit;
    } else {
        echo '<div class="alert alert-danger">Actif non trouvé</div>';
        exit;
    }
}

if (isset($_GET['ajax_list'])) {
    // For AJAX list requests, we'll just include the list-assets.php file directly
    $assets = $assetController->getAssets();
    $categories = $assetController->getCategories();
    include __DIR__ . '/list-assets.php';
    exit;
}

// Initialize flash message variable (matching income-expense pattern)
$flashMessage = null;
$flashType = null;

// Process form submission - verify field names
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['save_asset'])) {
        // Map asset_name to name if needed
        if (isset($_POST['asset_name']) && !isset($_POST['name'])) {
            $_POST['name'] = $_POST['asset_name'];
        }

        // Convert any dates from empty strings to NULL
        if (empty($_POST['acquisition_date'])) {
            $_POST['acquisition_date'] = date('Y-m-d');
        }
        if (empty($_POST['valuation_date'])) {
            $_POST['valuation_date'] = date('Y-m-d');
        }

        // Ensure we have values for required fields
        if (empty($_POST['name']) || empty($_POST['category_id'])) {
            $flashMessage = 'Erreur: Des champs requis sont manquants.';
            $flashType = 'danger';
        } else {
            // Actually check if the save was successful
            $result = $assetController->saveAsset($_POST);
            if ($result) {
                $flashMessage = 'Actif enregistré avec succès!';
                $flashType = 'success';
            } else {
                $flashMessage = 'Erreur lors de l\'enregistrement de l\'actif. Veuillez réessayer.';
                $flashType = 'danger';
            }
        }
    } elseif (isset($_POST['update_asset'])) {
        $assetController->updateAsset($_POST);
        $flashMessage = 'Actif mis à jour avec succès!';
        $flashType = 'success';
    } elseif (isset($_POST['delete_asset'])) {
        // Verify we have the asset_id
        if (isset($_POST['asset_id'])) {
            $assetController->deleteAsset($_POST['asset_id']);
            $flashMessage = 'Actif supprimé avec succès!';
            $flashType = 'success';
        } else {
            $flashMessage = 'Erreur: ID d\'actif manquant!';
            $flashType = 'danger';
        }
    }
}

// Check if there's a session flash message (matching income-expense pattern)
elseif (isset($_SESSION['asset_flash_message'])) {
    if ($_SESSION['asset_flash_message'] === 'asset_added') {
        $flashMessage = "Actif enregistré avec succès!";
    } elseif ($_SESSION['asset_flash_message'] === 'asset_updated') {
        $flashMessage = "Actif mis à jour avec succès!";
    } elseif ($_SESSION['asset_flash_message'] === 'asset_deleted') {
        $flashMessage = "Actif supprimé avec succès!";
    }
    $flashType = "success";
    unset($_SESSION['asset_flash_message']);
}

// Get data for the view - this needs to be after form processing
$viewData = $assetController->getViewData();
$assets = $viewData['assets'] ?? [];
$categories = $viewData['categories'] ?? [];
$selectedAsset = $viewData['selectedAsset'] ?? null;
$viewAsset = $viewData['viewAsset'] ?? null;
$editAsset = $viewData['editAsset'] ?? null;

$ajaxHandlerUrl = '/modules/planificator/modules/asset-management/ajax-handler.php';
?>

<!-- Success Message - Standardized with income-expense module -->
<?php if ($flashMessage): ?>
    <div class="alert alert-<?php echo $flashType; ?> alert-dismissible fade show" role="alert">
        <?php echo $flashMessage; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="row mb-3">
    <div class="col-12 text-end">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAssetModal">
            <i class="fas fa-plus"></i> Ajouter un Actif
        </button>
    </div>
</div>

<div class="row">
    <!-- Full-width column for Asset Details or Assets List -->
    <div class="col-md-12">
        <?php if ($viewAsset): ?>
            <?php include __DIR__ . '/view-asset.php'; ?>
        <?php elseif (!empty($assets)): ?>
            <?php include __DIR__ . '/list-assets.php'; ?>
        <?php else: ?>
            <div class="card">
                <div class="card-body">
                    <div class="text-center py-5">
                        <h4>Gérez vos actifs</h4>
                        <p class="text-muted">
                            Utilisez le formulaire pour ajouter des actifs à votre portefeuille.
                        </p>
                        <img src="https://via.placeholder.com/400x200?text=Asset+Management" alt="Asset Management"
                            class="img-fluid mt-3 mb-3 rounded">
                        <p>
                            Le module de gestion d'actifs vous permet de:
                        </p>
                        <ul class="text-start">
                            <li>Suivre tous vos actifs financiers et immobiliers</li>
                            <li>Enregistrer les détails importants de chaque actif</li>
                            <li>Associer des prêts à vos actifs immobiliers</li>
                            <li>Visualiser l'évolution de la valeur de vos actifs</li>
                            <li>Analyser votre patrimoine global</li>
                        </ul>
                    </div>
                </div>
            </div>
        <?php endif; ?>    </div>
</div>

<!-- Add Asset Modal -->
<div class="modal fade" id="addAssetModal" tabindex="-1" aria-labelledby="addAssetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addAssetModalLabel">Ajouter un Actif</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php
                $isEditMode = false;
                $asset = null;
                include __DIR__ . '/add-edit-asset.php';
                ?>
            </div>
        </div>
    </div>
</div>

<!-- Edit Asset Modal - Only keep this one modal -->
<div class="modal fade" id="editAssetModal" tabindex="-1" aria-labelledby="editAssetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editAssetModalLabel">Modifier Actif</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="edit-asset-form-container"></div>
            </div>
            <!-- Modal footer removed to prevent duplicate buttons -->
        </div>
    </div>
</div>

<!-- Global JavaScript utilities for asset management -->
<script>
    // Improved utility function to build asset form for editing - ensuring all fields are mapped correctly
    function buildAssetForm(data) {
        // Set default values for fields that might be null
        const acquisitionDate = data.acquisition_date || '';
        const acquisitionValue = data.acquisition_value ? data.acquisition_value : '0';
        const valuationDate = data.valuation_date || '';
        const currentValue = data.current_value || '0';

        // Create a complete form with all elements
        let formHtml = `
        <form id="edit-asset-form" method="POST" onsubmit="submitAssetForm(event)">
            <input type="hidden" name="asset_id" value="${data.id}">
            
            <div class="mb-3">
                <label for="asset_name" class="form-label">Nom de l'actif</label>
                <input type="text" class="form-control" id="asset_name" name="asset_name" 
                    value="${data.name || ''}" required>
            </div>
            
            <div class="mb-3">
                <label for="category_id" class="form-label">Catégorie</label>
                <select class="form-select" id="category_id" name="category_id" required>
                    <option value="">Choisir une catégorie</option>`;

        // Populate category options and set selected option
        if (data.categories && data.categories.length) {
            data.categories.forEach(category => {
                const selected = parseInt(category.id) === parseInt(data.category_id) ? 'selected="selected"' : '';
                formHtml += `<option value="${category.id}" ${selected}>${category.name}</option>`;
            });
        }

        formHtml += `</select>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="acquisition_date" class="form-label">Date d'acquisition</label>
                    <input type="date" class="form-control" id="acquisition_date" name="acquisition_date" 
                        value="${acquisitionDate}">
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="acquisition_value" class="form-label">Prix d'acquisition (€)</label>
                    <input type="text" class="form-control currency-input" id="acquisition_value" name="acquisition_value" 
                        value="${acquisitionValue}" required>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="valuation_date" class="form-label">Date de dernière évaluation</label>
                    <input type="date" class="form-control" id="valuation_date" name="valuation_date" 
                        value="${valuationDate}">
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="current_value" class="form-label">Valeur actuelle (€)</label>
                    <input type="text" class="form-control currency-input" id="current_value" name="current_value" 
                        value="${currentValue}" required>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="location" class="form-label">Emplacement</label>
                <input type="text" class="form-control" id="location" name="location" 
                    value="${data.location || ''}" 
                    placeholder="Adresse ou localisation (optionnel)">
            </div>
            
            <div class="mb-3">
                <label for="notes" class="form-label">Notes</label>
                <textarea class="form-control" id="notes" name="notes" rows="3" 
                    placeholder="Informations supplémentaires (optionnel)">${data.notes || ''}</textarea>
            </div>
            
            <div class="d-flex justify-content-between">
                <button type="submit" name="update_asset" class="btn btn-warning">
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    Mettre à jour
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
            </div>
        </form>
    `;

        return formHtml;
    }

    // Function to format currency values
    function formatCurrency(value) {
        return value ? Number(value).toLocaleString('fr-FR') : '';
    }

    // Initialize currency input formatter
    function initCurrencyInputs() {
        const currencyInputs = document.querySelectorAll('.currency-input');
        currencyInputs.forEach(input => {
            // Format when typing
            input.addEventListener('input', function (e) {
                let value = this.value.replace(/\D/g, '');
                this.value = new Intl.NumberFormat('fr-FR').format(value);
            });

            // Clean before form submission
            input.form?.addEventListener('submit', function () {
                currencyInputs.forEach(inp => {
                    inp.value = inp.value.replace(/\s/g, '');
                });
            });
        });
    }

    // Function to submit the form via AJAX - standardized with income-expense pattern
    window.submitAssetForm = function (event) {
        event.preventDefault();

        const form = event.target;
        const formData = new FormData(form);

        // Add the update_asset parameter that would normally be submitted with the button
        formData.append('update_asset', '1');

        // Add the action parameter to tell the AJAX handler what to do
        formData.append('action', 'update_asset');

        // Show spinner and disable button
        const submitBtn = form.querySelector('button[type="submit"]');
        const spinner = submitBtn.querySelector('.spinner-border');
        submitBtn.disabled = true;
        spinner.classList.remove('d-none');

        // Clean currency inputs for proper backend processing
        const currencyInputs = form.querySelectorAll('.currency-input');
        currencyInputs.forEach(input => {
            const cleanValue = input.value.replace(/\s/g, '');
            formData.set(input.name, cleanValue);
        });

        // Submit via AJAX
        fetch('<?php echo $ajaxHandlerUrl; ?>', {
            method: 'POST',
            body: formData
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(response => {
                // Hide spinner and enable button
                submitBtn.disabled = false;
                spinner.classList.add('d-none');

                if (response.success) {
                    // Close the modal
                    const editModal = bootstrap.Modal.getInstance(document.getElementById('editAssetModal'));
                    if (editModal) {
                        editModal.hide();
                    }

                    // Show success message
                    popup_alert(response.message || 'Actif mis à jour avec succès', "green filledlight", "#009900", "uk-icon-check");

                    // Reload the data table
                    if (window.assetManagementTable) {
                        window.assetManagementTable.ajax.reload();
                    }
                } else {
                    // Show error message
                    popup_alert(response.error || 'Une erreur est survenue', "#ff0000", "#FFFFFF", "uk-icon-close");
                }
            })
            .catch(error => {
                // Hide spinner and enable button
                submitBtn.disabled = false;
                spinner.classList.add('d-none');

                popup_alert('Erreur lors de la mise à jour de l\'actif', "#ff0000", "#FFFFFF", "uk-icon-close");
            });
    };

    // Clean up modal when closed - matching income-expense pattern
    $('#editAssetModal').on('hidden.bs.modal', function () {
        $('#edit-asset-form-container').empty();
    });    // Initialize when document is ready
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize currency inputs
        initCurrencyInputs();

        // If there are pre-existing currency inputs, format them
        document.querySelectorAll('.currency-input').forEach(input => {
            if (input.value) {
                let value = input.value.replace(/\D/g, '');
                input.value = new Intl.NumberFormat('fr-FR').format(value);
            }
        });
    });

    // Function to submit new asset form via AJAX with modal handling
    window.submitNewAssetForm = function(event) {
        event.preventDefault();

        const form = event.target;
        const formData = new FormData(form);

        // Add appropriate action parameter
        formData.append('action', 'save_asset');
        formData.append('save_asset', '1');

        // Show spinner and disable button
        const submitBtn = form.querySelector('button[type="submit"]');
        const spinner = submitBtn.querySelector('.spinner-border');
        submitBtn.disabled = true;
        spinner.classList.remove('d-none');

        // Clean currency inputs for proper backend processing
        const currencyInputs = form.querySelectorAll('.currency-input');
        currencyInputs.forEach(input => {
            const cleanValue = input.value.replace(/\s/g, '');
            formData.set(input.name, cleanValue);
        });

        // Submit via AJAX
        fetch('<?php echo $ajaxHandlerUrl; ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(response => {
            // Hide spinner and enable button
            submitBtn.disabled = false;
            spinner.classList.add('d-none');

            if (response.success) {
                // Close the modal
                const modal = document.getElementById('addAssetModal');
                if (modal) {
                    const modalInstance = bootstrap.Modal.getInstance(modal);
                    if (modalInstance) modalInstance.hide();
                }

                // Show success message
                popup_alert(response.message || 'Actif enregistré avec succès', "green filledlight", "#009900", "uk-icon-check");

                // Reload the data table
                if (window.assetManagementTable) {
                    window.assetManagementTable.ajax.reload();
                }

                // Reset form
                form.reset();
            } else {
                // Show error message
                popup_alert(response.error || 'Une erreur est survenue', "#ff0000", "#FFFFFF", "uk-icon-close");
            }
        })
        .catch(error => {
            // Hide spinner and enable button
            submitBtn.disabled = false;
            spinner.classList.add('d-none');
            
            popup_alert('Erreur lors de l\'enregistrement de l\'actif', "#ff0000", "#FFFFFF", "uk-icon-close");
        });
    };

    // Clean up modal when closed - matching income-expense pattern
    $('#editAssetModal').on('hidden.bs.modal', function () {
        $('#edit-asset-form-container').empty();
    });

    // Add event handler to clean up add asset modal when closed
    $('#addAssetModal').on('hidden.bs.modal', function () {
        // Reset form
        const form = document.getElementById('asset-form');
        if (form) form.reset();
        
        // Clear validation styling
        $('#asset-form').removeClass('was-validated');
        
        // Format currency inputs
        document.querySelectorAll('#asset-form .currency-input').forEach(input => {
            input.value = '';
        });
    });
</script>

<!-- Include asset management JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>