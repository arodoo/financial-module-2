<div class="deznav menu_lateral_left">
	<div class="deznav-scroll">
		<ul class="metismenu" id="menu">
			<!-- Financial Planning - Individual menu items -->
			<li><a class="ai-icon" href="/Tableau-de-Bord" aria-expanded="false">
					<i class="fas fa-tachometer-alt"></i>
					<span class="nav-text">Dashboard</span>
				</a>
			</li>

			<li><a class="ai-icon" href="/Revenus-Depenses" aria-expanded="false">
					<i class="fas fa-chart-pie"></i>
					<span class="nav-text">Revenus & Dépenses</span>
				</a>
			</li>

			<li><a class="ai-icon" href="/Gestion-Actifs" aria-expanded="false">
					<i class="fas fa-building"></i>
					<span class="nav-text">Gestion des actifs</span>
				</a>
			</li>

			<li><a class="ai-icon" href="/Paiements-Fixes" aria-expanded="false">
					<i class="fas fa-credit-card"></i>
					<span class="nav-text">Paiements fixes</span>
				</a>
			</li>

			<li><a class="ai-icon" href="/Projection-Financiere" aria-expanded="false">
					<i class="fas fa-clock"></i>
					<span class="nav-text">Projection Financière</span>
				</a>
			</li>

			<!-- <li><a class="ai-icon" href="/Simulateur-Pret" aria-expanded="false">
					<i class="fas fa-calculator"></i>
					<span class="nav-text">Simulateur de prêt</span>
				</a>
			</li>

			<li><a class="ai-icon" href="/Frais-Scolaires" aria-expanded="false">
					<i class="fas fa-graduation-cap"></i>
					<span class="nav-text">Frais scolaires</span>
				</a>
			</li> -->

			<li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
					<i class="fas fa-cog"></i>
					<span class="nav-text">Mon compte
						<span class="badge" title="Messages"><?php echo $total_message_non_lu; ?></span>
					</span>
				</a>
				<ul aria-expanded="false">

					<?php
					//////////////////////////////////SI ADMIN
					if ($admin_oo > 0) {
						echo "<li class='dropdown-item' ><a class='test' href='/administration/index-admin.php' ><span class='uk-icon-cogs'></span> Admin</a><li>";
					}
					if ($statut_compte_oo == 1) {
						?>

						<?php
					}

					?>
					<li><a href="<?php echo "$http"; ?><?php echo "$nomsiteweb"; ?>/Gestion-de-votre-compte.html"
							title="<?php echo "Mes informations"; ?>"><?php echo "Mes informations"; ?> </a></li>
					<?php
					// if($statut_compte_oo == 1 ){
					?>
					<li><a href="<?php echo "$http"; ?><?php echo "$nomsiteweb"; ?>/<?php echo "Avatar"; ?>"
							title="<?php echo "Avatar"; ?>"><?php echo "Avatar"; ?></a></li>
					<?php
					// }
					if ($statut_compte_oo >= 0) {
						?>
						<!-- <li><a href="<?php echo "$http"; ?><?php echo "$nomsiteweb"; ?>/Blocs-publicites" title="<?php echo "Publicités"; ?>"><?php echo "Mes publicités"; ?></a></li> -->
						<?php
					}

					if ($statut_compte_oo == 1) {
						?>
						<li><a href="<?php echo "$http"; ?><?php echo "$nomsiteweb"; ?>/Messagerie.html"
								title="<?php echo "Messagerie"; ?>"><?php echo "Messagerie"; ?> <span
									class="badge badge-primary"><?php echo $total_message_non_lu; ?></span></a></li>
						<?php
					}
					if ($statut_compte_oo != 1 && $statut_compte_oo != 6) {
						?>
						<!-- <li><a href="<?php echo "$http"; ?><?php echo "$nomsiteweb"; ?>/faq-extras.html" title="<?php echo "FAQ Extras"; ?>"><?php echo "FAQ Extras"; ?></a></li> -->
						<?php
					}
					?>
					<li><a id='Deconnexion' class='Deconnexion' href='#'>Déconnexion</a></li>
				</ul>
			</li>

			<!-- <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
					<i class="fas fa-file"></i>
					<span class="nav-text">Blog</span>
				</a>
				<ul aria-expanded="false">
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
						<li><a class="dropdown-item nav-link nav_item"
								href="<?php echo "$http"; ?><?php echo "$nomsiteweb"; ?>/<?php echo "$nom_url_categorie"; ?>"
								title="<?php echo "$Ancre_menu"; ?>"><?php echo "$Ancre_menu"; ?></a></li>
						<?php
					}
					$req_boucle->closeCursor();
					?>
				</ul>
			</li> -->

			<li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
					<i class="flaticon-381-internet"></i>
					<span class="nav-text">Pages</span>
				</a>
				<ul aria-expanded="false">
					<?php
					/////////////////////////////SELECT BOUCLE
					$req_boucle = $bdd->prepare("SELECT * FROM pages 
                   						 WHERE presence_footer=? 
                    							AND Statut_page=? 
                    							ORDER by position_footer ASC");
					$req_boucle->execute(array(
						"oui",
						"oui"
					));
					$count = 0;
					while ($ligne_boucle = $req_boucle->fetch()) {
						$id_page_menu = $ligne_boucle['id'];
						$PagePage_footer_page = $ligne_boucle['Page'];
						$Ancre_lien_footer_footer_page = $ligne_boucle['Ancre_lien_footer'];
						?>
						<li><a
								href="<?php echo "$http"; ?><?php echo "$nomsiteweb"; ?>/<?php echo "$PagePage_footer_page"; ?>"><?php echo "$Ancre_lien_footer_footer_page"; ?></a>
						</li>
						<?php
					}
					$req_boucle->closeCursor();
					?>
				</ul>
			</li>
		</ul>

		<div class="copyright" style="text-align: center;">
			<p>Copyright © Zen Famili</p>
		</div>

	</div>
</div>