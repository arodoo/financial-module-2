<?php
require_once __DIR__ . '/../config/database.php';

class Dashboard {
    private $conn;

    public function __construct() {
        $this->conn = getDbConnection();
    }

    public function getTotalIncome($startDate = null, $endDate = null) {
        global $id_oo;
        
        // 1. Get income from one-time transactions
        $transactionTotal = $this->getTransactionIncome($id_oo, $startDate, $endDate);
        
        // 2. Get income from fixed payments
        $fixedPaymentTotal = $this->getFixedPaymentIncome($id_oo, $startDate, $endDate);
        
        // Return combined total
        return $transactionTotal + $fixedPaymentTotal;
    }
    
    private function getTransactionIncome($membre_id, $startDate = null, $endDate = null) {
        $sql = "SELECT SUM(amount) as total FROM income_transactions WHERE membre_id = :membre_id";
        $params = [':membre_id' => $membre_id];

        if ($startDate) {
            $sql .= " AND transaction_date >= :start_date";
            $params[':start_date'] = $startDate;
        }

        if ($endDate) {
            $sql .= " AND transaction_date <= :end_date";
            $params[':end_date'] = $endDate;
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }
    
    private function getFixedPaymentIncome($membre_id, $startDate = null, $endDate = null) {
        // If no date range specified, assume current month
        if (!$startDate) {
            $startDate = date('Y-m-01'); // First day of current month
        }
        if (!$endDate) {
            $endDate = date('Y-m-t');    // Last day of current month
        }
        
        $sql = "SELECT 
                    pf.amount, 
                    pf.frequency, 
                    pf.payment_day, 
                    pf.start_date 
                FROM 
                    paiements_fixes pf 
                WHERE 
                    pf.membre_id = :membre_id 
                    AND pf.status = 'active' 
                    AND pf.start_date <= :end_date 
                    AND (pf.end_date IS NULL OR pf.end_date = '0000-00-00' OR pf.end_date >= :start_date)";
                    
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':membre_id', $membre_id, PDO::PARAM_INT);
        $stmt->bindValue(':start_date', $startDate);
        $stmt->bindValue(':end_date', $endDate);
        $stmt->execute();
        $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Calculate prorated monthly equivalent for each fixed payment
        $startDateObj = new DateTime($startDate);
        $endDateObj = new DateTime($endDate);
        $daysInPeriod = $endDateObj->diff($startDateObj)->days + 1;
        $daysInMonth = date('t', strtotime($startDate));
        $monthRatio = $daysInPeriod / $daysInMonth;
        
        $totalAmount = 0;
        foreach ($payments as $payment) {
            $amount = $payment['amount'];
            $frequency = strtolower($payment['frequency']);
            
            // Convert frequency to monthly equivalent
            $monthlyAmount = $this->getMonthlyEquivalent($amount, $frequency);
            
            // Apply proration if not a full month
            if ($daysInPeriod < $daysInMonth) {
                $monthlyAmount = $monthlyAmount * $monthRatio;
            }
            
            $totalAmount += $monthlyAmount;
        }
        
        return $totalAmount;
    }
    
    private function getMonthlyEquivalent($amount, $frequency) {
        switch ($frequency) {
            case 'monthly':
            case 'mensuel':
                return $amount;
            case 'weekly':
            case 'hebdomadaire':
                return $amount * 4.33; // Average weeks in a month
            case 'biweekly':
            case 'bi-weekly':
                return $amount * 2.17; // 26 payments per year / 12 months
            case 'quarterly':
            case 'trimestriel':
                return $amount / 3;
            case 'biannual':
            case 'semestriel':
            case 'semi-annual':
                return $amount / 6;
            case 'annual':
            case 'annuel':
                return $amount / 12;
            default:
                return $amount; // Default to monthly
        }
    }

    public function getTotalExpense($startDate = null, $endDate = null) {
        global $id_oo;
        
        // 1. Get expenses from one-time transactions
        $transactionTotal = $this->getTransactionExpense($id_oo, $startDate, $endDate);
        
        // 2. Get expenses from fixed expenses
        $fixedExpenseTotal = $this->getFixedExpenseTotal($id_oo, $startDate, $endDate);
        
        // Return combined total
        return $transactionTotal + $fixedExpenseTotal;
    }
    
    private function getTransactionExpense($membre_id, $startDate = null, $endDate = null) {
        $sql = "SELECT SUM(amount) as total FROM expense_transactions WHERE membre_id = :membre_id";
        $params = [':membre_id' => $membre_id];

        if ($startDate) {
            $sql .= " AND transaction_date >= :start_date";
            $params[':start_date'] = $startDate;
        }

        if ($endDate) {
            $sql .= " AND transaction_date <= :end_date";
            $params[':end_date'] = $endDate;
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }
    
    private function getFixedExpenseTotal($membre_id, $startDate = null, $endDate = null) {
        // If no date range specified, assume current month
        if (!$startDate) {
            $startDate = date('Y-m-01'); // First day of current month
        }
        if (!$endDate) {
            $endDate = date('Y-m-t');    // Last day of current month
        }
        
        $sql = "SELECT 
                    df.amount, 
                    df.frequency, 
                    df.payment_day, 
                    df.start_date 
                FROM 
                    depenses_fixes df 
                WHERE 
                    df.membre_id = :membre_id 
                    AND df.status = 'active' 
                    AND df.start_date <= :end_date 
                    AND (df.end_date IS NULL OR df.end_date = '0000-00-00' OR df.end_date >= :start_date)";
                    
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':membre_id', $membre_id, PDO::PARAM_INT);
        $stmt->bindValue(':start_date', $startDate);
        $stmt->bindValue(':end_date', $endDate);
        $stmt->execute();
        $expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Calculate prorated monthly equivalent for each fixed expense
        $startDateObj = new DateTime($startDate);
        $endDateObj = new DateTime($endDate);
        $daysInPeriod = $endDateObj->diff($startDateObj)->days + 1;
        $daysInMonth = date('t', strtotime($startDate));
        $monthRatio = $daysInPeriod / $daysInMonth;
        
        $totalAmount = 0;
        foreach ($expenses as $expense) {
            $amount = $expense['amount'];
            $frequency = strtolower($expense['frequency']);
            
            // Convert frequency to monthly equivalent
            $monthlyAmount = $this->getMonthlyEquivalent($amount, $frequency);
            
            // Apply proration if not a full month
            if ($daysInPeriod < $daysInMonth) {
                $monthlyAmount = $monthlyAmount * $monthRatio;
            }
            
            $totalAmount += $monthlyAmount;
        }
        
        return $totalAmount;
    }

    public function getRecentTransactions($limit = 5) {
        global $id_oo;
        
        // 1. Get recent one-time income transactions
        $incomeTransactions = $this->getRecentIncomeTransactions($id_oo, $limit);
        
        // 2. Get recent fixed income payments
        $fixedIncomePayments = $this->getRecentFixedIncomePayments($id_oo, $limit);
        
        // 3. Get recent one-time expense transactions
        $expenseTransactions = $this->getRecentExpenseTransactions($id_oo, $limit);
        
        // 4. Get recent fixed expenses
        $fixedExpenses = $this->getRecentFixedExpenses($id_oo, $limit);
        
        // 5. Combine all transactions
        $allTransactions = array_merge(
            $incomeTransactions, 
            $fixedIncomePayments, 
            $expenseTransactions, 
            $fixedExpenses
        );
        
        // 6. Sort by date (newest first)
        usort($allTransactions, function($a, $b) {
            $dateA = isset($a['transaction_date']) ? $a['transaction_date'] : $a['start_date'];
            $dateB = isset($b['transaction_date']) ? $b['transaction_date'] : $b['start_date'];
            return strtotime($dateB) - strtotime($dateA);
        });
        
        // 7. Return only the specified limit
        return array_slice($allTransactions, 0, $limit);
    }
    
    private function getRecentIncomeTransactions($membre_id, $limit) {
        $query = "
            SELECT 
                i.id, 
                i.amount, 
                i.description, 
                i.transaction_date, 
                c.name as category,
                'income' as type
            FROM income_transactions i
            JOIN income_categories c ON i.category_id = c.id
            WHERE i.membre_id = :membre_id
            ORDER BY i.transaction_date DESC
            LIMIT :limit
        ";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':membre_id', $membre_id, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    private function getRecentFixedIncomePayments($membre_id, $limit) {
        $query = "
            SELECT 
                p.id, 
                p.amount, 
                p.name as description, 
                p.start_date,
                c.name as category,
                'fixed_income' as type,
                p.frequency
            FROM paiements_fixes p
            JOIN paiement_categories c ON p.category_id = c.id
            WHERE p.membre_id = :membre_id
            AND p.status = 'active'
            ORDER BY p.created_at DESC
            LIMIT :limit
        ";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':membre_id', $membre_id, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    private function getRecentExpenseTransactions($membre_id, $limit) {
        $query = "
            SELECT 
                e.id, 
                e.amount, 
                e.description, 
                e.transaction_date, 
                c.name as category,
                'expense' as type
            FROM expense_transactions e
            JOIN expense_categories c ON e.category_id = c.id
            WHERE e.membre_id = :membre_id
            ORDER BY e.transaction_date DESC
            LIMIT :limit
        ";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':membre_id', $membre_id, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    private function getRecentFixedExpenses($membre_id, $limit) {
        $query = "
            SELECT 
                d.id, 
                d.amount, 
                d.name as description, 
                d.start_date,
                c.name as category,
                'fixed_expense' as type,
                d.frequency
            FROM depenses_fixes d
            JOIN depense_categories c ON d.category_id = c.id
            WHERE d.membre_id = :membre_id
            AND d.status = 'active'
            ORDER BY d.created_at DESC
            LIMIT :limit
        ";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':membre_id', $membre_id, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCategoryTotals($type = 'expense') {
        global $id_oo;
        
        if ($type == 'income') {
            // Get one-time income totals by category
            $transactionTotals = $this->getIncomeCategoryTotals($id_oo);
            
            // Get fixed income totals by category
            $fixedTotals = $this->getFixedIncomeCategoryTotals($id_oo);
            
            // Merge the results
            return $this->mergeCategoryTotals($transactionTotals, $fixedTotals);
        } else {
            // Get one-time expense totals by category
            $transactionTotals = $this->getExpenseCategoryTotals($id_oo);
            
            // Get fixed expense totals by category
            $fixedTotals = $this->getFixedExpenseCategoryTotals($id_oo);
            
            // Merge the results
            return $this->mergeCategoryTotals($transactionTotals, $fixedTotals);
        }
    }
    
    private function getIncomeCategoryTotals($membre_id) {
        $query = "
            SELECT c.name as category, SUM(t.amount) as total
            FROM income_transactions t
            JOIN income_categories c ON t.category_id = c.id
            WHERE t.membre_id = :membre_id
            GROUP BY t.category_id
            ORDER BY total DESC
        ";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':membre_id', $membre_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    private function getExpenseCategoryTotals($membre_id) {
        $query = "
            SELECT c.name as category, SUM(t.amount) as total
            FROM expense_transactions t
            JOIN expense_categories c ON t.category_id = c.id
            WHERE t.membre_id = :membre_id
            GROUP BY t.category_id
            ORDER BY total DESC
        ";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':membre_id', $membre_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    private function getFixedIncomeCategoryTotals($membre_id) {
        // Calculate the monthly equivalent for each fixed income
        $query = "
            SELECT 
                c.name as category,
                p.amount,
                p.frequency
            FROM paiements_fixes p
            JOIN paiement_categories c ON p.category_id = c.id
            WHERE p.membre_id = :membre_id
            AND p.status = 'active'
        ";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':membre_id', $membre_id, PDO::PARAM_INT);
        $stmt->execute();
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $totals = [];
        foreach ($items as $item) {
            $category = $item['category'];
            $monthlyAmount = $this->getMonthlyEquivalent($item['amount'], $item['frequency']);
            
            if (!isset($totals[$category])) {
                $totals[$category] = [
                    'category' => $category,
                    'total' => 0
                ];
            }
            
            $totals[$category]['total'] += $monthlyAmount;
        }
        
        return array_values($totals);
    }
    
    private function getFixedExpenseCategoryTotals($membre_id) {
        // Calculate the monthly equivalent for each fixed expense
        $query = "
            SELECT 
                c.name as category,
                d.amount,
                d.frequency
            FROM depenses_fixes d
            JOIN depense_categories c ON d.category_id = c.id
            WHERE d.membre_id = :membre_id
            AND d.status = 'active'
        ";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':membre_id', $membre_id, PDO::PARAM_INT);
        $stmt->execute();
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $totals = [];
        foreach ($items as $item) {
            $category = $item['category'];
            $monthlyAmount = $this->getMonthlyEquivalent($item['amount'], $item['frequency']);
            
            if (!isset($totals[$category])) {
                $totals[$category] = [
                    'category' => $category,
                    'total' => 0
                ];
            }
            
            $totals[$category]['total'] += $monthlyAmount;
        }
        
        return array_values($totals);
    }
    
    private function mergeCategoryTotals($totals1, $totals2) {
        $mergedTotals = [];
        
        // Process the first set of totals
        foreach ($totals1 as $item) {
            $category = $item['category'];
            $mergedTotals[$category] = isset($mergedTotals[$category]) ? 
                                      $mergedTotals[$category] + $item['total'] : $item['total'];
        }
        
        // Process the second set of totals
        foreach ($totals2 as $item) {
            $category = $item['category'];
            $mergedTotals[$category] = isset($mergedTotals[$category]) ? 
                                      $mergedTotals[$category] + $item['total'] : $item['total'];
        }
        
        // Convert back to array of arrays
        $result = [];
        foreach ($mergedTotals as $category => $total) {
            $result[] = [
                'category' => $category,
                'total' => $total
            ];
        }
        
        // Sort by total descending
        usort($result, function($a, $b) {
            return $b['total'] - $a['total'];
        });
        
        return $result;
    }
}
?>