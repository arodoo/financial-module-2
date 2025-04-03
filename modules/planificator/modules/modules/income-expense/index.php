<?php
/**
 * Income/Expense Module Controller
 * 
 * Main controller file for the income and expense management module.
 * This file:
 * - Processes all form submissions (add/update/delete transactions)
 * - Loads transaction data from models using a fixed date range (current month)
 * - Sets up all variables needed by the view
 * - Manages flash messages
 * - Includes the view template
 * 
 * It requires authentication and implements AJAX request handling through ajax-handler.php.
 */

if (!empty($_SESSION['4M8e7M5b1R2e8s']) || !empty($user)) {
    ob_start();

    // Check for AJAX request before anything else
    $isAjaxRequest = isset($_GET['ajax']);
    if ($isAjaxRequest) {
        ob_end_clean();
        include __DIR__ . '/ajax-handler.php';
        exit;
    }


    ob_end_clean(); // Clear buffer but continue with normal page load

    require_once __DIR__ . '/../../models/Income.php';
    require_once __DIR__ . '/../../models/Expense.php';

    // Initialize models
    $incomeModel = new Income();
    $expenseModel = new Expense();

    // Set default date range to current month
    $today = new DateTime();
    $startDate = $today->format('Y-m-01');
    $endDate = $today->format('Y-m-t');

    // Initialize flash message variable
    $flashMessage = null;
    $flashType = null;

    // Initialize edit transaction data
    $editTransaction = null;
    $editType = null;

    // Process form submissions
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Add income
        if (isset($_POST['add_income'])) {
            $incomeModel->addIncome(
                $_POST['category_id'],
                $_POST['amount'],
                $_POST['description'],
                $_POST['transaction_date']
            );
            $flashMessage = "Revenu ajouté avec succès!";
            $flashType = "success";
        }

        // Add expense
        elseif (isset($_POST['add_expense'])) {
            $expenseModel->addExpense(
                $_POST['category_id'],
                $_POST['amount'],
                $_POST['description'],
                $_POST['transaction_date']
            );
            $flashMessage = "Dépense ajoutée avec succès!";
            $flashType = "success";
        }

        // Update income
        elseif (isset($_POST['update_income']) && isset($_POST['transaction_id'])) {
            $incomeModel->updateIncome(
                $_POST['transaction_id'],
                $_POST['category_id'],
                $_POST['amount'],
                $_POST['description'],
                $_POST['transaction_date']
            );
            $flashMessage = "Revenu mis à jour avec succès!";
            $flashType = "success";
        }

        // Update expense
        elseif (isset($_POST['update_expense']) && isset($_POST['transaction_id'])) {
            $expenseModel->updateExpense(
                $_POST['transaction_id'],
                $_POST['category_id'],
                $_POST['amount'],
                $_POST['description'],
                $_POST['transaction_date']
            );
            $flashMessage = "Dépense mise à jour avec succès!";
            $flashType = "success";
        }

        // Delete income
        elseif (isset($_POST['delete_income']) && isset($_POST['transaction_id'])) {
            $incomeModel->deleteIncome($_POST['transaction_id']);
            $flashMessage = "Revenu supprimé avec succès!";
            $flashType = "success";
        }

        // Delete expense
        elseif (isset($_POST['delete_expense']) && isset($_POST['transaction_id'])) {
            $expenseModel->deleteExpense($_POST['transaction_id']);
            $flashMessage = "Dépense supprimée avec succès!";
            $flashType = "success";
        }
    }

    // Check if there's a session flash message (from a previous redirect)
    elseif (isset($_SESSION['finance_flash_message'])) {
        if ($_SESSION['finance_flash_message'] === 'income_added') {
            $flashMessage = "Revenu ajouté avec succès!";
        } elseif ($_SESSION['finance_flash_message'] === 'expense_added') {
            $flashMessage = "Dépense ajoutée avec succès!";
        }
        $flashType = "success";
        unset($_SESSION['finance_flash_message']);
    }

    // Get data for the dashboard
    $incomeCategories = $incomeModel->getAllCategories();
    $expenseCategories = $expenseModel->getAllCategories();
    $incomeTransactions = $incomeModel->getIncomeTransactions($startDate, $endDate);
    $expenseTransactions = $expenseModel->getExpenseTransactions($startDate, $endDate);

    $totalIncome = $incomeModel->getTotalIncome($startDate, $endDate);
    $totalExpense = $expenseModel->getTotalExpense($startDate, $endDate);
    $netBalance = $totalIncome - $totalExpense;

    include 'view.php';
} else {
    header("location: /");
}
?>