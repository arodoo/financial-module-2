<?php
ob_start();
////INCLUDES CONFIGURATIONS CMS CODI ONE
require_once('Configurations_bdd.php');
require_once('Configurations.php');
require_once('Configurations_modules.php');

require 'vendor/autoload.php';
//use \Ovh\Api;

$script = "oui";
require_once ('function/sms.php');

////INCLUDE FUNCTION HAUT CMS CODI ONE
$dir_fonction = "";
require_once ('function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php');

$req_boucle = $bdd->prepare("SELECT * FROM membres_etablissements_demandes_missions WHERE statut=? ORDER BY id DESC");
$req_boucle->execute(array("oui"));
while($ligne_boucle = $req_boucle->fetch()){
$id_prestation_mission = $ligne_boucle['id'];
$id_prestataire = $ligne_boucle['id_prestataire'];
$id_etablissement_mission = $ligne_boucle['id_etablissement_mission'];
$date_statut_demande= date('d-m-Y', $ligne_boucle['date']);
$token = $ligne_boucle['token'];

	///////////////////////////////SELECT
	$req_selectee = $bdd->prepare("SELECT * FROM membres_etablissements WHERE id=? ");
	$req_selectee->execute(array($id_etablissement_mission));
	$ligne_selectmm = $req_selectee->fetch();
        $req_selectee->closeCursor();

	///////////////////////////////SELECT
	$req_selecte = $bdd->prepare("SELECT * FROM membres_etablissements_indisponibilites WHERE id_etablissement=? AND id_membre=? AND type='Mission'");
	$req_selecte->execute(array($ligne_selectmm['id'],$id_prestataire));
	$ligne_selectm = $req_selecte->fetch();
        $req_selecte->closeCursor();

	if(empty($ligne_selectm['id']) && $ligne_selectmm['date_debut'] > (time()-(86400*30*2))){

		///////////////////////////////SELECT>
		$req_selecteeextra = $bdd->prepare("SELECT * FROM membres WHERE id=? ");
		$req_selecteeextra->execute(array($id_prestataire));
		$ligne_selectmmextra = $req_selecteeextra->fetch();
        	$req_selecteeextra->closeCursor();

		echo "Mise à jour : id prestation :  $id_prestation_mission - Token : $token - id membre : $id_prestataire ".$ligne_selectmmextra['prenom']." ".$ligne_selectmmextra['nom']." ".$ligne_selectmmextra['Telephone_portable']."- id mission :  $id_etablissement_mission  ".$ligne_selectmm['date']." ".date('d-m-Y', $ligne_selectmm['date_debut'])." - date mission ".date('d-m-Y', $ligne_selectmm['date_debut'])." ".date('d-m-Y', $ligne_selectmm['date_fin'])." - date statut de la demande $date_statut_demande<br /><br />";

        	$sql_update = $bdd->prepare("INSERT INTO membres_etablissements_indisponibilites
			(id_membre,
                        pseudo,
                        id_etablissement,
                        date_debut,
                        date_fin,
                        type)
		VALUES (?,?,?,?,?,?)");
		$sql_update->execute(
			array($ligne_selectmmextra['id'], 
			$ligne_selectmmextra['pseudo'], 
			$ligne_selectmm['id'],
			$ligne_selectmm['date_debut'],
			$ligne_selectmm['date_fin'],
            		"Mission"
                   ));   
		$sql_update->closeCursor();

 $messagesms = "Bonjour, tu as une mission sur PEPS qui arrive prochainement. Consulte rapidement les informations de la mission clique sur le lien.
 ".$ligne_selectmm['nom_etablisement']." ".$ligne_selectmm['adresse']." ".$ligne_selectmm['ville']." ".$ligne_selectmm['cp'].".
  ".$http."".$nomsiteweb."/Mes-missions/modifier/".$id_prestation_mission."/".$token."";
 $telephone = "+33".$ligne_selectmmextra['Telephone_portable'];
 $telephone = str_replace(' ', '', $telephone);

  if(!empty($ligne_selectmmextra['Telephone_portable'])){
    send_sms($messagesms, $telephone);
  }

	}

}
$req_boucle->closeCursor();

ob_end_flush();
?>