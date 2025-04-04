<?php
/**
 * Fixed Payment Controller
 * Handles business logic for fixed payments operations
 */
class FixedPaymentController
{
    private $model;
    private $currentUser;

    public function __construct()
    {
        global $id_oo;
        require_once __DIR__ . '/../models/FixedPayment.php';
        $this->model = new FixedPayment();
        $this->currentUser = ['id' => $id_oo];
    }

    /**
     * Get all data required for the view
     * @return array View data including payments and categories
     */
    public function getViewData()
    {
        $data = [
            'payments' => $this->getPayments($this->currentUser['id']),
            'categories' => $this->getCategories()
        ];

        // Check if a specific payment is requested to view (like in AssetController)
        if (isset($_GET['view_payment'])) {
            $paymentId = (int) $_GET['view_payment'];
            $payment = $this->getPaymentById($paymentId);

            if ($payment) {
                $data['viewPayment'] = $payment;
            }
        }

        // Check if a specific payment is requested to edit (like in AssetController)
        if (isset($_GET['edit_payment'])) {
            $paymentId = (int) $_GET['edit_payment'];
            $payment = $this->getPaymentById($paymentId);

            if ($payment) {
                $data['editPayment'] = $payment;
            }
        }

        return $data;
    }

    /**
     * Get all payments for a user (alias for getPayments for compatibility with shared templates)
     * @param int $membre_id User ID
     * @return array Payments data
     */
    public function getItems($membre_id = null)
    {
        return $this->getPayments($membre_id);
    }

    /**
     * Get all payments for a user
     * @param int $membre_id User ID
     * @return array Payments data
     */
    public function getPayments($membre_id = null)
    {
        if (!$membre_id) {
            $membre_id = $this->currentUser['id'];
        }

        if (!$membre_id)
            return [];

        return $this->model->getAllPayments($membre_id);
    }

    /**
     * Get a specific payment by ID
     * @param int $payment_id Payment ID
     * @return array|false Payment data or false if not found
     */
    public function getPaymentById($payment_id)
    {
        if (!$this->currentUser['id'])
            return false;

        return $this->model->getPaymentById($payment_id);
    }

    /**
     * Add a new fixed payment (renamed for consistency with asset module)
     * @param array $data Payment data
     * @return int|false New payment ID or false on failure
     */
    public function savePayment($data)
    {
        if (!$this->currentUser['id'])
            return false;

        // Debug log input data like in AssetController
        error_log("FixedPaymentController savePayment input data: " . print_r($data, true));

        // Data validation
        if (empty($data['name']) || empty($data['category_id']) || empty($data['amount'])) {
            error_log("Required payment fields missing");
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

        // Default dates if not provided (like in AssetController)
        if (empty($data['start_date'])) {
            $data['start_date'] = date('Y-m-d');
        }

        try {
            $result = $this->model->addPayment($data);
            error_log("Payment save result: " . ($result ? "Success with ID: $result" : "Failed"));
            return $result;
        } catch (Exception $e) {
            error_log("Exception in FixedPaymentController::savePayment: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update an existing payment
     * @param array $data Updated payment data
     * @return bool Success/failure
     */
    public function updatePayment($data)
    {
        if (!$this->currentUser['id'])
            return false;

        // Debug log input data like in AssetController
        error_log("FixedPaymentController updatePayment input data: " . print_r($data, true));

        // Ensure we have a payment ID
        if (empty($data['payment_id'])) {
            error_log("Payment ID missing in update");
            return false;
        }

        // Handle both field name formats for backward compatibility
        $name = isset($data['name']) ? $data['name'] : (isset($data['payment_name']) ? $data['payment_name'] : null);

        // Data validation
        if (empty($name) || empty($data['category_id']) || empty($data['amount'])) {
            error_log("Required payment fields missing");
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
            return $this->model->updatePayment($data);
        } catch (Exception $e) {
            error_log("Exception in FixedPaymentController::updatePayment: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete a payment
     * @param int $payment_id Payment ID to delete
     * @return bool Success/failure
     */
    public function deletePayment($payment_id)
    {
        if (!$this->currentUser['id'] || !$payment_id) {
            error_log("Cannot delete payment: User ID or payment ID missing");
            return false;
        }

        try {
            return $this->model->deletePayment($payment_id);
        } catch (Exception $e) {
            error_log("Exception in FixedPaymentController::deletePayment: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get all payment categories
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
     * Get payment status options
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
     * Get total active payment amount
     * @return float Total payment amount
     */
    public function getTotalPaymentAmount()
    {
        if (!$this->currentUser['id'])
            return 0;

        return $this->model->getTotalPaymentAmount($this->currentUser['id']);
    }

    /**
     * Get payments by category for reporting
     * @return array Payment statistics by category
     */
    public function getPaymentsByCategory()
    {
        if (!$this->currentUser['id'])
            return [];

        return $this->model->getPaymentsByCategory($this->currentUser['id']);
    }
}
?>
