<?php
// Start output buffering to prevent any unwanted output - matches income-expense pattern
ob_start();

// Include main configuration files
require_once $_SERVER['DOCUMENT_ROOT'] . '/Configurations_bdd.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Configurations.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Configurations_modules.php';

// Include any needed functions
$dir_fonction = $_SERVER['DOCUMENT_ROOT'] . '/';
require_once $_SERVER['DOCUMENT_ROOT'] . '/function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php';

// Default response - standardized with income-expense module
$response = [
    'success' => false,
    'error' => 'Default error state',
    'message' => '',
    'data' => null
];

// Only proceed if the user is logged in (like in income-expense module)
if (isset($user) || !empty($_SESSION['4M8e7M5b1R2e8s'])) {
    // Clear any previous output that might have occurred during includes
    ob_clean();
    
    try {
        // Since this is only accessed via AJAX, we require these files here
        require_once __DIR__ . '/../../controllers/AssetController.php';
        require_once __DIR__ . '/../../models/Asset.php';

        // Initialize the controller
        $assetController = new AssetController();
        
        // Process AJAX requests based on action parameter (like income-expense)
        if (isset($_GET['action'])) {
            $action = $_GET['action'];
            
            switch ($action) {
                case 'get_assets':
                    // Get all assets or filter by type/category
                    $type = $_REQUEST['type'] ?? null;
                    $category = $_REQUEST['category'] ?? null;
                    $response['data'] = $assetController->getAssets();
                    $response['success'] = true;
                    unset($response['error']); // Remove error from successful response
                    break;
                    
                case 'get_asset':
                    // Get details of a specific asset
                    if (isset($_GET['asset_id'])) {
                        $asset = $assetController->getAssetById($_GET['asset_id']);
                        if ($asset) {
                            // Instead of direct assignment, copy all asset data and set success flag
                            foreach ($asset as $key => $value) {
                                $response[$key] = $value;
                            }
                            $response['success'] = true;
                            
                            // Log field names for debugging
                            error_log('Asset fields: ' . print_r(array_keys($asset), true));
                            
                            unset($response['error']);
                        } else {
                            $response['error'] = 'Actif non trouvé';
                        }
                    } else {
                        $response['error'] = 'ID d\'actif manquant';
                    }
                    break;
                    
                case 'save_asset':
                    // Add a new asset
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        // Log the incoming data
                        error_log('Incoming save asset data: ' . print_r($_POST, true));
                        
                        // Map field names if needed (old form might still use asset_name)
                        if (isset($_POST['asset_name']) && !isset($_POST['name'])) {
                            $_POST['name'] = $_POST['asset_name'];
                        }
                        
                        // Ensure required fields exist
                        if (!isset($_POST['name']) || !isset($_POST['category_id'])) {
                            $response['error'] = 'Champs requis manquants';
                            break;
                        }
                        
                        // Set default dates if empty
                        if (empty($_POST['acquisition_date'])) {
                            $_POST['acquisition_date'] = date('Y-m-d');
                        }
                        if (empty($_POST['valuation_date'])) {
                            $_POST['valuation_date'] = date('Y-m-d');
                        }
                        
                        $result = $assetController->saveAsset($_POST);
                        if ($result) {
                            $response['success'] = true;
                            $response['message'] = 'Actif enregistré avec succès (ID: ' . $result . ')';
                            $response['data'] = $result;
                            unset($response['error']);
                        } else {
                            $response['error'] = 'Échec de l\'enregistrement de l\'actif';
                        }
                    } else {
                        $response['error'] = 'Méthode non valide';
                    }
                    break;
                    
                case 'update_asset':
                    // Update an existing asset
                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_REQUEST['asset_id'])) {
                        $result = $assetController->updateAsset($_POST);
                        if ($result) {
                            $response['success'] = true;
                            $response['message'] = 'Actif mis à jour avec succès';
                            $response['data'] = $result;
                            unset($response['error']);
                        } else {
                            $response['error'] = 'Échec de la mise à jour de l\'actif';
                        }
                    } else {
                        $response['error'] = 'Méthode non valide ou ID manquant';
                    }
                    break;
                    
                case 'delete_asset':
                    // Delete an asset
                    if (isset($_REQUEST['asset_id'])) {
                        // Log the asset ID we're trying to delete
                        error_log('Attempting to delete asset ID: ' . $_REQUEST['asset_id']);
                        
                        $result = $assetController->deleteAsset($_REQUEST['asset_id']);
                        if ($result) {
                            $response['success'] = true;
                            $response['message'] = 'Actif supprimé avec succès';
                            unset($response['error']);
                        } else {
                            $response['error'] = 'Échec de la suppression de l\'actif';
                        }
                    } else {
                        $response['error'] = 'ID d\'actif manquant';
                    }
                    break;
                    
                case 'get_categories':
                    // Get all asset categories
                    $response['data'] = $assetController->getCategories();
                    $response['success'] = true;
                    unset($response['error']);
                    break;
                    
                default:
                    $response['error'] = 'Action non reconnue';
                    break;
            }
        } else {
            // If no action is specified, return an error (like income-expense)
            $response['error'] = 'No action specified';
        }
    } catch (Exception $e) {
        // Log the error and provide a generic message (like income-expense)
        error_log('AJAX Error: ' . $e->getMessage());
        $response['error'] = 'Une erreur est survenue lors du traitement de la demande.';
    }
} else {
    // User not logged in (like income-expense)
    $response['error'] = 'Authentication required';
}

// Set appropriate headers (like income-expense)
header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');

// Output the JSON response (like income-expense)
echo json_encode($response);

// End output buffering and send the response (like income-expense)
ob_end_flush();
?>
