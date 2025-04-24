
<?php
if(empty($_GET['page'])){
?>
<!-- START menu -->
<header class="header light_skin hover_menu_style3">
<?php
}else{
?>
<header class="light_skin hover_menu_style3">
<?php
}
?>

<div class="container">

<nav class="navbar navbar-expand-lg">

    <a class="navbar-brand" href="/">
        <img class="logo_light" src="/images/peps-extra1.png" alt="PEP's" width="30" />
        <img class="logo_dark" src="/images/Logo-PEP's.jpg?v=1" alt="PEP's" width="50" />
        <img class="logo_default" src="/images/Logo-PEP's.jpg?v=1" alt="PEP's" width="50" />
    </a>

    <!-- Remplacement du texte "Menu" par une icône de burger -->
    <button style="margin-top: 15px;" class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"> <i class="uk-icon-bars" style="color: white;"></i></button>

<div class="navbar-collapse justify-content-end collapse" id="navbarSupportedContent">
    <ul class="navbar-nav">
        <li class="text-white">
            <a class="nav-link" href="/">Accueil</a>
        </li>
        <li>
            <a class="nav-link" href="/Offres">Offres CDD/CDI</a>
        </li>

    <?php if (empty($user)) {  ?>
        <li class="dropdown">
            <a class="dropdown-toggle nav-link" href="#" data-toggle="dropdown">Extra</a>
            <div class="dropdown-menu">
                <ul>
                    <li>
                        <a onclick="return false;" class="nav-link pxp-header-inscription" href="#" data-type="extra">Inscription</a>
                    </li>
                    <li>
                        <a onclick="return false;" class="nav-link pxp-header-user" href="#">Connexion</a>
                    </li>
                    <li>
                        <a class="nav-link" href="/Contact">Contact</a>
                    </li>
                </ul>
            </div>
        </li>
    <?php } ?>

    <?php if (empty($user)) {  ?>
        <li class="dropdown">
            <a class="dropdown-toggle nav-link" href="#" data-toggle="dropdown">Professionnel</a>
            <div class="dropdown-menu">
                <ul>
                    <li>
                        <a class="nav-link" href="/Trouver-des-extras" title="Professionnels">Trouvez des extras</a>
                    </li>
                    <li>
                        <a onclick="return false;" class="nav-link pxp-header-inscription" href="#" data-type="pro">Inscription</a>
                    </li>
                    <li>
                        <a onclick="return false;" class="nav-link pxp-header-user" href="#">Connexion</a>
                    </li>
                    <li>
                        <a class="nav-link" href="/Contact">Contact</a>
                    </li>
                </ul>
            </div>
        </li>
    <?php } ?>

    <li class="dropdown">
        <a class="nav-link" href="/Plateforme/Extras/1">Extras par département</a>
    </li>

    <li class="dropdown">
        <a class="dropdown-toggle nav-link" href="#" data-toggle="dropdown">Blog</a>
        <div class="dropdown-menu">
            <ul>
                <?php
                ///////////////////////////////SELECT BOUCLE
                $req_boucle = $bdd->prepare("SELECT * FROM codi_one_blog_categories WHERE activer=? ORDER by nom_categorie ASC");
                $req_boucle->execute(array("oui"));
                while ($ligne_boucle = $req_boucle->fetch()) {
                    $idoneinfos = $ligne_boucle['id'];
                    $nom_categorie = $ligne_boucle['nom_categorie'];
                    $nom_url_categorie = $ligne_boucle['nom_url_categorie'];
                    $nbr_consultation_blog = $ligne_boucle['nbr_consultation_blog'];
                    $Title = $ligne_boucle['Title'];
                    $Metas_description = $ligne_boucle['Metas_description'];
                    $Metas_mots_cles = $ligne_boucle['Metas_mots_cles'];
                    $activer_categorie_blog = $ligne_boucle['activer'];
                    $date_categorie_blog = $ligne_boucle['date'];
                    $Position_categorie = $ligne_boucle['Position_categorie'];
                    $Ancre_menu = $ligne_boucle['Ancre_menu'];
                ?>
                    <li><a class="dropdown-item nav-link nav_item" href="<?php echo "$http"; ?><?php echo "$nomsiteweb"; ?>/<?php echo "$nom_url_categorie"; ?>" title="<?php echo "$Ancre_menu"; ?>"><?php echo "$Ancre_menu"; ?></a></li>
                <?php
                }
                $req_boucle->closeCursor();
                ?>
            </ul>
        </div>
    </li>

<?php if (!empty($user)) {  ?>

    <?php if ($statut_compte_oo != 1 && $statut_compte_oo != 6) { ?>
        <li class="dropdown">
            <a class="dropdown-toggle nav-link" href="#" data-toggle="dropdown">Mes missions</a>
            <div class="dropdown-menu">
                <ul>
                <?php
                ///////////////////////////////SELECT BOUCLE
                $req_select = $bdd->prepare("SELECT * FROM membres_etablissements WHERE id_membre=? AND type_demande=1");
                $req_select->execute(array($id_oo));
                $ligne_select_e = $req_select->fetch();
                $req_select->closeCursor();
                ?>
                <li><a class="dropdown-item nav-link nav_item" href="<?php echo "$http"; ?><?php echo "$nomsiteweb"; ?>/<?php echo $ligne_select_e['nom_etablissement_url']; ?>" title="<?php echo "Mon profil public"; ?>"><?php echo "Mon profil public"; ?></a></li>
                <li><a class="dropdown-item nav-link nav_item" href="<?php echo "$http"; ?><?php echo "$nomsiteweb"; ?>/Mes-missions" title="<?php echo "Mes missions"; ?>"><?php echo "Mes missions"; ?></a></li>
                <li>
                    <div style="padding-left: 10px;">
                        <?php if ($ligne_selectcoui2['nbr_demandes_oui'] > 0) {
                            echo " <span class='badge badge-success' title='Validée' >+" . $ligne_selectcoui2['nbr_demandes_oui'] . "</span>";
                        } else {
                            echo "<span class='badge badge-success' title='Validée' >" . $ligne_selectcoui2['nbr_demandes_oui'] . "</span>";
                        } ?>
                        <?php if ($ligne_selectcattente2['nbr_demandes_attente'] > 0) {
                            echo " <span class='badge badge-warning' title='En attente' >+" . $ligne_selectcattente2['nbr_demandes_attente'] . "</span>";
                        } else {
                            echo "<span class='badge badge-warning' title='En attente' >" . $ligne_selectcattente2['nbr_demandes_attente'] . "</span>";
                        } ?>
                        <?php if ($ligne_selectcnon2['nbr_demandes_non'] > 0) {
                            echo " <span class='badge badge-danger' title='Refusée' >+" . $ligne_selectcnon2['nbr_demandes_non'] . "</span>";
                        } else {
                            echo "<span class='badge badge-danger' title='Refusée'>" . $ligne_selectcnon2['nbr_demandes_non'] . "</span>";
                        } ?>
                    </div>
                </li>
                    <li><a class="dropdown-item nav-link nav_item" href="<?php echo "$http"; ?><?php echo "$nomsiteweb"; ?>/Messagerie.html" title="<?php echo "Messagerie"; ?>">
                    <?php echo "Messagerie"; ?> <?php if ($total_message_non_lu > 0) {echo " <span class='badge badge-success'>" . $total_message_non_lu . "</span>"; } ?></a></li>
                </ul>
            </div>
        </li>
    <?php } ?>

<li class="dropdown">
    <a class="dropdown-toggle nav-link" href="#" data-toggle="dropdown">Mon compte
        <span class="badge badge-danger" title="Messages"><?php echo $total_message_non_lu; ?></span>
    </a>
    <div class="dropdown-menu">
        <ul>
            <?php
            ////////COMPTE CLIENT
            if ($statut_compte_oo != 1) {
            ?>
                <li style="padding-left:5px; font-weight: bold;"><?php echo "$nom_oo $prenom_oo"; ?></li>
            <?php
            }
            ////////COMPTE CLIENT
            if ($statut_compte_oo != 1 && $statut_compte_oo != 6) {
            ?>
                <li><a class="dropdown-item nav-link nav_item" href="https://www.shine.fr/?utm_source=affiliation&utm_medium=affilae&utm_campaign=PEP%27s%20EXTRA&ae=614" title="">Tu es extra Auto-entrepreneur?
                        <br /> Ouvre ton compte pro ici</a></li>
            <?php
            }
            ?>

            <?php
            //////////////////////////////////SI ADMIN
            if ($admin_oo > 0) {
                echo "<li class='dropdown-item' ><a class='test' href='/administration/index-admin.php' ><span class='uk-icon-cogs'></span> Admin</a><li>";
            }
            if ($statut_compte_oo == 1) {
            ?>
                <li><a class="dropdown-item nav-link nav_item" href="<?php echo "$http"; ?><?php echo "$nomsiteweb"; ?>/faq-etablissements.html" title="<?php echo "FAQ Etablissements"; ?>"><?php echo "FAQ Etablissements"; ?></a></li>
            <?php
            }

            ?>
            <li><a class="dropdown-item nav-link nav_item" href="<?php echo "$http"; ?><?php echo "$nomsiteweb"; ?>/Gestion-de-votre-compte.html" title="<?php echo "Mes informations"; ?>"><?php echo "Mes informations"; ?> </a></li>
            <?php
            if ($statut_compte_oo == 1) {
            ?>
                <li><a class="dropdown-item nav-link nav_item" href="<?php echo "$http"; ?><?php echo "$nomsiteweb"; ?>/<?php echo "Avatar"; ?>" title="<?php echo "Photo"; ?>"><?php echo "Ma photo"; ?></a></li>
            <?php
            }
            if ($statut_compte_oo >= 0) {
            ?>
                <li><a class="dropdown-item nav-link nav_item" href="<?php echo "$http"; ?><?php echo "$nomsiteweb"; ?>/Blocs-publicites" title="<?php echo "Publicités"; ?>"><?php echo "Mes publicités"; ?></a></li>
            <?php
            }
            ?>
            <?php
            ////////COMPTE CLIENT
            if ($statut_compte_oo != 1 && $statut_compte_oo != 6) {

            ///////////////////////////////SELECT BOUCLE
            $req_select = $bdd->prepare("SELECT * FROM membres_etablissements WHERE id_membre=? AND nom_etablissement!='' AND type_demande=1");
            $req_select->execute(array($id_oo));
            $ligne_select_e = $req_select->fetch();
            $req_select->closeCursor();
            if (empty($ligne_select_e['id'])) {
                $colorp = "color: red !important;";
            }

            ///////////////////////////////SELECT BOUCLE
            $req_select = $bdd->prepare("SELECT * FROM membres_etablissements WHERE id_membre=? AND type_demande=1 AND carte_identite_r!='' AND carte_identite_v!='' AND carte_de_ss!='' AND rib!='' ");
            $req_select->execute(array($id_oo));
            $ligne_select_o = $req_select->fetch();
            $req_select->closeCursor();
            if (empty($ligne_select_o['id'])) {
                $coloro = "color: red !important;";
            }

            ///////////////////////////////SELECT BOUCLE
            $req_select = $bdd->prepare("SELECT * FROM membres_etablissements_horaires WHERE id_membre=? AND id_etablissement!=''");
            $req_select->execute(array($id_oo));
            $ligne_select_i = $req_select->fetch();
            $req_select->closeCursor();
            if (empty($ligne_select_i['id'])) {
                $colori = "color: red !important;";
                }

            ?>
                <li><a class="dropdown-item nav-link nav_item" style="<?php echo $colorp; ?>" href="<?php echo "$http"; ?><?php echo "$nomsiteweb"; ?>/Guide" title="<?php echo "Guide"; ?>"><?php echo "Guide"; ?></a></li>
                <li>
                    <hr />
                </li>
                <li><a class="dropdown-item nav-link nav_item" style="<?php echo $colorp; ?>" href="<?php echo "$http"; ?><?php echo "$nomsiteweb"; ?>/Mon-profil" title="<?php echo "Mon profil"; ?>"><?php echo "1.Mon profil"; ?></a></li>
                <li><a class="dropdown-item nav-link nav_item" style="<?php if (empty($image_profil_oo)) {
                                                                            echo "color: red;";
                                                                        } ?>" href="<?php echo "$http"; ?><?php echo "$nomsiteweb"; ?>/<?php echo "Avatar"; ?>" title="<?php echo "Ma photo"; ?>"><?php echo "2.Ma photo"; ?></a></li>
                <li><a class="dropdown-item nav-link nav_item" style="<?php echo $coloro; ?>" href="<?php echo "$http"; ?><?php echo "$nomsiteweb"; ?>/Mes-documents" title="<?php echo "Mes documents"; ?>"><?php echo "3.Mes documents"; ?></a></li>
                <li><a class="dropdown-item nav-link nav_item" style="<?php echo $colori; ?>" href="<?php echo "$http"; ?><?php echo "$nomsiteweb"; ?>/Disponibilites" title="<?php echo "Disponibilités"; ?>"><?php echo "4.Mes disponibilités"; ?></a></li>
                <li><a class="dropdown-item nav-link nav_item" href="<?php echo "$http"; ?><?php echo "$nomsiteweb"; ?>/Agenda" title="<?php echo "Agenda"; ?>"><?php echo "5.Mon agenda"; ?></a></li>
                <li>
                    <hr />
                </li>
                <?php
                }
                ?>
                <?php
                ////////COMPTE CLIENT
                if ($statut_compte_oo == 1) {
                 ?>
                <hr />
                <li><a class="dropdown-item nav-link nav_item" href="<?php echo "$http"; ?><?php echo "$nomsiteweb"; ?>/Missions" title="<?php echo "Missions"; ?>"><?php echo "Missions"; ?></a></li>
                <li><a class="dropdown-item nav-link nav_item" href="<?php echo "$http"; ?><?php echo "$nomsiteweb"; ?>/Demandes-de-mission" title="<?php echo "Demandes de mission"; ?>"><?php echo "Demandes de mission"; ?></a></li>
                <li><a class="dropdown-item nav-link nav_item" href="<?php echo "$http"; ?><?php echo "$nomsiteweb"; ?>/Agendapro" title="<?php echo "Agenda des missions"; ?>"><?php echo "Agenda des missions"; ?></a></li>
                <li>
                <div style="padding-left: 10px;">
                    <?php if ($ligne_selectcoui['nbr_demandes_oui'] > 0) {
                        echo " <span class='badge badge-success' title='Validée' >+" . $ligne_selectcoui['nbr_demandes_oui'] . "</span>";
                    } else {
                        echo "<span class='badge badge-success' title='Validée' >" . $ligne_selectcoui['nbr_demandes_oui'] . "</span>";
                    } ?>
                    <?php if ($ligne_selectcattente['nbr_demandes_attente'] > 0) {
                        echo " <span class='badge badge-warning' title='En attente' >+" . $ligne_selectcattente['nbr_demandes_attente'] . "</span>";
                    } else {
                        echo "<span class='badge badge-warning' title='En attente' >" . $ligne_selectcattente['nbr_demandes_attente'] . "</span>";
                    } ?>
                    <?php if ($ligne_selectcnon['nbr_demandes_non'] > 0) {
                        echo " <span class='badge badge-danger' title='Refusée' >+" . $ligne_selectcnon['nbr_demandes_non'] . "</span>";
                    } else {
                        echo "<span class='badge badge-danger' title='Refusée'>" . $ligne_selectcnon['nbr_demandes_non'] . "</span>";
                    } ?>
                </div>
            </li>

            <hr />
            <li><a class="dropdown-item nav-link nav_item" href="<?php echo "$http"; ?><?php echo "$nomsiteweb"; ?>/Favoris" title="<?php echo "Favoris"; ?>"><?php echo "Extras en favoris"; ?></a></li>
            <?php
            }
            ?>
            <?php
            if ($statut_compte_oo == 1) {
            ?>
                <li><a class="dropdown-item nav-link nav_item" href="<?php echo "$http"; ?><?php echo "$nomsiteweb"; ?>/Messagerie.html" title="<?php echo "Messagerie"; ?>"><?php echo "Messagerie"; ?> <span class="badge badge-danger"><?php echo $total_message_non_lu; ?></span></a></li>
            <?php
            }
            if ($statut_compte_oo != 1 && $statut_compte_oo != 6) {
            ?>
                <li><a class="dropdown-item nav-link nav_item" href="<?php echo "$http"; ?><?php echo "$nomsiteweb"; ?>/faq-extras.html" title="<?php echo "FAQ Extras"; ?>"><?php echo "FAQ Extras"; ?></a></li>
            <?php
            }
            ?>
            <li><a class="dropdown-item nav-link nav_item" href="<?php echo "$http"; ?><?php echo "$nomsiteweb"; ?>/Notifications" title="<?php echo "Notifications"; ?>"><?php echo "Notifications"; ?> <?php if ($nbr_notifications > 0) {
                                                                                                                                                                                                                echo " <span class='badge badge-success'>" . $nbr_notifications . "</span>";
                                                                                                                                                                                                            } ?> </a></li>
            <li><a class="dropdown-item nav-link nav_item" id='Deconnexion' href='#'>Déconnexion</a></li>
        </ul>
    </div>
</li>

<?php
////////COMPTE CLIENT
if ($statut_compte_oo == 1) {
?>
    <li class="dropdown">
        <a class="dropdown-toggle nav-link" href="#" data-toggle="dropdown">Services </a>
        <div class="dropdown-menu">
            <ul>
                <li style="padding-left:5px;"><?php echo "$nom_oo $prenom_oo"; ?></li>
                <?php
                if (!empty($abonnement_information)) {
                ?>
                    <li class="dropdown-item nav-link nav_item" style="padding-left:10px; font-weight: bold;">Formule : <?php echo "$abonnement_information"; ?></li>
                <?php
                }
                ?>
                <li class="dropdown-item nav-link nav_item" style="padding-left:10px; font-weight: bold;">Crédits : 
                <?php if ($nbr_prestation > 0) {
                echo " <span class='badge badge-success'>+" . $nbr_prestation . "</span>";
            } else {
                echo "<span class='badge badge-danger'>" . $nbr_prestation . "</span>";
            } ?></li>
                <hr />

            <?php
            ////////COMPTE CLIENT
            if ($statut_compte_oo == 1) {
            ?>
                <li><a class="dropdown-item nav-link nav_item" href="https://c3po.link/QMxRrEQUKt" target="_blank" title="<?php echo "PAIES+"; ?>"><?php echo "PAIES+"; ?></a></li>
                <li><a class="dropdown-item nav-link nav_item" href="<?php echo "$http"; ?><?php echo "$nomsiteweb"; ?>/Formules" title="<?php echo "Formules abonnement"; ?>"><?php echo "Formules abonnement"; ?></a></li>
                <li><a class="dropdown-item nav-link nav_item" href="<?php echo "$http"; ?><?php echo "$nomsiteweb"; ?>/Formules-credits" title="<?php echo "Acheter des crédits"; ?>"><?php echo "Acheter des crédits"; ?></a></li>
                <li><a class="dropdown-item nav-link nav_item" href="<?php echo "$http"; ?><?php echo "$nomsiteweb"; ?>/Demande-de-devis" title="<?php echo "Demande de devis"; ?>"><?php echo "Demande de devis"; ?></a></li>
                <li><a class="dropdown-item nav-link nav_item" href="<?php echo "$http"; ?><?php echo "$nomsiteweb"; ?>/Commandes-cdd-cdi" title="<?php echo "Commandes de CDD ET CDI"; ?>"><?php echo "Commandes de CDD ET CDI"; ?></a></li>
                <?php if ($premium == "oui") { ?> <li><a class="dropdown-item nav-link nav_item" href="<?php echo "$http"; ?><?php echo "$nomsiteweb"; ?>/Commandes-de-formation" title="<?php echo "Commandes de formation"; ?>"><?php echo "Commandes de formation"; ?></a></li> <?php } ?>
            <?php
            }
            ?>
        <?php
            ////////COMPTE CLIENT
            if ($statut_compte_oo == 1) {
            ?>
                <li><a class="dropdown-item nav-link nav_item" href="<?php echo "$http"; ?><?php echo "$nomsiteweb"; ?>/<?php echo "Factures"; ?>" title="<?php echo "Factures"; ?>"><?php echo "Mes factures"; ?></a></li>
            <?php
            }
            ?>
            <li><a class="dropdown-item nav-link nav_item" id='Deconnexion' href='#'>Déconnexion</a></li>
        </ul>
    </div>
</li>
    <?php
    }
    ?>

<?php
}
?>

</ul>
</div>

<ul class="navbar-nav attr-nav align-items-center">

<script>
    $(document).ready(function() {
        $(document).on('click', '.search_icon', function() {
            $('#formRecherche2').submit();
        })
    });
</script>

<li><a href="#" class="nav-link search_trigger" onclick="return false;" title="Chercher"><i class="ion-ios-search-strong"></i></a>
    <div class="search-overlay">
        <span class="close-search"><i class="ion-ios-close-empty"></i></span>
        <div class="search_wrap">
            <form id="formRecherche2" method="post" action="/Plateforme/<?php echo $nom_annuaire_1; ?>/<?php echo $nom_annuaire_1_id; ?>">
                <input name="mot_cle1" type="text" placeholder="Rechercher par mot clé ..." class="form-control" id="search_input">
                <button type="submit" class="search_icon" onclick="return false;"><i class="ion-ios-search-strong"></i></button>
            </form>
        </div>
    </div>
</li>

<?php if (($statut_compte_oo == 1)) { ?>
    <li>
        <a href="/Dashboard-etablissement" class="nav-link" title="Dashboard"><i class="uk-icon-cogs search_icon" style="position: relative; top: 0px;"></i></a>

    </li>
<?php } ?>


</ul>


</nav>

</div>

<!-- Section Hero -->
<div class="hero-section">
<p class="text-white">PEP'S EXTRA</p>
<h2 class="text-white">Dédiée aux professionnels <br> Traiteurs – H&R et aux Extras</h2>
<div class="col-md-12">
    <button class="btn btn-outline-light card-button">Je suis un Pro</button>
    <button class="btn btn-outline-light card-button">Je suis un Extra</button>
</div>
</div>
</header>

<!-- START HEADER -->