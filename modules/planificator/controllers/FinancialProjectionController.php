<?php
// Enable error reporting for debugging

error_log('[DEBUG] Loading FinancialProjectionController');

/**
 * Financial Projection Controller
 * 
 * Handles business logic for financial projection calculations and data preparation
 */
class FinancialProjectionController
{
    private $model;
    private $membre_id;

    public function __construct()
    {
        global $id_oo;

        require_once __DIR__ . '/../models/FinancialProjection.php';
        $this->membre_id = $id_oo;
        $this->model = new FinancialProjection($this->membre_id);
    }

    /**
     * Get initial view data for financial projection configuration
     * @return array Data for the view
     */
    public function getViewData()
    {
        $fixedIncomes = $this->model->getFixedIncomes();
        $fixedExpenses = $this->model->getFixedExpenses();
        $totalAssets = $this->model->getTotalAssets();
        $currentBalance = $this->model->getCurrentBalance();

        return [
            'fixed_incomes' => $fixedIncomes,
            'fixed_expenses' => $fixedExpenses,
            'total_assets' => $totalAssets,
            'current_balance' => $currentBalance,
            'default_projection' => $this->getDefaultProjection()
        ];
    }

    /**
     * Generate projection data based on parameters
     * @param array $params Projection parameters
     * @return array Calculated projection data
     */
    public function generateProjection($params)
    {
        return $this->model->generateProjection($params);
    }

    /**
     * Get default projection data
     * @return array Default projection with standard parameters
     */
    public function getDefaultProjection()
    {
        $defaultParams = [
            'start_date' => date('Y-m-d'),
            'years' => 5,
            'income_growth_rate' => 2.0, // 2% annual growth
            'expense_inflation_rate' => 2.5, // 2.5% annual inflation
            'view_mode' => 'yearly',
            'initial_balance' => null, // Will use calculated balance
            'include_assets' => false // Default to excluding assets
        ];

        return $this->model->generateProjection($defaultParams);
    }

    /**
     * Get available time periods for projection
     * @return array Time period options
     */
    public function getYearOptions()
    {
        return [
            1 => '1 an',
            3 => '3 ans',
            5 => '5 ans',
            10 => '10 ans',
            15 => '15 ans',
            20 => '20 ans',
            30 => '30 ans'
        ];
    }

    /**
     * Get view mode options
     * @return array View mode options
     */
    public function getViewModeOptions()
    {
        return [
            'yearly' => 'Annuel',
            'quarterly' => 'Trimestriel',
            'monthly' => 'Mensuel'
        ];
    }

    /**
     * Calculate summary metrics from projection data
     * @param array $projection The projection data
     * @return array Summary metrics
     */
    public function calculateSummary($projection)
    {
        if (empty($projection)) {
            return [
                'total_duration' => 0,
                'total_income' => 0,
                'total_expenses' => 0,
                'net_change' => 0,
                'average_monthly_income' => 0,
                'average_monthly_expense' => 0,
                'average_monthly_savings' => 0,
                'final_balance' => 0,
                'growth_percentage' => 0,
                'savings_rate' => 0
            ];
        }

        $totalIncome = 0;
        $totalExpenses = 0;
        $initialBalance = $projection[0]['balance'] - $projection[0]['net_flow'];
        $finalBalance = end($projection)['balance'];

        foreach ($projection as $period) {
            $totalIncome += $period['incomes'];
            $totalExpenses += $period['expenses'];
        }

        $netChange = $totalIncome - $totalExpenses;
        $totalMonths = count($projection) * 12; // Approximate for now

        // Calculate metrics
        $averageMonthlyIncome = $totalIncome / $totalMonths;
        $averageMonthlyExpense = $totalExpenses / $totalMonths;
        $averageMonthlySavings = $averageMonthlyIncome - $averageMonthlyExpense;
        $growthPercentage = ($initialBalance > 0) ? (($finalBalance - $initialBalance) / $initialBalance) * 100 : 0;
        $savingsRate = ($totalIncome > 0) ? (($totalIncome - $totalExpenses) / $totalIncome) * 100 : 0;

        return [
            'total_duration' => count($projection),
            'total_income' => $totalIncome,
            'total_expenses' => $totalExpenses,
            'net_change' => $netChange,
            'average_monthly_income' => $averageMonthlyIncome,
            'average_monthly_expense' => $averageMonthlyExpense,
            'average_monthly_savings' => $averageMonthlySavings,
            'initial_balance' => $initialBalance,
            'final_balance' => $finalBalance,
            'growth_percentage' => $growthPercentage,
            'savings_rate' => $savingsRate
        ];
    }

    /**
     * Format currency values for display
     * @param float $amount Amount to format
     * @param string $currency Currency code
     * @return string Formatted currency
     */
    public function formatCurrency($amount, $currency = 'EUR')
    {
        return number_format($amount, 2, ',', ' ') . ' ' . $currency;
    }
}
?>