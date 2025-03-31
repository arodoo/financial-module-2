<?php
/**
 * Financial Projection Module - Main entry point
 */

// Enhanced debugging settings
error_reporting(E_ALL);
ini_set('display_errors', 1);
define('DEBUG_MODE', true);

// Start logging immediately
error_log("====== FINANCIAL PROJECTION INDEX.PHP LOADED ======");
error_log("Request URI: " . $_SERVER['REQUEST_URI']);
error_log("REQUEST params: " . json_encode($_REQUEST));
error_log("POST data: " . json_encode($_POST));
error_log("HTTP Headers: " . json_encode(getallheaders()));

// Debug function for convenience
function debug_log($message, $data = null) {
    if (DEBUG_MODE) {
        $log = "[DEBUG] " . $message;
        if ($data !== null) {
            $log .= ": " . (is_array($data) || is_object($data) ? json_encode($data) : $data);
        }
        error_log($log);
    }
}

debug_log("Script execution started");

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

debug_log("Is AJAX request?", $isAjaxRequest ? "YES" : "NO");
debug_log("Action requested:", $_REQUEST['action'] ?? 'none');
debug_log("Full URL:", (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");

if ($isAjaxRequest) {
    debug_log("Routing to AJAX handler");
    ob_end_clean(); // Discard buffered content for AJAX responses
    include __DIR__ . '/ajax-handler.php';
    exit;
}

// Include dependencies
debug_log("Loading dependencies");
require_once __DIR__ . '/../../controllers/FinancialProjectionController.php';

// Initialize controller
debug_log("Initializing controller");
$projectionController = new FinancialProjectionController();

// Get initial data for the view
debug_log("Getting view data");
$viewData = $projectionController->getViewData();

// Options for projection configuration
$yearOptions = $projectionController->getYearOptions();
$viewModeOptions = $projectionController->getViewModeOptions();

// Include the main projection page template
debug_log("Loading projection template");
include __DIR__ . '/projection-index.php';
debug_log("Script execution completed");
?>