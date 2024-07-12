<?php

  include_once ("../database.php");


    $id_reservation_info_devis_client_circuit = $_POST['id_reservation_info_devis_client_circuit'];
    $date = $_POST['date'];
    $num = $_POST['num'];
    $depart = $_POST['depart'];
    $arrive = $_POST['arrive'];
    $heure_depart = $_POST['heure_depart'];
    $heure_arrive = $_POST['heure_arrive'];


    
    $stmt55 = $conn ->prepare ("insert into `vol_devis_client_circuit` (`date`, `num`, `depart`, `arrive`, `heure_depart`, `heure_arrive`, `id_reservation_info_devis_client_circuit`) VALUE (:date, :num, :depart, :arrive, :heure_depart, :heure_arrive, :id_reservation_info_devis_client_circuit)");

   $stmt55->bindValue('date', $date);
   $stmt55->bindValue('num', $num);
   $stmt55->bindValue('depart', $depart);
   $stmt55->bindValue('arrive', $arrive);
   $stmt55->bindValue('heure_depart', $heure_depart);
   $stmt55->bindValue('heure_arrive', $heure_arrive);
   $stmt55->bindValue('id_reservation_info_devis_client_circuit', $id_reservation_info_devis_client_circuit);
   $stmt55->execute();



?>

                        <table style="width: 100%;text-align: center;">
                                       
                                        <tr>
                                          <td><b>Date</b></td>
                                          <td><b>Numéro vol</b></td>
                                          <td><b>Départ</b></td>
                                          <td><b>Arrivée</b></td>
                                          <td><b>Heure départ</b></td>
                                          <td><b>Heure arrivée</b></td>
                                          <td><b>Action</b></td>
                                        </tr>

<?php  

            $stmtx = $conn->prepare('SELECT * FROM vol_devis_client_circuit WHERE id_reservation_info_devis_client_circuit =:id_reservation_info_devis_client_circuit ORDER BY date ASC, heure_depart ASC');
            $stmtx ->bindValue('id_reservation_info_devis_client_circuit', $id_reservation_info_devis_client_circuit);
            $stmtx ->execute();
            while($accountx = $stmtx ->fetch(PDO::FETCH_OBJ))
            {

                if(isset($accountx -> id_vol_devis_client_circuit))
                {
                    ?>
                                        <tr>
                                          <td><?php 
$date_maj = explode('-',$accountx -> date);
   if($date_maj[1]=='01')
   {
        $mois = 'Janvier';
   }
   if($date_maj[1]=='02')
   {
        $mois = 'Février';
   }
      if($date_maj[1]=='03')
   {
        $mois = 'Mars';
   }
      if($date_maj[1]=='04')
   {
        $mois = 'Avril';
   }
      if($date_maj[1]=='05')
   {
        $mois = 'Mai';
   }
      if($date_maj[1]=='06')
   {
        $mois = 'Juin';
   }
      if($date_maj[1]=='07')
   {
        $mois = 'Juillet';
   }
      if($date_maj[1]=='08')
   {
        $mois = 'Août';
   }
      if($date_maj[1]=='08')
   {
        $mois = 'Août';
   }
         if($date_maj[1]=='09')
   {
        $mois = 'Septembre';
   }
         if($date_maj[1]=='10')
   {
        $mois = 'Octobre';
   }
         if($date_maj[1]=='11')
   {
        $mois = 'Novembre';
   }
         if($date_maj[1]=='12')
   {
        $mois = 'Décembre';
   }


echo $date_maj_1 = $date_maj[2].' '.$mois.' '.$date_maj[0];

?></td>
                                          <td><?php echo $accountx -> num ;?></td>
                                          <td><?php echo $accountx -> depart ;?></td>
                                          <td><?php echo $accountx -> arrive ;?></td>
                                          <td><?php echo $accountx -> heure_depart ;?></td>
                                          <td><?php echo $accountx -> heure_arrive ;?></td>
                                          <td><a href="ajout_devis_client_circuit_etape3.php?id=<?php echo MD5($id_reservation_info_devis_client_circuit); ?>&idxx=<?php echo $accountx -> id_vol_devis_client_circuit; ?>&action=delete" onclick="return confirm('Vous etes sur de supprimer cette enregistrement?')" class="btn btn-danger" id="delete_vol">Supprimer</a>

                                          <a href="javascript:void(0)" class="btn btn-primary" id="edit<?php echo $accountx -> id_vol_devis_client_circuit; ?>">Modifier</a>


                                          </td>
                                        </tr>
     <tr id="hide-edit<?php echo $accountx -> id_vol_devis_client_circuit; ?>" style="display: none;">
                                          <td><input type="date" name="date<?php echo $accountx -> id_vol_devis_client_circuit; ?>" value="<?php echo $accountx -> date ;?>" style="width: 120px;"></td>
                                          <td><input type="text" name="num<?php echo $accountx -> id_vol_devis_client_circuit; ?>" value="<?php echo $accountx -> num ;?>" style="width: 120px;"></td>
                                          <td><input type="text" name="depart<?php echo $accountx -> id_vol_devis_client_circuit; ?>" value="<?php echo $accountx -> depart ;?>" style="width: 120px;"></td>
                                          <td><input type="text" name="arrive<?php echo $accountx -> id_vol_devis_client_circuit; ?>" value="<?php echo $accountx -> arrive ;?>" style="width: 120px;"></td>
                                          <td><input type="text" name="heure_depart<?php echo $accountx -> id_vol_devis_client_circuit; ?>" value="<?php echo $accountx -> heure_depart ;?>" style="width: 120px;"></td>
                                          <td><input type="text" name="heure_arrive<?php echo $accountx -> id_vol_devis_client_circuit; ?>" value="<?php echo $accountx -> heure_arrive ;?>" style="width: 120px;"></td>
                                          <td>
                                            <input type="hidden" name="id<?php echo $accountx -> id_vol_devis_client_circuit; ?>" value="<?php echo $accountx -> id_vol_devis_client_circuit; ?>">

                                            <input type="hidden" name="id_devis<?php echo $accountx -> id_vol_devis_client_circuit; ?>" value="<?php echo $accountx -> id_reservation_info_devis_client_circuit; ?>">


                                            <a href="javascript:void(0)" class="btn btn-primary" id="save<?php echo $accountx -> id_vol_devis_client_circuit; ?>">Enregistrer</a>
                                          </td>
                                        </tr>





<script type="text/javascript">

    $("#edit<?php echo $accountx -> id_vol_devis_client_circuit; ?>").click( function(){
        $("#hide-edit<?php echo $accountx -> id_vol_devis_client_circuit; ?>").show();
        $("#show-edit<?php echo $accountx -> id_vol_devis_client_circuit; ?>").hide();
    }); 
    $("#save<?php echo $accountx -> id_vol_devis_client_circuit; ?>").click( function(){
        $("#hide-edit<?php echo $accountx -> id_vol_devis_client_circuit; ?>").hide();
        $("#show-edit<?php echo $accountx -> id_vol_devis_client_circuit; ?>").show();

        
        var date = document. form2.date<?php echo $accountx -> id_vol_devis_client_circuit; ?>.value;
        var num = document. form2.num<?php echo $accountx -> id_vol_devis_client_circuit; ?>.value;
        var depart = document. form2.depart<?php echo $accountx -> id_vol_devis_client_circuit; ?>.value;
        var arrive = document. form2.arrive<?php echo $accountx -> id_vol_devis_client_circuit; ?>.value;
        var heure_depart = document. form2.heure_depart<?php echo $accountx -> id_vol_devis_client_circuit; ?>.value;
        var heure_arrive = document. form2.heure_arrive<?php echo $accountx -> id_vol_devis_client_circuit; ?>.value;
        var id = document. form2.id<?php echo $accountx -> id_vol_devis_client_circuit; ?>.value;
        var id_devis = document. form2.id_devis<?php echo $accountx -> id_vol_devis_client_circuit; ?>.value;

                    $.ajax({
                             
                            url:"ajax/edit_vol_devis_circuit.php",
                            method:"POST",
                            data:{id:id,date:date,num:num,depart:depart,arrive:arrive,heure_depart:heure_depart,heure_arrive:heure_arrive,id_devis:id_devis},
                            success:function(data)
                            {
                              $('#vol_liste').html(data);
                            }

                     });
  


    }); 
 




</script>



                    <?php
                }

            }

?>

                            


                                     </table>


