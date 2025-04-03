<div class="card">
    <div class="card-header bg-light d-flex justify-content-between">
        <h5 class="mb-0">Résumé de la Projection</h5>
        <div id="summary-loading" class="spinner-border spinner-border-sm text-primary d-none" role="status">
            <span class="visually-hidden">Chargement...</span>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <!-- Final Balance -->
            <div class="col-md-3 mb-3 mb-md-0">
                <div class="card bg-light h-100">
                    <div class="card-body text-center">
                        <h6 class="text-muted mb-2">Solde Final</h6>
                        <h3 id="summary-final-balance" class="mb-0">0 €</h3>
                        <div>
                            <span id="summary-growth" class="badge bg-success">+0%</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Net Savings -->
            <div class="col-md-3 mb-3 mb-md-0">
                <div class="card bg-light h-100">
                    <div class="card-body text-center">
                        <h6 class="text-muted mb-2">Épargne Nette Totale</h6>
                        <h3 id="summary-net-change" class="mb-0">0 €</h3>
                        <div>
                            <span id="summary-savings-rate" class="badge bg-info">0%</span>
                            <small class="text-muted">du revenu</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Total Income -->
            <div class="col-md-3 mb-3 mb-md-0">
                <div class="card bg-light h-100">
                    <div class="card-body text-center">
                        <h6 class="text-muted mb-2">Revenus Totaux</h6>
                        <h3 id="summary-income" class="mb-0">0 €</h3>
                        <div>
                            <span id="summary-avg-monthly-income" class="text-muted">0 €</span>
                            <small class="text-muted">/mois</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Total Expenses -->
            <div class="col-md-3">
                <div class="card bg-light h-100">
                    <div class="card-body text-center">
                        <h6 class="text-muted mb-2">Dépenses Totales</h6>
                        <h3 id="summary-expenses" class="mb-0">0 €</h3>
                        <div>
                            <span id="summary-avg-monthly-expense" class="text-muted">0 €</span>
                            <small class="text-muted">/mois</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
/**
 * Update summary with new data
 * @param {Object} summaryData The summary data
 */
function updateSummary(summaryData) {
    // Update summary metrics
    document.getElementById('summary-final-balance').textContent = formatCurrency(summaryData.final_balance);
    document.getElementById('summary-net-change').textContent = formatCurrency(summaryData.net_change);
    document.getElementById('summary-income').textContent = formatCurrency(summaryData.total_income);
    document.getElementById('summary-expenses').textContent = formatCurrency(summaryData.total_expenses);
    
    // Update secondary metrics
    document.getElementById('summary-avg-monthly-income').textContent = formatCurrency(summaryData.average_monthly_income);
    document.getElementById('summary-avg-monthly-expense').textContent = formatCurrency(summaryData.average_monthly_expense);
    
    // Update percentages
    const growthElement = document.getElementById('summary-growth');
    const growthPercentage = summaryData.growth_percentage;
    growthElement.textContent = (growthPercentage >= 0 ? '+' : '') + growthPercentage.toFixed(1) + '%';
    growthElement.className = 'badge ' + (growthPercentage >= 0 ? 'bg-success' : 'bg-danger');
    
    const savingsRateElement = document.getElementById('summary-savings-rate');
    const savingsRate = summaryData.savings_rate;
    savingsRateElement.textContent = savingsRate.toFixed(1) + '%';
    savingsRateElement.className = 'badge ' + (savingsRate >= 0 ? 'bg-info' : 'bg-warning');
}
</script>