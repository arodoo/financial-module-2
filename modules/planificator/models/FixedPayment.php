<?php
require_once __DIR__ . '/../config/database.php';

/**
 * Fixed Payment Model
 * Handles database operations for fixed payments (recurring income)
 */
class FixedPayment {
    private $conn;
    private $table = 'paiements_fixes';
    private $categories_table = 'paiement_categories';
    
    public function __construct() {
        // Use getDbConnection() just like Asset model does
        $this->conn = getDbConnection();
    }
    
    /**
     * Get all fixed payments for a user
     * @param int $membre_id User ID
     * @return array Payments data
     */
    public function getAllPayments($membre_id = null) {
        if (!$this->conn) {
            return [];
        }
        
        try {
            // Use PDO exclusively like the Asset model
            $sql = "SELECT pf.*, pc.name as category_name 
                    FROM paiements_fixes pf
                    LEFT JOIN paiement_categories pc ON pf.category_id = pc.id";
            
            if ($membre_id) {
                $sql .= " WHERE pf.membre_id = :membre_id ORDER BY pf.id DESC";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindValue(':membre_id', $membre_id, PDO::PARAM_INT);
            } else {
                $sql .= " ORDER BY pf.id DESC";
                $stmt = $this->conn->prepare($sql);
            }
            
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }
    
    /**
     * Get a specific payment by ID
     * @param int $payment_id Payment ID
     * @return array|false Payment data or false if not found
     */
    public function getPaymentById($payment_id) {
        global $id_oo;
        
        if (!$this->conn) {
            return false;
        }
        
        try {
            $sql = "SELECT pf.*, pc.name as category_name 
                    FROM paiements_fixes pf
                    LEFT JOIN paiement_categories pc ON pf.category_id = pc.id
                    WHERE pf.id = :payment_id AND pf.membre_id = :membre_id";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':payment_id', $payment_id, PDO::PARAM_INT);
            $stmt->bindValue(':membre_id', $id_oo, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Add a new fixed payment
     * @param array $data Payment data
     * @return int|false New payment ID or false on failure
     */
    public function addPayment($data) {
        if (!$this->conn) {
            return false;
        }
        
        try {
            $sql = "INSERT INTO paiements_fixes (membre_id, category_id, name, amount, currency, 
                    frequency, payment_day, start_date, end_date, status, notes) 
                    VALUES (:membre_id, :category_id, :name, :amount, :currency, 
                    :frequency, :payment_day, :start_date, :end_date, :status, :notes)";
            
            $stmt = $this->conn->prepare($sql);
            
            // Using bindValue like Asset model instead of bindParam
            $stmt->bindValue(':membre_id', $data['membre_id'], PDO::PARAM_INT);
            $stmt->bindValue(':category_id', $data['category_id'], PDO::PARAM_INT);
            $stmt->bindValue(':name', $data['name'], PDO::PARAM_STR);
            $stmt->bindValue(':amount', $data['amount'], PDO::PARAM_STR);
            $stmt->bindValue(':currency', $data['currency'] ?? 'EUR', PDO::PARAM_STR);
            $stmt->bindValue(':frequency', $data['frequency'] ?? 'monthly', PDO::PARAM_STR);
            $stmt->bindValue(':payment_day', $data['payment_day'] ?? '1', PDO::PARAM_STR);
            $stmt->bindValue(':start_date', $data['start_date'], PDO::PARAM_STR);
            $stmt->bindValue(':end_date', $data['end_date'] ?? null, PDO::PARAM_STR);
            $stmt->bindValue(':status', $data['status'] ?? 'active', PDO::PARAM_STR);
            $stmt->bindValue(':notes', $data['notes'] ?? '', PDO::PARAM_STR);
            
            if ($stmt->execute()) {
                $newId = $this->conn->lastInsertId();
                return $newId;
            }
            
            return false;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Get all payment categories
     * @return array Categories data
     */
    public function getCategories() {
        if (!$this->conn) {
            return [];
        }
        
        try {
            $sql = "SELECT * FROM {$this->categories_table} ORDER BY name";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }
    
    /**
     * Update an existing payment
     * @param array $data Updated payment data
     * @return bool Success/failure
     */
    public function updatePayment($data) {
        if (!$this->conn) {
            return false;
        }
        
        try {
            global $id_oo;
            
            $sql = "UPDATE paiements_fixes 
                    SET category_id = :category_id, 
                        name = :name, 
                        amount = :amount, 
                        currency = :currency, 
                        frequency = :frequency, 
                        payment_day = :payment_day, 
                        start_date = :start_date, 
                        end_date = :end_date, 
                        status = :status, 
                        notes = :notes 
                    WHERE id = :id AND membre_id = :membre_id";
            
            $stmt = $this->conn->prepare($sql);
            
            // Using bindValue instead of bindParam like the Asset model
            $stmt->bindValue(':category_id', $data['category_id'], PDO::PARAM_INT);
            $stmt->bindValue(':name', $data['name'], PDO::PARAM_STR);
            $stmt->bindValue(':amount', $data['amount'], PDO::PARAM_STR);
            $stmt->bindValue(':currency', $data['currency'] ?? 'EUR', PDO::PARAM_STR);
            $stmt->bindValue(':frequency', $data['frequency'] ?? 'monthly', PDO::PARAM_STR);
            $stmt->bindValue(':payment_day', $data['payment_day'] ?? '1', PDO::PARAM_STR);
            $stmt->bindValue(':start_date', $data['start_date'], PDO::PARAM_STR);
            $stmt->bindValue(':end_date', $data['end_date'] ?? null, PDO::PARAM_STR);
            $stmt->bindValue(':status', $data['status'] ?? 'active', PDO::PARAM_STR);
            $stmt->bindValue(':notes', $data['notes'] ?? '', PDO::PARAM_STR);
            $stmt->bindValue(':id', $data['payment_id'], PDO::PARAM_INT);
            $stmt->bindValue(':membre_id', $id_oo, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Delete a payment
     * @param int $payment_id Payment ID to delete
     * @return bool Success/failure
     */
    public function deletePayment($payment_id) {
        if (!$this->conn) {
            return false;
        }
        
        try {
            global $id_oo;
            
            $sql = "DELETE FROM paiements_fixes WHERE id = :id AND membre_id = :membre_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':id', $payment_id, PDO::PARAM_INT);
            $stmt->bindValue(':membre_id', $id_oo, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Get total amount of all active payments for a user
     * @param int $membre_id User ID
     * @return float Total payment amount
     */
    public function getTotalPaymentAmount($membre_id = null) {
        if (!$this->conn) {
            return 0;
        }
        
        try {
            global $id_oo;
            if (!$membre_id) $membre_id = $id_oo;
            
            $sql = "SELECT SUM(amount) as total 
                    FROM paiements_fixes 
                    WHERE membre_id = :membre_id AND status = 'active'";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':membre_id', $membre_id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return floatval($result['total'] ?? 0);
        } catch (Exception $e) {
            return 0;
        }
    }
    
    /**
     * Get payments grouped by category
     * @param int $membre_id User ID
     * @return array Payments data grouped by category
     */
    public function getPaymentsByCategory($membre_id = null) {
        if (!$this->conn) {
            return [];
        }
        
        try {
            global $id_oo;
            if (!$membre_id) $membre_id = $id_oo;
            
            $sql = "SELECT 
                      c.name as category, 
                      COUNT(p.id) as count, 
                      SUM(p.amount) as total_amount
                    FROM 
                      paiements_fixes p
                    JOIN 
                      paiement_categories c ON p.category_id = c.id
                    WHERE 
                      p.membre_id = :membre_id AND p.status = 'active'
                    GROUP BY 
                      p.category_id
                    ORDER BY 
                      total_amount DESC";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':membre_id', $membre_id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }
}
?>
