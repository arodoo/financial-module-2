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

if (!empty($_SESSION['4M8e7M5b1R2e8s']) && !empty($user))
{
?>
    <style>
    .lds-ring {
  display: inline-block;
  position: relative;
  width: 80px;
  height: 80px;
}
.lds-ring div {
  box-sizing: border-box;
  display: block;
  position: absolute;
  width: 64px;
  height: 64px;
  margin: 8px;
  border: 8px solid #fff;
  border-radius: 50%;
  animation: lds-ring 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
  border-color: #00d7b3 transparent transparent transparent;
}
.lds-ring div:nth-child(1) {
  animation-delay: -0.45s;
}
.lds-ring div:nth-child(2) {
  animation-delay: -0.3s;
}
.lds-ring div:nth-child(3) {
  animation-delay: -0.15s;
}
@keyframes lds-ring {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}


    </style>
    <script>
        $(document).ready(function (){

            $(document).on("change", "#type_KYC", function (){
                type_mangopay();
            });

            function type_mangopay(){
                if($("#type_KYC").val() == "SA" || $("#type_KYC").val() == "SNC" || $("#type_KYC").val() == "SAS" || $("#type_KYC").val() == "SASU" || $("#type_KYC").val() == "EURL" || $("#type_KYC").val() == "SARL" ){
                    $(".Statut_a_jour_signe").css("display","");
                }
                else{
                    $(".Statut_a_jour_signe").css("display","none");
                    $("#Statut_a_jour_signe").val("");
                }
            }
            type_mangopay();

            $(document).on("click", "#mise_ajour_profil", function (){
                $.post({
                    url : '/panel/Profil/Modifier-profil-ajax.php',
                    type : 'POST',
                    data: new FormData($("#formulaire_profil")[0]),
                    processData: false,
                    contentType: false,
                    dataType: "json",
                    success: function (res) {
                        if(res.retour_validation == "ok"){
                            popup_alert(res.Texte_rapport,"green filledlight","#009900","uk-icon-check");
                            setTimeout(function(){$(location).attr("href", "");},1000); 
                        }
                        else{
                            popup_alert(res.Texte_rapport,"#CC0000 filledlight","#CC0000","uk-icon-times");
                        }
                    }
                });

            });

//AJAX SOUMISSION DU FORMULAIRE
            $(document).on("click", "#mise_ajour_profil_paiement", function (){ 

                // var oData = new FormData($("#formulaire_profil_paiement")[0]);

                // var oReq = new XMLHttpRequest();
                // oReq.open("POST", "/panel/Profil/Modifier-profil-paiement-ajax.php", true);
                // // oReq.setRequestHeader('Content-Type','multipart/form-data');
                // oReq.withCredentials = true;
                // oReq.onload = function(oEvent) {
                //     console.log(oEvent);
                //     // console.log(oEvent);
                //     // data = JSON.parse(oEvent.target.response);
                //     // if (oReq.status == 200) {
                //     //     if(data.retour_validation == 'ok')
                //     //     {
                //     //         popup_alert(data.Texte_rapport,"green filledlight","#009900","uk-icon-check");
                //     //         setTimeout(function(){$(location).attr("href", "");},1000); 
                //     //     }
                //     //     else
                //     //     {
                //     //         popup_alert(data.Texte_rapport,"#CC0000 filledlight","#CC0000","uk-icon-times");
                //     //     }
                        
                //     // } else {
                //     //     console.log(oEvent);
                //     //     popup_alert(oEvent,"#CC0000 filledlight","#CC0000","uk-icon-times");
                //     // }
                // };

                // oReq.send(oData);
                var formElement = $("#formulaire_profil_paiement");
                var bannerfil = new FormData();  

                // [type="file"] will be handled separately
                formElement.find('input[name][type!="file"], select[name], textarea[name]').each(function(i, e) {
                    if ($(e).attr('type') == 'checkbox' || $(e).attr('type') == 'radio') {
                    if ($(e).is(':checked')) {
                        bannerfil.append($(e).attr('name'), $(e).val());
                    }
                    } else {
                    bannerfil.append($(e).attr('name'), $(e).val());
                    }
                });

                formElement.find('input[name][type="file"]').each(function(i, e) {
                    if ($(e)[0].files.length > 0) {
                    bannerfil.append($(e).attr('name'), $(e)[0].files[0]); 
                    }
                });

                $(this).html('CHARGEMENT...')
                $('#loader').show()

                $.post({
                    url : '/panel/Profil/Modifier-profil-paiement-ajax.php',
                    type : 'POST',
                    data: bannerfil,
                    processData: false,
                    contentType: false,
                    xhrFields: {
                        withCredentials: true
                    },
                    dataType: "json",
                    success: function (res) {
                        if(res.retour_validation == "ok"){
                            popup_alert(res.Texte_rapport,"green filledlight","#009900","uk-icon-check");
                            setTimeout(function(){$(location).attr("href", "");},1000); 
                        }
                        else{
                            popup_alert(res.Texte_rapport,"#CC0000 filledlight","#CC0000","uk-icon-times");
                            $('#mise_ajour_profil_paiement').html('METTRE A JOUR')
                            $('#loader').hide()
                        }
                    }
                });

            });

            $('#payer_frais_kyc_mangopay').click(function() {
            	// Post
            	$.post({
            		url : '/panel/Profil/Modifier-profil-payer-frais-mangopay-ajax.php',
            		type : 'GET',
            		success: function (res) {
            			res = JSON.parse(res)
            			if(res.retour_validation == "ok"){
                            popup_alert(res.Texte_rapport,"green filledlight","#009900","uk-icon-check");
                            setTimeout(function(){$(location).attr("href", res.retour_lien);},1000); 
                        }
                        else{
                            popup_alert(res.Texte_rapport,"#CC0000 filledlight","#CC0000","uk-icon-times");
                        }
            		}
            	})
            })

            $('#utiliser_code_promo').click(function() {
            	$.post({
            		url : '/panel/Profil/Modifier-profil-code-promo.php',
            		type : 'POST',
            		data : {code_promo : $('#code_promo').val()},
            		success: function (res) {
            			res = JSON.parse(res)
            			if(res.retour_validation == "ok"){
                            popup_alert(res.Texte_rapport,"green filledlight","#009900","uk-icon-check");
                            setTimeout(function(){$(location).attr("href", res.retour_lien);},1000); 
                        }
                        else{
                            popup_alert(res.Texte_rapport,"#CC0000 filledlight","#CC0000","uk-icon-times");
                        }
            		}
            	})
            })

        });
    </script>

    <div class="form-popup" style="margin-bottom: 20px;" >
        <div class="form-popup-content" >
            <h2>Télécharger une image pour votre avatar</h2>
            <hr />

            <form method='post' id='formulaire_image' action='/Photos/recadrage/upload' enctype='multipart/form-data' >
                <table style='width: 100%;' >

                    <tr>
                        <td style='text-align: center;'>
                            <?php
                            if (!empty($image_profil_oo))
                            {
                            ?>
                                <img src="/images/membres/<?php echo "$user"; ?>/<?php echo "$image_profil_oo"; ?>" alt="<?php echo "$image_profil_oo"; ?>" style='cursor: pointer; border-radius: 50%; margin-bottom: 20px;' />
                                <input type='file' name='images' id="images" style='width: 100%;'onchange="document.getElementById('formulaire_image').submit();" /><br />
                            <?php
                            }
                            else
                            {
                            ?>
                                <input type='file' name='images' id="images" style='width: 100%;' onchange="document.getElementById('formulaire_image').submit();" /><br />
                            <?php
                            }
                            ?>
                        </td>
                    </tr>

                </table>
                <p style="text-align: left;">Formats autorisés .jpg ou .png</p>
                <div class="alert alert-info" style="text-align: left;" >L'image sera récadreé en 100x100, on recommande de télécharger une image avec une dimension de 100px de largeur.<br /> Et de 100px de hauteur pour conserver une bonne définition.</div>

            </form>
        </div>
    </div>

    <?php /*
    <!--
        <form method='post' id='formulaire_profil' action='#' enctype='multipart/form-data'>
    
                        <div class="form-group MarginBottom10">
                            <div class="col-sm-12" style='margin-bottom: 15px; text-align: left;'>
                                <label><?php echo "Titre"; ?></label>
                                <input type='text' class="form-control" id='Titre_profil' name='Titre_profil' value="<?php echo "$Titre_profil"; ?>" style='width: 100%;' />
                            </div>
                        </div>
    
                        <div class="form-group MarginBottom10">
                            <div class="col-sm-12" style='margin-bottom: 15px; text-align: left;'>
                                <label><?php echo "Description"; ?></label>
                                <textarea class="form-control" id='description_post' name='description_post' style='width: 100%; height: 100px;' /><?php echo "$description_post"; ?></textarea>
                            </div>
                        </div>
    
            <div class="form-group">
                <div class="col-sm-12" style='margin-bottom: 15px; text-align: center;'>
    <button type='button' id='mise_ajour_profil' class='btn btn-success' onclick="return false;" >METTRE A JOUR</button>
                </div>
            </div>
    -->
    */ ?>

    <?php
    if ($statut_compte_oo == 2)
    {
        ?>

        <form method='post' novalidate="true" id='formulaire_cgl' action='#' enctype='multipart/form-data'>
            
            <!-- pas très propre le style dans les balises -->
            <?php
            // On récupère les CGL du commerçant
            $sql_get_cgl = $bdd->prepare("SELECT CGL, cgl_default FROM 
                membres, configurations_preferences_generales
                WHERE membres.pseudo=?");
            $sql_get_cgl->execute(array(
                $user
            ));
            $cgl = $sql_get_cgl->fetch(PDO::FETCH_ASSOC);
            $sql_get_cgl->closeCursor();
            ?>

            <!-- Formulaire CGL -->
            <h2 style="margin-top: 20px;">Veuillez indiquez vos CGL</h2>
            <div style="margin-top: 20px;" >
                <div class="form-group">
                    <textarea name='cgl_membres' id='cgl_membres' class='mceEditor' style='width: 100%; min-height:200px; '><?=$cgl['CGL'] != null ? $cgl['CGL'] : $cgl['cgl_default'] ?></textarea>
                </div>

                <div class="form-group">
                    <div class="col-sm-12" style='margin-bottom: 15px; text-align: center;'>
                        <button type='button' id='mise_ajour_profil' class='btn btn-success' onclick="return false;" >METTRE A JOUR</button>
                    </div>
                </div>
            </div>

            <!-- A mettre dans les balises footer -->
            
            <script>
                $('#formulaire_cgl').click((e) => {
                    e.preventDefault()
                    tinyMCE.triggerSave(true, true)
                    $.post( "/panel/Profil/Modifier-profil-cgl-ajax.php", {cgl : $('#cgl_membres').val()})
                })
            </script>

        </form>

        <form method='post' novalidate="true" id='formulaire_profil' action='#' enctype='multipart/form-data'>

            <h2 style="margin-bottom: 20px;" >Paramètres pour les réservations</h2>   

            <div style="margin-top: 20px;" >
                <div class="form-group">
                    <div class="col-sm-6" style='margin-bottom: 15px; text-align: left;'>
                        <label>
                            <input type='radio' class="form-control" <?php if ($accepter_toutes_les_demandes_oo == "non"){ echo "checked";} ?> name='accepter_toutes_les_demandes' value="non" style='display: inline-block; width: 20px; margin-top: 2px;' /> Ne pas accepter toutes les demandes de réservation automatiquement 
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-6" style='margin-bottom: 15px; text-align: left;'>
                        <label>
                            <input type='radio' class="form-control" <?php if ($accepter_toutes_les_demandes_oo == "oui" || $accepter_toutes_les_demandes_oo == ""){ echo "checked";} ?> name='accepter_toutes_les_demandes' value="oui" style='display: inline-block; width: 20px; margin-top: 2px;' /> Accepter toutes les demandes de réservation automatiquement
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-12" style='margin-bottom: 15px; text-align: center;'>
                        <button type='button' id='mise_ajour_profil' class='btn btn-success' onclick="return false;" >METTRE A JOUR</button>
                    </div>
                </div>
            </div>

        </form>

        <form method='post' id='formulaire_profil_paiement' action='#' enctype='multipart/form-data'>

            <div style='clear: both; margin-bottom: 20px;' > </div>

            <?php
            //GESTION DES ETATS DES DOCUMENTS MANGOPAY
            require_once $_SERVER['DOCUMENT_ROOT'] . '/mangopay/mangopayClass.php';
            //On va interroger l'état du document CNI
            $sql = $bdd->prepare("SELECT * FROM membres 
                LEFT JOIN membres_professionnel ON (membres_professionnel.id_membre = membres.id) 
                LEFT JOIN membres_profil_paiement ON (membres_profil_paiement.id_membre = membres.id) 
                WHERE membres.id = ?");
            $sql->execute(array(
                $id_user
            ));
            $leUserPourMP = $sql->fetch(PDO::FETCH_ASSOC);
            $sql->closeCursor();
            $proofs = [
            	'ARTICLES_OF_ASSOCIATION' => [
            		'valid_item' => [
            			'class' => '',
            			'message' => ''
            		],
            		'current_item' => [
            			'class' => '',
            			'message' => ''
            		]
            	],
            	'IDENTITY_PROOF' => [
            		'valid_item' => [
            			'class' => '',
            			'message' => ''
            		],
            		'current_item' => [
            			'class' => '',
            			'message' => ''
            		]
            	],
            	'REGISTRATION_PROOF' => [
            		'valid_item' => [
            			'class' => '',
            			'message' => ''
            		],
            		'current_item' => [
            			'class' => '',
            			'message' => ''
            		]
            	],
            	'UBO' => [
            		'valid_item' => [
            			'class' => '',
            			'message' => ''
            		],
            		'current_item' => [
            			'class' => '',
            			'message' => ''
            		]
            	]
            ];

            $validation_asked = false;
            $id_created = false;
            $register_created = false;
            $aoa_created = false;
            $ubo_created = false;

			if (!empty($leUserPourMP['kyc_id']))
            {
                $datas_for_mangopay = ['UserId' => $leUserPourMP['bank_id'], 'KycDocumentId' => $leUserPourMP['kyc_id']];
                $infosKycCni = json_decode(mangopay_viewKycDocument($api, $datas_for_mangopay));

                if ($infosKycCni->Status == 'REFUSED')
                {
                	$proofs['IDENTITY_PROOF']['current_item']['class'] 		= 'alert-danger';
        			$proofs['IDENTITY_PROOF']['current_item']['message'] 	= $infosKycCni->RefuseReasonType." ".$infosKycCni->RefuseReasonMessage;
                }
                else if ($infosKycCni->Status == 'VALIDATION_ASKED')
                {
                    $proofs['IDENTITY_PROOF']['current_item']['class'] 		= 'alert-info';
        			$proofs['IDENTITY_PROOF']['current_item']['message'] 	= 'En cours de validation par MangoPay';
        			$validation_asked = true;
                }
                else if($infosKycCni->Status == 'CREATED')
                {
                    $proofs['IDENTITY_PROOF']['current_item']['class'] 		= 'alert-warning';
        			$proofs['IDENTITY_PROOF']['current_item']['message'] 	= 'Vous devez valider les frais de dossier MangoPay.';
        			$id_created = true;
                }
            }
            else
            {
            	$proofs['IDENTITY_PROOF']['current_item']['class'] 		= 'alert-danger';
    			$proofs['IDENTITY_PROOF']['current_item']['message'] 	= 'Vous n\'avez transmis aucun document d\'identité pour le moment';
            }

            if (!empty($leUserPourMP['registration_id']))
            {

                $datas_for_mangopay = ['UserId' => $leUserPourMP['bank_id'], 'KycDocumentId' => $leUserPourMP['registration_id']];
                $infosKycReg = json_decode(mangopay_viewKycDocument($api, $datas_for_mangopay));
                //REG PROOF
                if ($infosKycReg->Type == 'REGISTRATION_PROOF')
                {

                    $statusRegistrationProof = $infosKycReg->Status;
                    if ($statusRegistrationProof == 'REFUSED')
                    {
                        $proofs['REGISTRATION_PROOF']['current_item']['class'] 		= 'alert-danger';
        				$proofs['REGISTRATION_PROOF']['current_item']['message'] 	= $infosKycReg->RefuseReasonType." ".$infosKycReg->RefuseReasonMessage;
                    }
                    else if ($statusRegistrationProof == 'VALIDATION_ASKED')
                    {
                        $proofs['REGISTRATION_PROOF']['current_item']['class'] 		= 'alert-info';
        				$proofs['REGISTRATION_PROOF']['current_item']['message'] 	= 'En cours de validation';
        				$validation_asked = true;
                    }
                    else if($statusRegistrationProof == 'CREATED')
                    {
                        $proofs['REGISTRATION_PROOF']['current_item']['class'] 		= 'alert-warning';
        				$proofs['REGISTRATION_PROOF']['current_item']['message'] 	= 'Vous devez valider les frais de dossier MangoPay.';
        				$register_created = true;
                    }
                }
                
            }
            else
            {
                $proofs['REGISTRATION_PROOF']['current_item']['class'] 		= 'alert-danger';
				$proofs['REGISTRATION_PROOF']['current_item']['message'] 	= 'Vous n\'avez transmis aucune preuve d\'identification de votre société';
            }

            if (!empty($leUserPourMP['articleofasso_id']))
            {

                $datas_for_mangopay = ['UserId' => $leUserPourMP['bank_id'], 'KycDocumentId' => $leUserPourMP['articleofasso_id']];
                //var_dump($datas_for_mangopay);
                $infosKycAsso = json_decode(mangopay_viewKycDocument($api, $datas_for_mangopay));

                //REG PROOF
                if ($infosKycAsso->Type == 'ARTICLES_OF_ASSOCIATION')
                {

                    $statusAssoProof = $infosKycAsso->Status;
                    if ($statusAssoProof == 'REFUSED')
                    {
                        $proofs['ARTICLES_OF_ASSOCIATION']['current_item']['class'] 		= 'alert-danger';
						$proofs['ARTICLES_OF_ASSOCIATION']['current_item']['message'] 		= $infosKycAsso->RefuseReasonType." ".$infosKycAsso->RefuseReasonMessage;
                    }
                    else if ($statusAssoProof == 'VALIDATION_ASKED')
                    {
                        $proofs['ARTICLES_OF_ASSOCIATION']['current_item']['class'] 		= 'alert-info';
						$proofs['ARTICLES_OF_ASSOCIATION']['current_item']['message'] 		= 'En cours de validation';
						$validation_asked = true;
                    }
                    else if($statusAssoProof == 'CREATED')
                    {
                        $proofs['ARTICLES_OF_ASSOCIATION']['current_item']['class'] 		= 'alert-warning';
						$proofs['ARTICLES_OF_ASSOCIATION']['current_item']['message'] 		= 'Vous devez valider les frais de dossier MangoPay.';
						$aoa_created = true;
                    }
                }
            }
            else
            {
                $proofs['ARTICLES_OF_ASSOCIATION']['current_item']['class'] 		= 'alert-danger';
				$proofs['ARTICLES_OF_ASSOCIATION']['current_item']['message'] 		= 'Vous n\'avez transmis aucune preuve d\'identification de votre société';
            } 
            if(!empty($leUserPourMP['bank_id']))
            {
                $UserMangopay = json_decode(mangoPay_getUser($api, ['UserId' => $leUserPourMP['bank_id']]));

                if(!empty($leUserPourMP['ubo_id']))
                {
                    $UserUbo = mangoPay_getListUbosFromUser($api, ['UserId' => $leUserPourMP['bank_id'], 'UboDeclarationId' => $leUserPourMP['ubo_id']]);
                }
                if(isset($UserUbo)&&!empty($UserUbo))
                {
                	if($UserUbo->Status == 'REFUSED')
                	{
                		$proofs['UBO']['current_item']['class'] 	= 'alert-danger';
                        $proofs['UBO']['current_item']['message'] 	= $UserUbo->RefuseReasonType." ".$UserUbo->RefuseReasonMessage;
                	}
                    else if($UserUbo->Status == "VALIDATION_ASKED")
                    {
                        $proofs['UBO']['current_item']['class'] 	= 'alert-info';
                        $proofs['UBO']['current_item']['message'] 	= 'Votre document sera bientot traité. Durant cette période, vous ne pouvez pas modifier votre document.';
                        $validation_asked = true;
                    }
                    else if($UserUbo->Status == "CREATED" && count((array)$UserUbo->Ubos) == 0)
                    {
                    	$proofs['UBO']['current_item']['class'] 	= 'alert-warning';
                        $proofs['UBO']['current_item']['message'] 	= 'Vous devez remplir au moins un UBO.';
                    }
                    else if($UserUbo->Status == "CREATED")
                    {
                    	$proofs['UBO']['current_item']['class'] 	= 'alert-warning';
                        $proofs['UBO']['current_item']['message'] 	= 'Vous devez valider les frais de dossier MangoPay.';
                        $ubo_created = true;
                    }
                }
                else
                {
                	$proofs['UBO']['current_item']['class'] 	= 'alert-danger';
                    $proofs['UBO']['current_item']['message'] 	= 'Vous n\'avez effectué aucune demande d\'UBO';
                }
            }

            if($leUserPourMP['bank_id'])
            {
            	$getProofSuccess = json_decode(mangopay_getKycDocumentsForAUser($api, [
	        		'UserId' => $leUserPourMP['bank_id'],
	        		'Status' => 'VALIDATED'
	        	]));

	        	foreach($getProofSuccess as $successProof)
	        	{
	        		if(isset($proofs[$successProof->Type]))
	        		{
	        			$proofs[$successProof->Type]['valid_item']['class'] 	= 'alert-success';
	        			$proofs[$successProof->Type]['valid_item']['message'] 	= 'Vous possédez un document validé par MangoPay';
	        		}
	        	}

	        	$getAllUboFromUser = mangoPay_getAllUboFromUser($api, [
	        		'UserId' => $leUserPourMP['bank_id']
	        	]);

	        	foreach($getAllUboFromUser as $uboDeclarations)
	        	{
	        		if($uboDeclarations->Status == 'VALIDATED')
	        		{
	        			$proofs['UBO']['valid_item']['class'] 		= 'alert-success';
	        			$proofs['UBO']['valid_item']['message'] 	= 'Vous possédez un document validé par MangoPay';
	        		}
	        	}
            }

            ?>

            <h2 style="margin-bottom: 20px;" >Paramètres pour les paiements</h2>    

            <div style="margin-top: 20px;" >

                <div class="form-group">
                    <div class="col-sm-12" style=' text-align: left;'>
                        <label>
                            <?php 
                            if ($compte_valide == "oui")
                            { 
                                ?> 
                                <span class="label label-success" >Compte activé</span> 
                                <?php
                            }
                            else
                            { 
                                ?> 
                                <span class="label label-danger" >Compte non activé</span> 
                                <?php
                            }
                            if($UserMangopay->KYCLevel == "REGULAR")
                            {
                                ?> 
                                <span class="label label-success" >KYC Validé</span> 
                                <?php
                            } 
                            else
                            {
                                ?> 
                                <span class="label label-danger" >KYC non validé</span> 
                                <?php
                            }
                            ?>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-12" style='margin-bottom: 15px; text-align: center;'>
                    	<?php 
                    	$canPay = false;
                    	if($UserMangopay->LegalPersonType == 'BUSINESS' && $ubo_created && $register_created && $id_created && $aoa_created)
                    	{
                    		$canPay = true;
                    	}
                    	else if($UserMangopay->LegalPersonType == 'SOLETRADER' && $id_created && $register_created)
                    	{
                    		$canPay = true;
                    	}
                    	if($canPay)
                    	{

                    		?>
                    		<p class="alert alert-info">Attention, une fois que vous aurez demandé à validé vos documents vous ne pourrez plus les modifiers jusqu'à qu'ils soient vérifier.</p>
                    		<div class="row" style="margin-bottom:30px; width:50%; position:relative; margin-left:auto; margin-right:auto;">
                    			<?php 
                    			$sql = $bdd->query("SELECT promo_for_all FROM configurations_preferences_generales");
					            $general_preference = $sql->fetch(PDO::FETCH_ASSOC);
					            $sql->closeCursor();

					            if($general_preference['promo_for_all'])
					            {
					            	?>
					            	<!-- mettre ici le code promo -->
					            	<div class="col-md-6">
					            		<input type="text" id="code_promo" class="form-control" style="border: 1px solid #ccc;">
					            	</div>
					            	<div class="col-md-6">
					            		<button type="button" class="btn btn-success" onclick="return false;" id="utiliser_code_promo">CODE PROMO</button>
					            	</div>
					            	<?php
					            }
                    			?>
                    			<!-- code promo -->
                    		</div>
                    		<button type='button' id='payer_frais_kyc_mangopay' class='btn btn-success' onclick="return false;" ><?= $general_preference['promo_for_all'] ? 'PAYER LES FRAIS DE VALIDATION DES KYC MANGOPAY' : 'DEMANDER LA VALIDATION DES KYC MANGOPAY' ?></button><?php
                    	}
                    	?>
                    </div>
                </div>

                <div style='clear: both; margin-bottom: 20px;' > </div>

                <div class="form-group">

                    <div class="col-sm-12" style=' text-align: left;'>
                        <?php
                        if ($compte_valide == "oui")
                        {
                            ?>
                            <div class="alert alert-warning" style="text-align: left;" >
                                <span class="uk-icon-warning" ></span> Afin de continuer à recevoir des paiements, pensez à bien mettre à jour vos informations de paiement ci-dessous, si elles ont changées.
                            </div>
                            <?php
                        }
                        elseif ($compte_valide == "" || $compte_valide == "non")
                        {
                            ?>
                            <div class="alert alert-danger" style="text-align: left;" >
                                <span class="uk-icon-warning" ></span> Afin de recevoir des paiements, vous devez indiquer toutes les informations ci-dessous afin de vous créer un Wallet chez notre partenaire financier Mangopay.
                            </div>
                           <?php
                        } 
                        ?>
                    </div>
                </div>
                
                <div style='clear: both; margin-bottom: 20px;' > </div>

                <div class="row">
     
                    <div class="col-sm-6" style='margin-bottom: 15px; text-align: left;'>

                        <div class="col-sm-12">
                            <label>*Carte d'identité (CNI)</label>
                            <?php 
                            if(!empty($proofs['IDENTITY_PROOF']['valid_item']['message']))
                            {
                            	?><p class="alert <?= $proofs['IDENTITY_PROOF']['valid_item']['class'] ?>"><?= $proofs['IDENTITY_PROOF']['valid_item']['message'] ?></p><?php
                            }
                            ?>

                            <?php
                            if(!empty($proofs['IDENTITY_PROOF']['current_item']['message']))
                            {
                            	?><p class="alert <?= $proofs['IDENTITY_PROOF']['current_item']['class'] ?>">Statut de votre document : <?= $proofs['IDENTITY_PROOF']['current_item']['message'] ?></p><?php
                            }
                            ?>

                            <input type="file" multiple="multiple" class="form-control inputdrop" id="cni" name="cni[]" accept="image/*, application/pdf" data-count="2" style="width: 100%;" />
                                (Formats autorisés : jpeg/png/pdf)
                        </div>
     
                        

                        <div class="col-sm-12" style="margin-top: 30px">

                            <label>*IBAN</label>
                            <input type='text' class="form-control" id='iban' placeholder='ex: FR76000120000778979145874' name='iban' value="<?php echo $leUserPourMP['iban']; ?>" style='width: 100%;' maxlength="27" />
                        </div>

                    </div>
                    
                    <div class="form-group col-sm-6">

                        <div class="col-sm-12" style='margin-bottom: 15px; text-align: left;  padding-left: 0px;'>
                            <label>*Type de société</label>
                             <select id="type_KYC" name="type_KYC" class="form-control" style="width: 100%;" >
                                <option value="" > Choisissez un type </option>
                                <option value="EI" <?php if ($type_KYC == "EI"){ ?> selected <?php } ?> > EI </option>
                                <option value="Auto entrepreneur" <?php if ($type_KYC == "Auto entrepreneur"){ ?> selected <?php } ?> > Auto entrepreneur </option>
                                <option value="EURL" <?php if ($type_KYC == "EURL"){ ?> selected <?php } ?> > EURL </option>
                                <option value="SA" <?php if ($type_KYC == "SNC"){ ?> selected <?php } ?> > SNC </option>
                                <option value="SA" <?php if ($type_KYC == "SA"){ ?> selected <?php } ?> > SA </option>
                                <option value="SASU" <?php if ($type_KYC == "SASU"){ ?> selected <?php } ?> > SASU </option>
                                <option value="SAS" <?php if ($type_KYC == "SAS"){ ?> selected <?php } ?> > SAS </option>
                                <option value="SARL" <?php if ($type_KYC == "SARL"){ ?> selected <?php } ?> > SARL </option>
                            </select>
                        </div>

                        <br>
                        
                        <div class="col-sm-12">
                            <label>*Kbis</label>
        					<?php
                            if(!empty($proofs['REGISTRATION_PROOF']['valid_item']['message']))
                            {
                            	?><p class="alert <?= $proofs['REGISTRATION_PROOF']['valid_item']['class'] ?>"><?= $proofs['REGISTRATION_PROOF']['valid_item']['message'] ?></p><?php
                            }
                            ?>

                            <?php
                            if(!empty($proofs['REGISTRATION_PROOF']['current_item']['message']))
                            {
                            	?><p class="alert <?= $proofs['REGISTRATION_PROOF']['current_item']['class'] ?>">Statut de votre document : <?= $proofs['REGISTRATION_PROOF']['current_item']['message'] ?></p><?php
                            }
                            ?>
                            <input type="file" multiple="multiple" class="form-control inputdrop" id="kbis" name="kbis[]" accept="image/*, application/pdf" data-count="2" style="width: 100%;" />
                                    (Formats autorisés : jpeg/png/pdf)
                        </div>

                        <div class="col-sm-12 Statut_a_jour_signe" style='margin-top: 30px; margin-bottom: 15px; text-align: left; display: none;'>
                            <label>*Statuts société complets, à jour, datés signés</label>
                            <?php
                            if(!empty($proofs['ARTICLES_OF_ASSOCIATION']['valid_item']['message']))
                            {
                            	?><p class="alert <?= $proofs['ARTICLES_OF_ASSOCIATION']['valid_item']['class'] ?>"><?= $proofs['ARTICLES_OF_ASSOCIATION']['valid_item']['message'] ?></p><?php
                            }
                            ?>

                            <?php
                            if(!empty($proofs['ARTICLES_OF_ASSOCIATION']['current_item']['message']))
                            {
                            	?><p class="alert <?= $proofs['ARTICLES_OF_ASSOCIATION']['current_item']['class'] ?>">Statut de votre document : <?= $proofs['ARTICLES_OF_ASSOCIATION']['current_item']['message'] ?></p><?php
                            }
                            ?>

                            <input type='file' multiple="multiple" class="form-control inputdrop" id='Statut_a_jour_signe' name='Statut_a_jour_signe[]' data-count="2" accept="image/*, application/pdf" value="" style='width: 100%;' />
                             (Formats autorisés : jpeg/png/pdf)
                        </div>

                        

                    </div>

                    <div class="col-sm-12 UBO_declaration Statut_a_jour_signe" style='margin-top: 30px; margin-bottom: 15px; text-align: left; display: none;'>
                        
                        <div class="alert alert-info">
                            Le UBO est document demandé par Mangopay pour toutes personnes de type Business. Ce sont des informations supplémentaires envoyées à la banque, nous ne conservons pas ces informations.
                        </div>

                        <label>*Déclaration UBO validé</label>
    					
    					<?php
                        if(!empty($proofs['UBO']['valid_item']['message']))
                        {
                        	?><p class="alert <?= $proofs['UBO']['valid_item']['class'] ?>"><?= $proofs['UBO']['valid_item']['message'] ?></p><?php
                        }
                        ?>

                        <?php
                        if(!empty($proofs['UBO']['current_item']['message']))
                        {
                        	?><p class="alert <?= $proofs['UBO']['current_item']['class'] ?>">Statut de votre document : <?= $proofs['UBO']['current_item']['message'] ?></p><?php
                        }
                        ?>

                        <script>
                            function isBissextile(annee)
                            {
                                if ((annee%4==0) && ((annee%100!=0) || (annee%400==0))) return true;
                                else return false;
                            }

                            function verifDate(day, month, year){
                                // D'abord on vérifie si c'est Février
                                if(month.val() == 2)
                                {
                                    if(isBissextile(year.val()))
                                    {
                                        updateDate(29, day)
                                    }
                                    else
                                    {
                                        updateDate(28, day)
                                    }
                                }
                                else
                                {
                                    if(month.val() == 1 || month.val() == 3 || month.val() == 5 || month.val() == 7 || month.val() == 8 || month.val() == 10 || month.val() == 12)
                                    {
                                       updateDate(31, day) 
                                    }
                                    else
                                    {
                                        updateDate(30, day)
                                    }
                                }

                            }

                            function updateDate(nb, day){
                                let length = day.children().length
                                let index = nb - length
                                if(index < 0)
                                {
                                    day.children().slice(index).remove()
                                }
                                else
                                {
                                    let last = day.children().last().val()
                                    let options = ''
                                    while(last<nb)
                                    {
                                        last++
                                        options += '<option value="'+last+'">'+last+'</option>'
                                    }
                                    day.append(options)
                                }
                            }

                        </script>

                        <div class="row" id="UBO">
                            <?php 
                            $json = file_get_contents('https://restcountries.eu/rest/v2/all?fields=name;alpha2Code');
                			$countryCodeAPIJson = json_decode($json);
                            if($UserUbo && $UserUbo->Status != "VALIDATED")
                            {
                                foreach($UserUbo->Ubos as $key => $userUbo)
                                {
                                    include('panel/Profil/Modifier-profil-ubo-list.php');
                                }
                            }
                            if(!$UserUbo || count($UserUbo->Ubos) < 4 )
                            {
                                ?>
                                <div class="col-md-6">
                                    <span class="text-center form-control btn" id="addUbos" style="border: 1px solid #ccc;margin:auto;">Ajoutez un nouvel UBO</span>
                                </div>
                                <?php
                            }
                            ?>
                        </div>

                        <script>
                            $(document).ready(function() {

                            	function addHtml(len) {
                                    let html = `
                                        <fieldset class="col-md-6 ubos">
                                            <legend>UBO `+(len+1)+`</legend>
                                            <hr size="30">
                                            <!-- firstname - lastname -->
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="formUboFirstName">Prénom</label>
                                                    <input required type="text" class="form-control" id="formUboFirstName" name="ubo[`+len+`][FirstName]" placeholder="John" style="border: 1px solid #ccc;">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="formUboLastName">Nom</label>
                                                    <input required type="text" class="form-control" id="formUboLastName" name="ubo[`+len+`][LastName]" placeholder="Snow" style="border: 1px solid #ccc;">
                                                </div>
                                            </div>

                                            <!-- Adresse -->
                                            <div class="form-row">
                                                <hr size="30">
                                                <!-- Adresse line 1 -->
                                                <div class="form-group col-md-6">
                                                    <label for="formUboAddressLine1">Addresse ligne 1</label>
                                                    <input required type="text" class="form-control" id="formUboAddressLine1" name="ubo[`+len+`][Address][AddressLine1]" placeholder="exemple : 1 Mangopay Street" style="border: 1px solid #ccc;">
                                                </div>

                                                <!-- Adresse line 2 -->
                                                <div class="form-group col-md-6">
                                                    <label for="formUboAddressLine2">Addresse ligne 2</label>
                                                    <input required type="text" class="form-control" id="formUboAddressLine2" name="ubo[`+len+`][Address][AddressLine2]" placeholder="exemple : The loop" style="border: 1px solid #ccc;">
                                                </div>

                                                <!-- city -->
                                                <div class="form-group col-md-6">
                                                    <label for="formUboVille">Ville</label>
                                                    <input required type="text" class="form-control" id="formUboVille" name="ubo[`+len+`][Address][City]" placeholder="exemple : Paris" style="border: 1px solid #ccc;">
                                                </div>

                                                <!-- region -->
                                                <div class="form-group col-md-6">
                                                    <label for="formUboRegion">Région</label>
                                                    <input required type="text" class="form-control" id="formUboRegion" name="ubo[`+len+`][Address][Region]" placeholder="exemple : Ile de France" style="border: 1px solid #ccc;">
                                                </div>

                                                <!-- postalcode -->
                                                <div class="form-group col-md-6">
                                                    <label for="formUboVille">Code postal</label>
                                                    <input required type="text" class="form-control" id="formUboVille" name="ubo[`+len+`][Address][PostalCode]" placeholder="exemple : 750101" style="border: 1px solid #ccc;">
                                                </div>

                                                <!-- country code -->
                                                <div class="form-group col-md-3">
                                                    <label for="formUboCountryCode">Pays</label>
                                                    <select required id="formUboCountryCode" class="form-control" name="ubo[`+len+`][Address][CountryCode]">
                                                        <?php 
                                                        foreach($countryCodeAPIJson as $code)
                                                        {
                                                            ?>
                                                            <option <?php if($code->alpha2Code == 'FR'){echo 'selected';} ?> value="<?= $code->alpha2Code ?>"><?= $code->name ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- nationality -->
                                            <div class="form-group col-md-3">
                                                <label for="formUboNationality">Nationalité</label>
                                                <select required id="formUboNationality" class="form-control" name="ubo[`+len+`][Nationality]">
                                                    <?php
                                                    foreach($countryCodeAPIJson as $code)
                                                    {
                                                        ?>
                                                        <option <?php if($code->alpha2Code == 'FR'){echo 'selected';} ?> value="<?= $code->alpha2Code ?>"><?= $code->name ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>

                                            <hr size="30">
                                            <!-- birthplace -->
                                            <div class="form-row">
                                                
                                                <!-- birthplacecity -->
                                                <div class="form-group">
                                                    <div class="form-group col-md-6">
                                                        <label for="formUboBirthplaceCity">Lieu de naissance</label>
                                                        <input required type="text" class="form-control" id="formUboBirthplaceCity" name="ubo[`+len+`][Birthplace][City]" style="border: 1px solid #ccc;">
                                                    </div>
                                                </div>

                                                <?php 
                                                $now        = new DateTime('NOW');
                                                $birthdate  = new DateTime();
                                                $birthdate->setTimestamp($userUbo->Birthday);
                                                ?>

                                                <!-- birthday -->
                                                <div class="form-group"> <!-- (timestamp 00h - 23h) -->
                                                    <div class="form-group col-md-3">
                                                        <label for="formUboBirthday">Date de naissance</label>
                                                        <div class="form-row">
                                                            <!-- Jour -->
                                                            <div class="form-group">
                                                                <select required id="BirthdayDay`+len+`" name="ubo[`+len+`][Birthday][Day]" class="col-md-4 form-control">
                                                                    <?php 
                                                                    for($i=1;$i<=31;$i++)
                                                                    {
                                                                        ?>
                                                                        <option
                                                                            value="<?= $i ?>"
                                                                        >
                                                                            <?= $i ?>
                                                                        </option>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <!-- Mois -->
                                                            <div class="form-group">
                                                                <select required id="BirthdayMonth`+len+`" name="ubo[`+len+`][Birthday][Month]" class="col-md-4 form-control">
                                                                    <?php 
                                                                    $months = [
                                                                        'Janvier', 
                                                                        'Février',
                                                                        'Mars',
                                                                        'Avril',
                                                                        'Mai',
                                                                        'Juin',
                                                                        'Juillet',
                                                                        'Août',
                                                                        'Septembre',
                                                                        'Octobre',
                                                                        'Novembre',
                                                                        'Décembre'];
                                                                    foreach($months as $keyMonth => $month)
                                                                    {
                                                                        $keyMonth++;
                                                                        ?>
                                                                        <option 
                                                                            value="<?= $keyMonth ?>">
                                                                            <?= $month ?>
                                                                        </option>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <!-- Année -->
                                                            <div class="form-group">
                                                                <select required id="BirthdayYear`+len+`" name="ubo[`+len+`][Birthday][Year]"  class="col-md-4 form-control">
                                                                    <?php 
                                                                    for($i=0;$i<100;$i++)
                                                                    {
                                                                        ?>
                                                                        <option 
                                                                            value="<?= $now->format('Y') ?>">
                                                                                <?= $now->format('Y') ?>
                                                                        </option>
                                                                        <?php
                                                                        $now->modify('-1 year');
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- birthplacecountry -->
                                                <div class="form-group">
                                                    <div class="form-group col-md-3">
                                                        <label for="formUboBirthplaceCountry">Pays de naissance</label>
                                                        <select id="formUboBirthplaceCountry" class="form-control" name="ubo[`+len+`][Birthplace][Country]">
                                                            <?php
                                                            foreach($countryCodeAPIJson as $code)
                                                            {
                                                                ?>
                                                                <option <?php if($code->alpha2Code == 'FR'){echo 'selected';} ?> value="<?= $code->alpha2Code ?>"><?= $code->name ?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>
                                        </fieldset>
                                    `
                                    let ubo = $('#UBO')
                                    if(len < 3)
                                    {
                                        ubo.children().last().before(html)
                                    }
                                    else if(len == 3)
                                    {
                                        ubo.children().last().remove()
                                        ubo.append(html)
                                    }
                                    let day     = $('#BirthdayDay'+len)
                                    let month   = $('#BirthdayMonth'+len)
                                    let year    = $('#BirthdayYear'+len)

                                    day.change(function() {
                                        verifDate(day, month, year)
                                    })
                                    month.change(function() {
                                        verifDate(day, month, year)
                                    })
                                    year.change(function() {
                                        verifDate(day, month, year)
                                    })

                                    verifDate(day, month, year)
                            	}

                                $('#addUbos').click(function() {
                                    let len = $('.ubos').length

                                    if(len < 4) 
                                    {
                                        addHtml(len)
                                    }
                                })

                                let len = $('.ubos').length
                                if(len == 0)
                                {
                                	addHtml(0)
                                }

                            })

                        </script>
                    </div>
                    
                </div>


                <?php /*Pas besoin
                <div class="col-sm-6" style='margin-bottom: 15px; text-align: left;'>
                    <label>*Justificatif de domicile</label>
                    <input type='file' multiple class="form-control inputdrop" id='justificatif_domicile' accept='image/*, application/pdf' name='justificatif_domicile' value="" style='width: 100%;' />
            (Formats autorisés : jpeg/png/pdf)
                </div>
            */ ?>

                <div style='clear: both;' > </div>

                <?php /*
                <div class="col-sm-6 Statut_a_jour_signe" style='margin-bottom: 15px; text-align: left; display: none;'>
                    <label>*Statuts société complets, à jour, datés signés</label>
                    <input type='file' class="form-control" id='Statut_a_jour_signe' name='Statut_a_jour_signe' value="" style='width: 100%;' />
               (Formats autorisés : jpeg/png/pdf)
                </div>
            */ ?>
                <div style='clear: both; margin-bottom: 20px;' > </div>

                <div class="form-group" >
                    <div class="col-sm-12">
                        "Les données collectées par 123Vitrine sont nécessaires pour compléter votre profil. Vous disposez d'un droit d'accès, de rectification, d'opposition, de limitation du traitement, de suppression, de portabilité. 
                        Pour plus d'informations consultez notre <a href="/Traitements-de-mes-donnees">politique de confidentialité</a>"
                    </div>
                    <div class="col-sm-12">
                        <br>
                        "Conditions générales d'utilisation de <a href="https://www.mangopay.com/wp-content/uploads/CGU-EP-FR.pdf">MANGOPAY</a>"
                    </div>
                </div>
                <div style='clear: both; margin-bottom: 20px;' > </div>

                <div class="form-group">
                    <div class="col-sm-12" style='margin-bottom: 15px; text-align: center;'>
                        <div id="loader" style="display:none;"><div class="lds-ring"><div></div><div></div><div></div><div></div></div></div>
                        <button <?php if($validation_asked){echo 'disabled';} ?> type='button' id='mise_ajour_profil_paiement' class='btn btn-success' onclick="return false;" >METTRE A JOUR</button>
                    </div>
                </div>
            </div>
        </form>

    <?php
    }
}
else
{
    header('location: /index.html');
}
?>
