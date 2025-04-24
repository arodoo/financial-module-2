<?php
ob_start();
////INCLUDES CONFIGURATIONS CMS CODI ONE
require_once('Configurations_bdd.php');
require_once('Configurations.php');
require_once('Configurations_modules.php');

echo "<b>Liste des extras avec horaire de début mais pas d'horaire de fin</b><br /><br />"; 

///////////////////////////////SELECT BOUCLE
$req_boucle = $bdd->prepare("
SELECT * FROM membres_etablissements_horaires 
WHERE 
(
(horaire_lundi_crenau1_debut IS NOT NULL AND horaire_lundi_crenau1_debut != '' AND (horaire_lundi_crenau1_fin IS NULL OR horaire_lundi_crenau1_fin = '')) OR
(horaire_lundi_crenau2_debut IS NOT NULL AND horaire_lundi_crenau2_debut != '' AND (horaire_lundi_crenau2_fin IS NULL OR horaire_lundi_crenau2_fin = '')) OR
(horaire_mardi_crenau1_debut IS NOT NULL AND horaire_mardi_crenau1_debut != '' AND (horaire_mardi_crenau1_fin IS NULL OR horaire_mardi_crenau1_fin = '')) OR
(horaire_mardi_crenau2_debut IS NOT NULL AND horaire_mardi_crenau2_debut != '' AND (horaire_mardi_crenau2_fin IS NULL OR horaire_mardi_crenau2_fin = '')) OR
(horaire_mercredi_crenau1_debut IS NOT NULL AND horaire_mercredi_crenau1_debut != '' AND (horaire_mercredi_crenau1_fin IS NULL OR horaire_mercredi_crenau1_fin = '')) OR
(horaire_mercredi_crenau2_debut IS NOT NULL AND horaire_mercredi_crenau2_debut != '' AND (horaire_mercredi_crenau2_fin IS NULL OR horaire_mercredi_crenau2_fin = '')) OR
(horaire_jeudi_crenau1_debut IS NOT NULL AND horaire_jeudi_crenau1_debut != '' AND (horaire_jeudi_crenau1_fin IS NULL OR horaire_jeudi_crenau1_fin = '')) OR
(horaire_jeudi_crenau2_debut IS NOT NULL AND horaire_jeudi_crenau2_debut != '' AND (horaire_jeudi_crenau2_fin IS NULL OR horaire_jeudi_crenau2_fin = '')) OR
(horaire_vendredi_crenau1_debut IS NOT NULL AND horaire_vendredi_crenau1_debut != '' AND (horaire_vendredi_crenau1_fin IS NULL OR horaire_vendredi_crenau1_fin = '')) OR
(horaire_vendredi_crenau2_debut IS NOT NULL AND horaire_vendredi_crenau2_debut != '' AND (horaire_vendredi_crenau2_fin IS NULL OR horaire_vendredi_crenau2_fin = '')) OR
(horaire_samedi_crenau1_debut IS NOT NULL AND horaire_samedi_crenau1_debut != '' AND (horaire_samedi_crenau1_fin IS NULL OR horaire_samedi_crenau1_fin = '')) OR
(horaire_samedi_crenau2_debut IS NOT NULL AND horaire_samedi_crenau2_debut != '' AND (horaire_samedi_crenau2_fin IS NULL OR horaire_samedi_crenau2_fin = '')) OR
(horaire_dimanche_crenau1_debut IS NOT NULL AND horaire_dimanche_crenau1_debut != '' AND (horaire_dimanche_crenau1_fin IS NULL OR horaire_dimanche_crenau1_fin = '')) OR
(horaire_dimanche_crenau2_debut IS NOT NULL AND horaire_dimanche_crenau2_debut != '' AND (horaire_dimanche_crenau2_fin IS NULL OR horaire_dimanche_crenau2_fin = ''))
)
ORDER BY id
");
$req_boucle->execute();

$iii = 0;
while($ligne_boucle = $req_boucle->fetch()){

///////////////////////////////SELECT
$req_select = $bdd->prepare("SELECT * FROM membres WHERE id=?");
$req_select->execute(array($ligne_boucle['id_membre']));
$ligne_select = $req_select->fetch();
$req_select->closeCursor();

if(!empty($ligne_select['prenom'])){
echo $ligne_boucle['id_membre'] . " | date inscription " . date('d-m-Y', $ligne_select['date_enregistrement']) . " | " . $ligne_select['prenom'] . " " . $ligne_select['nom'];

// Ajouter les jours sans horaire de fin
$jours_sans_fin = [];
if (!empty($ligne_boucle['horaire_lundi_crenau1_debut']) && empty($ligne_boucle['horaire_lundi_crenau1_fin'])) {
$jours_sans_fin[] = 'Lundi créneau 1';
//$bdd->prepare("UPDATE membres_etablissements_horaires SET horaire_lundi_crenau1_fin = '23:59' WHERE id = ?")->execute([$ligne_boucle['id']]);
}
if (!empty($ligne_boucle['horaire_lundi_crenau2_debut']) && empty($ligne_boucle['horaire_lundi_crenau2_fin'])) {
$jours_sans_fin[] = 'Lundi créneau 2';
//$bdd->prepare("UPDATE membres_etablissements_horaires SET horaire_lundi_crenau2_fin = '23:59' WHERE id = ?")->execute([$ligne_boucle['id']]);
}
if (!empty($ligne_boucle['horaire_mardi_crenau1_debut']) && empty($ligne_boucle['horaire_mardi_crenau1_fin'])) {
$jours_sans_fin[] = 'Mardi créneau 1';
//$bdd->prepare("UPDATE membres_etablissements_horaires SET horaire_mardi_crenau1_fin = '23:59' WHERE id = ?")->execute([$ligne_boucle['id']]);
}
if (!empty($ligne_boucle['horaire_mardi_crenau2_debut']) && empty($ligne_boucle['horaire_mardi_crenau2_fin'])) {
$jours_sans_fin[] = 'Mardi créneau 2';
//$bdd->prepare("UPDATE membres_etablissements_horaires SET horaire_mardi_crenau2_fin = '23:59' WHERE id = ?")->execute([$ligne_boucle['id']]);
}
if (!empty($ligne_boucle['horaire_mercredi_crenau1_debut']) && empty($ligne_boucle['horaire_mercredi_crenau1_fin'])) {
$jours_sans_fin[] = 'Mercredi créneau 1';
//$bdd->prepare("UPDATE membres_etablissements_horaires SET horaire_mercredi_crenau1_fin = '23:59' WHERE id = ?")->execute([$ligne_boucle['id']]);
}
if (!empty($ligne_boucle['horaire_mercredi_crenau2_debut']) && empty($ligne_boucle['horaire_mercredi_crenau2_fin'])) {
$jours_sans_fin[] = 'Mercredi créneau 2';
//$bdd->prepare("UPDATE membres_etablissements_horaires SET horaire_mercredi_crenau2_fin = '23:59' WHERE id = ?")->execute([$ligne_boucle['id']]);
}
if (!empty($ligne_boucle['horaire_jeudi_crenau1_debut']) && empty($ligne_boucle['horaire_jeudi_crenau1_fin'])) {
$jours_sans_fin[] = 'Jeudi créneau 1';
//$bdd->prepare("UPDATE membres_etablissements_horaires SET horaire_jeudi_crenau1_fin = '23:59' WHERE id = ?")->execute([$ligne_boucle['id']]);
}
if (!empty($ligne_boucle['horaire_jeudi_crenau2_debut']) && empty($ligne_boucle['horaire_jeudi_crenau2_fin'])) {
$jours_sans_fin[] = 'Jeudi créneau 2';
//$bdd->prepare("UPDATE membres_etablissements_horaires SET horaire_jeudi_crenau2_fin = '23:59' WHERE id = ?")->execute([$ligne_boucle['id']]);
}
if (!empty($ligne_boucle['horaire_vendredi_crenau1_debut']) && empty($ligne_boucle['horaire_vendredi_crenau1_fin'])) {
$jours_sans_fin[] = 'Vendredi créneau 1';
//$bdd->prepare("UPDATE membres_etablissements_horaires SET horaire_vendredi_crenau1_fin = '23:59' WHERE id = ?")->execute([$ligne_boucle['id']]);
}
if (!empty($ligne_boucle['horaire_vendredi_crenau2_debut']) && empty($ligne_boucle['horaire_vendredi_crenau2_fin'])) {
$jours_sans_fin[] = 'Vendredi créneau 2';
//$bdd->prepare("UPDATE membres_etablissements_horaires SET horaire_vendredi_crenau2_fin = '23:59' WHERE id = ?")->execute([$ligne_boucle['id']]);
}
if (!empty($ligne_boucle['horaire_samedi_crenau1_debut']) && empty($ligne_boucle['horaire_samedi_crenau1_fin'])) {
$jours_sans_fin[] = 'Samedi créneau 1';
//$bdd->prepare("UPDATE membres_etablissements_horaires SET horaire_samedi_crenau1_fin = '23:59' WHERE id = ?")->execute([$ligne_boucle['id']]);
}
if (!empty($ligne_boucle['horaire_samedi_crenau2_debut']) && empty($ligne_boucle['horaire_samedi_crenau2_fin'])) {
$jours_sans_fin[] = 'Samedi créneau 2';
//$bdd->prepare("UPDATE membres_etablissements_horaires SET horaire_samedi_crenau2_fin = '23:59' WHERE id = ?")->execute([$ligne_boucle['id']]);
}
if (!empty($ligne_boucle['horaire_dimanche_crenau1_debut']) && empty($ligne_boucle['horaire_dimanche_crenau1_fin'])) {
$jours_sans_fin[] = 'Dimanche créneau 1';
//$bdd->prepare("UPDATE membres_etablissements_horaires SET horaire_dimanche_crenau1_fin = '23:59' WHERE id = ?")->execute([$ligne_boucle['id']]);
}
if (!empty($ligne_boucle['horaire_dimanche_crenau2_debut']) && empty($ligne_boucle['horaire_dimanche_crenau2_fin'])) {
$jours_sans_fin[] = 'Dimanche créneau 2';
//$bdd->prepare("UPDATE membres_etablissements_horaires SET horaire_dimanche_crenau2_fin = '23:59' WHERE id = ?")->execute([$ligne_boucle['id']]);
}

if (!empty($jours_sans_fin)) {
echo " | Jours sans horaire de fin : " . implode(', ', $jours_sans_fin);
}

echo "<br />---------------- <br />";
$iii++;
}
}

$req_boucle->closeCursor();
echo "Total : $iii <br /><br />";

ob_end_flush();
?>