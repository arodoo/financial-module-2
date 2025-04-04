<?php
/**
 * Fixed Expense Controller
 * Handles business logic for fixed expenses operations
 */
class FixedDepenseController {
    private $model;
    private $currentUser;
    
    public function __construct() {
        global $id_oo;
        require_once __DIR__ . '/../models/FixedDepense.php';
        $this->model = new FixedDepense();
        $this->currentUser = ['id' => $id_oo];
    }
    
    /**
     * Get all data required for the view
     * @return array View data including expenses and categories
     */
    public function getViewData() {
        $data = [
            'expenses' => $this->getDepenses($this->currentUser['id']),
            'categories' => $this->getCategories()
        ];
        
        // Check if a specific expense is requested to view
        if (isset($_GET['view_expense'])) {
            $expenseId = (int)$_GET['view_expense'];
            $expense = $this->getDepenseById($expenseId);
            
            if ($expense) {
                $data['viewExpense'] = $expense;
            }
        }
        
        // Check if a specific expense is requested to edit
        if (isset($_GET['edit_expense'])) {
            $expenseId = (int)$_GET['edit_expense'];
            $expense = $this->getDepenseById($expenseId);
            
            if ($expense) {
                $data['editExpense'] = $expense;
            }
        }
        
        return $data;
    }
    
    /**
     * Get all expenses for a user
     * @param int $membre_id User ID
     * @return array Expenses data
     */
    public function getDepenses($membre_id = null) {
        if (!$membre_id) {
            $membre_id = $this->currentUser['id'];
        }
        
        if (!$membre_id) return [];
        
        return $this->model->getAllExpenses($membre_id);
    }
    
    /**
     * Get all expenses for a user (alias for getDepenses for compatibility with shared templates)
     * @param int $membre_id User ID
     * @return array Expenses data
     */
    public function getItems($membre_id = null) {
        return $this->getDepenses($membre_id);
    }
    
    /**
     * Get a specific expense by ID
     * @param int $expense_id Expense ID
     * @return array|false Expense data or false if not found
     */
    public function getDepenseById($expense_id) {
        if (!$this->currentUser['id']) return false;
        
        return $this->model->getExpenseById($expense_id);
    }
    
    /**
     * Add a new fixed expense
     * @param array $data Expense data
     * @return int|false New expense ID or false on failure
     */
    public function saveExpense($data) {
        if (!$this->currentUser['id']) return false;
        
        // Debug log input data
        error_log("FixedDepenseController saveExpense input data: " . print_r($data, true));
        
        // Data validation
        if (empty($data['name']) || empty($data['category_id']) || empty($data['amount'])) {
            error_log("Required expense fields missing");
            return false;
        }
        
        // Ensure membre_id is set
        $data['membre_id'] = $this->currentUser['id'];
        
        // Clean and format amount - handle both comma and dot as decimal separators
        $data['amount'] = str_replace(' ', '', $data['amount']);
        $data['amount'] = str_replace(',', '.', $data['amount']);
        $data['amount'] = preg_replace('/[^0-9.]/', '', $data['amount']);
        
        // Always set EUR as the currency
        $data['currency'] = 'EUR';
        
        // Default dates if not provided
        if (empty($data['start_date'])) {
            $data['start_date'] = date('Y-m-d');
        }
        
        try {
            $result = $this->model->addExpense($data);
            error_log("Expense save result: " . ($result ? "Success with ID: $result" : "Failed"));
            return $result;
        } catch (Exception $e) {
            error_log("Exception in FixedDepenseController::saveExpense: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update an existing expense
     * @param array $data Updated expense data
     * @return bool Success/failure
     */
    public function updateExpense($data) {
        if (!$this->currentUser['id']) return false;
        
        // Debug log input data
        error_log("FixedDepenseController updateExpense input data: " . print_r($data, true));
        
        // Ensure we have an expense ID
        if (empty($data['expense_id'])) {
            error_log("Expense ID missing in update");
            return false;
        }
        
        // Handle both field name formats for backward compatibility
        $name = isset($data['name']) ? $data['name'] : (isset($data['expense_name']) ? $data['expense_name'] : null);
        
        // Data validation
        if (empty($name) || empty($data['category_id']) || empty($data['amount'])) {
            error_log("Required expense fields missing");
            return false;
        }
        
        // Set the name field for consistency
        $data['name'] = $name;
        
        // Clean and format amount - handle both comma and dot as decimal separators
        $data['amount'] = str_replace(' ', '', $data['amount']);
        $data['amount'] = str_replace(',', '.', $data['amount']);
        $data['amount'] = preg_replace('/[^0-9.]/', '', $data['amount']);
        
        // Always set EUR as the currency
        $data['currency'] = 'EUR';
        
        try {
            return $this->model->updateExpense($data);
        } catch (Exception $e) {
            error_log("Exception in FixedDepenseController::updateExpense: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete an expense
     * @param int $expense_id Expense ID to delete
     * @return bool Success/failure
     */
    public function deleteExpense($expense_id) {
        if (!$this->currentUser['id'] || !$expense_id) {
            error_log("Cannot delete expense: User ID or expense ID missing");
            return false;
        }
        
        try {
            return $this->model->deleteExpense($expense_id);
        } catch (Exception $e) {
            error_log("Exception in FixedDepenseController::deleteExpense: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get all expense categories
     * @return array Categories data
     */
    public function getCategories() {
        return $this->model->getCategories();
    }
    
    /**
     * Get available frequency options
     * @return array Frequency options
     */
    public function getFrequencyOptions() {
        return [
            'monthly' => 'Mensuel',
            'quarterly' => 'Trimestriel',
            'biannual' => 'Semestriel',
            'annual' => 'Annuel'
        ];
    }
    
    /**
     * Get expense status options
     * @return array Status options
     */
    public function getStatusOptions() {
        return [
            'active' => 'Actif',
            'inactive' => 'Inactif',
            'cancelled' => 'Annulé'
        ];
    }
    
    /**
     * Get total active expense amount
     * @return float Total expense amount
     */
    public function getTotalExpenseAmount() {
        if (!$this->currentUser['id']) return 0;
        
        return $this->model->getTotalExpenseAmount($this->currentUser['id']);
    }
    
    /**
     * Get expenses by category for reporting
     * @return array Expense statistics by category
     */
    public function getExpensesByCategory() {
        if (!$this->currentUser['id']) return [];
        
        return $this->model->getExpensesByCategory($this->currentUser['id']);
    }
}
?>