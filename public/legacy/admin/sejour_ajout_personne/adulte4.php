
<div class="col-sm-12" id="form_adulte_4" style="display:none">
<?php



$rr=4;

?>
    <a href="javascript:void(0)" id="tab-adulte_<?php echo $rr; ?>" style="width: 100%;"><p class="tab-adulte" ><i class="fa fa-plus"></i>&nbsp;&nbsp;Participants au voyage: &nbsp;<span style="color: white;text-transform: uppercase;background: #9F9191;font-size: 10px;padding: 1px 10px;width: 76px;display: inline-block;text-align: center;">Adulte <?php echo $rr; ?></span></p></a>
    <a href="javascript:void(0)" id="tab-adulte_<?php echo $rr; ?>_off" style="display: none;width: 100%;"><p class="tab-adulte" style="background: #FF0707;color: #FFF;"><i class="fa fa-angle-down"></i>&nbsp;&nbsp;Participants au voyage: &nbsp;<span style="color: white;text-transform: uppercase;background: #9F9191;font-size: 10px;padding: 1px 10px;width: 76px;display: inline-block;text-align: center;">Adulte <?php echo $rr; ?></span></p></a>


        <div class="col-sm-12" id="tab-adulte_<?php echo $rr; ?>-aff" style="border-left: 2px solid #FF00B3; display: none">
            <div class="col-sm-1">
                <p>&nbsp;</p>
            </div>


                                <!-- Voyage -->
            <div class="col-sm-12">

                    <div class="row">
                                <div class="col-sm-6">


                                        <label for="exampleInputName2">Nationalité</label>
                                        
                                            <div class="input-group select_custom">
                                                <select class="form-control" style="z-index: 0;" name="nationalite_participant_<?php echo $rr; ?>" id="">
                                                    <option value="Suisse" class="others" selected>Suisse</option>
                                                    <option value="France" class="others" >France</option>
                                                    <option value="Espagne" class="others" >Espagne</option>
                                                    <option value="Portugal" class="others" >Portugal</option>
                                                        <?php
                                                          $stmt = $conn->prepare('SELECT * FROM pays ORDER BY nom_fr_fr ASC');
                                                          $stmt ->execute();
                                                          while($account = $stmt ->fetch(PDO::FETCH_OBJ))
                                                          {
                                                              ?>  


                                                                <option value="<?php echo $account -> nom_fr_fr; ?>" 
                                                                  <?php 

                                                                    $nationalite_participant = 'nationalite_participant_'.$rr;
                                                                    if (isset($_POST[$nationalite_participant]))
                                                                    {
                                                                      if($_POST[$nationalite_participant]==$account -> nom_fr_fr)
                                                                      {
                                                                        echo 'selected';
                                                                      }                                                                      
                                                                    } 
                                                                  ?>
                                                                  ><?php echo $account -> nom_fr_fr; ?>
                                                                </option>
                                                        <?php
                                                          }
                                                        ?>
                                                </select>
                                            </div>
                            
                                    
                                </div>
                                <div class="col-sm-6">
                                    
                                        <label for="exampleInputName2">Titre *</label>
                                        
                                            <select class="form-control" name="titre_participant_<?php echo $rr; ?>" id="">
                                                    <option value="Mr" class="others"                                                                   
                                                    <?php 
                                                    $titre_participant = 'titre_participant_'.$rr;
                                                        if (isset($_POST[$titre_participant]))
                                                          {
                                                            if($_POST[$titre_participant]=="0")
                                                              {
                                                                echo 'selected';
                                                              }                                                                      
                                                          } 
                                                      ?>
                                                                  >Sélectionnez</option>           
                                                    <option value="Mr" class="others"
                                                      <?php 
                                                        if (isset($_POST[$titre_participant]))
                                                          {
                                                            if($_POST[$titre_participant]=="Mr")
                                                              {
                                                                echo 'selected';
                                                              }                                                                      
                                                          } 
                                                      ?>
                                                    selected >Mr</option>
                                                    <option value="Mme" class="others"
                                                      <?php 
                                                        if (isset($_POST[$titre_participant]))
                                                          {
                                                            if($_POST[$titre_participant]=="Mme")
                                                              {
                                                                echo 'selected';
                                                              }                                                                      
                                                          } 
                                                      ?>
                                                    >Mme</option>
                                                    
                                            </select>
                                        </div>


                                        <div class="col-sm-6"><br>
                                            <div class="form-group form_group_line">
                                                <label for="exampleInputName2">Nom *</label>
                                                <input type="text" class="form-control" id="exampleInputName2" name="nom_participant_<?php echo $rr; ?>" value="<?php $nom_participant = 'nom_participant_'.$rr; if (isset($_POST[$nom_participant])){echo $_POST[$nom_participant];} ?>"  OnKeyUp="javascript:nomadulte<?php echo $rr; ?>(this.value);"  >
                                            </div>

                                        </div>

                                        <div class="col-sm-6"><br>

                                            <div class="form-group form_group_line">
                                                <label for="exampleInputEmail2">Prénom *</label>
                                                <input type="text" class="form-control" id="exampleInputEmail2" name="prenom_participant_<?php echo $rr; ?>" value="<?php $prenom_participant = 'prenom_participant_'.$rr; if (isset($_POST[$prenom_participant])){echo $_POST[$prenom_participant];} ?>" OnKeyUp="javascript:prenomadulte<?php echo $rr; ?>(this.value);" >
                                            </div>
                                        </div>




                                   







<script type="text/javascript">
function nomadulte1(chiffre1)
{
    document.getElementById('nomcord').value = chiffre1;

}
function prenomadulte1(chiffre1)
{
    document.getElementById('prenomcord').value = chiffre1;

}
</script>

<div class="col-sm-12">
    <a href="javascript:void(0)" id=""><h4 style="font-size: 14px;font-weight: 1000;background: #09DCFF;padding: 10px;color: #FFF;margin-bottom: 0px;border-bottom: 4px solid #13C0DD;"><i class="fa fa-eye"></i>  Voir nos offres assurances pour votre voyage </h4></a>
</div>


<div class="col-sm-12" style="margin-bottom: 40px;font-size: 12px;">


    <div class="col-sm-12" style="padding: 20px 40px;background: #FBFBFB;border-bottom: 4px solid #13C0DD;">
    <div class="row">

    <?php

    $assurance = 'assurance_'.$rr;
    $total_adulte4_new = 0;

    if($rr == 1)
    {
      $total_adulte = $total_adulte1_new;
    }
    if($rr == 2)
    {
      $total_adulte = $total_adulte2_new;
    }
    if($rr == 3)
    {
      $total_adulte = $total_adulte3_new;
    }
    if($rr == 4)
    {
      $total_adulte = $total_adulte4_new;
    }



    ?>
  
                                
                                <div class="form-group form_check col-sm-9"  style="padding: 0px;margin: 0px;">
                                <input type="radio" name="assurance_<?php echo $rr; ?>"  onchange="radio<?php echo $rr; ?>adulte(this)" value="0" <?php  if (isset($_POST[$assurance])){ if($_POST[$assurance]=="0"){echo 'checked';}}else{ echo 'checked'; }?> >&nbsp;&nbsp;Non, je ne désire pas souscrire à une assurance de voyage
                                </div>
                                <div class="form-group form_check col-sm-2" style="padding: 0px;margin: 0px;text-align: left;">                            
                                </div>
                                
                                <div class="form-group form_check col-sm-9" style="padding: 0px;margin: 0px;text-align: right;">
                                  <span style="font-weight: bold;color: #EA1F2E;"></span>
                                </div>



<?php

$id_assurance='';
$stmt34 = $conn->prepare('SELECT * FROM assurance WHERE id_assurance !=:id_assurance ORDER BY prix_assurance');
$stmt34 ->bindValue('id_assurance', $id_assurance);
$stmt34 ->execute();
while($account34 = $stmt34 ->fetch(PDO::FETCH_OBJ))
    {

                if($account34 -> prix_assurance==0)
                {
                    $prix_assurance = round(($total_adulte * $account34 -> pourcentage/100), 1);
                  //$prix_assurance = ($total_adulte * $account34 -> pourcentage/100);
                }
                else
                {
                    $prix_assurance = $account34 -> prix_assurance;
                }



                if($account34 -> info =="annuelle")
                  {
                      $prestation_ass = 'Assurance annulle';
                  }
                if($account34 -> info =="uniquement")
                  {
                      $prestation_ass =  'Pour le voyage uniquement';
                  }


?>

            <div class="form-group form_check col-sm-5" id="reservation_adulte_<?php echo $rr; ?>_<?php echo $account34 -> id_assurance; ?>" style="padding: 0px;margin: 0px;">
            <input type="radio" name="assurance_<?php echo $rr; ?>"  onchange="radio<?php echo $rr; ?>adulte(this)" value="<?php echo $prix_assurance.'-'.$account34 -> id_assurance.'-'.$account34 -> titre_assurance.'-'.$prestation_ass; ?>" 
            <?php 
                  $assurance = 'assurance_'.$rr;
                  $prix_assurance_rec = $prix_assurance.'-'.$account34 -> id_assurance.'-'.$account34 -> titre_assurance.'-'.$prestation_ass;
                  if (isset($_POST[$assurance]))
                    { 
                      if($_POST[$assurance]==$prix_assurance_rec)
                        {
                          echo 'checked';
                        } 
                    } 

            ?>

               >&nbsp;&nbsp;<?php echo $account34 -> titre_assurance; ?>
            </div>



            <div class="form-group form_check col-sm-4" style="padding: 0px;margin: 0px;text-align: left;">
                <?php
                    echo $prestation_ass;
                ?>


            </div>



            <div class="form-group form_check col-sm-2" style="padding: 0px;margin: 0px;text-align: left;">
                                  <?php echo $account34 -> par; ?>
            </div>
                                
            <div class="form-group form_check col-sm-1" style="padding: 0px;margin: 0px;text-align: right;">
                <span style="font-weight: bold;color: #EA1F2E;"><?php echo number_format($prix_assurance, 2, '.', ' '); ?> CHF</span>
            </div>


<?php

    }

?>

</div>
</div>

                </div>



            </div>
</div>




        </div>

    <script type="text/javascript">
        $("#tab-adulte_<?php echo $rr; ?>").click( function(){
            $("#tab-adulte_<?php echo $rr; ?>-aff").show(500);
            $("#tab-adulte_<?php echo $rr; ?>_off").show(500);
            $("#tab-adulte_<?php echo $rr; ?>").hide(500);
          });
        $("#tab-adulte_<?php echo $rr; ?>_off").click( function(){
            $("#tab-adulte_<?php echo $rr; ?>-aff").hide(500);
            $("#tab-adulte_<?php echo $rr; ?>_off").hide(500);
            $("#tab-adulte_<?php echo $rr; ?>").show(500);

          });
    </script>





</div>