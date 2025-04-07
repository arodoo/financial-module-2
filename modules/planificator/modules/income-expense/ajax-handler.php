<?php
// Start output buffering to prevent any unwanted output
ob_start();

// Include main configuration files - this is key
require_once $_SERVER['DOCUMENT_ROOT'] . '/Configurations_bdd.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Configurations.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Configurations_modules.php';

// Include any needed functions
$dir_fonction = $_SERVER['DOCUMENT_ROOT'] . '/';
require_once $_SERVER['DOCUMENT_ROOT'] . '/function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php';

// Now we should have access to the $id_oo variable from the included Configurations.php

// Only proceed if the user is logged in (like in the Notifications file)
if (isset($user)) {
    // Clear any previous output that might have occurred during includes
    ob_clean();
    
    // Require necessary model files
    require_once __DIR__ . '/../../models/Income.php';
    require_once __DIR__ . '/../../models/Expense.php';
    
    // Initialize models
    $incomeModel = new Income();
    $expenseModel = new Expense();
    
    // Set appropriate headers
    header('Content-Type: application/json');
    header('Cache-Control: no-cache, must-revalidate');

    // Handle GET requests (reading data)
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
        $response = ['success' => false, 'error' => 'Invalid action'];
        
        // Get income transaction
        if ($_GET['action'] === 'get_income_transaction' && isset($_GET['id'])) {
            $transaction = $incomeModel->getTransactionById($_GET['id']);
            if ($transaction) {
                $response = $transaction;
                $response['success'] = true;
            } else {
                $response['error'] = 'Income transaction not found';
            }
        } 
        // Get expense transaction
        elseif ($_GET['action'] === 'get_expense_transaction' && isset($_GET['id'])) {
            $transaction = $expenseModel->getTransactionById($_GET['id']);
            if ($transaction) {
                $response = $transaction;
                $response['success'] = true;
            } else {
                $response['error'] = 'Expense transaction not found';
            }
        }
        // Get all income transactions
        elseif ($_GET['action'] === 'get_income_list') {
            $startDate = $_GET['start_date'] ?? date('Y-m-01');
            $endDate = $_GET['end_date'] ?? date('Y-m-t');
            $transactions = $incomeModel->getIncomeTransactions($startDate, $endDate);
            $response = [
                'success' => true,
                'data' => $transactions
            ];
        }
        // Get all expense transactions
        elseif ($_GET['action'] === 'get_expense_list') {
            $startDate = $_GET['start_date'] ?? date('Y-m-01');
            $endDate = $_GET['end_date'] ?? date('Y-m-t');
            $transactions = $expenseModel->getExpenseTransactions($startDate, $endDate);
            $response = [
                'success' => true,
                'data' => $transactions
            ];
        }
        
        // Output the JSON response
        echo json_encode($response);
    } 
    // Handle POST requests (creating, updating, deleting)
    elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
        $response = ['success' => false, 'error' => 'Invalid action'];
        $data = $_POST;
        
        // Add income transaction
        if ($data['action'] === 'add_income') {
            $result = $incomeModel->addIncome(
                $data['category_id'],
                $data['amount'],
                $data['description'] ?? '',
                $data['transaction_date']
            );
            
            if ($result) {
                $response = [
                    'success' => true,
                    'message' => 'Revenu ajouté avec succès!'
                ];
            } else {
                $response['error'] = 'Erreur lors de l\'ajout du revenu';
            }
        }
        
        // Add expense transaction
        elseif ($data['action'] === 'add_expense') {
            $result = $expenseModel->addExpense(
                $data['category_id'],
                $data['amount'],
                $data['description'] ?? '',
                $data['transaction_date']
            );
            
            if ($result) {
                $response = [
                    'success' => true,
                    'message' => 'Dépense ajoutée avec succès!'
                ];
            } else {
                $response['error'] = 'Erreur lors de l\'ajout de la dépense';
            }
        }
        
        // Update income transaction
        elseif ($data['action'] === 'update_income') {
            $result = $incomeModel->updateIncome(
                $data['transaction_id'],
                $data['category_id'],
                $data['amount'],
                $data['description'] ?? '',
                $data['transaction_date']
            );
            
            if ($result) {
                $response = [
                    'success' => true,
                    'message' => 'Revenu mis à jour avec succès!'
                ];
            } else {
                $response['error'] = 'Erreur lors de la mise à jour du revenu';
            }
        }
        
        // Update expense transaction
        elseif ($data['action'] === 'update_expense') {
            $result = $expenseModel->updateExpense(
                $data['transaction_id'],
                $data['category_id'],
                $data['amount'],
                $data['description'] ?? '',
                $data['transaction_date']
            );
            
            if ($result) {
                $response = [
                    'success' => true,
                    'message' => 'Dépense mise à jour avec succès!'
                ];
            } else {
                $response['error'] = 'Erreur lors de la mise à jour de la dépense';
            }
        }
        
        // Delete income transaction
        elseif ($data['action'] === 'delete_income') {
            $result = $incomeModel->deleteIncome($data['transaction_id']);
            
            if ($result) {
                $response = [
                    'success' => true,
                    'message' => 'Revenu supprimé avec succès!'
                ];
            } else {
                $response['error'] = 'Erreur lors de la suppression du revenu';
            }
        }
        
        // Delete expense transaction
        elseif ($data['action'] === 'delete_expense') {
            $result = $expenseModel->deleteExpense($data['transaction_id']);
            
            if ($result) {
                $response = [
                    'success' => true,
                    'message' => 'Dépense supprimée avec succès!'
                ];
            } else {
                $response['error'] = 'Erreur lors de la suppression de la dépense';
            }
        }
        
        // Get summary data for updating the UI
        if ($response['success']) {
            $startDate = $data['start_date'] ?? date('Y-m-01');
            $endDate = $data['end_date'] ?? date('Y-m-t');
            
            $totalIncome = $incomeModel->getTotalIncome($startDate, $endDate);
            $totalExpense = $expenseModel->getTotalExpense($startDate, $endDate);
            $netBalance = $totalIncome - $totalExpense;
            
            $response['summary'] = [
                'totalIncome' => $totalIncome,
                'totalExpense' => $totalExpense,
                'netBalance' => $netBalance
            ];
        }
        
        // Output the JSON response
        echo json_encode($response);
    }
    else {
        // If no action is specified, return an error
        echo json_encode(['success' => false, 'error' => 'No action specified']);
    }
} else {
    // User not logged in or session expired
    echo json_encode(['success' => false, 'error' => 'Authentication required']);
}

// End output buffering and send the response
ob_end_flush();
?>