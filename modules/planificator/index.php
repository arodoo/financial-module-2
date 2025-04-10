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
        'fixed-payments' => 'Ressources & Charges Fixes ',
        'asset-management' => 'Gestion des actifs',
        'financial-projection' => 'Financière Projection',
        'loan-simulator' => 'Simulateur de prêt',
        'school-fee' => 'Frais scolaires',
    ];

    // Add personalized descriptions for each module
    $moduleDescriptions = [
        'dashboard' => 'Vue d\'ensemble de votre situation financière',
        'income-expense' => 'Gérez et suivez vos revenus et dépenses',
        'fixed-payments' => 'Configurez vos ressources et charges récurrentes',
        'asset-management' => 'Suivez la valeur et l\'évolution de vos actifs',
        'financial-projection' => 'Planifiez votre avenir financier',
        'loan-simulator' => 'Simulez vos emprunts et calculez vos mensualités',
        'school-fee' => 'Planifiez les frais de scolarité de vos enfants',
    ];

    $action = isset($_GET['action']) ? $_GET['action'] : 'dashboard';

    if (!array_key_exists($action, $modules)) {
        $action = 'dashboard';
    }
    ?>

    <link rel="stylesheet" href="/modules/planificator/modules/modules.css">

    <nav id="main-nav" class="navbar navbar-expand-lg navbar-dark bg-primary mb-4 border rounded">
        <div class="container">
            <a class="navbar-brand" href="#">Planification et gestion financière pour la Famile</a>
        </div>
    </nav>
    <div class="">
        <div class="alert text-center" role="alert" style="background-color: #87CEEB; color: white;">
            <span><?php echo $moduleDescriptions[$action]; ?></span>
        </div>
    </div>

    <div class="container fixed-container">
        <div class=" content-card">
            <div class="card-header bg-light">
                <h2 class="card-title h4 my-2"><?php echo $modules[$action]; ?></h2>
            </div>
            <div class="card-body fixed-card-body">

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