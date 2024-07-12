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

$url = $_GET['id_repas_hotel'];
$tab = explode('?',$url);
$id_repas_hotel = $tab[0];
$dossier = str_replace('dossier=', '', $tab[1]);


    $stmt1 = $conn->prepare('SELECT * FROM repas_hotel  WHERE id_repas_hotel= :id_repas_hotel');
    $stmt1 -> bindValue('id_repas_hotel', $id_repas_hotel);
    $stmt1 ->execute();
    $account1 = $stmt1 ->fetch(PDO::FETCH_OBJ);


$stmt5 = $conn ->prepare ("insert into `repas_hotel`( `id_hotel`, `id_partenaire`, `debut_validite`, `fin_validite`, `taux_change`, `taux_commission`, `prix_net_adulte`, `prix_brute_adulte`, `total_adulte`, `prix_net_enfant`, `prix_brute_enfant`, `total_enfant`, `prix_net_bebe`, `prix_brute_bebe`, `total_bebe`, `photo`) VALUE (:id_hotel, :id_partenaire, :debut_validite, :fin_validite, :taux_change, :taux_commission, :prix_net_adulte, :prix_brute_adulte, :total_adulte, :prix_net_enfant, :prix_brute_enfant, :total_enfant, :prix_net_bebe, :prix_brute_bebe, :total_bebe,:photo)");


$stmt5->bindValue('id_hotel',addslashes($account1 -> id_hotel));
$stmt5->bindValue('id_partenaire',addslashes(($account1 -> id_partenaire)));
$stmt5->bindValue('debut_validite',addslashes($account1 -> debut_validite));
$stmt5->bindValue('fin_validite',addslashes($account1 -> fin_validite));
$stmt5->bindValue('taux_change',addslashes($account1 -> taux_change));
$stmt5->bindValue('taux_commission',addslashes($account1 -> taux_commission));
$stmt5->bindValue('prix_net_adulte',addslashes($account1 -> prix_net_adulte));
$stmt5->bindValue('prix_brute_adulte',addslashes($account1 -> prix_brute_adulte));
$stmt5->bindValue('total_adulte',addslashes($account1 -> total_adulte));
$stmt5->bindValue('prix_net_enfant',addslashes($account1 -> prix_net_enfant));
$stmt5->bindValue('prix_brute_enfant',addslashes($account1 -> prix_brute_enfant));
$stmt5->bindValue('total_enfant',addslashes($account1 -> total_enfant));
$stmt5->bindValue('prix_net_bebe',addslashes($account1 -> prix_net_bebe));
$stmt5->bindValue('prix_brute_bebe',addslashes($account1 -> prix_brute_bebe));
$stmt5->bindValue('total_bebe',addslashes($account1 -> total_bebe));
$stmt5->bindValue('photo',addslashes($account1 -> photo));
$stmt5->execute();



echo "<meta http-equiv='refresh' content='0;url=liste_repas_hotel.php?dossier=$dossier'/>";



?>

<?php
}
else{
            header('Location:../index.php');
           }
?>