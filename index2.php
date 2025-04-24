<?php
ob_start();

////INCLUDES CONFIGURATIONS CMS CODI ONE
require_once('Configurations_bdd.php');
require_once('Configurations_modules.php');
require_once('Configurations.php');

////INCLUDE FUNCTION HAUT CMS CODI ONE
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
<meta name="description" content="<?php echo str_replace('"','', $Metas_description_page);?>">
<meta name="keywords" content="<?php echo str_replace('"','', $Metas_mots_cles_page);?>">
<meta name="viewport" content="initial-scale=1, width=device-width">

<!-- Favicon Icon -->
<link rel="shortcut icon" type="image/x-icon" href="/images/PEPs-favicon.jpg?v=1">
<!-- Latest Bootstrap min CSS -->
<link rel="stylesheet" href="/template/assets/bootstrap/css/bootstrap.min.css">
<!-- Icon Font CSS -->
<link rel="stylesheet" href="/template/assets/css/ionicons.min.css">
<link rel="stylesheet" href="/template/assets/css/themify-icons.css">
<!-- FontAwesome CSS -->
<link rel="stylesheet" href="/template/assets/css/all.min.css">
<!-- Style CSS -->
<link rel="stylesheet" href="/template/assets/css/style3.css?v=248">
<link rel="stylesheet" href="/template/assets/css/responsive.css">
<link rel="stylesheet" id="layoutstyle" href="/template/assets/color/theme-orange.css">
<link rel="stylesheet" href="/template/demo-restaurant/css/demo-restaurant.css?v=1">

<?php
if(empty($_GET['page'])){
?>
	<meta property="og:type" content="website" />
	<meta property="og:url" content="https://peps-extra.com/" />
	<meta property="og:image" content="https://peps-extra.com/images/peps-accueil-6.jpg" />
<?php
}
?>

<!-- Brevo Conversations {literal} -->
<script>
    (function(d, w, c) {
        w.BrevoConversationsID = '62c54b716c593e4e5d1abddc';
        w[c] = w[c] || function() {
            (w[c].q = w[c].q || []).push(arguments);
        };
        var s = d.createElement('script');
        s.async = true;
        s.src = 'https://conversations-widget.brevo.com/brevo-conversations.js';
        if (d.head) d.head.appendChild(s);
    })(document, window, 'BrevoConversations');
</script>
<!-- /Brevo Conversations {/literal} -->

<?php 
////INCLUDE JS BAS CMS CODI ONE
include('js/INCLUDE-JS-HAUT-CMS-CODI-ONE.php');
////TITLE

	if(!empty($_GET['page'])){
		echo "<title>$TitreTitrea_page | Pep's Extra</title>";
	}else{
		echo "<title>$TitreTitrea_page</title>";
	}

////GOOGLE ANALYTICS
echo "$Google_analytic";
?>

</head>
<body>

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

////INCLUDE POP-UP HAUT CMS CODI ONE
include('pop-up/INCLUDE-POP-UP-HAUT-CMS-CODI-ONE.php');

include('index-menu.php');

////INCLUDE POP-UP HAUT CMS CODI ONE
include('pop-up/INCLUDE-POP-UP-HAUT-CMS-CODI-ONE.php');

////HEADER
include('index-header.php');

////////////////////On appelle la page demandée
if($p404_existe != "oui"){
	////PAGE BANDEAU
	page_bandeaux();
	////SWITCH DES PAGES
	if(isset($_GET['page']) && $_GET['page'] != "Referencer-un-etablissement" && $_GET['page'] != "Fiche" && $_GET['page'] != "Plateforme" && $_GET['page'] != "Plateforme2" ){

		//Informations extras alerte
		include('pop-up/extras/extras-missions-include.php');

		?> <div class="container" style="margin-top: 40px;" > <?php
		include('pages.php');
		?> </div> <?php
	}else{
		include('pages.php');
	}
////SWITCH DES PAGES
}elseif($p404_existe == "oui"){
	include("function/404/404r.php");
}
////////////////////On apelle la page demandée
?>

<?php
////FOOTER
include('index-footer.php');

if(empty($user)){
?>
<a onclick="return false;" class="nav-link pxp-header-user-test" href="#"></a><?php
}
////INCLUDE CSS BAS CMS CODI ONE
include('css/INCLUDE-CSS-BAS-CMS-CODI-ONE.php');

////INCLUDE JS BAS CMS CODI ONE
include('js/INCLUDE-JS-BAS-CMS-CODI-ONE.php');

////INCLUDE POP-UP BAS CMS CODI ONE
include('pop-up/INCLUDE-POP-UP-BAS-CMS-CODI-ONE.php');

////////////////////////////////////////////////////////////////SI MODULE POPUP SUPPORT ACTIVE
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
<?php if(empty($user)){  ?>
<script type="text/javascript" charset="UTF-8" src="//cdn.cookie-script.com/s/df15203d06999e693ca39baf11444fbd.js"></script>
<?php } ?>
	<script>
		$(document).ready(function (){
		$('[href="https://www.brevo.com/products/conversations/?utm_source=logo_paid&utm_medium=chat"]').css('display', 'none')

		console.log('s')
		})
		</script>
</body>
</html>

<?php

//include('scan/recredite-demande-mission.php');
include('scan/abonnement-update-gratuit.php');
include('scan/abonnement-mail-1-jour.php');
include('scan/abonnement-mail-7-jours.php');
include('scan/abonnement-supprimer.php');
include('scan/bandeau-image.php');

ob_end_flush();
?>

