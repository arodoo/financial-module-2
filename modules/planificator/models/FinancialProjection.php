<?php
require_once __DIR__ . '/../config/database.php';

/**
 * Financial Projection Model
 * 
 * Handles data retrieval and projection calculations for financial forecasting
 */
class FinancialProjection {
    private $conn;
    private $membre_id;
    
    public function __construct($membre_id = null) {
        // Get database connection
        $this->conn = getDbConnection();
        
        // Set member ID (default to current user if not specified)
        global $id_oo;
        $this->membre_id = $membre_id ?? $id_oo;
    }
    
    /**
     * Test database connection
     * @return bool Connection status
     */
    public function testConnection() {
        if (!$this->conn) return false;
        
        try {
            $stmt = $this->conn->query("SELECT 1");
            return ($stmt !== false);
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Get all fixed incomes (payments) for a user
     * @return array Income data
     */
    public function getFixedIncomes() {
        if (!$this->conn) return [];
        
        try {
            $sql = "SELECT 
                        pf.*, 
                        pc.name as category_name 
                    FROM 
                        paiements_fixes pf 
                    JOIN 
                        paiement_categories pc ON pf.category_id = pc.id 
                    WHERE 
                        pf.membre_id = :membre_id AND 
                        pf.status = 'active' 
                    ORDER BY 
                        pf.start_date";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':membre_id', $this->membre_id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }
    
    /**
     * Get all fixed expenses for a user
     * @return array Expense data
     */
    public function getFixedExpenses() {
        if (!$this->conn) return [];
        
        try {
            $sql = "SELECT 
                        df.*, 
                        dc.name as category_name 
                    FROM 
                        depenses_fixes df 
                    JOIN 
                        depense_categories dc ON df.category_id = dc.id 
                    WHERE 
                        df.membre_id = :membre_id AND 
                        df.status = 'active' 
                    ORDER BY 
                        df.start_date";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':membre_id', $this->membre_id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }
    
    /**
     * Get current assets total value
     * @return float Total assets value
     */
    public function getTotalAssets() {
        if (!$this->conn) return 0;
        
        try {
            $sql = "SELECT 
                        SUM(current_value) as total_value 
                    FROM 
                        assets 
                    WHERE 
                        membre_id = :membre_id";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':membre_id', $this->membre_id, PDO::PARAM_INT);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return floatval($result['total_value'] ?? 0);
        } catch (Exception $e) {
            return 0;
        }
    }
    
    /**
     * Generate projected financial data for given timeframe and parameters
     * 
     * @param array $params Configuration parameters for projection
     * @return array Projection data by time period
     */
    public function generateProjection($params) {
        $projection = [];
        $fixedIncomes = $this->getFixedIncomes();
        $fixedExpenses = $this->getFixedExpenses();
        
        // Default parameters with fallbacks
        $startDate = new DateTime($params['start_date'] ?? 'now');
        $years = intval($params['years'] ?? 5);
        $incomeGrowthRate = floatval($params['income_growth_rate'] ?? 2) / 100; // Convert percentage to decimal
        $expenseInflationRate = floatval($params['expense_inflation_rate'] ?? 2) / 100; // Convert percentage to decimal
        $viewMode = $params['view_mode'] ?? 'yearly'; // yearly, quarterly, monthly
        
        // Check if including assets and determine initial balance
        $includeAssets = isset($params['include_assets']) ? (bool)$params['include_assets'] : false;
        
        // Calculate initial balance based on settings
        if (isset($params['initial_balance']) && is_numeric($params['initial_balance'])) {
            // Use the provided initial balance
            $initialBalance = floatval($params['initial_balance']);
        } else {
            // Calculate from current income/expense balance
            $initialBalance = $this->getCurrentBalance($startDate->format('Y-m-d'));
            
            // Add asset values if requested
            if ($includeAssets) {
                $initialBalance += $this->getTotalAssets();
            }
        }
        
        // Determine projection end date and interval
        $endDate = clone $startDate;
        $endDate->modify("+{$years} years");
        
        // Determine interval for data points
        switch ($viewMode) {
            case 'monthly':
                $interval = 'P1M'; // 1 month
                $dateFormat = 'Y-m';
                $displayFormat = 'M Y';
                break;
                
            case 'quarterly':
                $interval = 'P3M'; // 3 months
                $dateFormat = 'Y-\QN'; // Year-QN (e.g., 2023-Q1)
                $displayFormat = '\QN Y'; // QN Year (e.g., Q1 2023)
                break;
                
            case 'yearly':
            default:
                $interval = 'P1Y'; // 1 year
                $dateFormat = 'Y';
                $displayFormat = 'Y';
                break;
        }
        
        $dateInterval = new DateInterval($interval);
        $currentDate = clone $startDate;
        $runningBalance = $initialBalance;
        $period = 0;
        
        // Generate projection for each time period
        while ($currentDate <= $endDate) {
            $periodLabel = $currentDate->format($dateFormat);
            $displayDate = $currentDate->format($displayFormat);
            
            // Calculate incomes for this period
            $periodIncomes = $this->calculatePeriodicAmount(
                $fixedIncomes,
                $currentDate,
                $dateInterval,
                $period,
                $incomeGrowthRate
            );
            
            // Calculate expenses for this period
            $periodExpenses = $this->calculatePeriodicAmount(
                $fixedExpenses,
                $currentDate,
                $dateInterval,
                $period,
                $expenseInflationRate
            );
            
            // Calculate net cash flow
            $netCashFlow = $periodIncomes - $periodExpenses;
            
            // Update running balance
            $runningBalance += $netCashFlow;
            
            // Add period data to projection
            $projection[] = [
                'period' => $period,
                'date' => $periodLabel,
                'display_date' => $displayDate,
                'timestamp' => $currentDate->getTimestamp(),
                'incomes' => $periodIncomes,
                'expenses' => $periodExpenses,
                'net_flow' => $netCashFlow,
                'balance' => $runningBalance
            ];
            
            // Move to next period
            $currentDate->add($dateInterval);
            $period++;
        }
        
        return $projection;
    }
    
    /**
     * Calculate total amount for a period based on recurring items
     * 
     * @param array $items List of recurring income/expense items
     * @param DateTime $currentDate Current period date
     * @param DateInterval $periodInterval Interval for period
     * @param int $periodNumber Number of period from start (for growth calculation)
     * @param float $growthRate Annual growth rate as decimal
     * @return float Total calculated amount
     */
    private function calculatePeriodicAmount($items, $currentDate, $periodInterval, $periodNumber, $growthRate) {
        $totalAmount = 0;
        $periodEnd = clone $currentDate;
        $periodEnd->add($periodInterval);
        $periodEnd->modify('-1 day'); // End of period
        
        foreach ($items as $item) {
            $itemStartDate = new DateTime($item['start_date']);
            $itemEndDate = !empty($item['end_date']) && $item['end_date'] !== '0000-00-00' 
                ? new DateTime($item['end_date']) 
                : null;
            
            // Skip if item hasn't started yet or has already ended
            if ($itemStartDate > $periodEnd || ($itemEndDate && $itemEndDate < $currentDate)) {
                continue;
            }
            
            // Calculate occurrence count in this period based on frequency
            $amount = floatval($item['amount']);
            $frequency = strtolower($item['frequency']);
            
            // Apply growth/inflation based on period number
            if ($growthRate > 0 && $periodNumber > 0) {
                // Calculate compound growth factor
                $periodInterval_in_years = $this->getIntervalInYears($periodInterval);
                $years = $periodNumber * $periodInterval_in_years;
                $growthFactor = pow(1 + $growthRate, $years);
                $amount = $amount * $growthFactor;
            }
            
            // Convert frequency to monthly equivalent for standardized calculation
            $monthlyAmount = $this->getMonthlyEquivalent($amount, $frequency);
            
            // Calculate months in period
            $monthsInPeriod = $this->getMonthsBetween($currentDate, $periodEnd);
            
            // Add to total
            $totalAmount += $monthlyAmount * $monthsInPeriod;
        }
        
        return $totalAmount;
    }
    
    /**
     * Calculate the monthly equivalent of an amount based on frequency
     * @param float $amount The amount
     * @param string $frequency Payment frequency
     * @return float Monthly equivalent amount
     */
    private function getMonthlyEquivalent($amount, $frequency) {
        switch ($frequency) {
            case 'daily':
                return $amount * 30; // Approximate
            case 'weekly':
                return $amount * 4.33; // Approximate weeks per month
            case 'biweekly':
                return $amount * 2.17; // Approximate
            case 'monthly':
                return $amount;
            case 'quarterly':
                return $amount / 3;
            case 'biannual':
            case 'semi-annual':
                return $amount / 6;
            case 'annual':
            case 'yearly':
                return $amount / 12;
            default:
                return $amount; // Default to monthly
        }
    }
    
    /**
     * Get the equivalent of a DateInterval in years
     * @param DateInterval $interval The interval
     * @return float Years
     */
    private function getIntervalInYears($interval) {
        if ($interval->y > 0) {
            return $interval->y + ($interval->m / 12);
        } else if ($interval->m > 0) {
            return $interval->m / 12;
        } else if ($interval->d > 0) {
            return $interval->d / 365;
        }
        return 1; // Default to 1 year
    }
    
    /**
     * Calculate number of months between two dates
     * @param DateTime $start Start date
     * @param DateTime $end End date
     * @return float Number of months
     */
    private function getMonthsBetween($start, $end) {
        $startObj = clone $start;
        $endObj = clone $end;
        
        $years = $endObj->format('Y') - $startObj->format('Y');
        $months = $endObj->format('n') - $startObj->format('n');
        $days = $endObj->format('j') - $startObj->format('j');
        
        // Adjust for month transitions with different day counts
        if ($days < 0) {
            $monthEndDay = $startObj->format('t');
            $months--;
            $days += $monthEndDay;
        }
        
        $totalMonths = ($years * 12) + $months + ($days / 30);
        return max(0, $totalMonths);
    }

    /**
     * Calculate the current balance of income and expenses
     * @param string $startDate The start date for calculation
     * @return float The current balance
     */
    public function getCurrentBalance($startDate = null) {
        if (!$this->conn) return 0;
        
        if (!$startDate) {
            $startDate = date('Y-m-d');
        }
        
        try {
            // Get total income until start date
            $sql = "SELECT COALESCE(SUM(amount), 0) as total FROM income_transactions 
                    WHERE membre_id = :membre_id AND transaction_date <= :start_date";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':membre_id', $this->membre_id, PDO::PARAM_INT);
            $stmt->bindValue(':start_date', $startDate, PDO::PARAM_STR);
            $stmt->execute();
            $incomeResult = $stmt->fetch(PDO::FETCH_ASSOC);
            $totalIncome = floatval($incomeResult['total'] ?? 0);
            
            // Get total expenses until start date
            $sql = "SELECT COALESCE(SUM(amount), 0) as total FROM expense_transactions 
                    WHERE membre_id = :membre_id AND transaction_date <= :start_date";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':membre_id', $this->membre_id, PDO::PARAM_INT);
            $stmt->bindValue(':start_date', $startDate, PDO::PARAM_STR);
            $stmt->execute();
            $expenseResult = $stmt->fetch(PDO::FETCH_ASSOC);
            $totalExpenses = floatval($expenseResult['total'] ?? 0);
            
            // Calculate balance
            $balance = $totalIncome - $totalExpenses;
            return $balance;
        } catch (Exception $e) {
            error_log('Error calculating current balance: ' . $e->getMessage());
            return 0;
        }
    }
}
?>