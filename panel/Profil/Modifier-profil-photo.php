<?php

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

$id_liaison = $_GET['id_liaison'];
$action = $_GET['action'];
$idaction = $_GET['idaction'];
$now = time();

////////////////////////////////////////AJOUTER UNE PHOTO
?>

<div style='text-align: left;'>
<?php
include('panel/Profil/Modifier-profil-photo-recadrage.php');
?>
</div><br /><br />
<?php
////////////////////////////////////////AJOUTER UNE PHOTO

}else{
header('location: /index.html');
}
?>