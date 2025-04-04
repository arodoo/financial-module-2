<?php
/**
 * Shared Item Handler for Payments and Expenses
 * 
 * This file handles the logic for both payments and expenses by using a
 * parameterized approach to differentiate between the two types.
 */

// Ensure this file is included, not accessed directly
if (!defined('MODULE_LOADED')) {
    die('Direct access to this file is not allowed.');
}

// Determine the type (payment or expense)
$type = isset($_GET['type']) ? $_GET['type'] : 'payment';
if (!in_array($type, ['payment', 'expense'])) {
    die('Invalid type specified.');
}

// Set table and category names based on type
$tableName = $type === 'payment' ? 'paiements_fixes' : 'depenses_fixes';
$categoryTableName = $type === 'payment' ? 'paiement_categories' : 'depense_categories';
$itemLabel = $type === 'payment' ? 'Paiement' : 'Dépense';
$itemLabelPlural = $type === 'payment' ? 'Paiements' : 'Dépenses';

// Include the unified controller and instantiate with type
require_once __DIR__ . '/../../controllers/FixedItemController.php';
$controller = new FixedItemController($type);

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['save_item'])) {
        $controller->saveItem($_POST);
    } elseif (isset($_POST['update_item'])) {
        $controller->updateItem($_POST);
    } elseif (isset($_POST['delete_item'])) {
        $controller->deleteItem($_POST['item_id']);
    }
}

// Fetch data for the view
$items = $controller->getItems();
$categories = $controller->getCategories();
$frequencyOptions = $controller->getFrequencyOptions();
$statusOptions = $controller->getStatusOptions();