<?php
// Check if required files exist before including them
if (!file_exists('Configurations_bdd.php')) {
	die('Configurations_bdd.php not found');
}
if (!file_exists('Configurations_modules.php')) {
	die('Configurations_modules.php not found');
}
if (!file_exists('Configurations.php')) {
	die('Configurations.php not found');
}

////INCLUDES CONFIGURATIONS CMS CODI ONE
require_once('Configurations_bdd.php');
require_once('Configurations_modules.php');
require_once('Configurations.php');

////INCLUDE FUNCTION HAUT CMS CODI ONE
if (!file_exists('function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php')) {
	die('function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php not found');
}
include('function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php');

/*****************************************************\
 * Adresse e-mail => direction@codi-one.fr             *
 * La conception est assujettie à une autorisation     *
 * spéciale de codi-one.com. Si vous ne disposez pas de*
 * cette autorisation, vous êtes dans l'illégalité.    *
 * L'auteur de la conception est et restera            *
 * codi-one.fr                                         *
 * Codage, script & images (all contenu) sont réalisés * 
 * par codi-one.fr                                     *
 * La conception est à usage unique et privé.          *
 * La tierce personne qui utilise le script se porte   *
 * garante de disposer des autorisations nécessaires   *
 *                                                     *
 * Copyright ... Tous droits réservés auteur (Fabien B)*
\*****************************************************/
?>

<!DOCTYPE html>
<html lang="fr">

<head>
	<meta charset="utf-8">
	<meta name="description" content="<?php echo str_replace('"', '', $Metas_description_page); ?>">
	<meta name="keywords" content="<?php echo str_replace('"', '', $Metas_mots_cles_page); ?>">
	<meta name="viewport" content="initial-scale=1, width=device-width">

	<!-- Favicon Icon -->
	<link rel="shortcut icon" type="image/x-icon" href="/images/logo/image-logo.png?v=1">

	<?php
	////////////////////SI CONNECTE 
	if (!empty($user)) {
	?>
		<link rel="stylesheet" href="/template/assets/css/style3.css?v=8">
		<link rel="stylesheet" id="layoutstyle" href="/template/assets/color/theme-orange.css?v=20">
		<link rel="stylesheet" href="/template/demo-restaurant/css/demo-restaurant.css?v=4">

		<link rel="stylesheet" href="/vendor/chartist/css/chartist.min.css?v=8">
		<link href="/vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
		<link href="/vendor/owl-carousel/owl.carousel.css" rel="stylesheet">
		<link href="/vendor/jquery-smartwizard/dist/css/smart_wizard.min.css" rel="stylesheet">
		<link href="/vendor/datatables/responsive/responsive.css" rel="stylesheet">
		<link rel="stylesheet" href="/vendor/toastr/css/toastr.min.css">
		<link href="/css/style-dashboard.css?v=8" rel="stylesheet">
		<link href="/css/custom-style.css?v=<?php echo time(); ?>" rel="stylesheet">

	<?php
	} else {
	?>

		<link rel="stylesheet" href="/template/assets/bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" href="/template/assets/css/ionicons.min.css?v=2">
		<link rel="stylesheet" href="/template/assets/css/themify-icons.css">
		<link rel="stylesheet" href="/template/assets/css/all.min.css">
		<link rel="stylesheet" href="/template/assets/css/style3.css?v=8">
		<link rel="stylesheet" href="/template/assets/css/responsive.css">
		<link rel="stylesheet" id="layoutstyle" href="/template/assets/color/theme-orange.css?v=20">
		<link rel="stylesheet" href="/template/demo-restaurant/css/demo-restaurant.css?v=4">
		<link href="/css/custom-style.css?v=<?php echo time(); ?>" rel="stylesheet">

	<?php
	}

	if (empty($_GET['page'])) {
	?>
		<meta property="og:type" content="website" />
		<meta property="og:url" content="https://zen-famili.com/" />
		<meta property="og:image" content="https://zen-famili.com/images/Page d'accueil.jpg" />
	<?php
	}
	?>

	<?php

	////////////////////SI CONNECTE 
	if (!empty($user)) {

		////INCLUDE JS BAS CMS CODI ONE
		include('js/INCLUDE-JS-HAUT-CMS-CODI-ONE-dashboard.php');
	} else {
		////INCLUDE JS BAS CMS CODI ONE
		include('js/INCLUDE-JS-HAUT-CMS-CODI-ONE.php');
	}

	////TITLE

	if (!empty($_GET['page'])) {
		echo "<title>$TitreTitrea_page | Zen Famili</title>";
	} else {
		echo "<title>$TitreTitrea_page</title>";
	}

	?>

</head>

<body>

	<?php
	////INCLUDE POP-UP HAUT CMS CODI ONE
	include('pop-up/INCLUDE-POP-UP-HAUT-CMS-CODI-ONE.php');

	////////////////////SI CONNECTE 
	if (!empty($user)) {
	?>

		<div id="preloader">
			<div class="preloader">
				<div class="loader_grid">
					<div class="loader_box loader_box1"></div>
					<div class="loader_box loader_box2"></div>
					<div class="loader_box loader_box3"></div>
					<div class="loader_box loader_box4"></div>
					<div class="loader_box loader_box5"></div>
					<div class="loader_box loader_box6"></div>
					<div class="loader_box loader_box7"></div>
					<div class="loader_box loader_box8"></div>
					<div class="loader_box loader_box9"></div>
				</div>
			</div>
		</div>

		<div id="main-wrapper" class="show">

			<div class="nav-header">
				<a href="/" class="brand-logo">
					<img class="logo_light" src="/images/logo/image-logo.png" alt="Zen Famili" width="100" />
					<img class="logo_light" src="/images/logo/image-logo.png" alt="Zen Famili" width="100" />
					<img class="logo_light" src="/images/logo/image-logo.png" alt="Zen Famili" width="100" />
					<img class="logo-abbr" src="/images/logo/image-logo.png" alt="Zen Famili" width="100">
				</a>

				<div class="nav-control">
					<div class="hamburger">
						<span class="line"></span><span class="line"></span><span class="line"></span>
					</div>
				</div>
			</div>
		<?php
		////MENU
		include('index-menu-alertes-notes-messages-dashboard.php');
		////MENU
		include('index-menu-horizontal-dashboard.php');
		////MENU
		include('index-menu-lateral-dashboard.php');
	} else {

		?>
			<!-- LOADER -->
			<div class="preloader">
				<div class="loader_grid">
					<div class="loader_box loader_box1"></div>
					<div class="loader_box loader_box2"></div>
					<div class="loader_box loader_box3"></div>
					<div class="loader_box loader_box4"></div>
					<div class="loader_box loader_box5"></div>
					<div class="loader_box loader_box6"></div>
					<div class="loader_box loader_box7"></div>
					<div class="loader_box loader_box8"></div>
					<div class="loader_box loader_box9"></div>
				</div>
			</div>
			<!-- END LOADER -->

			<?php

			////MENU
			include('index-menu.php');
			////HEADER
			include('index-header.php');
		}


		////////////////////SI CONNECTE 
		if (!empty($user)) {

			if (empty($_GET['page'])) {
			?>
				<div class="content-body default-height">
					<div class="container" style="margin-top: 40px;">

						<?php
						include('index-fil-ariane.php');
						?>

						<div class="row">
							<div class="col-12">
								<div class="card">
									<div class="card-body">
										<?php
										// Only redirect if we're not already coming from a redirect
										// This prevents redirect loops
										$fromRedirect = isset($_GET['from_redirect']) && $_GET['from_redirect'] == '1';

										if (!empty($user) && !$fromRedirect) {
											echo '<script>window.location.href = "/Planificator/dashboard";;</script>';
											// Use JavaScript redirect instead of PHP header to avoid buffering issues
										} else if ($fromRedirect) {
											echo '<div class="alert alert-warning">';
											echo 'Unable to access the Financial Planning module. Please check your session status.';
											echo '<p><a href="/Planificator?debug_auth=1" class="btn btn-primary mt-3">Try Again</a></p>';
											echo '</div>';
										}
										?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php
			} else {
			?>
				<div class="content-body default-height">
					<div class="container" style="margin-top: 40px;">

						<?php
						include('index-fil-ariane.php');
						?>

						<div class="row">
							<div class="col-12">
								<div class="card">
									<div class="card-body">
										<?php
										include('pages.php');
										?>
									</div>

								</div>
							</div>
						</div>
					</div>
				</div>

				<?php
			}
		} else {

			////////////////////SI PAS CONNECTE 
			if ($p404_existe != "oui") {
				////PAGE BANDEAU
				page_bandeaux();
				////SWITCH DES PAGES
				if (isset($_GET['page']) && $_GET['page'] != "Referencer-un-etablissement" && $_GET['page'] != "Fiche" && $_GET['page'] != "Plateforme" && $_GET['page'] != "Plateforme2") {


					if ($_GET['page'] == "Extra" || $_GET['page'] == "Pro") {
						include('pages.php');
					} else {
				?>
						<div class="container" style="margin-top: 40px;">
							<?php
							include('pages.php');
							?>
						</div>
			<?php

					}
				} else {
					include('pages.php');
				}
				////SWITCH DES PAGES
			} elseif ($p404_existe == "oui") {
				include("function/404/404r.php");
			}
		}

		////////////////////SI CONNECTE 
		if (!empty($user)) {
			////FOOTER
			include('index-footer2.php');
		} else {
			////FOOTER
			include('index-footer.php');
		}

		////////////////////SI CONNECTE 
		if (!empty($user)) {
			?>

			<script src="/vendor/global/global.min.js"></script>

			<script src="/vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
			<script src="/vendor/owl-carousel/owl.carousel.js"></script>

			<!-- Chart piety plugin files -->
			<script src="/vendor/peity/jquery.peity.min.js"></script>

			<script src="/vendor/toastr/js/toastr.min.js"></script>

			<script src="/vendor/bootstrap-datepicker-master/js/bootstrap-datepicker.min.js"></script>
			<script src="/js/dashboard/cms.js"></script>

			<script src="/vendor/jquery-smartwizard/dist/js/jquery.smartWizard.js"></script>

			<script src="/js/custom.js?v=<?php echo time(); ?>"></script>
			<script src="/js/deznav-init.js?v=<?php echo time(); ?>"></script>

			<script src="/js/bootstrap/bootstrap-modal.js?v=1"></script>

			<!-- <script src="/js/datepicker-fr.js?v=1"></script> -->


		<?php
		} else {
		?>

			<script src="/js/bootstrap/bootstrap-modal.js?v=1"></script>

		<?php
		}

		////INCLUDE CSS BAS CMS CODI ONE
		include('css/INCLUDE-CSS-BAS-CMS-CODI-ONE.php');
		////INCLUDE JS BAS CMS CODI ONE
		include('js/INCLUDE-JS-BAS-CMS-CODI-ONE.php');
		////INCLUDE POP-UP BAS CMS CODI ONE
		include('pop-up/INCLUDE-POP-UP-BAS-CMS-CODI-ONE.php');

		////////////////////SI CONNECTE 
		if (!empty($user)) {
		?>

		<?php
		} else {
		?>

			<!--- owl carousel CSS-->
			<link rel="stylesheet" href="/template/assets/owlcarousel/css/owl.carousel.min.css">
			<link rel="stylesheet" href="/template/assets/owlcarousel/css/owl.theme.css">
			<link rel="stylesheet" href="/template/assets/owlcarousel/css/owl.theme.default.min.css">

			<script src="/template/assets/owlcarousel/js/owl.carousel.min.js"></script>
			<!-- waypoints min js  -->
			<script src="/template/assets/js/waypoints.min.js"></script>
			<!-- parallax js  -->
			<script src="/template/assets/js/parallax.js"></script>
			<!-- fit video  -->
			<script src="/template/assets/js/jquery.fitvids.js"></script>
			<!-- isotope min js -->
			<script src="/template/assets/js/isotope.min.js"></script>
			<!-- scripts js -->
			<script src="/template/assets/js/scripts.js?v=5"></script>
			<!-- <?php if (empty($user)) { ?>
		<script type="text/javascript" charset="UTF-8" src="//cdn.cookie-script.com/s/df15203d06999e693ca39baf11444fbd.js"></script>
	<?php } ?> -->

		<?php
		}
		?>

		<!-- <script>
$(document).ready(function (){
$('[href="https://www.brevo.com/products/conversations/?utm_source=logo_paid&utm_medium=chat"]').css('display', 'none')
})
</script> -->

		<?php
		// Include the Synergie Vertueuse modal just before the body ends
		// The file is designed to prevent multiple initializations
		include($_SERVER['DOCUMENT_ROOT'] . '/pop-up/utils/synergie-vertueuse.php');

		// Include the Communaute Intentionnelle modal
		include($_SERVER['DOCUMENT_ROOT'] . '/pop-up/utils/communaute-intentionnelle.php');

		////////////////////SI CONNECTE 
		if (!empty($user)) {
		?>
		</div>
	<?php
		}
	?>

</body>

</html>
<?php
//include('scan/recredite-demande-mission.php');
/* include('scan/abonnement-update-gratuit.php');
include('scan/abonnement-mail-1-jour.php');
include('scan/abonnement-mail-7-jours.php');
include('scan/abonnement-supprimer.php');
include('scan/bandeau-image.php'); */
?>