<?php
// Prevent any notices or warnings from being output
error_reporting(E_ERROR);
ini_set('display_errors', 0);

// Start output buffering to prevent any unwanted output
ob_start();

// Include main configuration files
require_once $_SERVER['DOCUMENT_ROOT'] . '/Configurations_bdd.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Configurations.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Configurations_modules.php';

// Include any needed functions
$dir_fonction = $_SERVER['DOCUMENT_ROOT'] . '/';
require_once $_SERVER['DOCUMENT_ROOT'] . '/function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php';

// Default response
$response = [
    'success' => false,
    'error' => 'Default error state',
    'message' => '',
    'data' => null
];

// Only proceed if the user is logged in
try {
    if (isset($user) || !empty($_SESSION['4M8e7M5b1R2e8s'])) {
        // Clear any previous output that might have occurred during includes
        ob_clean();

        // Include necessary controller and model files
        require_once __DIR__ . '/../../controllers/AssetController.php';
        require_once __DIR__ . '/../../models/Asset.php';

        // Initialize the controller
        $assetController = new AssetController();

        // Process AJAX requests based on action parameter
        if (isset($_GET['action']) || isset($_POST['action'])) {
            $action = isset($_GET['action']) ? $_GET['action'] : $_POST['action'];

            switch ($action) {
                case 'get_assets_list':
                    // Get all assets for DataTable
                    $assets = $assetController->getAssets();

                    // Get categories to merge with asset data
                    $categories = $assetController->getCategories();
                    $categoriesMap = [];

                    // Create a map of category IDs to names
                    foreach ($categories as $category) {
                        $categoriesMap[$category['id']] = $category['name'];
                    }

                    // Add category name to each asset
                    foreach ($assets as &$asset) {
                        $asset['category_name'] = isset($categoriesMap[$asset['category_id']])
                            ? $categoriesMap[$asset['category_id']]
                            : 'Non catégorisé';
                    }

                    $response['data'] = $assets;
                    $response['success'] = true;
                    unset($response['error']);
                    break;

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
                            // Map database field names to UI field names
                            $response['success'] = true;
                            $response['id'] = $asset['id'];
                            $response['name'] = $asset['name'];
                            $response['category_id'] = $asset['category_id'];

                            // Map the mismatched field names from database to UI
                            $response['acquisition_date'] = $asset['purchase_date'];
                            $response['acquisition_value'] = $asset['purchase_value'];
                            $response['valuation_date'] = $asset['last_valuation_date'];

                            // These fields match the database column names
                            $response['current_value'] = $asset['current_value'];
                            $response['location'] = $asset['location'] ?? '';
                            $response['notes'] = $asset['notes'] ?? '';

                            // Also include categories for proper dropdown population
                            $response['categories'] = $assetController->getCategories();

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
                            $response['message'] = 'Actif enregistré avec succès';
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
            // If no action is specified, return an error
            $response['error'] = 'No action specified';
        }
    } else {
        // If user is not logged in, return an error
        $response['error'] = 'User not authenticated';
    }
} catch (Exception $e) {
    $response['error'] = 'Exception: ' . $e->getMessage();
}

// Set headers and output the JSON response
header('Content-Type: application/json');
echo json_encode($response);
