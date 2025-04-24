<!-- row -->
<div class="container-fluid">
	<div class="form-head mb-4">
		<h2 class="text-black font-w600 mb-0">Dashboard</h2>
	</div>
	<div class="row">
		<div class="col-md-6">
			<div class="row">
				<div class="col-xl-12">
					<div class="card">
						<div class="card-header flex-wrap border-0 pb-0">
							<div class="me-3 mb-2">
								<p class="fs-14 mb-1">Economie</p>
								<span class="fs-24 text-black font-w600">1400</span>
							</div>
							<span class="fs-12 mb-2">
								<svg width="21" height="15" viewBox="0 0 21 15" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M0.999939 13.5C1.91791 12.4157 4.89722 9.22772 6.49994 7.5L12.4999 10.5L19.4999 1.5" stroke="url(#paint1_linear1)" stroke-width="2" />
									<path d="M6.49994 7.5C4.89722 9.22772 1.91791 12.4157 0.999939 13.5H19.4999V1.5L12.4999 10.5L6.49994 7.5Z" fill="url(#paint1_linear1)" />
									<defs>
										<linearGradient id="paint1_linear1" x1="10.2499" y1="3" x2="10.9999" y2="13.5" gradientUnits="userSpaceOnUse">
											<stop offset="0" stop-color="#E89696" stop-opacity="0.73" />
											<stop offset="1" stop-color="#E89696" stop-opacity="0" />
										</linearGradient>
									</defs>
								</svg>
								+10% (30 jours)</span>
						</div>
						<div class="card-body p-0">
							<canvas id="widgetChart1_economie_euros" height="200"></canvas>
						</div>
					</div>
				</div>

				<div class="col-xl-12">
					<div class="card">
						<div class="card-header flex-wrap border-0 pb-0">
							<div class="me-3 mb-2">
								<p class="fs-14 mb-1">Economie CO2 kg</p>
								<span class="fs-24 text-black font-w600">700.20</span>
							</div>
							<span class="fs-12 mb-2">
								<svg width="21" height="15" viewBox="0 0 21 15" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M0.999939 13.5C1.91791 12.4157 4.89722 9.22772 6.49994 7.5L12.4999 10.5L19.4999 1.5" stroke="url(#paint1_linear1)" stroke-width="2" />
									<path d="M6.49994 7.5C4.89722 9.22772 1.91791 12.4157 0.999939 13.5H19.4999V1.5L12.4999 10.5L6.49994 7.5Z" fill="url(#paint1_linear1)" />
									<defs>
										<linearGradient id="paint1_linear1" x1="10.2499" y1="3" x2="10.9999" y2="13.5" gradientUnits="userSpaceOnUse">
											<stop offset="0" stop-color="#E89696" stop-opacity="0.73" />
											<stop offset="1" stop-color="#E89696" stop-opacity="0" />
										</linearGradient>
									</defs>
								</svg>
								+20% (30 jours)</span>
						</div>
						<div class="card-body p-0">
							<canvas id="widgetChart1_economie_co2" height="200"></canvas>
						</div>
					</div>
				</div>

				<div class="col-xl-12">
					<div class="card">
						<div class="card-header d-sm-flex d-block border-0 pb-0">
							<div class="pr-3 mb-sm-0 mb-3 me-auto">
								<h4 class="fs-20 text-black mb-1">Nouveaux inscrits</h4>
								<span class="fs-12">Tous les derneirs inscrits sur la solution</span>
							</div>
							<span class="fs-24 text-black font-w600"></span>
						</div>
						<div class="card-body">
							<div class="owl-carousel testimonial-one mb-5">
								<div class="item">
									<div class="image-bx mb-2">
										<?php
										if (!empty($image_profil_oo)) {
										?>
											<img width="63" class="rounded-circle" src="/images/membres/<?php echo ($pseudo_oo); ?>/<?php echo ($image_profil_oo); ?>" alt="Image de profil de <?php echo ($pseudo_oo); ?>">
										<?php
										} else {
										?>
											<img src="/images/profile/1.jpg" width="63" class="rounded-circle" alt="Photo de profil par défaut">
										<?php
										}
										?>
										<i class="las la-check-circle"></i>
									</div>
									<h6 class="fs-16 mb-0"><a href="" class="text-black">David</a></h6>
									<span class="fs-12">
										<i class="flaticon-381-star-1"></i>
										<i class="flaticon-381-star-1"></i>
										<i class="flaticon-381-star-1"></i>
										<i class="flaticon-381-star-1"></i>
										<i class="flaticon-381-star-1"></i>
									</span>
									<br>
									<a class="text-primary" href="" title="Profil">Profil</a>
								</div>

								<div class="item">
									<div class="image-bx mb-2">
										<?php
										if (!empty($image_profil_oo)) {
										?>
											<img width="63" class="rounded-circle" src="/images/membres/<?php echo ($pseudo_oo); ?>/<?php echo ($image_profil_oo); ?>" alt="Image de profil de <?php echo ($pseudo_oo); ?>">
										<?php
										} else {
										?>
											<img src="/images/profile/1.jpg" width="63" class="rounded-circle" alt="Photo de profil par défaut">
										<?php
										}
										?>
										<i class="las la-check-circle"></i>
									</div>
									<h6 class="fs-16 mb-0"><a href="" class="text-black">Cindy</a></h6>
									<span class="fs-12">
										<i class="flaticon-381-star-1"></i>
										<i class="flaticon-381-star-1"></i>
										<i class="flaticon-381-star-1"></i>
										<i class="flaticon-381-star-1"></i>
										<i class="flaticon-381-star-1"></i>
									</span>
									<br>
									<a class="text-primary" href="" title="Profil">Profil</a>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="col-xl-6 col-sm-12">
					<div class="card">
						<div class="card-body">
							<div class="media align-items-center invoice-card">
								<div class="media-body">
									<h2 class="text-black font-w600">20%</h2>
									<span>Economie Co2</span>
								</div>
								<span class="p-3 border ms-3 rounded-circle">
									<i class="fas fa-leaf" style="color: #ff9900; font-size: 34px;"></i> </span>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xl-6 col-sm-12">
					<div class="card">
						<div class="card-body">
							<div class="media align-items-center invoice-card">
								<div class="media-body">
									<h2 class="text-black font-w600">140€</h2>
									<span>Economie pécunière</span>
								</div>
								<span class="p-3 border ms-3 rounded-circle">
									<i class="fas fa-wallet" style="color: #ff9900; font-size: 34px;"></i>
								</span>
							</div>
						</div>
					</div>
				</div>

				<div class="col-xl-6 col-sm-12">
					<div class="card">
						<div class="card-body">
							<div class="media align-items-center invoice-card">
								<div class="media-body">
									<h2 class="text-black font-w600">4</h2>
									<span>Nombre de conducteur</span>
								</div>
								<span class="p-3 border ms-3 rounded-circle">
									<i class="fas fa-car" style="color: #ff9900; font-size: 34px;"></i>
								</span>
							</div>
						</div>
					</div>
				</div>

				<div class="col-xl-6 col-sm-12">
					<div class="card">
						<div class="card-body">
							<div class="media align-items-center invoice-card">
								<div class="media-body">
									<h2 class="text-black font-w600">2</h2>
									<span>Nombre de passager</span>
								</div>
								<span class="p-3 border ms-3 rounded-circle">
									<i class="fas fa-users" style="color: #ff9900; font-size: 34px;"></i>
								</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-xl-6">
			<div class="row">
				<div class="col-xl-12">
					<div class="card">
						<div class="card-header flex-wrap border-0 pb-0">
							<div class="me-3 mb-2">
								<p class="fs-14 mb-1">Trajets</p>
								<span class="fs-24 text-black font-w600">14</span>
							</div>
							<span class="fs-12 mb-2">
								<svg width="21" height="15" viewBox="0 0 21 15" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M0.999939 13.5C1.91791 12.4157 4.89722 9.22772 6.49994 7.5L12.4999 10.5L19.4999 1.5" stroke="url(#paint1_linear1)" stroke-width="2" />
									<path d="M6.49994 7.5C4.89722 9.22772 1.91791 12.4157 0.999939 13.5H19.4999V1.5L12.4999 10.5L6.49994 7.5Z" fill="url(#paint1_linear1)" />
									<defs>
										<linearGradient id="paint1_linear1" x1="10.2499" y1="3" x2="10.9999" y2="13.5" gradientUnits="userSpaceOnUse">
											<stop offset="0" stop-color="#E89696" stop-opacity="0.73" />
											<stop offset="1" stop-color="#E89696" stop-opacity="0" />
										</linearGradient>
									</defs>
								</svg>
								10% (30 jours)</span>
						</div>
						<div class="card-body p-0">
							<canvas id="widgetChart1" height="80"></canvas>
						</div>
					</div>
				</div>

				<div class="col-xl-12">
					<div class="card">
						<div class="card-header flex-wrap border-0 pb-0">
							<div class="me-3 mb-2">
								<p class="fs-14 mb-1">Co-voiturage</p>
								<span class="fs-24 text-black font-w600">km 140</span>
							</div>
							<span class="fs-12 mb-2">
								<svg width="21" height="15" viewBox="0 0 21 15" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
									<path d="M0.999939 13.5C1.91791 12.4157 4.89722 9.22772 6.49994 7.5L12.4999 10.5L19.4999 1.5" stroke="url(#paint1_linear2)" stroke-width="2" />
									<path d="M6.49994 7.5C4.89722 9.22772 1.91791 12.4157 0.999939 13.5H19.4999V1.5L12.4999 10.5L6.49994 7.5Z" fill="url(#paint1_linear2)" />
									<defs>
										<linearGradient id="paint1_linear2" x1="10.2499" y1="3" x2="10.9999" y2="13.5" gradientUnits="userSpaceOnUse" colors="#E89696">
											<stop offset="0" stop-color="#E89696" stop-opacity="0.73" />
											<stop offset="1" stop-color="#E89696" stop-opacity="0" />
										</linearGradient>
									</defs>
								</svg>
								20% (30 jours)</span>
						</div>
						<div class="card-body p-0">
							<canvas id="widgetChart2" height="80"></canvas>
						</div>
					</div>
				</div>

				<div class="col-xl-12">
					<div class="card">
						<div class="card-header flex-wrap border-0 pb-0">
							<div class="me-3 mb-2">
								<p class="fs-14 mb-1">Participations</p>
								<span class="fs-24 text-black font-w600">€ 40.25</span>
							</div>
							<span class="fs-12 mb-2">
								<svg width="21" height="15" viewBox="0 0 21 15" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M14.3514 7.5C15.9974 9.37169 19.0572 12.8253 20 14H1V1L8.18919 10.75L14.3514 7.5Z" stroke="url(#paint1_linear3)" />
									<path d="M19.5 13.5C18.582 12.4157 15.6027 9.22772 14 7.5L8 10.5L1 1.5" fill="url(#paint1_linear3)" stroke-width="2" stroke-linecap="round" />
									<defs>
										<linearGradient id="paint1_linear3" x1="10.5" y1="2.625" x2="9.64345" y2="13.9935" gradientUnits="userSpaceOnUse">
											<stop offset="0" stop-color="#E89696" stop-opacity="0.73" />
											<stop offset="1" stop-color="#E89696" stop-opacity="0" />
										</linearGradient>
									</defs>
								</svg>
								4% (30 jours)</span>
						</div>
						<div class="card-body p-0">
							<canvas id="widgetChart3" height="80"></canvas>
						</div>
					</div>
				</div>

				<div class="col-xl-12">
					<div class="card">
						<div class="card-body">
							<div class="row align-items-center">
								<div class="col-xl-5 col-xxl-12 col-md-5">
									<h4 class="fs-20 text-black mb-4">Synthèse</h4>
									<div class="row">
										<div class="d-flex col-xl-12 col-xxl-6  col-md-12 col-sm-6 mb-4">
											<svg class="me-3" width="14" height="54" viewBox="0 0 14 54" fill="none" xmlns="http://www.w3.org/2000/svg">
												<rect x="-6.10352e-05" width="14" height="54" rx="7" fill="#AC39D4" />
											</svg>
											<div>
												<p class="fs-14 mb-2">Trajets validés</p>
												<span class="fs-18 font-w500"><span class="text-black me-2">71%</span>/40 trajets</span>
											</div>
										</div>
										<div class="d-flex col-xl-12 col-xxl-6 col-md-12 col-sm-6 mb-4">
											<svg class="me-3" width="14" height="54" viewBox="0 0 14 54" fill="none" xmlns="http://www.w3.org/2000/svg">
												<rect x="-6.10352e-05" width="14" height="54" rx="7" fill="#461EE7" />
											</svg>
											<div>
												<p class="fs-14 mb-2">Trajets non validés</p>
												<span class="fs-18 font-w500"><span class="text-black me-2">29%</span>/18 trajets</span>
											</div>
										</div>
										<div class="d-flex col-xl-12 col-xxl-6 col-md-12 col-sm-6 mb-4">
											<svg class="me-3" width="14" height="54" viewBox="0 0 14 54" fill="none" xmlns="http://www.w3.org/2000/svg">
												<rect x="-6.10352e-05" width="14" height="54" rx="7" fill="#40D4A8" />
											</svg>
											<div>
												<p class="fs-14 mb-2">Trajets en conducteur</p>
												<span class="fs-18 font-w500"><span class="text-black me-2">30%</span>/12 trajets</span>
											</div>
										</div>
										<div class="d-flex col-xl-12 col-xxl-6 col-md-12 col-sm-6 mb-4">
											<svg class="me-3" width="14" height="54" viewBox="0 0 14 54" fill="none" xmlns="http://www.w3.org/2000/svg">
												<rect x="-6.10352e-05" width="14" height="54" rx="7" fill="#ff9900" />
											</svg>
											<div>
												<p class="fs-14 mb-2">Trajets en co-équipié</p>
												<span class="fs-18 font-w500"><span class="text-black me-2">70%</span>/28 trajets</span>
											</div>
										</div>
									</div>
								</div>

								<div class="col-xl-7  col-xxl-12 col-md-7">
									<div class="row g-3">

										<div class="col-sm-6">
											<div class="bg-secondary rounded text-center p-3">
												<div class="d-inline-block position-relative donut-chart-sale mb-3">
													<span class="donut1" data-peity='{ "fill": ["rgb(255, 255, 255)", "rgba(255, 255, 255, 0.2)"],   "innerRadius": 33, "radius": 10}'>7.1/10</span>
													<small class="text-white">71%</small>
												</div>
												<span class="fs-14 text-white d-block">Trajets validés</span>
											</div>
										</div>

										<div class="col-sm-6">
											<div class="bg-info rounded text-center p-3">
												<div class="d-inline-block position-relative donut-chart-sale mb-3">
													<span class="donut1" data-peity='{ "fill": ["rgb(255, 255, 255)", "rgba(255, 255, 255, 0.2)"],   "innerRadius": 33, "radius": 10}'>2.9/10</span>
													<small class="text-white">29%</small>
												</div>
												<span class="fs-14 text-white d-block">Trajets non validés</span>
											</div>
										</div>

										<div class="col-sm-6">
											<div class="bg-success rounded text-center p-3">
												<div class="d-inline-block position-relative donut-chart-sale mb-3">
													<span class="donut1" data-peity='{ "fill": ["rgb(255, 255, 255)", "rgba(255, 255, 255, 0.2)"],   "innerRadius": 33, "radius": 10}'>3/10</span>
													<small class="text-white">30%</small>
												</div>
												<span class="fs-14 text-white d-block">Trajets en conducteur</span>
											</div>
										</div>

										<div class="col-sm-6">
											<div class="bg-warning rounded text-center p-3">
												<div class="d-inline-block position-relative donut-chart-sale mb-3">
													<span class="donut1" data-peity='{ "fill": ["rgb(255, 255, 255)", "rgba(255, 255, 255, 0.2)"],   "innerRadius": 33, "radius": 10}'>7/10</span>
													<small class="text-white">70%</small>
												</div>
												<span class="fs-14 text-white d-block">Trajets co-équipié</span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {

		var ctx = document.getElementById('widgetChart1_economie_euros').getContext('2d');
		var myChart = new Chart(ctx, {
			type: 'bar',
			data: {
				labels: ['Jan', 'Fev', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul'],
				datasets: [{
					label: 'Economie en euros',
					data: [12, 19, 3, 5, 2, 3, 7],
					backgroundColor: 'rgba(43, 193, 85, 0.2)',
					borderColor: 'rgba(43, 193, 85, 0.2)',
					borderWidth: 1
				}]
			},
			options: {
				responsive: true,
				maintainAspectRatio: false,
				scales: {
					y: {
						beginAtZero: true
					}
				}
			}
		});

		var ctx = document.getElementById('widgetChart1_economie_co2').getContext('2d');
		var myChart = new Chart(ctx, {
			type: 'bar',
			data: {
				labels: ['Jan', 'Fev', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul'],
				datasets: [{
					label: 'Economie en CO2',
					data: [42.34, 29.56, 10.78, 60.90, 7.12, 10.45, 7.89], // Données fixes avec décimales
					backgroundColor: 'rgba(34, 139, 34, 0.2)',
					borderColor: 'rgba(34, 139, 34, 0.2)',
					borderWidth: 1
				}]
			},
			options: {
				responsive: true,
				maintainAspectRatio: false,
				scales: {
					y: {
						beginAtZero: true
					}
				}
			}
		});

	});
</script>