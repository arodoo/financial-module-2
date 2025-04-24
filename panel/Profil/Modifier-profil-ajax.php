<?php
ob_start();
////INCLUDES CONFIGURATIONS CMS CODI ONE
require_once('../../Configurations_bdd.php');
require_once('../../Configurations.php');
require_once('../../Configurations_modules.php');

////INCLUDE FUNCTION HAUT CMS CODI ONE
$dir_fonction= "../../";
require_once('../../function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php');

$lasturl = $_SERVER['HTTP_REFERER'];

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

if(!empty($_SESSION['4M8e7M5b1R2e8s']) && !empty($user)){

////////////////////////////////////////////////////////////////////////Update du profil

$accepter_toutes_les_demandes = $_POST['accepter_toutes_les_demandes'];

if(!empty($accepter_toutes_les_demandes)){

	///////////////////////////////UPDATE PROFIL
	$sql_update = $bdd->prepare("UPDATE membres SET 
		accepter_toutes_les_demandes=?
		WHERE pseudo=?");
	$sql_update->execute(array(
		$accepter_toutes_les_demandes,
		$user));                     
	$sql_update->closeCursor();

$result2 = array("Texte_rapport"=>"Modification apportées avec succès !","retour_validation"=>"ok","retour_lien"=>"");

}elseif(empty($accepter_toutes_les_demandes)){
	$result2 = array("Texte_rapport"=>"Choisissez un paramètre pour les réservations !","retour_validation"=>"","retour_lien"=>"");

}
////////////////////////////////////////////////////////////////////////Update du profil

	$result2 = json_encode($result2);
	echo $result2;

}else{
	header('location: /index.html');
}

ob_end_flush();
?>