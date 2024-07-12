<div class="col-sm-12" id="option_autre_vol_<?php echo $vol->id; ?>" style="display : none;border-left: 10px solid red;">
                                                    <div class="row_cust_bot_subtitle" style="text-align: left;color: rgb(0, 0, 0) !important;">
                                                        <br>
                                                       <span style="font-weight: bold"><i class="fa fa-bars"></i>&nbsp;&nbsp;  Option sur les vols par personne en  <span style="color:red"> CHF</span></span>
                                                        <br/><br/>
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

    $stmt8 = $conn->prepare('SELECT * FROM option_vol_autre WHERE idtxp=:idtxp AND personne =:personne');
    $stmt8 ->bindValue('idtxp', $vol->id);
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
                      <input type="checkbox" name="adulte1_option" id="myCheck_adulte1_option<?php echo $account8 -> id_option; ?>" onclick="myFunctionAdulteOption_<?php echo $account8 -> id_option; ?>()" >
                  </td>

                  <td style="width: 13%; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;"><span style="color:#92a5ac;font-weight: 300;"><?php echo ($account8 -> titre); ?></span></td>

                  <td style="width: 13%; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;"><span style="color:#92a5ac;font-weight: 300;">Adulte</span></td>
                  <td style="width: 13%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;"><span style="color:#92a5ac;font-weight: 300;"><?php echo $adulte_option_vol = round($account8 -> prix_total, 2); ?> CHF</span></td>
                  <td style="width: 12%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                      <span style="color:#92a5ac;font-weight: 300;"><?php echo $adulte; ?></span>
                  </td>
                  <td style="width: 12%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                       <span style="color:#92a5ac;font-weight: 300;"> <?php echo $adulte_option_vol * $adulte; ?> CHF
                        </span>
                  </td>
                </tr>

<script>
 document.getElementById("option_autre").value = <?php echo $adulte_option_vol * $adulte; ?>;
function myFunctionAdulteOption_<?php echo $account8 -> id_option; ?>() {
  var checkBox = document.getElementById("myCheck_adulte1_option<?php echo $account8 -> id_option; ?>");
  if (checkBox.checked == true)
  {


    var option_autre_value =document.getElementById("option_autre").value;

    var prix_vol_value =document.getElementById("prix_vol_input_<?php echo $vol->id; ?>").value;

    var newprix1 = parseFloat(option_autre_value) + parseFloat(prix_vol_value);
    var newprix = (newprix1).toLocaleString(
      undefined, // leave undefined to use the visitor's browser
                 // locale or a string like 'en-US' to override it.
      { minimumFractionDigits: 2 }
    );

   // document.getElementById("prix_vol_id").innerHTML = newprix + "<span style='font-size: 12px;'> CHF</span>";
    document.getElementById("prix_vol_input_<?php echo $vol->id; ?>").value = newprix1;
    document.getElementById("id_option_adulte<?php echo $vol->id; ?>").value = document.getElementById("id_option_adulte<?php echo $vol->id; ?>").value + '-' + <?php echo $account8 -> id_option; ?>;


  } else {

    var option_autre_value =document.getElementById("option_autre").value;
    var prix_vol_value =document.getElementById("prix_vol_input_<?php echo $vol->id; ?>").value;

    var newprix1 = parseFloat(prix_vol_value) - parseFloat(option_autre_value);
    var newprix = (newprix1).toLocaleString(
      undefined, // leave undefined to use the visitor's browser
                 // locale or a string like 'en-US' to override it.
      { minimumFractionDigits: 2 }
    );

   // document.getElementById("prix_vol_id").innerHTML = newprix + "<span style='font-size: 12px;'> CHF</span>";
    document.getElementById("prix_vol_input_<?php echo $vol->id; ?>").value = newprix1;
    var str = document.getElementById("id_option_adulte<?php echo $vol->id; ?>").value;
    document.getElementById("id_option_adulte<?php echo $vol->id; ?>").value = str.replace(<?php echo $account8 -> id_option; ?>, "");

  }
}
</script>


<?php

        }
      }


}



    $stmt8 = $conn->prepare('SELECT * FROM option_vol_autre WHERE idtxp=:idtxp AND personne =:personne');
    $stmt8 ->bindValue('idtxp', $vol->id);
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
                      <input type="checkbox" name="enfant_option" id="myCheck_enfant_option<?php echo $account8 -> id_option; ?>" onclick="myFunctionEnfantOption_<?php echo $account8 -> id_option; ?>()">
                  </td>

                  <td style="width: 13%; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;"><span style="color:#92a5ac;font-weight: 300;"><?php echo ($account8 -> titre); ?></span></td>

                  <td style="width: 13%; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;"><span style="color:#92a5ac;font-weight: 300;">Enfant</span></td>
                  <td style="width: 13%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;"><span style="color:#92a5ac;font-weight: 300;"><?php echo $adulte_option_vol = round($account8 -> prix_total, 2); ?> CHF</span></td>
                  <td style="width: 12%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                      <span style="color:#92a5ac;font-weight: 300;"><?php echo $enfant; ?></span>
                  </td>
                  <td style="width: 12%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                       <span style="color:#92a5ac;font-weight: 300;"> <?php echo $adulte_option_vol * $enfant; ?> CHF
                       </span>
                  </td>
                </tr>

<script>

document.getElementById("option_autre_enfant").value = <?php echo $adulte_option_vol * $enfant; ?>;

function myFunctionEnfantOption_<?php echo $account8 -> id_option; ?>() {
  var checkBoxEnfant = document.getElementById("myCheck_enfant_option<?php echo $account8 -> id_option; ?>");
  if (checkBoxEnfant.checked == true)
  {


    var option_autre_enfant =document.getElementById("option_autre_enfant").value;
    var prix_vol_value =document.getElementById("prix_vol_input_<?php echo $vol->id; ?>").value;
    var newprix1 = parseFloat(option_autre_enfant) + parseFloat(prix_vol_value);
    var newprix = (newprix1).toLocaleString(
      undefined, // leave undefined to use the visitor's browser
                 // locale or a string like 'en-US' to override it.
      { minimumFractionDigits: 2 }
    );

    //document.getElementById("prix_vol_id").innerHTML = newprix + "<span style='font-size: 12px;'> CHF</span>";
    document.getElementById("prix_vol_input_<?php echo $vol->id; ?>").value = newprix1;
    document.getElementById("id_option_enfant<?php echo $vol->id; ?>").value = document.getElementById("id_option_enfant<?php echo $vol->id; ?>").value + '-' + <?php echo $account8 -> id_option; ?>;


  } else {

    var option_autre_enfant =document.getElementById("option_autre_enfant").value;
    var prix_vol_value =document.getElementById("prix_vol_input_<?php echo $vol->id; ?>").value;

    var newprix1 = parseFloat(prix_vol_value) - parseFloat(option_autre_enfant);
    var newprix = (newprix1).toLocaleString(
      undefined, // leave undefined to use the visitor's browser
                 // locale or a string like 'en-US' to override it.
      { minimumFractionDigits: 2 }
    );

   // document.getElementById("prix_vol_id").innerHTML = newprix + "<span style='font-size: 12px;'> CHF</span>";
    document.getElementById("prix_vol_input_<?php echo $vol->id; ?>").value = newprix1;
    var str = document.getElementById("id_option_enfant<?php echo $vol->id; ?>").value;
    document.getElementById("id_option_enfant<?php echo $vol->id; ?>").value = str.replace(<?php echo $account8 -> id_option; ?>, "");
  }
}
</script>


<?php
        }
      }

}



    $stmt8 = $conn->prepare('SELECT * FROM option_vol_autre WHERE idtxp=:idtxp AND personne =:personne');
    $stmt8 ->bindValue('idtxp', $vol->id);
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
                      <input type="checkbox" name="bebe_option" id="myCheck_bebe_option<?php echo $account8 -> id_option; ?>" onclick="myFunctionbebeOption_<?php echo $account8 -> id_option; ?>()">
                  </td>

                  <td style="width: 13%; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;"><span style="color:#92a5ac;font-weight: 300;"><?php echo $account8 -> titre; ?></span></td>

                  <td style="width: 13%; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;"><span style="color:#92a5ac;font-weight: 300;">Bébé</span></td>
                  <td style="width: 13%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;"><span style="color:#92a5ac;font-weight: 300;"><?php echo $adulte_option_vol = round($account8 -> prix_total, 2); ?> CHF</span></td>
                  <td style="width: 12%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">

                      <span style="color:#92a5ac;font-weight: 300;"><?php echo $bebe; ?> </span>

                  </td>
                  <td style="width: 12%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                       <span style="color:#92a5ac;font-weight: 300;"> <?php echo $adulte_option_vol * $bebe; ?> CHF
                        </span>
                  </td>
                </tr>

<script>

document.getElementById("option_autre_bebe").value = <?php echo $adulte_option_vol * $bebe; ?>;
function myFunctionbebeOption_<?php echo $account8 -> id_option; ?>() {
  var checkBoxbebe = document.getElementById("myCheck_bebe_option<?php echo $account8 -> id_option; ?>");
  if (checkBoxbebe.checked == true)
  {


    var option_autre_bebe =document.getElementById("option_autre_bebe").value;
    var prix_vol_value =document.getElementById("prix_vol_input_<?php echo $vol->id; ?>").value;
    var newprix1 = parseFloat(option_autre_bebe) + parseFloat(prix_vol_value);
    var newprix = (newprix1).toLocaleString(
      undefined, // leave undefined to use the visitor's browser
                 // locale or a string like 'en-US' to override it.
      { minimumFractionDigits: 2 }
    );

    //document.getElementById("prix_vol_id").innerHTML = newprix + "<span style='font-size: 12px;'> CHF</span>";
    document.getElementById("prix_vol_input_<?php echo $vol->id; ?>").value = newprix1;
    document.getElementById("id_option_bebe<?php echo $vol->id; ?>").value = document.getElementById("id_option_bebe<?php echo $vol->id; ?>").value + '-' + <?php echo $account8 -> id_option; ?>;

  } else {

    var option_autre_bebe =document.getElementById("option_autre_bebe").value;
    var prix_vol_value =document.getElementById("prix_vol_input_<?php echo $vol->id; ?>").value;

    var newprix1 = parseFloat(prix_vol_value) - parseFloat(option_autre_bebe);
    var newprix = (newprix1).toLocaleString(
      undefined, // leave undefined to use the visitor's browser
                 // locale or a string like 'en-US' to override it.
      { minimumFractionDigits: 2 }
    );

  //  document.getElementById("prix_vol_id").innerHTML = newprix + "<span style='font-size: 12px;'> CHF</span>";
    document.getElementById("prix_vol_input_<?php echo $vol->id; ?>").value = newprix1;
    var str = document.getElementById("id_option_bebe<?php echo $vol->id; ?>").value;
    document.getElementById("id_option_bebe<?php echo $vol->id; ?>").value = str.replace(<?php echo $account8 -> id_option; ?>, "");
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


