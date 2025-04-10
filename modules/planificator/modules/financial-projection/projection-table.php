<div class="card">
    <div class="card-header bg-light d-flex justify-content-between">
        <h5 class="mb-0">Détails de la Projection</h5>
        <div id="table-loading" class="spinner-border spinner-border-sm text-primary d-none" role="status">
            <span class="visually-hidden">Chargement...</span>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="projection-table" class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Période</th>
                        <th class="text-end">Revenus</th>
                        <th class="text-end">Dépenses</th>
                        <th class="text-end">Flux Net</th>
                        <th class="text-end">Solde</th>
                    </tr>
                </thead>
                <tbody id="projection-table-body">
                    <!-- Table rows will be dynamically inserted here -->
                </tbody>
            </table>
        </div>
        
        <div class="text-center mb-3" id="pagination-container">
            <!-- Pagination will be inserted here -->
        </div>
        
        <div class="d-flex justify-content-between align-items-center">
            <div class="form-group d-flex align-items-center">
                <label for="rows-per-page" class="me-2">Lignes par page:</label>
                <select id="rows-per-page" class="form-select form-select-sm" style="width: auto;">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
            
            <!-- <button type="button" class="btn btn-sm btn-primary" id="export-csv">
                <i class="fas fa-file-csv me-1"></i> Exporter CSV
            </button> -->
        </div>
    </div>
</div>

<script>
const ROWS_PER_PAGE = 10;
let currentPage = 1;
let currentProjectionData = [];

/**
 * Update the projection table with new data
 * @param {Array} projectionData The financial projection data
 */
function updateProjectionTable(projectionData) {
    // Save data globally for pagination
    currentProjectionData = projectionData;
    
    // Reset pagination
    currentPage = 1;
    
    // Render the table
    renderProjectionTable();
    
    // Initialize event listeners
    initTableListeners();
}

/**
 * Render the projection table with pagination
 */
function renderProjectionTable() {
    const tableBody = document.getElementById('projection-table-body');
    const paginationContainer = document.getElementById('pagination-container');
    
    // Clear current content
    tableBody.innerHTML = '';
    
    // Calculate pagination
    const rowsPerPage = parseInt(document.getElementById('rows-per-page').value) || ROWS_PER_PAGE;
    const totalPages = Math.ceil(currentProjectionData.length / rowsPerPage);
    const startIndex = (currentPage - 1) * rowsPerPage;
    const endIndex = Math.min(startIndex + rowsPerPage, currentProjectionData.length);
    
    // Render table rows
    for (let i = startIndex; i < endIndex; i++) {
        const data = currentProjectionData[i];
        
        // Create table row
        const row = document.createElement('tr');
        
        // Determine balance class (positive/negative)
        const balanceClass = data.balance >= 0 ? 'text-success' : 'text-danger';
        const netFlowClass = data.net_flow >= 0 ? 'text-success' : 'text-danger';
        
        // Populate row cells
        row.innerHTML = `
            <td>${data.display_date}</td>
            <td class="text-end">${formatCurrency(data.incomes)}</td>
            <td class="text-end">${formatCurrency(data.expenses)}</td>
            <td class="text-end ${netFlowClass}">
                ${data.net_flow >= 0 ? '+' : ''}${formatCurrency(data.net_flow)}
            </td>
            <td class="text-end ${balanceClass}">
                ${formatCurrency(data.balance)}
            </td>
        `;
        
        tableBody.appendChild(row);
    }
    
    // Render pagination
    renderPagination(paginationContainer, totalPages);
}

/**
 * Render pagination controls
 * @param {HTMLElement} container The container for pagination controls
 * @param {Number} totalPages Total number of pages
 */
function renderPagination(container, totalPages) {
    if (totalPages <= 1) {
        container.innerHTML = '';
        return;
    }
    
    // Create pagination UI
    const pagination = document.createElement('nav');
    pagination.setAttribute('aria-label', 'Pagination de la table');
    
    const paginationList = document.createElement('ul');
    paginationList.className = 'pagination justify-content-center';
    
    // Previous button
    const prevItem = document.createElement('li');
    prevItem.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`;
    prevItem.innerHTML = `
        <a class="page-link" href="#" data-page="${currentPage - 1}" aria-label="Précédent">
            <span aria-hidden="true">&laquo;</span>
        </a>
    `;
    paginationList.appendChild(prevItem);
    
    // Page numbers
    const maxVisible = 5;
    let startPage = Math.max(1, currentPage - Math.floor(maxVisible / 2));
    const endPage = Math.min(startPage + maxVisible - 1, totalPages);
    
    // Adjust start page if we're near the end
    if (endPage - startPage < maxVisible - 1) {
        startPage = Math.max(1, endPage - maxVisible + 1);
    }
    
    // First page if not included in range
    if (startPage > 1) {
        const firstItem = document.createElement('li');
        firstItem.className = 'page-item';
        firstItem.innerHTML = `<a class="page-link" href="#" data-page="1">1</a>`;
        paginationList.appendChild(firstItem);
        
        // Ellipsis if needed
        if (startPage > 2) {
            const ellipsisItem = document.createElement('li');
            ellipsisItem.className = 'page-item disabled';
            ellipsisItem.innerHTML = '<a class="page-link" href="#">&hellip;</a>';
            paginationList.appendChild(ellipsisItem);
        }
    }
    
    // Generate page numbers
    for (let i = startPage; i <= endPage; i++) {
        const pageItem = document.createElement('li');
        pageItem.className = `page-item ${i === currentPage ? 'active' : ''}`;
        pageItem.innerHTML = `<a class="page-link" href="#" data-page="${i}">${i}</a>`;
        paginationList.appendChild(pageItem);
    }
    
    // Last page if not included in range
    if (endPage < totalPages) {
        // Ellipsis if needed
        if (endPage < totalPages - 1) {
            const ellipsisItem = document.createElement('li');
            ellipsisItem.className = 'page-item disabled';
            ellipsisItem.innerHTML = '<a class="page-link" href="#">&hellip;</a>';
            paginationList.appendChild(ellipsisItem);
        }
        
        const lastItem = document.createElement('li');
        lastItem.className = 'page-item';
        lastItem.innerHTML = `<a class="page-link" href="#" data-page="${totalPages}">${totalPages}</a>`;
        paginationList.appendChild(lastItem);
    }
    
    // Next button
    const nextItem = document.createElement('li');
    nextItem.className = `page-item ${currentPage === totalPages ? 'disabled' : ''}`;
    nextItem.innerHTML = `
        <a class="page-link" href="#" data-page="${currentPage + 1}" aria-label="Suivant">
            <span aria-hidden="true">&raquo;</span>
        </a>
    `;
    paginationList.appendChild(nextItem);
    
    pagination.appendChild(paginationList);
    container.innerHTML = '';
    container.appendChild(pagination);
}

/**
 * Initialize table event listeners
 */
function initTableListeners() {
    // Pagination click events
    document.getElementById('pagination-container').addEventListener('click', function(e) {
        e.preventDefault();
        
        // Find the clicked page link
        const pageLink = e.target.closest('[data-page]');
        if (!pageLink || pageLink.parentNode.classList.contains('disabled')) {
            return;
        }
        
        // Update current page and re-render
        currentPage = parseInt(pageLink.dataset.page);
        renderProjectionTable();
    });
    
    // Rows per page change event
    document.getElementById('rows-per-page').addEventListener('change', function() {
        // Reset to first page and re-render
        currentPage = 1;
        renderProjectionTable();
    });
    
    // Export CSV functionality
    //document.getElementById('export-csv').addEventListener('click', exportProjectionToCsv);
}

/**
 * Export projection data to CSV
 */
function exportProjectionToCsv() {
    if (!currentProjectionData || !currentProjectionData.length) {
        return;
    }
    
    // Prepare CSV header row
    let csvContent = "data:text/csv;charset=utf-8,Période,Revenus,Dépenses,Flux Net,Solde\n";
    
    // Add data rows
    currentProjectionData.forEach(row => {
        const csvRow = [
            row.display_date,
            row.incomes,
            row.expenses,
            row.net_flow,
            row.balance
        ].join(',');
        
        csvContent += csvRow + '\n';
    });
    
    // Create download link and trigger download
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement('a');
    link.setAttribute('href', encodedUri);
    link.setAttribute('download', `projection-financiere-${new Date().toISOString().slice(0, 10)}.csv`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>
