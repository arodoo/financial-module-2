
<?php
///////////////////////////////SELECT BOUCLE
if($type_catagorie == "catégorie" ){
$req_boucles = $bdd->prepare("SELECT * FROM configurations_publicites WHERE id_categorie=? AND type_publicite=? AND type_section=? AND statut_activer_post=? AND (Duree_de_la_publicite='Illimité' || ( date_debut <= ? AND date_fin >= ? ) ) ORDER BY position_publicite ASC");
$req_boucles->execute(array($_GET['idaction'],"Image avec lien",$type_section,"oui",time(),time()));
}else{
$req_boucles = $bdd->prepare("SELECT * FROM configurations_publicites WHERE id_categorie=? AND type_publicite=? AND type_section=? AND statut_activer_post=? AND (Duree_de_la_publicite='Illimité' || ( date_debut <= ? AND date_fin >= ? ) ) ORDER BY position_publicite ASC");
$req_boucles->execute(array("Accueil","Image avec lien",$type_section,"oui",time(),time()));
}
$ligne_boucles = $req_boucles->fetch();
$req_boucles->closeCursor();

if(!empty($ligne_boucles['id'])){
            ?>

<?php
///////////////////////////////SELECT BOUCLE
if($type_catagorie == "catégorie" ){
?>
<!-- START SECTION PUBLICITE -->
<section style="padding-bottom: 0px; padding-top: 0px;" >
<?php
}else{
?>
<!-- START SECTION PUBLICITE -->
<section style="padding-bottom: 0px; padding-top: 40px;" >
<?php
}
?>

    <div class="container">
        <div class="row blog_wrap justify-content-center">
            <?php
            ///////////////////////////////SELECT BOUCLE
		if($type_catagorie == "catégorie" ){
			$req_boucle = $bdd->prepare("SELECT * FROM configurations_publicites WHERE id_categorie=? AND type_publicite=? AND type_section=? AND statut_activer_post=? AND (Duree_de_la_publicite='Illimité' || ( date_debut <= ? AND date_fin >= ? ) ) ORDER BY position_publicite ASC");
			$req_boucle->execute(array($_GET['idaction'],"Image avec lien",$type_section,"oui",time(),time()));
		}else{
			$req_boucle = $bdd->prepare("SELECT * FROM configurations_publicites WHERE id_categorie=? AND type_publicite=? AND type_section=? AND statut_activer_post=? AND (Duree_de_la_publicite='Illimité' || ( date_debut <= ? AND date_fin >= ? ) ) ORDER BY position_publicite ASC");
			$req_boucle->execute(array("Accueil","Image avec lien",$type_section,"oui",time(),time()));
		}
            while ($ligne_boucle = $req_boucle->fetch()) {
            ?>
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 mb-md-4 mb-2 pb-2">
                    <div class="blog_post blog_style1">
                        <div class="blog_img">
                            <a rel="nofollow" href="<?= $ligne_boucle['url_page_publicite']; ?>">
                                <img src="/images/publicites/<?= $ligne_boucle['imagepublicite']; ?>" alt="<?= $ligne_boucle['imagepublicite']; ?>">
                            </a>
                            <span class="post_date bg_blue text-light">Publicité</span>
                        </div>
                        <div class="blog_content bg-white">
                            <div class="blog_text">
                                <h4 class="blog_title" style="width: 100%; text-align: center;" ><a href="<?= $ligne_boucle['lien_publicite']; ?>" target='blank_' ><u><?= $ligne_boucle['nom_publicite']; ?></u></a></h4>
                                <a rel="nofollow" href="<?= $ligne_boucle['lien_publicite']; ?>" class="btn btn-default btn-xs mb-2" style="width: 100%;" target='blank_' >Consulter </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            }
            $req_boucle->closeCursor();
            ?>
        </div>
        </div>
</section>
<!-- END SECTION PUBLICITE -->

            <?php
            }
            ?>