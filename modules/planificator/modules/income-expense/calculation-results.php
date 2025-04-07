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
    <div class="tab-pane fade show active" id="income" role="tabpanel">
        <div class="row">
            <!-- Income Form -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Ajouter Nouveau Revenu</h5>
                    </div>
                    <div class="card-body">
                        <form id="income-form" method="POST">
                            <div class="mb-3">
                                <label for="income_category" class="form-label">Catégorie</label>
                                <select class="form-select" id="income_category" name="category_id" required>
                                    <option value="">Sélectionner Catégorie</option>
                                    <?php foreach ($incomeCategories as $category): ?>
                                        <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="income_amount" class="form-label">Montant (€)</label>
                                <input type="number" class="form-control" id="income_amount" name="amount" step="0.01" min="0" required>
                            </div>
                            <div class="mb-3">
                                <label for="income_date" class="form-label">Date</label>
                                <input type="date" class="form-control" id="income_date" name="transaction_date" value="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="income_description" class="form-label">Description</label>
                                <textarea class="form-control" id="income_description" name="description" rows="3"></textarea>
                            </div>
                            <input type="hidden" name="action" value="add_income">
                            <button type="submit" class="btn btn-primary w-100">Ajouter Revenu</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Income Transactions List -->
            <div class="col-md-8">
                <?php $transactionType = 'income'; include 'render-transactions.php'; ?>
            </div>
        </div>
    </div>
    
    <!-- Expense Tab -->
    <div class="tab-pane fade" id="expense" role="tabpanel">
        <div class="row">
            <!-- Expense Form -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0">Ajouter Nouvelle Dépense</h5>
                    </div>
                    <div class="card-body">
                        <form id="expense-form" method="POST">
                            <div class="mb-3">
                                <label for="expense_category" class="form-label">Catégorie</label>
                                <select class="form-select" id="expense_category" name="category_id" required>
                                    <option value="">Sélectionner Catégorie</option>
                                    <?php foreach ($expenseCategories as $category): ?>
                                        <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="expense_amount" class="form-label">Montant (€)</label>
                                <input type="number" class="form-control" id="expense_amount" name="amount" step="0.01" min="0" required>
                            </div>
                            <div class="mb-3">
                                <label for="expense_date" class="form-label">Date</label>
                                <input type="date" class="form-control" id="expense_date" name="transaction_date" value="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="expense_description" class="form-label">Description</label>
                                <textarea class="form-control" id="expense_description" name="description" rows="3"></textarea>
                            </div>
                            <input type="hidden" name="action" value="add_expense">
                            <button type="submit" class="btn btn-danger w-100">Ajouter Dépense</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Expense Transactions List -->
            <div class="col-md-8">
                <?php $transactionType = 'expense'; include 'render-transactions.php'; ?>
            </div>
        </div>
    </div>
</div>
