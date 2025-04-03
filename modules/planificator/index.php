<?php
/* error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__) . '/debug.log'); */
if (!empty($_SESSION['4M8e7M5b1R2e8s']) || !empty($user)) {

    require_once 'config/config.php';
    require_once 'models/Membre.php';

    // Get current membre info
    $membreModel = new Membre();
    $currentMembre = $membreModel->getMembre($id_oo);

    // Define available modules and their titles
    $modules = [
    'dashboard' => 'Tableau de bord',
    'income-expense' => 'Revenus & Dépenses',     
    'fixed-payments' => 'Paiements fixes',
    'asset-management' => 'Gestion des actifs',
    'financial-projection' => 'Financière Projection',
    'loan-simulator' => 'Simulateur de prêt',
    'school-fee' => 'Frais scolaires'
];

    // Route requests based on the action parameter
    $action = isset($_GET['action']) ? $_GET['action'] : 'dashboard';

    // Validate action
    if (!array_key_exists($action, $modules)) {
        $action = 'dashboard';
    }
    ?>

    
    <link rel="stylesheet" href="/modules/planificator/modules/modules.css">

    <nav id="main-nav" class="navbar navbar-expand-lg navbar-dark bg-primary mb-4 border rounded">
        <div class="container">
            <a class="navbar-brand" href="#">Système de Gestion Financière</a>
            <!-- Membre indicator -->
            <span class="navbar-text me-3 text-white">
                <?php if ($currentMembre): ?>
                <?php else: ?>
                    ID Membre: <?php echo $id_oo; ?>
                <?php endif; ?>
            </span>
        </div>
    </nav>

    <div class="container">
        <div class=" content-card">
            <div class="card-header bg-light">
                <h2 class="card-title h4 my-2"><?php echo $modules[$action]; ?></h2>
            </div>
            <div class="card-body">
               <!--  <div class="row">
                    <div class="col-12">
                        
                    </div>
                </div> -->

                <?php
                // Display content based on action
                switch ($action) {
                    case 'dashboard':
                        // Include the dashboard module instead of hardcoded content
                        include 'modules/dashboard/index.php';
                        break;
                    case 'income-expense':
                        // Include the income-expense module
                        include 'modules/income-expense/index.php';
                        break;
                    case 'fixed-payments':
                        // Include the income-expense module
                        include 'modules/fixed-payments/index.php';
                        break;
                    case 'asset-management':
                        // Include the asset-management module
                        include 'modules/asset-management/index.php';
                        break;
                    case 'financial-projection': 
                        // Add this case to match the URL in the menu
                        include 'modules/financial-projection/index.php';
                        break;
                    /* case 'simulation':
                        // Include the loan simulator module
                        include 'modules/loan-simulator/index.php';
                        break;
                    case 'frais-scolaires':
                        include 'modules/school-fee-simulator/index.php';
                        break; */
                }
                ?>
            </div>
        </div>
    </div>

    <?php
} else {
    header("location: /");
}
?>