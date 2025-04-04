<?php
/**
 * AJAX Handler for Fixed Items (Payments and Expenses)
 * 
 * Processes AJAX requests for payments and expenses
 */

// Start output buffering to prevent any unwanted output
ob_start();

// Include main configuration files
require_once $_SERVER['DOCUMENT_ROOT'] . '/Configurations_bdd.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Configurations.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Configurations_modules.php';

// Include any needed functions
$dir_fonction = $_SERVER['DOCUMENT_ROOT'] . '/';
require_once $_SERVER['DOCUMENT_ROOT'] . '/function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php';

// Default response structure
$response = [
    'success' => false,
    'error' => 'Default error state',
    'message' => '',
    'data' => null
];

// Only proceed if the user is logged in
if (isset($user) || !empty($_SESSION['4M8e7M5b1R2e8s'])) {
    // Clear any previous output that might have occurred during includes
    ob_clean();

    // Start session if not already started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Define base path to avoid path issues
    define('BASE_PATH', dirname(dirname(dirname(__FILE__))));

    // Error handler for AJAX requests
    function ajaxError($message, $code = 400)
    {
        http_response_code($code);
        echo json_encode(['success' => false, 'error' => $message]);
        exit;
    }

    // Try to include necessary files with proper error handling
    try {
        // Include necessary configuration files
        if (!file_exists(BASE_PATH . '/config/config.php')) {
            ajaxError('Configuration file not found', 500);
        }
        require_once BASE_PATH . '/config/config.php';

        // Fix: Use database.php instead of db.php to match the model includes
        if (!file_exists(BASE_PATH . '/config/database.php')) {
            ajaxError('Database configuration file not found: ' . BASE_PATH . '/config/database.php', 500);
        }
        require_once BASE_PATH . '/config/database.php';

        if (!file_exists(BASE_PATH . '/controllers/FixedItemController.php')) {
            ajaxError('Controller file not found', 500);
        }
        require_once BASE_PATH . '/controllers/FixedItemController.php';
    } catch (Exception $e) {
        ajaxError('Failed to load required files: ' . $e->getMessage(), 500);
    }

    // Set headers for JSON response
    header('Content-Type: application/json');

    // Get the action from the request
    $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

    // Process based on action
    if (empty($action)) {
        ajaxError('No action specified');
    }

    // Determine type based on action prefix and explicit type parameter
    $type = isset($_REQUEST['type']) ? $_REQUEST['type'] : 'payment';
    
    // Override type based on action if needed
    if (strpos($action, 'payment') !== false || strpos($action, 'income') !== false) {
        $type = 'payment';
    } elseif (strpos($action, 'expense') !== false || strpos($action, 'depense') !== false) {
        $type = 'expense';
    }

    // Verify we have a valid database connection via getDbConnection function
    try {
        $conn = getDbConnection();
        if (!$conn) {
            ajaxError('Database connection is not available', 500);
        }
    } catch (Exception $e) {
        ajaxError('Database connection error: ' . $e->getMessage(), 500);
    }

    // Instantiate the appropriate controller
    try {
        $controller = new FixedItemController($type);
    } catch (Exception $e) {
        ajaxError("Failed to initialize controller: " . $e->getMessage(), 500);
    }

    // Handle different actions
    try {
        switch ($action) {
            case 'get_payment':
            case 'get_expense':
                $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
                if (!$id) {
                    ajaxError('Invalid ID');
                }

                $item = $controller->getItemById($id);
                if (!$item) {
                    ajaxError('Item not found', 404);
                }

                echo json_encode(['success' => true, 'item' => $item]);
                break;

            // New action to get all payments or expenses for DataTable
            case 'get_payments_list':
            case 'get_expenses_list':
                $items = $controller->getItems();
                
                // Add category names to each item for easier display
                $categories = $controller->getCategories();
                $categoryMap = [];
                foreach ($categories as $cat) {
                    $categoryMap[$cat['id']] = $cat['name'];
                }
                
                foreach ($items as &$item) {
                    $item['category_name'] = isset($categoryMap[$item['category_id']]) ? 
                                            $categoryMap[$item['category_id']] : 'Non catégorisé';
                }
                
                echo json_encode([
                    'success' => true,
                    'items' => $items
                ]);
                break;

            case 'save_payment':
            case 'save_expense':
                if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                    ajaxError('Invalid request method');
                }

                $data = $_POST;
                $result = $controller->saveItem($data);

                if (!$result) {
                    ajaxError('Failed to save item');
                }

                echo json_encode([
                    'success' => true,
                    'message' => ($type === 'payment' ? 'Revenu' : 'Dépense') . ' enregistré(e) avec succès',
                    'id' => $result
                ]);
                break;

            case 'update_payment':
            case 'update_expense':
                if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                    ajaxError('Invalid request method');
                }

                $data = $_POST;
                $idField = $type === 'payment' ? 'payment_id' : 'expense_id';

                if (empty($data[$idField]) && empty($data['item_id'])) {
                    ajaxError('No item ID provided');
                }

                // For backwards compatibility
                if (!empty($data['item_id']) && empty($data[$idField])) {
                    $data[$idField] = $data['item_id'];
                }

                $result = $controller->updateItem($data);

                if (!$result) {
                    ajaxError('Failed to update item');
                }

                echo json_encode([
                    'success' => true,
                    'message' => 'Item updated successfully'
                ]);
                break;

            case 'delete_payment':
            case 'delete_expense':
                if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                    ajaxError('Invalid request method');
                }

                $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
                if (!$id) {
                    ajaxError('Invalid ID');
                }

                $result = $controller->deleteItem($id);

                if (!$result) {
                    ajaxError('Failed to delete item');
                }

                echo json_encode([
                    'success' => true,
                    'message' => 'Item deleted successfully'
                ]);
                break;

            case 'get_categories':
                $categories = $controller->getCategories();
                echo json_encode(['success' => true, 'categories' => $categories]);
                break;

            default:
                ajaxError('Unknown action: ' . $action);
        }
    } catch (Exception $e) {
        ajaxError('Error processing request: ' . $e->getMessage(), 500);
    }
} else {
    // Not authenticated
    header('HTTP/1.1 401 Unauthorized');
    echo json_encode([
        'success' => false,
        'error' => 'Authentication required',
        'message' => 'You must be logged in to perform this action'
    ]);
}
?>