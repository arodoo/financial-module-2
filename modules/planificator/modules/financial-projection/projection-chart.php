<div class="card">
    <div class="card-header bg-light d-flex justify-content-between">
        <h5 class="mb-0">Visualisation Graphique</h5>
        <div id="chart-loading" class="spinner-border spinner-border-sm text-primary d-none" role="status">
            <span class="visually-hidden">Chargement...</span>
        </div>
    </div>
    <div class="card-body">
        <ul class="nav nav-tabs" id="chart-tabs" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" id="balance-tab" data-bs-toggle="tab" data-bs-target="#balance-chart-container" type="button" role="tab">
                    Évolution du solde
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="cash-flow-tab" data-bs-toggle="tab" data-bs-target="#cash-flow-chart-container" type="button" role="tab">
                    Flux de trésorerie
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="comparison-tab" data-bs-toggle="tab" data-bs-target="#comparison-chart-container" type="button" role="tab">
                    Revenus vs Dépenses
                </button>
            </li>
        </ul>
        
        <div class="tab-content" id="chartTabContent">
            <!-- Balance Evolution Chart -->
            <div class="tab-pane fade show active pt-4" id="balance-chart-container" role="tabpanel" aria-labelledby="balance-tab">
                <canvas id="balanceChart" height="300"></canvas>
            </div>
            
            <!-- Cash Flow Chart -->
            <div class="tab-pane fade pt-4" id="cash-flow-chart-container" role="tabpanel" aria-labelledby="cash-flow-tab">
                <canvas id="cashFlowChart" height="300"></canvas>
            </div>
            
            <!-- Income vs Expense Comparison Chart -->
            <div class="tab-pane fade pt-4" id="comparison-chart-container" role="tabpanel" aria-labelledby="comparison-tab">
                <canvas id="comparisonChart" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
/**
 * Initialize all charts with projection data
 * @param {Array} projectionData The financial projection data
 */
function initCharts(projectionData) {
    // Destroy existing charts if they exist
    Object.values(chartInstances).forEach(chart => chart.destroy && chart.destroy());
    chartInstances = {};
    
    // Create new charts
    initBalanceChart(projectionData);
    initCashFlowChart(projectionData);
    initComparisonChart(projectionData);
}

/**
 * Update all charts with new data
 * @param {Array} projectionData The updated financial projection data
 */
function updateCharts(projectionData) {
    // Update each chart with new data
    updateBalanceChart(projectionData);
    updateCashFlowChart(projectionData);
    updateComparisonChart(projectionData);
}

/**
 * Initialize the balance evolution chart
 * @param {Array} projectionData The financial projection data
 */
function initBalanceChart(projectionData) {
    const ctx = document.getElementById('balanceChart').getContext('2d');
    
    // Extract data for the chart
    const labels = projectionData.map(data => data.display_date);
    const balanceData = projectionData.map(data => data.balance);
    
    // Create gradient fill
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(54, 162, 235, 0.2)');
    gradient.addColorStop(1, 'rgba(54, 162, 235, 0)');
    
    // Create the chart
    chartInstances.balanceChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Solde',
                data: balanceData,
                backgroundColor: gradient,
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointRadius: 3,
                pointBackgroundColor: 'rgba(54, 162, 235, 1)'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + formatCurrency(context.parsed.y);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: false,
                    ticks: {
                        callback: function(value) {
                            return formatCurrency(value);
                        }
                    }
                }
            }
        }
    });
}

/**
 * Update the balance chart with new data
 * @param {Array} projectionData The updated financial projection data
 */
function updateBalanceChart(projectionData) {
    const chart = chartInstances.balanceChart;
    
    if (chart) {
        chart.data.labels = projectionData.map(data => data.display_date);
        chart.data.datasets[0].data = projectionData.map(data => data.balance);
        chart.update();
    } else {
        initBalanceChart(projectionData);
    }
}

/**
 * Initialize the cash flow chart
 * @param {Array} projectionData The financial projection data
 */
function initCashFlowChart(projectionData) {
    const ctx = document.getElementById('cashFlowChart').getContext('2d');
    
    // Extract data for the chart
    const labels = projectionData.map(data => data.display_date);
    const netFlowData = projectionData.map(data => data.net_flow);
    
    // Create the chart
    chartInstances.cashFlowChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Flux net',
                data: netFlowData,
                backgroundColor: netFlowData.map(value => value >= 0 ? 'rgba(75, 192, 192, 0.6)' : 'rgba(255, 99, 132, 0.6)'),
                borderColor: netFlowData.map(value => value >= 0 ? 'rgba(75, 192, 192, 1)' : 'rgba(255, 99, 132, 1)'),
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + formatCurrency(context.parsed.y);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: false,
                    ticks: {
                        callback: function(value) {
                            return formatCurrency(value);
                        }
                    }
                }
            }
        }
    });
}

/**
 * Update the cash flow chart with new data
 * @param {Array} projectionData The updated financial projection data
 */
function updateCashFlowChart(projectionData) {
    const chart = chartInstances.cashFlowChart;
    
    if (chart) {
        const netFlowData = projectionData.map(data => data.net_flow);
        
        chart.data.labels = projectionData.map(data => data.display_date);
        chart.data.datasets[0].data = netFlowData;
        chart.data.datasets[0].backgroundColor = netFlowData.map(value => 
            value >= 0 ? 'rgba(75, 192, 192, 0.6)' : 'rgba(255, 99, 132, 0.6)');
        chart.data.datasets[0].borderColor = netFlowData.map(value => 
            value >= 0 ? 'rgba(75, 192, 192, 1)' : 'rgba(255, 99, 132, 1)');
            
        chart.update();
    } else {
        initCashFlowChart(projectionData);
    }
}

/**
 * Initialize the income vs expense comparison chart
 * @param {Array} projectionData The financial projection data
 */
function initComparisonChart(projectionData) {
    const ctx = document.getElementById('comparisonChart').getContext('2d');
    
    // Extract data for the chart
    const labels = projectionData.map(data => data.display_date);
    const incomeData = projectionData.map(data => data.incomes);
    const expenseData = projectionData.map(data => data.expenses);
    
    // Create the chart
    chartInstances.comparisonChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Revenus',
                    data: incomeData,
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Dépenses',
                    data: expenseData,
                    backgroundColor: 'rgba(255, 99, 132, 0.6)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + formatCurrency(context.parsed.y);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return formatCurrency(value);
                        }
                    }
                }
            }
        }
    });
}

/**
 * Update the comparison chart with new data
 * @param {Array} projectionData The updated financial projection data
 */
function updateComparisonChart(projectionData) {
    const chart = chartInstances.comparisonChart;
    
    if (chart) {
        chart.data.labels = projectionData.map(data => data.display_date);
        chart.data.datasets[0].data = projectionData.map(data => data.incomes);
        chart.data.datasets[1].data = projectionData.map(data => data.expenses);
        chart.update();
    } else {
        initComparisonChart(projectionData);
    }
}
</script>
