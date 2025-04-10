<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card border-primary">
            <div class="card-body text-center">
                <h5 class="card-title text-primary">Revenu Total</h5>
                <h3 class="card-text" id="totalIncomeValue"><?php echo '€' . number_format($totalIncome, 2); ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-danger">
            <div class="card-body text-center">
                <h5 class="card-title text-danger">Dépenses Totales</h5>
                <h3 class="card-text" id="totalExpenseValue"><?php echo '€' . number_format($totalExpense, 2); ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card <?php echo $netBalance >= 0 ? 'border-success' : 'border-warning'; ?>" id="netBalanceCard">
            <div class="card-body text-center">
                <h5 class="card-title <?php echo $netBalance >= 0 ? 'text-success' : 'text-warning'; ?>" id="netBalanceLabel">Solde Net</h5>
                <h3 class="card-text" id="netBalanceValue"><?php echo '€' . number_format($netBalance, 2); ?></h3>
            </div>
        </div>
    </div>
</div>

<!-- Tabs -->
<ul class="nav nav-tabs mb-3" id="financeTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="income-tab" data-bs-toggle="tab" data-bs-target="#income" type="button" role="tab" aria-controls="income" aria-selected="true">
            <i class="fas fa-arrow-down text-success"></i> Gestion des Revenus
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="expense-tab" data-bs-toggle="tab" data-bs-target="#expense" type="button" role="tab" aria-controls="expense" aria-selected="false">
            <i class="fas fa-arrow-up text-danger"></i> Gestion des Dépenses
        </button>
    </li>
</ul>

<div class="tab-content" id="financeTabContent">
    <!-- Income Tab -->
    <div class="tab-pane fade show active" id="income" role="tabpanel" aria-labelledby="income-tab">
        <div class="row mb-3">
            <div class="col-12 text-end">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addIncomeModal">
                    <i class="fas fa-plus"></i> Ajouter un Revenu
                </button>
            </div>
        </div>
        <div class="row">
            <!-- Income Transactions List (Full Width) -->
            <div class="col-md-12">
                <?php $transactionType = 'income'; include 'render-transactions.php'; ?>
            </div>
        </div>
    </div>
    
    <!-- Expense Tab -->
    <div class="tab-pane fade" id="expense" role="tabpanel" aria-labelledby="expense-tab">
        <div class="row mb-3">
            <div class="col-12 text-end">
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#addExpenseModal">
                    <i class="fas fa-plus"></i> Ajouter une Dépense
                </button>
            </div>
        </div>
        <div class="row">
            <!-- Expense Transactions List (Full Width) -->
            <div class="col-md-12">
                <?php $transactionType = 'expense'; include 'render-transactions.php'; ?>
            </div>
        </div>
    </div>
</div>
