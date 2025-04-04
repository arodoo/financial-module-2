<?php
/**
 * AJAX Handler for Fixed Items (Payments and Expenses)
 * 
 * Processes AJAX requests for payments and expenses
 */
 
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include necessary files
require_once dirname(dirname(dirname(__FILE__))) . '/config/config.php';
require_once dirname(dirname(dirname(__FILE__))) . '/controllers/FixedItemController.php';

// Set headers for JSON response
header('Content-Type: application/json');

// Error handler for AJAX requests
function ajaxError($message, $code = 400) {
    http_response_code($code);
    echo json_encode(['success' => false, 'error' => $message]);
    exit;
}

// Get the action from the request
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

// Process based on action
if (empty($action)) {
    ajaxError('No action specified');
}

// Determine type based on action prefix (get_payment, add_expense, etc.)
$type = 'payment'; // Default
if (strpos($action, 'payment') !== false || strpos($action, 'income') !== false) {
    $type = 'payment';
} elseif (strpos($action, 'expense') !== false || strpos($action, 'depense') !== false) {
    $type = 'expense';
} elseif (isset($_REQUEST['type'])) {
    // Explicit type parameter takes precedence
    $type = $_REQUEST['type'];
}

// Instantiate the appropriate controller
$controller = new FixedItemController($type);

// Handle different actions
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
            'message' => 'Item saved successfully', 
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
?>
