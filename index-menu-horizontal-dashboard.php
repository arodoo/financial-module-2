<div class="header">
	<div class="header-content">
		<nav class="navbar navbar-expand">
			<div class="collapse navbar-collapse justify-content-between">

				<div class="header-left">
					<!--
					<div class="dashboard_bar">
						<div class="input-group search-area d-lg-inline-flex d-none">
							<div class="input-group-append">
								<button class="input-group-text search_icon search_icon" title="Chercher un trajet de covoiturage"><i class="flaticon-381-search-2"></i></button>
							</div>
							<input type="text" id="date_trajet" name="date_trajet" class="form-control" value="" placeholder="Chercher un trajet par date" data-default-date="">
						</div>
					</div>
					-->
				</div>

				<ul class="navbar-nav header-right">

				
					
					<li class="nav-item dropdown header-profile">
						<a class="nav-link" href="javascript:void(0)" role="button" data-bs-toggle="dropdown">
							<div class="header-info">
								<span class="text-black">Bonjour, <strong><?php echo $prenom_oo; ?> <b><?php echo strtoupper(substr($nom_oo, 0, 1)); ?>.</b> </strong></span>
								<p class="fs-12 mb-0"><?php if ($admin_oo > 0) {
															echo "Administrateur";
														} else { ?> <?php echo $id_statut_compte_membre; ?> <?php } ?> </p>
							</div>
							<?php
							if (!empty($image_profil_oo)) {
							?>
								<img alt="image" src="/images/membres/<?php echo $user; ?>/<?php echo $image_profil_oo; ?>" style="width:20;" alt="<?php echo $image_profil_oo; ?>" class="img-fluid rounded-circle">
							<?php
							} else {
							?>
								<img src="/images/profile/1.jpg" class="img-fluid rounded-circle" style="width:20;" alt="Photo de profil">
							<?php
							}
							?>
						</a>
						<div class="dropdown-menu dropdown-menu-end dropdown-menu2">
							<a href="/Gestion-de-votre-compte.html" class="dropdown-item ai-icon">
								<svg id="icon-info" xmlns="http://www.w3.org/2000/svg" class="text-primary" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
									<circle cx="12" cy="12" r="10"></circle>
									<line x1="12" y1="16" x2="12" y2="12"></line>
									<line x1="12" y1="8" x2="12.01" y2="8"></line>
								</svg>
								<span class="ms-2">Informations</span>
							</a>

							<a href="/Avatar" class="dropdown-item ai-icon">
								<svg id="icon-avatar" xmlns="http://www.w3.org/2000/svg" class="text-primary" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
									<path d="M20 21v-2a4 4 0 0 0-3-3.87"></path>
									<path d="M4 21v-2a4 4 0 0 1 3-3.87"></path>
									<path d="M9 8.35a4 4 0 1 1 6 0"></path>
									<line x1="12" y1="20" x2="12" y2="14"></line>
								</svg>
								<span class="ms-2">Photo de profil</span>
							</a>

							<a href="#" class="dropdown-item ai-icon Deconnexion" onclick="return false;">
								<svg id="icon-logout" xmlns="http://www.w3.org/2000/svg" class="text-danger" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
									<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
									<polyline points="16 17 21 12 16 7"></polyline>
									<line x1="21" y1="12" x2="9" y2="12"></line>
								</svg>
								<span class="ms-2">Déconnection </span>
							</a>
						</div>
					</li>
				</ul>
			</div>
		</nav>
	</div>
</div>