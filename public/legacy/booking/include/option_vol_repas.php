<div class="col-sm-12" id="option_repas_vol_<?php echo $account5 -> idtxp; ?>" style="display : none;border-left: 10px solid red;">



                                                <div class="col-sm-12">
                                                    <div class="row_cust_bot_subtitle" style="text-align: left;color: rgb(0, 0, 0) !important;">
                                                        <br>
                                                       <span style="font-weight: bold"><i class="fa fa-bars"></i>&nbsp;&nbsp;  Option Repas sur les vols par personne en  <span style="color:red"> CHF</span></span>
                                                        <br/><br/>
                                                    </div>
                                                </div>






<div class="col-sm-12">

        <table class="article" style="width: 100%; margin-right: auto;">
            <tbody>
                <tr>
                    <td style="width: 13%; text-align: left; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;"><span style="font-weight: bold; color: #000000;">Coche</span></td>
                    <td style="width: 13%; text-align: left; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;"><span style="font-weight: bold; color: #000000;">Type</span></td>

                    <td style="width: 13%; text-align: left; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;"><span style="font-weight: bold; color: #000000;">Personne</span></td>                    
                    <td style="width: 13%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;"><span style="font-weight: bold; color: #000000;">Prix Unité</span></td>
                    <td style="width: 12%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;"><span style="font-weight: bold; color: #000000;">Nbr Perso</span></td>
                    <td style="width: 20%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;"><span style="font-weight: bold; color: #000000;">Sous Total</span></td>

                </tr>

<?php

    $stmt8 = $conn->prepare('SELECT * FROM option_vol_repas WHERE idtxp=:idtxp AND personne =:personne');
    $stmt8 ->bindValue('idtxp', $account5 -> idtxp);
    $stmt8 ->bindValue('personne', 'Adulte');
    $stmt8 ->execute();
    while($account8 = $stmt8 ->fetch(PDO::FETCH_OBJ))
    {

          if(isset($account8 -> id_option))
          {
                if($adulte!=0)
                {     
?>

                <tr>
                  <td style="width: 13%;  text-align: left; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                      <input type="checkbox" name="adulte1_option_repas" id="myCheck_adulte1_option_repas<?php echo $account8 -> id_option; ?>" onclick="myFunctionAdulteOptionRepas_<?php echo $account8 -> id_option; ?>()">
                  </td>

                  <td style="width: 13%; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;"><span style="color:#92a5ac;font-weight: 300;"><?php echo ($account8 -> titre); ?></span></td>

                  <td style="width: 13%; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;"><span style="color:#92a5ac;font-weight: 300;">Adulte</span></td>
                  <td style="width: 13%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;"><span style="color:#92a5ac;font-weight: 300;"><?php echo $adulte_option_vol_repas = round($account8 -> prix_total, 2); ?> CHF</span></td>
                  <td style="width: 12%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                      <span style="color:#92a5ac;font-weight: 300;"><?php echo $adulte; ?></span>
                  </td>
                  <td style="width: 12%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                       <span style="color:#92a5ac;font-weight: 300;"> <?php echo $adulte_option_vol_repas * $adulte; ?> CHF
                      </span>
                  </td>
                </tr>

<script>

document.getElementById("option_repas").value = <?php echo $adulte_option_vol_repas * $adulte; ?>;

function myFunctionAdulteOptionRepas_<?php echo $account8 -> id_option; ?>() {
  var checkboxRepas = document.getElementById("myCheck_adulte1_option_repas<?php echo $account8 -> id_option; ?>");
  if (checkboxRepas.checked == true)
  {

    
    var option_repas_value =document.getElementById("option_repas").value;
    var prix_vol_value =document.getElementById("prix_vol_input_<?php echo $account5 -> idtxp; ?>").value;
    var newprix1 = parseFloat(option_repas_value) + parseFloat(prix_vol_value);
    var newprix = (newprix1).toLocaleString(
      undefined, // leave undefined to use the visitor's browser 
                 // locale or a string like 'en-US' to override it.
      { minimumFractionDigits: 2 }
    );

    document.getElementById("prix_vol_id").innerHTML = newprix + "<span style='font-size: 12px;'> CHF</span>";
    document.getElementById("prix_vol_input_<?php echo $account5 -> idtxp; ?>").value = newprix1;
    document.getElementById("id_option_adulte_repas<?php echo $account5 -> idtxp; ?>").value = document.getElementById("id_option_adulte_repas<?php echo $account5 -> idtxp; ?>").value + '-' + <?php echo $account8 -> id_option; ?>;
    
  } else {
   
    var option_repas_value =document.getElementById("option_repas").value;
    var prix_vol_value =document.getElementById("prix_vol_input_<?php echo $account5 -> idtxp; ?>").value;

    var newprix1 = parseFloat(prix_vol_value) - parseFloat(option_repas_value);
    var newprix = (newprix1).toLocaleString(
      undefined, // leave undefined to use the visitor's browser 
                 // locale or a string like 'en-US' to override it.
      { minimumFractionDigits: 2 }
    );

    document.getElementById("prix_vol_id").innerHTML = newprix + "<span style='font-size: 12px;'> CHF</span>";
    document.getElementById("prix_vol_input_<?php echo $account5 -> idtxp; ?>").value = newprix1;
        var str = document.getElementById("id_option_adulte_repas<?php echo $account5 -> idtxp; ?>").value;
    document.getElementById("id_option_adulte_repas<?php echo $account5 -> idtxp; ?>").value = str.replace(<?php echo $account8 -> id_option; ?>, "");
  }
}
</script>


<?php
 
        }
      }


}

?>



<?php

    $stmt8 = $conn->prepare('SELECT * FROM option_vol_repas WHERE idtxp=:idtxp AND personne =:personne');
    $stmt8 ->bindValue('idtxp', $account5 -> idtxp);
    $stmt8 ->bindValue('personne', 'Enfant');
    $stmt8 ->execute();
    while($account8 = $stmt8 ->fetch(PDO::FETCH_OBJ))
    {

          if(isset($account8 -> id_option))
          {
                if($enfant!=0)
                {     
?>

                <tr>
                  <td style="width: 13%;  text-align: left; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                      <input type="checkbox" name="enfant_option_repas" id="myCheck_enfant_option_repas<?php echo $account8 -> id_option; ?>" onclick="myFunctionenfantOptionRepas_<?php echo $account8 -> id_option; ?>()">
                  </td>

                  <td style="width: 13%; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;"><span style="color:#92a5ac;font-weight: 300;"><?php echo ($account8 -> titre); ?></span></td>

                  <td style="width: 13%; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;"><span style="color:#92a5ac;font-weight: 300;">Enfant</span></td>
                  <td style="width: 13%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;"><span style="color:#92a5ac;font-weight: 300;"><?php echo $enfant_option_vol_repas = round($account8 -> prix_total, 2); ?> CHF</span></td>
                  <td style="width: 12%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                      <span style="color:#92a5ac;font-weight: 300;"><?php echo $enfant; ?></span>
                  </td>
                  <td style="width: 12%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                       <span style="color:#92a5ac;font-weight: 300;"> <?php echo $enfant_option_vol_repas * $enfant; ?> CHF
                       </span>
                  </td>
                </tr>

<script>

document.getElementById("option_repas_enfant").value = <?php echo $enfant_option_vol_repas * $enfant; ?>

function myFunctionenfantOptionRepas_<?php echo $account8 -> id_option; ?>() {
  var checkboxRepas = document.getElementById("myCheck_enfant_option_repas<?php echo $account8 -> id_option; ?>");
  if (checkboxRepas.checked == true)
  {

    
    var option_repas_value =document.getElementById("option_repas_enfant").value;
    var prix_vol_value =document.getElementById("prix_vol_input_<?php echo $account5 -> idtxp; ?>").value;
    var newprix1 = parseFloat(option_repas_value) + parseFloat(prix_vol_value);
    var newprix = (newprix1).toLocaleString(
      undefined, // leave undefined to use the visitor's browser 
                 // locale or a string like 'en-US' to override it.
      { minimumFractionDigits: 2 }
    );

    document.getElementById("prix_vol_id").innerHTML = newprix + "<span style='font-size: 12px;'> CHF</span>";
    document.getElementById("prix_vol_input_<?php echo $account5 -> idtxp; ?>").value = newprix1;
    document.getElementById("id_option_enfant_repas<?php echo $account5 -> idtxp; ?>").value = document.getElementById("id_option_enfant_repas<?php echo $account5 -> idtxp; ?>").value + '-' + <?php echo $account8 -> id_option; ?>;
    
  } else {
   
    var option_repas_value =document.getElementById("option_repas_enfant").value;
    var prix_vol_value =document.getElementById("prix_vol_input_<?php echo $account5 -> idtxp; ?>").value;

    var newprix1 = parseFloat(prix_vol_value) - parseFloat(option_repas_value);
    var newprix = (newprix1).toLocaleString(
      undefined, // leave undefined to use the visitor's browser 
                 // locale or a string like 'en-US' to override it.
      { minimumFractionDigits: 2 }
    );

    document.getElementById("prix_vol_id").innerHTML = newprix + "<span style='font-size: 12px;'> CHF</span>";
    document.getElementById("prix_vol_input_<?php echo $account5 -> idtxp; ?>").value = newprix1;
    var str = document.getElementById("id_option_enfant_repas<?php echo $account5 -> idtxp; ?>").value;
    document.getElementById("id_option_enfant_repas<?php echo $account5 -> idtxp; ?>").value = str.replace(<?php echo $account8 -> id_option; ?>, "");
  }
}
</script>


<?php
 
        }
      }


}

?>


<?php

    $stmt8 = $conn->prepare('SELECT * FROM option_vol_repas WHERE idtxp=:idtxp AND personne =:personne');
    $stmt8 ->bindValue('idtxp', $account5 -> idtxp);
    $stmt8 ->bindValue('personne', 'Bébé');
    $stmt8 ->execute();
    while($account8 = $stmt8 ->fetch(PDO::FETCH_OBJ))
    {

          if(isset($account8 -> id_option))
          {
                if($bebe!=0)
                {     
?>

                <tr>
                  <td style="width: 13%;  text-align: left; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                      <input type="checkbox" name="bebe_option_repas" id="myCheck_bebe_option_repas<?php echo $account8 -> id_option; ?>" onclick="myFunctionbebeOptionRepas_<?php echo $account8 -> id_option; ?>()">
                  </td>

                  <td style="width: 13%; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;"><span style="color:#92a5ac;font-weight: 300;"><?php echo ($account8 -> titre); ?></span></td>

                  <td style="width: 13%; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;"><span style="color:#92a5ac;font-weight: 300;">Bébé</span></td>
                  <td style="width: 13%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;"><span style="color:#92a5ac;font-weight: 300;"><?php echo $bebe_option_vol_repas = round($account8 -> prix_total, 2); ?> CHF</span></td>
                  <td style="width: 12%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                      <span style="color:#92a5ac;font-weight: 300;"><?php echo $bebe; ?></span>
                  </td>
                  <td style="width: 12%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                       <span style="color:#92a5ac;font-weight: 300;"> <?php echo $bebe_option_vol_repas * $bebe; ?> CHF
                        </span>
                  </td>
                </tr>

<script>

document.getElementById("option_repas_bebe").value = <?php echo $bebe_option_vol_repas * $bebe; ?>;
function myFunctionbebeOptionRepas_<?php echo $account8 -> id_option; ?>() {
  var checkboxRepas = document.getElementById("myCheck_bebe_option_repas<?php echo $account8 -> id_option; ?>");
  if (checkboxRepas.checked == true)
  {

    
    var option_repas_value =document.getElementById("option_repas_bebe").value;
    var prix_vol_value =document.getElementById("prix_vol_input_<?php echo $account5 -> idtxp; ?>").value;
    var newprix1 = parseFloat(option_repas_value) + parseFloat(prix_vol_value);
    var newprix = (newprix1).toLocaleString(
      undefined, // leave undefined to use the visitor's browser 
                 // locale or a string like 'en-US' to override it.
      { minimumFractionDigits: 2 }
    );

    document.getElementById("prix_vol_id").innerHTML = newprix + "<span style='font-size: 12px;'> CHF</span>";
    document.getElementById("prix_vol_input_<?php echo $account5 -> idtxp; ?>").value = newprix1;
    document.getElementById("id_option_bebe_repas<?php echo $account5 -> idtxp; ?>").value = document.getElementById("id_option_bebe_repas<?php echo $account5 -> idtxp; ?>").value + '-' + <?php echo $account8 -> id_option; ?>;
    
  } else {
   
    var option_repas_value =document.getElementById("option_repas_bebe").value;
    var prix_vol_value =document.getElementById("prix_vol_input_<?php echo $account5 -> idtxp; ?>").value;

    var newprix1 = parseFloat(prix_vol_value) - parseFloat(option_repas_value);
    var newprix = (newprix1).toLocaleString(
      undefined, // leave undefined to use the visitor's browser 
                 // locale or a string like 'en-US' to override it.
      { minimumFractionDigits: 2 }
    );

    document.getElementById("prix_vol_id").innerHTML = newprix + "<span style='font-size: 12px;'> CHF</span>";
    document.getElementById("prix_vol_input_<?php echo $account5 -> idtxp; ?>").value = newprix1;
            var str = document.getElementById("id_option_bebe_repas<?php echo $account5 -> idtxp; ?>").value;
    document.getElementById("id_option_bebe_repas<?php echo $account5 -> idtxp; ?>").value = str.replace(<?php echo $account8 -> id_option; ?>, "");
  }
}
</script>


<?php
 
        }
      }


}

?>

          </tbody>
        </table>
</div>
<div class="col-sm-12">&nbsp;&nbsp;</div>
</div>
