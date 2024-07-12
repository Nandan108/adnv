<?php

  include_once ("../database.php");


    $id_reservation_info_circuit = $_POST['id_reservation_info_circuit'];
    $date = $_POST['date'];
    $num = $_POST['num'];
    $depart = $_POST['depart'];
    $arrive = $_POST['arrive'];
    $heure_depart = $_POST['heure_depart'];
    $heure_arrive = $_POST['heure_arrive'];


    
    $stmt55 = $conn ->prepare ("insert into `vol_circuit` (`date`, `num`, `depart`, `arrive`, `heure_depart`, `heure_arrive`, `id_reservation_info_circuit`) VALUE (:date, :num, :depart, :arrive, :heure_depart, :heure_arrive, :id_reservation_info_circuit)");

   $stmt55->bindValue('date', $date);
   $stmt55->bindValue('num', $num);
   $stmt55->bindValue('depart', $depart);
   $stmt55->bindValue('arrive', $arrive);
   $stmt55->bindValue('heure_depart', $heure_depart);
   $stmt55->bindValue('heure_arrive', $heure_arrive);
   $stmt55->bindValue('id_reservation_info_circuit', $id_reservation_info_circuit);
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

            $stmtx = $conn->prepare('SELECT * FROM vol_circuit WHERE id_reservation_info_circuit =:id_reservation_info_circuit');
            $stmtx ->bindValue('id_reservation_info_circuit', $id_reservation_info_circuit);
            $stmtx ->execute();
            while($accountx = $stmtx ->fetch(PDO::FETCH_OBJ))
            {

                if(isset($accountx -> id_vol_circuit))
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
                                          <td><a href="javascript:void(0)" onclick="return confirm('Vous etes sur de supprimer cette enregistrement?')" class="btn btn-danger" id="delete<?php echo $accountx -> id_vol_circuit; ?>">Supprimer</a>

                                          <a href="javascript:void(0)" class="btn btn-primary" id="edit<?php echo $accountx -> id_vol_circuit; ?>">Modifier</a>


                                          </td>
                                        </tr>


                    <?php
                }

            }

?>

                            


                                     </table>


