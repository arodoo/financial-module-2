
		<div class="footer">
			<div class="copyright" style="width: 100%; text-align: center;" >
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
							<a class="icon<?= $count ?>" href="<?php echo "$http"; ?><?php echo "$nomsiteweb"; ?>/<?php echo "$PagePage_footer_page"; ?>" title="<?php echo "$Ancre_lien_footer_footer_page - $nom_proprietaire"; ?>" style="margin-left: 5px; margin-right: 5px;" ><?php echo "$Ancre_lien_footer_footer_page"; ?></a> 
						<?php
						}
						$req_boucle->closeCursor();
						?>


				<p><?php echo "$text_informations_footer"; ?></p>
			</div>

					<ul class="list_none footer_social">
						<?php
						/////ICONS DES RESEAUX SOCIAUX CMS CODI ONE
						include('function/reseaux-sociaux/reseaux-sociaux.php');
						////ICONS DES RESEAUX SOCIAUX CMS CODI ONE
						?>
					</ul>

		</div>