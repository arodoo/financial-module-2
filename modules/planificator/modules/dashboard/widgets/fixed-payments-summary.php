<?php
/**
 * Fixed Payments & Expenses Summary Widget
 * Shows a summary of all recurring fixed payments and expenses
 */
require_once __DIR__ . '/../../../models/FixedPayment.php';
require_once __DIR__ . '/../../../models/FixedDepense.php';

// Initialize models
$fixedPaymentModel = new FixedPayment();
$fixedDepenseModel = new FixedDepense();

// Get fixed payments and expenses using models
$fixedPayments = $fixedPaymentModel->getAllPayments($id_oo);
$fixedExpenses = $fixedDepenseModel->getAllExpenses($id_oo);

// Filter only active items
$activePayments = array_filter($fixedPayments, function($payment) {
    return $payment['status'] === 'active';
});

$activeExpenses = array_filter($fixedExpenses, function($expense) {
    return $expense['status'] === 'active';
});

// Calculate monthly equivalents for all payments and expenses
foreach ($activePayments as &$payment) {
    $payment['monthly_amount'] = calculateMonthlyEquivalent(
        $payment['amount'],
        $payment['frequency']
    );
}

foreach ($activeExpenses as &$expense) {
    $expense['monthly_amount'] = calculateMonthlyEquivalent(
        $expense['amount'],
        $expense['frequency']
    );
}

// Calculate totals
$totalMonthlyIncome = array_sum(array_column($activePayments, 'monthly_amount'));
$totalMonthlyExpenses = array_sum(array_column($activeExpenses, 'monthly_amount'));
$netMonthly = $totalMonthlyIncome - $totalMonthlyExpenses;

// Helper function to convert different payment frequencies to monthly equivalents
function calculateMonthlyEquivalent($amount, $frequency) {
    $frequency = strtolower($frequency);
    
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

// Sort by monthly amount (descending)
usort($activePayments, function($a, $b) {
    return $b['monthly_amount'] <=> $a['monthly_amount'];
});

usort($activeExpenses, function($a, $b) {
    return $b['monthly_amount'] <=> $a['monthly_amount'];
});
?>

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Paiements & Dépenses Fixes</h5>
                <a href="?action=fixed-payments" class="btn btn-sm btn-outline-primary">Gérer</a>
            </div>
            <div class="card-body">
                
                <!-- Monthly Summary -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card text-bg-success h-100">
                            <div class="card-body text-center">
                                <h6>Revenus Fixes Mensuels</h6>
                                <h3>€<?php echo number_format($totalMonthlyIncome, 2); ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-bg-danger h-100">
                            <div class="card-body text-center">
                                <h6>Dépenses Fixes Mensuelles</h6>
                                <h3>€<?php echo number_format($totalMonthlyExpenses, 2); ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100 <?php echo $netMonthly >= 0 ? 'text-bg-info' : 'text-bg-warning'; ?>">
                            <div class="card-body text-center">
                                <h6>Solde Net Mensuel</h6>
                                <h3>€<?php echo number_format($netMonthly, 2); ?></h3>
                                <small>Basé uniquement sur vos revenus et dépenses fixes</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <!-- Fixed Income List -->
                    <div class="col-md-6">
                        <h6 class="text-success mb-3">Revenus Fixes</h6>
                        <?php if (empty($activePayments)): ?>
                            <p class="text-muted">Aucun revenu fixe enregistré.</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Nom</th>
                                            <th>Fréquence</th>
                                            <th class="text-end">Montant</th>
                                            <th class="text-end">Mensuel</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach (array_slice($activePayments, 0, 4) as $payment): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($payment['name']); ?></td>
                                                <td><?php echo htmlspecialchars($payment['frequency']); ?></td>
                                                <td class="text-end">€<?php echo number_format($payment['amount'], 2); ?></td>
                                                <td class="text-end">€<?php echo number_format($payment['monthly_amount'], 2); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <?php if (count($activePayments) > 4): ?>
                                            <tr>
                                                <td colspan="4" class="text-center">
                                                    <a href="?action=fixed-payments" class="small">Voir tous (<?php echo count($activePayments); ?>)</a>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Fixed Expenses List -->
                    <div class="col-md-6">
                        <h6 class="text-danger mb-3">Dépenses Fixes</h6>
                        <?php if (empty($activeExpenses)): ?>
                            <p class="text-muted">Aucune dépense fixe enregistrée.</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Nom</th>
                                            <th>Fréquence</th>
                                            <th class="text-end">Montant</th>
                                            <th class="text-end">Mensuel</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach (array_slice($activeExpenses, 0, 4) as $expense): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($expense['name']); ?></td>
                                                <td><?php echo htmlspecialchars($expense['frequency']); ?></td>
                                                <td class="text-end">€<?php echo number_format($expense['amount'], 2); ?></td>
                                                <td class="text-end">€<?php echo number_format($expense['monthly_amount'], 2); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <?php if (count($activeExpenses) > 4): ?>
                                            <tr>
                                                <td colspan="4" class="text-center">
                                                    <a href="?action=fixed-payments" class="small">Voir tous (<?php echo count($activeExpenses); ?>)</a>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>