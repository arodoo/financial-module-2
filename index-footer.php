<?php
if ($_GET['page'] == "Extra") {
?>
	<div class="bg-warning text-white text-center p-4" style="background-color: #383838 !important;">
		<p class="text-white">Plateforme dédiée aux porteurs de projet et aventuriers</p>
		<a href="#" class="btn btn-dark pxp-header-inscription" style="background-color: #5FAA3E !important;" onclick="return false;">Je découvre</a>
	</div>

<?php
} elseif ($_GET['page'] == "Pro") {
?>

	<div class="bg-warning text-white text-center p-4" style="background-color: #383838 !important;">
		<p class="text-white">Plateforme dédiée aux porteurs de projet et aventuriers</p>
	</div>

<?php
}
?>

<!-- START FOOTER SECTION -->
<footer class="footer_dark
">
	<div class="top_footer" style="padding: 40px 0;">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="medium_divider"></div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-3 col-md-6 mb-4 mb-lg-0 animation" data-animation="fadeInUp" data-animation-delay="0.2s">
					<div class="footer_logo">
					</div>
					<p>Zen Famili est une application web dédiée à la planification budgétaire.</p>
				
					<p>Rejoignez notre communauté et prenez le contrôle de votre budget dès aujourd'hui !</p>

					<ul class="contact_info contact_info_light list_none">
						<li>
							<span class="ti-location-pin"></span>
							<address>13 rue des Erables, 35400 Saint-Malo</address>
						</li>
						<li>
							<span class="ti-email"></span>
							<a href="mailto:emoriconsulting@gmail.com">emoriconsulting@gmail.com | Contactez-nous</a>
						</li>
					</ul>
				</div>
				<div class="col-lg-2 col-md-6 mb-4 mb-lg-0 animation" data-animation="fadeInUp" data-animation-delay="0.3s">
					<h2 class="widget_title" style="font-size: 16px;">Zen Famili</h2>
					<ul class="list_none widget_links">
						<style>
							.widget_links li a.icon0::before {
								top: 23%;
							}
						</style>
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
							<li><a class="icon<?= $count ?>" href="<?php echo "$http"; ?><?php echo "$nomsiteweb"; ?>/<?php echo "$PagePage_footer_page"; ?>" title="<?php echo "$Ancre_lien_footer_footer_page - $nom_proprietaire"; ?>"><?php echo "$Ancre_lien_footer_footer_page"; ?></a></li>
						<?php
						}
						$req_boucle->closeCursor();
						?>

					</ul>
				</div>
				<!-- <div class="col-lg-3 col-md-6 mb-4 mb-lg-0 animation" data-animation="fadeInUp" data-animation-delay="0.4s">
					<h2 class="widget_title" style="font-size: 16px;">Mentions légales</h2>
					<div class="footer_mentons">
						<p>Société EMORI Consulting, Société par Actions Simplifiée au capital de 1000 euros.</p>
						<p>984 516 500 RCS Saint-Malo</p>
						<p>N° TVA intracommunautaire : FR04 984516500</p>
					</div>
				</div> -->
			</div>
		</div>
	</div>
	<div class="bottom_footer">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-md-6">
					<p class="copyright m-md-0 text-center text-md-left"><?php echo "$text_informations_footer"; ?> | EMORI Consulting SAS</p>
				</div>
				<div class="col-md-6">
					<ul class="list_none footer_link text-center text-md-right">
					</ul>
				</div>
			</div>
		</div>
	</div>
</footer>
<!-- END FOOTER SECTION -->