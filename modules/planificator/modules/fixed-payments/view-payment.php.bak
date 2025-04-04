<?php
    if (!isset($viewData['viewPayment']) || empty($viewData['viewPayment'])) {
        echo '<div class="alert alert-danger">Payment not found.</div>';
        exit;
    }
    
    $payment = $viewData['viewPayment'];
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">View Payment: <?php echo htmlspecialchars($payment['name']); ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="?module=fixed-payments">Fixed Payments</a></li>
        <li class="breadcrumb-item active">View Payment</li>
    </ol>
    
    <?php if (isset($success)): ?>
        <div class="alert alert-success">
            <?php echo $success; ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>
    
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-info-circle me-1"></i>
                    Payment Details
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <dl class="row">
                                <dt class="col-sm-4">Name</dt>
                                <dd class="col-sm-8"><?php echo htmlspecialchars($payment['name']); ?></dd>
                                
                                <dt class="col-sm-4">Category</dt>
                                <dd class="col-sm-8"><?php echo htmlspecialchars($payment['category_name']); ?></dd>
                                
                                <dt class="col-sm-4">Amount</dt>
                                <dd class="col-sm-8">
                                    <span class="text-danger">
                                        <?php echo number_format($payment['amount'], 2); ?> €
                                    </span>
                                </dd>
                                
                                <dt class="col-sm-4">Payment Day</dt>
                                <dd class="col-sm-8"><?php echo $payment['payment_day']; ?> of each month</dd>
                            </dl>
                        </div>
                        <div class="col-md-6">
                            <dl class="row">
                                <dt class="col-sm-4">Frequency</dt>
                                <dd class="col-sm-8"><?php echo ucfirst(htmlspecialchars($payment['frequency'])); ?></dd>
                                
                                <dt class="col-sm-4">Next Due Date</dt>
                                <dd class="col-sm-8">
                                    <?php 
                                        $nextDate = new DateTime($payment['next_due_date']);
                                        $today = new DateTime();
                                        $interval = $today->diff($nextDate);
                                        $daysRemaining = $interval->format('%R%a');
                                        
                                        $badgeClass = 'bg-success';
                                        if ($daysRemaining < 0) {
                                            $badgeClass = 'bg-danger';
                                        } elseif ($daysRemaining < 7) {
                                            $badgeClass = 'bg-warning';
                                        }
                                    ?>
                                    <?php echo date('Y-m-d', strtotime($payment['next_due_date'])); ?>
                                    <span class="badge <?php echo $badgeClass; ?>">
                                        <?php 
                                            if ($daysRemaining < 0) {
                                                echo abs($daysRemaining) . " days overdue";
                                            } elseif ($daysRemaining == 0) {
                                                echo "Today";
                                            } else {
                                                echo $daysRemaining . " days left";
                                            }
                                        ?>
                                    </span>
                                </dd>
                                
                                <dt class="col-sm-4">Created</dt>
                                <dd class="col-sm-8"><?php echo date('Y-m-d', strtotime($payment['created_at'])); ?></dd>
                                
                                <?php if (!empty($payment['updated_at']) && $payment['updated_at'] !== $payment['created_at']): ?>
                                    <dt class="col-sm-4">Last Updated</dt>
                                    <dd class="col-sm-8"><?php echo date('Y-m-d', strtotime($payment['updated_at'])); ?></dd>
                                <?php endif; ?>
                            </dl>
                        </div>
                    </div>
                    
                    <?php if (!empty($payment['notes'])): ?>
                        <div class="row mt-3">
                            <div class="col-12">
                                <h5>Notes</h5>
                                <p><?php echo nl2br(htmlspecialchars($payment['notes'])); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <a href="?module=fixed-payments&view=edit&edit_payment=<?php echo $payment['id']; ?>" class="btn btn-primary me-2">
                                <i class="fas fa-edit"></i> Edit Payment
                            </a>
                            <button class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#markPaidModal">
                                <i class="fas fa-check"></i> Mark as Paid
                            </button>
                            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                <i class="fas fa-trash"></i> Delete Payment
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete the payment <strong><?php echo htmlspecialchars($payment['name']); ?></strong>?
            </div>
            <div class="modal-footer">
                <form method="post">
                    <input type="hidden" name="action" value="delete_payment">
                    <input type="hidden" name="payment_id" value="<?php echo $payment['id']; ?>">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Mark Paid Modal -->
<div class="modal fade" id="markPaidModal" tabindex="-1" aria-labelledby="markPaidModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="markPaidModalLabel">Mark Payment as Paid</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" id="mark-paid-form">
                    <input type="hidden" name="action" value="mark_paid">
                    <input type="hidden" name="payment_id" value="<?php echo $payment['id']; ?>">
                    
                    <div class="mb-3">
                        <label for="payment_date" class="form-label">Payment Date</label>
                        <input type="date" class="form-control" id="payment_date" name="payment_date" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="mark-paid-form" class="btn btn-success">Mark as Paid</button>
            </div>
        </div>
    </div>
</div>
