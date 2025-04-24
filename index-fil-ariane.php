<?php
////////////////////SI CONNECTE 
if (!empty($user) && $id_oo == 8) {
?>

	<div class="page-titles">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12">

			<?php
		}

		////////////////////////////////////////////////////////////////////////////////////////////FICHE ANNONCE
		if ($_GET['page'] == "Fiche") {

			$req_select0 = $bdd->prepare("SELECT * FROM membres_etablissements WHERE id=?");
			$req_select0->execute(array($_GET['idaction']));
			$ligne_select0 = $req_select0->fetch();
			$req_select0->closeCursor();

			///////////////////////////////SELECT CATEGORIE
			$req_select = $bdd->prepare("SELECT * FROM pages_categories WHERE id=?");
			$req_select->execute(array($ligne_select0['id_categorie']));
			$ligne_select = $req_select->fetch();
			$req_select->closeCursor();
			$nom_categorie = $ligne_select['nom_categorie'];
			$nom_categorie_url = $ligne_select['nom_categorie_url'];

			///////////////////////////////SELECT VILLE
			$req_select = $bdd->prepare("SELECT * FROM pages_categories_pays_villes WHERE id=?");
			$req_select->execute(array($ligne_select0['id_ville']));
			$ligne_select = $req_select->fetch();
			$req_select->closeCursor();
			$nom_pays_ville = $ligne_select['nom_pays_ville'];
			$nom_pays_ville_id = $ligne_select['id'];

			if ($ligne_select0['type_demande'] == 1) {
				$ancre_type = "$nom_annuaire_1_titre";
				$url = "/Plateforme/$nom_annuaire_1/$nom_annuaire_1_id";
				$url2 = "$nom_annuaire_1";
				$id2 = "$nom_annuaire_1_id";
			} elseif ($ligne_select0['type_demande'] == 2) {
				$ancre_type = "$nom_annuaire_2_titre";
				$url = "/Plateforme/$nom_annuaire_2/$nom_annuaire_2_id";
				$url2 = "$nom_annuaire_2";
				$id2 = "$nom_annuaire_2_id";
			}


			$req_selectm = $bdd->prepare("SELECT * FROM membres WHERE id=?");
			$req_selectm->execute(array($ligne_select['id_membre']));
			$ligne_selectm = $req_selectm->fetch();
			$req_selectm->closeCursor();

			$req_selectmt = $bdd->prepare("SELECT * FROM membres_type_de_compte WHERE id=?");
			$req_selectmt->execute(array($ligne_selectm['statut_compte']));
			$ligne_selectmt = $req_selectmt->fetch();
			$req_selectmt->closeCursor();

			?>
				<div class="row align-items-center mb-5">
					<div class="col-sm-12 text-center">
						<div class="page-title">
							<h1><?php echo $ligne_select0['nom_etablissement']; ?> / <?php echo $ligne_select0['ville']; ?> / <?php echo $ligne_select0['cp']; ?></h1>
						</div>
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb justify-content-center">
								<li class="breadcrumb-item"><a href="<?php echo "/"; ?>"><?= $nom_proprietaire; ?></a></li>
								<?php
								if ($ligne_select0['type_demande'] == 1) {
								?>
									<li class="breadcrumb-item"><a href="<?php echo $url; ?>"><?php echo $ancre_type; ?></a></li>
								<?php
								}
								?>
								<?php
								if ($ligne_select0['type_demande'] == 1) {
									$req_boucle = $bdd->prepare("SELECT * FROM membres_etablissements_categories WHERE id_etablissement=?");
									$req_boucle->execute(array($_GET['idaction']));
									while ($ligne_boucle = $req_boucle->fetch()) {

										///////////////////////////////SELECT
										$req_select = $bdd->prepare("SELECT * FROM pages_categories WHERE id=?");
										$req_select->execute(array($ligne_boucle['id_categorie']));
										$ligne_select = $req_select->fetch();
										$req_select->closeCursor();
								?>
										<li class="breadcrumb-item"><a href="<?php echo "/Plateforme/" . $url2 . "/" . $ligne_select['nom_categorie_url'] . "/" . $id2 . "/" . $ligne_select['id'] . ""; ?>"><?php echo $ligne_select['nom_categorie']; ?></a></li>
									<?php
									}
									$req_boucle->closeCursor();
								} else {
									$req_boucle = $bdd->prepare("SELECT * FROM membres_etablissements_categories WHERE id_etablissement=?");
									$req_boucle->execute(array($_GET['idaction']));
									while ($ligne_boucle = $req_boucle->fetch()) {

										///////////////////////////////SELECT
										$req_select = $bdd->prepare("SELECT * FROM pages_categories WHERE id=?");
										$req_select->execute(array($ligne_boucle['id_categorie']));
										$ligne_select = $req_select->fetch();
										$req_select->closeCursor();
									?>
										<li class="breadcrumb-item"><?php echo $ligne_select['nom_categorie']; ?></li>
								<?php
									}
									$req_boucle->closeCursor();
								}
								?>
								<li class="breadcrumb-item active" aria-current="page"><?php echo $ligne_select0['nom_etablissement']; ?> / <?php echo $ligne_select0['ville']; ?> / <?php echo $ligne_select0['cp']; ?></li>

							</ol>

							<?php
							if ($ligne_select0['type_demande'] == 1 && (empty($user) || $statut_compte_oo == 1) && $ligne_select0['mode_vacance'] != "oui") {
								///////////////////////////////SELECT
								$req_select = $bdd->prepare("SELECT * FROM membres_etablissements WHERE id=?");
								$req_select->execute(array($_GET['idaction']));
								$ligne_select = $req_select->fetch();
								$req_select->closeCursor();
								///////////////////////////////SELECT
								$req_select = $bdd->prepare("SELECT * FROM membres_etablissements_demandes_missions WHERE id_membre=? AND id_prestataire=? AND id_etablissement_mission=?");
								$req_select->execute(array($id_oo, $ligne_select['id_membre'], $_SESSION['mission_id']));
								$ligne_select = $req_select->fetch();
								$req_select->closeCursor();
								$id_selectionner_oui = $ligne_select['id'];
							?>

							<?php
							} elseif ($ligne_select0['type_demande'] == 2) {
								///////////////////////////////SELECT
								$req_select = $bdd->prepare("SELECT * FROM membres_etablissements WHERE id=?");
								$req_select->execute(array($_GET['idaction']));
								$ligne_select = $req_select->fetch();
								$req_select->closeCursor();
							?>
								<span style='font-weight: bold;'>Mission du <?php if (!empty($ligne_select['date_debut']) && !empty($ligne_select['date_fin'])) {
																				echo date('d-m-Y', $ligne_select['date_debut']);
																				echo " au ";
																				echo date('d-m-Y', $ligne_select['date_fin']);
																			} ?> </span> <br />
								<?php
								if ($ligne_select['lundi'] == "oui") {
									echo "<span class='badge badge-info' style='margin: 2px;' > Lundi </span>";
								}
								if ($ligne_select['mardi'] == "oui") {
									echo "<span class='badge badge-info' style='margin: 2px;' > Mardi </span> ";
								}
								if ($ligne_select['mercredi'] == "oui") {
									echo "<span class='badge badge-info' style='margin: 2px;' > Mercredi </span> ";
								}
								if ($ligne_select['jeudi'] == "oui") {
									echo "<span class='badge badge-info' style='margin: 2px;' > Jeudi </span> ";
								}
								if ($ligne_select['vendredi'] == "oui") {
									echo "<span class='badge badge-info' style='margin: 2px;' > Vendredi </span> ";
								}
								if ($ligne_select['samedi'] == "oui") {
									echo "<span class='badge badge-info' style='margin: 2px;' > Samedi </span> ";
								}
								if ($ligne_select['dimanche'] == "oui") {
									echo "<span class='badge badge-info' style='margin: 2px;' > Dimanche </span> ";
								}
								?>
							<?php
							}
							?>

						</nav>
					</div>
				</div>

			<?php
		}if ($_GET['page'] == "Fiche-annonce") {

			$req_select0 = $bdd->prepare("SELECT * FROM membres_annonces WHERE id=?");
			$req_select0->execute(array($_GET['idaction']));
			$ligne_select0 = $req_select0->fetch();
			$req_select0->closeCursor();

			///////////////////////////////SELECT CATEGORIE
			$req_select = $bdd->prepare("SELECT * FROM pages_categories WHERE id=?");
			$req_select->execute(array($ligne_select0['id_categorie']));
			$ligne_select = $req_select->fetch();
			$req_select->closeCursor();
			$nom_categorie = $ligne_select['nom_categorie'];
			$nom_categorie_url = $ligne_select['nom_categorie_url'];

			$req_selectm = $bdd->prepare("SELECT * FROM membres WHERE id=?");
			$req_selectm->execute(array($ligne_select0['id_membre']));
			$ligne_selectm = $req_selectm->fetch();
			$req_selectm->closeCursor();


			?>
				<div class="row align-items-center mb-5">
					<div class="col-sm-12 text-center">
						<div class="page-title">
							<h1><?php echo $ligne_select0['titre_annonce']; ?></h1>
						</div>
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb justify-content-center">
								<li class="breadcrumb-item"><a href="<?php echo "/"; ?>"><?= $nom_proprietaire; ?></a></li>
							
								<li class="breadcrumb-item active" aria-current="page"><?php echo $ligne_select0['titre_annonce']; ?></li>

							</ol>
						</nav>
					</div>
				</div>

			<?php
		} elseif ($_GET['page'] == "Plateforme" && !empty($_GET['ville'])) {

			///////////////////////////////SELECT CATEGORIE
			$req_select = $bdd->prepare("SELECT * FROM pages_categories WHERE nom_categorie_url=?");
			$req_select->execute(array($_GET['categorie']));
			$ligne_select = $req_select->fetch();
			$req_select->closeCursor();
			//$nom_categorie = $ligne_select['nom_categorie'];
			//$nom_categorie_url = $ligne_select['nom_categorie_url'];
			$nom_categorie = "Voyages Cacher";
			$nom_categorie_url = "Voyages-Cacher";


			///////////////////////////////SELECT TYPE VILLE
			$req_select = $bdd->prepare("SELECT * FROM pages_categories_pays_villes WHERE nom_pays_ville_url=?");
			$req_select->execute(array($_GET['ville']));
			$ligne_select = $req_select->fetch();
			$req_select->closeCursor();
			$nom_pays_ville = $ligne_select['nom_pays_ville'];
			$nom_pays_ville_url = $ligne_select['nom_pays_ville_url'];

			?>
				<div class="row align-items-center">
					<div class="col-sm-12 text-center">
						<div class="page-title">
							<h1><?php echo "$nom_categorie / $nom_type_de_voyage / $nom_pays / $nom_pays_ville"; ?></h1>
						</div>
					</div>
					<div class="col-12">
						<nav aria-label="breadcrumb d-block text-centerv">
							<ol class="breadcrumb justify-content-center">
								<li class="breadcrumb-item"><a href="<?php echo "/"; ?>"><?= $nom_proprietaire; ?></a></li>
								<li class="breadcrumb-item"><a href="<?php echo "/Plateforme/$nom_categorie_url"; ?>"><?php echo "$nom_categorie"; ?></a></li>
								<li class="breadcrumb-item"><a href="<?php echo "/Plateforme/$nom_categorie_url/$type_de_voyage_url"; ?>"><?php echo "$nom_type_de_voyage"; ?></a></li>
								<li class="breadcrumb-item"><a href="<?php echo "/Plateforme/$nom_categorie_url/$type_de_voyage_url/$nom_pays_url"; ?>"><?php echo "$nom_pays"; ?></a></li>
								<li class="breadcrumb-item active" aria-current="page"><?php echo "$nom_pays_ville"; ?></li>
							</ol>
						</nav>
					</div>
				</div>
			<?php

		} elseif ($_GET['page'] == "Plateforme" && empty($_GET['categorie']) && !empty($_GET['idactionn'])) {

			if ($_GET['idactionn'] == 1) {
				$ancre_type = "$nom_annuaire_1_titre";
				$url = "/Plateforme/$nom_annuaire_1/$nom_annuaire_1_id";
			} elseif ($_GET['idactionn'] == 2) {
				$ancre_type = "$nom_annuaire_2_titre";
				$url = "/Plateforme/$nom_annuaire_2/$nom_annuaire_2_id";
			}

			?>
				<div class="row align-items-center filariane_liste">
					<div class="col-sm-12 text-center">
						<div class="page-title">
							<h1><?php echo "$ancre_type"; ?></h1>
						</div>
					</div>
					<div class="col-12" style="text-align: center;">
						<nav aria-label="breadcrumb d-block text-center" style="margin-bottom: 10px">
							<ol class="breadcrumb justify-content-center">
								<li class="breadcrumb-item"><a href="<?php echo "/"; ?>"><?= $nom_proprietaire; ?></a></li>
								<li class="breadcrumb-item active" aria-current="page"><?php echo "$ancre_type"; ?></li>
							</ol>
						</nav>

						<!-- <form id="formRecherche" method="post" action="/Plateforme/Extras/1" style="max-width: 600px; margin: auto; text-align: center;">
							<div class="input-group" style="max-width: 280px; margin: auto;">

								<select class="selectpicker categorie-accueil" data-live-search="true" title="Choisissez le département" data-original-title="Choisissez le département">

									<?php
									///////////////////////////////SELECT BOUCLE
									$req_boucle = $bdd->prepare("SELECT * FROM pages_categories WHERE id_type=1 AND activer=? ORDER by position ASC");
									$req_boucle->execute(array("oui"));
									while ($ligne_boucle = $req_boucle->fetch()) {
									?>
										<option value="/Plateforme/<?php echo $nom_annuaire_1; ?>/<?php echo $ligne_boucle['nom_categorie_url']; ?>/<?php echo $nom_annuaire_1_id; ?>/<?php echo $ligne_boucle['id']; ?>" data-tokens="/Plateforme/<?php echo $nom_annuaire_1; ?>/<?php echo $ligne_boucle['nom_categorie_url']; ?>/<?php echo $nom_annuaire_1_id; ?>/<?php echo $ligne_boucle['id']; ?>"><?php echo $ligne_boucle['nom_categorie']; ?></option>
									<?php
									}
									$req_boucle->closeCursor();
									?>

								</select>
							</div>
						</form> -->

					</div>

				</div>

			<?php
		} elseif ($_GET['page'] == "Plateforme" && !empty($_GET['categorie'])) {

			///////////////////////////////SELECT CATEGORIE
			$req_select = $bdd->prepare("SELECT * FROM pages_categories WHERE nom_categorie_url=?");
			$req_select->execute(array($_GET['categorie']));
			$ligne_select = $req_select->fetch();
			$req_select->closeCursor();
			$nom_categorie = $ligne_select['nom_categorie'];
			$nom_categorie_url = $ligne_select['nom_categorie_url'];

			if ($_GET['idactionn'] == 1) {
				$ancre_type = "$nom_annuaire_1_titre";
				$url = "/Plateforme/$nom_annuaire_1/$nom_annuaire_1_id";
				$h1_categorie = "$nom_annuaire_1_titre";
				$nom_categorie = str_replace('Extras ', '', $nom_categorie);
			} elseif ($_GET['idactionn'] == 2) {
				$ancre_type = "$nom_annuaire_2_titre";
				$url = "/Plateforme/$nom_annuaire_2/$nom_annuaire_2_id";
				$h1_categorie = "$nom_annuaire_2_titre";
				$nom_categorie = str_replace('Missions ', '', $nom_categorie);
			}

			?>
				<div class="row align-items-center">
					<div class="col-sm-12 text-center">
						<div class="page-title">
							<h1><?php echo "$h1_categorie / $nom_categorie"; ?></h1>
						</div>
					</div>
					<div class="col-12" style="text-align: center;">
						<nav aria-label="breadcrumb d-block text-center" style="margin-bottom: 10px;">
							<ol class="breadcrumb justify-content-center">
								<li class="breadcrumb-item"><a href="<?php echo "/"; ?>"><?= $nom_proprietaire; ?></a></li>
								<li class="breadcrumb-item"><a href="<?php echo $url; ?>"><?php echo $ancre_type; ?></a></li>
								<li class="breadcrumb-item active" aria-current="page"><?php echo "$nom_categorie"; ?></li>
							</ol>
						</nav>

					</div>

				</div>

			<?php
		} elseif ($_GET['page'] == "Plateforme2" && empty($_GET['categorie']) && !empty($_GET['idactionn'])) {
			?>


			<?php
		} elseif ($_GET['page'] == "Plateforme2" && !empty($_GET['categorie'])) {
			?>

			<?php
		} elseif ($_GET['page'] == "Plateforme") {
			?>
				<div class="row align-items-center">
					<div class="col-sm-12 text-center">
						<div class="page-title">
							<h1><?php echo "Annonces"; ?></h1>
						</div>
					</div>
					<div class="col-12" style="text-align: center;">
						<nav aria-label="breadcrumb d-block text-centerv">
							<ol class="breadcrumb justify-content-center">
								<li class="breadcrumb-item"><a href="<?php echo "/"; ?>"><?= $nom_proprietaire; ?></a></li>
								<li class="breadcrumb-item active" aria-current="page"><?php echo "Les services du bien être"; ?></li>
							</ol>
						</nav>

					</div>

				</div>
			</div>

		<?php

		} elseif ($_GET['page'] == "Compte-modifications") {
		?>
			<div class="row align-items-center">
				<div class="col-sm-12 text-center">
					<div class="page-title">
						<h1><?php echo "Profil"; ?></h1>
					</div>
				</div>
				<div class="col-12">
					<nav aria-label="breadcrumb d-block text-centerv">
						<ol class="breadcrumb justify-content-center">
							<li class="breadcrumb-item"><a href="<?php echo "/"; ?>"><?= $nom_proprietaire; ?></a></li>
							<li class="breadcrumb-item active" aria-current="page"><?php echo "Profil"; ?></li>
						</ol>
					</nav>
				</div>
			</div>

		<?php
		} elseif ($_GET['page'] == "faq-extras") {
		?>
			<div class="row align-items-center">
				<div class="col-sm-12 text-center">
					<div class="page-title">
						<h1><?php echo "FAQ Extras"; ?></h1>
					</div>
				</div>
				<div class="col-12">
					<nav aria-label="breadcrumb d-block text-centerv">
						<ol class="breadcrumb justify-content-center">
							<li class="breadcrumb-item"><a href="<?php echo "/"; ?>"><?= $nom_proprietaire; ?></a></li>
							<?php if ($statut_compte_oo != 1 && $statut_compte_oo != 6) { ?>
								<li class="breadcrumb-item"><a href="<?php echo "/Guide"; ?>">Guide</a></li>
							<?php } ?>
							<li class="breadcrumb-item active" aria-current="page"><?php echo "FAQ Extras"; ?></li>
						</ol>
					</nav>
				</div>
			</div>
		<?php

		} elseif ($_GET['page'] == "Dashboard-etablissement") {
		?>
			<div class="row align-items-center">
				<div class="col-sm-12 text-center">
					<div class="page-title">
						<h1><?php echo "Dashboard Etablissements"; ?></h1>
					</div>
				</div>
				<div class="col-12">
					<nav aria-label="breadcrumb d-block text-centerv">
						<ol class="breadcrumb justify-content-center">
							<li class="breadcrumb-item"><a href="<?php echo "/"; ?>"><?= $nom_proprietaire; ?></a></li>
							<li class="breadcrumb-item active" aria-current="page"><?php echo "Dashboard Etablissements"; ?></li>
						</ol>
					</nav>
				</div>
			</div>
		<?php

		} elseif ($_GET['page'] == "faq-etablissements") {
		?>
			<div class="row align-items-center">
				<div class="col-sm-12 text-center">
					<div class="page-title">
						<h1><?php echo "FAQ Etablissements"; ?></h1>
					</div>
				</div>
				<div class="col-12">
					<nav aria-label="breadcrumb d-block text-centerv">
						<ol class="breadcrumb justify-content-center">
							<li class="breadcrumb-item"><a href="<?php echo "/"; ?>"><?= $nom_proprietaire; ?></a></li>
							<li class="breadcrumb-item active" aria-current="page"><?php echo "FAQ Etablissements"; ?></li>
						</ol>
					</nav>
				</div>
			</div>
		<?php

		} elseif ($_GET['page'] == "Traitements-informations") {
		?>
			<div class="row align-items-center">
				<div class="col-sm-12 text-center">
					<div class="page-title">
						<h1><?php echo "Paiement validé"; ?></h1>
					</div>
				</div>
				<div class="col-12">
					<nav aria-label="breadcrumb d-block text-centerv">
						<ol class="breadcrumb justify-content-center">
							<li class="breadcrumb-item"><a href="<?php echo "/"; ?>"><?= $nom_proprietaire; ?></a></li>
							<li class="breadcrumb-item active" aria-current="page"><?php echo "Paiement validé"; ?></li>
						</ol>
					</nav>
				</div>
			</div>
		<?php

			////////////////////////////////////////////////////////////////////////////////////////////FICHE BLOG
		} elseif ($_GET['page'] == "Blog" && empty($_GET['name']) && !empty($_GET['idaction'])) {

			///////////////////////////////SELECT
			$req_select = $bdd->prepare("SELECT * FROM codi_one_blog WHERE id=?");
			$req_select->execute(array($_GET['idaction']));
			$ligne_select = $req_select->fetch();
			$req_select->closeCursor();

			///////////////////////////////SELECT CATEGORIE
			$req_select = $bdd->prepare("SELECT * FROM codi_one_blog_categories WHERE id=?");
			$req_select->execute(array($ligne_select['id_categorie']));
			$ligne_select = $req_select->fetch();
			$req_select->closeCursor();

		?>
			<div class="row align-items-center">
				<div class="col-sm-12 text-center">
					<div class="page-title">
						<h1><?php echo "$titre_blog_1_artciles_blog"; ?></h1>
					</div>
					<ol class="breadcrumb justify-content-center">
						<ol class="breadcrumb justify-content-sm-end">
							<li class="breadcrumb-item"><a href="<?php echo "/"; ?>"><?= $nom_proprietaire; ?></a></li>
							<li class="breadcrumb-item"><a href="<?php echo "/Blog"; ?>">Blog</a></li>
							<li class="breadcrumb-item"><a href="/<?php echo $ligne_select['nom_url_categorie']; ?>"><?php echo $ligne_select['nom_categorie']; ?></a></li>
							<li class="breadcrumb-item active"><?php echo "$titre_blog_1_artciles_blog"; ?></li>
						</ol>
						</nav>
				</div>
			</div>

		<?php
			////////////////////////////////////////////////////////////////////////////////////////////FICHE BLOG

		} elseif ($_GET['page'] == "page-introuvable-404") {
		?>
			<div class="row align-items-center">
				<div class="col-sm-12 text-center">
					<div class="page-title">
						<h1><?php echo "Page introuvable"; ?></h1>
					</div>
				</div>
				<div class="col-12">
					<nav aria-label="breadcrumb d-block text-centerv" class="container2">
						<ol class="breadcrumb justify-content-center">
							<li class="breadcrumb-item"><a href="<?php echo "/"; ?>"><?= $nom_proprietaire; ?></a></li>
							<li class="breadcrumb-item active" aria-current="page"><?php echo "Page introuvable"; ?></li>
						</ol>
					</nav>
				</div>
			</div>
		<?php

		} elseif ($_GET['page'] == "Mes-missions") {
		?>
			<div class="row align-items-center">
				<div class="col-sm-12 text-center">
					<div class="page-title">
						<h1><?php echo "Mes missions"; ?></h1>
					</div>
				</div>
				<div class="col-12">
					<nav aria-label="breadcrumb d-block text-centerv" class="container2">
						<ol class="breadcrumb justify-content-center">
							<li class="breadcrumb-item"><a href="<?php echo "/"; ?>"><?= $nom_proprietaire; ?></a></li>
							<?php if (($statut_compte_oo != 1 && $statut_compte_oo != 6) && empty($_GET['token'])) { ?>
								<li class="breadcrumb-item"><a href="<?php echo "/Guide"; ?>">Guide</a></li>
							<?php } ?>
							<li class="breadcrumb-item active" aria-current="page"><?php echo "Mes missions"; ?></li>
						</ol>
					</nav>
				</div>
			</div>
		<?php

		} elseif ($_GET['page'] == "Avatar") {
		?>
			<div class="row align-items-center">
				<div class="col-sm-12 text-center">
					<div class="page-title">

						<h1><?php echo "Avatar"; ?></h1>

					</div>
				</div>
				<div class="col-12">
					<nav aria-label="breadcrumb d-block text-centerv" class="container2">
						<ol class="breadcrumb justify-content-center">
							<li class="breadcrumb-item"><a href="<?php echo "/"; ?>"><?= $nom_proprietaire; ?></a></li>
							<li class="breadcrumb-item"><a href="<?php echo "/Guide"; ?>">Guide</a></li>
							<li class="breadcrumb-item active" aria-current="page"><?php echo "Avatar"; ?></li>
						</ol>
					</nav>
				</div>
			</div>

		<?php

		} elseif ($_GET['page'] == "Guide") {
		?>
			<div class="row align-items-center">
				<div class="col-sm-12 text-center">
					<div class="page-title">
						<h1><?php echo "Guide"; ?></h1>
					</div>
				</div>
				<div class="col-12">
					<nav aria-label="breadcrumb d-block text-centerv" class="container2">
						<ol class="breadcrumb justify-content-center">
							<li class="breadcrumb-item"><a href="<?php echo "/"; ?>"><?= $nom_proprietaire; ?></a></li>
							<li class="breadcrumb-item active" aria-current="page"><?php echo "Guide"; ?></li>
						</ol>
					</nav>
				</div>
			</div>

		<?php
			////////////////////////////////////////////////////////////////////////////////////////////BLOG
		} elseif ($_GET['page'] == "Blog" && !empty($_GET['name'])) {

			///////////////////////////////SELECT CATEGORIE
			$req_select = $bdd->prepare("SELECT * FROM codi_one_blog_categories WHERE id=?");
			$req_select->execute(array($_GET['idaction']));
			$ligne_select = $req_select->fetch();
			$req_select->closeCursor();

		?>
			<div class="row align-items-center">
				<div class="col-sm-12 text-center">
					<div class="page-title">
						<h1><?php echo $ligne_select['nom_categorie']; ?> <?php echo $title_page_numeroth1; ?></h1>
					</div>
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb justify-content-center">
							<li class="breadcrumb-item"><a href="<?php echo "/"; ?>"><?= $nom_proprietaire; ?></a></li>
							<li class="breadcrumb-item"><a href="<?php echo "/Blog"; ?>">Blog</a></li>
							<li class="breadcrumb-item active"><?php echo $ligne_select['nom_categorie']; ?> <?php echo $title_page_numeroth1; ?> </li>
						</ol>
					</nav>
				</div>
			</div>

		<?php
			////////////////////////////////////////////////////////////////////////////////////////////BLOG

			////////////////////////////////////////////////////////////////////////////////////////////BLOG
		} elseif ($_GET['page'] == "Blog" && empty($_GET['name'])) {

		?>
			<div class="row align-items-center">
				<div class="col-sm-12 text-center">
					<div class="page-title">
						<h1><?php echo "$Titre_h1_page"; ?> <?php echo $title_page_numeroth1; ?></h1>
					</div>
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb justify-content-center">
							<li class="breadcrumb-item"><a href="<?php echo "/"; ?>"><?= $nom_proprietaire; ?></a></li>
							<li class="breadcrumb-item active"><?php echo "$Ancre_fil_ariane_page"; ?> <?php echo $title_page_numeroth1; ?></li>
						</ol>
					</nav>
				</div>
			</div>

		<?php
			////////////////////////////////////////////////////////////////////////////////////////////BLOG

		} elseif ($_GET['page'] == "modifier-profil-photo") {
		?>
			<div class="row align-items-center">
				<div class="col-sm-12 text-center">
					<div class="page-title">
							<h1><?php echo "Avatar"; ?></h1>
					</div>
				</div>
				<div class="col-12">
					<nav aria-label="breadcrumb d-block text-centerv" class="container2">
						<ol class="breadcrumb justify-content-center">
							<li class="breadcrumb-item"><a href="<?php echo "/"; ?>"><?= $nom_proprietaire; ?></a></li>
								<li class="breadcrumb-item"><a href="<?php echo "/Guide"; ?>">Guide</a></li>
								<li class="breadcrumb-item active" aria-current="page"><?php echo "Avatar"; ?></li>
						</ol>
					</nav>
				</div>
			</div>

		<?php
			////////////////////////////////////////////////////////////////////////////////////////////BLOG

			////////////////////////////////////////////////////////////////////////////////////////////Disponibilites
		} elseif ($_GET['page'] == "Disponibilites") {
		?>
			<div class="row align-items-center">
				<div class="col-sm-12 text-center">
					<div class="page-title">
						<h1><?php echo "Disponibilités"; ?></h1>
					</div>
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb justify-content-center">
							<li class="breadcrumb-item"><a href="<?php echo "/"; ?>"><?= $nom_proprietaire; ?></a></li>
							<?php if ($statut_compte_oo != 1 && $statut_compte_oo != 6) { ?>
								<li class="breadcrumb-item"><a href="<?php echo "/Guide"; ?>">Guide</a></li>
							<?php } ?>
							<li class="breadcrumb-item active"><?php echo "Disponibilités"; ?></li>
						</ol>
					</nav>
				</div>
			</div>

		<?php
			////////////////////////////////////////////////////////////////////////////////////////////Disponibilites

			////////////////////////////////////////////////////////////////////////////////////////////Agenda
		} elseif ($_GET['page'] == "Agenda") {
		?>
			<div class="row align-items-center">
				<div class="col-sm-12 text-center">
					<div class="page-title">
						<h1><?php echo "Agenda"; ?></h1>
					</div>
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb justify-content-center">
							<li class="breadcrumb-item"><a href="<?php echo "/"; ?>"><?= $nom_proprietaire; ?></a></li>
							<?php if ($statut_compte_oo != 1 && $statut_compte_oo != 6) { ?>
								<li class="breadcrumb-item"><a href="<?php echo "/Guide"; ?>">Guide</a></li>
							<?php } ?>
							<li class="breadcrumb-item active"><?php echo "Agenda"; ?></li>
						</ol>
					</nav>
				</div>
			</div>

		<?php
			////////////////////////////////////////////////////////////////////////////////////////////Agenda

			////////////////////////////////////////////////////////////////////////////////////////////Agenda pro
		} elseif ($_GET['page'] == "Agendapro") {
		?>
			<div class="row align-items-center">
				<div class="col-sm-12 text-center">
					<div class="page-title">
						<h1><?php echo "Agenda des missions"; ?></h1>
					</div>
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb justify-content-center">
							<li class="breadcrumb-item"><a href="<?php echo "/"; ?>"><?= $nom_proprietaire; ?></a></li>
							<li class="breadcrumb-item active"><?php echo "Agenda des missions"; ?></li>
						</ol>
					</nav>
				</div>
			</div>

		<?php
			////////////////////////////////////////////////////////////////////////////////////////////Agenda pro

			////////////////////////////////////////////////////////////////////////////////////////////Demandes-de-mission
		} elseif ($_GET['page'] == "Demandes-de-mission") {
		?>
			<div class="row align-items-center">
				<div class="col-sm-12 text-center">
					<div class="page-title">
						<h1><?php echo "Demandes de mission"; ?></h1>
					</div>
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb justify-content-center">
							<li class="breadcrumb-item"><a href="<?php echo "/"; ?>"><?= $nom_proprietaire; ?></a></li>
							<?php if ($statut_compte_oo != 1 && $statut_compte_oo != 6) { ?>
								<li class="breadcrumb-item"><a href="<?php echo "/Guide"; ?>">Guide</a></li>
							<?php } ?>
							<li class="breadcrumb-item active"><?php echo "Demandes de mission"; ?></li>
						</ol>
					</nav>
				</div>
			</div>
		<?php

			////////////////////////////////////////////////////////////////////////////////////////////Mes-documents
		} elseif ($_GET['page'] == "Mes-documents") {
		?>
			<div class="row align-items-center">
				<div class="col-sm-12 text-center">
					<div class="page-title">
						<h1><?php echo "Mes documents"; ?></h1>
					</div>
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb justify-content-center">
							<li class="breadcrumb-item"><a href="<?php echo "/"; ?>"><?= $nom_proprietaire; ?></a></li>
							<?php if ($statut_compte_oo != 1 && $statut_compte_oo != 6) { ?>
								<li class="breadcrumb-item"><a href="<?php echo "/Guide"; ?>">Guide</a></li>
							<?php } ?>
							<li class="breadcrumb-item active"><?php echo "Mes documents"; ?></li>
						</ol>
					</nav>
				</div>
			</div>
		<?php

			////////////////////////////////////////////////////////////////////////////////////////////Commandes-de-DUE
		} elseif ($_GET['page'] == "Commandes-de-DUE") {
		?>
			<div class="row align-items-center">
				<div class="col-sm-12 text-center">
					<div class="page-title">
						<h1><?php echo "DPAE contrat"; ?></h1>
					</div>
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb justify-content-center">
							<li class="breadcrumb-item"><a href="<?php echo "/"; ?>"><?= $nom_proprietaire; ?></a></li>
							<li class="breadcrumb-item active"><?php echo "DPAE contrat"; ?></li>
						</ol>
					</nav>
				</div>
			</div>

		<?php
			////////////////////////////////////////////////////////////////////////////////////////////Commandes-de-DUE

			////////////////////////////////////////////////////////////////////////////////////////////Commandes-cdd-cdi
		} elseif ($_GET['page'] == "Commandes-cdd-cdi") {
		?>
			<div class="row align-items-center">
				<div class="col-sm-12 text-center">
					<div class="page-title">
						<h1><?php echo "Demandes CDD/CDI"; ?></h1>
					</div>
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb justify-content-center">
							<li class="breadcrumb-item"><a href="<?php echo "/"; ?>"><?= $nom_proprietaire; ?></a></li>
							<li class="breadcrumb-item active"><?php echo "Demandes CDD/CDI"; ?></li>
						</ol>
					</nav>
				</div>
			</div>

		<?php
			////////////////////////////////////////////////////////////////////////////////////////////Commandes-cdd-cdi

			////////////////////////////////////////////////////////////////////////////////////////////Commandes-de-formation
		} elseif ($_GET['page'] == "Commandes-de-formation") {
		?>
			<div class="row align-items-center">
				<div class="col-sm-12 text-center">
					<div class="page-title">
						<h1><?php echo "Commandes de formation"; ?></h1>
					</div>
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb justify-content-center">
							<li class="breadcrumb-item"><a href="<?php echo "/"; ?>"><?= $nom_proprietaire; ?></a></li>
							<li class="breadcrumb-item active"><?php echo "Commandes de formation"; ?></li>
						</ol>
					</nav>
				</div>
			</div>

		<?php
			////////////////////////////////////////////////////////////////////////////////////////////Commandes-de-formation

			////////////////////////////////////////////////////////////////////////////////////////////Demande-de-devis
		} elseif ($_GET['page'] == "Demande-de-devis") {
		?>
			<div class="row align-items-center">
				<div class="col-sm-12 text-center">
					<div class="page-title">
						<h1><?php echo "Demande de devis"; ?></h1>
					</div>
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb justify-content-center">
							<li class="breadcrumb-item"><a href="<?php echo "/"; ?>"><?= $nom_proprietaire; ?></a></li>
							<li class="breadcrumb-item active"><?php echo "Demande de devis"; ?></li>
						</ol>
					</nav>
				</div>
			</div>

		<?php
			////////////////////////////////////////////////////////////////////////////////////////////Demande-de-devis

			////////////////////////////////////////////////////////////////////////////////////////////Demandes-mise-en-relation
		} elseif ($_GET['page'] == "Demandes-mise-en-relation") {
		?>
			<div class="row align-items-center">
				<div class="col-sm-12 text-center">
					<div class="page-title">
						<h1><?php echo "Demandes mise en relation"; ?></h1>
					</div>
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb justify-content-center">
							<li class="breadcrumb-item"><a href="<?php echo "/"; ?>"><?= $nom_proprietaire; ?></a></li>
							<li class="breadcrumb-item active"><?php echo "Demandes mise en relation"; ?></li>
						</ol>
					</nav>
				</div>
			</div>

		<?php
			////////////////////////////////////////////////////////////////////////////////////////////Demandes-mise-en-relation

			////////////////////////////////////////////////////////////////////////////////////////////Images
		} elseif ($_GET['page'] == "Blocs-publicites-images") {
		?>
			<div class="row align-items-center">
				<div class="col-sm-12 text-center">
					<div class="page-title">
						<h1><?php echo "Images"; ?></h1>
					</div>
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb justify-content-center">
							<li class="breadcrumb-item"><a href="<?php echo "/"; ?>"><?= $nom_proprietaire; ?></a></li>
							<li class="breadcrumb-item active"><?php echo "Images"; ?></li>
						</ol>
					</nav>
				</div>
			</div>
		<?php

			////////////////////////////////////////////////////////////////////////////////////////////Blocs-publicites-recadrage-images
		} elseif ($_GET['page'] == "Blocs-publicites-recadrage-images") {
		?>
			<div class="row align-items-center">
				<div class="col-sm-12 text-center">
					<div class="page-title">
						<h1><?php echo "Image recadrage"; ?></h1>
					</div>
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb justify-content-center">
							<li class="breadcrumb-item"><a href="<?php echo "/"; ?>"><?= $nom_proprietaire; ?></a></li>
							<li class="breadcrumb-item active"><?php echo "Image recadrage"; ?></li>
						</ol>
					</nav>
				</div>
			</div>
		<?php

			////////////////////////////////////////////////////////////////////////////////////////////Favoris
		} elseif ($_GET['page'] == "Favoris") {
		?>
			<div class="row align-items-center">
				<div class="col-sm-12 text-center">
					<div class="page-title">
						<h1><?php echo "Favoris"; ?></h1>
					</div>
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb justify-content-center">
							<li class="breadcrumb-item"><a href="<?php echo "/"; ?>"><?= $nom_proprietaire; ?></a></li>
							<li class="breadcrumb-item active"><?php echo "Favoris"; ?></li>
						</ol>
					</nav>
				</div>
			</div>
		<?php

			////////////////////////////////////////////////////////////////////////////////////////////Mon-profil
		} elseif ($_GET['page'] == "Mon-profil") {
		?>
			<div class="row align-items-center">
				<div class="col-sm-12 text-center">
					<div class="page-title">
						<h1><?php echo "Mon profil"; ?></h1>
					</div>
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb justify-content-center">
							<li class="breadcrumb-item"><a href="<?php echo "/"; ?>"><?= $nom_proprietaire; ?></a></li>
							<?php if ($statut_compte_oo != 1 && $statut_compte_oo != 6) { ?>
								<li class="breadcrumb-item"><a href="<?php echo "/Guide"; ?>">Guide</a></li>
							<?php } ?>
							<li class="breadcrumb-item active"><?php echo "Mon profil"; ?></li>
						</ol>
					</nav>
				</div>
			</div>
		<?php

			////////////////////////////////////////////////////////////////////////////////////////////Missions
		} elseif ($_GET['page'] == "Missions") {
		?>
			<div class="row align-items-center">
				<div class="col-sm-12 text-center">
					<div class="page-title">
						<h1><?php echo "Missions"; ?></h1>
					</div>
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb justify-content-center">
							<li class="breadcrumb-item"><a href="<?php echo "/"; ?>"><?= $nom_proprietaire; ?></a></li>
							<li class="breadcrumb-item active"><?php echo "Missions"; ?></li>
						</ol>
					</nav>
				</div>
			</div>
		<?php

			////////////////////////////////////////////////////////////////////////////////////////////Réservations
		} elseif ($_GET['page'] == "Reservations") {
		?>
			<div class="row align-items-center">
				<div class="col-sm-12 text-center">
					<div class="page-title">
						<h1><?php echo "Réservations"; ?></h1>
					</div>
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb justify-content-center">
							<li class="breadcrumb-item"><a href="<?php echo "/"; ?>"><?= $nom_proprietaire; ?></a></li>
							<li class="breadcrumb-item active"><?php echo "Réservations"; ?></li>
						</ol>
					</nav>
				</div>
			</div>
		<?php

			////////////////////////////////////////////////////////////////////////////////////////////Formules
		} elseif ($_GET['page'] == "Formules") {
		?>
			<div class="row align-items-center">
				<div class="col-sm-12 text-center">
					<div class="page-title">
						<h1><?php echo "CHOISISSEZ VOTRE FORMULE"; ?></h1>
					</div>
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb justify-content-center">
							<li class="breadcrumb-item"><a href="<?php echo "/"; ?>"><?= $nom_proprietaire; ?></a></li>
							<li class="breadcrumb-item active"><?php echo "Choisissez votre formule"; ?></li>
						</ol>
					</nav>
				</div>
			</div>
		<?php

			////////////////////////////////////////////////////////////////////////////////////////////Formules-credits
		} elseif ($_GET['page'] == "Formules-credits") {
		?>
			<div class="row align-items-center">
				<div class="col-sm-12 text-center">
					<div class="page-title">
						<h1><?php echo "Acheter des crédits"; ?></h1>
					</div>
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb justify-content-center">
							<li class="breadcrumb-item"><a href="<?php echo "/"; ?>"><?= $nom_proprietaire; ?></a></li>
							<li class="breadcrumb-item active"><?php echo "Acheter des crédits"; ?></li>
						</ol>
					</nav>
				</div>
			</div>
		<?php

			////////////////////////////////////////////////////////////////////////////////////////////Blocs-publicites
		} elseif ($_GET['page'] == "Blocs-publicites") {
		?>
			<div class="row align-items-center">
				<div class="col-sm-12 text-center">
					<div class="page-title">
						<h1><?php echo "Publicités"; ?></h1>
					</div>
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb justify-content-center">
							<li class="breadcrumb-item"><a href="<?php echo "/"; ?>"><?= $nom_proprietaire; ?></a></li>
							<li class="breadcrumb-item active"><?php echo "Publicités"; ?></li>
						</ol>
					</nav>
				</div>
			</div>
		<?php

			////////////////////////////////////////////////////////////////////////////////////////////Paiement
		} elseif ($_GET['page'] == "Panier") {
		?>
			<div class="row align-items-center">
				<div class="col-sm-12 text-center">
					<div class="page-title">
						<h1><?php echo "Paiement"; ?></h1>
					</div>
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb justify-content-center">
							<li class="breadcrumb-item"><a href="<?php echo "/"; ?>"><?= $nom_proprietaire; ?></a></li>
							<li class="breadcrumb-item active"><?php echo "Paiement"; ?></li>
						</ol>
					</nav>
				</div>
			</div>
		<?php

			////////////////////////////////////////////////////////////////////////////////////////////Abonnements-annuaires
		} elseif ($_GET['page'] == "Abonnements-annuaires") {
		?>
			<div class="row align-items-center">
				<div class="col-sm-12 text-center">
					<div class="page-title">
						<h1><?php echo "Abonnements annuaires"; ?></h1>
					</div>
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb justify-content-center">
							<li class="breadcrumb-item"><a href="<?php echo "/"; ?>"><?= $nom_proprietaire; ?></a></li>
							<li class="breadcrumb-item active"><?php echo "Abonnements annuaires"; ?></li>
						</ol>
					</nav>
				</div>
			</div>
		<?php

			////////////////////////////////////////////////////////////////////////////////////////////Messagerie
		} elseif ($_GET['page'] == "Messagerie") {
		?>
			<div class="row align-items-center">
				<div class="col-sm-12 text-center">
					<div class="page-title">
						<h1><?php echo "Messagerie"; ?></h1>
					</div>
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb justify-content-center">
							<li class="breadcrumb-item"><a href="<?php echo "/"; ?>"><?= $nom_proprietaire; ?></a></li>
							<?php if ($statut_compte_oo != 1 && $statut_compte_oo != 6) { ?>
								<li class="breadcrumb-item"><a href="<?php echo "/Guide"; ?>">Guide</a></li>
							<?php } ?>
							<li class="breadcrumb-item active"><?php echo "Messagerie"; ?></li>
						</ol>
					</nav>
				</div>
			</div>
		<?php

			////////////////////////////////////////////////////////////////////////////////////////////Messagerie
		} elseif ($_GET['page'] == "Message") {
		?>
			<div class="row align-items-center">
				<div class="col-sm-12 text-center">
					<div class="page-title">
						<h1><?php echo "Messagerie"; ?></h1>
					</div>
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb justify-content-center">
							<li class="breadcrumb-item"><a href="<?php echo "/"; ?>"><?= $nom_proprietaire; ?></a></li>
							<?php if ($statut_compte_oo != 1 && $statut_compte_oo != 6) { ?>
								<li class="breadcrumb-item"><a href="<?php echo "/Guide"; ?>">Guide</a></li>
							<?php } ?>
							<li class="breadcrumb-item active"><?php echo "Message"; ?></li>
						</ol>
					</nav>
				</div>
			</div>
		<?php

			////////////////////////////////////////////////////////////////////////////////////////////Messagerie
		} elseif ($_GET['page'] == "factures") {
		?>
			<div class="row align-items-center">
				<div class="col-sm-12 text-center">
					<div class="page-title">
						<h1><?php echo "Factures"; ?></h1>
					</div>
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb justify-content-center">
							<li class="breadcrumb-item"><a href="<?php echo "/"; ?>"><?= $nom_proprietaire; ?></a></li>
							<li class="breadcrumb-item active"><?php echo "Factures"; ?></li>
						</ol>
					</nav>
				</div>
			</div>
		<?php

			////////////////////////////////////////////////////////////////////////////////////////////NOTIFICATIONS
		} elseif ($_GET['page'] == "Notifications") {
		?>
			<div class="row align-items-center">
				<div class="col-sm-12 text-center">
					<div class="page-title">
						<h1><?php echo "Notifications"; ?></h1>
					</div>
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb justify-content-center">
							<li class="breadcrumb-item"><a href="<?php echo "/"; ?>"><?= $nom_proprietaire; ?></a></li>
							<li class="breadcrumb-item active"><?php echo "Notifications"; ?></li>
						</ol>
					</nav>
				</div>
			</div>

		<?php

			////////////////////////////////////////////////////////////////////////////////////////////MOT DE PASSE PERDU
		} elseif ($_GET['page'] == "mot-de-passe-oublie") {
		?>
			<div class="row align-items-center">
				<div class="col-sm-12 text-center">
					<div class="page-title">
						<h1><?php echo "Mot de passe perdu"; ?></h1>
					</div>
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb justify-content-center">
							<li class="breadcrumb-item"><a href="<?php echo "/"; ?>"><?= $nom_proprietaire; ?></a></li>
							<li class="breadcrumb-item active"><?php echo "Mot de passe perdu"; ?></li>
						</ol>
					</nav>
				</div>
			</div>

		<?php
			////////////////////////////////////////////////////////////////////////////////////////////MOT DE PASSE PERDU

			////////////////////SI CONNECTE 
		} elseif (!empty($user) && $id_oo == 8 && empty($_GET['page'])) {

		?>

			<div class="row align-items-center">
				<div class="col-sm-12 text-center">
					<div class="page-title">
						<h1><?php echo "Dashboard"; ?></h1>
					</div>
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb justify-content-center">
							<li class="breadcrumb-item"><a href="<?php echo "/"; ?>"><?= $nom_proprietaire; ?></a></li>
							<li class="breadcrumb-item active" aria-current="page"><?php echo "Dashboard"; ?></li>
						</ol>
					</nav>
				</div>
			</div>

		<?php

			////////////////////////////////////////////////////////////////////////////////////////////PAGE FRONT
		} elseif (!empty($user)) {
		?>

			<div class="row align-items-center">
				<div class="col-sm-12 text-center">
					<div class="page-title">
						<h1><?php echo "$Titre_h1_page"; ?></h1>
					</div>
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb justify-content-center">
							<li class="breadcrumb-item"><a href="<?php echo "/"; ?>"><?= $nom_proprietaire; ?></a></li>
							<li class="breadcrumb-item active" aria-current="page"><?php echo "Dashboard"; ?></li>
						</ol>
					</nav>
				</div>
			</div>

		<?php

			////////////////////////////////////////////////////////////////////////////////////////////PAGE FRONT
		} else {
		?>

			<div class="row align-items-center">
				<div class="col-sm-12 text-center">
					<div class="page-title">
						<h1><?php echo "$Titre_h1_page"; ?></h1>
					</div>
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb justify-content-center">
							<li class="breadcrumb-item"><a href="<?php echo "/"; ?>"><?= $nom_proprietaire; ?></a></li>
							<li class="breadcrumb-item active" aria-current="page"><?php echo "$Ancre_fil_ariane_page"; ?></li>
						</ol>
					</nav>
				</div>
			</div>

		<?php
		}
		////////////////////////////////////////////////////////////////////////////////////////////PAGE FRONT

		////////////////////SI CONNECTE 
		if (!empty($user) && $id_oo == 8) {
		?>

		</div>
	</div>
	</div>

<?php
		}

?>