<?php
require_once __DIR__ . '/../config/database.php';

/**
 * Fixed Expense Model
 * Handles database operations for fixed expenses (recurring expenses)
 */
class FixedDepense {
    private $conn;
    private $table = 'depenses_fixes';
    private $categories_table = 'depense_categories';
    
    public function __construct() {
        // Use getDbConnection() just like Asset model does
        $this->conn = getDbConnection();
    }
    
    /**
     * Get all fixed expenses for a user
     * @param int $membre_id User ID
     * @return array Expenses data
     */
    public function getAllExpenses($membre_id = null) {
        if (!$this->conn) {
            return [];
        }
        
        try {
            // Use PDO exclusively like the Asset model
            $sql = "SELECT df.*, dc.name as category_name 
                    FROM depenses_fixes df
                    LEFT JOIN depense_categories dc ON df.category_id = dc.id";
            
            if ($membre_id) {
                $sql .= " WHERE df.membre_id = :membre_id ORDER BY df.id DESC";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindValue(':membre_id', $membre_id, PDO::PARAM_INT);
            } else {
                $sql .= " ORDER BY df.id DESC";
                $stmt = $this->conn->prepare($sql);
            }
            
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }
    
    /**
     * Get a specific expense by ID
     * @param int $expense_id Expense ID
     * @return array|false Expense data or false if not found
     */
    public function getExpenseById($expense_id) {
        global $id_oo;
        
        if (!$this->conn) {
            return false;
        }
        
        try {
            $sql = "SELECT df.*, dc.name as category_name 
                    FROM depenses_fixes df
                    LEFT JOIN depense_categories dc ON df.category_id = dc.id
                    WHERE df.id = :expense_id AND df.membre_id = :membre_id";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':expense_id', $expense_id, PDO::PARAM_INT);
            $stmt->bindValue(':membre_id', $id_oo, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Add a new fixed expense
     * @param array $data Expense data
     * @return int|false New expense ID or false on failure
     */
    public function addExpense($data) {
        if (!$this->conn) {
            return false;
        }
        
        try {
            $sql = "INSERT INTO depenses_fixes (membre_id, category_id, name, amount, currency, 
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
     * Get all expense categories
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
     * Update an existing expense
     * @param array $data Updated expense data
     * @return bool Success/failure
     */
    public function updateExpense($data) {
        if (!$this->conn) {
            return false;
        }
        
        try {
            global $id_oo;
            
            $sql = "UPDATE depenses_fixes 
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
            $stmt->bindValue(':id', $data['expense_id'], PDO::PARAM_INT);
            $stmt->bindValue(':membre_id', $id_oo, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Delete an expense
     * @param int $expense_id Expense ID to delete
     * @return bool Success/failure
     */
    public function deleteExpense($expense_id) {
        if (!$this->conn) {
            return false;
        }
        
        try {
            global $id_oo;
            
            $sql = "DELETE FROM depenses_fixes WHERE id = :id AND membre_id = :membre_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':id', $expense_id, PDO::PARAM_INT);
            $stmt->bindValue(':membre_id', $id_oo, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Get total amount of all active expenses for a user
     * @param int $membre_id User ID
     * @return float Total expense amount
     */
    public function getTotalExpenseAmount($membre_id = null) {
        if (!$this->conn) {
            return 0;
        }
        
        try {
            global $id_oo;
            if (!$membre_id) $membre_id = $id_oo;
            
            $sql = "SELECT SUM(amount) as total 
                    FROM depenses_fixes 
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
     * Get expenses grouped by category
     * @param int $membre_id User ID
     * @return array Expense data grouped by category
     */
    public function getExpensesByCategory($membre_id = null) {
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
                      depenses_fixes p
                    JOIN 
                      depense_categories c ON p.category_id = c.id
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