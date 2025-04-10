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
    // Load required files with error suppression
    @include_once $_SERVER['DOCUMENT_ROOT'] . '/Configurations_bdd.php';
    @include_once $_SERVER['DOCUMENT_ROOT'] . '/Configurations.php';
    @include_once $_SERVER['DOCUMENT_ROOT'] . '/Configurations_modules.php';

    // Minimum dependencies only - avoid loading problematic includes
    require_once $_SERVER['DOCUMENT_ROOT'] . '/modules/planificator/controllers/FinancialProjectionController.php';

    // Verify session (if needed)
    if (empty($_SESSION['4M8e7M5b1R2e8s'])) {
        throw new Exception('Authentication required');
    }
    // Initialize controller
    $projectionController = new FinancialProjectionController();

    // Process based on action
    if (empty($_REQUEST['action'])) {
        throw new Exception('Missing action parameter');
    }

    // Handle different actions
    $action = $_REQUEST['action'];

    if ($action === 'generate_projection') {
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
    } else if ($action === 'get_balance_data') {
        try {
            // Get the model to access its methods
            require_once $_SERVER['DOCUMENT_ROOT'] . '/modules/planificator/models/FinancialProjection.php';

            $model = new FinancialProjection();
            $currentBalance = 0;
            $totalAssets = 0;

            // Try to get current balance with error trapping
            try {
                $currentBalance = $model->getCurrentBalance();
            } catch (Exception $balanceError) {
                $currentBalance = 0;
            }

            // Try to get total assets with error trapping
            try {
                $totalAssets = $model->getTotalAssets();
            } catch (Exception $assetsError) {
                $totalAssets = 0;
            }

            // Successful response - force numeric values
            $response = [
                'success' => true,
                'data' => [
                    'current_balance' => (float) $currentBalance,
                    'total_assets' => (float) $totalAssets
                ]
            ];
        } catch (Exception $e) {
            $response['error'] = "Error retrieving balance data";
            $response['data'] = ['current_balance' => 0, 'total_assets' => 0];
            $response['success'] = false;
        }
    } else {
        throw new Exception('Invalid action: ' . $action);
    }

} catch (Exception $e) {
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