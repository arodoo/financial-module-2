<?php
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
    
    try {
        // Include necessary controller and model files
        require_once __DIR__ . '/../../controllers/FixedPaymentController.php';
        require_once __DIR__ . '/../../models/FixedPayment.php';
        require_once __DIR__ . '/../../controllers/FixedDepenseController.php';
        require_once __DIR__ . '/../../models/FixedDepense.php';

        // Initialize the controllers
        $paymentController = new FixedPaymentController();
        $depenseController = new FixedDepenseController();
        
        // Process AJAX requests based on action parameter
        if (isset($_GET['action'])) {
            $action = $_GET['action'];
            
            // Actions for payments/incomes
            if (strpos($action, 'payment') !== false || strpos($action, 'income') !== false) {
                switch ($action) {
                    case 'get_payments':
                        $response['data'] = $paymentController->getPayments();
                        $response['success'] = true;
                        unset($response['error']);
                        break;
                        
                    case 'get_payment':
                        if (isset($_GET['payment_id'])) {
                            $payment = $paymentController->getPaymentById($_GET['payment_id']);
                            if ($payment) {
                                $response = array_merge($response, $payment);
                                $response['success'] = true;
                                unset($response['error']);
                            } else {
                                $response['error'] = 'Paiement non trouvé';
                            }
                        } else {
                            $response['error'] = 'ID de paiement manquant';
                        }
                        break;
                        
                    case 'save_payment':
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            error_log('Incoming save payment data: ' . print_r($_POST, true));
                            
                            if (isset($_POST['payment_name']) && !isset($_POST['name'])) {
                                $_POST['name'] = $_POST['payment_name'];
                            }
                            
                            if (!isset($_POST['name']) || !isset($_POST['category_id']) || !isset($_POST['amount'])) {
                                $response['error'] = 'Champs requis manquants';
                                break;
                            }
                            
                            $result = $paymentController->savePayment($_POST);
                            if ($result) {
                                $response['success'] = true;
                                $response['message'] = 'Paiement enregistré avec succès (ID: ' . $result . ')';
                                $response['data'] = $result;
                                unset($response['error']);
                            } else {
                                $response['error'] = 'Échec de l\'enregistrement du paiement';
                            }
                        } else {
                            $response['error'] = 'Méthode non valide';
                        }
                        break;
                        
                    case 'update_payment':
                        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_REQUEST['payment_id'])) {
                            $result = $paymentController->updatePayment($_POST);
                            if ($result) {
                                $response['success'] = true;
                                $response['message'] = 'Paiement mis à jour avec succès';
                                unset($response['error']);
                            } else {
                                $response['error'] = 'Échec de la mise à jour du paiement';
                            }
                        } else {
                            $response['error'] = 'Méthode non valide ou ID manquant';
                        }
                        break;
                        
                    case 'delete_payment':
                        if (isset($_REQUEST['payment_id'])) {
                            $result = $paymentController->deletePayment($_REQUEST['payment_id']);
                            if ($result) {
                                $response['success'] = true;
                                $response['message'] = 'Paiement supprimé avec succès';
                                unset($response['error']);
                            } else {
                                $response['error'] = 'Échec de la suppression du paiement';
                            }
                        } else {
                            $response['error'] = 'ID de paiement manquant';
                        }
                        break;
                        
                    case 'get_categories':
                        $response['data'] = $paymentController->getCategories();
                        $response['success'] = true;
                        unset($response['error']);
                        break;
                        
                    default:
                        $response['error'] = 'Action non reconnue';
                        break;
                }
            }
            
            // Actions for expenses
            elseif (strpos($action, 'depense') !== false || strpos($action, 'expense') !== false) {
                switch ($action) {
                    case 'get_depenses':
                        $response['data'] = $depenseController->getDepenses();
                        $response['success'] = true;
                        unset($response['error']);
                        break;
                        
                    case 'get_depense':
                        if (isset($_GET['depense_id'])) {
                            $depense = $depenseController->getDepenseById($_GET['depense_id']);
                            if ($depense) {
                                $response = array_merge($response, $depense);
                                $response['success'] = true;
                                unset($response['error']);
                            } else {
                                $response['error'] = 'Dépense non trouvée';
                            }
                        } else {
                            $response['error'] = 'ID de dépense manquant';
                        }
                        break;
                        
                    case 'save_depense':
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            error_log('Incoming save depense data: ' . print_r($_POST, true));
                            
                            if (isset($_POST['depense_name']) && !isset($_POST['name'])) {
                                $_POST['name'] = $_POST['depense_name'];
                            }
                            
                            if (!isset($_POST['name']) || !isset($_POST['category_id']) || !isset($_POST['amount'])) {
                                $response['error'] = 'Champs requis manquants';
                                break;
                            }
                            
                            $result = $depenseController->saveDepense($_POST);
                            if ($result) {
                                $response['success'] = true;
                                $response['message'] = 'Dépense enregistrée avec succès (ID: ' . $result . ')';
                                $response['data'] = $result;
                                unset($response['error']);
                            } else {
                                $response['error'] = 'Échec de l\'enregistrement de la dépense';
                            }
                        } else {
                            $response['error'] = 'Méthode non valide';
                        }
                        break;
                        
                    case 'update_depense':
                        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_REQUEST['depense_id'])) {
                            $result = $depenseController->updateDepense($_POST);
                            if ($result) {
                                $response['success'] = true;
                                $response['message'] = 'Dépense mise à jour avec succès';
                                unset($response['error']);
                            } else {
                                $response['error'] = 'Échec de la mise à jour de la dépense';
                            }
                        } else {
                            $response['error'] = 'Méthode non valide ou ID manquant';
                        }
                        break;
                        
                    case 'delete_depense':
                        if (isset($_REQUEST['depense_id'])) {
                            $result = $depenseController->deleteDepense($_REQUEST['depense_id']);
                            if ($result) {
                                $response['success'] = true;
                                $response['message'] = 'Dépense supprimée avec succès';
                                unset($response['error']);
                            } else {
                                $response['error'] = 'Échec de la suppression de la dépense';
                            }
                        } else {
                            $response['error'] = 'ID de dépense manquant';
                        }
                        break;
                        
                    case 'get_categories':
                        $response['data'] = $depenseController->getCategories();
                        $response['success'] = true;
                        unset($response['error']);
                        break;
                        
                    default:
                        $response['error'] = 'Action non reconnue';
                        break;
                }
            }
            
            else {
                $response['error'] = 'Action non reconnue';
            }
        } elseif (isset($_GET['get_expenses'])) {
            // Get expenses data
            $expenseData = $depenseController->getViewData();
            $expenses = $expenseData['expenses'] ?? [];
            $expenseCategories = $expenseData['categories'] ?? [];
            
            // Include only the HTML content for the expenses table
            ob_start();
            $categories = $expenseCategories;
            include __DIR__ . '/list-expenses.php';
            $html = ob_get_clean();
            
            echo $html;
            exit;
        } else {
            $response['error'] = 'Aucune action spécifiée';
        }
    } catch (Exception $e) {
        error_log('AJAX Error: ' . $e->getMessage());
        $response['error'] = 'Une erreur est survenue lors du traitement de la demande.';
    }
} else {
    $response['error'] = 'Authentication requise';
}

// Set appropriate headers
header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');

// Output the JSON response
echo json_encode($response);

// End output buffering and send the response
ob_end_flush();
?>
