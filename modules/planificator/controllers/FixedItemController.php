<?php
/**
 * Unified Fixed Item Controller
 * 
 * This controller handles the logic for both fixed payments and expenses
 * by using a parameterized approach to differentiate between the two types.
 */
class FixedItemController
{
    private $model;
    private $currentUser;
    private $type;
    private $tableName;
    private $categoryTable;

    /**
     * Constructor initializes controller with the specified item type
     * @param string $type Either 'payment' or 'expense'
     */
    public function __construct($type = 'payment')
    {
        global $id_oo;

        // Validate and set type
        $this->type = (in_array($type, ['payment', 'expense'])) ? $type : 'payment';

        // Set appropriate model and tables based on type
        if ($this->type === 'payment') {
            require_once __DIR__ . '/../models/FixedPayment.php';
            $this->model = new FixedPayment();
            $this->tableName = 'paiements_fixes';
            $this->categoryTable = 'paiement_categories';
        } else {
            require_once __DIR__ . '/../models/FixedDepense.php';
            $this->model = new FixedDepense();
            $this->tableName = 'depenses_fixes';
            $this->categoryTable = 'depense_categories';
        }

        // Set the appropriate category table in the model
        $this->model->setCategoryTable($this->categoryTable);

        // Set current user
        $this->currentUser = ['id' => $id_oo];
    }

    /**
     * Get all data required for the view
     * @return array View data including items and categories
     */
    public function getViewData()
    {
        $data = [
            'items' => $this->getItems($this->currentUser['id']),
            'categories' => $this->getCategories()
        ];

        // Check if a specific item is requested to view
        $viewParam = ($this->type === 'payment') ? 'view_payment' : 'view_expense';
        if (isset($_GET[$viewParam])) {
            $itemId = (int) $_GET[$viewParam];
            $item = $this->getItemById($itemId);

            if ($item) {
                $data[$this->type . 's'][] = $item; // Add to appropriate array
            }
        }

        // Check if a specific item is requested to edit
        $editParam = ($this->type === 'payment') ? 'edit_payment' : 'edit_expense';
        if (isset($_GET[$editParam])) {
            $itemId = (int) $_GET[$editParam];
            $item = $this->getItemById($itemId);

            if ($item) {
                $data['editItem'] = $item;
            }
        }

        return $data;
    }

    /**
     * Get all items for a user
     * @param int $membre_id User ID
     * @return array Items data
     */
    public function getItems($membre_id = null)
    {
        if (!$membre_id) {
            $membre_id = $this->currentUser['id'];
        }

        if (!$membre_id)
            return [];

        return $this->type === 'payment'
            ? $this->model->getAllPayments($membre_id)
            : $this->model->getAllExpenses($membre_id);
    }

    /**
     * Get a specific item by ID
     * @param int $id Item ID
     * @return array|false Item data or false if not found
     */
    public function getItemById($id)
    {
        if (!$this->currentUser['id'] || !$id)
            return false;

        return $this->type === 'payment'
            ? $this->model->getPaymentById($id)
            : $this->model->getExpenseById($id);
    }

    /**
     * Add a new item
     * @param array $data Item data
     * @return int|false New item ID or false on failure
     */
    public function saveItem($data)
    {
        if (!$this->currentUser['id'])
            return false;

        // Handle naming inconsistencies
        if (isset($data['payment_name']) && !isset($data['name'])) {
            $data['name'] = $data['payment_name'];
        }
        if (isset($data['expense_name']) && !isset($data['name'])) {
            $data['name'] = $data['expense_name'];
        }

        // Data validation
        if (empty($data['name']) || empty($data['category_id']) || empty($data['amount'])) {
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
            $result = $this->type === 'payment'
                ? $this->model->addPayment($data)
                : $this->model->addExpense($data);
            return $result;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Update an existing item
     * @param array $data Updated item data
     * @return bool Success/failure
     */
    public function updateItem($data)
    {
        if (!$this->currentUser['id'])
            return false;

        // Determine the ID field name
        $idField = $this->type === 'payment' ? 'payment_id' : 'expense_id';

        // Ensure we have an item ID
        if (empty($data[$idField]) && empty($data['item_id'])) {
            return false;
        }

        // Set ID field consistently if using generic item_id
        if (!empty($data['item_id']) && empty($data[$idField])) {
            $data[$idField] = $data['item_id'];
        }

        // Handle both field name formats for backward compatibility
        $name = isset($data['name']) ? $data['name'] :
            (isset($data[$this->type . '_name']) ? $data[$this->type . '_name'] : null);

        // Data validation
        if (empty($name) || empty($data['category_id']) || empty($data['amount'])) {
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
            return $this->type === 'payment'
                ? $this->model->updatePayment($data)
                : $this->model->updateExpense($data);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Delete an item
     * @param int $id Item ID to delete
     * @return bool Success/failure
     */
    public function deleteItem($id)
    {
        if (!$this->currentUser['id'] || !$id) {
            return false;
        }

        try {
            return $this->type === 'payment'
                ? $this->model->deletePayment($id)
                : $this->model->deleteExpense($id);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Get all item categories
     * @return array Categories data
     */
    public function getCategories()
    {
        return $this->model->getCategories();
    }

    /**
     * Get available frequency options
     * @return array Frequency options
     */
    public function getFrequencyOptions()
    {
        return [
            'monthly' => 'Mensuel',
            'quarterly' => 'Trimestriel',
            'biannual' => 'Semestriel',
            'annual' => 'Annuel'
        ];
    }

    /**
     * Get item status options
     * @return array Status options
     */
    public function getStatusOptions()
    {
        return [
            'active' => 'Actif',
            'inactive' => 'Inactif',
            'cancelled' => 'Annulé'
        ];
    }

    /**
     * Get total active item amount
     * @return float Total item amount
     */
    public function getTotalItemAmount()
    {
        if (!$this->currentUser['id'])
            return 0;

        return $this->type === 'payment'
            ? $this->model->getTotalPaymentAmount($this->currentUser['id'])
            : $this->model->getTotalExpenseAmount($this->currentUser['id']);
    }

    /**
     * Get items by category for reporting
     * @return array Item statistics by category
     */
    public function getItemsByCategory()
    {
        if (!$this->currentUser['id'])
            return [];

        return $this->type === 'payment'
            ? $this->model->getPaymentsByCategory($this->currentUser['id'])
            : $this->model->getExpensesByCategory($this->currentUser['id']);
    }

    /**
     * Get the current item type
     * @return string Current type ('payment' or 'expense')
     */
    public function getType()
    {
        return $this->type;
    }
}