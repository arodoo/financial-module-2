<?php

if (!empty($_GET['page']) && $_GET['page'] != "Annonce") {

    if ($_GET['page'] == "Plateforme" || $_GET['page'] == "Plateforme2" || $_GET['page'] == "Fiche-annonce") {
?>
        <section class="page-title-light" style="padding-top: 80px; padding-bottom: 80px; background-color: #D6D31B;">
            <div class="container">
                <?php
                include('index-fil-ariane.php');
                ?>
            </div>
        </section>
    <?php
    } else {
    ?>
        <section class="page-title-light" style="padding: 10px; 
 padding-top: 80px; padding-bottom: 80px; background-color: #00A1D7;">
            <div class="container">
                <?php
                if ($_GET['page'] != "Pro" && $_GET['page'] != "Extra") {
                    include('index-fil-ariane.php');
                } ?>
            </div>
        </section>

    <?php
    }
} elseif ($_GET['page'] == "Contact") {
    ?>
    <!-- image pour header de la page contact -->
    <section class="page-title-light" style="padding: 10px; padding-top: 80px; padding-bottom: 80px; background-image: url('/images/blue.jpeg');">
        <div id="carouselExampleControls" class="banner_content_wrap carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item" style="display: block;">
                    <div class="banner_slide_content">
                        <div class="container"><!-- STRART CONTAINER -->
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 text-left">
                                    <div class="banner_content text_white">
                                        <div class="col-lg-8 col-md-9 animation animated fadeInUp " data-animation="fadeInUp" data-animation-delay="0.1s" style="animation-delay: 0.1s; opacity: 1;">
                                            <h1 class="text-white"><small><?php echo $Titre_h1_page; ?></small></h1>
                                            <p class=" text-white" style="font-size: 25px;">DEMANDE URGENTE<br>Contactez-nous !</p>
                                            <p>
                                                <a href="mailto:<?php echo "$emaildefault"; ?>"><?php echo "$emaildefault"; ?></a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- END CONTAINER-->
                    </div>
                </div>

            </div>
        </div>
    </section>

<?php
} else {
    ////Si page accueil
?>

    <section class="header_wrap banner_section p-0 full_screen">
        <div class="container-fluid p-0">
            <div class="row justify-content-center" style="margin-right: 0px;">
                <div class="carousel-inner">
                    <div class="carousel-item" style="display: block;">
                        <div class="banner_slide_content">
                            <div class="banner_content text_white col-lg-12">
                                <div class="row align-items-center text-center text-lg-start">

                                    <div class="col-md-2 d-flex justify-content-center">

                                    </div>

                                    <div class="col-md-10 text-overlay">
                                        <h1>
                                            <small>
                                            <b>Zen Famili</b>  a pour objectif de permettre aux familles de cheminer sereinement dans leur parcours de vie jalonné de multiples événements qui font évoluer l'équilibre financier du couple. 
                                            </small>
                                        </h1>
                                        <p class="mt-4">
                                        Zen Famili est une application de planification et gestion financière permettant à la famille de visualiser son budget et son patrimoine en modulant les paramètres suivant son cycle de vie. Anticiper l'évolution de ses besoins financiers permet d'agir et mettre en place des stratégies gagnantes sur le long terme, en préservant les intérêts de chacun. 
                                        </p>
                                        
                                    </div>


                                </div>
                                <!-- <div class="row">
                                <div class="col-12 col-lg text-overlay text-bottom">
                                        <p>Ici, on se pose pour s'envoler !</p>
                                        <p>Néo-ruraux ou amoureux des villes, ici commence l'aventure...</p>
                                    </div>

                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        $(document).ready(function() {
            // Ecouter le changement sur le selectpicker
            $('.departAcc').on('change', function() {
                // Récupérer la valeur du selectpicker
                // Rediriger vers l'URL correspondante
                $(location).attr("href", $(this).val());
            });
            $(document).on('click', '.btnRecherche', function() {
                $('#formRecherche').submit();
            })

            $(document).on('click', '.inscription-custom', function() {

            });
        });

        //////////////////////////////////////////////////////////////////////TAB DEPUIS CHAMPS DE RECHERCHE -> PUIS ENTREE SUR BOUTON RECHERCHE 
        $(document).on('keyup keypress', '.call_btn', function(e) {
            // $(document).on('keypress', '.btnRecherche', function(e) {
            // $(document).on('keypress keyup', '.call_btn', function(e) {
            var keyCode = e.keyCode || e.which;
            console.log(e.keyCode);
            console.log("a");
            console.log(keyCode);
            if (keyCode === 13) {
                console.log("ENTER OK");
                e.preventDefault();
                $('#formRecherche').submit();
                // $(this).click();
                return false;
            }
        });
    </script>


    <!--     <script>
        $(document).ready(() => {
            const time = <?= json_encode(time()) ?>;

            // GEOLOC AU CLICK
            $('.geoloc_marker').click(() => {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition((position) => {
                        console.log(position)
                        const latHere = position.coords.latitude
                        const lngHere = position.coords.longitude

                        $.post({
                            url: '/pages/Plateforme/Plateforme-retrieve-coords-ajax.php',
                            processData: false,
                            data: JSON.stringify({
                                lat: latHere,
                                lng: lngHere
                            }),
                            contentType: "json",
                            dataType: "json",
                            success: ((res) => {
                                console.log(res)
                                if (res.res == "ok") {
                                    localStorage.setItem("lat", latHere)
                                    localStorage.setItem("lng", lngHere)
                                    localStorage.setItem("last_activity", time)
                                    $(location).attr("href", "/Plateforme");
                                }
                            })
                        })
                    });
                } else {
                    console.log("Geoloc is not supported by this browser.");
                }
            })

        })
    </script> -->

<?php
}
?>