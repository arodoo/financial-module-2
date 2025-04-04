<?php
/**
 * Financial Projection Module - Main entry point
 */

// Start output buffering immediately to capture any unwanted output
ob_start();

// ----- AJAX REQUEST DETECTION - IMPROVED -----
// Check for AJAX request using multiple methods
$headers = function_exists('getallheaders') ? getallheaders() : array();
$isAjaxRequest = (
    isset($_GET['ajax']) || 
    (isset($headers['X-Requested-With']) && $headers['X-Requested-With'] == 'XMLHttpRequest') ||
    (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')
);

if ($isAjaxRequest) {
    ob_end_clean(); // Discard buffered content for AJAX responses
    include __DIR__ . '/ajax-handler.php';
    exit;
}

// Include dependencies
require_once __DIR__ . '/../../controllers/FinancialProjectionController.php';

// Initialize controller
$projectionController = new FinancialProjectionController();

// Get initial data for the view
$viewData = $projectionController->getViewData();

// Options for projection configuration
$yearOptions = $projectionController->getYearOptions();
$viewModeOptions = $projectionController->getViewModeOptions();

// Include the main projection page template
include __DIR__ . '/projection-index.php';
?>