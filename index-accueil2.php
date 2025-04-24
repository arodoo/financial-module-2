<?php
//$myLat = $_SESSION['lat'] ?: 500.00; // VALEUR PAR DEFAUT POUR NE PAS FAIRE BUGUER LA FONCTION
//$myLng = $_SESSION['lng'] ?: 500.00;
function getDistance($lat1, $lon1, $lat2, $lon2)
{
    $earthRadius = 6371; // rayon de la Terre en kilomètres
    $lat1 = deg2rad($lat1);
    $lon1 = deg2rad($lon1);
    $lat2 = deg2rad($lat2);
    $lon2 = deg2rad($lon2);
    $latDiff = $lat2 - $lat1;
    $lonDiff = $lon2 - $lon1;
    $angle = 2 * asin(sqrt(pow(sin($latDiff / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($lonDiff / 2), 2)));
    return round($angle * $earthRadius, 1);
}
?>
<!--
<style>
    #commentaires {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2) !important;
        border-radius: 10px;
    }

    .equal-height-card {
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    /* Container global de la première carte */
    .card-container {
        border-radius: 15px;
        background: linear-gradient(to bottom, #B82E36 70%, #ae1832 30%);
        color: white;
        overflow: hidden;
        text-align: left;
        position: relative;
        padding: 20px;
        height: 250px;
        display: flex;
        align-items: center;
        flex-direction: row;

    }

    /* Container global de la deuxième carte */
    .card-container-second {
        border-radius: 15px;
        background-color: #003e49;
        color: white;
        overflow: hidden;
        text-align: left;
        position: relative;
        padding: 20px;
        height: 250px;
        display: flex;
        align-items: center;
        flex-direction: row;
    }

    /* Image de la personne dans la carte */
    .card-image {
        width: 150px;
        height: auto;
        margin-right: 20px;
        z-index: 2;

    }

    /* Texte dans la carte */
    .card-text {
        font-size: 18px;
        line-height: 1.5;
        z-index: 1;
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;

    }

    /* Couleur du texte */
    .card-text p {
        color: white;
    }



    /* Effet hover pour le bouton */
    .card-button:hover {
        background-color: #FFD700;
        color: white;

    }

    /* Réduction de la taille des logos */
    .logo-small {
        max-width: 80px;
        height: auto;
    }

    /* Container FAQ Card */
    .faq-card {
        border-radius: 15px;
        border: none;
        overflow: hidden;
        background-color: white;
    }

    .faq-card .card-body {
        padding: 20px;
    }

    /* Style des questions */
    .faq-item {
        font-size: 18px;
        padding: 4px 20px;
        color: #333;

    }

    /* Séparateur entre chaque question */
    .separator {
        height: 1px;
        width: 100%;
        background-color: #000;
        margin: 10px 0;
    }


    /* Pour les petits écrans, ajustement de la taille des logos */
    @media (max-width: 576px) {
        .logo-small {
            max-width: 60px;

        }

        #commentaires {
            margin-bottom: 30px;
        }
    }
</style>
-->


<div class="container my-3">
    <div class="row justify-content-between">
        <!-- Première carte -->
        <div class="col-md-6 mb-4 mb-md-0">
            <div class="card-container">
                <img class="card-image img-fluid" src="/images/mise-en-relation-restaurant.png" alt="Serveur avec une assiette">
                <div class="card-text">
                    <p>LOREM IPSUM</p>
                    <p>LOREM IPSUM</p>
                    <p>LOREM IPSUM</p>
                    <p>LOREM IPSUM</p>
                    <button class="btn btn-outline-light card-button">Je suis un Pro</button>
                </div>
            </div>
        </div>

        <!-- Deuxième carte avec le style personnalisé -->
        <div class="col-md-6">
            <div class="card-container-second">
                <img class="card-image img-fluid" src="/images/blog/travail-en-extra-le-week-end-salaire-1686271561.jpg" alt="Serveur avec une assiette">
                <div class="card-text">
                    <p>LOREM IPSUM</p>
                    <p>LOREM IPSUM</p>
                    <p>LOREM IPSUM</p>
                    <p>LOREM IPSUM</p>
                    <button class="btn btn-outline-light card-button">Je suis un Pro</button>
                </div>
            </div>
        </div>
    </div>
</div>






<section class="small_pt small_pb" style="padding: 20px;">
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-10 col-md-10 animation animated fadeInUp" data-animation="fadeInUp" data-animation-delay="0.1s" style="animation-delay: 0.1s; opacity: 1;">
                <div class="heading_s2 text-center">
                    <h2>Les partenaires de Pep's Extra</h2>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 animation" data-animation="fadeInUp" data-animation-delay="0.2s">
                <div class="cl_logo_slider owl-carousel owl-theme" data-margin="30" data-loop="true" data-autoplay="true" data-dots="false" data-autoplay-timeout="2000">
                    <div class="item">
                        <a href="#" onclick="return false;"><img src="/images/partenaires/Codi-one.png?v=1" alt="Codi one" style="margin-top: 2px;" /></a>
                    </div>
                    <div class="item">
                        <a href="#" onclick="return false;"><img src="/images/partenaires/saxo-45.png?v=1" alt="Saxo 45" style="margin-top: 25px;" /></a>
                    </div>
                    <div class="item">
                        <a href="#" onclick="return false;"><img src="/images/partenaires/Aplinet.png?v=1" alt="Aplinet" style="margin-top: 10px;" /></a>
                    </div>
                    <div class="item">
                        <a href="#" onclick="return false;"><img src="/images/partenaires/PROXITE.png" alt="PROXITE" style="height: 60px; margin-top: 10px;" /></a>
                    </div>
                    <div class="item">
                        <a href="#" onclick="return false;"><img src="/images/partenaires/Logo-Technopole.png?v=1" alt="Technopole" style="height: 60px; margin-top: 10px;" /></a>
                    </div>
                    <div class="item">
                        <a href="#" onclick="return false;"><img src="/images/partenaires/DEVUP.png?v=1" alt="DEVUP" style="margin-top: 2px;" /></a>
                    </div>
                    <div class="item">
                        <a href="#" onclick="return false;"><img src="/images/partenaires/CIC.png?v=1" alt="CIC" style="height: 60px; margin-top: 10px;" /></a>
                    </div>
                    <div class="item">
                        <a href="#" onclick="return false;"><img src="/images/partenaires/Promocash.jpg?v=1" alt="Promocash" style="margin-top: 20px;" /></a>
                    </div>
                    <div class="item">
                        <a href="#" onclick="return false;"><img src="/images/partenaires/shine.png?v=1" alt="Shine" style="height: 60px; margin-top: 10px;" /></a>
                    </div>
                    <div class="item">
                        <a href="#" onclick="return false;"><img src="/images/partenaires/LABO.jpg" alt="LABO" style="height: 80px; margin-top: 5px;" /></a>
                    </div>
                    <div class="item">
                        <a href="#" onclick="return false;"><img src="/images/partenaires/paies_logo.png" alt="paies" style="height: 60px; margin-top: 10px;" /></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



<div class="container my-5">
    <div class="row">
        <div class="col-md-6 d-flex align-items-stretch">
            <div id="commentaires">
                <div class="card-body">
                    <h5 class="card-title">Merci à toute l'équipe PEPS</h5>
                    <p class="card-text">
                        Un grand merci à toute l'équipe PEPS extras qui nous apporte toujours de nouveaux talents lorsque nous en avons besoin...
                    </p>
                    <div class="client-info">
                        <img src="/images/blog/2-Faire-un-extra-1685530578.jpg" alt="Château les Muids" class="imageRadius">
                        <p>Ibis Montargis <br>Hôtel</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 d-flex align-items-stretch">
            <div id="commentaires">
                <div class="card-body">
                    <h5 class="card-title">D'une simplicité d'utilisation parfaite</h5>
                    <p class="card-text">
                        Peps'extra est une plateforme très appréciable et d'une simplicité d'utilisation parfaite...
                    </p>
                    <div class="client-info">
                        <img src="/images/blog/2-Faire-un-extra-1685530578.jpg" alt="Château les Muids" class="imageRadius">
                        <p>Château les Muids <br>Hôtel</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="container my-5">

    <div id="commentaires" class="card-body">
        <h5 class="card-title">FAQ</h5>
        <div class="faq-item">Question 1</div>
        <div class="separator"></div>
        <div class="faq-item">Question 2</div>
        <div class="separator"></div>
        <div class="faq-item">Question 3</div>
        <div class="separator"></div>
        <div class="faq-item">Question 4</div>
    </div>
</div>





<section style="padding: 20px; background-color: #383838;">
    <div class="container">
        <div class="text-center d-flex flex-column justify-content-center align-items-center" style="height: 100%;">
            <div class="col-md-8 animation" data-animation="fadeInUp" data-animation-delay="0.2s">

            </div>
            <div class="col-md-4 animation" data-animation="fadeInUp" data-animation-delay="0.4s">

                <div class="text_white">
                    <form method="post" action="/Trouver-des-extras">
                        <p>Plateforme dédiée aux Pros & Extras</p>
                        <button type="submit" title="Rechercher" class="btn btn-outline-light card-button" name="submit" value="Submit">
                            Je découvre
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</section>










<section class="small_pb" style="padding-top: 0px; margin-top: 0px;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 animation animated fadeInLeft" data-animation="fadeInLeft" data-animation-delay="0.1s" style="animation-delay: 0.1s; opacity: 1;">
                <div class="cleafix small_divider"></div>
                <div class="heading_s2">
                    <span>La plateforme de mise en relation</span>
                    <h2 class="font_style2">PEP's, un outil pour les pros conçue par une pro !</h2>
                </div>
                <p>PEP's, c'est LA SOLUTION qui permet de trouver des Extras et de simplifier les démarches administratives de recrutement.</p>
                <p>Découvrez un vivier important d'Extras disponibles et géolocalisés, vous serez mis en relation directement.</p>
                <p>La plateforme PEP's vous fait gagner un temps précieux que vous pourrez consacrer à vos clients et au développement de votre business. Votre temps, c'est de l'argent !</p>
                <p>Pep's vous propose des prestations de service pour réaliser vos DUE, vos Contrats de travail, et vos formations pour les collaborateurs.</p>
                <p>Restez focus sur l'activité principale de votre métier en vous déchargeant de la gestion administrative des Extras avec la formule PEP's.</p>
                <p><span class="uk-icon-check"></span> Mise en relation directe avec des Extras ciblés et géolocalisés à la prestation.</p>
                <p><span class="uk-icon-check"></span> Pré-réservation d'extras sur une durée définie (Mois, saison complète, période).</p>
                <p><span class="uk-icon-check"></span> Demande de devis pour constituer une équipe complète (à partir de 4 Extras et 1 Maitre d'hôtel).</p>
                <p><span class="uk-icon-check"></span> Formation managériale, profil Maitre d'hôtel avec accompagnement et suivi.</p>
                <?php if (!($statut_compte_oo >= 2)) { ?>
                    <a href="/Trouver-des-extras" class="btn btn-outline-default">Les formules Pep's</a>
                <?php } ?>
                <div class="cleafix large_divider"></div>
            </div>
            <div class="col-md-6 order-md-first animation animated fadeInRight" data-animation="fadeInRight" data-animation-delay="0.2s" style="animation-delay: 0.2s; opacity: 1; vertical-align: top;">
                <img src="/images/peps-accueil-4.jpg" alt="mise en relation restaurant">
                <img src="/images/peps-accueil-3.jpg" alt="mise en relation restaurant">

            </div>
        </div>
    </div>
</section>

<section class="p-0 overflow_hide">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-12 p-0 animation animated fadeInLeft" data-animation="fadeInLeft" data-animation-delay="0.2s" style="animation-delay: 0.2s; opacity: 1;">
                <div class="gray_bg h-100 d-flex align-items-center medium_padding pt-4 pt-md-5">
                    <div>
                        <div class="heading_s1">
                            <h2>Tu es Extras ?</h2>
                        </div>
                        <p>La plateforme PEP's te permet d'optimiser ton planning grâce à un agenda astucieux et de gérer tes missions avec des réservations fermes.</p>
                        <div class="row">
                            <div class="col-xl-6 col-lg-12 col-sm-6" style="text-align: left;">
                                <div class="icon_box icon_box_style_2 mt-3" style="text-align: left;">
                                    <div class="box_icon">
                                        <i class="uk-icon-user"></i>
                                    </div>
                                    <div class="icon_box_content">
                                        <h3 style="font-size: 20px;">Profil complet</h3>
                                        <p>Tu disposes d'un profil complet avec tes informations, ton avatar, une bio et les avis sur tes prestations.</p>
                                    </div>
                                </div>
                                <div class="icon_box icon_box_style_2 mt-3" style="text-align: left;">
                                    <div class="box_icon">
                                        <i class="uk-icon-eye"></i>
                                    </div>
                                    <div class="icon_box_content">
                                        <h3 style="font-size: 20px;">Disponibilités</h3>
                                        <p>Gère ton agenda en temps réel; Indique tes disponibilités à la journée, à la semaine ou en créneau horaire.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-12 col-sm-6" style="text-align: left;">
                                <div class="icon_box icon_box_style_2 mt-3" style="text-align: left;">
                                    <div class="box_icon">
                                        <i class="uk-icon-cube"></i>
                                    </div>
                                    <div class="icon_box_content">
                                        <h3 style="font-size: 20px;">Mise en relation</h3>
                                        <p>PEP's te met en relation avec des professionnels et c'est toi qui valide tes missions.</p>
                                    </div>
                                </div>
                                <div class="icon_box icon_box_style_2 mt-3" style="text-align: left;">
                                    <div class="box_icon">
                                        <i class="uk-icon-calendar"></i>
                                    </div>
                                    <div class="icon_box_content">
                                        <h3 style="font-size: 20px;">Agenda</h3>
                                        <p>Gère ton emploi du temps en visualisant instantanément toutes tes missions à la semaine ou au mois.</p>
                                    </div>
                                </div>
                            </div>

                            <?php
                            if (empty($user)) {
                            ?>
                                <div style="text-align: center; width: 100%; margin-top: 20px;">
                                    <a onclick="return false;" class="btn btn-default pxp-header-inscription" href="#">Je m'inscris</a>
                                </div>
                            <?php
                            } elseif (!empty($user) && ($statut_compte_oo != 1 || $statut_compte_oo != 6)) {
                            ?>
                                <div style="text-align: center; width: 100%; margin-top: 20px;">
                                    <a href="/Mon-profil" class="btn btn-default">Mon profil</a>
                                </div>
                            <?php
                            }
                            ?>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12 p-0 animation animated fadeInRight" data-animation="fadeInRight" data-animation-delay="0.4s" style="animation-delay: 0.4s; opacity: 1;">
                <div class="h-100 background_bg md-height-300" data-img-src="/images/peps-accueil-6.jpg"></div>
            </div>
        </div>
    </div>
</section>

<section class="small_pb" style="padding-bottom: 0px; margin-bottom: 0px;">
    <div class="container">
        <div class="row">
            <div class="col-md-12 animation animated fadeInUp" data-animation="fadeInUp" data-animation-delay="0.2s" style="animation-delay: 0.2s; opacity: 1;">
                <div class="heading_s1 text-center">
                    <h2>Des avantages V.I.P pour les Extras</h2>
                </div>
            </div>
        </div>
        <div class="row justify-content-center animation animated fadeInUp" data-animation="fadeInUp" data-animation-delay="0.4s" style="animation-delay: 0.4s; opacity: 1;">
            <div class="col-md-3 col-sm-6 mb-lg-5 mb-4 text-center">
                <div class="icon_box icon_box_style_5">
                    <div class="box_icon mb-3">
                        <i class="ti-briefcase"></i>
                    </div>
                    <div class="icon_box_content">
                        <h3 style="font-size: 20px;">Augmente tes revenus et ta visibilité</h3>
                        <p>Obtiens plus de missions</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-lg-5 mb-4 text-center">
                <div class="icon_box icon_box_style_5">
                    <div class="box_icon mb-3">
                        <i class="ti-ruler-pencil"></i>
                    </div>
                    <div class="icon_box_content">
                        <h3 style="font-size: 20px;">Un planning complet</h3>
                        <p>Optimise ton planning grâce à ton agenda</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-lg-5 mb-4 text-center">
                <div class="icon_box icon_box_style_5">
                    <div class="box_icon mb-3">
                        <i class="ti-layers-alt"></i>
                    </div>
                    <div class="icon_box_content">
                        <h3 style="font-size: 20px;">Des outils adaptés</h3>
                        <p>Des outils dédiés pour ton activité d'Extra</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-lg-5 mb-4 text-center">
                <div class="icon_box icon_box_style_5">
                    <div class="box_icon mb-3">
                        <i class="ti-settings"></i>
                    </div>
                    <div class="icon_box_content">
                        <h3 style=" font-size: 20px;">Organisation optimale</h3>
                        <p>Gagne du temps avec un outil de gestion optimisé.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="bg-dark" style="background-color: #003E49 !important;">

    <div class="container text_white">

        <div class="row justify-content-center" style="padding-top: 15px;">
            <div class="col-md-12 text-center animation animated fadeInUp" data-animation="fadeInUp" data-animation-delay="0.1s" style="animation-delay: 0.1s; opacity: 1;">
                <div>
                    <div class="heading_s3 mb-md-3text-center" style="max-width: 550px; margin: auto; margin-bottom: 80px;">
                        <h2 class="heading_s2 text-center">Les nouveaux extras</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="row animation animated fadeInUp justify-content-center" data-animation="fadeInUp" data-animation-delay="0.2s" style="animation-delay: 0.2s; opacity: 1;">

            <?php
            $i = 0;
            $req_boucle = $bdd->prepare("SELECT * FROM membres_etablissements WHERE type_demande=1 AND activer=? AND documents_telecharges='oui' AND mode_vacance!='oui' order by date DESC");
            $req_boucle->execute(array("oui"));
            while ($ligne_boucle = $req_boucle->fetch()) {
                $idoneinfos_artciles_blog = $ligne_boucle['id'];
                $id_membre = $ligne_boucle['id_membre'];
                $type_demande = $ligne_boucle['type_demande'];
                $nom_etablissement = $ligne_boucle['nom_etablissement'];
                $nom_etablissement_url = $ligne_boucle['nom_etablissement_url'];
                $adresse = $ligne_boucle['adresse'];
                $cp = $ligne_boucle['cp'];
                $ville = $ligne_boucle['ville'];
                $adresse_info = "$adresse $cp $ville";
                $slug_ville = $ligne_boucle['slug_ville'];
                $id_ville = $ligne_boucle['id_ville'];
                $longitude = $ligne_boucle['longitude'] ?: 00.00; // VALEUR PAR DEFAUT POUR NE PAS FAIRE BUGUER LA FONCTION DE CALCUL DE DISTANCE
                $latitude = $ligne_boucle['latitude'] ?: 00.00; // IDEM
                $mail = $ligne_boucle['mail'];
                $telephone = $ligne_boucle['telephone'];
                $site_web = $ligne_boucle['site_web'];
                $description = $ligne_boucle['description'];
                $photo_principale1 = $ligne_boucle['photo_principale1'];
                $photo_principale2 = $ligne_boucle['photo_principale2'];
                $photo_principale3 = $ligne_boucle['photo_principale3'];
                $photo_principale4 = $ligne_boucle['photo_principale4'];
                $photo_principale5 = $ligne_boucle['photo_principale5'];
                $horaire_semaine = $ligne_boucle['horaire_semaine'];
                $horaire_samedi = $ligne_boucle['horaire_samedi'];
                $horaire_ferme = $ligne_boucle['horaire_ferme'];
                $avis = $ligne_boucle['avis'];
                $nbr_vue = $ligne_boucle['nbr_vue'];
                $activer = $ligne_boucle['activer'];
                $title = $ligne_boucle['title'];
                $meta_description = $ligne_boucle['meta_description'];
                $meta_keyword = $ligne_boucle['meta_keyword'];
                $date = $ligne_boucle['date'];

                $req_bouclem = $bdd->prepare("SELECT * FROM membres WHERE id=?");
                $req_bouclem->execute(array($ligne_boucle['id_membre']));
                $ligne_bouclem = $req_bouclem->fetch();
                $image_profil = $ligne_bouclem['image_profil'];
                $statut_compte = $ligne_bouclem['statut_compte'];
                $nom_prenom = "<b style='color: #ac0606;' >" . $ligne_bouclem['prenom'] . " <span style='text-transform: uppercase; color: #ac0606;' >" . substr($ligne_bouclem['nom'], 0, 1) . "</span>.</b>";

                $req_bouclem = $bdd->prepare("SELECT * FROM membres_type_de_compte WHERE id=?");
                $req_bouclem->execute(array($statut_compte));
                $ligne_bouclem = $req_bouclem->fetch();
                $statut_compte = $ligne_bouclem['Nom_type'];

                ///////////////////////////////SELECT
                $req_select = $bdd->prepare("SELECT COUNT(*) AS nbr_avis FROM membres_etablissements_avis WHERE id_etablissement=?");
                $req_select->execute(array($idoneinfos_artciles_blog));
                $ligne_select = $req_select->fetch();
                $req_select->closeCursor();
                $nbr_avis = $ligne_select['nbr_avis'];

                if (!empty($_SESSION['lat']) && !empty($_SESSION['lng'])) {
                    $kmToMe = getDistance($_SESSION['lat'], $_SESSION['lng'], $latitude, $longitude);
                }

                if ($kmToMe < $kmLimit2 && !empty($kmToMe) && $i < 6 || empty($kmToMe) && $i < 6) { // ON AFFICHE QUE LES ETABLISSEMENTS A MOINS DE 20km || !empty($kmToMe)
                    $vip_oui = "oui";
                    $i++;

            ?>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" style="margin-bottom: 10px;">

                        <div class="single_menu_product">

                            <div class="row" style="width: 100%;">

                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 text-center">
                                    <a href="/<?php echo $nom_etablissement_url; ?>">
                                        <?php if (!empty($image_profil)) { ?>
                                            <img class="imageRadius" src="/images/membres/<?php echo $ligne_boucle['pseudo']; ?>/<?php echo $image_profil; ?>" alt="<?php echo $image_profil; ?>">
                                        <?php
                                        } else {
                                        ?>
                                            <img class="imageRadius" src="/images/extra.jpg" alt="extra.jpg">
                                        <?php } ?>
                                    </a>
                                </div>

                                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                                    <div class="BlocTexteVipRes" style="padding: 10px; padding-top: 0px;">
                                        <h3 style="margin-top: -4px; font-size: 20px;"><a href="/<?php echo $nom_etablissement_url; ?>" class="" style="min-width: 200px;"><?php echo "$nom_etablissement"; ?></a></h3>
                                        <p style="margin-bottom: 0px;"><span class="uk-icon-comment"></span> <?php echo $statut_compte; ?></p>
                                        <p style="font-size: 14px; margin-bottom: 0px;">
                                            <?php ($avis); ?>
                                            <b><?php echo $avis; ?>/5</b> <u><?php echo "$nbr_avis"; ?> avis</u>
                                        </p>
                                        <p style="margin-bottom: 0px;">
                                            <span class="uk-icon-globe" style="color: #ac0606;"></span> <?php echo $nom_prenom; ?>
                                        </p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                <?php
                }
            }
            $req_boucle->closeCursor();

            if (empty($vip_oui)) {
                ?>
                <div class="alert alert-warning" style="text-align: center; margin: 10px; width: 100%;">
                    Il n'y a aucun extra à la une. <br />
                    Veuillez cliquez sur l'icône de géolocalisation. <br />
                    <a href="#" onclick="return false;" class="geoloc_marker">
                        <span class="geoloc uk-icon-map-marker" style="font-size: 20px;"></span>
                    </a>
                </div>
            <?php
            }
            ?>

        </div>

    </div>
</section>

<section class="small_pt">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-12 col-lg-12 col-md-12 animation animated fadeInUp" data-animation="fadeInUp" data-animation-delay="0.1s" style="animation-delay: 0.1s; opacity: 1;">
                <div class="heading_s2 text-center">
                    <h2>Plateforme dédiée aux Extras</h2>
                </div>
                <p class="text-center">
                    PEP’s EXTRA aide les professionnels Traiteurs / H&R à trouver des extras au plus près de leur lieu de prestation et ce, même en dernière minute.<br>
                    PEP’s EXTRA est également dédié aux Slasheurs et à toutes personnes qui ont besoin d’une activité ou d’un complément de revenus fiable.<br>
                    Comment : grâce à une plateforme de mise en relation qui s’appuie sur un agenda des disponibilité des Extras, la géolocalisation et un principe de covoiturage.
                </p>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="row">
                <div class="col-12 text-center mt-md-4">
                    <a class="btn btn-outline-default" href="/A-propos" aria-label="Qui sommes nous">Qui sommes nous</a>
                </div>
            </div>
        </div>
</section>



<section class="bg_blue small_pt small_pt" style="padding: 40px;">
    <div class="container">
        <div class="row align-items-center justify-content-between">
            <div class="col-md-8 animation" data-animation="fadeInUp" data-animation-delay="0.2s">
                <div class="text_white">
                    <h2>Système Pep's Extra en marque blanche ?</h2>
                    <p>Vous souhaitez gérer vos Extras avec la solution de Pep's Extra ? Découvrez la solution personnalisée et complète de Pep's en utilisant la base de donnée de notre réseau d'Extra.</p>
                </div>
            </div>
            <div class="col-md-4 animation" data-animation="fadeInUp" data-animation-delay="0.4s">
                <div class="" style="float: right;">
                    <form method="post" action="/Contact">
                        <button type="submit" title="Rechercher" class="btn btn-outline-white" name="submit" value="Submit">
                            Demander une démo
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- START SECTION BLOG -->
<section class="gray_bg">
    <div class="container">
        <div class="row">
            <div class="col-md-12 animation" data-animation="fadeInUp" data-animation-delay="0.2s">
                <div class="heading_s1 text-center">
                    <h2>Blog de Pep's</h2>
                </div>
                <p style="text-align: center;"></p>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="clearfix small_divider"></div>
            </div>
        </div>
        <div class="row blog_wrap justify-content-center animation" data-animation="fadeInUp" data-animation-delay="0.4s">
            <?php
            ///////////////////////////////SELECT BOUCLE
            $req_boucle = $bdd->prepare("SELECT * FROM codi_one_blog WHERE activer=? AND type_blog_artciles=? ORDER BY date_blog DESC LIMIT 0,3");
            $req_boucle->execute(array("oui", "standard"));
            while ($ligne_boucle = $req_boucle->fetch()) {
                ///////////////////////////////SELECT
                $req_select = $bdd->prepare("SELECT * FROM codi_one_blog_a_b_image WHERE id_page=?");
                $req_select->execute(array($ligne_boucle['id']));
                $ligne_select = $req_select->fetch();
                $req_select->closeCursor();
                $img_lienii = $ligne_select['img_lien2'];
                //affichage date
                $date_fiche = $ligne_boucle['date_blog'];
                $jour = date('d', $date_fiche);
                $mois = date('m', $date_fiche);
                $annee = date('y', $date_fiche);
                $b++;
                $texte_article_blog_source = strip_tags($ligne_boucle['texte_article']);
                $texte_article_blog_len = strlen($texte_article_blog_source);
                $texte_article_blog = substr($texte_article_blog_source, "0", "100");
                $texte_article_blog_texte = mb_substr($texte_article_blog_source, "0", 100 * 2);
                if ($texte_article_blog_len > $limitation_texte_liste_blog_cfg && $type_blog_artciles_blog != "texte") {
                    $texte_article_blog = "$texte_article_blog ...";
                } elseif ($texte_article_blog_len > ($limitation_texte_liste_blog_cfg * 2) && $type_blog_artciles_blog == "texte") {
                    $texte_article_blog = "$texte_article_blog_texte ...";
                }
            ?>
                <div class="col-lg-4 col-md-6 mb-md-4 mb-2 pb-2">
                    <div class="blog_post blog_style1">
                        <div class="blog_img">
                            <a href="/<?= $ligne_boucle['url_fiche_blog']; ?>" title="<?= $ligne_boucle['titre_blog_1']; ?>">
                                <img src="/images/blog/<?= $img_lienii; ?>" alt="<?= $ligne_boucle['titre_blog_1']; ?>">
                            </a>
                            <span class="post_date bg_blue text-light"><?php echo "" . $jour . "-" . $mois . "-" . $annee . ""; ?></span>
                        </div>
                        <div class="blog_content bg-white">
                            <div class="blog_text">
                                <h3 class="blog_title" style="width: 100%; text-align: center; font-size: 18px;"><a href="/<?= $ligne_boucle['url_fiche_blog']; ?>" title="<?= $ligne_boucle['titre_blog_1']; ?>"><u><?= $ligne_boucle['titre_blog_1']; ?></u></a></h3>
                                <p style="min-height: 80px; text-align: center;"><?php echo $texte_article_blog; ?></p>
                                <a href="/<?= $ligne_boucle['url_fiche_blog']; ?>" class="btn btn-default" style="width: 100%;" aria-label="<?= $ligne_boucle['titre_blog_1']; ?>" title="<?= $ligne_boucle['titre_blog_1']; ?>">Lire l'article </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            }
            $req_boucle->closeCursor();
            ?>
        </div>
</section>
<!-- END SECTION BLOG -->


<?php
$req_select = $bdd->prepare("SELECT count(*) as avis FROM membres_etablissements_avis order by note DESC LIMIT 0,4");
$req_select->execute();
$ligne_select = $req_select->fetch();
$req_select->closeCursor();


if ($ligne_select['avis'] != 0) {
?>
    <section class="bg-dark" style="background-color: #003E49 !important;">
        <div class="container">

            <div class="row justify-content-center">
                <div class="col-xl-12 col-lg-12 col-md-12 animation animated fadeInUp" data-animation="fadeInUp" data-animation-delay="0.1s" style="animation-delay: 0.1s; opacity: 1;">
                    <div class="heading_s2 text-center text_white">
                        <h2>Les avis des professionnels sur Pep's</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-12">

                <div class="testimonial_slider testimonial_style2 text_white carousel_slide3 owl-carousel owl-theme" data-margin="10" data-center="true" data-loop="true" data-autoplay="true">

                    <?php

                    $req_boucle = $bdd->prepare("SELECT * FROM membres_etablissements_avis order by note DESC LIMIT 0,4");
                    $req_boucle->execute();
                    while ($ligne_boucle = $req_boucle->fetch()) {
                        $idoneinfos_avis = $ligne_boucle['id'];

                        ///////////////////////////////SELECT
                        $req_select = $bdd->prepare("SELECT * FROM membres_etablissements WHERE id=?");
                        $req_select->execute(array($ligne_boucle['id_etablissement']));
                        $ligne_select = $req_select->fetch();
                        $req_select->closeCursor();
                        $idoneinfos = $ligne_select['id'];
                        $nom_etablissement = $ligne_select['nom_etablissement'];
                        $nom_etablissement_url = $ligne_select['nom_etablissement_url'];

                    ?>




                        <div class="item">
                            <div class="testimonial_box">
                                <div class="testi_meta">
                                    <div class="quote" style="margin-bottom: 10px;">
                                        <img src="/template/assets/images/quote.png" alt="quote" style="width: 40px;">
                                    </div>
                                    <span><?php ($ligne_boucle['note']); ?>
                                        <b><?php echo $ligne_boucle['note']; ?>/5</b>
                                    </span>
                                    <p><?php echo $ligne_boucle['commentaire']; ?></p>
                                </div>
                                <div class="testimonial_cl_info">
                                    <div class="testimonial_img">
                                    </div>
                                    <div class="client_info">
                                        <h6><a href="/<?php echo $nom_etablissement_url; ?>"><b><?php echo "$nom_etablissement"; ?> </b></a> </h6>
                                    </div>
                                </div>
                            </div>
                        </div>


                    <?php
                    }
                    $req_boucle->closeCursor();

                    ?>

                </div>

                <?php
                if (empty($idoneinfos_avis)) {
                ?>
                    <div class="alert alert-warning" style="text-align: left; width: 100%;">
                        Il n'y a aucun avis.
                    </div>
                <?php
                }
                ?>

            </div>

            <div class="justify-content-center" style="text-align: center; margin-top: 40px; margin-bottom: 10px;">
                <a href="/Avis-plateforme" class="btn btn-default btn-default2">Tous les avis </a>
            </div>

        </div>
    </section>

<?php
}
?>

<?php
//PUBLICITE
$type_section = "Footer";
include('index-accueil-publicite.php');
?>

<section class="bg_blue small_pt small_pt" style="padding: 20px; background-color: #B58210;">
    <div class="container">
        <div class="row align-items-center justify-content-between">
            <div class="col-md-8 animation" data-animation="fadeInUp" data-animation-delay="0.2s">
                <div class="text_white">
                    <p>Vous êtes un Professionnel Traiteur, Restaurateur, Hôtelier..? Vous cherchez des Extras disponibles pour une/des prestations?</p>
                    <p>Inscrivez-vous gratuitement et optez pour la formule la plus adaptée à vos besoins !</p>
                </div>
            </div>
            <div class="col-md-4 animation" data-animation="fadeInUp" data-animation-delay="0.4s">
                <div class="" style="float: right;">
                    <form method="post" action="/Plateforme/Extras/1">
                        <button type="submit" title="Rechercher" class="btn btn-outline-white" name="submit" value="Submit">
                            Rechercher
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- START SECTION CLIENT LOGO -->