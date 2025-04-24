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

?>

<!--<script src="/js/dist/clipboard.min.js"></script>-->

<script>
	$(document).ready(function() {

		//AJAX
		$(document).on("change", "#remember-me", function() {
			facturation_autre();
		});

		function facturation_autre() {
			if ($('#remember-me').is(':checked')) {
				$("#autre_adresse_facturation").css("display", "");
			} else {
				$("#autre_adresse_facturation").css("display", "none");
			}
		}
		facturation_autre();
	});

	//new ClipboardJS('.btn');
</script>

<div id='is' class="contact-form-wrapper background-white p30" style='text-align: left;'>

	<?php

	/////////Variable * => ok si inscription
	if ($modif != "oui") {
		$inscription_ok = "*";
	} else {
		$inscription_ok = "";
	}
	/////////Variable * => ok si inscription

	?>

	<div style='clear: both; margin-bottom: 15px;'></div>

	<div style='clear: both;'></div>

	<div class="alert alert-success background_color" role="alert">
		<p style="margin-bottom: 0px;"><span class="uk-icon-user"></span> <b>Dernière connexion</b> le,
			<?php
			if (!empty($last_login)) {
				echo date('d-m-Y à H:i', $last_login);
			} else {
				echo date('d-m-Y à H:i', time());

				///////////////////////////////UPDATE
				$sql_update = $bdd->prepare("UPDATE membres SET 
			last_ip=?,
			last_login=? 
			WHERE pseudo=?");
				$sql_update->execute(array(
					$_SERVER['REMOTE_ADDR'],
					time(),
					htmlspecialchars($user)
				));
				$sql_update->closeCursor();
			}
			?>
		</p>
	</div>

	<hr />
	<h2 class="style_color">Mon compte</h2>
	<hr />

	<div style='clear: both; margin-bottom: 15px;'></div>

	<div class="row style_color">
		<label class="control-label col-sm-2"><?php echo "Type de compte $inscription_ok"; ?></label>
		<div class="col-sm-5">

			<?php

			$req_bouclem = $bdd->prepare("SELECT * FROM membres WHERE id=?");
			$req_bouclem->execute(array($id_oo));
			$ligne_bouclem = $req_bouclem->fetch();
			$statut_compte = $ligne_bouclem['statut_compte'];

			$req_bouclem2 = $bdd->prepare("SELECT * FROM membres_type_de_compte WHERE id=?");
			$req_bouclem2->execute(array($statut_compte));
			$ligne_bouclem2 = $req_bouclem2->fetch();
			$statut_compte2 = $ligne_bouclem2['Nom_type'];

			echo $statut_compte2;

			?>

		</div>
	</div>

	<?php if ($statut_compte == 1) { ?>
		<div style='clear: both; margin-bottom: 15px;'></div>
		<div class="row style_color">
			<label class="control-label col-sm-2"><?php echo "Catégorie Socio-Professionnelle"; ?> *</label>
			<div class="col-sm-2">
				<select id="cat_socio" name="cat_socio" class="form-control" style='margin-bottom: 15px; <?php echo "$coloorpr"; ?>'>
					<option value="">Sélection</option>
					<option <?php if ($cat_socio == "Agriculteur exploitant") {
								echo "selected";
							} ?> value="Agriculteur exploitant">Agriculteur exploitant</option>
					<option <?php if ($cat_socio == "Artisan commerçant et chef d’entreprise") {
								echo "selected";
							} ?> value="Artisan commerçant et chef d’entreprise">Artisan commerçant et chef d’entreprise</option>
					<option <?php if ($cat_socio == "Cadre et profession intellectuelle supérieure") {
								echo "selected";
							} ?> value="Cadre et profession intellectuelle supérieure">Cadre et profession intellectuelle supérieure</option>
					<option <?php if ($cat_socio == "Profession intermédiaire") {
								echo "selected";
							} ?> value="Profession intermédiaire">Profession intermédiaire</option>
					<option <?php if ($cat_socio == "Employé") {
								echo "selected";
							} ?> value="Employé">Employé</option>
					<option <?php if ($cat_socio == "Ouvrier") {
								echo "selected";
							} ?> value="Ouvrier">Ouvrier</option>
					<option <?php if ($cat_socio == "Retraité") {
								echo "selected";
							} ?> value="Retraité">Retraité</option>
					<option <?php if ($cat_socio == "Autre personne, sans activité professionnelle") {
								echo "selected";
							} ?> value="Autre personne, sans activité professionnelle">Autre personne, sans activité professionnelle</option>
				</select>
			</div>
		</div>
	<?php } elseif ($statut_compte == 2) {
	?>
		<div style='clear: both; margin-bottom: 15px;'></div>
		<div class="row style_color">
			<label class="control-label col-sm-2">Vous êtes : *</label>
			<div class="col-sm-2">
				<select id="categorie_annonceur" name="categorie_annonceur" class="form-control" style='margin-bottom: 15px; <?php echo "$coloorpr"; ?>'>
					<option value="">Sélection</option>
					<option <?php if ($categorie_annonceur == "1") {
								echo "selected";
							} ?> value="1">Agriculteur/Maraicher/Eleveur</option>
					<option <?php if ($categorie_annonceur == "2") {
								echo "selected";
							} ?> value="2">Artisan</option>
					<option <?php if ($categorie_annonceur == "3") {
								echo "selected";
							} ?> value="3">Association</option>
					<option <?php if ($categorie_annonceur == "4") {
								echo "selected";
							} ?> value="4">Auteur d'un projet</option>
					<option <?php if ($categorie_annonceur == "5") {
								echo "selected";
							} ?> value="5">Un bénévole</option>
					<option <?php if ($categorie_annonceur == "6") {
								echo "selected";
							} ?> value="6">Commerce</option>
					<option <?php if ($categorie_annonceur == "7") {
								echo "selected";
							} ?> value="7">Commune, Communauté de communes</option>
					<option <?php if ($categorie_annonceur == "8") {
								echo "selected";
							} ?> value="8">Communauté intentionnelle ou autres</option>
					<option <?php if ($categorie_annonceur == "9") {
								echo "selected";
							} ?> value="9">Cooperative</option>
					<option <?php if ($categorie_annonceur == "10") {
								echo "selected";
							} ?> value="10">Entreprise</option>
					<option <?php if ($categorie_annonceur == "11") {
								echo "selected";
							} ?> value="11">Un financeur</option>
					<option <?php if ($categorie_annonceur == "12") {
								echo "selected";
							} ?> value="12">Proprietaire Bailleur</option>
					<option <?php if ($categorie_annonceur == "13") {
								echo "selected";
							} ?> value="13">Professionnel de santé</option>
					<option <?php if ($categorie_annonceur == "14") {
								echo "selected";
							} ?> value="14">Autres</option>
				</select>
			</div>
		</div>
	<?php
	} ?>

	<div style='clear: both; margin-bottom: 15px;'></div>

	<div class="row style_color">
		<label class="control-label col-sm-2">Abonné à la newsletter</label>
		<div class="col-sm-5">
			<input type="checkbox" id='newslettre' name='newslettre' <?php if ($newslettre_oo == "1") {
																			echo "checked";
																		} ?> />
		</div>
	</div>

	<!-- <?php
			if ($ligne_bouclem['statut_compte'] == 1) {
			?>

<div class="row style_color">
<label class="control-label col-sm-2"><?php echo "Nom du commercial"; ?> </label>
<div class="col-sm-5">
<input type="text" id='nom_commercial' name='nom_commercial' class="form-control" placeholder="" autocomplete="off" value="<?php echo "$nom_commercial"; ?>" style='<?php echo "$coloorm"; ?> margin-bottom: 0px;' />
</div>
</div>


<div style='clear: both; margin-bottom: 15px;'></div>

<?php
			}
?> -->

	<div class="row style_color">
		<label class="control-label col-sm-2"><?php echo "Adresse mail $inscription_ok"; ?> *</label>
		<div class="col-sm-5">
			<input type="text" id='Mail' name='Mail' class="form-control" placeholder="" autocomplete="off" value="<?php echo "$Mail"; ?>" style='<?php echo "$coloorm"; ?> margin-bottom: 0px;' />
		</div>
	</div>

	<div style='clear: both; margin-bottom: 15px;'></div>

	<div class="row style_color">
		<label class="control-label col-sm-2"><?php echo "Mot de passe actuel $inscription_ok"; ?>*</label>
		<div class="col-sm-5">
			<input type="password" id='password_actuel' name='password_actuel' class="form-control" id="password_actuel" placeholder="<?php echo "Mot de passe"; ?>" value="<?php echo "$passwordclient"; ?>" style='<?php echo "$coloorppasse"; ?> margin-bottom: 15px;' />
		</div>
	</div>

	<div style='clear: both; margin-bottom: 15px;'></div>

	<div id="rappot_mot_de_passe_nouveau" class="alert alert-warning" role="alert" style="margin-bottom: 10px; display: none;"><span class="uk-icon-exclamation-circle"></span> <b>Mot de passe</b> : Alphanumérique, 8 caractères avec miniscules et majuscules. Ex : Ni7Co1As</div>

	<div class="row style_color">
		<label class="control-label col-sm-2"><?php echo "Nouveau mot de passe $inscription_ok"; ?></label>
		<div class="col-sm-5">
			<input type="password" id='password' name='password' class="form-control" id="passwordclient" placeholder="<?php echo "Mot de passe"; ?>" value="<?php echo "$passwordclient"; ?>" style='<?php echo "$coloorppasse"; ?> margin-bottom: 15px;' />
		</div>
		<div class="col-sm-5">
			<input type="password" id='passwordclient2' name='passwordclient2' class="form-control" id="passwordclient2" placeholder="<?php echo "Confirmer mot de passe"; ?>" value="<?php echo "$passwordclient2"; ?>" style='<?php echo "$coloorppasse"; ?> margin-bottom: 15px;' />
		</div>
	</div>

	<div style='clear: both;'></div>

	<hr />
	<h2 class="style_color"><?php echo "Mes coordonnées"; ?></h2>
	<hr />

	<div class="row style_color">
		<label class="control-label col-sm-2"><?php echo "Civilité"; ?> *</label>
		<div class="col-sm-2">
			<select id="FH" name="FH" class="form-control" style='margin-bottom: 15px; <?php echo "$coloorpr"; ?>'>
				<option value="">Sélection</option>
				<option <?php if ($civilites_oo == "Madame") {
							echo "selected";
						} ?> value="Madame">Madame</option>
				<option <?php if ($civilites_oo == "Monsieur") {
							echo "selected";
						} ?> value="Monsieur">Monsieur</option>
			</select>
		</div>
	</div>

	<div style='clear: both; margin-bottom: 15px;'></div>

	<div class="row style_color">
		<div class="col-sm-6">
			<label class="control-label"><?php echo "Nom"; ?> *</label>
			<input type="text" id='Nom' name='Nom' class="form-control" placeholder="" value="<?php echo "$Nom"; ?>" style='margin-bottom: 15px; <?php echo "$coloorn"; ?>' />
		</div>
		<div class="col-sm-6">
			<label class="control-label"><?php echo "Prénom"; ?> *</label>
			<input id='Prenom' name='Prenom' type="text" class="form-control" placeholder="" value="<?php echo "$Prenom"; ?>" style='margin-bottom: 15px; <?php echo "$coloorpr"; ?>' />
		</div>
	</div>

	<div style='clear: both;'></div>

	<div class="row col-sm-12 style_color">
		<label class="control-label"><?php echo "Adresse"; ?> *</label>
		<input type="text" id='Adresse' name='Adresse' class="form-control" placeholder="<?php echo "Adresse"; ?>" value="<?php echo "$Adresse"; ?>" style='<?php echo "$coloorpaaa"; ?>' />
	</div>

	<div style='clear: both; margin-bottom: 15px;'></div>

	<div class="row">
		<div class="col-sm-6 style_color">
			<label class="control-label">Code postal*</label>
			<input id='Code_postal' name='Code_postal' type="text" class="form-control" placeholder="<?php echo "Code postal"; ?>" value="<?php echo "$Code_postal"; ?>" style='margin-bottom: 15px; <?php echo "$coloorpccc"; ?>' />
		</div>

		<div class="col-sm-6 style_color">
			<label class="control-label">Ville*</label>
			<input type="text" class="form-control" placeholder="<?php echo "Ville"; ?>" id='Ville' name='Ville' value="<?php echo "$Ville"; ?>" style='margin-bottom: 15px; <?php echo "$coloorpvvv"; ?>' />
		</div>
	</div>

	<div style='clear: both; margin-bottom: 15px;'></div>

	<div class="background-white">
		<hr />
		<h2 class="style_color"><?php echo "Coordonnées de contact"; ?></h2>
		<hr />

		<div class="row">
			<div class="col-sm-6 style_color" style='margin-bottom: 15px;'>
				<label>Téléphone fixe</label>
				<input type="text" id='Telephone' name='Telephone' class="form-control" placeholder="<?php echo "Téléphone"; ?>" value="<?php echo "$Telephone"; ?>" style='<?php echo "$coloorpccc1telfixe"; ?> height: 35px;' />
			</div>

			<div class="col-sm-6 style_color" style='margin-bottom: 15px;'>
				<label>Téléphone portable</label>
				<input type="text" id='Telephone_portable' name='Telephone_portable' class="form-control" placeholder="<?php echo "Portable"; ?>" value="<?php if (!empty($Telephone_portable)) {
																																								echo "$Telephone_portable";
																																							} else {
																																								echo "$Telephone";
																																							} ?>" style='<?php echo "$coloorpccc1portable"; ?> height: 35px;' />
			</div>
		</div>

	</div>

	<div style='clear: both; margin-bottom: 15px;'></div>

	<?php
	if ($statut_compte_oo == 2) {
	?>

		<div style='clear: both; margin-bottom: 15px;'></div>

		<div class="background-white">
			<hr />
			<h2 class="style_color"><?php echo "Informations"; ?></h2>
			<hr />

			<div class="row style_color">
				<div class="col-sm-6" style='margin-bottom: 15px;'>
					<label>Nom société</label>
					<input type="text" id='Nom_societe' name='Nom_societe' class="form-control" placeholder="<?php echo "Nom société"; ?>" value="<?php echo "$Nom_societe"; ?>" style='<?php echo "$coloorpccc1telfixe"; ?> height: 35px;' />
				</div>


				<div class="col-sm-6" style='margin-bottom: 15px;'>
					<label>Siret</label>
					<input type="text" id='Numero_identification' name='Numero_identification' class="form-control" placeholder="<?php echo "Siret"; ?>" value="<?php echo "$Numero_identification"; ?>" style='<?php echo "$coloorpccc1portable"; ?> height: 35px;' />
				</div>
			</div>

		</div>

		<div style='clear: both; margin-bottom: 15px;'></div>

	<?php
	}
	?>

	<?php
	if ($modif != "oui") {
	?>
		<div class="row style_color">
			<label class="control-label col-sm-6"></label>
			<div class="col-sm-10">
				<div class="checkbox">
					<label> <input id='cbb' name='cbb' type="checkbox" checked="checked" value='1' /><?php echo "Je m'inscris à la newsletter"; ?></label>
				</div>
			</div>
		</div>
	<?php
	}

	//////////////////////////////////////SI LES CONDITIONS GENERALES EXISTES
	if (!empty($lien_conditions_generales_compte)) {
	?>
		<div style="clear: both;"></div>
		<div class="row">
			<div class="col-sm-12" style="margin-bottom: 15px;">
				<?php echo "$lien_conditions_generales_compte"; ?></a>
			</div>
		</div>
	<?php
	}
	?>

	<div class="row">
		<div class="col-sm-12">
			<b style="font-weight : normal;">"Les données collectées par la plateforme sont nécessaires pour compléter votre profil. Vous disposez d'un droit d'accès, de rectification, d'opposition, de limitation du traitement, de suppression, de portabilité.
				Pour plus d'informations consultez notre <a class="style_color" href="/Traitements-de-mes-donnees" target="_blank">politique de confidentialité</a>"</b>
		</div>
	</div>


	<div class="row style_color">
		<label class="control-label col-sm-6"></label>
		<div class="col-sm-10">
			<small><?php echo "P.S : Tous les champs précédés d'une étoile (*) doivent être obligatoirement remplis."; ?></small>
		</div>
	</div>

	<div style="clear: both;"></div>

	<div class="row" style="margin-top: 15px;">
		<div class="col-sm-12" style="text-align: center;">
			<?php
			if ($modif != "oui") {
			?>
				<button type='button' id='creation_post' class='btn btn-default btn-white w-space btn-couleur' style='text-align: center; display: inline-block;' onclick="return false;">ENREGISTRER</button>
			<?php
			} else {
			?>
				<button type='button' id='modification_post' class='btn btn-default btn-white w-space btn-couleur' style='text-align: center; display: inline-block;' onclick="return false;">ENREGISTRER</button>
			<?php
			}
			?>
		</div>
	</div>

</div>


<div style='clear: both; margin-bottom: 20px;'></div>