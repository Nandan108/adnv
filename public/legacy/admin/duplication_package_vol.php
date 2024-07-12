<?php
        session_start ();
        if (isset($_SESSION['account_login'])) {

            $account_login=$_SESSION['account_login'];
            require 'database.php';
            $stmt7 = $conn->prepare('SELECT * FROM admin WHERE account_login =:account_login');
            $stmt7 ->bindValue('account_login', $account_login);
            $stmt7 ->execute();
            $account7 = $stmt7 ->fetch(PDO::FETCH_OBJ);

            $nom = $account7 -> nom;
            $prenom = $account7 -> prenom;

            include 'header.php';


 ?>

<!-- header -->
<style type="text/css">

.custom-date-style {
    background-color: red !important;
}

.input-wide{
    width: 500px;
}

</style>

 
<?php
require 'database.php';

if(isset($_GET['id_sejour']))
{
  $id_sejour = $_GET['id_sejour'];
}


    $stmtx = $conn->prepare('SELECT * FROM package  WHERE id_sejour= :id_sejour');
    $stmtx -> bindValue('id_sejour', $id_sejour);
    $stmtx ->execute();
    $account1 = $stmtx ->fetch(PDO::FETCH_OBJ);



    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES,false); 
    $stmt55 = $conn ->prepare ("insert into `package` (`id_sejour`, `titre`, `pays`, `ville`, `id_hotel`, `debut_vente`, `fin_vente`, `debut_voyage`, `fin_voyage`, `id_vol`, `id_transfert`, `type_transfert`, `id_chambre`, `nb_nuit`, `adulte1_sejour`, `adulte2_sejour`, `enfant1_sejour`, `enfant2_sejour`, `bebe_sejour`, `adulte1_sejour_1`, `enfant1_sejour_1`, `enfant2_sejour_1`, `bebe_sejour_1`, `adulte1_sejour_3`, `adulte2_sejour_3`, `adulte3_sejour_3`, `photo`, `inclu`, `noninclu`, `total_sans_remise`, `promo`, `avant`, `manuel`) VALUE (:id_sejour, :titre, :pays, :ville, :id_hotel, :debut_vente, :fin_vente, :debut_voyage, :fin_voyage, :id_vol, :id_transfert, :type_transfert, :id_chambre, :nb_nuit, :adulte1_sejour, :adulte2_sejour, :enfant1_sejour, :enfant2_sejour, :bebe_sejour, :adulte1_sejour_1, :enfant1_sejour_1, :enfant2_sejour_1, :bebe_sejour_1, :adulte1_sejour_3, :adulte2_sejour_3, :adulte3_sejour_3, :photo, :inclu, :noninclu, :total_sans_remise, :promo, :avant, :manuel)");
   $stmt55->bindValue('id_sejour','');
   $stmt55->bindValue('titre',addslashes($account1 ->titre).' (Copie)');
   $stmt55->bindValue('pays',addslashes($account1 ->pays));
   $stmt55->bindValue('ville',addslashes($account1 ->ville));
   $stmt55->bindValue('id_hotel',addslashes($account1 ->id_hotel));
   $stmt55->bindValue('debut_vente',addslashes($account1 ->debut_vente));
   $stmt55->bindValue('fin_vente',addslashes($account1 ->fin_vente));
   $stmt55->bindValue('debut_voyage',addslashes($account1 ->debut_voyage));
   $stmt55->bindValue('fin_voyage',addslashes($account1 ->fin_voyage));
   $stmt55->bindValue('id_vol',addslashes($account1 ->id_vol));
   $stmt55->bindValue('id_transfert',addslashes($account1 ->id_transfert));
   $stmt55->bindValue('type_transfert',addslashes($account1 ->type_transfert));
   $stmt55->bindValue('id_chambre',addslashes($account1 ->id_chambre));
   $stmt55->bindValue('nb_nuit',addslashes($account1 ->nb_nuit));
   $stmt55->bindValue('adulte1_sejour',addslashes($account1 ->adulte1_sejour));
   $stmt55->bindValue('adulte2_sejour',addslashes($account1 ->adulte2_sejour));
   $stmt55->bindValue('enfant1_sejour',addslashes($account1 ->enfant1_sejour));
   $stmt55->bindValue('enfant2_sejour',addslashes($account1 ->enfant2_sejour));
   $stmt55->bindValue('bebe_sejour',addslashes($account1 ->bebe_sejour));

   $stmt55->bindValue('adulte1_sejour_1',addslashes($account1 ->adulte1_sejour_1));
   $stmt55->bindValue('enfant1_sejour_1',addslashes($account1 ->enfant1_sejour_1));
   $stmt55->bindValue('enfant2_sejour_1',addslashes($account1 ->enfant2_sejour_1));
   $stmt55->bindValue('bebe_sejour_1',addslashes($account1 ->bebe_sejour_1));
   $stmt55->bindValue('adulte1_sejour_3',addslashes($account1 ->adulte1_sejour_3));
   $stmt55->bindValue('adulte2_sejour_3',addslashes($account1 ->adulte2_sejour_3));
   $stmt55->bindValue('adulte3_sejour_3',addslashes($account1 ->adulte3_sejour_3));


   $stmt55->bindValue('photo',addslashes($account1 ->photo));
   $stmt55->bindValue('inclu',addslashes($account1 ->inclu));
   $stmt55->bindValue('noninclu',addslashes($account1 ->noninclu));

   $stmt55->bindValue('total_sans_remise',addslashes($account1 ->total_sans_remise));
   $stmt55->bindValue('promo',addslashes($account1 ->promo));
   $stmt55->bindValue('avant',addslashes($account1 ->avant));
   $stmt55->bindValue('manuel',addslashes($account1 ->manuel));
   $stmt55->execute();






 
        if (!$stmt55) {
   echo "\nPDO::errorInfo():\n";
   print_r($conn->errorInfo());
}


$id_sejour_manuel = $conn->lastInsertId();

    $stmtx = $conn->prepare('SELECT * FROM package_manuel_vol  WHERE id_sejour= :id_sejour');
    $stmtx -> bindValue('id_sejour', $id_sejour);
    $stmtx ->execute();
    $account10 = $stmtx ->fetch(PDO::FETCH_OBJ);





$stmt55 = $conn ->prepare ("insert into `package_manuel_vol`(`id_sejour_manuel_vol`, `id_sejour`, `compagnie`, `class_reservation`, `ville_depart`, `ville_arrive`, `aeroport_transit`, `taux_change`, `taux_commission`, `jour_depart`, `arrive_dv`, `adulte_vol_net`, `adulte_vol_brut`, `adulte_taxe`, `adulte_total`, `enfant_vol_net`, `enfant_vol_brut`, `enfant_taxe`, `enfant_total`, `bebe_vol_net`, `bebe_vol_brut`, `bebe_taxe`, `bebe_total`) VALUE (:id_sejour_manuel_vol, :id_sejour, :compagnie, :class_reservation, :ville_depart, :ville_arrive, :aeroport_transit, :taux_change, :taux_commission, :jour_depart, :arrive_dv, :adulte_vol_net, :adulte_vol_brut, :adulte_taxe, :adulte_total, :enfant_vol_net, :enfant_vol_brut, :enfant_taxe, :enfant_total, :bebe_vol_net, :bebe_vol_brut, :bebe_taxe, :bebe_total)");
    

  $stmt55->bindValue('id_sejour_manuel_vol','');
    $stmt55->bindValue('id_sejour', $id_sejour_manuel);
    $stmt55->bindValue('compagnie',addslashes(stripslashes($account10 -> compagnie)));
    $stmt55->bindValue('class_reservation',addslashes(stripslashes($account10 -> class_reservation)));
   $stmt55->bindValue('ville_depart',addslashes(stripslashes($account10 -> ville_depart)));
   $stmt55->bindValue('ville_arrive',addslashes(stripslashes($account10 -> ville_arrive)));
   $stmt55->bindValue('aeroport_transit',addslashes(stripslashes($account10 -> aeroport_transit)));

  $stmt55->bindValue('taux_change',addslashes(stripslashes($account10 -> taux_change)));
  $stmt55->bindValue('taux_commission',addslashes(stripslashes($account10 -> taux_commission)));
  $stmt55->bindValue('jour_depart',addslashes(stripslashes($account10 -> jour_depart)));  
  $stmt55->bindValue('arrive_dv',addslashes(stripslashes($account10 -> arrive_dv)));

 $stmt55->bindValue('adulte_vol_net',addslashes(stripslashes($account10 -> adulte_vol_net)));
 $stmt55->bindValue('adulte_vol_brut',addslashes(stripslashes($account10 -> adulte_vol_brut)));
 $stmt55->bindValue('adulte_taxe',addslashes(stripslashes($account10 -> adulte_taxe)));
 $stmt55->bindValue('adulte_total',addslashes(stripslashes($account10 -> adulte_total)));
 $stmt55->bindValue('enfant_vol_net',addslashes(stripslashes($account10 -> enfant_vol_net)));
 $stmt55->bindValue('enfant_vol_brut',addslashes(stripslashes($account10 -> enfant_vol_brut)));
 $stmt55->bindValue('enfant_taxe',addslashes(stripslashes($account10 -> enfant_taxe)));
 $stmt55->bindValue('enfant_total',addslashes(stripslashes($account10 -> enfant_total)));
 $stmt55->bindValue('bebe_vol_net',addslashes(stripslashes($account10 -> bebe_vol_net)));
 $stmt55->bindValue('bebe_vol_brut',addslashes(stripslashes($account10 -> bebe_vol_brut)));
 $stmt55->bindValue('bebe_taxe',addslashes(stripslashes($account10 -> bebe_taxe)));
 $stmt55->bindValue('bebe_total',addslashes(stripslashes($account10 -> bebe_total)));
   $stmt55->execute();



 



echo "<script type='text/javascript'>alert('Duplication effectuée avec succée');</script>";
 echo "<meta http-equiv='refresh' content='0;url=package_vol.php'/>";

?>


<?php
}
else{
            header('Location:../index.php');
           }
?>