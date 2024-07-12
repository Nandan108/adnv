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



    $stmt1 = $conn->prepare('SELECT * FROM partenaire_liste  WHERE id_partenaire_list =:id_partenaire_list');
    $stmt1 -> bindValue('id_partenaire_list', $_GET['id_partenaire_list']); 
    $stmt1 ->execute();
$account1 = $stmt1 ->fetch(PDO::FETCH_OBJ);

    $stmt = $conn ->prepare ("insert into `partenaire_liste` (`id_partenaire_list`, `type`, `nom`, `photo`) VALUE ( :id_partenaire_list,:type,:nom,:photo)");
    $stmt->bindValue('id_partenaire_list','');
        $stmt->bindValue('type',($account1 -> type));
        $stmt->bindValue('nom',($account1 -> nom));
        $stmt->bindValue('photo',($account1 -> photo));
        $stmt->execute();




echo "<script type='text/javascript'>alert('La duplication partenaire a été dupliqué avec succée');</script>";
   echo "<meta http-equiv='refresh' content='0;url=liste_partenaires.php'/>";



?>

<?php
}
else{
            header('Location:../index.php');
           }
?>