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


$id_sejour2 = $conn->lastInsertId();

    $stmtx = $conn->prepare('SELECT * FROM package_manuel  WHERE id_sejour= :id_sejour');
    $stmtx -> bindValue('id_sejour', $id_sejour);
    $stmtx ->execute();
    $account11 = $stmtx ->fetch(PDO::FETCH_OBJ);

    $stmt55 = $conn ->prepare ("insert into `package_manuel`(`id_sejour_manuel`, `id_sejour`, `hotel`, `jour_depart`, `enfant3_sejour`, `enfant3_sejour_1`, `simple_nb_max`, `simple_adulte_max`, `simple_enfant_max`, `simple_bebe_max`, `de_1_enfant`, `a_1_enfant`, `de_2_enfant`, `a_2_enfant`, `de_3_enfant`, `a_3_enfant`, `bebe_1`, `double_nb_max`, `double_adulte_max`, `double_enfant_max`, `double_bebe_max`, `double_de_1_enfant`, `double_a_1_enfant`, `double_de_2_enfant`, `double_a_2_enfant`, `double_de_3_enfant`, `double_a_3_enfant`, `double_bebe_1`, `tripple_nb_max`, `tripple_adulte_max`) VALUE (:id_sejour_manuel, :id_sejour, :hotel, :jour_depart, :enfant3_sejour, :enfant3_sejour_1, :simple_nb_max, :simple_adulte_max, :simple_enfant_max, :simple_bebe_max, :de_1_enfant, :a_1_enfant, :de_2_enfant, :a_2_enfant, :de_3_enfant, :a_3_enfant, :bebe_1, :double_nb_max, :double_adulte_max, :double_enfant_max, :double_bebe_max, :double_de_1_enfant, :double_a_1_enfant, :double_de_2_enfant, :double_a_2_enfant, :double_de_3_enfant, :double_a_3_enfant, :double_bebe_1, :tripple_nb_max, :tripple_adulte_max )");
    
   $stmt55->bindValue('id_sejour_manuel','');
   $stmt55->bindValue('id_sejour', $id_sejour2);
   $stmt55->bindValue('hotel',addslashes($account11 ->hotel));
   $stmt55->bindValue('jour_depart',addslashes($account11 ->jour_depart));

  $stmt55->bindValue('enfant3_sejour',addslashes($account11 ->enfant3_sejour));
  $stmt55->bindValue('enfant3_sejour_1',addslashes($account11 ->enfant3_sejour_1));
  $stmt55->bindValue('simple_nb_max',addslashes($account11 ->simple_nb_max));
  $stmt55->bindValue('simple_adulte_max',addslashes($account11 ->simple_adulte_max));
  $stmt55->bindValue('simple_enfant_max',addslashes($account11 ->simple_enfant_max));
  $stmt55->bindValue('simple_bebe_max',addslashes($account11 ->simple_bebe_max));
  $stmt55->bindValue('de_1_enfant',addslashes($account11 ->de_1_enfant));
  $stmt55->bindValue('a_1_enfant',addslashes($account11 ->a_1_enfant));
  $stmt55->bindValue('de_2_enfant',addslashes($account11 ->de_2_enfant));
  $stmt55->bindValue('a_2_enfant',addslashes($account11 ->a_2_enfant));
  $stmt55->bindValue('de_3_enfant',addslashes($account11 ->de_3_enfant));
  $stmt55->bindValue('a_3_enfant',addslashes($account11 ->a_3_enfant));
  $stmt55->bindValue('bebe_1',addslashes($account11 ->bebe_1));
  $stmt55->bindValue('double_nb_max',addslashes($account11 ->double_nb_max));
  $stmt55->bindValue('double_adulte_max',addslashes($account11 ->double_adulte_max));
  $stmt55->bindValue('double_enfant_max',addslashes($account11 ->double_enfant_max));
  $stmt55->bindValue('double_bebe_max',addslashes($account11 ->double_bebe_max));
  $stmt55->bindValue('double_de_1_enfant',addslashes($account11 ->double_de_1_enfant));
  $stmt55->bindValue('double_a_1_enfant',addslashes($account11 ->double_a_1_enfant));
  $stmt55->bindValue('double_de_2_enfant',addslashes($account11 ->double_de_2_enfant));
  $stmt55->bindValue('double_a_2_enfant',addslashes($account11 ->double_a_2_enfant));
  $stmt55->bindValue('double_de_3_enfant',addslashes($account11 ->double_de_3_enfant));
  $stmt55->bindValue('double_a_3_enfant',addslashes($account11 ->double_a_3_enfant));
  $stmt55->bindValue('double_bebe_1',addslashes($account11 ->double_bebe_1));
  $stmt55->bindValue('tripple_nb_max',addslashes($account11 ->tripple_nb_max));
  $stmt55->bindValue('tripple_adulte_max',addslashes($account11 ->tripple_adulte_max));

   $stmt55->execute();
 //echo "<meta http-equiv='refresh' content='0;url=packages.php'/>";



echo "<script type='text/javascript'>alert('Duplication effectuée avec succée');</script>";
  echo "<meta http-equiv='refresh' content='0;url=package_manuel.php'/>";

?>


<?php
}
else{
            header('Location:../index.php');
           }
?>