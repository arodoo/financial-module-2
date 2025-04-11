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
        </ul>          <div class="tab-content" id="chartTabContent">            <!-- Balance Evolution Chart -->
            <div class="tab-pane fade show active pt-3" id="balance-chart-container" role="tabpanel" aria-labelledby="balance-tab">
                <div class="chart-responsive-container">
                    <canvas id="balanceChart"></canvas>
                </div>
            </div>
            
            <!-- Cash Flow Chart -->
            <div class="tab-pane fade pt-3" id="cash-flow-chart-container" role="tabpanel" aria-labelledby="cash-flow-tab">
                <div class="chart-responsive-container">
                    <canvas id="cashFlowChart"></canvas>
                </div>
            </div>
            
            <!-- Income vs Expense Comparison Chart -->
            <div class="tab-pane fade pt-3" id="comparison-chart-container" role="tabpanel" aria-labelledby="comparison-tab">
                <div class="chart-responsive-container">
                    <canvas id="comparisonChart"></canvas>
                </div>
            </div>
        </div>    </div>
</div>

<style>
    /* Responsive chart styling with viewport units */
    .tab-pane {
        position: relative;
        overflow: hidden; /* Prevent overflow */
    }
    
    .chart-responsive-container {
        position: relative;
        width: 100%;
        height: 45vh; /* Use viewport height instead of fixed pixels */
        max-height: 450px; /* Maximum height */
        min-height: 200px; /* Minimum height */
    }
    
    /* Adjust for different screen sizes */
    @media (max-width: 992px) {
        .chart-responsive-container {
            height: 40vh;
        }
    }
    
    @media (max-width: 768px) {
        .chart-responsive-container {
            height: 35vh; /* Take up more vertical space on mobile */
            max-height: 350px;
        }
    }
    
    @media (max-width: 576px) {
        .chart-responsive-container {
            height: 45vh; /* Even more space on small phones */
            min-height: 250px;
        }
    }
</style>

<script>
// Dynamic aspect ratio based on screen size
function getDynamicAspectRatio() {
    const width = window.innerWidth;
    if (width < 576) return 1.2; // Small phones
    if (width < 768) return 1.5; // Mobile
    if (width < 992) return 1.8; // Tablets
    return 2.2; // Desktop
}

// Enhanced responsive options for charts
function getResponsiveChartOptions(baseOptions) {
    return {
        ...baseOptions,
        responsive: true,
        maintainAspectRatio: false, // Let the container control size
        plugins: {
            ...baseOptions.plugins,
            legend: {
                ...baseOptions.plugins?.legend,
                position: 'top',
                labels: {
                    boxWidth: window.innerWidth < 768 ? 12 : 20,
                    padding: window.innerWidth < 768 ? 8 : 10,
                    font: {
                        size: window.innerWidth < 768 ? 10 : 12
                    }
                }
            }
        }
    };
}

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
      // Create the chart with improved responsive options
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
        options: getResponsiveChartOptions({
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
        })
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
      // Create the chart with improved responsive options
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
        options: getResponsiveChartOptions({
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
        })
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
      // Create the chart with improved responsive options
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
        options: getResponsiveChartOptions({
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
        })
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
