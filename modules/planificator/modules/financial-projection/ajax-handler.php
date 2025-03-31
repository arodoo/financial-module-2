<?php
// Prevent any output before headers
ob_start();

// Disable error display - log errors instead
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Explicitly set JSON content type
header('Content-Type: application/json');
header('Cache-Control: no-cache, no-store, must-revalidate');

// Initialize a clean response structure
$response = [
    'success' => false,
    'error' => null,
    'data' => null
];

try {
    error_log("AJAX handler executed: " . $_SERVER['REQUEST_URI']);
    
    // Load required files with error suppression
    @include_once $_SERVER['DOCUMENT_ROOT'] . '/Configurations_bdd.php';
    @include_once $_SERVER['DOCUMENT_ROOT'] . '/Configurations.php';
    @include_once $_SERVER['DOCUMENT_ROOT'] . '/Configurations_modules.php';
    
    // Minimum dependencies only - avoid loading problematic includes
    require_once $_SERVER['DOCUMENT_ROOT'] . '/modules/planificator/controllers/FinancialProjectionController.php';
    
    // Verify session (if needed)
    if (empty($_SESSION['4M8e7M5b1R2e8s'])) {
        error_log("AJAX: Session not authenticated");
        throw new Exception('Authentication required');
    }
    
    // Initialize controller
    $projectionController = new FinancialProjectionController();
    
    // Verify we have the required action
    if (empty($_REQUEST['action']) || $_REQUEST['action'] !== 'generate_projection') {
        throw new Exception('Invalid or missing action');
    }
    
    // Process the projection
    $params = $_POST;
    $projection = $projectionController->generateProjection($params);
    $summary = $projectionController->calculateSummary($projection);
    
    // Successful response
    $response = [
        'success' => true,
        'data' => [
            'projection' => $projection,
            'summary' => $summary
        ]
    ];
    
    error_log("AJAX: Generated projection with " . count($projection) . " periods");
    
} catch (Exception $e) {
    error_log("AJAX Error: " . $e->getMessage());
    $response['error'] = $e->getMessage();
}

// Clean any output before sending JSON
while (ob_get_level()) {
    ob_end_clean();
}

// Send response
echo json_encode($response, JSON_NUMERIC_CHECK);
exit;
?>
