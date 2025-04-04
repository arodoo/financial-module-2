<?php
    // Calculate default month range (current month)
    $currentMonth = date('Y-m');
    $startDate = $currentMonth . '-01';
    $endDate = date('Y-m-t', strtotime($startDate));
    
    // Get month and year from GET parameters if available
    if (isset($_GET['month']) && isset($_GET['year'])) {
        $month = intval($_GET['month']);
        $year = intval($_GET['year']);
        if ($month >= 1 && $month <= 12 && $year >= 2000 && $year <= 2100) {
            $startDate = sprintf('%04d-%02d-01', $year, $month);
            $endDate = date('Y-m-t', strtotime($startDate));
        }
    }
    
    // Parse dates to get month and year
    $selectedMonth = date('n', strtotime($startDate));
    $selectedYear = date('Y', strtotime($startDate));
    
    // Get payments for the selected month
    $payments = $paymentController->getPaymentsForCalendar($startDate, $endDate);
    
    // Organize payments by date
    $paymentsByDate = [];
    foreach ($payments as $payment) {
        $dueDate = date('Y-m-d', strtotime($payment['next_due_date']));
        if (!isset($paymentsByDate[$dueDate])) {
            $paymentsByDate[$dueDate] = [];
        }
        $paymentsByDate[$dueDate][] = $payment;
    }
    
    // For monthly recurring payments, generate all occurrences in the month
    foreach ($payments as $payment) {
        if ($payment['frequency'] == 'monthly' && $payment['status'] == 'active') {
            // If the payment is monthly, we need to check if it falls on the same day each month
            $paymentDay = date('d', strtotime($payment['next_due_date']));
            $paymentDateInSelectedMonth = sprintf('%04d-%02d-%02d', $selectedYear, $selectedMonth, $paymentDay);
            
            // Validate the date (handle cases like 31st in 30-day months)
            $validatedDate = date('Y-m-d', strtotime($paymentDateInSelectedMonth));
            
            // Add to payments by date if it's in the selected month and not already included
            if (date('m', strtotime($validatedDate)) == $selectedMonth && 
                date('Y', strtotime($validatedDate)) == $selectedYear &&
                $validatedDate != $dueDate) {
                
                if (!isset($paymentsByDate[$validatedDate])) {
                    $paymentsByDate[$validatedDate] = [];
                }
                
                // Mark this as a recurring instance
                $payment['is_recurring_instance'] = true;
                $paymentsByDate[$validatedDate][] = $payment;
            }
        }
    }
    
    // Previous and next month links
    $prevMonth = $selectedMonth - 1;
    $prevYear = $selectedYear;
    if ($prevMonth < 1) {
        $prevMonth = 12;
        $prevYear--;
    }
    
    $nextMonth = $selectedMonth + 1;
    $nextYear = $selectedYear;
    if ($nextMonth > 12) {
        $nextMonth = 1;
        $nextYear++;
    }
    
    // Month name
    $monthName = date('F', strtotime($startDate));
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Payment Calendar: <?php echo $monthName . ' ' . $selectedYear; ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="?module=fixed-payments">Fixed Payments</a></li>
        <li class="breadcrumb-item active">Calendar View</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-calendar me-1"></i>
                Calendar View
            </div>
            <div>
                <a href="?module=fixed-payments&view=calendar&month=<?php echo $prevMonth; ?>&year=<?php echo $prevYear; ?>" class="btn btn-sm btn-outline-secondary me-2">
                    <i class="fas fa-chevron-left"></i> Previous Month
                </a>
                <a href="?module=fixed-payments&view=calendar&month=<?php echo date('n'); ?>&year=<?php echo date('Y'); ?>" class="btn btn-sm btn-outline-primary me-2">
                    Current Month
                </a>
                <a href="?module=fixed-payments&view=calendar&month=<?php echo $nextMonth; ?>&year=<?php echo $nextYear; ?>" class="btn btn-sm btn-outline-secondary">
                    Next Month <i class="fas fa-chevron-right"></i>
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered calendar-table">
                    <thead>
                        <tr>
                            <th>Sunday</th>
                            <th>Monday</th>
                            <th>Tuesday</th>
                            <th>Wednesday</th>
                            <th>Thursday</th>
                            <th>Friday</th>
                            <th>Saturday</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            // Get the first day of the month
                            $firstDayOfMonth = date('w', strtotime($startDate)); // 0 (Sunday) to 6 (Saturday)
                            
                            // Get the number of days in the month
                            $daysInMonth = date('t', strtotime($startDate));
                            
                            // Generate the calendar
                            $day = 1;
                            $calendar = '';
                            
                            // Generate rows until all days are displayed
                            while ($day <= $daysInMonth) {
                                $calendar .= '<tr>';
                                
                                // Fill in each day of the week
                                for ($i = 0; $i < 7; $i++) {
                                    if (($day == 1 && $i < $firstDayOfMonth) || ($day > $daysInMonth)) {
                                        $calendar .= '<td class="empty-day"></td>';
                                    } else {
                                        $currentDate = sprintf('%04d-%02d-%02d', $selectedYear, $selectedMonth, $day);
                                        $isToday = ($currentDate == date('Y-m-d'));
                                        $tdClass = $isToday ? 'today' : '';
                                        
                                        $calendar .= '<td class="' . $tdClass . '">';
                                        $calendar .= '<div class="date-header">' . $day . '</div>';
                                        
                                        // Check if there are payments on this day
                                        if (isset($paymentsByDate[$currentDate])) {
                                            $calendar .= '<div class="payment-list">';
                                            foreach ($paymentsByDate[$currentDate] as $payment) {
                                                $paymentTypeClass = $payment['payment_type'] == 'income' ? 'bg-success' : 'bg-danger';
                                                $recurringBadge = isset($payment['is_recurring_instance']) ? 
                                                    '<span class="badge bg-info ms-1" title="Recurring payment"><i class="fas fa-sync-alt"></i></span>' : '';
                                                
                                                $calendar .= '<div class="payment-item ' . $paymentTypeClass . '">';
                                                $calendar .= '<a href="?module=fixed-payments&view=view&view_payment=' . $payment['id'] . '" class="text-white">';
                                                $calendar .= htmlspecialchars($payment['name']) . ' ' . $recurringBadge . '<br>';
                                                $calendar .= '<small>' . number_format($payment['amount'], 2) . ' €</small>';
                                                $calendar .= '</a>';
                                                $calendar .= '</div>';
                                            }
                                            $calendar .= '</div>';
                                        }
                                        
                                        $calendar .= '</td>';
                                        $day++;
                                    }
                                }
                                
                                $calendar .= '</tr>';
                                
                                // Break if all days have been displayed
                                if ($day > $daysInMonth) {
                                    break;
                                }
                            }
                            
                            echo $calendar;
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-list me-1"></i>
            Payments This Month
        </div>
        <div class="card-body">
            <?php if (empty($payments)): ?>
                <div class="alert alert-info">
                    No payments scheduled for this month.
                </div>
            <?php else: ?>
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="text-success mb-3">Income</h5>
                        <ul class="list-group mb-4">
                            <?php 
                                $incomeFound = false;
                                foreach ($payments as $payment) {
                                    if ($payment['payment_type'] == 'income') {
                                        $incomeFound = true;
                                        echo '<li class="list-group-item">';
                                        echo '<div class="d-flex w-100 justify-content-between">';
                                        echo '<h6 class="mb-1"><a href="?module=fixed-payments&view=view&view_payment=' . $payment['id'] . '">' . htmlspecialchars($payment['name']) . '</a></h6>';
                                        echo '<span class="text-success">' . number_format($payment['amount'], 2) . ' €</span>';
                                        echo '</div>';
                                        echo '<p class="mb-1 small">Due on: ' . date('Y-m-d', strtotime($payment['next_due_date'])) . '</p>';
                                        echo '</li>';
                                    }
                                }
                                if (!$incomeFound) {
                                    echo '<li class="list-group-item">No income payments this month.</li>';
                                }
                            ?>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h5 class="text-danger mb-3">Expenses</h5>
                        <ul class="list-group">
                            <?php 
                                $expenseFound = false;
                                foreach ($payments as $payment) {
                                    if ($payment['payment_type'] == 'expense') {
                                        $expenseFound = true;
                                        echo '<li class="list-group-item">';
                                        echo '<div class="d-flex w-100 justify-content-between">';
                                        echo '<h6 class="mb-1"><a href="?module=fixed-payments&view=view&view_payment=' . $payment['id'] . '">' . htmlspecialchars($payment['name']) . '</a></h6>';
                                        echo '<span class="text-danger">' . number_format($payment['amount'], 2) . ' €</span>';
                                        echo '</div>';
                                        echo '<p class="mb-1 small">Due on: ' . date('Y-m-d', strtotime($payment['next_due_date'])) . '</p>';
                                        echo '</li>';
                                    }
                                }
                                if (!$expenseFound) {
                                    echo '<li class="list-group-item">No expense payments this month.</li>';
                                }
                            ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    .calendar-table {
        table-layout: fixed;
    }
    .calendar-table th {
        text-align: center;
        background-color: #f8f9fa;
    }
    .calendar-table td {
        height: 120px;
        vertical-align: top;
        padding: 5px;
    }
    .calendar-table td.empty-day {
        background-color: #f8f9fa;
    }
    .calendar-table td.today {
        background-color: #fffde7;
    }
    .date-header {
        font-weight: bold;
        margin-bottom: 5px;
    }
    .payment-list {
        overflow-y: auto;
        max-height: 90px;
    }
    .payment-item {
        padding: 3px 6px;
        margin-bottom: 3px;
        border-radius: 3px;
        font-size: 0.85em;
    }
    .payment-item a {
        text-decoration: none;
    }
</style>