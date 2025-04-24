
		<div class="chatbox">
			<div class="chatbox-close"></div>
			<div class="custom-tab-1">
				<ul class="nav nav-tabs">
					<li class="nav-item">
						<a class="nav-link active" data-bs-toggle="tab" href="#chat">Messages</a>
					</li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane fade active show" id="chat" role="tabpanel">
						<div class="card mb-sm-3 mb-md-0 contacts_card dz-chat-user-box">
							<div class="card-header chat-list-header text-center">
								<div style="text-align: center; width: 100%;" >
									<h6 class="mb-1">Messages</h6>
									<p class="mb-0">Messages non lus</p>
								</div>
							</div>

							<div class="card-body contacts_body p-0 dz-scroll">
								<ul class="contacts">

									<?php
					///////////////////////////////SELECT BOUCLE
					$req_boucle = $bdd->prepare("SELECT * FROM membres_messages 
						WHERE pseudo=?
						OR pseudo_destinataire=? 
						ORDER BY date_message DESC");
					$req_boucle->execute(array(
						$user,
						$user
					));
					while ($ligne_boucle = $req_boucle->fetch()) {
						$idd_message_o = $ligne_boucle['id'];
						$id_membre_message_o = $ligne_boucle['id_membre'];
						$pseudo_message_o = $ligne_boucle['pseudo'];
						$id_membre_destinataire_message_o = $ligne_boucle['id_membre_destinataire'];
						$pseudo_destinataire_message_o = $ligne_boucle['pseudo_destinataire'];
						$id_article_message_o = $ligne_boucle['id_article'];
						$titre_message_message_o = $ligne_boucle['titre_message'];
						$message_message_o = nl2br($ligne_boucle['message']);
						$message_lu_message_o = $ligne_boucle['message_lu'];

						$date_lu_message_o1 = $ligne_boucle['date_lu'];
						if (!empty($date_lu_message_o1)) {
							$date_lu_message_o = date('d-m-Y', $date_lu_message_o1);
							$date_lu_message_oh = date('H\hi', $date_lu_message_o1);
						}

						$date_message_message_o1 = $ligne_boucle['date_message'];
						if (!empty($date_message_message_o1)) {
							$date_message_message_o = date('d-m-Y', $date_message_message_o1);
							$date_message_message_oh = date('H\hi', $date_message_message_o1);
						}

						$fichier_message_o = $ligne_boucle['fichier'];
						$ancre_message_o = $ligne_boucle['plus1'];

						///////////////////////////////SELECT
						$req_select = $bdd->prepare("SELECT * FROM membres WHERE pseudo=?");
						$req_select->execute(array($pseudo_message_o));
						$ligne_select = $req_select->fetch();
						$req_select->closeCursor();
						$idd_destinataire = $ligne_select['id'];
						$pseudo2_destinataire = $ligne_select['pseudo'];
						$mail_destinataire = $ligne_select['mail'];
						$nom_pseudo_message_o = $ligne_select['nom'];
						$prenom_pseudo_message_o = $ligne_select['prenom'];

						///////////////////////////////SELECT
						$req_select = $bdd->prepare("SELECT * FROM membres WHERE pseudo=?");
						$req_select->execute(array($pseudo_destinataire_message_o));
						$ligne_select = $req_select->fetch();
						$req_select->closeCursor();
						$idd_destinataire = $ligne_select['id'];
						$pseudo2_destinataire = $ligne_select['pseudo'];
						$mail_destinataire = $ligne_select['mail'];
						$nom_pseudo_destinataire_message_o = $ligne_select['nom'];
						$prenom_pseudo_destinataire_message_o = $ligne_select['prenom'];
						$pseudo_destinataire_message_o = $ligne_select['pseudo'];
						$image_pseudo_destinataire_message_o = $ligne_select['image_profil'];

						/////////////////////////NOMBRE DE MESSAGE
						///////////////////////////////SELECT
						$req_select = $bdd->prepare("SELECT COUNT(*) AS nbrmessage FROM membres_messages_reponse WHERE id_message=?");
						$req_select->execute(array($idd_message_o));
						$ligne_select = $req_select->fetch();
						$req_select->closeCursor();
						$idd_message_o_redirectm = $ligne_select['nbrmessage'];
						$idd_message_o_redirectm = (1 + $idd_message_o_redirectm);
						/////////////////////////NOMBRE DE MESSAGE

						/////////////////////////NOMBRE DE fichier
						///////////////////////////////SELECT
						$req_select = $bdd->prepare("SELECT COUNT(*) AS nbrfichier FROM membres_messages WHERE id=? AND fichier!=?");
						$req_select->execute(array($idd_message_o, ''));
						$ligne_select = $req_select->fetch();
						$req_select->closeCursor();
						$nbrfichiernbrfichier = $ligne_select['nbrfichier'];

						///////////////////////////////SELECT
						$req_select = $bdd->prepare("SELECT COUNT(*) AS nbrfichier_r FROM membres_messages_reponse WHERE id_message=? AND fichier!=?");
						$req_select->execute(array($idd_message_o, ''));
						$ligne_select = $req_select->fetch();
						$req_select->closeCursor();
						$nbrfichiernbrfichier_r = $ligne_select['nbrfichier_r'];
						$nbrfichiernbrfichier_r_total = ($nbrfichiernbrfichier + $nbrfichiernbrfichier_r);
						/////////////////////////NOMBRE DE fichier

						/////////////////////////////////////////////////////////DERNIER MESSAGE
						///////////////////////////////SELECT
						$req_select = $bdd->prepare("SELECT * FROM membres_messages_reponse WHERE id_message=? ORDER BY date_reponse_message DESC");
						$req_select->execute(array($idd_message_o));
						$ligne_select = $req_select->fetch();
						$req_select->closeCursor();
						$idd_message_o_r = $ligne_select['id'];
						$id_membre_message_o_r = $ligne_select['id_membre'];
						$pseudo_message_o_r = $ligne_select['pseudo'];
						$id_article_message_o_r = $ligne_select['id_message'];
						$titre_message_message_o_r = $ligne_select['titre_reponse_message'];
						$message_message_o_r = nl2br($ligne_select['message_reponse']);
						$message_lu_message_o_r = $ligne_select['message_reponse_lu'];

						///////////////////////////////SELECT
						$req_select = $bdd->prepare("SELECT * FROM membres WHERE pseudo=?");
						$req_select->execute(array($pseudo_message_o_r));
						$ligne_select = $req_select->fetch();
						$req_select->closeCursor();
						$idd_destinataire = $ligne_select['id'];
						$pseudo2_destinataire = $ligne_select['pseudo'];
						$mail_destinataire = $ligne_select['mail'];
						$nom_pseudo_message_o_r = $ligne_select['nom'];
						$prenom_pseudo_message_o_r = $ligne_select['prenom'];
						$pseudo_message_o_r = $ligne_select['pseudo'];
						$image_profil_message_o_r = $ligne_select['image_profil'];

						$date_lu_message_o1_r = $ligne_select['date_reponse_lu'];
						if (!empty($date_lu_message_o1_r)) {
							$date_lu_message_o_r = date('d-m-Y', $date_lu_message_o1_r);
							$date_lu_message_oh_r = date('H\hi', $date_lu_message_o1_r);
						}

						$date_message_message_o1_r = $ligne_select['date_reponse_message'];
						if (!empty($date_message_message_o1_r)) {
							$date_message_message_o_r = date('d-m-Y', $date_message_message_o1_r);
							$date_message_message_oh_r = date('H\hi', $date_message_message_o1_r);
						}

						$fichier_message_o_r = $ligne_select['fichier'];
						$ancre_message_o_r = $ligne_select['plus1'];

						if ($pseudo_message_o_r == $user && $pseudo_message_o_r != $pseudo_destinataire_message_o) {
							$pseudo_attente_reponse = "$prenom_pseudo_message_o_r $nom_pseudo_message_o_r";
							$image_profil_message = "$image_profil_message_o_r";
							$pseudo_message = "$pseudo_message_o_r";
						} else {
							$pseudo_attente_reponse = "$prenom_pseudo_destinataire_message_o $nom_pseudo_destinataire_message_o";
							$image_profil_message = "$image_pseudo_destinataire_message_o";
							$pseudo_message = "$pseudo_destinataire_message_o";
						}
						/////////////////////////////////////////////////////////DERNIER MESSAGE

						$message_message_o_r_len = strlen($message_message_o_r);
						$message_message_o_r = substr("$message_message_o_r", 0, 140);

						if ($message_message_o_r_len > 140) {
							$suite = "...";
						}

						////////////////////////////////////////////////////SI MESSAGE LU
						///////////////////////////////SELECT
						$req_select = $bdd->prepare("SELECT * FROM membres_messages 
							WHERE id=? 
							AND pseudo_destinataire=?
							AND message_lu=?");
						$req_select->execute(array(
							$idd_message_o,
							$user,
							'oui'
						));
						$ligne_select = $req_select->fetch();
						$req_select->closeCursor();
						$idd_message_o_rccl = $ligne_select['id'];

						///////////////////////////////SELECT
						$req_select = $bdd->prepare("SELECT * FROM membres_messages_reponse 
							WHERE id_message=?
							AND pseudo!=?
							AND message_reponse_lu=?");
						$req_select->execute(array(
							$idd_message_o,
							$user,
							'oui'
						));
						$ligne_select = $req_select->fetch();
						$req_select->closeCursor();
						$idd_message_o_rcll = $ligne_select['id'];
						////////////////////////////////////////////////////SI MESSAGE LU

						////////////////////////////////////////////////////SI MESSAGE NON LU
						///////////////////////////////SELECT
						$req_select = $bdd->prepare("SELECT * FROM membres_messages 
							WHERE id=?
							AND pseudo_destinataire=? 
							AND message_lu!=?");
						$req_select->execute(array(
							$idd_message_o,
							$user,
							'oui'
						));
						$ligne_select = $req_select->fetch();
						$req_select->closeCursor();
						$idd_message_o_rcc = $ligne_select['id'];

						///////////////////////////////SELECT
						$req_select = $bdd->prepare("SELECT * FROM membres_messages_reponse 
							WHERE id_message=?
							AND pseudo!=? 
							AND message_reponse_lu!=?
						");
						$req_select->execute(array(
							$idd_message_o,
							$user,
							'oui'
						));
						$ligne_select = $req_select->fetch();
						$req_select->closeCursor();
						$idd_message_o_rc = $ligne_select['id'];
						////////////////////////////////////////////////////SI MESSAGE NON LU

						////////////////////////////////////////////////////MESSAGE EN ATTENTE DE LECTURE
						///////////////////////////////SELECT
						$req_select = $bdd->prepare("SELECT * FROM membres_messages 
							WHERE id=?
							AND pseudo=?
							AND message_lu!=?");
						$req_select->execute(array(
							$idd_message_o,
							$user,
							'oui'
						));
						$ligne_select = $req_select->fetch();
						$req_select->closeCursor();
						$idd_message_o_rccc = $ligne_select['id'];

						///////////////////////////////SELECT
						$req_select = $bdd->prepare("SELECT * FROM membres_messages_reponse WHERE 
							id_message=? 
							AND pseudo=?
							AND message_reponse_lu!=?");
						$req_select->execute(array(
							$idd_message_o,
							$user,
							'oui'
						));
						$ligne_select = $req_select->fetch();
						$req_select->closeCursor();
						$idd_message_o_rcccc = $ligne_select['id'];
						////////////////////////////////////////////////////MESSAGE EN ATTENTE DE LECTURE

							if(!empty($idd_message_o_rcc) || !empty($idd_message_o_rc) ){

							?>

									<li class="active">
										<div class="d-flex bd-highlight">
											<div class="img_cont">
												<?php
												if (!empty($image_profil_message)) {
												?>
													<img src="/images/membres/<?php echo $pseudo_message; ?>/<?php echo $image_profil_message; ?>" class="rounded-circle user_img" alt="<?php echo $image_profil; ?>">
												<?php
												} else {
												?>
													<img src="/images/avatar/1.jpg" class="rounded-circle user_img" alt="">
												<?php
												}
												?>
											</div>
											<div class="user_info">
												<span><?php echo $pseudo_attente_reponse; ?></span>
												<p><?php echo "Objet : $titre_message_message_o"; ?></p>
												<span class="alert alert-danger"><?php echo "<span class='uk-icon-warning' ></span> Message non lu"; ?></span> <br>
												<p><?php echo "" . $date_message_message_o_r . " à " . $date_message_message_oh_r . ""; ?></p>
											</div>
										</div>
									</li>
							<?php

							}
						}									
									?>

								</ul>
							</div>

						</div>

					</div>

				</div>
			</div>
		</div>
