<!-- Recent Transactions -->
<div class="col-lg-6 mb-4">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Transactions Récentes</h5>
        </div>
        <div class="card-body">
            <?php if (empty($recentTransactions)): ?>
                <p class="text-muted">Aucune transaction enregistrée pour le moment.</p>
            <?php else: ?>
                <div class="list-group">
                    <?php foreach ($recentTransactions as $transaction):
                        // Determine transaction type and styling
                        $isIncome = in_array($transaction['type'], ['income', 'fixed_income']);
                        $isFixed = in_array($transaction['type'], ['fixed_income', 'fixed_expense']);

                        // Date field may be transaction_date or start_date depending on type
                        $dateField = isset($transaction['transaction_date']) ? 'transaction_date' : 'start_date';

                        // Style classes
                        $textColorClass = $isIncome ? 'text-success' : 'text-danger';
                        $badgeClass = $isFixed ? 'bg-info' : 'bg-secondary';
                        $badgeText = $isFixed ? 'Fixe' : 'Ponctuel';
                        ?>
                        <div class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">
                                    <?php echo htmlspecialchars($transaction['category']); ?>
                                    <span class="badge <?php echo $badgeClass; ?> ms-2" style="font-size: 0.7rem;">
                                        <?php echo $badgeText; ?>
                                    </span>
                                </h6>
                                <small><?php echo date('d/m/Y', strtotime($transaction[$dateField])); ?></small>
                            </div>
                            <p class="mb-1"><?php echo htmlspecialchars($transaction['description'] ?: 'Pas de description'); ?>
                            </p>
                            <div class="d-flex w-100 justify-content-between">
                                <small class="text-muted">
                                    <?php echo $isIncome ? 'Revenu' : 'Dépense'; ?>
                                    <?php if ($isFixed && isset($transaction['frequency'])): ?>
                                        <span class="ms-1">(<?php echo htmlspecialchars($transaction['frequency']); ?>)</span>
                                    <?php endif; ?>
                                </small>
                                <span class="<?php echo $textColorClass; ?>">
                                    <?php echo $isIncome ? '+' : '-'; ?>€<?php echo number_format($transaction['amount'], 2); ?>
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>