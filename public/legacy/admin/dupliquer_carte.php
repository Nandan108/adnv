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

    $stmt1 = $conn->prepare('SELECT * FROM carte  WHERE id_carte= :id_carte');
    $stmt1 -> bindValue('id_carte', $_GET['id_carte']);
    $stmt1 ->execute();
    $account1 = $stmt1 ->fetch(PDO::FETCH_OBJ);

    $stmt = $conn ->prepare ("insert into `carte`(`titre`, `lat`, `longitude`, `photo`, `pays`, `ville`, `adresse`, `tel`, `categorie`, `description`, `quartier`, `code_postale`, `site`, `recommande`, `etat`) VALUE (:titre, :lat, :longitude, :photo, :pays, :ville, :adresse, :tel, :categorie, :description, :quartier, :code_postale, :site, :recommande, :etat)");
    $stmt->bindValue('titre',$account1 -> titre);
    $stmt->bindValue('lat',$account1 -> lat);
    $stmt->bindValue('longitude',$account1 -> longitude);
    $stmt->bindValue('pays',$account1 -> pays);
    $stmt->bindValue('ville',$account1 -> ville);
    $stmt->bindValue('adresse',$account1 -> adresse);
    $stmt->bindValue('tel',$account1 -> tel);
    $stmt->bindValue('categorie',$account1 -> categorie);
    $stmt->bindValue('description',$account1 -> description);
    $stmt->bindValue('photo',$account1 -> photo);
    $stmt->bindValue('quartier',$account1 -> quartier);
    $stmt->bindValue('code_postale',$account1 -> code_postale);
    $stmt->bindValue('site',$account1 -> site);
    $stmt->bindValue('recommande',$account1 -> recommande);
    $stmt->bindValue('etat',$account1 -> etat);

   $stmt->execute();


    echo "<script type='text/javascript'>alert('".$account1 -> titre." a été dupliqué avec succée');</script>";
    echo "<meta http-equiv='refresh' content='0;url=cartes.php'/>";


?>

<?php
}
else{
            header('Location:../index.php');
           }
?>