
<div class="col-sm-12" id="form_enfant_2" style="display:none">

<?php

    $rr=2;

?>
    <a href="javascript:void(0)" id="tab-enfant_<?php echo $rr; ?>" style="width: 100%;"><p class="tab-adulte" ><i class="fa fa-plus"></i>&nbsp;&nbsp;Participants au voyage: &nbsp;<span style="color: white;text-transform: uppercase;background: #9F9191;font-size: 10px;padding: 1px 10px;width: 76px;display: inline-block;text-align: center;">enfant <?php echo $rr; ?></span></p></a>
    <a href="javascript:void(0)" id="tab-enfant_<?php echo $rr; ?>_off" style="display: none;width: 100%;"><p class="tab-adulte" style="background: #FF0707;color: #FFF;"><i class="fa fa-angle-down"></i>&nbsp;&nbsp;Participants au voyage: &nbsp;<span style="color: white;text-transform: uppercase;background: #9F9191;font-size: 10px;padding: 1px 10px;width: 76px;display: inline-block;text-align: center;">enfant <?php echo $rr; ?></span></p></a>


        <div class="col-sm-12" id="tab-enfant_<?php echo $rr; ?>-aff" style="border-left: 2px solid #FF00B3; display: none">
            <div class="col-sm-1">
                <p>&nbsp;</p>
            </div>


                                <!-- Voyage -->
            <div class="col-sm-12">

                    <div class="row">
                                <div class="col-sm-6">


                                        <label for="exampleInputName2">Nationalité</label>
                                        
                                            <div class="input-group select_custom">
                                                <select class="form-control" style="z-index: 0;" name="nationalite_participant_enfant_<?php echo $rr; ?>" id="">
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

                                                                    $nationalite_participant_enfant = 'nationalite_participant_enfant_'.$rr;
                                                                    if (isset($_POST[$nationalite_participant_enfant]))
                                                                    {
                                                                      if($_POST[$nationalite_participant_enfant]==$account -> nom_fr_fr)
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
                                        
                                            <select class="form-control" name="titre_participant_enfant_<?php echo $rr; ?>" id="">
                                                    <option value="Enfant" class="others"                                                                   
                                                    <?php 
                                                    $titre_participant_enfant = 'titre_participant_enfant_'.$rr;
                                                        if (isset($_POST[$titre_participant_enfant]))
                                                          {
                                                            if($_POST[$titre_participant_enfant]=="0")
                                                              {
                                                                echo 'selected';
                                                              }                                                                      
                                                          } 
                                                      ?>
                                                                  >Sélectionnez</option>           
                                                   
                                                    <option value="Enfant" class="others"
                                                      <?php 
                                                        if (isset($_POST[$titre_participant_enfant]))
                                                          {
                                                            if($_POST[$titre_participant_enfant]=="Enfant")
                                                              {
                                                                echo 'selected';
                                                              }                                                                      
                                                          } 
                                                      ?>
                                                    selected>Enfant</option> 
                                            </select>
                                        </div>


                                        <div class="col-sm-6"><br>
                                            <div class="form-group form_group_line">
                                                <label for="exampleInputName2">Nom *</label>
                                                <input type="text" class="form-control" id="exampleInputName2" name="nom_participant_enfant_<?php echo $rr; ?>" value="<?php $nom_participant_enfant = 'nom_participant_enfant_'.$rr; if (isset($_POST[$nom_participant_enfant])){echo $_POST[$nom_participant_enfant];} ?>"  OnKeyUp="javascript:nomenfant1(this.value);"  >
                                            </div>

                                        </div>

                                        <div class="col-sm-6"><br>

                                            <div class="form-group form_group_line">
                                                <label for="exampleInputEmail2">Prénom *</label>
                                                <input type="text" class="form-control" id="exampleInputEmail2" name="prenom_participant_enfant_<?php echo $rr; ?>" value="<?php $prenom_participant_enfant = 'prenom_participant_enfant_'.$rr; if (isset($_POST[$prenom_participant_enfant])){echo $_POST[$prenom_participant_enfant];} ?>" OnKeyUp="javascript:prenomenfant1(this.value);" >
                                            </div>
                                        </div>



 <script>
  $(function() {
    $( "#from_<?php echo $rr; ?>" ).datepicker({

      defaultDate: "-100w",
      changeMonth: true,
      changeYear: true,
      yearRange: '2010:2023',
      numberOfMonths: 1,

    });

  });
  </script>



<div class="col-sm-12">

  
                                         <div class="form-group tm-form-element tm-form-element-50">
                                            <label for="exampleInputEmail2">Date de naissance *</label>
                                            <input name="date_naissance_participant_enfant_<?php echo $rr; ?>" type="text" class="form-control" id="from_<?php echo $rr; ?>" value="<?php $date_naissance_participant_enfant = 'date_naissance_participant_enfant_'.$rr; if (isset($_POST[$date_naissance_participant_enfant])){echo $_POST[$date_naissance_participant_enfant];} else {echo '01 Janvier 2023';} ?>">






                                        </div>   <br>                               

</div>






            </div>
</div>




        </div>

    <script type="text/javascript">
        $("#tab-enfant_<?php echo $rr; ?>").click( function(){
            $("#tab-enfant_<?php echo $rr; ?>-aff").show(500);
            $("#tab-enfant_<?php echo $rr; ?>_off").show(500);
            $("#tab-enfant_<?php echo $rr; ?>").hide(500);
          });
        $("#tab-enfant_<?php echo $rr; ?>_off").click( function(){
            $("#tab-enfant_<?php echo $rr; ?>-aff").hide(500);
            $("#tab-enfant_<?php echo $rr; ?>_off").hide(500);
            $("#tab-enfant_<?php echo $rr; ?>").show(500);

          });
    </script>

</div>