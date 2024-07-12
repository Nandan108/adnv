<?php
require 'database.php';
include ('header.php');

//c4ca4238a0b923820dcc509a6f75849b
?>

<!--
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.css" />
-->
<script src="https://kit.fontawesome.com/17b19527c0.js" crossorigin="anonymous"></script>
<style type="text/css">
  .divTable.blueTable .divTableHeading .divTableHead,
  .divTable.blueTable .divTableBody .divTableCell {
    width: 11px;
  }
</style>
<?php


if (isset($_GET['xx'])) {
  $xx = $_GET['xx'];


  $stmt = $conn->prepare('SELECT * FROM reservation_valeur_package');
  $stmt->execute();
  while ($account = $stmt->fetch(PDO::FETCH_OBJ)) {
    if (Md5($account->id_reservation_valeur) == $xx) {

      //destination=Egypte?du=19-11-2021?au=26-11-2021?adulte=2?enfant=1?enfant1=6?enfant=0?bebe=0?budget=
      $id_reservation_valeur = $account->id_reservation_valeur;
      $url = $account->url;
      $id_hotel = $account->id_hotel;
      $total_grobal = $account->total_grobal;

      $adulte10 = $account->adulte1;





      $stmt1 = $conn->prepare('SELECT * FROM hotel WHERE id_hotel =:id_hotel');
      $stmt1->bindValue('id_hotel', $id_hotel);
      $stmt1->execute();
      $account1 = $stmt1->fetch(PDO::FETCH_OBJ);
      $hotel = $account1->hotel;


      $adulte20 = $account->adulte2;


      $adulte30 = $account->adulte3;


      $adulte40 = $account->adulte4;


      $enfant10 = $account->enfant1;


      $enfant20 = $account->enfant2;

      $bebe10 = $account->bebe1;

    }
  }

}







if (isset($_POST['reservation'])) {


  if ($_POST['securite'] != $_POST['code_securite']) {
    echo "<script type='text/javascript'>
                alert('CODE DE SECURITE INCORECTE');
              </script> ";
    echo "<meta http-equiv='refresh' content='0;url=reservation.php?xx=$xx#securite'/>";
  } else {


    if ($_POST['id_prest1'] == "") {
      $id_prest1 = 0;
    } else {
      $id_prest1 = $_POST['id_prest1'];
    }

    if ($_POST['id_prest2'] == "") {
      $id_prest2 = 0;
    } else {
      $id_prest2 = $_POST['id_prest2'];
    }

    if ($_POST['id_prest3'] == "") {
      $id_prest3 = 0;
    } else {
      $id_prest3 = $_POST['id_prest3'];
    }


    if ($_POST['id_prest4'] == "") {
      $id_prest4 = 0;
    } else {
      $id_prest4 = $_POST['id_prest4'];
    }

    if ($_POST['id_prest5'] == "") {
      $id_prest5 = 0;
    } else {
      $id_prest5 = $_POST['id_prest5'];
    }


    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $stmt5 = $conn->prepare("insert into `reservation_info`(`id_reservation_valeur`, `titre_participant_1`, `nom_participant_1`, `prenom_participant_1`, `nationalite_participant_1`, `assurance_1`, `titre_participant_2`, `nom_participant_2`, `prenom_participant_2`, `nationalite_participant_2`, `assurance_2`, `titre_participant_3`, `nom_participant_3`, `prenom_participant_3`, `nationalite_participant_3`, `assurance_3`, `titre_participant_4`, `nom_participant_4`, `prenom_participant_4`, `nationalite_participant_4`, `assurance_4`, `titre_participant_enfant_1`, `nom_participant_enfant_1`, `prenom_participant_enfant_1`, `date_naissance_participant_enfant_1`, `nationalite_participant_enfant_1`, `assurance_enfant_1`, `titre_participant_enfant_2`, `nom_participant_enfant_2`, `prenom_participant_enfant_2`, `date_naissance_participant_enfant_2`, `nationalite_participant_enfant_2`, `assurance_enfant_2`, `titre_participant_bebe_1`, `nom_participant_bebe_1`, `prenom_participant_bebe_1`, `date_naissance_participant_bebe_1`, `nationalite_participant_bebe_1`, `assurance_bebe_1`, `titre_participant_bebe_2`, `nom_participant_bebe_2`, `prenom_participant_bebe_2`, `date_naissance_participant_bebe_2`, `nationalite_participant_bebe_2`, `assurance_bebe_2`, `titre_coordonnee`, `nom`, `prenom`, `rue`, `npa`, `lieu`, `pays`, `email`, `reemail`, `tel`, `paiement`, `cgcv`, `document`, `newsletter`, `prix_chambre_a1`, `prix_chambre_a2`, `prix_chambre_a3`, `prix_chambre_a4`, `prix_chambre_e1`, `prix_chambre_e2`, `prix_chambre_b1`, `prix_transfert_a1`, `prix_transfert_a2`, `prix_transfert_a3`, `prix_transfert_a4`, `prix_transfert_e1`, `prix_transfert_e2`, `prix_transfert_b1`, `prix_visa_a1`, `prix_visa_a2`, `prix_visa_a3`, `prix_visa_a4`, `prix_visa_e1`, `prix_visa_e2`, `prix_visa_b1`, `prix_option_a1`, `prix_option_a2`, `prix_option_a3`, `prix_option_a4`, `prix_option_e1`, `prix_option_e2`, `prix_option_b1`, `prix_repas_a1`, `prix_repas_a2`, `prix_repas_a3`, `prix_repas_a4`, `prix_repas_e1`, `prix_repas_e2`, `prix_repas_b1`, `prix_tour_a1`, `prix_tour_a2`, `prix_tour_a3`, `prix_tour_a4`, `prix_tour_e1`, `prix_tour_e2`, `prix_tour_b1`, `prix_total_a1`, `prix_total_a2`, `prix_total_a3`, `prix_total_a4`, `prix_total_e1`, `prix_total_e2`, `prix_total_b1`, `prix_total_total`, `id_prest1`, `id_prest2`, `id_prest3`, `id_prest4`, `id_prest5`, `id_prix_repas_obligatoire`, `option_autre_transfert`, `option_autre_transfert_enfant`, `option_autre_transfert_bebe`, `id_option_autre_transfert`, `id_option_autre_transfert_enfant`, `id_option_autre_transfert_bebe`, `id_prix_transfert_obligatoire`) VALUE ( :id_reservation_valeur, :titre_participant_1, :nom_participant_1, :prenom_participant_1, :nationalite_participant_1, :assurance_1, :titre_participant_2, :nom_participant_2, :prenom_participant_2, :nationalite_participant_2, :assurance_2, :titre_participant_3, :nom_participant_3, :prenom_participant_3,  :nationalite_participant_3, :assurance_3, :titre_participant_4, :nom_participant_4, :prenom_participant_4, :nationalite_participant_4, :assurance_4, :titre_participant_enfant_1, :nom_participant_enfant_1, :prenom_participant_enfant_1, :date_naissance_participant_enfant_1, :nationalite_participant_enfant_1, :assurance_enfant_1, :titre_participant_enfant_2, :nom_participant_enfant_2, :prenom_participant_enfant_2, :date_naissance_participant_enfant_2, :nationalite_participant_enfant_2, :assurance_enfant_2, :titre_participant_bebe_1, :nom_participant_bebe_1, :prenom_participant_bebe_1, :date_naissance_participant_bebe_1, :nationalite_participant_bebe_1, :assurance_bebe_1, :titre_participant_bebe_2, :nom_participant_bebe_2, :prenom_participant_bebe_2, :date_naissance_participant_bebe_2, :nationalite_participant_bebe_2, :assurance_bebe_2, :titre_coordonnee, :nom, :prenom, :rue, :npa, :lieu, :pays, :email, :reemail, :tel, :paiement, :cgcv, :document, :newsletter, :prix_chambre_a1, :prix_chambre_a2, :prix_chambre_a3, :prix_chambre_a4, :prix_chambre_e1, :prix_chambre_e2, :prix_chambre_b1, :prix_transfert_a1, :prix_transfert_a2, :prix_transfert_a3, :prix_transfert_a4, :prix_transfert_e1, :prix_transfert_e2, :prix_transfert_b1, :prix_visa_a1, :prix_visa_a2, :prix_visa_a3, :prix_visa_a4, :prix_visa_e1, :prix_visa_e2, :prix_visa_b1, :prix_option_a1, :prix_option_a2, :prix_option_a3, :prix_option_a4, :prix_option_e1, :prix_option_e2, :prix_option_b1, :prix_repas_a1, :prix_repas_a2, :prix_repas_a3, :prix_repas_a4, :prix_repas_e1, :prix_repas_e2, :prix_repas_b1, :prix_tour_a1, :prix_tour_a2, :prix_tour_a3, :prix_tour_a4, :prix_tour_e1, :prix_tour_e2, :prix_tour_b1, :prix_total_a1, :prix_total_a2, :prix_total_a3, :prix_total_a4, :prix_total_e1, :prix_total_e2, :prix_total_b1, :prix_total_total, :id_prest1, :id_prest2, :id_prest3, :id_prest4, :id_prest5, :id_prix_repas_obligatoire, :option_autre_transfert, :option_autre_transfert_enfant, :option_autre_transfert_bebe, :id_option_autre_transfert, :id_option_autre_transfert_enfant, :id_option_autre_transfert_bebe, :id_prix_transfert_obligatoire)");
    $stmt5->bindValue('id_reservation_valeur', addslashes($_POST['id_reservation_valeur']));
    $stmt5->bindValue('titre_participant_1', addslashes($_POST['titre_participant_1']));
    $stmt5->bindValue('nom_participant_1', addslashes($_POST['nom_participant_1']));
    $stmt5->bindValue('prenom_participant_1', addslashes($_POST['prenom_participant_1']));
    $stmt5->bindValue('nationalite_participant_1', addslashes($_POST['nationalite_participant_1']));
    $stmt5->bindValue('assurance_1', addslashes($_POST['assurance_1']));
    $stmt5->bindValue('titre_participant_2', addslashes($_POST['titre_participant_2']));
    $stmt5->bindValue('nom_participant_2', addslashes($_POST['nom_participant_2']));
    $stmt5->bindValue('prenom_participant_2', addslashes($_POST['prenom_participant_2']));
    $stmt5->bindValue('nationalite_participant_2', addslashes($_POST['nationalite_participant_2']));
    $stmt5->bindValue('assurance_2', addslashes($_POST['assurance_2']));
    $stmt5->bindValue('titre_participant_3', addslashes($_POST['titre_participant_3']));
    $stmt5->bindValue('nom_participant_3', addslashes($_POST['nom_participant_3']));
    $stmt5->bindValue('prenom_participant_3', addslashes($_POST['prenom_participant_3']));
    $stmt5->bindValue('nationalite_participant_3', addslashes($_POST['nationalite_participant_3']));
    $stmt5->bindValue('assurance_3', addslashes($_POST['assurance_3']));
    $stmt5->bindValue('titre_participant_4', addslashes($_POST['titre_participant_4']));
    $stmt5->bindValue('nom_participant_4', addslashes($_POST['nom_participant_4']));
    $stmt5->bindValue('prenom_participant_4', addslashes($_POST['prenom_participant_4']));
    $stmt5->bindValue('nationalite_participant_4', addslashes($_POST['nationalite_participant_4']));
    $stmt5->bindValue('assurance_4', addslashes($_POST['assurance_4']));
    $stmt5->bindValue('titre_participant_enfant_1', addslashes($_POST['titre_participant_enfant_1']));
    $stmt5->bindValue('nom_participant_enfant_1', addslashes($_POST['nom_participant_enfant_1']));
    $stmt5->bindValue('prenom_participant_enfant_1', addslashes($_POST['prenom_participant_enfant_1']));
    $stmt5->bindValue('date_naissance_participant_enfant_1', addslashes($_POST['date_naissance_participant_enfant_1']));
    $stmt5->bindValue('nationalite_participant_enfant_1', addslashes($_POST['nationalite_participant_enfant_1']));
    $stmt5->bindValue('assurance_enfant_1', addslashes($_POST['assurance_enfant_1']));
    $stmt5->bindValue('titre_participant_enfant_2', addslashes($_POST['titre_participant_enfant_2']));
    $stmt5->bindValue('nom_participant_enfant_2', addslashes($_POST['nom_participant_enfant_2']));
    $stmt5->bindValue('prenom_participant_enfant_2', addslashes($_POST['prenom_participant_enfant_2']));
    $stmt5->bindValue('date_naissance_participant_enfant_2', addslashes($_POST['date_naissance_participant_enfant_2']));
    $stmt5->bindValue('nationalite_participant_enfant_2', addslashes($_POST['nationalite_participant_enfant_2']));
    $stmt5->bindValue('assurance_enfant_2', addslashes($_POST['assurance_enfant_2']));
    $stmt5->bindValue('titre_participant_bebe_1', addslashes($_POST['titre_participant_bebe_1']));
    $stmt5->bindValue('nom_participant_bebe_1', addslashes($_POST['nom_participant_bebe_1']));
    $stmt5->bindValue('prenom_participant_bebe_1', addslashes($_POST['prenom_participant_bebe_1']));
    $stmt5->bindValue('date_naissance_participant_bebe_1', addslashes($_POST['date_naissance_participant_bebe_1']));
    $stmt5->bindValue('nationalite_participant_bebe_1', addslashes($_POST['nationalite_participant_bebe_1']));
    $stmt5->bindValue('assurance_bebe_1', addslashes($_POST['assurance_bebe_1']));
    $stmt5->bindValue('titre_participant_bebe_2', addslashes($_POST['titre_participant_bebe_2']));
    $stmt5->bindValue('nom_participant_bebe_2', addslashes($_POST['nom_participant_bebe_2']));
    $stmt5->bindValue('prenom_participant_bebe_2', addslashes($_POST['prenom_participant_bebe_2']));
    $stmt5->bindValue('date_naissance_participant_bebe_2', addslashes($_POST['date_naissance_participant_bebe_2']));
    $stmt5->bindValue('nationalite_participant_bebe_2', addslashes($_POST['nationalite_participant_bebe_2']));
    $stmt5->bindValue('assurance_bebe_2', addslashes($_POST['assurance_bebe_2']));
    $stmt5->bindValue('titre_coordonnee', addslashes($_POST['titre_coordonnee']));
    $stmt5->bindValue('nom', addslashes($_POST['nom']));
    $stmt5->bindValue('prenom', addslashes($_POST['prenom']));
    $stmt5->bindValue('rue', addslashes($_POST['rue'] . " - " . addslashes($_POST['num_rue'])));
    $stmt5->bindValue('npa', addslashes($_POST['npa']));
    $stmt5->bindValue('lieu', addslashes($_POST['lieu']));
    $stmt5->bindValue('pays', addslashes($_POST['pays']));
    $stmt5->bindValue('email', addslashes($_POST['email']));
    $stmt5->bindValue('reemail', addslashes($_POST['reemail']));
    $stmt5->bindValue('tel', addslashes($_POST['tel']));
    $stmt5->bindValue('paiement', addslashes($_POST['paiement']));
    $stmt5->bindValue('cgcv', addslashes($_POST['cgcv']));
    $stmt5->bindValue('document', addslashes($_POST['document']));
    $stmt5->bindValue('newsletter', addslashes($_POST['newsletter']));
    $stmt5->bindValue('prix_chambre_a1', addslashes($_POST['prix_chambre_a1']));
    $stmt5->bindValue('prix_chambre_a2', addslashes($_POST['prix_chambre_a2']));
    $stmt5->bindValue('prix_chambre_a3', addslashes($_POST['prix_chambre_a3']));
    $stmt5->bindValue('prix_chambre_a4', addslashes($_POST['prix_chambre_a4']));
    $stmt5->bindValue('prix_chambre_e1', addslashes($_POST['prix_chambre_e1']));
    $stmt5->bindValue('prix_chambre_e2', addslashes($_POST['prix_chambre_e2']));
    $stmt5->bindValue('prix_chambre_b1', addslashes($_POST['prix_chambre_b1']));
    $stmt5->bindValue('prix_transfert_a1', addslashes($_POST['prix_transfert_a1']));
    $stmt5->bindValue('prix_transfert_a2', addslashes($_POST['prix_transfert_a2']));
    $stmt5->bindValue('prix_transfert_a3', addslashes($_POST['prix_transfert_a3']));
    $stmt5->bindValue('prix_transfert_a4', addslashes($_POST['prix_transfert_a4']));
    $stmt5->bindValue('prix_transfert_e1', addslashes($_POST['prix_transfert_e1']));
    $stmt5->bindValue('prix_transfert_e2', addslashes($_POST['prix_transfert_e2']));
    $stmt5->bindValue('prix_transfert_b1', addslashes($_POST['prix_transfert_b1']));
    $stmt5->bindValue('prix_visa_a1', addslashes($_POST['prix_visa_a1']));
    $stmt5->bindValue('prix_visa_a2', addslashes($_POST['prix_visa_a2']));
    $stmt5->bindValue('prix_visa_a3', addslashes($_POST['prix_visa_a3']));
    $stmt5->bindValue('prix_visa_a4', addslashes($_POST['prix_visa_a4']));
    $stmt5->bindValue('prix_visa_e1', addslashes($_POST['prix_visa_e1']));
    $stmt5->bindValue('prix_visa_e2', addslashes($_POST['prix_visa_e2']));
    $stmt5->bindValue('prix_visa_b1', addslashes($_POST['prix_visa_b1']));
    $stmt5->bindValue('prix_option_a1', addslashes($_POST['prix_option_a1']));
    $stmt5->bindValue('prix_option_a2', addslashes($_POST['prix_option_a2']));
    $stmt5->bindValue('prix_option_a3', addslashes($_POST['prix_option_a3']));
    $stmt5->bindValue('prix_option_a4', addslashes($_POST['prix_option_a4']));
    $stmt5->bindValue('prix_option_e1', addslashes($_POST['prix_option_e1']));
    $stmt5->bindValue('prix_option_e2', addslashes($_POST['prix_option_e2']));
    $stmt5->bindValue('prix_option_b1', addslashes($_POST['prix_option_b1']));
    $stmt5->bindValue('prix_repas_a1', addslashes($_POST['prix_repas_a1']));
    $stmt5->bindValue('prix_repas_a2', addslashes($_POST['prix_repas_a2']));
    $stmt5->bindValue('prix_repas_a3', addslashes($_POST['prix_repas_a3']));
    $stmt5->bindValue('prix_repas_a4', addslashes($_POST['prix_repas_a4']));
    $stmt5->bindValue('prix_repas_e1', addslashes($_POST['prix_repas_e1']));
    $stmt5->bindValue('prix_repas_e2', addslashes($_POST['prix_repas_e2']));
    $stmt5->bindValue('prix_repas_b1', addslashes($_POST['prix_repas_b1']));
    $stmt5->bindValue('prix_tour_a1', addslashes($_POST['prix_tour_a1']));
    $stmt5->bindValue('prix_tour_a2', addslashes($_POST['prix_tour_a2']));
    $stmt5->bindValue('prix_tour_a3', addslashes($_POST['prix_tour_a3']));
    $stmt5->bindValue('prix_tour_a4', addslashes($_POST['prix_tour_a4']));
    $stmt5->bindValue('prix_tour_e1', addslashes($_POST['prix_tour_e1']));
    $stmt5->bindValue('prix_tour_e2', addslashes($_POST['prix_tour_e2']));
    $stmt5->bindValue('prix_tour_b1', addslashes($_POST['prix_tour_b1']));
    $stmt5->bindValue('prix_total_a1', addslashes($_POST['prix_total_a1']));
    $stmt5->bindValue('prix_total_a2', addslashes($_POST['prix_total_a2']));
    $stmt5->bindValue('prix_total_a3', addslashes($_POST['prix_total_a3']));
    $stmt5->bindValue('prix_total_a4', addslashes($_POST['prix_total_a4']));
    $stmt5->bindValue('prix_total_e1', addslashes($_POST['prix_total_e1']));
    $stmt5->bindValue('prix_total_e2', addslashes($_POST['prix_total_e2']));
    $stmt5->bindValue('prix_total_b1', addslashes($_POST['prix_total_b1']));
    $stmt5->bindValue('prix_total_total', addslashes($_POST['prix_total_total']));
    $stmt5->bindValue('id_prest1', $id_prest1);
    $stmt5->bindValue('id_prest2', $id_prest2);
    $stmt5->bindValue('id_prest3', $id_prest3);
    $stmt5->bindValue('id_prest4', $id_prest4);
    $stmt5->bindValue('id_prest5', $id_prest5);
    $stmt5->bindValue('id_prix_repas_obligatoire', $_POST['id_prix_repas_obligatoire']);
    $stmt5->bindValue('option_autre_transfert', $_POST['option_autre_transfert']);
    $stmt5->bindValue('option_autre_transfert_enfant', $_POST['option_autre_transfert_enfant']);
    $stmt5->bindValue('option_autre_transfert_bebe', $_POST['option_autre_transfert_bebe']);
    $stmt5->bindValue('id_option_autre_transfert', $_POST['id_option_autre_transfert']);
    $stmt5->bindValue('id_option_autre_transfert_enfant', $_POST['id_option_autre_transfert_enfant']);
    $stmt5->bindValue('id_option_autre_transfert_bebe', $_POST['id_option_autre_transfert_bebe']);
    $stmt5->bindValue('id_prix_transfert_obligatoire', $_POST['id_prix_transfert_obligatoire']);
    $stmt5->execute();

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "\nPDO::errorInfo():\n";
    print_r($conn->errorInfo());




  }
}




// --------------------------- DEVIS ---------------------------------//
if (isset($_POST['devis'])) {


  if ($_POST['securite'] != $_POST['code_securite']) {
    echo "<script type='text/javascript'>
                alert('CODE DE SECURITE INCORECTE');
              </script> ";
    echo "<meta http-equiv='refresh' content='0;url=reservation.php?xx=$xx#securite'/>";
  } else {


    if ($_POST['id_prest1'] == "") {
      $id_prest1 = 0;
    } else {
      $id_prest1 = $_POST['id_prest1'];
    }

    if ($_POST['id_prest2'] == "") {
      $id_prest2 = 0;
    } else {
      $id_prest2 = $_POST['id_prest2'];
    }

    if ($_POST['id_prest3'] == "") {
      $id_prest3 = 0;
    } else {
      $id_prest3 = $_POST['id_prest3'];
    }


    if ($_POST['id_prest4'] == "") {
      $id_prest4 = 0;
    } else {
      $id_prest4 = $_POST['id_prest4'];
    }

    if ($_POST['id_prest5'] == "") {
      $id_prest5 = 0;
    } else {
      $id_prest5 = $_POST['id_prest5'];
    }

    $prix_total_a1 = str_replace(' ', '', $_POST['prix_total_a1']);
    $prix_total_a2 = str_replace(' ', '', $_POST['prix_total_a2']);
    $prix_total_a3 = str_replace(' ', '', $_POST['prix_total_a3']);
    $prix_total_a4 = str_replace(' ', '', $_POST['prix_total_a4']);





    if ($_POST['adulte'] >= "1") {
      $stmt34 = $conn->prepare('SELECT * FROM assurance');

      $stmt34->execute();
      while ($account34 = $stmt34->fetch(PDO::FETCH_OBJ)) {

        if ($account34->prix_assurance == 0) {
          if ($_POST['prix_total_a1'] != '') {

            $prix_assurance = ceil($prix_total_a1 * $account34->pourcentage / 100);

            if ($prix_assurance == $_POST['assurance_1']) {
              $titre_ass_1 = $account34->titre_assurance;
            }
          }


        } else {
          $prix_assurance = $account34->prix_assurance;

          if ($prix_assurance == $_POST['assurance_1']) {
            $titre_ass_1 = $account34->titre_assurance;
          }

        }
      }
    }



    if ($_POST['adulte'] >= "2") {
      $stmt34 = $conn->prepare('SELECT * FROM assurance');

      $stmt34->execute();
      while ($account34 = $stmt34->fetch(PDO::FETCH_OBJ)) {

        if ($account34->prix_assurance == 0) {
          if ($_POST['prix_total_a2'] != '') {
            $prix_assurance = ceil($prix_total_a2 * $account34->pourcentage / 100);

            if ($prix_assurance == $_POST['assurance_2']) {
              $titre_ass_2 = $account34->titre_assurance;
            }
          }

        } else {
          $prix_assurance = $account34->prix_assurance;

          if ($prix_assurance == $_POST['assurance_2']) {
            $titre_ass_2 = $account34->titre_assurance;
          }

        }
      }
    }

    if ($_POST['adulte'] >= "3") {
      $stmt34 = $conn->prepare('SELECT * FROM assurance');

      $stmt34->execute();
      while ($account34 = $stmt34->fetch(PDO::FETCH_OBJ)) {

        if ($account34->prix_assurance == 0) {
          if ($_POST['prix_total_a3'] != '') {
            $prix_assurance = ceil($prix_total_a3 * $account34->pourcentage / 100);

            if ($prix_assurance == $_POST['assurance_3']) {
              $titre_ass_3 = $account34->titre_assurance;
            }
          }

        } else {
          $prix_assurance = $account34->prix_assurance;

          if ($prix_assurance == $_POST['assurance_3']) {
            $titre_ass_3 = $account34->titre_assurance;
          }

        }
      }
    }


    if ($_POST['adulte'] >= "4") {
      $stmt34 = $conn->prepare('SELECT * FROM assurance');

      $stmt34->execute();
      while ($account34 = $stmt34->fetch(PDO::FETCH_OBJ)) {

        if ($account34->prix_assurance == 0) {
          if ($_POST['prix_total_a4'] != '') {
            $prix_assurance = ceil($prix_total_a4 * $account34->pourcentage / 100);

            if ($prix_assurance == $_POST['assurance_4']) {
              $titre_ass_4 = $account34->titre_assurance;
            }
          }

        } else {
          $prix_assurance = $account34->prix_assurance;

          if ($prix_assurance == $_POST['assurance_3']) {
            $titre_ass_4 = $account34->titre_assurance;
          }

        }
      }
    }









    if (!isset($titre_ass_1)) {
      $titre_ass_1 = '';
    }
    if (!isset($titre_ass_2)) {
      $titre_ass_2 = '';
    }
    if (!isset($titre_ass_3)) {
      $titre_ass_3 = '';
    }
    if (!isset($titre_ass_4)) {
      $titre_ass_4 = '';
    }



    //echo $id_prest1.' '.$id_prest2.' '.$id_prest3.' '.$id_prest4.' '.$id_prest5;


    $date1 = date('Y-m-d'); // Date du jour
    setlocale(LC_TIME, "fr_FR");
    $date_facture = (strftime("%d %B %G", strtotime($date1)));

    $date_facture = utf8_encode($date_facture);

    if ($_POST['document'] == 0) {
      echo "<script type='text/javascript'>alert('Veuillez certifié que tous les noms et prénoms de participants sont correctement orthographiés selon vos passeports et pour les enfants la date de naissance pour les enfants sont juste.');</script>";
    } else {

      $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
      $stmt5 = $conn->prepare("insert into `reservation_info` (`id_reservation_valeur`, `titre_participant_1`, `nom_participant_1`, `prenom_participant_1`, `nationalite_participant_1`, `assurance_1`, `titre_participant_2`, `nom_participant_2`, `prenom_participant_2`, `nationalite_participant_2`, `assurance_2`, `titre_participant_3`, `nom_participant_3`, `prenom_participant_3`, `nationalite_participant_3`, `assurance_3`, `titre_participant_4`, `nom_participant_4`, `prenom_participant_4`, `nationalite_participant_4`, `assurance_4`, `titre_participant_enfant_1`, `nom_participant_enfant_1`, `prenom_participant_enfant_1`, `date_naissance_participant_enfant_1`, `nationalite_participant_enfant_1`, `assurance_enfant_1`, `titre_participant_enfant_2`, `nom_participant_enfant_2`, `prenom_participant_enfant_2`, `date_naissance_participant_enfant_2`, `nationalite_participant_enfant_2`, `assurance_enfant_2`, `titre_participant_bebe_1`, `nom_participant_bebe_1`, `prenom_participant_bebe_1`, `date_naissance_participant_bebe_1`, `nationalite_participant_bebe_1`, `assurance_bebe_1`, `titre_participant_bebe_2`, `nom_participant_bebe_2`, `prenom_participant_bebe_2`, `date_naissance_participant_bebe_2`, `nationalite_participant_bebe_2`, `assurance_bebe_2`, `titre_coordonnee`, `nom`, `prenom`, `rue`, `npa`, `lieu`, `pays`, `email`, `reemail`, `tel`, `paiement`, `cgcv`, `document`, `newsletter`, `prix_chambre_a1`, `prix_chambre_a2`, `prix_chambre_a3`, `prix_chambre_a4`, `prix_chambre_e1`, `prix_chambre_e2`, `prix_chambre_b1`, `prix_transfert_a1`, `prix_transfert_a2`, `prix_transfert_a3`, `prix_transfert_a4`, `prix_transfert_e1`, `prix_transfert_e2`, `prix_transfert_b1`, `prix_visa_a1`, `prix_visa_a2`, `prix_visa_a3`, `prix_visa_a4`, `prix_visa_e1`, `prix_visa_e2`, `prix_visa_b1`, `prix_option_a1`, `prix_option_a2`, `prix_option_a3`, `prix_option_a4`, `prix_option_e1`, `prix_option_e2`, `prix_option_b1`, `prix_repas_a1`, `prix_repas_a2`, `prix_repas_a3`, `prix_repas_a4`, `prix_repas_e1`, `prix_repas_e2`, `prix_repas_b1`, `prix_tour_a1`, `prix_tour_a2`, `prix_tour_a3`, `prix_tour_a4`, `prix_tour_e1`, `prix_tour_e2`, `prix_tour_b1`, `prix_total_a1`, `prix_total_a2`, `prix_total_a3`, `prix_total_a4`, `prix_total_e1`, `prix_total_e2`, `prix_total_b1`, `prix_total_total`, `id_prest1`, `id_prest2`, `id_prest3`, `id_prest4`, `id_prest5`, `id_prix_repas_obligatoire`, `option_autre_transfert`, `option_autre_transfert_enfant`, `option_autre_transfert_bebe`, `id_option_autre_transfert`, `id_option_autre_transfert_enfant`, `id_option_autre_transfert_bebe`, `id_prix_transfert_obligatoire`, `devis`, `date_creation`, `status`, `num_rue`, `titre_ass_1`, `titre_ass_2`, `titre_ass_3`, `titre_ass_4`, `id_assurance_1`, `id_assurance_2`, `id_assurance_3`, `id_assurance_4`, `check_assurance_adulte_1`, `check_assurance_adulte_2`, `check_assurance_adulte_3`, `check_assurance_adulte_4`, `check_repas_adulte_101`, `check_repas_enfant_101`, `check_repas_bebe_101`, `check_prestation_adulte_101`, `check_prestation_enfant_101`, `check_prestation_bebe_101`, `check_excursion_adulte_101`, `check_excursion_enfant_101`, `check_excursion_bebe_101`, `check_excursion_adulte_201`, `check_excursion_enfant_201`, `check_excursion_bebe_201`, `remarque`) VALUE ( :id_reservation_valeur, :titre_participant_1, :nom_participant_1, :prenom_participant_1, :nationalite_participant_1, :assurance_1, :titre_participant_2, :nom_participant_2, :prenom_participant_2, :nationalite_participant_2, :assurance_2, :titre_participant_3, :nom_participant_3, :prenom_participant_3,  :nationalite_participant_3, :assurance_3, :titre_participant_4, :nom_participant_4, :prenom_participant_4, :nationalite_participant_4, :assurance_4, :titre_participant_enfant_1, :nom_participant_enfant_1, :prenom_participant_enfant_1, :date_naissance_participant_enfant_1, :nationalite_participant_enfant_1, :assurance_enfant_1, :titre_participant_enfant_2, :nom_participant_enfant_2, :prenom_participant_enfant_2, :date_naissance_participant_enfant_2, :nationalite_participant_enfant_2, :assurance_enfant_2, :titre_participant_bebe_1, :nom_participant_bebe_1, :prenom_participant_bebe_1, :date_naissance_participant_bebe_1, :nationalite_participant_bebe_1, :assurance_bebe_1, :titre_participant_bebe_2, :nom_participant_bebe_2, :prenom_participant_bebe_2, :date_naissance_participant_bebe_2, :nationalite_participant_bebe_2, :assurance_bebe_2, :titre_coordonnee, :nom, :prenom, :rue, :npa, :lieu, :pays, :email, :reemail, :tel, :paiement, :cgcv, :document, :newsletter, :prix_chambre_a1, :prix_chambre_a2, :prix_chambre_a3, :prix_chambre_a4, :prix_chambre_e1, :prix_chambre_e2, :prix_chambre_b1, :prix_transfert_a1, :prix_transfert_a2, :prix_transfert_a3, :prix_transfert_a4, :prix_transfert_e1, :prix_transfert_e2, :prix_transfert_b1, :prix_visa_a1, :prix_visa_a2, :prix_visa_a3, :prix_visa_a4, :prix_visa_e1, :prix_visa_e2, :prix_visa_b1, :prix_option_a1, :prix_option_a2, :prix_option_a3, :prix_option_a4, :prix_option_e1, :prix_option_e2, :prix_option_b1, :prix_repas_a1, :prix_repas_a2, :prix_repas_a3, :prix_repas_a4, :prix_repas_e1, :prix_repas_e2, :prix_repas_b1, :prix_tour_a1, :prix_tour_a2, :prix_tour_a3, :prix_tour_a4, :prix_tour_e1, :prix_tour_e2, :prix_tour_b1, :prix_total_a1, :prix_total_a2, :prix_total_a3, :prix_total_a4, :prix_total_e1, :prix_total_e2, :prix_total_b1, :prix_total_total, :id_prest1, :id_prest2, :id_prest3, :id_prest4, :id_prest5, :id_prix_repas_obligatoire, :option_autre_transfert, :option_autre_transfert_enfant, :option_autre_transfert_bebe, :id_option_autre_transfert, :id_option_autre_transfert_enfant, :id_option_autre_transfert_bebe, :id_prix_transfert_obligatoire, :devis, :date_creation, :status, :num_rue, :titre_ass_1, :titre_ass_2, :titre_ass_3, :titre_ass_4, :id_assurance_1, :id_assurance_2, :id_assurance_3, :id_assurance_4, :check_assurance_adulte_1, :check_assurance_adulte_2, :check_assurance_adulte_3, :check_assurance_adulte_4, :check_repas_adulte_101, :check_repas_enfant_101, :check_repas_bebe_101, :check_prestation_adulte_101, :check_prestation_enfant_101, :check_prestation_bebe_101, :check_excursion_adulte_101, :check_excursion_enfant_101, :check_excursion_bebe_101, :check_excursion_adulte_201, :check_excursion_enfant_201, :check_excursion_bebe_201, :remarque)");
      $stmt5->bindValue('id_reservation_valeur', addslashes($_POST['id_reservation_valeur']));
      $stmt5->bindValue('titre_participant_1', addslashes($_POST['titre_participant_1']));
      $stmt5->bindValue('nom_participant_1', addslashes($_POST['nom_participant_1']));
      $stmt5->bindValue('prenom_participant_1', addslashes($_POST['prenom_participant_1']));
      $stmt5->bindValue('nationalite_participant_1', addslashes($_POST['nationalite_participant_1']));
      $stmt5->bindValue('assurance_1', addslashes($_POST['assurance_1']));
      $stmt5->bindValue('titre_participant_2', addslashes($_POST['titre_participant_2']));
      $stmt5->bindValue('nom_participant_2', addslashes($_POST['nom_participant_2']));
      $stmt5->bindValue('prenom_participant_2', addslashes($_POST['prenom_participant_2']));
      $stmt5->bindValue('nationalite_participant_2', addslashes($_POST['nationalite_participant_2']));
      $stmt5->bindValue('assurance_2', addslashes($_POST['assurance_2']));
      $stmt5->bindValue('titre_participant_3', addslashes($_POST['titre_participant_3']));
      $stmt5->bindValue('nom_participant_3', addslashes($_POST['nom_participant_3']));
      $stmt5->bindValue('prenom_participant_3', addslashes($_POST['prenom_participant_3']));
      $stmt5->bindValue('nationalite_participant_3', addslashes($_POST['nationalite_participant_3']));
      $stmt5->bindValue('assurance_3', addslashes($_POST['assurance_3']));
      $stmt5->bindValue('titre_participant_4', addslashes($_POST['titre_participant_4']));
      $stmt5->bindValue('nom_participant_4', addslashes($_POST['nom_participant_4']));
      $stmt5->bindValue('prenom_participant_4', addslashes($_POST['prenom_participant_4']));
      $stmt5->bindValue('nationalite_participant_4', addslashes($_POST['nationalite_participant_4']));
      $stmt5->bindValue('assurance_4', addslashes($_POST['assurance_4']));
      $stmt5->bindValue('titre_participant_enfant_1', addslashes($_POST['titre_participant_enfant_1']));
      $stmt5->bindValue('nom_participant_enfant_1', addslashes($_POST['nom_participant_enfant_1']));
      $stmt5->bindValue('prenom_participant_enfant_1', addslashes($_POST['prenom_participant_enfant_1']));
      $stmt5->bindValue('date_naissance_participant_enfant_1', addslashes($_POST['date_naissance_participant_enfant_1']));
      $stmt5->bindValue('nationalite_participant_enfant_1', addslashes($_POST['nationalite_participant_enfant_1']));
      $stmt5->bindValue('assurance_enfant_1', '');
      $stmt5->bindValue('titre_participant_enfant_2', addslashes($_POST['titre_participant_enfant_2']));
      $stmt5->bindValue('nom_participant_enfant_2', addslashes($_POST['nom_participant_enfant_2']));
      $stmt5->bindValue('prenom_participant_enfant_2', addslashes($_POST['prenom_participant_enfant_2']));
      $stmt5->bindValue('date_naissance_participant_enfant_2', addslashes($_POST['date_naissance_participant_enfant_2']));
      $stmt5->bindValue('nationalite_participant_enfant_2', addslashes($_POST['nationalite_participant_enfant_2']));
      $stmt5->bindValue('assurance_enfant_2', '');
      $stmt5->bindValue('titre_participant_bebe_1', addslashes($_POST['titre_participant_bebe_1']));
      $stmt5->bindValue('nom_participant_bebe_1', addslashes($_POST['nom_participant_bebe_1']));
      $stmt5->bindValue('prenom_participant_bebe_1', addslashes($_POST['prenom_participant_bebe_1']));
      $stmt5->bindValue('date_naissance_participant_bebe_1', addslashes($_POST['date_naissance_participant_bebe_1']));
      $stmt5->bindValue('nationalite_participant_bebe_1', addslashes($_POST['nationalite_participant_bebe_1']));
      $stmt5->bindValue('assurance_bebe_1', '');
      $stmt5->bindValue('titre_participant_bebe_2', addslashes($_POST['titre_participant_bebe_2']));
      $stmt5->bindValue('nom_participant_bebe_2', addslashes($_POST['nom_participant_bebe_2']));
      $stmt5->bindValue('prenom_participant_bebe_2', addslashes($_POST['prenom_participant_bebe_2']));
      $stmt5->bindValue('date_naissance_participant_bebe_2', addslashes($_POST['date_naissance_participant_bebe_2']));
      $stmt5->bindValue('nationalite_participant_bebe_2', addslashes($_POST['nationalite_participant_bebe_2']));
      $stmt5->bindValue('assurance_bebe_2', addslashes($_POST['assurance_bebe_2']));
      $stmt5->bindValue('titre_coordonnee', addslashes($_POST['titre_coordonnee']));
      $stmt5->bindValue('nom', addslashes($_POST['nom']));
      $stmt5->bindValue('prenom', addslashes($_POST['prenom']));
      $stmt5->bindValue('rue', addslashes($_POST['rue']));
      $stmt5->bindValue('npa', addslashes($_POST['npa']));
      $stmt5->bindValue('lieu', addslashes($_POST['lieu']));
      $stmt5->bindValue('pays', addslashes($_POST['pays']));
      $stmt5->bindValue('email', addslashes($_POST['email']));
      $stmt5->bindValue('reemail', addslashes($_POST['reemail']));
      $stmt5->bindValue('tel', addslashes($_POST['tel']));
      $stmt5->bindValue('paiement', '1');
      $stmt5->bindValue('cgcv', addslashes($_POST['cgcv']));
      $stmt5->bindValue('document', addslashes($_POST['document']));
      $stmt5->bindValue('newsletter', addslashes($_POST['newsletter']));
      $stmt5->bindValue('prix_chambre_a1', addslashes($_POST['prix_chambre_a1']));
      $stmt5->bindValue('prix_chambre_a2', addslashes($_POST['prix_chambre_a2']));
      $stmt5->bindValue('prix_chambre_a3', addslashes($_POST['prix_chambre_a3']));
      $stmt5->bindValue('prix_chambre_a4', addslashes($_POST['prix_chambre_a4']));
      $stmt5->bindValue('prix_chambre_e1', addslashes($_POST['prix_chambre_e1']));
      $stmt5->bindValue('prix_chambre_e2', addslashes($_POST['prix_chambre_e2']));
      $stmt5->bindValue('prix_chambre_b1', addslashes($_POST['prix_chambre_b1']));
      $stmt5->bindValue('prix_transfert_a1', addslashes($_POST['prix_transfert_a1']));
      $stmt5->bindValue('prix_transfert_a2', addslashes($_POST['prix_transfert_a2']));
      $stmt5->bindValue('prix_transfert_a3', addslashes($_POST['prix_transfert_a3']));
      $stmt5->bindValue('prix_transfert_a4', addslashes($_POST['prix_transfert_a4']));
      $stmt5->bindValue('prix_transfert_e1', addslashes($_POST['prix_transfert_e1']));
      $stmt5->bindValue('prix_transfert_e2', addslashes($_POST['prix_transfert_e2']));
      $stmt5->bindValue('prix_transfert_b1', addslashes($_POST['prix_transfert_b1']));
      $stmt5->bindValue('prix_visa_a1', addslashes($_POST['prix_visa_a1']));
      $stmt5->bindValue('prix_visa_a2', addslashes($_POST['prix_visa_a2']));
      $stmt5->bindValue('prix_visa_a3', addslashes($_POST['prix_visa_a3']));
      $stmt5->bindValue('prix_visa_a4', addslashes($_POST['prix_visa_a4']));
      $stmt5->bindValue('prix_visa_e1', addslashes($_POST['prix_visa_e1']));
      $stmt5->bindValue('prix_visa_e2', addslashes($_POST['prix_visa_e2']));
      $stmt5->bindValue('prix_visa_b1', addslashes($_POST['prix_visa_b1']));
      $stmt5->bindValue('prix_option_a1', addslashes($_POST['prix_option_a1']));
      $stmt5->bindValue('prix_option_a2', addslashes($_POST['prix_option_a2']));
      $stmt5->bindValue('prix_option_a3', addslashes($_POST['prix_option_a3']));
      $stmt5->bindValue('prix_option_a4', addslashes($_POST['prix_option_a4']));
      $stmt5->bindValue('prix_option_e1', addslashes($_POST['prix_option_e1']));
      $stmt5->bindValue('prix_option_e2', addslashes($_POST['prix_option_e2']));
      $stmt5->bindValue('prix_option_b1', addslashes($_POST['prix_option_b1']));
      $stmt5->bindValue('prix_repas_a1', addslashes($_POST['prix_repas_a1']));
      $stmt5->bindValue('prix_repas_a2', addslashes($_POST['prix_repas_a2']));
      $stmt5->bindValue('prix_repas_a3', addslashes($_POST['prix_repas_a3']));
      $stmt5->bindValue('prix_repas_a4', addslashes($_POST['prix_repas_a4']));
      $stmt5->bindValue('prix_repas_e1', addslashes($_POST['prix_repas_e1']));
      $stmt5->bindValue('prix_repas_e2', addslashes($_POST['prix_repas_e2']));
      $stmt5->bindValue('prix_repas_b1', addslashes($_POST['prix_repas_b1']));
      $stmt5->bindValue('prix_tour_a1', addslashes($_POST['prix_tour_a1']));
      $stmt5->bindValue('prix_tour_a2', addslashes($_POST['prix_tour_a2']));
      $stmt5->bindValue('prix_tour_a3', addslashes($_POST['prix_tour_a3']));
      $stmt5->bindValue('prix_tour_a4', addslashes($_POST['prix_tour_a4']));
      $stmt5->bindValue('prix_tour_e1', addslashes($_POST['prix_tour_e1']));
      $stmt5->bindValue('prix_tour_e2', addslashes($_POST['prix_tour_e2']));
      $stmt5->bindValue('prix_tour_b1', addslashes($_POST['prix_tour_b1']));
      $stmt5->bindValue('prix_total_a1', addslashes($_POST['total_input_1']));
      $stmt5->bindValue('prix_total_a2', addslashes($_POST['total_input_2']));
      $stmt5->bindValue('prix_total_a3', addslashes($_POST['total_input_3']));
      $stmt5->bindValue('prix_total_a4', addslashes($_POST['total_input_4']));
      $stmt5->bindValue('prix_total_e1', addslashes($_POST['total_input_5']));
      $stmt5->bindValue('prix_total_e2', addslashes($_POST['total_input_6']));
      $stmt5->bindValue('prix_total_b1', addslashes($_POST['total_input_7']));
      $stmt5->bindValue('prix_total_total', addslashes($_POST['prix_total_total']));
      $stmt5->bindValue('id_prest1', $id_prest1);
      $stmt5->bindValue('id_prest2', $id_prest2);
      $stmt5->bindValue('id_prest3', $id_prest3);
      $stmt5->bindValue('id_prest4', $id_prest4);
      $stmt5->bindValue('id_prest5', $id_prest5);
      $stmt5->bindValue('id_prix_repas_obligatoire', $_POST['id_prix_repas_obligatoire']);
      $stmt5->bindValue('option_autre_transfert', $_POST['option_autre_transfert']);
      $stmt5->bindValue('option_autre_transfert_enfant', $_POST['option_autre_transfert_enfant']);
      $stmt5->bindValue('option_autre_transfert_bebe', $_POST['option_autre_transfert_bebe']);
      $stmt5->bindValue('id_option_autre_transfert', $_POST['id_option_autre_transfert']);
      $stmt5->bindValue('id_option_autre_transfert_enfant', $_POST['id_option_autre_transfert_enfant']);
      $stmt5->bindValue('id_option_autre_transfert_bebe', $_POST['id_option_autre_transfert_bebe']);
      $stmt5->bindValue('id_prix_transfert_obligatoire', $_POST['id_prix_transfert_obligatoire']);
      $stmt5->bindValue('devis', '1');
      $stmt5->bindValue('date_creation', $date_facture);
      $stmt5->bindValue('status', '1');
      $stmt5->bindValue('num_rue', addslashes($_POST['num_rue']));
      $stmt5->bindValue('titre_ass_1', addslashes($titre_ass_1));
      $stmt5->bindValue('titre_ass_2', addslashes($titre_ass_2));
      $stmt5->bindValue('titre_ass_3', addslashes($titre_ass_3));
      $stmt5->bindValue('titre_ass_4', addslashes($titre_ass_4));
      $stmt5->bindValue('id_assurance_1', addslashes($_POST['id_assurance_1']));
      $stmt5->bindValue('id_assurance_2', addslashes($_POST['id_assurance_2']));
      $stmt5->bindValue('id_assurance_3', addslashes($_POST['id_assurance_3']));
      $stmt5->bindValue('id_assurance_4', addslashes($_POST['id_assurance_4']));
      $stmt5->bindValue('check_assurance_adulte_1', addslashes($_POST['check_assurance_adulte_1']));
      $stmt5->bindValue('check_assurance_adulte_2', addslashes($_POST['check_assurance_adulte_2']));
      $stmt5->bindValue('check_assurance_adulte_3', addslashes($_POST['check_assurance_adulte_3']));
      $stmt5->bindValue('check_assurance_adulte_4', addslashes($_POST['check_assurance_adulte_4']));
      $stmt5->bindValue('check_repas_adulte_101', addslashes($_POST['check_repas_adulte_101']));
      $stmt5->bindValue('check_repas_enfant_101', addslashes($_POST['check_repas_enfant_101']));
      $stmt5->bindValue('check_repas_bebe_101', addslashes($_POST['check_repas_bebe_101']));
      $stmt5->bindValue('check_prestation_adulte_101', addslashes($_POST['check_prestation_adulte_101']));
      $stmt5->bindValue('check_prestation_enfant_101', addslashes($_POST['check_prestation_enfant_101']));
      $stmt5->bindValue('check_prestation_bebe_101', addslashes($_POST['check_prestation_bebe_101']));
      $stmt5->bindValue('check_excursion_adulte_101', addslashes($_POST['check_excursion_adulte_101']));
      $stmt5->bindValue('check_excursion_enfant_101', addslashes($_POST['check_excursion_enfant_101']));
      $stmt5->bindValue('check_excursion_bebe_101', addslashes($_POST['check_excursion_bebe_101']));
      $stmt5->bindValue('check_excursion_adulte_201', addslashes($_POST['check_excursion_adulte_201']));
      $stmt5->bindValue('check_excursion_enfant_201', addslashes($_POST['check_excursion_enfant_201']));
      $stmt5->bindValue('check_excursion_bebe_201', addslashes($_POST['check_excursion_bebe_201']));
      $stmt5->bindValue('remarque', addslashes($_POST['remarque']));
      $stmt5->execute();


      if (!$stmt5) {
        echo "\nPDO::errorInfo():\n";
        print_r($conn->errorInfo());

      }




      $id = md5($conn->lastInsertId());

      echo "<script type='text/javascript'>alert('Vous allez recevoir dans quelques minutes notre détail de votre devis sur votre boîte mail. Merci de vérifier aussi dans la boîte Spam au cas où. Merci.');</script>";

      echo "<meta http-equiv='refresh' content='0;url=Devis_paiement_Facture.php?xx=$id'/>";
    }

  }
}


?>



<link rel="stylesheet" type="text/css" href="date/jquery.datetimepicker.css" />

<script type="text/javascript">
  function nom(chiffre1) {
    result1 = chiffre1;
    document.getElementById('nom_cord').value = result1;

  }
</script>


<style type="text/css">
  .tab-adulte {
    background: #F6F6F6;
    padding: 10px 20px;
    border-left: 3px solid #CCC;
    color: #000;
    font-weight: bold;
  }

  .form-control {
    padding: 0.4rem 0.70rem;
    font-size: 14px;
  }

  .Price {
    background: #F2F2F2;
    padding: 15px;
    margin: 2px;
    text-align: center;
  }

  .Price h1 {

    font-size: 20px;
    font-weight: 700;
    text-align: center;
    text-transform: uppercase;
  }

  .Price h4 {

    font-size: 16px;
  }

  .Price h2 {

    font-size: 40px;
  }

  .ombre {
    /* background: transparent url('img/formulairehotel.png') no-repeat scroll 0% 0%; */
    height: 77px;
    position: relative;
    top: -136px;
    z-index: -9;
  }

  label.radiobutton {
    cursor: pointer;
    font-size: 13px;
    font-family: Arial, "Arial Unicode MS", Helvetica, sans-serif;
    font-weight: normal;
    font-style: normal;
    line-height: 16px;
    display: inline-block;
    color: #364049 !important;
    position: relative;
  }

  label.radiobutton::before {
    background: #FAFAFA none repeat scroll 0% 0%;
    border-radius: 8px;
    border: 2px solid #E2341D;
    margin: 0px auto;
    width: 16px;
    height: 16px;
    display: inline-block;
    vertical-align: top;
    content: " ";
  }

  label.radiobutton input[type="radio"] {
    display: none;
  }

  .radiobutton>input[type="radio"] {
    vertical-align: baseline;
    margin: 0px 5px 0px 0px;
  }

  label.radiobutton.checked:after {
    content: url('images/radiobuttonicon.png');
    position: absolute;
    line-height: 12px;
    left: 2px;
    top: 2px;
  }

  input[type="radio"] {
    width: 10px !important;
    margin: 0px !important;
    margin-bottom: 10px !important;
  }

  .botton-ajout {
    background: #b9ca7a;
    color: #FFF;
    padding: 10px;
  }


  .botton-ajout:hover {
    background: #a4b75e;
    color: #FFF;
  }
</style>




<?php

if (isset($id_reservation_valeur)) {

  $stmt = $conn->prepare('SELECT * FROM reservation_valeur_package WHERE id_reservation_valeur =:id_reservation_valeur');
  $stmt->bindValue('id_reservation_valeur', $id_reservation_valeur);
  $stmt->execute();
  $account = $stmt->fetch(PDO::FETCH_OBJ);
  $url = $account->url;


  //DECOMPOSITION URL

  $tab = explode("?", $url);
  $destination1 = str_replace('destination=', '', $tab[0]);
  $destination2 = str_replace('%20', ' ', $destination1);
  $destination3 = str_replace('%C3%A9', 'é', $destination2);
  //$destination=utf8_decode($destination3);
  $destination = $destination3;

  $dd = str_replace('du=', '', $tab[1]);
  $dai = str_replace('au=', '', $tab[2]);
  $adulte = str_replace('adulte=', '', $tab[3]);
  $enfant = str_replace('enfant=', '', $tab[4]);
  $enfant_age = str_replace('enfant1=', '', $tab[5]);
  $enfant_age_1 = str_replace('enfant=', '', $tab[6]);
  $nb_bebe = str_replace('bebe=', '', $tab[7]);

  // CALCUL NOMBRE ENFANT ET BEBE

  if ($enfant == "0") {
    $nb_enfant = "0";
  }
  if ($enfant == "1") {
    $nb_enfant = "1";
  }

  if ($enfant == "2") {
    $nb_enfant = "2";
  }


  $daad = new DateTime($dd);

  $daad = $daad->format('d m y');

  $daa = explode(' ', $daad);

  if ($daa[1] == '01') {
    $mois = 'Janvier';
  }
  if ($daa[1] == '02') {
    $mois = 'Février';
  }
  if ($daa[1] == '03') {
    $mois = 'Mars';
  }
  if ($daa[1] == '04') {
    $mois = 'Avril';
  }
  if ($daa[1] == '05') {
    $mois = 'Mai';
  }
  if ($daa[1] == '06') {
    $mois = 'Juin';
  }
  if ($daa[1] == '07') {
    $mois = 'Juillet';
  }
  if ($daa[1] == '08') {
    $mois = 'Août';
  }
  if ($daa[1] == '08') {
    $mois = 'Août';
  }
  if ($daa[1] == '09') {
    $mois = 'Septembre';
  }
  if ($daa[1] == '10') {
    $mois = 'Octobre';
  }
  if ($daa[1] == '11') {
    $mois = 'Novembre';
  }
  if ($daa[1] == '12') {
    $mois = 'Décembre';
  }

  $dateeee = $daa[0] . ' ' . $mois . ' ' . $daa[2];





  $package = 'Séjour - ' . $hotel . ' - Date de départ le ' . $dateeee;

  $date3 = strtotime($dd);
  $date4 = strtotime($dai);
  // On récupère la différence de timestamp entre les 2 précédents
  $nbJoursTimestamp = $date4 - $date3;

  $nbJours = round($nbJoursTimestamp / 86400);


  $da = new DateTime($dai);
  $daa = new DateTime($dai);

  $daa = $daa->format('Y-m-d');

  $da->modify('-1 day');
  $da = $da->format('Y-m-d');

  // NOMBRE DE JOURS

  if ($nbJours > 21) {
    echo "<script Type=text/javascript>";
    echo "alert('LE SEJOUR NE  DOIT PAS DEPASSER DE 21 JOURS')</script>";
    echo "<meta http-equiv='refresh' content='0;url=index.php'/>";
  }


  $date_visiteur = date("Y-m-d");



  function rndfunc($x)
  {
    return round($x * 2, 1) / 2;
  }





  $stmt = $conn->prepare('SELECT * FROM reservation_valeur_package');
  $stmt->execute();
  while ($reservation = $stmt->fetch(PDO::FETCH_OBJ)) {
    if (Md5($reservation->id_reservation_valeur) == $xx) {
      $id_reservation_valeur = $reservation->id_reservation_valeur;

      $tab_tour = explode(" <br> ", $reservation->id_excursion);

      $id_excursion = $reservation->id_excursion;

      $id_prestation_hotel = $reservation->id_total_autre;
      $id_repas_hotel = $reservation->id_total_repas;
      $id_prestation_hotel = $reservation->id_total_autre;
      $id_total_repas = $reservation->id_total_repas;

      $transf = ($reservation->adulte1_transfert - $reservation->adulte_visa) + $reservation->adulte_visa_1;

      if ($id_total_repas != "0") {
        $repas_hotel = $reservation->repas_adulte / ($adulte);
      } else {

        $repas_hotel = "0";
      }


      if ($id_prestation_hotel != "0") {
        $prestation_hotel = $reservation->autre_adulte / ($adulte);

      } else {

        $prestation_hotel = "0";
      }

      $tour_adulte1 = "0";

      $tab_tour = explode(" <br> ", $reservation->id_excursion);

      $tab_tour_nb_adulte = $reservation->nb_adulte_tour;
      $tab_tour_jr_adulte = $reservation->jr_adulte_tour;

      for ($u = 0; $u < count($tab_tour); $u++) {

        if ($tab_tour[$u] != "0") {

          $stmt5 = $conn->prepare('SELECT * FROM tour WHERE id_excursion =:id_excursion');
          $stmt5->bindValue('id_excursion', $tab_tour[$u]);
          $stmt5->execute();
          while ($account5 = $stmt5->fetch(PDO::FETCH_OBJ)) {

            $prix_tour = $account5->total_adulte * ($tab_tour_nb_adulte / $adulte) * $tab_tour_jr_adulte;
            $tour_adulte1 += $prix_tour;
          }
        }

      }


      if (isset($_POST['total_adulte1'])) {
        $total_adulte1 = $_POST['total_adulte1'];

      } else {
        $total_adulte1 = $adulte10 + $transf + $tour_adulte1 + $prestation_hotel + $repas_hotel;
      }



      $adulte100 = ($total_adulte1 * 6) / 100;
      $adulte1 = rndfunc($adulte100);
      $adulte1 = number_format($adulte1, 2, '.', ' ');





      if ($adulte == "2") {
        $tranbsf = ($reservation->adulte2_transfert - $reservation->adulte_visa) + $reservation->adulte_visa_2;

        if ($id_total_repas != "0") {
          $repas_hotel = $reservation->repas_adulte / ($adulte);
        } else {
          $repas_hotel = "0";
        }

        if ($id_prestation_hotel != "0") {
          $prestation_hotel = $reservation->autre_adulte / ($adulte);
        } else {
          $prestation_hotel = "0";
        }

        $tab_tour = explode(" <br> ", $reservation->id_excursion);

        $tour_adulte2 = "0";
        $tab_tour_nb_adulte = $reservation->nb_adulte_tour;
        $tab_tour_jr_adulte = $reservation->jr_adulte_tour;

        for ($u = 0; $u < count($tab_tour); $u++) {

          if ($tab_tour[$u] != "0") {

            $stmt5 = $conn->prepare('SELECT * FROM tour WHERE id_excursion =:id_excursion');
            $stmt5->bindValue('id_excursion', $tab_tour[$u]);
            $stmt5->execute();
            while ($account5 = $stmt5->fetch(PDO::FETCH_OBJ)) {

              $prix_tour = $account5->total_adulte * ($tab_tour_nb_adulte / $adulte) * $tab_tour_jr_adulte;
              $tour_adulte2 += $prix_tour;
            }
          }

        }



        if (isset($_POST['total_adulte2'])) {
          $total_adulte2 = $_POST['total_adulte2'];

        } else {
          $total_adulte2 = $adulte20 + $tranbsf + $tour_adulte2 + $prestation_hotel + $repas_hotel;
        }


        $adulte200 = ($total_adulte2 * 6) / 100;
        $adulte2 = rndfunc($adulte200);
        $adulte2 = number_format($adulte2, 2, '.', ' ');


      }

      if ($adulte == "3") {

        $tranbsf = ($reservation->adulte2_transfert - $reservation->adulte_visa) + $reservation->adulte_visa_2;

        if ($id_total_repas != "0") {
          $repas_hotel = $reservation->repas_adulte / ($adulte);
        } else {
          $repas_hotel = "0";
        }

        if ($id_prestation_hotel != "0") {
          $prestation_hotel = $reservation->autre_adulte / ($adulte);
        } else {
          $prestation_hotel = "0";
        }

        $tab_tour = explode(" <br> ", $reservation->id_excursion);

        $tour_adulte2 = "0";
        $tab_tour_nb_adulte = $reservation->nb_adulte_tour;
        $tab_tour_jr_adulte = $reservation->jr_adulte_tour;
        for ($u = 0; $u < count($tab_tour); $u++) {

          if ($tab_tour[$u] != "0") {

            $stmt5 = $conn->prepare('SELECT * FROM tour WHERE id_excursion =:id_excursion');
            $stmt5->bindValue('id_excursion', $tab_tour[$u]);
            $stmt5->execute();
            while ($account5 = $stmt5->fetch(PDO::FETCH_OBJ)) {

              $prix_tour = $account5->total_adulte * ($tab_tour_nb_adulte / $adulte) * $tab_tour_jr_adulte;
              $tour_adulte2 += $prix_tour;
            }
          }

        }



        if (isset($_POST['total_adulte2'])) {
          $total_adulte2 = $_POST['total_adulte2'];

        } else {
          $total_adulte2 = $adulte20 + $tranbsf + $tour_adulte2 + $prestation_hotel + $repas_hotel;
        }


        $adulte200 = ($total_adulte2 * 6) / 100;
        $adulte2 = rndfunc($adulte200);
        $adulte2 = number_format($adulte2, 2, '.', ' ');


        $transf = ($reservation->adulte3_transfert - $reservation->adulte_visa) + $reservation->adulte_visa_3;
        if ($id_total_repas != "0") {
          $repas_hotel = $reservation->repas_adulte / ($adulte);
        } else {
          $repas_hotel = "0";
        }

        if ($id_prestation_hotel != "0") {
          $prestation_hotel = $reservation->autre_adulte / ($adulte);
        } else {
          $prestation_hotel = "0";
        }

        $tour_adulte3 = "0";
        $tab_tour = explode(" <br> ", $reservation->id_excursion);


        $tab_tour_nb_adulte = $reservation->nb_adulte_tour;
        $tab_tour_jr_adulte = $reservation->jr_adulte_tour;

        for ($u = 0; $u < count($tab_tour); $u++) {

          if ($tab_tour[$u] != "0") {

            $stmt5 = $conn->prepare('SELECT * FROM tour WHERE id_excursion =:id_excursion');
            $stmt5->bindValue('id_excursion', $tab_tour[$u]);
            $stmt5->execute();
            while ($account5 = $stmt5->fetch(PDO::FETCH_OBJ)) {

              $prix_tour = $account5->total_adulte * ($tab_tour_nb_adulte / $adulte) * $tab_tour_jr_adulte;
              $tour_adulte3 += $prix_tour;
            }
          }

        }

        if (isset($_POST['total_adulte3'])) {
          $total_adulte3 = $_POST['total_adulte3'];
        } else {
          $total_adulte3 = $adulte30 + $transf + $tour_adulte3 + $prestation_hotel + $repas_hotel;
        }


        $adulte300 = ($total_adulte3 * 6) / 100;
        $adulte3 = round($adulte300, 2, PHP_ROUND_HALF_UP);
        $adulte3 = rndfunc($adulte300);



      }


      if ($adulte == "4") {

        $tranbsf = ($reservation->adulte2_transfert - $reservation->adulte_visa) + $reservation->adulte_visa_2;

        if ($id_total_repas != "0") {
          $repas_hotel = $reservation->repas_adulte / ($adulte);
        } else {
          $repas_hotel = "0";
        }

        if ($id_prestation_hotel != "0") {
          $prestation_hotel = $reservation->autre_adulte / ($adulte);
        } else {
          $prestation_hotel = "0";
        }

        $tab_tour = explode(" <br> ", $reservation->id_excursion);

        $tour_adulte2 = "0";
        $tab_tour_nb_adulte = $reservation->nb_adulte_tour;
        $tab_tour_jr_adulte = $reservation->jr_adulte_tour;

        for ($u = 0; $u < count($tab_tour); $u++) {

          if ($tab_tour[$u] != "0") {

            $stmt5 = $conn->prepare('SELECT * FROM tour WHERE id_excursion =:id_excursion');
            $stmt5->bindValue('id_excursion', $tab_tour[$u]);
            $stmt5->execute();
            while ($account5 = $stmt5->fetch(PDO::FETCH_OBJ)) {

              $prix_tour = $account5->total_adulte * ($tab_tour_nb_adulte / $adulte) * $tab_tour_jr_adulte;
              $tour_adulte2 += $prix_tour;
            }
          }

        }



        if (isset($_POST['total_adulte2'])) {
          $total_adulte2 = $_POST['total_adulte2'];

        } else {
          $total_adulte2 = $adulte20 + $tranbsf + $tour_adulte2 + $prestation_hotel + $repas_hotel;
        }


        $adulte200 = ($total_adulte2 * 6) / 100;
        $adulte2 = rndfunc($adulte200);
        $adulte2 = number_format($adulte2, 2, '.', ' ');


        $transf = ($reservation->adulte3_transfert - $reservation->adulte_visa) + $reservation->adulte_visa_3;
        if ($id_total_repas != "0") {
          $repas_hotel = $reservation->repas_adulte / ($adulte);
        } else {
          $repas_hotel = "0";
        }

        if ($id_prestation_hotel != "0") {
          $prestation_hotel = $reservation->autre_adulte / ($adulte);
        } else {
          $prestation_hotel = "0";
        }

        $tour_adulte3 = "0";
        $tab_tour = explode(" <br> ", $reservation->id_excursion);


        $tab_tour_nb_adulte = $reservation->nb_adulte_tour;
        $tab_tour_jr_adulte = $reservation->jr_adulte_tour;

        for ($u = 0; $u < count($tab_tour); $u++) {

          if ($tab_tour[$u] != "0") {

            $stmt5 = $conn->prepare('SELECT * FROM tour WHERE id_excursion =:id_excursion');
            $stmt5->bindValue('id_excursion', $tab_tour[$u]);
            $stmt5->execute();
            while ($account5 = $stmt5->fetch(PDO::FETCH_OBJ)) {

              $prix_tour = $account5->total_adulte * ($tab_tour_nb_adulte / $adulte) * $tab_tour_jr_adulte;
              $tour_adulte3 += $prix_tour;
            }
          }

        }

        if (isset($_POST['total_adulte3'])) {
          $total_adulte3 = $_POST['total_adulte3'];
        } else {
          $total_adulte3 = $adulte30 + $transf + $tour_adulte3 + $prestation_hotel + $repas_hotel;
        }


        $adulte300 = ($total_adulte3 * 6) / 100;
        $adulte3 = round($adulte300, 2, PHP_ROUND_HALF_UP);
        $adulte3 = rndfunc($adulte300);


        $transf = ($reservation->adulte4_transfert - $reservation->adulte_visa) + $reservation->adulte_visa_4;

        if ($id_total_repas != "0") {
          $repas_hotel = $reservation->repas_adulte / ($adulte);
        } else {
          $repas_hotel = "0";
        }

        if ($id_prestation_hotel != "0") {
          $prestation_hotel = $reservation->autre_adulte / ($adulte);
        } else {
          $prestation_hotel = "0";
        }

        $tour_adulte2 = "0";
        $tab_tour = explode(" <br> ", $reservation->id_excursion);
        $tab_tour_nb_adulte = $reservation->nb_adulte_tour;
        $tab_tour_jr_adulte = $reservation->jr_adulte_tour;

        for ($u = 0; $u < count($tab_tour); $u++) {

          if ($tab_tour[$u] != "0") {

            $stmt5 = $conn->prepare('SELECT * FROM tour WHERE id_excursion =:id_excursion');
            $stmt5->bindValue('id_excursion', $tab_tour[$u]);
            $stmt5->execute();
            while ($account5 = $stmt5->fetch(PDO::FETCH_OBJ)) {
              $prix_tour = $account5->total_adulte * ($tab_tour_nb_adulte / $adulte) * $tab_tour_jr_adulte;
              $tour_adulte2 += $prix_tour;
            }
          }

        }

        if (isset($_POST['total_adulte4'])) {
          $total_adulte4 = $_POST['total_adulte4'];

        } else {

          $total_adulte4 = $adulte40 + $transf + $tour_adulte2 + $prestation_hotel + $repas_hotel;

        }


        $adulte400 = ($total_adulte4 * 6) / 100;
        $adulte4 = round($adulte400, 2, PHP_ROUND_HALF_UP);
        $adulte4 = rndfunc($adulte400);



      }


      if ($nb_enfant == "1") {


        $transf = ($reservation->enfant1_transfert - $reservation->enfant_visa) + $reservation->enfant_visa_1;

        if ($id_total_repas != "0") {
          $repas_hotel = $reservation->repas_enfant / ($nb_enfant);
        } else {
          $repas_hotel = "0";
        }


        if ($id_prestation_hotel != "0") {
          $prestation_hotel = $reservation->autre_enfant / ($nb_enfant);
        } else {
          $prestation_hotel = "0";
        }

        $tour_enfant1 = "0";
        $tab_tour = explode(" <br> ", $reservation->id_excursion);
        $tab_tour_nb_enfant = $reservation->nb_enfant_tour;
        $tab_tour_jr_enfant = $reservation->jr_enfant_tour;

        for ($u = 0; $u < count($tab_tour); $u++) {

          if ($tab_tour[$u] != "0") {

            $stmt5 = $conn->prepare('SELECT * FROM tour WHERE id_excursion =:id_excursion');
            $stmt5->bindValue('id_excursion', $tab_tour[$u]);
            $stmt5->execute();
            while ($account5 = $stmt5->fetch(PDO::FETCH_OBJ)) {

              $prix_tour = $account5->total_enfant * ($tab_tour_nb_enfant / $nb_enfant) * $tab_tour_jr_enfant;
              $tour_enfant1 += $prix_tour;
            }
          }

        }

        if (isset($_POST['total_adulte5'])) {
          $total_enfant1 = $_POST['total_adulte5'];
        } else {
          $total_enfant1 = $enfant10 + $transf + $tour_enfant1 + $prestation_hotel + $repas_hotel;
        }


        $enfant100 = ($total_enfant1 * 6) / 100;

        $enfant1 = rndfunc($enfant100);
        $enfant1 = number_format($enfant1, 2, '.', ' ');


      }

      if ($nb_enfant == "2") {

        $transf = ($reservation->enfant1_transfert - $reservation->enfant_visa) + $reservation->enfant_visa_1;

        if ($id_total_repas != "0") {
          $repas_hotel = $reservation->repas_enfant / ($nb_enfant);
        } else {
          $repas_hotel = "0";
        }


        if ($id_prestation_hotel != "0") {
          $prestation_hotel = $reservation->autre_enfant / ($nb_enfant);
        } else {
          $prestation_hotel = "0";
        }

        $tour_enfant1 = "0";
        $tab_tour = explode(" <br> ", $reservation->id_excursion);
        $tab_tour_nb_enfant = $reservation->nb_enfant_tour;
        $tab_tour_jr_enfant = $reservation->jr_enfant_tour;

        for ($u = 0; $u < count($tab_tour); $u++) {

          if ($tab_tour[$u] != "0") {

            $stmt5 = $conn->prepare('SELECT * FROM tour WHERE id_excursion =:id_excursion');
            $stmt5->bindValue('id_excursion', $tab_tour[$u]);
            $stmt5->execute();
            while ($account5 = $stmt5->fetch(PDO::FETCH_OBJ)) {

              $prix_tour = $account5->total_enfant * ($tab_tour_nb_enfant / $nb_enfant) * $tab_tour_jr_enfant;
              $tour_enfant1 += $prix_tour;
            }
          }

        }

        if (isset($_POST['total_adulte5'])) {
          $total_enfant1 = $_POST['total_adulte5'];
        } else {
          $total_enfant1 = $enfant10 + $transf + $tour_enfant1 + $prestation_hotel + $repas_hotel;
        }


        $enfant100 = ($total_enfant1 * 6) / 100;

        $enfant1 = rndfunc($enfant100);
        $enfant1 = number_format($enfant1, 2, '.', ' ');


        $transf = ($reservation->enfant2_transfert - $reservation->enfant_visa) + $reservation->enfant_visa_2;

        if ($id_total_repas != "0") {
          $repas_hotel = $reservation->repas_enfant / ($nb_enfant);
        } else {
          $repas_hotel = "0";
        }


        if ($id_prestation_hotel != "0") {
          $prestation_hotel = $reservation->autre_enfant / ($nb_enfant);

        } else {

          $prestation_hotel = "0";
        }

        $tour_enfant1 = "0";
        $tab_tour = explode(" <br> ", $reservation->id_excursion);

        $tab_tour_nb_enfant = $reservation->nb_enfant_tour;
        $tab_tour_jr_enfant = $reservation->jr_enfant_tour;

        for ($u = 0; $u < count($tab_tour); $u++) {

          if ($tab_tour[$u] != "0") {

            $stmt5 = $conn->prepare('SELECT * FROM tour WHERE id_excursion =:id_excursion');
            $stmt5->bindValue('id_excursion', $tab_tour[$u]);
            $stmt5->execute();
            while ($account5 = $stmt5->fetch(PDO::FETCH_OBJ)) {

              $prix_tour = $account5->total_enfant * ($tab_tour_nb_enfant / $nb_enfant) * $tab_tour_jr_enfant;
              $tour_enfant1 += $prix_tour;
            }
          }

        }


        if (isset($_POST['total_adulte6'])) {
          $total_enfant2 = $_POST['total_adulte6'];

        } else {
          $total_enfant2 = $enfant20 + $transf + $tour_enfant1 + $prestation_hotel + $repas_hotel;

        }



        $enfant200 = ($total_enfant2 * 6) / 100;

        $enfant2 = rndfunc($enfant200);
        $enfant2 = number_format($enfant2, 2, '.', ' ');

      }


      if ($nb_bebe == "1") {

        $transf = ($reservation->bebe1_transfert + $reservation->bebe_visa) - $reservation->bebe_visa;


        if ($id_total_repas != "0") {
          $repas_hotel = $reservation->repas_bebe;
        } else {
          $repas_hotel = "0";
        }

        if ($id_prestation_hotel != "0") {
          $prestation_hotel = $reservation->autre_bebe;
        } else {
          $prestation_hotel = "0";
        }

        $tour_enfant1 = "0";
        $tab_tour = explode(" <br> ", $reservation->id_excursion);
        $tab_tour_nb_bebe = $reservation->nb_bebe_tour;
        $tab_tour_jr_bebe = $reservation->jr_bebe_tour;

        for ($u = 0; $u < count($tab_tour); $u++) {

          if ($tab_tour[$u] != "0") {

            $stmt5 = $conn->prepare('SELECT * FROM tour WHERE id_excursion =:id_excursion');
            $stmt5->bindValue('id_excursion', $tab_tour[$u]);
            $stmt5->execute();
            while ($account5 = $stmt5->fetch(PDO::FETCH_OBJ)) {

              $prix_tour = $account5->total_bebe * ($tab_tour_nb_bebe) * $tab_tour_jr_bebe;
              $tour_enfant1 += $prix_tour;
            }
          }

        }

        if (isset($_POST['total_adulte7'])) {
          $total_bebe1 = $_POST['total_adulte7'];
        } else {
          $total_bebe1 = $bebe10 + $transf + $tour_enfant1 + $prestation_hotel + $repas_hotel;


        }

        $bebe100 = ($total_bebe1 * 6) / 100;

        $bebe1 = rndfunc($bebe100);
        $bebe1 = number_format($bebe1, 2, '.', ' ');

      }



    }
  }






  ?>

  <style type="text/css">
    select {
      border: 1px solid #92A5AC;
      color: #92A5AC;
      border-radius: 2px;
    }

    p {
      margin-bottom: 5px;
    }


    [type="checkbox"]:not(:checked)+label::after,
    [type="checkbox"]:checked+label::after {
      content: '✔';
      position: absolute;
      top: 1px;
      left: 3px;
      font-size: 13px;
      color: #9bacb2;
      transition: all .2s;
    }

    [type="checkbox"]:not(:checked)+label::before,
    [type="checkbox"]:checked+label::before {
      content: '';
      position: absolute;
      left: 0;
      top: 2px;
      width: 17px;
      height: 17px;
      border: 2px solid #9bacb2;
      border-radius: 2px;
    }

    .checkbox_group {
      display: block;
      width: 100%;
      margin-bottom: 0px;
      margin-left: 5px;
      margin-top: 2px;
    }

    .ui-tabs .ui-tabs-nav .ui-tabs-anchor {
      text-align: center;
    }

    .ui-tabs-anchor span {
      text-align: center;
      font-weight: 1000;
      font-size: 10px;
    }

    .message-extra {
      background: rgba(0, 0, 0, 0.6);
      position: fixed;
      z-index: 9999999999;
      width: 100%;
      top: 0;
      left: 0;
      bottom: 0;
    }

    b,
    strong {
      font-weight: bold;
    }
  </style>
  <!-- Header -->
  <div class="tm-section-2" id="contenu2" style="padding-top: 15px;background: #01ccf4;">
    <div class="container">
      <div class="row">
        <div class="col text-center">
          <h2 class="tm-section-title" style="font-size: 26px;">
            <?php echo $package; ?>
          </h2>
          <p class="tm-color-white tm-section-subtitle">


          </p>
        </div>
      </div>
    </div>
  </div>
  <!-- Services Section -->
  <section id="container_pp">
    <div class="container" style="padding-top: 18px;">

      <div class="row row_list_custom">




        <div class="col-sm-12">
          <h1 style="font-weight: 700;text-transform: uppercase;">Votre devis</h1>
          <hr>
        </div>

        <div class="col-sm-12">




          <p style="margin-bottom: 0;line-height: 20px;color: #058C08;"><i class="fa fa-check"></i>&nbsp;Le nom des
            participants au voyage doit concorder avec les indications du passeport !</p>
          <p style="margin-bottom: 0;line-height: 20px;color: #058C08;"><i class="fa fa-check"></i>&nbsp;Assurez-vous que
            l'orthographe de notre nom complet et prénom soit bien correcte.</p>
          <p style="margin-bottom: 0;line-height: 20px;color: #058C08;"><i class="fa fa-check"></i>&nbsp;Toute correction
            ultérieure implique des coûts supplémentaires.</p>
          <p style="margin-bottom: 0;line-height: 20px;color: #058C08;"><i class="fa fa-check"></i>&nbsp;Merci de remplir
            le formulaire ci-dessous.</p>



        </div>
        <div class="col-sm-12">

          <p>&nbsp;</p>

        </div>



        <?php

        include ('sejour_include6.php');

        ?>




        <div class="col-sm-12">
          <h4 style="color: #FFF;font-weight: 700;text-transform: uppercase;font-size: 20px;padding: 18px 0;">
            <span
              style="background: #000;padding: 20px 28px 19px 20px;color: #FFF;border-top-right-radius: 10px;border-bottom-right-radius: 10px;z-index: 9;"><i
                class="fa fa-users-cog" style="font-size: 34px;"></i></span>
            <span
              style="background: #f68730;padding: 5px 28px 5px 20px;color: #FFF;z-index: -1;left: -18px;position: relative">Participants
              au voyage</span>
          </h4>
        </div>
        <form action="" method="POST" name="form2">
          <input type="hidden" name="id_reservation_valeur" value="<?php echo $id_reservation_valeur; ?>">





          <input type="hidden" name="id_prix_repas_obligatoire"
            value="<?php echo $account->id_prix_repas_obligatoire; ?>">
          <input type="hidden" name="option_autre_transfert" value="<?php echo $account->option_autre_transfert; ?>">
          <input type="hidden" name="option_autre_transfert_enfant"
            value="<?php echo $account->option_autre_transfert_enfant; ?>">
          <input type="hidden" name="option_autre_transfert_bebe"
            value="<?php echo $account->option_autre_transfert_bebe; ?>">
          <input type="hidden" name="id_option_autre_transfert"
            value="<?php echo $account->id_option_autre_transfert; ?>">
          <input type="hidden" name="id_option_autre_transfert_enfant"
            value="<?php echo $account->id_option_autre_transfert_enfant; ?>">
          <input type="hidden" name="id_option_autre_transfert_bebe"
            value="<?php echo $account->id_option_autre_transfert_bebe; ?>">
          <input type="hidden" name="id_prix_transfert_obligatoire"
            value="<?php echo $account->id_prix_transfert_obligatoire; ?>">

          <input type="hidden" name="id_prest1" id="id_prest1" value="<?php echo $account->id_prest1; ?>">
          <input type="hidden" name="id_prest2" id="id_prest2" value="<?php echo $account->id_prest2; ?>">
          <input type="hidden" name="id_prest3" id="id_prest3" value="<?php echo $account->id_prest3; ?>">
          <input type="hidden" name="id_prest4" id="id_prest4" value="<?php echo $account->id_prest4; ?>">
          <input type="hidden" name="id_prest5" id="id_prest5" value="<?php echo $account->id_prest5; ?>">




          <input type="hidden" name="id_prest5" id="id_prest5" value="<?php echo $account->id_prest5; ?>">


          <div class="col-sm-12">
            <p>&nbsp;</p>

            <?php



            for ($rr = 1; $rr <= $adulte; $rr++) {


              ?>
              <a href="javascript:void(0)" id="tab-adulte_<?php echo $rr; ?>" style="width: 100%;">
                <p class="tab-adulte"><i class="fa fa-plus"></i>&nbsp;&nbsp;Participants au voyage: &nbsp;<span
                    style="color: white;text-transform: uppercase;background: #9F9191;font-size: 10px;padding: 1px 10px;width: 76px;display: inline-block;text-align: center;">Adulte
                    <?php echo $rr; ?>
                  </span></p>
              </a>
              <a href="javascript:void(0)" id="tab-adulte_<?php echo $rr; ?>_off" style="display: none;width: 100%;">
                <p class="tab-adulte" style="background: #FF0707;color: #FFF;"><i
                    class="fa fa-angle-down"></i>&nbsp;&nbsp;Participants au voyage: &nbsp;<span
                    style="color: white;text-transform: uppercase;background: #9F9191;font-size: 10px;padding: 1px 10px;width: 76px;display: inline-block;text-align: center;">Adulte
                    <?php echo $rr; ?>
                  </span></p>
              </a>


              <div class="col-sm-12" id="tab-adulte_<?php echo $rr; ?>-aff"
                style="border-left: 2px solid #FF00B3; display: none">
                <div class="col-sm-1">
                  <p>&nbsp;</p>
                </div>


                <!-- Voyage -->
                <div class="col-sm-12">

                  <div class="row">
                    <div class="col-sm-6">


                      <label for="exampleInputName2">Nationalité</label>

                      <div class="input-group select_custom">
                        <select class="form-control" style="z-index: 0;" name="nationalite_participant_<?php echo $rr; ?>"
                          id="">
                          <option value="Suisse" class="others" selected>Suisse</option>
                          <option value="France" class="others">France</option>
                          <option value="Espagne" class="others">Espagne</option>
                          <option value="Portugal" class="others">Portugal</option>
                          <?php
                          $stmt = $conn->prepare('SELECT * FROM pays ORDER BY nom_fr_fr ASC');
                          $stmt->execute();
                          while ($account = $stmt->fetch(PDO::FETCH_OBJ)) {
                            ?>


                            <option value="<?php echo $account->nom_fr_fr; ?>" <?php

                                 $nationalite_participant = 'nationalite_participant_' . $rr;
                                 if (isset($_POST[$nationalite_participant])) {
                                   if ($_POST[$nationalite_participant] == $account->nom_fr_fr) {
                                     echo 'selected';
                                   }
                                 }
                                 ?>>
                              <?php echo $account->nom_fr_fr; ?>
                            </option>
                            <?php
                          }
                          ?>
                        </select>
                      </div>


                    </div>
                    <div class="col-sm-6">

                      <label for="exampleInputName2">Titre *</label>

                      <select class="form-control" name="titre_participant_<?php echo $rr; ?>" id="">
                        <option value="Mr" class="others" <?php
                        $titre_participant = 'titre_participant_' . $rr;
                        if (isset($_POST[$titre_participant])) {
                          if ($_POST[$titre_participant] == "0") {
                            echo 'selected';
                          }
                        }
                        ?>>Sélectionnez</option>
                        <option value="Mr" class="others" <?php
                        if (isset($_POST[$titre_participant])) {
                          if ($_POST[$titre_participant] == "Mr") {
                            echo 'selected';
                          }
                        }
                        ?> selected>Mr</option>
                        <option value="Mme" class="others" <?php
                        if (isset($_POST[$titre_participant])) {
                          if ($_POST[$titre_participant] == "Mme") {
                            echo 'selected';
                          }
                        }
                        ?>>Mme</option>

                      </select>
                    </div>


                    <div class="col-sm-6"><br>
                      <div class="form-group form_group_line">
                        <label for="exampleInputName2">Nom *</label>
                        <input type="text" class="form-control" id="exampleInputName2"
                          name="nom_participant_<?php echo $rr; ?>"
                          value="<?php $nom_participant = 'nom_participant_' . $rr;
                          if (isset($_POST[$nom_participant])) {
                            echo $_POST[$nom_participant];
                          } ?>"
                          OnKeyUp="javascript:nomadulte<?php echo $rr; ?>(this.value);" required>
                      </div>

                    </div>

                    <div class="col-sm-6"><br>

                      <div class="form-group form_group_line">
                        <label for="exampleInputEmail2">Prénom *</label>
                        <input type="text" class="form-control" id="exampleInputEmail2"
                          name="prenom_participant_<?php echo $rr; ?>"
                          value="<?php $prenom_participant = 'prenom_participant_' . $rr;
                          if (isset($_POST[$prenom_participant])) {
                            echo $_POST[$prenom_participant];
                          } ?>"
                          OnKeyUp="javascript:prenomadulte<?php echo $rr; ?>(this.value);" required>
                      </div>
                    </div>












                    <script type="text/javascript">
                      function nomadulte1(chiffre1) {
                        document.getElementById('nomcord').value = chiffre1;

                      }
                      function prenomadulte1(chiffre1) {
                        document.getElementById('prenomcord').value = chiffre1;

                      }
                    </script>

                    <div class="col-sm-12">
                      <a href="javascript:void(0)" id="">
                        <h4
                          style="font-size: 14px;font-weight: 1000;background: #09DCFF;padding: 10px;color: #FFF;margin-bottom: 0px;border-bottom: 4px solid #13C0DD;">
                          <i class="fa fa-eye"></i> Voir nos offres assurances pour votre voyage </h4>
                      </a>
                    </div>


                    <div class="col-sm-12" style="margin-bottom: 40px;font-size: 12px;">


                      <div class="col-sm-12"
                        style="padding: 20px 40px;background: #FBFBFB;border-bottom: 4px solid #13C0DD;">
                        <div class="row">

                          <?php

                          $assurance = 'assurance_' . $rr;
                          $total_adulte1_new;

                          if ($rr == 1) {
                            $total_adulte = $total_adulte1_new;
                          }
                          if ($rr == 2) {
                            $total_adulte = $total_adulte2_new;
                          }
                          if ($rr == 3) {
                            $total_adulte = $total_adulte3_new;
                          }
                          if ($rr == 4) {
                            $total_adulte = $total_adulte4_new;
                          }



                          ?>


                          <div class="form-group form_check col-sm-9" style="padding: 0px;margin: 0px;">
                            <input type="radio" name="assurance_<?php echo $rr; ?>"
                              onchange="radio<?php echo $rr; ?>adulte(this)" value="0" <?php if (isset($_POST[$assurance])) {
                                   if ($_POST[$assurance] == "0") {
                                     echo 'checked';
                                   }
                                 } else {
                                   echo 'checked';
                                 } ?>>&nbsp;&nbsp;Non, je ne
                            désire pas souscrire à une assurance de voyage
                          </div>
                          <div class="form-group form_check col-sm-2" style="padding: 0px;margin: 0px;text-align: left;">
                          </div>

                          <div class="form-group form_check col-sm-9" style="padding: 0px;margin: 0px;text-align: right;">
                            <span style="font-weight: bold;color: #EA1F2E;"></span>
                          </div>



                          <?php

                          $id_assurance = '';
                          $stmt34 = $conn->prepare('SELECT * FROM assurance WHERE id_assurance !=:id_assurance ORDER BY prix_assurance');
                          $stmt34->bindValue('id_assurance', $id_assurance);
                          $stmt34->execute();
                          while ($account34 = $stmt34->fetch(PDO::FETCH_OBJ)) {

                            if ($account34->prix_assurance == 0) {
                              $prix_assurance = round(($total_adulte * $account34->pourcentage / 100), 1);
                              //$prix_assurance = ($total_adulte * $account34 -> pourcentage/100);
                            } else {
                              $prix_assurance = $account34->prix_assurance;
                            }



                            if ($account34->info == "annuelle") {
                              $prestation_ass = 'Assurance annulle';
                            }
                            if ($account34->info == "uniquement") {
                              $prestation_ass = 'Pour le voyage uniquement';
                            }


                            ?>

                            <div class="form-group form_check col-sm-5"
                              id="reservation_adulte_<?php echo $rr; ?>_<?php echo $account34->id_assurance; ?>"
                              style="padding: 0px;margin: 0px;">
                              <input type="radio" name="assurance_<?php echo $rr; ?>"
                                onchange="radio<?php echo $rr; ?>adulte(this)"
                                value="<?php echo $prix_assurance . '-' . $account34->id_assurance . '-' . $account34->titre_assurance . '-' . $prestation_ass; ?>"
                                <?php
                                $assurance = 'assurance_' . $rr;
                                $prix_assurance_rec = $prix_assurance . '-' . $account34->id_assurance . '-' . $account34->titre_assurance . '-' . $prestation_ass;
                                if (isset($_POST[$assurance])) {
                                  if ($_POST[$assurance] == $prix_assurance_rec) {
                                    echo 'checked';
                                  }
                                }

                                ?>>&nbsp;&nbsp;
                              <?php echo $account34->titre_assurance; ?>
                            </div>



                            <div class="form-group form_check col-sm-4" style="padding: 0px;margin: 0px;text-align: left;">
                              <?php
                              echo $prestation_ass;
                              ?>


                            </div>



                            <div class="form-group form_check col-sm-2" style="padding: 0px;margin: 0px;text-align: left;">
                              <?php echo $account34->par; ?>
                            </div>

                            <div class="form-group form_check col-sm-1" style="padding: 0px;margin: 0px;text-align: right;">
                              <span style="font-weight: bold;color: #EA1F2E;">
                                <?php echo number_format($prix_assurance, 2, '.', ' '); ?> CHF
                              </span>
                            </div>


                            <?php

                          }

                          ?>

                        </div>
                      </div>

                    </div>



                  </div>
                </div>




              </div>

              <script type="text/javascript">
                $("#tab-adulte_<?php echo $rr; ?>").click(function () {
                  $("#tab-adulte_<?php echo $rr; ?>-aff").show(500);
                  $("#tab-adulte_<?php echo $rr; ?>_off").show(500);
                  $("#tab-adulte_<?php echo $rr; ?>").hide(500);
                });
                $("#tab-adulte_<?php echo $rr; ?>_off").click(function () {
                  $("#tab-adulte_<?php echo $rr; ?>-aff").hide(500);
                  $("#tab-adulte_<?php echo $rr; ?>_off").hide(500);
                  $("#tab-adulte_<?php echo $rr; ?>").show(500);

                });
              </script>





              <?php
            }

            if ($adulte == 1) {
              ?>
              <input type="hidden" name="titre_participant_2" value="0">
              <input type="hidden" name="nom_participant_2" value="0">
              <input type="hidden" name="prenom_participant_2" value="0">
              <input type="hidden" name="nationalite_participant_2" value="0">
              <input type="hidden" name="assurance_2" value="0">

              <input type="hidden" name="titre_participant_3" value="0">
              <input type="hidden" name="nom_participant_3" value="0">
              <input type="hidden" name="prenom_participant_3" value="0">
              <input type="hidden" name="nationalite_participant_3" value="0">
              <input type="hidden" name="assurance_3" value="0">

              <input type="hidden" name="titre_participant_4" value="0">
              <input type="hidden" name="nom_participant_4" value="0">
              <input type="hidden" name="prenom_participant_4" value="0">
              <input type="hidden" name="nationalite_participant_4" value="0">
              <input type="hidden" name="assurance_4" value="0">

            <?php
            }

            if ($adulte == 2) {
              ?>
              <input type="hidden" name="titre_participant_3" value="0">
              <input type="hidden" name="nom_participant_3" value="0">
              <input type="hidden" name="prenom_participant_3" value="0">
              <input type="hidden" name="nationalite_participant_3" value="0">
              <input type="hidden" name="assurance_3" value="0">

              <input type="hidden" name="titre_participant_4" value="0">
              <input type="hidden" name="nom_participant_4" value="0">
              <input type="hidden" name="prenom_participant_4" value="0">
              <input type="hidden" name="nationalite_participant_4" value="0">
              <input type="hidden" name="assurance_4" value="0">

            <?php
            }
            if ($adulte == 3) {
              ?>
              <input type="hidden" name="titre_participant_4" value="0">
              <input type="hidden" name="nom_participant_4" value="0">
              <input type="hidden" name="prenom_participant_4" value="0">
              <input type="hidden" name="nationalite_participant_4" value="0">
              <input type="hidden" name="assurance_4" value="0">

            <?php
            }

            ?>

          </div>




          <?php

          include ('sejour_ajout_personne/adulte3.php');
          include ('sejour_ajout_personne/adulte4.php');
          include ('sejour_ajout_personne/enfant1.php');
          include ('sejour_ajout_personne/enfant2.php');
          include ('sejour_ajout_personne/bebe.php');
          ?>

          <div class="col-sm-12" style="margin-top: 40px;">

            <a href="javascript:void(0)" class="botton-ajout" id="adulte_add_3"><i class="fa fa-plus"></i> Ajouter
              adulte</a>
            <a href="javascript:void(0)" class="botton-ajout" id="adulte_add_4" style="display:none"><i
                class="fa fa-plus"></i> Ajouter adulte</a>

            <a href="javascript:void(0)" class="botton-ajout" id="adulte_add_fin" style="display:none;background: red">Fin
              ajout adulte</a>





            <a href="javascript:void(0)" class="botton-ajout" id="enfant_add_1"><i class="fa fa-plus"></i> Ajouter
              enfant</a>
            <a href="javascript:void(0)" class="botton-ajout" id="enfant_add_2" style="display:none"><i
                class="fa fa-plus"></i> Ajouter enfant</a>

            <a href="javascript:void(0)" class="botton-ajout" id="enfant_add_fin" style="display:none;background: red">Fin
              ajout enfant</a>

            <a href="javascript:void(0)" class="botton-ajout" id="bebe_add"><i class="fa fa-plus"></i> Ajouter bébé</a>

            <a href="javascript:void(0)" class="botton-ajout" id="bebe_add_fin" style="display:none;background: red">Fin
              ajout bébé</a>


            <a href="javascript:void(0)" class="botton-ajout" id="ajout_remove"
              style="display:none; background: #1db2ec"><i class="fa fa-plus"></i> Supprimer les ajouts</a>



            <input type="hidden" name="nombre_adulte" value="<?php echo $adulte; ?>">
            <input type="hidden" name="nombre_enfant" value="0">
            <input type="hidden" name="nombre_bebe" value="0">
          </div>

          <script type="text/javascript">
            $("#adulte_add_3").click(function () {
              $("#adulte_add_3").hide();
              $("#adulte_add_4").show();
              $("#form_adulte_3").show();
              $("#ajout_remove").show();
              $("#table_adulte_3").show();

              document.form2.nombre_adulte.value = 3;
            });
            $("#adulte_add_4").click(function () {
              $("#adulte_add_4").hide();
              $("#adulte_add_fin").show();
              $("#form_adulte_4").show();
              $("#table_adulte_1").show();
              document.form2.nombre_adulte.value = 4;
            });


            $("#enfant_add_1").click(function () {
              $("#enfant_add_1").hide();
              $("#enfant_add_2").show();
              $("#form_enfant_1").show();
              $("#ajout_remove").show();
              $("#table_enfant_1").show();
              document.form2.nombre_enfant.value = 1;
            });

            $("#enfant_add_2").click(function () {
              $("#enfant_add_2").hide();
              $("#enfant_add_fin").show();
              $("#form_enfant_2").show();
              $("#table_enfant_2").show();
              document.form2.nombre_enfant.value = 2;
            });

            $("#bebe_add").click(function () {
              $("#bebe_add").hide();
              $("#bebe_add_fin").show();
              $("#form_bebe").show();
              $("#ajout_remove").show();
              $("#table_bebe_1").show();
              document.form2.nombre_bebe.value = 1;
            });

            $("#ajout_remove").click(function () {

              $("#table_adulte_3").hide();
              $("#table_adulte_4").hide();
              $("#table_enfant_1").hide();
              $("#table_enfant_2").hide();
              $("#table_bebe_1").hide();


              $("#adulte_add_3").show();
              $("#adulte_add_4").hide();
              $("#enfant_add_1").show();
              $("#enfant_add_2").hide();
              $("#bebe_add").show();

              $("#adulte_add_fin").hide();
              $("#enfant_add_fin").hide();
              $("#bebe_add_fin").hide();

              $("#form_adulte_3").hide();
              $("#form_adulte_4").hide();
              $("#form_enfant_1").hide();
              $("#form_enfant_2").hide();
              $("#form_bebe").hide();
              $("#ajout_remove").hide();

              document.form2.nombre_adulte.value = <?php echo $adulte; ?>;
              document.form2.nombre_enfant.value = 0;
              document.form2.nombre_bebe.value = 0;
            });

          </script>




          <div class="col-sm-12">
            <p>&nbsp;</p>
            <h4 style="color: #FFF;font-weight: 700;text-transform: uppercase;font-size: 20px;padding: 18px 0;">
              <span
                style="background: #000;padding: 20px 28px 19px 20px;color: #FFF;border-top-right-radius: 10px;border-bottom-right-radius: 10px;z-index: 9;"><i
                  class="fa fa-book" style="font-size: 34px;"></i></span>
              <span
                style="background: #f68730;padding: 5px 28px 5px 20px;color: #FFF;z-index: -1;left: -18px;position: relative">RÉSUMÉ
                DE VOTRE DEVIS A L'HÔTEL (
                <?php echo str_replace('HOTEL - ', '', $hotel); ?>)
              </span>
            </h4>
          </div>









          <script type="text/javascript">
            function radio1adulte(radio) {

              var val10 = $('[name=assurance_1]:checked').val();

              let text = val10;
              const myArray = text.split("-");

              var val1 = myArray[0];
              var val2 = myArray[1];
              var val3 = myArray[2];
              var val4 = myArray[3];

              // document.form2.check_assurance_adulte1.value=val1.toFixed(2);
              document.getElementById("nom_assurance_1").innerHTML = val3;
              document.getElementById("prestation_assurance_1").innerHTML = val4;
              document.form2.check_assurance_adulte1.value = val1;
              document.form2.id_assurance_1.value = val2;
              document.form2.check_assurance_adulte.value = val1;

              $("#assurance_show").show();
              $("#assurance_show1").show();




              var tot1 = document.form2.total_input_1.value;


              var totb = parseFloat(document.form2.totalb_initiale.value) + parseFloat(document.form2.ass_adulte2.value) + parseFloat(document.form2.ass_adulte3.value) + parseFloat(document.form2.ass_adulte4.value) + parseFloat(document.form2.ass_adulte5.value) + parseFloat(document.form2.ass_adulte6.value) + parseFloat(document.form2.ass_adulte7.value);


              var totbb = parseFloat(totb) + parseFloat(val1);

              <?php
              if (isset($_POST['ass_adulte1'])) {
                ?>
                var ass_moin = document.form2.ass_adulte1_1.value;
                var total = parseFloat(val1) + parseFloat(tot1) - parseFloat(ass_moin);


                <?php
              } else {
                ?>
                var total = parseFloat(val1) + parseFloat(tot1);
                <?php
              }
              ?>


              document.getElementById("assurance_1").innerHTML = parseFloat(val1).toFixed(2);

              //document.getElementById("total_1").innerHTML = parseFloat(total).toFixed(2);
              document.getElementById("totalb").innerHTML = parseFloat(totbb).toFixed(2);


              document.form2.prix_total_total.value = parseFloat(totbb).toFixed(2);
              document.form2.ass_adulte1.value = parseFloat(val1).toFixed(2);
              document.form2.ass_adulte1_1.value = parseFloat(val1).toFixed(2);




            }

            function radio2adulte(radio) {

              var val10 = $('[name=assurance_2]:checked').val();

              let text = val10;
              const myArray = text.split("-");

              var val1 = myArray[0];
              var val2 = myArray[1];
              var val3 = myArray[2];
              var val4 = myArray[3];

              document.getElementById("nom_assurance_2").innerHTML = val3;
              document.getElementById("prestation_assurance_2").innerHTML = val4;

              document.form2.check_assurance_adulte12.value = val1;
              document.form2.id_assurance_2.value = val2;
              document.form2.check_assurance_adulte2.value = val1;

              $("#assurance_show").show();
              $("#assurance_show2").show();


              var tot1 = document.form2.total_input_2.value;


              var totb = parseFloat(document.form2.totalb_initiale.value) + parseFloat(document.form2.ass_adulte1.value) + parseFloat(document.form2.ass_adulte3.value) + parseFloat(document.form2.ass_adulte4.value) + parseFloat(document.form2.ass_adulte5.value) + parseFloat(document.form2.ass_adulte6.value) + parseFloat(document.form2.ass_adulte7.value);
              var totbb = parseFloat(totb) + parseFloat(val1);

              <?php
              if (isset($_POST['ass_adulte2'])) {
                ?>
                var ass_moin = document.form2.ass_adulte2_2.value;
                var total = parseFloat(val1) + parseFloat(tot1) - parseFloat(ass_moin);
                <?php
              } else {
                ?>
                var total = parseFloat(val1) + parseFloat(tot1);
                <?php
              }
              ?>


              document.getElementById("assurance_2").innerHTML = parseFloat(val1).toFixed(2);

              //document.getElementById("total_1").innerHTML = parseFloat(total).toFixed(2);
              document.getElementById("totalb").innerHTML = parseFloat(totbb).toFixed(2);


              document.form2.prix_total_total.value = parseFloat(totbb).toFixed(2);
              document.form2.ass_adulte1.value = parseFloat(val1).toFixed(2);
              document.form2.ass_adulte1_1.value = parseFloat(val1).toFixed(2);

            }

            function radio3adulte(radio) {

              var val10 = $('[name=assurance_3]:checked').val();

              let text = val10;
              const myArray = text.split("-");

              var val1 = myArray[0];
              var val2 = myArray[1];
              var val3 = myArray[2];
              var val4 = myArray[3];



              document.getElementById("nom_assurance_3").innerHTML = val3;
              document.getElementById("prestation_assurance_3").innerHTML = val4;
              document.form2.check_assurance_adulte13.value = val1;
              document.form2.id_assurance_3.value = val2;
              document.form2.check_assurance_adulte3.value = val1;

              $("#assurance_show").show();
              $("#assurance_show3").show();

              var tot1 = document.form2.total_input_3.value;
              var totb = parseFloat(document.form2.totalb_initiale.value) + parseFloat(document.form2.ass_adulte2.value) + parseFloat(document.form2.ass_adulte1.value) + parseFloat(document.form2.ass_adulte4.value) + parseFloat(document.form2.ass_adulte5.value) + parseFloat(document.form2.ass_adulte6.value) + parseFloat(document.form2.ass_adulte7.value);

              var totbb = parseFloat(totb) + parseFloat(val1);
              <?php
              if (isset($_POST['ass_adulte3'])) {
                ?>
                var ass_moin = document.form2.ass_adulte3_3.value;
                var total = parseFloat(val1) + parseFloat(tot1) - parseFloat(ass_moin);
                <?php
              } else {
                ?>
                var total = parseFloat(val1) + parseFloat(tot1);
                <?php
              }
              ?>


              alert(val1);

              document.getElementById("assurance_3").innerHTML = parseFloat(val1).toFixed(2);

              //document.getElementById("total_1").innerHTML = parseFloat(total).toFixed(2);
              document.getElementById("totalb").innerHTML = parseFloat(totbb).toFixed(2);


              document.form2.prix_total_total.value = parseFloat(totbb).toFixed(2);
              document.form2.ass_adulte1.value = parseFloat(val1).toFixed(2);
              document.form2.ass_adulte1_1.value = parseFloat(val1).toFixed(2);

            }

            function radio4adulte(radio) {
              var val10 = $('[name=assurance_4]:checked').val();

              let text = val10;
              const myArray = text.split("-");

              var val1 = myArray[0];
              var val2 = myArray[1];
              var val3 = myArray[2];
              var val4 = myArray[3];

              document.getElementById("nom_assurance_4").innerHTML = val3;
              document.getElementById("prestation_assurance_4").innerHTML = val4;
              document.form2.check_assurance_adulte14.value = val1;
              document.form2.id_assurance_4.value = val2;
              document.form2.check_assurance_adulte4.value = val1;

              $("#assurance_show").show();
              $("#assurance_show4").show();




              var tot1 = document.form2.total_input_4.value;
              var totb = parseFloat(document.form2.totalb_initiale.value) + parseFloat(document.form2.ass_adulte2.value) + parseFloat(document.form2.ass_adulte3.value) + parseFloat(document.form2.ass_adulte1.value) + parseFloat(document.form2.ass_adulte5.value) + parseFloat(document.form2.ass_adulte6.value) + parseFloat(document.form2.ass_adulte7.value);

              var totbb = parseFloat(totb) + parseFloat(val1);
              <?php
              if (isset($_POST['ass_adulte4'])) {
                ?>
                var ass_moin = document.form2.ass_adulte4_4.value;
                var total = parseFloat(val1) + parseFloat(tot1) - parseFloat(ass_moin);
                <?php
              } else {
                ?>
                var total = parseFloat(val1) + parseFloat(tot1);
                <?php
              }
              ?>


              document.getElementById("assurance_4").innerHTML = parseFloat(val1).toFixed(2);

              //document.getElementById("total_1").innerHTML = parseFloat(total).toFixed(2);
              document.getElementById("totalb").innerHTML = parseFloat(totbb).toFixed(2);


              document.form2.prix_total_total.value = parseFloat(totbb).toFixed(2);
              document.form2.ass_adulte1.value = parseFloat(val1).toFixed(2);
              document.form2.ass_adulte1_1.value = parseFloat(val1).toFixed(2);



            }



          </script>













          <div class="col-sm-12">
            <p>&nbsp;</p>

            <div class="divTable blueTable">
              <div class="divTableHeading">
                <div class="divTableRow">
                  <div class="divTableHead">Personne</div>

                  <div class="divTableHead">Vols</div>
                  <div class="divTableHead">Taxes aéroport</div>
                  <div class="divTableHead">Transferts</div>
                  <div class="divTableHead">Visa</div>
                  <div class="divTableHead">Hôtel</div>
                  <div class="divTableHead" style="width: 12%">Total</div>
                </div>
              </div>
              <div class="divTableBody">

                <?php

                $totalb = "0";


                $stmt = $conn->prepare('SELECT * FROM reservation_valeur_package');
                $stmt->execute();
                while ($reservation = $stmt->fetch(PDO::FETCH_OBJ)) {
                  if (Md5($reservation->id_reservation_valeur) == $xx) {
                    $id_reservation_valeur = $reservation->id_reservation_valeur;

                    $tab_tour = explode(" <br> ", $reservation->id_excursion);

                    $id_prestation_hotel = $reservation->id_total_autre;
                    $id_total_repas = $reservation->id_total_repas;
                    $id_prix_total_vol = $reservation->id_prix_total_vol;
                    $id_total_transfert = $reservation->id_total_transfert;

                    $id_excursion = explode(" <br> ", $reservation->id_excursion);

                    // ********************** OFFRE VOL **************************** //



                    ?>


                    <div class="divTableRow">
                      <div class="divTableCell"><span>Adulte 1</span></div>


                      <div class="divTableCell">
                        <?php
                        if ($id_prix_total_vol != "0") {
                          $stmt53 = $conn->prepare('SELECT * FROM vols WHERE idtxp =:idtxp');
                          $stmt53->bindValue('idtxp', $id_prix_total_vol);
                          $stmt53->execute();
                          $account53 = $stmt53->fetch(PDO::FETCH_OBJ);
                          if ($account53->adulte_total_eco == 0 or $account53->adulte_total_eco >= $account53->adulte_total) {


                            $prix_total_vol = $account53->adulte_total;
                            echo number_format(($prix_total_vol - $account53->adulte_taxe), 2, '.', ' ');
                            $adulte_taxe_1 = $account53->adulte_taxe;
                          } else {

                            $prix_total_vol = $account53->adulte_total_eco;
                            echo number_format(($prix_total_vol - $account53->adulte_taxe_eco), 2, '.', ' ');
                            $adulte_taxe_1 = $account53->adulte_taxe_eco;
                          }
                          //echo '<hr style="margin-top: 2px;margin-bottom: 2px;">';
                          echo '<br>';

                          $id_option_adulte_vol = explode('-', $reservation->id_option_adulte);




                          for ($p = 0; $p < count($id_option_adulte_vol); $p++) {
                            $stmt530 = $conn->prepare('SELECT * FROM option_vol_autre WHERE id_option =:id_option');
                            $stmt530->bindValue('id_option', $id_option_adulte_vol[$p]);
                            $stmt530->execute();
                            $account530 = $stmt530->fetch(PDO::FETCH_OBJ);
                            if (isset($account530->id_option)) {
                              echo '<small style="color:red;font-weight:bold;">+ ' . $account530->titre . ' ' . $account530->personne . ': ' . $account530->prix_total . '<br></small>';
                              //$prix_total_vol += $account530 -> prix_total;
                            }
                          }





                        } else {
                          $prix_total_vol = "0";
                          $adulte_taxe_1 = '<i class="fa fa-times" style="color: red;font-size: 25px;"></i>';
                          ?>
                          <i class="fa fa-times" style="color: red;font-size: 25px;"></i>
                        <?php
                        }
                        ?>
                        <INPUT TYPE="hidden" NAME="prix_total_vol"
                          value="<?php echo number_format($prix_total_vol, 2, '.', ' '); ?>">
                      </div>


                      <div class="divTableCell">

                        <?php echo $adulte_taxe_1; ?>
                      </div>



                      <div class="divTableCell">

                        <?php
                        if ($id_total_transfert != "0") {
                          $stmt53 = $conn->prepare('SELECT * FROM transfert WHERE id_transfert =:id_transfert');
                          $stmt53->bindValue('id_transfert', $id_total_transfert);
                          $stmt53->execute();
                          $account53 = $stmt53->fetch(PDO::FETCH_OBJ);


                          if ($account53->type == "Hydravion" or $account53->type == "Speedboat") {




                            if ($adulte == "1") {
                              $transfert_total = (int) $account53->adulte_2_total_1_h;
                            }
                            if ($adulte == "2") {
                              $transfert_total = (int) $account53->adulte_2_total_1_h;
                            }

                            if ($adulte == "3") {
                              $transfert_total = (int) $account53->adulte_2_total_1_h;
                            }
                            if ($adulte == "4") {
                              $transfert_total = (int) $account53->adulte_2_total_1_h;
                            }


                            echo number_format($transfert_total, 2, '.', ' ');

                          } else {

                            if ($adulte == "1") {
                              $transfert_total = (int) $account53->adulte_2_total_1;
                            }
                            if ($adulte == "2") {
                              $transfert_total = (int) $account53->adulte_2_total_2;
                            }

                            if ($adulte == "3") {
                              $transfert_total = (int) $account53->adulte_2_total_3;
                            }
                            if ($adulte == "4") {
                              $transfert_total = (int) $account53->adulte_2_total_4;
                            }


                            echo number_format($transfert_total, 2, '.', ' ');


                          }
                          echo '<br>';

                          $visa_trasfert = 0;
                          $id_option_adulte_transfert = explode('-', $reservation->id_option_autre_transfert);

                          for ($p = 0; $p < count($id_option_adulte_transfert); $p++) {
                            $stmt530 = $conn->prepare('SELECT * FROM option_transfert_autre WHERE id_option =:id_option');
                            $stmt530->bindValue('id_option', $id_option_adulte_transfert[$p]);
                            $stmt530->execute();
                            $account530 = $stmt530->fetch(PDO::FETCH_OBJ);
                            if (isset($account530->id_option)) {
                              // echo '<br><small style="color:red;font-weight:bold">+ '.$account530 -> titre.' '.$account530 -> personne.': '.$account530 -> prix_total.'</small>';
                              //$transfert_total += $account530 -> prix_total;
                              $visa_trasfert += $account530->prix_total;
                            }
                          }


                          $id_prix_transfert_obligatoire = explode('-', $reservation->id_prix_transfert_obligatoire);

                          for ($p = 0; $p < count($id_prix_transfert_obligatoire); $p++) {
                            $stmt530 = $conn->prepare('SELECT * FROM option_transfert_autre WHERE id_option =:id_option AND personne =:personne');
                            $stmt530->bindValue('id_option', $id_prix_transfert_obligatoire[$p]);
                            $stmt530->bindValue('personne', "Adulte");
                            $stmt530->execute();
                            $account530 = $stmt530->fetch(PDO::FETCH_OBJ);
                            if (isset($account530->id_option)) {
                              //echo '<br><small style="color:green;font-weight:bold">* '.$account530 -> titre.' '.$account530 -> personne.': '.$account530 -> prix_total.'</small>';
                              //$transfert_total += $account530 -> prix_total;
                              $visa_trasfert += $account530->prix_total;
                            }
                          }




                        } else {
                          $transfert_total = "0";
                          $visa_trasfert = 0;
                          ?>
                          <i class="fa fa-times" style="color: red;font-size: 25px;"></i>
                        <?php
                        }







                        ?>
                        <INPUT TYPE="hidden" NAME="prix_transfert_a1"
                          value="<?php echo number_format($transfert_total, 2, '.', ' '); ?>">

                      </div>

                      <div class="divTableCell">
                        <?php echo $visa_trasfert; ?>
                      </div>

                      <div class="divTableCell">
                        <?php echo number_format($adulte10, 2, '.', ' '); ?> <br>

                        <INPUT TYPE="hidden" NAME="prix_chambre_a1"
                          value="<?php echo number_format($adulte10, 2, '.', ' '); ?>">
                        <INPUT TYPE="hidden" NAME="ass_adulte1" value="0">

                      </div>



                      <div class="divTableCell" id="total_1" style="font-weight:bold"><b>
                          <?php
                          if (isset($_POST['total_adulte1'])) {
                            echo $total_adulte1 = $_POST['total_adulte1'];
                            $totalb += $total_adulte1;
                          } else {


                            $total_adulte1 = $adulte10 + $transfert_total + $prix_total_vol + $visa_trasfert;
                            echo number_format($total_adulte1, 2, '.', ' ');
                            $totalb += $total_adulte1;
                          }
                          ?>
                        </b>
                      </div>
                    </div>



                    <INPUT TYPE="hidden" NAME="prix_visa_a1" value="0">


                    <?php
                    if ($adulte == "1") {
                      ?>

                      <INPUT TYPE="hidden" NAME="prix_visa_a1" value="0">


                      <INPUT TYPE="hidden" NAME="prix_chambre_a2" value="0">
                      <INPUT TYPE="hidden" NAME="prix_transfert_a2" value="0">
                      <INPUT TYPE="hidden" NAME="prix_visa_a2" value="0">
                      <INPUT TYPE="hidden" NAME="prix_option_a2" value="0">
                      <INPUT TYPE="hidden" NAME="prix_tour_a2" value="0">
                      <INPUT TYPE="hidden" NAME="prix_repas_a2" value="0">
                      <INPUT TYPE="hidden" NAME="prix_total_a2" value="0">

                      <INPUT TYPE="hidden" NAME="prix_chambre_a3" value="0">
                      <INPUT TYPE="hidden" NAME="prix_transfert_a3" value="0">
                      <INPUT TYPE="hidden" NAME="prix_visa_a3" value="0">
                      <INPUT TYPE="hidden" NAME="prix_option_a3" value="0">
                      <INPUT TYPE="hidden" NAME="prix_tour_a3" value="0">
                      <INPUT TYPE="hidden" NAME="prix_repas_a3" value="0">
                      <INPUT TYPE="hidden" NAME="prix_total_a3" value="0">

                      <INPUT TYPE="hidden" NAME="prix_chambre_a4" value="0">
                      <INPUT TYPE="hidden" NAME="prix_transfert_a4" value="0">
                      <INPUT TYPE="hidden" NAME="prix_visa_a4" value="0">
                      <INPUT TYPE="hidden" NAME="prix_option_a4" value="0">
                      <INPUT TYPE="hidden" NAME="prix_tour_a4" value="0">
                      <INPUT TYPE="hidden" NAME="prix_repas_a4" value="0">
                      <INPUT TYPE="hidden" NAME="prix_total_a4" value="0">

                      <INPUT TYPE="hidden" NAME="ass_adulte2" value="0">
                      <INPUT TYPE="hidden" NAME="ass_adulte3" value="0">
                      <INPUT TYPE="hidden" NAME="ass_adulte4" value="0">





                      <?php
                    }
                    ?>





                    <?php
                    if ($adulte >= "2") {




                      ?>













                      <div class="divTableRow">
                        <div class="divTableCell"><span>Adulte 2</span></div>


                        <div class="divTableCell">
                          <?php


                          if ($id_prix_total_vol != "0") {

                            $stmt53 = $conn->prepare('SELECT * FROM vols WHERE idtxp =:idtxp');
                            $stmt53->bindValue('idtxp', $id_prix_total_vol);
                            $stmt53->execute();
                            $account53 = $stmt53->fetch(PDO::FETCH_OBJ);

                            if ($account53->adulte_total_eco == 0 or $account53->adulte_total_eco >= $account53->adulte_total) {
                              $prix_total_vol = $account53->adulte_total;
                              echo number_format(($prix_total_vol - $account53->adulte_taxe), 2, '.', ' ');
                              $adulte_taxe_1 = $account53->adulte_taxe;
                            } else {

                              $prix_total_vol = $account53->adulte_total_eco;
                              echo number_format(($prix_total_vol - $account53->adulte_taxe_eco), 2, '.', ' ');
                              $adulte_taxe_1 = $account53->adulte_taxe_eco;
                            }



                            $id_option_adulte_vol = explode('-', $reservation->id_option_adulte);

                            for ($p = 0; $p < count($id_option_adulte_vol); $p++) {
                              $stmt530 = $conn->prepare('SELECT * FROM option_vol_autre WHERE id_option =:id_option');
                              $stmt530->bindValue('id_option', $id_option_adulte_vol[$p]);
                              $stmt530->execute();
                              $account530 = $stmt530->fetch(PDO::FETCH_OBJ);
                              if (isset($account530->id_option)) {
                                echo '<br><small style="color:red;font-weight:bold">+ ' . $account530->titre . ' ' . $account530->personne . ': ' . $account530->prix_total . '</small>';
                                $prix_total_vol += $account530->prix_total;
                              }
                            }



                          } else {
                            $prix_total_vol = "0";
                            $adulte_taxe_1 = '<i class="fa fa-times" style="color: red;font-size: 25px;"></i>';
                            ?>
                            <i class="fa fa-times" style="color: red;font-size: 25px;"></i>
                          <?php
                          }
                          ?>
                          <INPUT TYPE="hidden" NAME="prix_total_vol"
                            value="<?php echo number_format($prix_total_vol, 2, '.', ' '); ?>">
                        </div>

                        <div class="divTableCell">

                          <?php echo $adulte_taxe_1; ?>
                        </div>


                        <div class="divTableCell">

                          <?php
                          if ($id_total_transfert != "0") {
                            $stmt53 = $conn->prepare('SELECT * FROM transfert WHERE id_transfert =:id_transfert');
                            $stmt53->bindValue('id_transfert', $id_total_transfert);
                            $stmt53->execute();
                            $account53 = $stmt53->fetch(PDO::FETCH_OBJ);

                            if ($account53->type == "Hydravion" or $account53->type == "Speedboat") {




                              if ($adulte == "1") {
                                $transfert_total = (int) $account53->adulte_2_total_1_h;
                              }
                              if ($adulte == "2") {
                                $transfert_total = (int) $account53->adulte_2_total_1_h;
                              }

                              if ($adulte == "3") {
                                $transfert_total = (int) $account53->adulte_2_total_1_h;
                              }
                              if ($adulte == "4") {
                                $transfert_total = (int) $account53->adulte_2_total_1_h;
                              }


                              echo number_format($transfert_total, 2, '.', ' ');

                            } else {

                              if ($adulte == "1") {
                                $transfert_total = (int) $account53->adulte_2_total_1;
                              }
                              if ($adulte == "2") {
                                $transfert_total = (int) $account53->adulte_2_total_2;
                              }

                              if ($adulte == "3") {
                                $transfert_total = (int) $account53->adulte_2_total_3;
                              }
                              if ($adulte == "4") {
                                $transfert_total = (int) $account53->adulte_2_total_4;
                              }


                              echo number_format($transfert_total, 2, '.', ' ');


                            }
                            echo '<br>';


                            $id_option_adulte_transfert = explode('-', $reservation->id_option_autre_transfert);
                            $visa_trasfert = 0;

                            for ($p = 0; $p < count($id_option_adulte_transfert); $p++) {
                              $stmt530 = $conn->prepare('SELECT * FROM option_transfert_autre WHERE id_option =:id_option');
                              $stmt530->bindValue('id_option', $id_option_adulte_transfert[$p]);
                              $stmt530->execute();
                              $account530 = $stmt530->fetch(PDO::FETCH_OBJ);
                              if (isset($account530->id_option)) {
                                //echo '<br><small style="color:red;font-weight:bold">+ '.$account530 -> titre.' '.$account530 -> personne.': '.$account530 -> prix_total.' CHF</small>';
                                $visa_trasfert += $account530->prix_total;
                              }
                            }
                            $id_prix_transfert_obligatoire = explode('-', $reservation->id_prix_transfert_obligatoire);

                            for ($p = 0; $p < count($id_prix_transfert_obligatoire); $p++) {
                              $stmt530 = $conn->prepare('SELECT * FROM option_transfert_autre WHERE id_option =:id_option AND personne =:personne');
                              $stmt530->bindValue('id_option', $id_prix_transfert_obligatoire[$p]);
                              $stmt530->bindValue('personne', "Adulte");
                              $stmt530->execute();
                              $account530 = $stmt530->fetch(PDO::FETCH_OBJ);
                              if (isset($account530->id_option)) {
                                //echo '<br><small style="color:green;font-weight:bold">* '.$account530 -> titre.' '.$account530 -> personne.': '.$account530 -> prix_total.' CHF</small>';
                                $visa_trasfert += $account530->prix_total;
                              }
                            }



                          } else {
                            $transfert_total = "0";
                            $visa_trasfert = "0";
                            ?>
                            <i class="fa fa-times" style="color: red;font-size: 25px;"></i>
                          <?php
                          }
                          ?>
                          <INPUT TYPE="hidden" NAME="prix_transfert_a2"
                            value="<?php echo number_format($transfert_total, 2, '.', ' '); ?>">
                        </div>

                        <div class="divTableCell">
                          <?php echo $visa_trasfert; ?>
                        </div>

                        <div class="divTableCell">

                          <?php
                          echo number_format($adulte20, 2, '.', ' ');
                          ?> <br>
                          <INPUT TYPE="hidden" NAME="ass_adulte2" value="0">


                        </div>




                        <div class="divTableCell" id="total_2" style="font-weight: bold;">
                          <b>
                            <?php
                            if (isset($_POST['total_adulte2'])) {
                              echo $total_adulte2 = $_POST['total_adulte2'];
                              $totalb += $total_adulte2;
                            } else {
                              $total_adulte2 = $adulte20 + $transfert_total + $prix_total_vol + $visa_trasfert;
                              echo number_format($total_adulte2, 2, '.', ' ');
                              $totalb += $total_adulte2;
                            }
                            ?>




                          </b>

                        </div>
                      </div>


                      <div class="divTableRow" id="table_adulte_3" style="display: none;">
                        <div class="divTableCell"><span>Adulte 3</span></div>
                        <div class="divTableCell">0</div>
                        <div class="divTableCell">0</div>
                        <div class="divTableCell">0</div>
                        <div class="divTableCell">0</div>
                        <div class="divTableCell">0</div>
                        <div class="divTableCell">0</div>
                      </div>
                      <div class="divTableRow" id="table_adulte_4" style="display: none;">
                        <div class="divTableCell"><span>Adulte 4</span></div>
                        <div class="divTableCell">0</div>
                        <div class="divTableCell">0</div>
                        <div class="divTableCell">0</div>
                        <div class="divTableCell">0</div>
                        <div class="divTableCell">0</div>
                        <div class="divTableCell">0</div>
                      </div>
                      <div class="divTableRow" id="table_enfant_1" style="display: none;">
                        <div class="divTableCell"><span>Enfant 1</span></div>
                        <div class="divTableCell">0</div>
                        <div class="divTableCell">0</div>
                        <div class="divTableCell">0</div>
                        <div class="divTableCell">0</div>
                        <div class="divTableCell">0</div>
                        <div class="divTableCell">0</div>
                      </div>
                      <div class="divTableRow" id="table_enfant_2" style="display: none;">
                        <div class="divTableCell"><span>Enfant 2</span></div>
                        <div class="divTableCell">0</div>
                        <div class="divTableCell">0</div>
                        <div class="divTableCell">0</div>
                        <div class="divTableCell">0</div>
                        <div class="divTableCell">0</div>
                        <div class="divTableCell">0</div>
                      </div>
                      <div class="divTableRow" id="table_bebe_1" style="display: none;">
                        <div class="divTableCell"><span>Bébé</span></div>
                        <div class="divTableCell">0</div>
                        <div class="divTableCell">0</div>
                        <div class="divTableCell">0</div>
                        <div class="divTableCell">0</div>
                        <div class="divTableCell">0</div>
                        <div class="divTableCell">0</div>
                      </div>



                      <INPUT TYPE="hidden" NAME="prix_chambre_a2" value="<?php echo number_format($adulte20, 2, '.', ' '); ?>">
                      <INPUT TYPE="hidden" NAME="prix_visa_a2"
                        value="<?php if ($reservation->adulte_visa_2 != '') {
                          echo number_format($reservation->adulte_visa_2, 2, '.', ' ');
                        } else {
                          echo '0.00';
                        } ?>">
                      <INPUT TYPE="hidden" NAME="prix_option_a2"
                        value="<?php echo number_format($prestation_hotel, 2, '.', ' '); ?>">
                      <INPUT TYPE="hidden" NAME="prix_tour_a2" value="<?php echo $tour_adulte1; ?>">
                      <INPUT TYPE="hidden" NAME="prix_repas_a2" value="<?php echo $repas_hotel; ?>">
                      <INPUT TYPE="hidden" NAME="prix_total_a2"
                        value="<?php echo number_format($total_adulte2, 2, '.', ' '); ?>">

                      <INPUT TYPE="hidden" NAME="prix_chambre_a3" value="0">
                      <INPUT TYPE="hidden" NAME="prix_transfert_a3" value="0">
                      <INPUT TYPE="hidden" NAME="prix_visa_a3" value="0">
                      <INPUT TYPE="hidden" NAME="prix_option_a3" value="0">
                      <INPUT TYPE="hidden" NAME="prix_tour_a3" value="0">
                      <INPUT TYPE="hidden" NAME="prix_repas_a3" value="0">
                      <INPUT TYPE="hidden" NAME="prix_total_a3" value="0">

                      <INPUT TYPE="hidden" NAME="prix_chambre_a4" value="0">
                      <INPUT TYPE="hidden" NAME="prix_transfert_a4" value="0">
                      <INPUT TYPE="hidden" NAME="prix_visa_a4" value="0">
                      <INPUT TYPE="hidden" NAME="prix_option_a4" value="0">
                      <INPUT TYPE="hidden" NAME="prix_tour_a4" value="0">
                      <INPUT TYPE="hidden" NAME="prix_repas_a4" value="0">
                      <INPUT TYPE="hidden" NAME="prix_total_a4" value="0">

                      <INPUT TYPE="hidden" NAME="ass_adulte3" value="0">
                      <INPUT TYPE="hidden" NAME="ass_adulte4" value="0">



                      <INPUT TYPE="hidden" NAME="total_input_2" value="<?php echo $total_adulte2_new; ?>">
                      <INPUT TYPE="hidden" NAME="total_adulte2" value="<?php echo $total_adulte2; ?>">


                      <?php
                    }
                    ?>







                    <?php
                    if ($adulte >= "3") {
                      ?>








                      <div class="divTableRow">
                        <div class="divTableCell"><span>Adulte 3</span></div>

                        <div class="divTableCell">
                          <?php
                          if ($id_prix_total_vol != "0") {
                            $stmt53 = $conn->prepare('SELECT * FROM vols WHERE idtxp =:idtxp');
                            $stmt53->bindValue('idtxp', $id_prix_total_vol);
                            $stmt53->execute();
                            $account53 = $stmt53->fetch(PDO::FETCH_OBJ);

                            if ($account53->adulte_total_eco == 0 or $account53->adulte_total_eco >= $account53->adulte_total) {
                              $prix_total_vol = $account53->adulte_total;
                              echo number_format(($prix_total_vol - $account53->adulte_taxe), 2, '.', ' ');
                              $adulte_taxe_1 = $account53->adulte_taxe;
                            } else {

                              $prix_total_vol = $account53->adulte_total_eco;
                              echo number_format(($prix_total_vol - $account53->adulte_taxe_eco), 2, '.', ' ');
                              $adulte_taxe_1 = $account53->adulte_taxe_eco;
                            }


                            $id_option_adulte_vol = explode('-', $reservation->id_option_adulte);

                            for ($p = 0; $p < count($id_option_adulte_vol); $p++) {
                              $stmt530 = $conn->prepare('SELECT * FROM option_vol_autre WHERE id_option =:id_option');
                              $stmt530->bindValue('id_option', $id_option_adulte_vol[$p]);
                              $stmt530->execute();
                              $account530 = $stmt530->fetch(PDO::FETCH_OBJ);
                              if (isset($account530->id_option)) {
                                echo '<br><small style="color:red;font-weight:bold">+ ' . $account530->titre . ' ' . $account530->personne . ': ' . $account530->prix_total . ' CHF</small>';
                                $prix_total_vol += $account530->prix_total;
                              }
                            }




                          } else {
                            $prix_total_vol = "0";
                            $adulte_taxe_1 = '<i class="fa fa-times" style="color: red;font-size: 25px;"></i>';
                            ?>
                            <i class="fa fa-times" style="color: red;font-size: 25px;"></i>
                          <?php
                          }
                          ?>
                          <INPUT TYPE="hidden" NAME="prix_total_vol"
                            value="<?php echo number_format($prix_total_vol, 2, '.', ' '); ?>">
                        </div>

                        <div class="divTableCell">

                          <?php echo $adulte_taxe_1; ?>
                        </div>


                        <div class="divTableCell">

                          <?php
                          if ($id_total_transfert != "0") {
                            $stmt53 = $conn->prepare('SELECT * FROM transfert WHERE id_transfert =:id_transfert');
                            $stmt53->bindValue('id_transfert', $id_total_transfert);
                            $stmt53->execute();
                            $account53 = $stmt53->fetch(PDO::FETCH_OBJ);


                            if ($account53->type == "Hydravion" or $account53->type == "Speedboat") {




                              if ($adulte == "1") {
                                $transfert_total = (int) $account53->adulte_2_total_1_h;
                              }
                              if ($adulte == "2") {
                                $transfert_total = (int) $account53->adulte_2_total_1_h;
                              }

                              if ($adulte == "3") {
                                $transfert_total = (int) $account53->adulte_2_total_1_h;
                              }
                              if ($adulte == "4") {
                                $transfert_total = (int) $account53->adulte_2_total_1_h;
                              }


                              echo number_format($transfert_total, 2, '.', ' ');

                            } else {

                              if ($adulte == "1") {
                                $transfert_total = (int) $account53->adulte_2_total_1;
                              }
                              if ($adulte == "2") {
                                $transfert_total = (int) $account53->adulte_2_total_2;
                              }

                              if ($adulte == "3") {
                                $transfert_total = (int) $account53->adulte_2_total_3;
                              }
                              if ($adulte == "4") {
                                $transfert_total = (int) $account53->adulte_2_total_4;
                              }


                              echo number_format($transfert_total, 2, '.', ' ');


                            }
                            echo '<br>';




                            $id_option_adulte_transfert = explode('-', $reservation->id_option_autre_transfert);

                            $visa_trasfert = 0;
                            for ($p = 0; $p < count($id_option_adulte_transfert); $p++) {
                              $stmt530 = $conn->prepare('SELECT * FROM option_transfert_autre WHERE id_option =:id_option');
                              $stmt530->bindValue('id_option', $id_option_adulte_transfert[$p]);
                              $stmt530->execute();
                              $account530 = $stmt530->fetch(PDO::FETCH_OBJ);
                              if (isset($account530->id_option)) {
                                //echo '<br><small style="color:red;font-weight:bold">+ '.$account530 -> titre.' '.$account530 -> personne.': '.$account530 -> prix_total.' CHF</small>';
                                $visa_trasfert += $account530->prix_total;
                              }
                            }
                            $id_prix_transfert_obligatoire = explode('-', $reservation->id_prix_transfert_obligatoire);

                            for ($p = 0; $p < count($id_prix_transfert_obligatoire); $p++) {
                              $stmt530 = $conn->prepare('SELECT * FROM option_transfert_autre WHERE id_option =:id_option AND personne =:personne');
                              $stmt530->bindValue('id_option', $id_prix_transfert_obligatoire[$p]);
                              $stmt530->bindValue('personne', "Adulte");
                              $stmt530->execute();
                              $account530 = $stmt530->fetch(PDO::FETCH_OBJ);
                              if (isset($account530->id_option)) {
                                //echo '<br><small style="color:green;font-weight:bold">* '.$account530 -> titre.' '.$account530 -> personne.': '.$account530 -> prix_total.' CHF</small>';
                                $visa_trasfert += $account530->prix_total;
                              }
                            }

                          } else {
                            $transfert_total = "0";
                            $visa_trasfert = "0";
                            ?>
                            <i class="fa fa-times" style="color: red;font-size: 25px;"></i>
                          <?php
                          }
                          ?>
                          <INPUT TYPE="hidden" NAME="prix_transfert_a3"
                            value="<?php echo number_format($transfert_total, 2, '.', ' '); ?>">
                        </div>

                        <div class="divTableCell">
                          <?php echo $visa_trasfert; ?>
                        </div>

                        <div class="divTableCell">

                          <?php
                          echo number_format($adulte30, 2, '.', ' ');
                          ?> <br>
                          <INPUT TYPE="hidden" NAME="ass_adulte3" value="0">

                        </div>

                        <div class="divTableCell" id="total_3" style="font-weight: bold;">
                          <b>
                            <?php
                            if (isset($_POST['total_adulte3'])) {
                              echo $total_adulte3 = $_POST['total_adulte3'];
                              $totalb += $total_adulte3;
                            } else {
                              $total_adulte3 = $adulte30 + $transfert_total + $prix_total_vol + $visa_trasfert;
                              echo number_format($total_adulte3, 2, '.', ' ');
                              $totalb += $total_adulte3;
                            }
                            ?>




                          </b>

                        </div>
                      </div>


                      <INPUT TYPE="hidden" NAME="prix_chambre_a3" value="<?php echo number_format($adulte30, 2, '.', ' '); ?>">
                      <INPUT TYPE="hidden" NAME="prix_visa_a3"
                        value="<?php echo number_format($reservation->adulte_visa_3, 2, '.', ' '); ?>">
                      <INPUT TYPE="hidden" NAME="prix_option_a3"
                        value="<?php echo number_format($prestation_hotel, 2, '.', ' '); ?>">
                      <INPUT TYPE="hidden" NAME="prix_tour_a3" value="<?php echo $tour_adulte1; ?>">
                      <INPUT TYPE="hidden" NAME="prix_repas_a1" value="<?php echo number_format($repas_hotel, 2, '.', ' '); ?>">
                      <INPUT TYPE="hidden" NAME="prix_total_a3"
                        value="<?php echo number_format($total_adulte3, 2, '.', ' '); ?>">

                      <INPUT TYPE="hidden" NAME="prix_chambre_a4" value="0">
                      <INPUT TYPE="hidden" NAME="prix_transfert_a4" value="0">
                      <INPUT TYPE="hidden" NAME="prix_visa_a4" value="0">
                      <INPUT TYPE="hidden" NAME="prix_option_a4" value="0">
                      <INPUT TYPE="hidden" NAME="prix_tour_a4" value="0">
                      <INPUT TYPE="hidden" NAME="prix_repas_a4" value="0">
                      <INPUT TYPE="hidden" NAME="prix_total_a4" value="0">

                      <INPUT TYPE="hidden" NAME="ass_adulte4" value="0">

                      <INPUT TYPE="hidden" NAME="total_input_3" value="<?php echo $total_adulte3_new; ?>">
                      <INPUT TYPE="hidden" NAME="total_adulte3" value="<?php echo $total_adulte3; ?>">


                      <?php
                    }
                    ?>










                    <?php
                    if ($adulte >= "4") {
                      ?>








                      <div class="divTableRow">
                        <div class="divTableCell"><span>Adulte 4</span></div>

                        <div class="divTableCell">
                          <?php
                          if ($id_prix_total_vol != "0") {
                            $stmt53 = $conn->prepare('SELECT * FROM vols WHERE idtxp =:idtxp');
                            $stmt53->bindValue('idtxp', $id_prix_total_vol);
                            $stmt53->execute();
                            $account53 = $stmt53->fetch(PDO::FETCH_OBJ);

                            if ($account53->adulte_total_eco == 0 or $account53->adulte_total_eco >= $account53->adulte_total) {
                              $prix_total_vol = $account53->adulte_total;
                              echo number_format(($prix_total_vol - $account53->adulte_taxe), 2, '.', ' ');
                              $adulte_taxe_1 = $account53->adulte_taxe;
                            } else {

                              $prix_total_vol = $account53->adulte_total_eco;
                              echo number_format(($prix_total_vol - $account53->adulte_taxe_eco), 2, '.', ' ');
                              $adulte_taxe_1 = $account53->adulte_taxe_eco;
                            }

                            $id_option_adulte_vol = explode('-', $reservation->id_option_adulte);

                            for ($p = 0; $p < count($id_option_adulte_vol); $p++) {
                              $stmt530 = $conn->prepare('SELECT * FROM option_vol_autre WHERE id_option =:id_option');
                              $stmt530->bindValue('id_option', $id_option_adulte_vol[$p]);
                              $stmt530->execute();
                              $account530 = $stmt530->fetch(PDO::FETCH_OBJ);
                              if (isset($account530->id_option)) {
                                echo '<br><small style="color:red;font-weight:bold">+ ' . $account530->titre . ' ' . $account530->personne . ': ' . $account530->prix_total . ' CHF</small>';
                                $prix_total_vol += $account530->prix_total;
                              }
                            }



                          } else {
                            $prix_total_vol = "0";
                            $adulte_taxe_1 = '<i class="fa fa-times" style="color: red;font-size: 25px;"></i>';
                            ?>
                            <i class="fa fa-times" style="color: red;font-size: 25px;"></i>
                          <?php
                          }
                          ?>
                          <INPUT TYPE="hidden" NAME="prix_total_vol"
                            value="<?php echo number_format($prix_total_vol, 2, '.', ' '); ?>">
                        </div>
                        <div class="divTableCell">

                          <?php echo $adulte_taxe_1; ?>
                        </div>
                        <div class="divTableCell">

                          <?php
                          if ($id_total_transfert != "0") {
                            $stmt53 = $conn->prepare('SELECT * FROM transfert WHERE id_transfert =:id_transfert');
                            $stmt53->bindValue('id_transfert', $id_total_transfert);
                            $stmt53->execute();
                            $account53 = $stmt53->fetch(PDO::FETCH_OBJ);

                            if ($account53->type == "Hydravion" or $account53->type == "Speedboat") {




                              if ($adulte == "1") {
                                $transfert_total = (int) $account53->adulte_2_total_1_h;
                              }
                              if ($adulte == "2") {
                                $transfert_total = (int) $account53->adulte_2_total_1_h;
                              }

                              if ($adulte == "3") {
                                $transfert_total = (int) $account53->adulte_2_total_1_h;
                              }
                              if ($adulte == "4") {
                                $transfert_total = (int) $account53->adulte_2_total_1_h;
                              }


                              echo number_format($transfert_total, 2, '.', ' ');

                            } else {

                              if ($adulte == "1") {
                                $transfert_total = (int) $account53->adulte_2_total_1;
                              }
                              if ($adulte == "2") {
                                $transfert_total = (int) $account53->adulte_2_total_2;
                              }

                              if ($adulte == "3") {
                                $transfert_total = (int) $account53->adulte_2_total_3;
                              }
                              if ($adulte == "4") {
                                $transfert_total = (int) $account53->adulte_2_total_4;
                              }


                              echo number_format($transfert_total, 2, '.', ' ');


                            }

                            echo '<br>';

                            $id_option_adulte_transfert = explode('-', $reservation->id_option_autre_transfert);

                            $visa_trasfert = 0;
                            for ($p = 0; $p < count($id_option_adulte_transfert); $p++) {
                              $stmt530 = $conn->prepare('SELECT * FROM option_transfert_autre WHERE id_option =:id_option');
                              $stmt530->bindValue('id_option', $id_option_adulte_transfert[$p]);
                              $stmt530->execute();
                              $account530 = $stmt530->fetch(PDO::FETCH_OBJ);
                              if (isset($account530->id_option)) {
                                //echo '<br><small style="color:red;font-weight:bold">+ '.$account530 -> titre.' '.$account530 -> personne.': '.$account530 -> prix_total.' CHF</small>';
                                $visa_trasfert += $account530->prix_total;
                              }
                            }

                            $id_prix_transfert_obligatoire = explode('-', $reservation->id_prix_transfert_obligatoire);

                            for ($p = 0; $p < count($id_prix_transfert_obligatoire); $p++) {
                              $stmt530 = $conn->prepare('SELECT * FROM option_transfert_autre WHERE id_option =:id_option AND personne =:personne');
                              $stmt530->bindValue('id_option', $id_prix_transfert_obligatoire[$p]);
                              $stmt530->bindValue('personne', "Adulte");
                              $stmt530->execute();
                              $account530 = $stmt530->fetch(PDO::FETCH_OBJ);
                              if (isset($account530->id_option)) {
                                //echo '<br><small style="color:green;font-weight:bold">* '.$account530 -> titre.' '.$account530 -> personne.': '.$account530 -> prix_total.' CHF</small>';
                                $visa_trasfert += $account530->prix_total;
                              }
                            }


                          } else {
                            $transfert_total = "0";
                            $visa_trasfert = "0";
                            ?>
                            <i class="fa fa-times" style="color: red;font-size: 25px;"></i>
                          <?php
                          }
                          ?>
                          <INPUT TYPE="hidden" NAME="prix_transfert_a4"
                            value="<?php echo number_format($transfert_total, 2, '.', ' '); ?>">
                        </div>


                        <div class="divTableCell">
                          <?php echo $visa_trasfert; ?>
                        </div>

                        <div class="divTableCell">

                          <?php
                          echo number_format($adulte40, 2, '.', ' ');
                          ?> <br>
                          <INPUT TYPE="hidden" NAME="ass_adulte4" value="0">


                        </div>






                        <div class="divTableCell" id="total_4" style="font-weight: bold;">
                          <b>
                            <?php
                            if (isset($_POST['total_adulte3'])) {
                              echo $total_adulte3 = $_POST['total_adulte3'];
                              $totalb += $total_adulte4;
                            } else {
                              $total_adulte4 = $adulte40 + $transfert_total + $prix_total_vol + $visa_trasfert;
                              echo number_format($total_adulte4, 2, '.', ' ');
                              $totalb += $total_adulte4;
                            }
                            ?>

                          </b>

                        </div>
                      </div>


                      <INPUT TYPE="hidden" NAME="prix_chambre_a4" value="<?php echo number_format($adulte40, 2, '.', ' '); ?>">
                      <INPUT TYPE="hidden" NAME="prix_visa_a4"
                        value="<?php echo number_format($reservation->adulte_visa, 2, '.', ' '); ?>">
                      <INPUT TYPE="hidden" NAME="prix_option_a4"
                        value="<?php echo number_format($prestation_hotel, 2, '.', ' '); ?>">
                      <INPUT TYPE="hidden" NAME="prix_tour_a4" value="<?php echo $tour_adulte1; ?>">
                      <INPUT TYPE="hidden" NAME="prix_repas_a4" value="0">
                      <INPUT TYPE="hidden" NAME="prix_total_a4"
                        value="<?php echo number_format($total_adulte4, 2, '.', ' '); ?>">



                      <INPUT TYPE="hidden" NAME="total_input_4" value="<?php echo $total_adulte4_new; ?>">
                      <INPUT TYPE="hidden" NAME="total_adulte4" value="<?php echo $total_adulte4; ?>">


                      <?php
                    }
                    ?>





                    <?php
                    if ($nb_enfant == "1") {
                      ?>

                      <div class="divTableRow">
                        <div class="divTableCell"><span>Enfant 1</span></div>

                        <div class="divTableCell">
                          <?php
                          if ($id_prix_total_vol != "0") {


                            $stmt53 = $conn->prepare('SELECT * FROM vols WHERE idtxp =:idtxp');
                            $stmt53->bindValue('idtxp', $id_prix_total_vol);
                            $stmt53->execute();
                            $account53 = $stmt53->fetch(PDO::FETCH_OBJ);

                            if ($account53->enfant_total_eco == 0 or $account53->enfant_total_eco >= $account53->enfant_total) {
                              $prix_total_vol = $account53->enfant_total;
                              echo number_format(($prix_total_vol - $account53->enfant_taxe), 2, '.', ' ');
                              $enfant_taxe_1 = $account53->enfant_taxe;
                            } else {

                              $prix_total_vol = $account53->enfant_total_eco;
                              echo number_format(($prix_total_vol - $account53->enfant_taxe_eco), 2, '.', ' ');
                              $enfant_taxe_1 = $account53->enfant_taxe_eco;
                            }






                            $id_option_enfant_vol = explode('-', $reservation->id_option_enfant);

                            for ($p = 0; $p < count($id_option_enfant_vol); $p++) {
                              $stmt530 = $conn->prepare('SELECT * FROM option_vol_autre WHERE id_option =:id_option');
                              $stmt530->bindValue('id_option', $id_option_enfant_vol[$p]);
                              $stmt530->execute();
                              $account530 = $stmt530->fetch(PDO::FETCH_OBJ);
                              if (isset($account530->id_option)) {
                                echo '<br><small style="color:red;font-weight:bold">+ ' . $account530->titre . ' ' . $account530->personne . ': ' . $account530->prix_total . ' CHF</small>';
                                $prix_total_vol += $account530->prix_total;
                              }
                            }



                          } else {
                            $prix_total_vol = "0";
                            $enfant_taxe_1 = '<i class="fa fa-times" style="color: red;font-size: 25px;"></i>';
                            ?>
                            <i class="fa fa-times" style="color: red;font-size: 25px;"></i>
                          <?php
                          }
                          ?>
                          <INPUT TYPE="hidden" NAME="prix_total_vol"
                            value="<?php echo number_format($prix_total_vol, 2, '.', ' '); ?>">
                        </div>

                        <div class="divTableCell">

                          <?php echo $enfant_taxe_1; ?>
                        </div>

                        <div class="divTableCell">

                          <?php
                          if ($id_total_transfert != "0") {


                            $stmt53 = $conn->prepare('SELECT * FROM transfert WHERE id_transfert =:id_transfert');
                            $stmt53->bindValue('id_transfert', $id_total_transfert);
                            $stmt53->execute();
                            $account53 = $stmt53->fetch(PDO::FETCH_OBJ);


                            if ($account53->type == "Hydravion" or $account53->type == "Speedboat") {

                              $transfert_total = $account53->adulte_2_total_4_h / ($nb_enfant);
                              echo number_format($transfert_total, 2, '.', ' ');

                            } else {

                              if ($account53->adulte_2_total_5 != "") {
                                $transfert_total = $account53->adulte_2_total_5 / ($nb_enfant);
                                echo number_format($transfert_total, 2, '.', ' ');

                              } else {
                                echo $transfert_total = '0.00';

                              }


                            }






                            echo '<br>';



                            $id_option_enfant_transfert = explode('-', $reservation->id_option_autre_transfert_enfant);

                            $visa_trasfert = 0;
                            for ($p = 0; $p < count($id_option_enfant_transfert); $p++) {
                              $stmt530 = $conn->prepare('SELECT * FROM option_transfert_autre WHERE id_option =:id_option');
                              $stmt530->bindValue('id_option', $id_option_enfant_transfert[$p]);
                              $stmt530->execute();
                              $account530 = $stmt530->fetch(PDO::FETCH_OBJ);
                              if (isset($account530->id_option)) {
                                // echo '<br><small style="color:red;font-weight:bold">+ '.$account530 -> titre.' '.$account530 -> personne.': '.$account530 -> prix_total.' CHF</small>';
                                $visa_trasfert += $account530->prix_total;
                              }
                            }
                            $id_prix_transfert_obligatoire = explode('-', $reservation->id_prix_transfert_obligatoire);

                            for ($p = 0; $p < count($id_prix_transfert_obligatoire); $p++) {
                              $stmt530 = $conn->prepare('SELECT * FROM option_transfert_autre WHERE id_option =:id_option AND personne =:personne');
                              $stmt530->bindValue('id_option', $id_prix_transfert_obligatoire[$p]);
                              $stmt530->bindValue('personne', "Enfant");
                              $stmt530->execute();
                              $account530 = $stmt530->fetch(PDO::FETCH_OBJ);
                              if (isset($account530->id_option)) {
                                echo '<br><small style="color:green;font-weight:bold">* ' . $account530->titre . ' ' . $account530->personne . ': ' . $account530->prix_total . ' CHF</small>';
                                $visa_trasfert += $account530->prix_total;
                              }
                            }


                          } else {
                            $transfert_total = "0";
                            $visa_trasfert = "0";
                            ?>
                            <i class="fa fa-times" style="color: red;font-size: 25px;"></i>
                          <?php
                          }
                          ?>
                          <INPUT TYPE="hidden" NAME="prix_transfert_e1"
                            value="<?php echo number_format($transfert_total, 2, '.', ' '); ?>">
                        </div>

                        <div class="divTableCell">
                          <?php echo $visa_trasfert; ?>
                        </div>
                        <div class="divTableCell">

                          <?php
                          echo number_format($enfant10, 2, '.', ' ');
                          ?> <br>
                          <INPUT TYPE="hidden" NAME="ass_adulte5" value="0">


                        </div>

                        <div class="divTableCell" id="total_5" style="font-weight: bold;">
                          <b>
                            <?php
                            if (isset($_POST['total_adulte5'])) {
                              echo $total_adulte5 = $_POST['total_adulte5'];
                              $totalb += $total_enfant1;
                            } else {
                              $total_enfant1 = $enfant10 + $transfert_total + $prix_total_vol + $visa_trasfert;
                              echo number_format($total_enfant1, 2, '.', ' ');
                              $totalb += $total_enfant1;
                            }
                            ?>

                          </b>

                        </div>
                      </div>


                      <INPUT TYPE="hidden" NAME="total_input_5" value="<?php echo $total_enfant1_new; ?>">
                      <INPUT TYPE="hidden" NAME="total_adulte5" value="<?php echo $total_enfant1; ?>">


                      <INPUT TYPE="hidden" NAME="prix_chambre_e1" value="<?php echo number_format($enfant10, 2, '.', ' '); ?>">
                      <INPUT TYPE="hidden" NAME="prix_visa_e1"
                        value="<?php if ($reservation->enfant_visa_1 != '') {
                          echo number_format($reservation->enfant_visa_1, 2, '.', ' ');
                        } else {
                          echo '0.00';
                        } ?>">
                      <INPUT TYPE="hidden" NAME="prix_option_e1"
                        value="<?php echo number_format($prestation_hotel, 2, '.', ' '); ?>">
                      <INPUT TYPE="hidden" NAME="prix_tour_e1" value="<?php echo $tour_adulte1; ?>">
                      <INPUT TYPE="hidden" NAME="prix_repas_e1" value="0">
                      <INPUT TYPE="hidden" NAME="prix_total_e1"
                        value="<?php echo number_format($total_enfant1, 2, '.', ' '); ?>">

                      <INPUT TYPE="hidden" NAME="prix_chambre_e2" value="0">
                      <INPUT TYPE="hidden" NAME="prix_transfert_e2" value="0">
                      <INPUT TYPE="hidden" NAME="prix_visa_e2" value="0">
                      <INPUT TYPE="hidden" NAME="prix_option_e2" value="0">
                      <INPUT TYPE="hidden" NAME="prix_tour_e2" value="0">
                      <INPUT TYPE="hidden" NAME="prix_repas_e2" value="0">
                      <INPUT TYPE="hidden" NAME="prix_total_e2" value="0">

                      <INPUT TYPE="hidden" NAME="ass_adulte6" value="0">
                      <?php

                    }


                    ?>


                    <?php
                    if ($nb_enfant == "2") {

                      ?>

                      <div class="divTableRow">
                        <div class="divTableCell"><span>Enfant 1</span></div>


                        <div class="divTableCell">
                          <?php
                          if ($id_prix_total_vol != "0") {


                            $stmt53 = $conn->prepare('SELECT * FROM vols WHERE idtxp =:idtxp');
                            $stmt53->bindValue('idtxp', $id_prix_total_vol);
                            $stmt53->execute();
                            $account53 = $stmt53->fetch(PDO::FETCH_OBJ);

                            if ($account53->enfant_total_eco == 0 or $account53->enfant_total_eco >= $account53->enfant_total) {
                              $prix_total_vol = $account53->enfant_total;
                              echo number_format(($prix_total_vol - $account53->enfant_taxe), 2, '.', ' ');
                              $enfant_taxe_1 = $account53->enfant_taxe;
                            } else {

                              $prix_total_vol = $account53->enfant_total_eco;
                              echo number_format(($prix_total_vol - $account53->enfant_taxe_eco), 2, '.', ' ');
                              $enfant_taxe_1 = $account53->enfant_taxe_eco;
                            }

                            $id_option_enfant_vol = explode('-', $reservation->id_option_enfant);

                            for ($p = 0; $p < count($id_option_enfant_vol); $p++) {
                              $stmt530 = $conn->prepare('SELECT * FROM option_vol_autre WHERE id_option =:id_option');
                              $stmt530->bindValue('id_option', $id_option_enfant_vol[$p]);
                              $stmt530->execute();
                              $account530 = $stmt530->fetch(PDO::FETCH_OBJ);
                              if (isset($account530->id_option)) {
                                echo '<br><small style="color:red;font-weight:bold">+ ' . $account530->titre . ' ' . $account530->personne . ': ' . $account530->prix_total . ' CHF</small>';
                                $prix_total_vol += $account530->prix_total;
                              }
                            }



                          } else {
                            $prix_total_vol = "0";
                            $enfant_taxe_1 = '<i class="fa fa-times" style="color: red;font-size: 25px;"></i>';
                            ?>
                            <i class="fa fa-times" style="color: red;font-size: 25px;"></i>
                          <?php
                          }
                          ?>
                          <INPUT TYPE="hidden" NAME="prix_total_vol"
                            value="<?php echo number_format($prix_total_vol, 2, '.', ' '); ?>">
                        </div>
                        <div class="divTableCell">

                          <?php echo $enfant_taxe_1; ?>
                        </div>
                        <div class="divTableCell">

                          <?php
                          if ($id_total_transfert != "0") {


                            $stmt53 = $conn->prepare('SELECT * FROM transfert WHERE id_transfert =:id_transfert');
                            $stmt53->bindValue('id_transfert', $id_total_transfert);
                            $stmt53->execute();
                            $account53 = $stmt53->fetch(PDO::FETCH_OBJ);
                            if ($account53->type == "Hydravion" or $account53->type == "Speedboat") {

                              $transfert_total = $account53->adulte_2_total_4_h / ($nb_enfant);
                              echo number_format($transfert_total, 2, '.', ' ');

                            } else {

                              if ($account53->adulte_2_total_5 != "") {
                                $transfert_total = $account53->adulte_2_total_5;
                                echo number_format($transfert_total, 2, '.', ' ');

                              } else {
                                echo $transfert_total = '0.00';


                              }



                            }
                            echo '<br>';


                            $id_option_enfant_transfert = explode('-', $reservation->id_option_autre_transfert_enfant);

                            $visa_trasfert = 0;
                            for ($p = 0; $p < count($id_option_enfant_transfert); $p++) {
                              $stmt530 = $conn->prepare('SELECT * FROM option_transfert_autre WHERE id_option =:id_option');
                              $stmt530->bindValue('id_option', $id_option_enfant_transfert[$p]);
                              $stmt530->execute();
                              $account530 = $stmt530->fetch(PDO::FETCH_OBJ);
                              if (isset($account530->id_option)) {
                                //echo '<br><small style="color:red;font-weight:bold">+ '.$account530 -> titre.' '.$account530 -> personne.': '.$account530 -> prix_total.' CHF</small>';
                                $visa_trasfert += $account530->prix_total;
                              }
                            }


                            $id_prix_transfert_obligatoire = explode('-', $reservation->id_prix_transfert_obligatoire);

                            for ($p = 0; $p < count($id_prix_transfert_obligatoire); $p++) {
                              $stmt530 = $conn->prepare('SELECT * FROM option_transfert_autre WHERE id_option =:id_option AND personne =:personne');
                              $stmt530->bindValue('id_option', $id_prix_transfert_obligatoire[$p]);
                              $stmt530->bindValue('personne', "Enfant");
                              $stmt530->execute();
                              $account530 = $stmt530->fetch(PDO::FETCH_OBJ);
                              if (isset($account530->id_option)) {
                                //echo '<br><small style="color:green;font-weight:bold">* '.$account530 -> titre.' '.$account530 -> personne.': '.$account530 -> prix_total.' CHF</small>';
                                $visa_trasfert += $account530->prix_total;
                              }
                            }


                          } else {
                            $transfert_total = "0";
                            $visa_trasfert = 0;
                            ?>
                            <i class="fa fa-times" style="color: red;font-size: 25px;"></i>
                          <?php
                          }
                          ?>
                          <INPUT TYPE="hidden" NAME="prix_transfert_e1"
                            value="<?php echo number_format($transfert_total, 2, '.', ' '); ?>">
                        </div>

                        <div class="divTableCell">
                          <?php echo $visa_trasfert; ?>
                        </div>

                        <div class="divTableCell">

                          <?php
                          echo number_format($enfant10, 2, '.', ' ');
                          ?> <br>
                          <INPUT TYPE="hidden" NAME="ass_adulte5" value="0">


                        </div>

                        <div class="divTableCell" id="total_5" style="font-weight: bold;">
                          <b>
                            <?php
                            if (isset($_POST['total_adulte5'])) {
                              echo $total_adulte5 = $_POST['total_adulte5'];
                              $totalb += $total_enfant1;
                            } else {
                              $total_enfant1 = $enfant10 + $transfert_total + $prix_total_vol + $visa_trasfert;
                              echo number_format($total_enfant1, 2, '.', ' ');
                              $totalb += $total_enfant1;
                            }
                            ?>

                          </b>

                        </div>
                      </div>





                      <div class="divTableRow">
                        <div class="divTableCell"><span>Enfant 2</span></div>

                        <div class="divTableCell">
                          <?php
                          if ($id_prix_total_vol != "0") {


                            $stmt53 = $conn->prepare('SELECT * FROM vols WHERE idtxp =:idtxp');
                            $stmt53->bindValue('idtxp', $id_prix_total_vol);
                            $stmt53->execute();
                            $account53 = $stmt53->fetch(PDO::FETCH_OBJ);
                            if ($account53->enfant_total_eco == 0 or $account53->enfant_total_eco >= $account53->enfant_total) {
                              $prix_total_vol = $account53->enfant_total;
                              echo number_format(($prix_total_vol - $account53->enfant_taxe), 2, '.', ' ');
                              $enfant_taxe_1 = $account53->enfant_taxe;
                            } else {

                              $prix_total_vol = $account53->enfant_total_eco;
                              echo number_format(($prix_total_vol - $account53->enfant_taxe_eco), 2, '.', ' ');
                              $enfant_taxe_1 = $account53->enfant_taxe_eco;
                            }

                            $id_option_enfant_vol = explode('-', $reservation->id_option_enfant);

                            for ($p = 0; $p < count($id_option_enfant_vol); $p++) {
                              $stmt530 = $conn->prepare('SELECT * FROM option_vol_autre WHERE id_option =:id_option');
                              $stmt530->bindValue('id_option', $id_option_enfant_vol[$p]);
                              $stmt530->execute();
                              $account530 = $stmt530->fetch(PDO::FETCH_OBJ);
                              if (isset($account530->id_option)) {
                                echo '<br><small style="color:red;font-weight:bold">+ ' . $account530->titre . ' ' . $account530->personne . ': ' . $account530->prix_total . ' CHF</small>';
                                $prix_total_vol += $account530->prix_total;
                              }
                            }


                          } else {
                            $prix_total_vol = "0";
                            $enfant_taxe_1 = '<i class="fa fa-times" style="color: red;font-size: 25px;"></i>';
                            ?>
                            <i class="fa fa-times" style="color: red;font-size: 25px;"></i>
                          <?php
                          }
                          ?>
                          <INPUT TYPE="hidden" NAME="prix_total_vol"
                            value="<?php echo number_format($prix_total_vol, 2, '.', ' '); ?>">
                        </div>

                        <div class="divTableCell">

                          <?php echo $enfant_taxe_1; ?>
                        </div>
                        <div class="divTableCell">

                          <?php
                          if ($id_total_transfert != "0") {


                            $stmt53 = $conn->prepare('SELECT * FROM transfert WHERE id_transfert =:id_transfert');
                            $stmt53->bindValue('id_transfert', $id_total_transfert);
                            $stmt53->execute();
                            $account53 = $stmt53->fetch(PDO::FETCH_OBJ);

                            if ($account53->type == "Hydravion" or $account53->type == "Speedboat") {

                              $transfert_total = $account53->adulte_2_total_4_h / ($nb_enfant);
                              echo number_format($transfert_total, 2, '.', ' ');

                            } else {

                              if ($account53->adulte_2_total_5 != "") {
                                $transfert_total = $account53->adulte_2_total_5;
                                echo number_format($transfert_total, 2, '.', ' ');

                              } else {
                                echo $transfert_total = '0.00';

                              }

                            }
                            echo '<br>';



                            $id_option_enfant_transfert = explode('-', $reservation->id_option_autre_transfert_enfant);

                            $visa_trasfert = 0;
                            for ($p = 0; $p < count($id_option_enfant_transfert); $p++) {
                              $stmt530 = $conn->prepare('SELECT * FROM option_transfert_autre WHERE id_option =:id_option');
                              $stmt530->bindValue('id_option', $id_option_enfant_transfert[$p]);
                              $stmt530->execute();
                              $account530 = $stmt530->fetch(PDO::FETCH_OBJ);
                              if (isset($account530->id_option)) {
                                //echo '<br><small style="color:red;font-weight:bold">+ '.$account530 -> titre.' '.$account530 -> personne.': '.$account530 -> prix_total.' CHF</small>';
                                $visa_trasfert += $account530->prix_total;
                              }
                            }
                            $id_prix_transfert_obligatoire = explode('-', $reservation->id_prix_transfert_obligatoire);

                            for ($p = 0; $p < count($id_prix_transfert_obligatoire); $p++) {
                              $stmt530 = $conn->prepare('SELECT * FROM option_transfert_autre WHERE id_option =:id_option AND personne =:personne');
                              $stmt530->bindValue('id_option', $id_prix_transfert_obligatoire[$p]);
                              $stmt530->bindValue('personne', "Enfant");
                              $stmt530->execute();
                              $account530 = $stmt530->fetch(PDO::FETCH_OBJ);
                              if (isset($account530->id_option)) {
                                //echo '<br><small style="color:green;font-weight:bold">* '.$account530 -> titre.' '.$account530 -> personne.': '.$account530 -> prix_total.' CHF</small>';
                                $visa_trasfert += $account530->prix_total;
                              }
                            }

                          } else {
                            $transfert_total = "0";
                            $visa_trasfert = "0";
                            ?>
                            <i class="fa fa-times" style="color: red;font-size: 25px;"></i>
                          <?php
                          }
                          ?>
                          <INPUT TYPE="hidden" NAME="prix_transfert_e2"
                            value="<?php echo number_format($transfert_total, 2, '.', ' '); ?>">
                        </div>

                        <div class="divTableCell">
                          <?php echo $visa_trasfert; ?>
                        </div>

                        <div class="divTableCell">

                          <?php
                          echo number_format($enfant20, 2, '.', ' ');
                          ?> <br>
                          <INPUT TYPE="hidden" NAME="ass_adulte6" value="0">

                        </div>

                        <div class="divTableCell" id="total_6" style="font-weight: bold;">
                          <b>
                            <?php
                            if (isset($_POST['total_adulte6'])) {
                              echo $total_adulte6 = $_POST['total_adulte6'];
                              $totalb += $total_enfant1;
                            } else {
                              $total_enfant2 = $enfant20 + $transfert_total + $prix_total_vol + $visa_trasfert;
                              echo number_format($total_enfant2, 2, '.', ' ');
                              $totalb += $total_enfant2;
                            }
                            ?>

                          </b>

                        </div>
                      </div>






                      <INPUT TYPE="hidden" NAME="total_input_5" value="<?php echo $total_enfant1; ?>">
                      <INPUT TYPE="hidden" NAME="total_adulte5" value="<?php echo $total_enfant1; ?>">


                      <INPUT TYPE="hidden" NAME="prix_chambre_e1" value="<?php echo number_format($enfant10, 2, '.', ' '); ?>">
                      <INPUT TYPE="hidden" NAME="prix_visa_e1"
                        value="<?php if ($reservation->enfant_visa_1 != '') {
                          echo number_format($reservation->enfant_visa_1, 2, '.', ' ');
                        } else {
                          echo '0.00';
                        } ?>">
                      <INPUT TYPE="hidden" NAME="prix_option_e1"
                        value="<?php echo number_format($prestation_hotel, 2, '.', ' '); ?>">
                      <INPUT TYPE="hidden" NAME="prix_tour_e1" value="<?php echo $tour_adulte1; ?>">
                      <INPUT TYPE="hidden" NAME="prix_repas_e1" value="0">
                      <INPUT TYPE="hidden" NAME="prix_total_e1"
                        value="<?php echo number_format($total_enfant1, 2, '.', ' '); ?>">















                      <INPUT TYPE="hidden" NAME="prix_chambre_e2" value="<?php echo number_format($enfant20, 2, '.', ' '); ?>">
                      <INPUT TYPE="hidden" NAME="prix_visa_e2"
                        value="<?php echo number_format($reservation->enfant_visa_2, 2, '.', ' '); ?>">
                      <INPUT TYPE="hidden" NAME="prix_option_e2"
                        value="<?php echo number_format($prestation_hotel, 2, '.', ' '); ?>">

                      <INPUT TYPE="hidden" NAME="prix_tour_e2" value="<?php echo $tour_adulte1; ?>">
                      <INPUT TYPE="hidden" NAME="prix_repas_e2" value="0">
                      <INPUT TYPE="hidden" NAME="prix_total_e2"
                        value="<?php echo number_format($total_enfant2, 2, '.', ' '); ?>">

                      <INPUT TYPE="hidden" NAME="total_input_6" value="<?php echo $total_enfant2; ?>">
                      <INPUT TYPE="hidden" NAME="total_adulte6" value="<?php echo $total_enfant2; ?>">
                      <?php

                    }



                    if ($nb_enfant == "0") {
                      ?>

                      <INPUT TYPE="hidden" NAME="prix_chambre_e1" value="0">
                      <INPUT TYPE="hidden" NAME="prix_transfert_e1" value="0">
                      <INPUT TYPE="hidden" NAME="prix_visa_e1" value="0">
                      <INPUT TYPE="hidden" NAME="prix_option_e1" value="0">
                      <INPUT TYPE="hidden" NAME="prix_tour_e1" value="0">
                      <INPUT TYPE="hidden" NAME="prix_repas_e1" value="0">
                      <INPUT TYPE="hidden" NAME="prix_total_e1" value="0">

                      <INPUT TYPE="hidden" NAME="prix_chambre_e2" value="0">
                      <INPUT TYPE="hidden" NAME="prix_transfert_e2" value="0">
                      <INPUT TYPE="hidden" NAME="prix_visa_e2" value="0">
                      <INPUT TYPE="hidden" NAME="prix_option_e2" value="0">
                      <INPUT TYPE="hidden" NAME="prix_tour_e2" value="0">
                      <INPUT TYPE="hidden" NAME="prix_repas_e2" value="0">
                      <INPUT TYPE="hidden" NAME="prix_total_e2" value="0">

                      <INPUT TYPE="hidden" NAME="ass_adulte5" value="0">
                      <INPUT TYPE="hidden" NAME="ass_adulte6" value="0">


                      <?php
                    }


                    ?>







                    <?php



                    if ($nb_bebe == "1") {
                      ?>

                      <div class="divTableRow">
                        <div class="divTableCell"><span>Bébé 1</span></div>


                        <div class="divTableCell">
                          <?php
                          if ($id_prix_total_vol != "0") {


                            $stmt53 = $conn->prepare('SELECT * FROM vols WHERE idtxp =:idtxp');
                            $stmt53->bindValue('idtxp', $id_prix_total_vol);
                            $stmt53->execute();
                            $account53 = $stmt53->fetch(PDO::FETCH_OBJ);



                            if ($account53->bebe_total_eco == 0 or $account53->bebe_total_eco >= $account53->bebe_total) {
                              $prix_total_vol = $account53->bebe_total;
                              echo number_format(($prix_total_vol - $account53->bebe_taxe), 2, '.', ' ');
                              $bebe_taxe_1 = $account53->bebe_taxe;
                            } else {

                              $prix_total_vol = $account53->bebe_total_eco;
                              echo number_format(($prix_total_vol - $account53->bebe_taxe_eco), 2, '.', ' ');
                              $bebe_taxe_1 = $account53->bebe_taxe_eco;
                            }




                            $id_option_bebe_vol = explode('-', $reservation->id_option_bebe);

                            for ($p = 0; $p < count($id_option_bebe_vol); $p++) {
                              $stmt530 = $conn->prepare('SELECT * FROM option_vol_autre WHERE id_option =:id_option');
                              $stmt530->bindValue('id_option', $id_option_bebe_vol[$p]);
                              $stmt530->execute();
                              $account530 = $stmt530->fetch(PDO::FETCH_OBJ);
                              if (isset($account530->id_option)) {
                                echo '<br><small style="color:red;font-weight:bold">+ ' . $account530->titre . ' ' . $account530->personne . ': ' . $account530->prix_total . ' CHF</small>';
                                $prix_total_vol += $account530->prix_total;
                              }
                            }


                          } else {
                            $prix_total_vol = "0";
                            ?>
                            <i class="fa fa-times" style="color: red;font-size: 25px;"></i>
                          <?php
                          }
                          ?>
                          <INPUT TYPE="hidden" NAME="prix_total_vol"
                            value="<?php echo number_format($prix_total_vol, 2, '.', ' '); ?>">
                        </div>
                        <div class="divTableCell">

                          <?php echo $bebe_taxe_1; ?>
                        </div>
                        <div class="divTableCell">

                          <?php
                          if ($id_total_transfert != "0") {


                            $stmt53 = $conn->prepare('SELECT * FROM transfert WHERE id_transfert =:id_transfert');
                            $stmt53->bindValue('id_transfert', $id_total_transfert);
                            $stmt53->execute();
                            $account53 = $stmt53->fetch(PDO::FETCH_OBJ);


                            if ($account53->type == "Hydravion" or $account53->type == "Speedboat") {

                              $transfert_total = $account53->adulte_2_total_5_h;
                              echo number_format($transfert_total, 2, '.', ' ');

                            } else {

                              if ($account53->adulte_2_total_6 != "") {
                                $transfert_total = $account53->adulte_2_total_6;
                                echo number_format($transfert_total, 2, '.', ' ');

                              } else {
                                echo $transfert_total = '0.00';

                              }

                            }






                            echo '<br>';

                            $id_option_bebe_transfert = explode('-', $reservation->id_option_autre_transfert_bebe);


                            for ($p = 0; $p < count($id_option_bebe_transfert); $p++) {
                              $stmt530 = $conn->prepare('SELECT * FROM option_transfert_autre WHERE id_option =:id_option');
                              $stmt530->bindValue('id_option', $id_option_bebe_transfert[$p]);
                              $stmt530->execute();
                              $account530 = $stmt530->fetch(PDO::FETCH_OBJ);
                              if (isset($account530->id_option)) {
                                //echo '<br><small style="color:red;font-weight:bold">+ '.$account530 -> titre.' '.$account530 -> personne.': '.$account530 -> prix_total.' CHF</small>';
                                $visa_trasfert += $account530->prix_total;
                              }
                            }
                            $id_prix_transfert_obligatoire = explode('-', $reservation->id_prix_transfert_obligatoire);

                            for ($p = 0; $p < count($id_prix_transfert_obligatoire); $p++) {
                              $stmt530 = $conn->prepare('SELECT * FROM option_transfert_autre WHERE id_option =:id_option AND personne =:personne');
                              $stmt530->bindValue('id_option', $id_prix_transfert_obligatoire[$p]);
                              $stmt530->bindValue('personne', "Bébé");
                              $stmt530->execute();
                              $account530 = $stmt530->fetch(PDO::FETCH_OBJ);
                              if (isset($account530->id_option)) {
                                // echo '<br><small style="color:green;font-weight:bold">* '.$account530 -> titre.' '.$account530 -> personne.': '.$account530 -> prix_total.' CHF</small>';
                                $visa_trasfert += $account530->prix_total;
                              }
                            }
                          } else {
                            $transfert_total = "0";
                            ?>
                            <i class="fa fa-times" style="color: red;font-size: 25px;"></i>
                          <?php
                          }
                          ?>
                          <INPUT TYPE="hidden" NAME="prix_transfert_b1"
                            value="<?php echo number_format($transfert_total, 2, '.', ' '); ?>">

                        </div>
                        <div class="divTableCell">
                          <?php echo $visa_trasfert; ?>
                        </div>

                        <div class="divTableCell">

                          <?php
                          echo number_format($bebe10, 2, '.', ' ');
                          ?> <br>
                          <INPUT TYPE="hidden" NAME="ass_adulte7" value="0">


                        </div>

                        <div class="divTableCell" id="total_7" style="font-weight: bold;">
                          <b>
                            <?php
                            if (isset($_POST['total_adulte7'])) {
                              echo $total_bebe1 = $_POST['total_adulte7'];
                              $totalb += $total_bebe1;
                            } else {
                              $total_bebe1 = $bebe10 + $transfert_total + $prix_total_vol + $visa_trasfert;
                              echo number_format($total_bebe1, 2, '.', ' ');
                              $totalb += $total_bebe1;
                            }
                            ?>
                          </b>

                        </div>
                      </div>


                      <INPUT TYPE="hidden" NAME="prix_chambre_b1" value="<?php echo number_format($bebe10, 2, '.', ' '); ?>">
                      <INPUT TYPE="hidden" NAME="prix_visa_b1"
                        value="<?php echo number_format($reservation->enfant_visa, 2, '.', ' '); ?>">
                      <INPUT TYPE="hidden" NAME="prix_option_b1"
                        value="<?php echo number_format($prestation_hotel, 2, '.', ' '); ?>">
                      <INPUT TYPE="hidden" NAME="prix_tour_b1" value="<?php echo $tour_adulte1; ?>">
                      <INPUT TYPE="hidden" NAME="prix_repas_b1" value="0">
                      <INPUT TYPE="hidden" NAME="prix_total_b1" value="<?php echo number_format($total_bebe1, 2, '.', ' '); ?>">


                      <INPUT TYPE="hidden" NAME="total_input_7" value="<?php echo $total_bebe1; ?>">
                      <INPUT TYPE="hidden" NAME="total_adulte7" value="<?php echo $total_bebe1; ?>">
                      <?php

                    }












                    if ($nb_bebe == "0") {


                      ?>

                      <INPUT TYPE="hidden" NAME="titre_participant_bebe_1" value="0">
                      <INPUT TYPE="hidden" NAME="nom_participant_bebe_1" value="0">
                      <INPUT TYPE="hidden" NAME="prenom_participant_bebe_1" value="0">
                      <INPUT TYPE="hidden" NAME="date_naissance_participant_bebe_1" value="0">
                      <INPUT TYPE="hidden" NAME="nationalite_participant_bebe_1" value="0">
                      <INPUT TYPE="hidden" NAME="assurance_bebe_1" value="0">


                      <INPUT TYPE="hidden" NAME="prix_chambre_b1" value="0">
                      <INPUT TYPE="hidden" NAME="prix_transfert_b1" value="0">
                      <INPUT TYPE="hidden" NAME="prix_visa_b1" value="0">
                      <INPUT TYPE="hidden" NAME="prix_option_b1" value="0">
                      <INPUT TYPE="hidden" NAME="prix_tour_b1" value="0">
                      <INPUT TYPE="hidden" NAME="prix_repas_b1" value="0">
                      <INPUT TYPE="hidden" NAME="prix_total_b1" value="0">

                      <INPUT TYPE="hidden" NAME="ass_adulte7" value="0">

                      <?php
                    }


                    ?>


                    <div class="divTableRow">
                      <div class="divTableCell" style=""><span style="font-weight: 700;">Sous total séjour</span></div>
                      <div class="divTableCell" style="border-right: 0;background: none;border-left: 0;"></div>
                      <div class="divTableCell" style="border-right: 0;background: none;border-left: 0;"></div>
                      <div class="divTableCell" style="border-right: 0;background: none;border-left: 0;"></div>
                      <div class="divTableCell" style="border-right: 0;background: none;border-left: 0;"></div>
                      <div class="divTableCell" style="border-right: 0;background: none;border-left: 0;"><b>CHF</b></div>
                      <div class="divTableCell" style="">

                        <b>
                          <?php
                          echo number_format($totalb, 2, '.', ' ');
                          ?>

                        </b>

                      </div>
                    </div>



                  </div>
                </div>




                <br>



                <?php

                include ('option/repas.php');
                include ('option/prestation.php');
                include ('option/tour.php');

                include ('option/valuecheck.php');
                include ('sejour_option/assurance.php');
                ?>









                <?php

                  }

                }


                ?>

















            <br>


          </div>
      </div>
      <br>

      <div class="divTable blueTable">
        <div class="divTableBody">

          <div class="divTableRow">
            <div class="divTableCell" style="text-align: right;"><span
                style="color: red;font-weight: 700;padding: 20px;text-align: right;">Montant final du devis</span></div>
            <div class="divTableCell" style="width: 12%;"><span
                style="font-weight: 700;padding: 20px;text-align: right;font-weight: bold">CHF</span></div>

            <div class="divTableCell" style="width: 12%;">

              <?php
              /*
              if (isset($_POST['prix_total_total']))
                {
              ?>
              <div id="totalb" style="font-weight: bold">
                  <?php
                    echo $_POST['prix_total_total'];
                    $grandtotal = $_POST['prix_total_total'];
                  ?>
              </div>
              <?php
                }
                else
                {
                  */
              ?>

              <div id="totalb" style="font-weight: bold">
                <?php

                $totalbfinal = $totalb + $repas_total + $prestation_total + $excursion_total;

                $grandtotal = special_round($totalbfinal, 0.05);
                echo number_format($grandtotal, 2, ".", " ");

                ?>

              </div>
              <?php
              /*
                }
              */
              ?>

            </div>
          </div>



        </div>

      </div>
    </div>




    <INPUT TYPE="hidden" NAME="prix_repas_a1" value="<?php echo number_format($repas_hotel, 2, '.', ' '); ?>">
    <INPUT TYPE="hidden" NAME="prix_total_a1" value="<?php echo number_format($total_adulte1, 2, '.', ' '); ?>">

    <INPUT TYPE="hidden" NAME="total_input_1" value="<?php echo $total_adulte1_new; ?>">
    <INPUT TYPE="hidden" NAME="total_adulte1" value="<?php echo $total_adulte1; ?>">
    <INPUT TYPE="hidden" NAME="prix_tour_a1" value="<?php echo $tour_adulte1; ?>">

    <?php

    if ($adulte == "1") {
      ?>
      <INPUT TYPE="hidden" NAME="total_input_2" value="0">
      <INPUT TYPE="hidden" NAME="total_input_3" value="0">
      <INPUT TYPE="hidden" NAME="total_input_4" value="0">
      <?php
    }
    if ($nb_enfant == "1") {
      ?>
      <INPUT TYPE="hidden" NAME="total_input_6" value="0">
      <?php
    }


    ?>











    <INPUT TYPE="hidden" NAME="totalb_initiale" value="<?php echo $grandtotal; ?>">

    <INPUT TYPE="hidden" NAME="totalb" value="<?php echo $grandtotal; ?>">
    <INPUT TYPE="hidden" NAME="prix_total_total" value="<?php echo $grandtotal; ?>">




    <div class="col-sm-12">
      <p><br></p>
      <p><br></p>
      <h4 style="color: #FFF;font-weight: 700;text-transform: uppercase;font-size: 20px;padding: 18px 0;">
        <span
          style="background: #000;padding: 20px 28px 19px 20px;color: #FFF;border-top-right-radius: 10px;border-bottom-right-radius: 10px;z-index: 9;"><i
            class="fa fa-envelope" style="font-size: 34px;"></i></span>
        <span
          style="background: #f68730;padding: 5px 28px 5px 20px;color: #FFF;z-index: -1;left: -18px;position: relative">VOTRE
          REMARQUE</span>
      </h4>
    </div>

    <div class="col-sm-12">
      <div class="row">

        <div class="col-sm-12">
          <br>



          <div class="form-group form_group_line" style="margin-right: 5px;display: inline-block;width: 100%;">
            <label for="exampleInputName2">Vous pouvez ecrire ci-dessous vos remarques</label>
            <textarea class="form-control" style="width: 100%; height: 100px;" name="remarque"></textarea>

          </div>



        </div>


      </div>
    </div>






    <div class="col-sm-12">

      <h4 style="color: #FFF;font-weight: 700;text-transform: uppercase;font-size: 20px;padding: 18px 0;">
        <span
          style="background: #000;padding: 20px 28px 19px 20px;color: #FFF;border-top-right-radius: 10px;border-bottom-right-radius: 10px;z-index: 9;"><i
            class="fa fa-address-card" style="font-size: 34px;"></i></span>
        <span
          style="background: #f68730;padding: 5px 28px 5px 20px;color: #FFF;z-index: -1;left: -18px;position: relative">VOS
          COORDONNÉES SUR VOTRE DEVIS</span>
      </h4>
    </div>


    <input type="hidden" name="titre_coordonnee"
      value="<?php if (isset($_POST['titre_coordonnee'])) {
        echo $_POST['titre_coordonnee'];
      } ?>">




    <div class="col-sm-12">
      <div class="row">

        <div class="col-sm-12">
          <p>&nbsp;</p>
        </div>

        <div class="col-sm-6">
          <div class="col-sm-12" style="text-align: right">
            <span class="h2">Contact <i class="fa fa-envelope"></i></span>
            <hr>
            <p>&nbsp;</p>
          </div>


          <div class="form-group form_group_line" style="margin-right: 5px;display: inline-block;width: 48%;">
            <label for="exampleInputName2">Nom *</label>
            <input type="text" name="nom" value="<?php if (isset($_POST['nom'])) {
              echo $_POST['nom'];
            } ?>"
              class="form-control" id="nomcord" required>
          </div>

          <div class="form-group form_group_line" style="margin-right: 5px;display: inline-block;width: 48%;">
            <label for="exampleInputName2">Prenom *</label>
            <input type="text" name="prenom" value="<?php if (isset($_POST['prenom'])) {
              echo $_POST['prenom'];
            } ?>"
              class="form-control" id="prenomcord" required>
          </div>


          <div class="form-group form_group_line" style="margin-right: 5px;display: inline-block;width: 48%;">
            <label for="exampleInputName2">Adresse email *</label>
            <input type="text" class="form-control" id="exampleInputName2" name="email"
              value="<?php if (isset($_POST['email'])) {
                echo $_POST['email'];
              } ?>" required>
          </div>
          <div class="form-group form_group_line" style="margin-right: 5px;display: inline-block;width: 48%;">
            <label for="exampleInputName2">Confirmez l'adresse email *</label>
            <input type="text" class="form-control" id="exampleInputName2" name="reemail"
              value="<?php if (isset($_POST['reemail'])) {
                echo $_POST['reemail'];
              } ?>" required>
          </div>

          <div class="form-group form_group_line" style="margin-right: 5px;display: inline-block;width: 98%;">
            <label for="exampleInputName2">Téléphone * </label>
            <input type="text" class="form-control" id="exampleInputName2" name="tel"
              value="<?php if (isset($_POST['tel'])) {
                echo $_POST['tel'];
              } ?>" required>
          </div>

        </div>

        <div class="col-sm-6">
          <div class="col-sm-12" style="text-align: left;">
            <span class="h2"><i class="fa fa-map-marker-alt"></i> Adresse </span>
            <hr>
            <p>&nbsp;</p>
          </div>
          <div class="form-group form_group_line" style="margin-right: 5px;display: inline-block;width: 48%;">
            <label for="exampleInputName2">Rue *</label>
            <input type="text" class="form-control" id="exampleInputName2" name="rue"
              value="<?php if (isset($_POST['rue'])) {
                echo $_POST['rue'];
              } ?>" required>
          </div>

          <div class="form-group form_group_line" style="margin-right: 5px;display: inline-block;width: 48%;">
            <label for="exampleInputName2">N° *</label>
            <input type="text" class="form-control" id="exampleInputName2" name="num_rue"
              value="<?php if (isset($_POST['num_rue'])) {
                echo $_POST['num_rue'];
              } ?>" required>
          </div>

          <div class="form-group form_group_line" style="margin-right: 5px;display: inline-block;width: 48%;">
            <label for="exampleInputName2">NPA *</label>
            <input type="text" class="form-control" id="exampleInputName2" name="npa"
              value="<?php if (isset($_POST['npa'])) {
                echo $_POST['npa'];
              } ?>" required>
          </div>
          <div class="form-group form_group_line" style="margin-right: 5px;display: inline-block;width: 48%;">
            <label for="exampleInputName2">Lieu *</label>
            <input type="text" class="form-control" id="exampleInputName2" name="lieu"
              value="<?php if (isset($_POST['lieu'])) {
                echo $_POST['lieu'];
              } ?>" required>
          </div>
          <div class="form-group form_group_line">

            <label for="exampleInputName2">Pays *</label>
            <div class="input-group select_custom" style="width: 98%;">

              <select class="form-control" name="pays" id="" style="z-index: 0;">
                <option value="Suisse" class="others" selected>Suisse</option>
                <option value="France" class="others">France</option>
                <option value="Espagne" class="others">Espagne</option>
                <option value="Portugal" class="others">Portugal</option>
                <?php
                $stmt = $conn->prepare('SELECT * FROM pays ORDER BY nom_fr_fr ASC');
                $stmt->execute();
                while ($account = $stmt->fetch(PDO::FETCH_OBJ)) {
                  ?>
                  <option value="<?php echo $account->nom_fr_fr; ?>" <?php
                       if (isset($_POST['pays'])) {
                         if ($_POST['pays'] == $account->nom_fr_fr) {
                           echo 'selected';
                         }
                       }
                       ?>>
                    <?php echo $account->nom_fr_fr; ?>
                  </option>
                  <?php
                }
                ?>
              </select>



            </div>

          </div>


        </div>
      </div>
    </div>







    <div class="clearfix clearfix_form"></div>



    <div class="col-sm-12">
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <h4 style="color: #FFF;font-weight: 700;text-transform: uppercase;font-size: 20px;padding: 18px 0;">
        <span
          style="background: #000;padding: 20px 28px 19px 20px;color: #FFF;border-top-right-radius: 10px;border-bottom-right-radius: 10px;z-index: 9;"><i
            class="fa fa-handshake" style="font-size: 34px;"></i></span>
        <span
          style="background: #f68730;padding: 5px 28px 5px 20px;color: #FFF;z-index: -1;left: -18px;position: relative">Liste
          important de votre contrôle</span>
      </h4>
      <p>&nbsp;</p>
    </div>

    <div class="col-sm-12">

      <p style="font-size: 13px;font-weight: 400;color: #000;line-height: 20px;margin-bottom: 5px" style="display:hidden">
        <input type="hidden" value="1" name="newsletter">
        <input type="hidden" value="1" name="cgcv">
      </p>
      <p style="font-size: 13px;font-weight: 400;color: #000;line-height: 20px;margin-bottom: 0">
        <input type="hidden" value="0" name="document">
        <input type="checkbox" id="confirme_2" value="1" name="document">
        &nbsp;&nbsp;Je certifie que tous les noms et prénoms de participants sont correctement orthographiés selon vos
        passeports et pour les enfants la date de naissance pour les enfants sont juste.
      </p>


    </div>



    <div class="col-sm-12">
      <p>&nbsp;</p>
      <h4 style="color: #FFF;font-weight: 700;text-transform: uppercase;font-size: 20px;padding: 18px 0;">
        <span
          style="background: #000;padding: 20px 28px 19px 20px;color: #FFF;border-top-right-radius: 10px;border-bottom-right-radius: 10px;z-index: 9;"><i
            class="fa fa-lock" style="font-size: 34px;"></i></span>
        <span
          style="background: #f68730;padding: 5px 28px 5px 20px;color: #FFF;z-index: -1;left: -18px;position: relative">Sécurité</span>
      </h4>
      <p>&nbsp;</p>
    </div>



    <?php

    $input = array("ARE", "YTE", "IOU", "WXE", "CRF", "TGR", "PLO", "ASZ", "YHT", "KLM", "PAS", "DFG", "SDE", "VCX", "QML", "EDF", "TZQ", "WSM", "NHB");
    $rand_keys = array_rand($input, 2);

    $input_1 = array("AZE", "YTP", "NBG", "MOP", "EDZ", "RTY", "UYT", "UJK", "OKI", "EDF", "FRC", "BFI", "OZS", "CQZ", "UJY", "OLM", "RFP", "EFN", "SZX");
    $rand_keys_1 = array_rand($input_1, 2);

    $input_2 = array("2", "3", "4", "5", "6", "7", "8", "9");
    $rand_keys_2 = array_rand($input_2, 2);


    $code_securite = $input[$rand_keys[0]] . '' . $input_2[$rand_keys_2[0]] . '' . $input_1[$rand_keys_1[0]];
    ?>
    <div class="col-sm-12">

      <div class="row">
        <div class="col-sm-4">
          <p>&nbsp;</p>
          <span class="securiterobot"
            style="font-size: 50px;background: #ccc;padding: 19px;color: #FFF;text-shadow: 0px 1px 1px #000;">
            <?php echo $input[$rand_keys[0]] . '<span style="font-family:Arial;color:#000;">' . $input_2[$rand_keys_2[0]] . '</span>' . $input_1[$rand_keys_1[0]];
            ; ?>
          </span>
        </div>

        <script type="text/javascript">
          function upperCaseF(a) {
            setTimeout(function () {
              a.value = a.value.toUpperCase();
            }, 1);
          }
        </script>

        <div class="col-sm-4">
          <span>Merci, d'introduire le code de sécurité fourni par nos soins, afin de nous garantir que vous n'êtes pas un
            robot afin de lancer votre réservation ou devis finale.</span><br><br>
          <input type="hidden" class="form-control" id="exampleInputName2" name="code_securite"
            value="<?php echo $code_securite; ?>">
          <input type="text" class="form-control" id="exampleInputName2" name="securite" required
            onkeydown="upperCaseF(this)">
          <br>
          <br>
          <br>

        </div>
        <div class="col-sm-4">


        </div>
      </div>
    </div>
    <div class="col-sm-12">

      <input type="hidden" name="adulte" value="<?php echo $adulte; ?>">
      <input type="hidden" name="nb_enfant" value="<?php echo $nb_enfant; ?>">
      <input type="hidden" name="nb_bebe" value="<?php echo $nb_bebe; ?>">



      <div class="row">

        <div class="col-sm-6">
          <a href="hotel_detail.php?w=<?php echo $id_hotel; ?>&<?php echo $url; ?>"
            class="btn btn-primary btn-red bnt-gray">Retour</a>




        </div>
        <div class="col-sm-6" style="text-align: right">

          <button type="submit" class="btn btn-primary btn-red" name="devis">Confirmer votre devis</button>

          <!--<button type="submit" class="btn btn-primary btn-red" name="reservation">Réservez maintenant</button>-->
        </div>


      </div>
    </div>


    </form>
    </div>
    </div>







    <!-- xxxxxxxxxxxxxxxxxxxxxx POPUP DYNAMIQUE xxxxxxxxxxxxxxxxxxxxxxx -->
    <!-- xxxxxxxxxxxxxxxxxxxxxx POPUP DYNAMIQUE xxxxxxxxxxxxxxxxxxxxxxx -->
    <!-- xxxxxxxxxxxxxxxxxxxxxx POPUP DYNAMIQUE xxxxxxxxxxxxxxxxxxxxxxx -->



    <?php

    $id_assurance = '';
    $stmt34 = $conn->prepare('SELECT * FROM assurance WHERE id_assurance !=:id_assurance');
    $stmt34->bindValue('id_assurance', $id_assurance);
    $stmt34->execute();
    while ($account34 = $stmt34->fetch(PDO::FETCH_OBJ)) {


      for ($rrr = 1; $rrr <= 4; $rrr++) {

        if ($rrr == 1) {
          $total_adulte = $total_adulte1_new;
        }
        if ($rrr == 2) {
          $total_adulte = $total_adulte2_new;
        }
        if ($rrr == 3) {
          $total_adulte = $total_adulte3_new;
        }
        if ($rrr == 4) {
          $total_adulte = $total_adulte4_new;
        }




        ?>

        <!-- xxxxxxxxxxxxxxxxxxxxxx ADULTE 1 xxxxxxxxxxxxxxxxxxxxxxx -->

        <div>
          <div class="poppy" id="popup-reservation_adulte_<?php echo $rrr; ?>_<?php echo $account34->id_assurance; ?>">
            <div class="row">
              <div class="col-sm-12 pop">
                <p>
                  <span class="h4"
                    style="margin-bottom: 10px !important;font-size: 20px;font-weight: 700;text-transform: uppercase;line-height: 36px;color: #000;">
                    <?php echo stripslashes($account34->titre_assurance); ?>
                  </span>
                  <span class="close-btn h4"
                    style="margin-bottom: 0;top: -56px;right: 28px;font-family: arial;font-size: 28px;color: red">
                    <i class="fa fa-times-circle"></i>
                  </span>
                  <hr>
                </p>
              </div>

              <div class="col-sm-3" style="text-align: center;">
                <h2 style="font-size: 20px;margin: 0px 0;font-weight: 100;">
                  <?php
                  if ($account34->info == "annuelle") {
                    echo 'Assurance annulle à';
                  }
                  if ($account34->info == "uniquement") {
                    echo 'Pour le voyage uniquement à';
                  }
                  ?>

                </h2>
                <h1 style="font-weight: 1000;">
                  <?php

                  if ($account34->prix_assurance == 0) {
                    $prix_assurance = round(($total_adulte * $account34->pourcentage / 100), 1);
                    //$prix_assurance = ($total_adulte * $account34 -> pourcentage/100);
                    echo number_format($prix_assurance, 2, '.', ' ');
                  } else {
                    $prix_assurance = $account34->prix_assurance;
                    echo number_format($prix_assurance, 2, '.', ' ');
                  }

                  ?>
                  <span style="font-weight: 100;font-size: 22px;">CHF</span>
                </h1>
                <hr>

                <p style="text-align:left !important;padding: 0px 10px;line-height: 13px;font-size:11px;color: #000;">

                  <span style="font-size: 12px;font-weight: bold;color: #000;">Prestation :<br></span>
                  <?php
                  $prestation_assurance = stripslashes(($account34->prestation_assurance));
                  echo $prestation_assurance = str_replace('?', '✓', $prestation_assurance);

                  ?>

                </p>
              </div>

              <div class="col-sm-3">

                <span style="font-size: 12px;font-weight: bold;color: #000;">Frais d’annulation :
                  <hr>
                </span>
                <?php

                $prestation_assurance = stripslashes(($account34->frais_annulation));
                echo $prestation_assurance = str_replace('?', '✓', $prestation_assurance);

                ?>

              </div>
              <div class="col-sm-3">
                <span style="font-size: 12px;font-weight: bold;color: #000;">Assistance :
                  <hr>
                </span>
                <?php

                $prestation_assurance = stripslashes(($account34->assistance));
                echo $prestation_assurance = str_replace('?', '✓', $prestation_assurance);

                ?>
              </div>
              <div class="col-sm-3">
                <span style="font-size: 12px;font-weight: bold;color: #000;">Frais de recherche et de sauvetage :
                  <hr>
                </span>
                <?php

                $prestation_assurance = stripslashes(($account34->fraisderecherche));
                echo $prestation_assurance = str_replace('?', '✓', $prestation_assurance);

                ?>
                <hr>

                <span style="font-size: 12px;font-weight: bold;color: #000;">Vol retardé :
                  <hr>
                </span>
                <?php

                $prestation_assurance = stripslashes(($account34->volretarde));
                echo $prestation_assurance = str_replace('?', '✓', $prestation_assurance);

                ?>
                <hr>

              </div>

            </div>

          </div>
        </div>
        <script>
          $("#reservation_adulte_<?php echo $rrr; ?>_<?php echo $account34->id_assurance; ?>").poppy("popup-reservation_adulte_<?php echo $rrr; ?>_<?php echo $account34->id_assurance; ?>");
        </script>

        <?php

      }

    }
    $id_assurance = '';
    $stmt34 = $conn->prepare('SELECT * FROM assurance WHERE id_assurance !=:id_assurance');
    $stmt34->bindValue('id_assurance', $id_assurance);
    $stmt34->execute();
    while ($account34 = $stmt34->fetch(PDO::FETCH_OBJ)) {


      for ($rrr = 1; $rrr <= $nb_enfant; $rrr++) {
        ?>
        <!-- xxxxxxxxxxxxxxxxxxxxxx Enfant 1 xxxxxxxxxxxxxxxxxxxxxxx -->


        <div>
          <div class="poppy" id="popup-reservation_enfant_<?php echo $rrr; ?>_<?php echo $account34->id_assurance; ?>">
            <div class="row">
              <div class="col-sm-12 pop">
                <p>
                  <span class="h4"
                    style="margin-bottom: 10px !important;font-size: 20px;font-weight: 700;text-transform: uppercase;line-height: 36px;color: #000;">
                    <?php echo stripslashes($account34->titre_assurance); ?>
                  </span>
                  <span class="close-btn h4"
                    style="margin-bottom: 0;top: -56px;right: 28px;font-family: arial;font-size: 28px;color: red">
                    <i class="fa fa-times-circle"></i>
                  </span>
                  <hr>
                </p>
              </div>







              <div class="col-sm-3" style="text-align: center;">
                <h2 style="font-size: 20px;margin: 0px 0;font-weight: 100;">
                  <?php
                  if ($account34->info == "annuelle") {
                    echo 'Assurance annulle à';
                  }
                  if ($account34->info == "uniquement") {
                    echo 'Pour le voyage uniquement à';
                  }
                  ?>

                </h2>
                <h1 style="font-weight: 1000;">
                  <?php

                  if ($account34->prix_assurance == 0) {
                    echo ceil($total_enfant * $account34->pourcentage / 100);
                  } else {
                    echo $account34->prix_assurance;
                  }

                  ?>
                  <span style="font-weight: 100;font-size: 22px;">CHF</span>
                </h1>
                <hr>

                <p style="text-align:left !important;padding: 0px 10px;line-height: 13px;font-size:11px;color: #000;">

                  <span style="font-size: 12px;font-weight: bold;color: #000;">Prestation :<br></span>
                  <?php
                  $prestation_assurance = stripslashes(($account34->prestation_assurance));
                  echo $prestation_assurance = str_replace('?', '✓', $prestation_assurance);

                  ?>

                </p>
              </div>

              <div class="col-sm-3">

                <span style="font-size: 12px;font-weight: bold;color: #000;">Frais d’annulation :
                  <hr>
                </span>
                <?php

                $prestation_assurance = stripslashes(($account34->frais_annulation));
                echo $prestation_assurance = str_replace('?', '✓', $prestation_assurance);

                ?>

              </div>
              <div class="col-sm-3">
                <span style="font-size: 12px;font-weight: bold;color: #000;">Assistance :
                  <hr>
                </span>
                <?php

                $prestation_assurance = stripslashes(($account34->assistance));
                echo $prestation_assurance = str_replace('?', '✓', $prestation_assurance);

                ?>
              </div>
              <div class="col-sm-3">
                <span style="font-size: 12px;font-weight: bold;color: #000;">Frais de recherche et de sauvetage :
                  <hr>
                </span>
                <?php

                $prestation_assurance = stripslashes(($account34->fraisderecherche));
                echo $prestation_assurance = str_replace('?', '✓', $prestation_assurance);

                ?>
                <hr>

                <span style="font-size: 12px;font-weight: bold;color: #000;">Vol retardé :
                  <hr>
                </span>
                <?php

                $prestation_assurance = stripslashes(($account34->volretarde));
                echo $prestation_assurance = str_replace('?', '✓', $prestation_assurance);

                ?>
                <hr>

              </div>

            </div>

          </div>
        </div>




        <script>
          $("#reservation_enfant_<?php echo $rrr; ?>_<?php echo $account34->id_assurance; ?>").poppy("popup-reservation_enfant_<?php echo $rrr; ?>_<?php echo $account34->id_assurance; ?>");
        </script>

        <?php

      }

    }
    $id_assurance = '';
    $stmt34 = $conn->prepare('SELECT * FROM assurance WHERE id_assurance !=:id_assurance');
    $stmt34->bindValue('id_assurance', $id_assurance);
    $stmt34->execute();
    while ($account34 = $stmt34->fetch(PDO::FETCH_OBJ)) {
      ?>

      <div>
        <div class="poppy" id="popup-reservation_bebe_1_<?php echo $account34->id_assurance; ?>">
          <div class="row">
            <div class="col-sm-12 pop">
              <p>
                <span class="h4"
                  style="margin-bottom: 10px !important;font-size: 20px;font-weight: 700;text-transform: uppercase;line-height: 36px;color: #000;">
                  <?php echo stripslashes($account34->titre_assurance); ?>
                </span>
                <span class="close-btn h4"
                  style="margin-bottom: 0;top: -56px;right: 28px;font-family: arial;font-size: 28px;color: red">
                  <i class="fa fa-times-circle"></i>
                </span>
                <hr>
              </p>
            </div>







            <div class="col-sm-3" style="text-align: center;">
              <h2 style="font-size: 20px;margin: 0px 0;font-weight: 100;">
                <?php
                if ($account34->info == "annuelle") {
                  echo 'Assurance annulle à';
                }
                if ($account34->info == "uniquement") {
                  echo 'Pour le voyage uniquement à';
                }
                ?>

              </h2>
              <h1 style="font-weight: 1000;">
                <?php

                if ($account34->prix_assurance == 0) {
                  echo ceil($total_bebe * $account34->pourcentage / 100);
                } else {
                  echo $account34->prix_assurance;
                }

                ?>
                <span style="font-weight: 100;font-size: 22px;">CHF</span>
              </h1>
              <hr>

              <p style="text-align:left !important;padding: 0px 10px;line-height: 13px;font-size:11px;color: #000;">

                <span style="font-size: 12px;font-weight: bold;color: #000;">Prestation :<br></span>
                <?php
                $prestation_assurance = stripslashes(($account34->prestation_assurance));
                $prestation_assurance = str_replace('?', '✓', $prestation_assurance);
                $prestation_assurance = str_replace('<div>', '<br>', $prestation_assurance);
                $prestation_assurance = str_replace('</div>', '<br>', $prestation_assurance);
                echo $prestation_assurance = str_replace('<br><br>', '<br>', $prestation_assurance);

                ?>

              </p>
            </div>

            <div class="col-sm-3">

              <span style="font-size: 12px;font-weight: bold;color: #000;">Frais d’annulation :
                <hr>
              </span>
              <?php

              $prestation_assurance = stripslashes(($account34->frais_annulation));
              echo $prestation_assurance = str_replace('?', '✓', $prestation_assurance);

              ?>

            </div>
            <div class="col-sm-3">
              <span style="font-size: 12px;font-weight: bold;color: #000;">Assistance :
                <hr>
              </span>
              <?php

              $prestation_assurance = stripslashes(($account34->assistance));
              echo $prestation_assurance = str_replace('?', '✓', $prestation_assurance);

              ?>
            </div>
            <div class="col-sm-3">
              <span style="font-size: 12px;font-weight: bold;color: #000;">Frais de recherche et de sauvetage :
                <hr>
              </span>
              <?php

              $prestation_assurance = stripslashes(($account34->fraisderecherche));
              echo $prestation_assurance = str_replace('?', '✓', $prestation_assurance);

              ?>
              <hr>

              <span style="font-size: 12px;font-weight: bold;color: #000;">Vol retardé :
                <hr>
              </span>
              <?php

              $prestation_assurance = stripslashes(($account34->volretarde));
              echo $prestation_assurance = str_replace('?', '✓', $prestation_assurance);

              ?>
              <hr>

            </div>

          </div>

        </div>
      </div>




      <script>
        $("#reservation_bebe_1_<?php echo $account34->id_assurance; ?>").poppy("popup-reservation_bebe_1_<?php echo $account34->id_assurance; ?>");
      </script>

      <?php

    }

    ?>



    <!-- xxxxxxxxxxxxxxxxxxxxxx POPUP FIXE xxxxxxxxxxxxxxxxxxxxxxx -->
    <!-- xxxxxxxxxxxxxxxxxxxxxx POPUP FIXE xxxxxxxxxxxxxxxxxxxxxxx -->
    <!-- xxxxxxxxxxxxxxxxxxxxxx POPUP FIXE xxxxxxxxxxxxxxxxxxxxxxx -->


    <div>
      <div class="poppy" id="popup-reservation_adulte1_<?php echo $id_reservation_valeur; ?>">
        <div class="col-sm-12 pop">
          <p>
            <span class="h4" style="margin-bottom: 10px !important;font-size: 25px;">
              Passeport assurance voyage all inclusive
            </span>
            <span class="close-btn h4"
              style="margin-bottom: 0;top: -56px;right: 28px;font-family: arial;font-size: 17px;">
              X
            </span>
          </p>
        </div>


        <div class="col-sm-12">
          <p style="text-align:left !important;padding: 0px 10px;line-height: 13px;font-size:11px;">

            <span style="font-size: 12px;font-weight: bold;color: #000;">Prestation :<br><br></span>
            • Frais annulation max 30'000.00 CHF<br>
            • Assistance illimitée<br>
            • Frais de recherche 30'000.00 CHF<br>
            • Retard de vol 2'000.00 CHF<br>
            • Voyage de remplacement max 30'000.00 CHF<br>
            • Assurance automobile illimitée pour les pannes<br>
            • Private medical 1'000'000.00 CHF<br>
            • Bagages 2'000.00 CHF franchis à 200.00 CHF<br>
            • Accident d’avion 100'000.00 CHF<br>
            • Protection juridique (Europe 250'000.00 CHF / Monde 50'000.00 CHF<br>
            • Assurance franchise (CDW) pour location voiture 10'000.00 CHF

          </p>
        </div>

      </div>
    </div>
    <script>
      $("#reservation_adulte1_<?php echo $id_reservation_valeur; ?>").poppy("popup-reservation_adulte1_<?php echo $id_reservation_valeur; ?>");
    </script>





    <div>
      <div class="poppy" id="popup-reservation_adulte2_<?php echo $id_reservation_valeur; ?>">
        <div class="col-sm-12 pop">
          <p>
            <span class="h4" style="margin-bottom: 10px !important;font-size: 25px;">
              Passeport assurance voyage all inclusive
            </span>
            <span class="close-btn h4"
              style="margin-bottom: 0;top: -56px;right: 28px;font-family: arial;font-size: 17px;">
              X
            </span>
          </p>
        </div>


        <div class="col-sm-12">
          <p style="text-align:left !important;padding: 0px 10px;line-height: 13px;font-size:11px;">

            <span style="font-size: 12px;font-weight: bold;color: #000;">Prestation :<br><br></span>
            • Frais annulation max 30'000.00 CHF<br>
            • Assistance illimitée<br>
            • Frais de recherche 30'000.00 CHF<br>
            • Retard de vol 2'000.00 CHF<br>
            • Voyage de remplacement max 30'000.00 CHF<br>
            • Assurance automobile illimitée pour les pannes<br>
            • Private medical 1'000'000.00 CHF<br>
            • Bagages 2'000.00 CHF franchis à 200.00 CHF<br>
            • Accident d’avion 100'000.00 CHF<br>
            • Protection juridique (Europe 250'000.00 CHF / Monde 50'000.00 CHF<br>
            • Assurance franchise (CDW) pour location voiture 10'000.00 CHF

          </p>
        </div>

      </div>
    </div>
    <script>
      $("#reservation_adulte2_<?php echo $id_reservation_valeur; ?>").poppy("popup-reservation_adulte2_<?php echo $id_reservation_valeur; ?>");
    </script>

    <div>
      <div class="poppy" id="popup-reservation_adulte3_<?php echo $id_reservation_valeur; ?>">
        <div class="col-sm-12 pop">
          <p>
            <span class="h4" style="margin-bottom: 10px !important;font-size: 25px;">
              Passeport assurance voyage all inclusive
            </span>
            <span class="close-btn h4"
              style="margin-bottom: 0;top: -56px;right: 28px;font-family: arial;font-size: 17px;">
              X
            </span>
          </p>
        </div>


        <div class="col-sm-12">
          <p style="text-align:left !important;padding: 0px 10px;line-height: 13px;font-size:11px;">

            <span style="font-size: 12px;font-weight: bold;color: #000;">Prestation :<br><br></span>
            • Frais annulation max 30'000.00 CHF<br>
            • Assistance illimitée<br>
            • Frais de recherche 30'000.00 CHF<br>
            • Retard de vol 2'000.00 CHF<br>
            • Voyage de remplacement max 30'000.00 CHF<br>
            • Assurance automobile illimitée pour les pannes<br>
            • Private medical 1'000'000.00 CHF<br>
            • Bagages 2'000.00 CHF franchis à 200.00 CHF<br>
            • Accident d’avion 100'000.00 CHF<br>
            • Protection juridique (Europe 250'000.00 CHF / Monde 50'000.00 CHF<br>
            • Assurance franchise (CDW) pour location voiture 10'000.00 CHF

          </p>
        </div>

      </div>
    </div>
    <script>
      $("#reservation_adulte3_<?php echo $id_reservation_valeur; ?>").poppy("popup-reservation_adulte3_<?php echo $id_reservation_valeur; ?>");
    </script>


    <div>
      <div class="poppy" id="popup-reservation_adulte4_<?php echo $id_reservation_valeur; ?>">
        <div class="col-sm-12 pop">
          <p>
            <span class="h4" style="margin-bottom: 10px !important;font-size: 25px;">
              Passeport assurance voyage all inclusive
            </span>
            <span class="close-btn h4"
              style="margin-bottom: 0;top: -56px;right: 28px;font-family: arial;font-size: 17px;">
              X
            </span>
          </p>
        </div>


        <div class="col-sm-12">
          <p style="text-align:left !important;padding: 0px 10px;line-height: 13px;font-size:11px;">

            <span style="font-size: 12px;font-weight: bold;color: #000;">Prestation :<br><br></span>
            • Frais annulation max 30'000.00 CHF<br>
            • Assistance illimitée<br>
            • Frais de recherche 30'000.00 CHF<br>
            • Retard de vol 2'000.00 CHF<br>
            • Voyage de remplacement max 30'000.00 CHF<br>
            • Assurance automobile illimitée pour les pannes<br>
            • Private medical 1'000'000.00 CHF<br>
            • Bagages 2'000.00 CHF franchis à 200.00 CHF<br>
            • Accident d’avion 100'000.00 CHF<br>
            • Protection juridique (Europe 250'000.00 CHF / Monde 50'000.00 CHF<br>
            • Assurance franchise (CDW) pour location voiture 10'000.00 CHF

          </p>
        </div>

      </div>
    </div>
    <script>
      $("#reservation_adulte4_<?php echo $id_reservation_valeur; ?>").poppy("popup-reservation_adulte4_<?php echo $id_reservation_valeur; ?>");
    </script>


    <div>
      <div class="poppy" id="popup-reservation_enfant1_<?php echo $id_reservation_valeur; ?>">
        <div class="col-sm-12 pop">
          <p>
            <span class="h4" style="margin-bottom: 10px !important;font-size: 25px;">
              Passeport assurance voyage all inclusive
            </span>
            <span class="close-btn h4"
              style="margin-bottom: 0;top: -56px;right: 28px;font-family: arial;font-size: 17px;">
              X
            </span>
          </p>
        </div>


        <div class="col-sm-12">
          <p style="text-align:left !important;padding: 0px 10px;line-height: 13px;font-size:11px;">

            <span style="font-size: 12px;font-weight: bold;color: #000;">Prestation :<br><br></span>
            • Frais annulation max 30'000.00 CHF<br>
            • Assistance illimitée<br>
            • Frais de recherche 30'000.00 CHF<br>
            • Retard de vol 2'000.00 CHF<br>
            • Voyage de remplacement max 30'000.00 CHF<br>
            • Assurance automobile illimitée pour les pannes<br>
            • Private medical 1'000'000.00 CHF<br>
            • Bagages 2'000.00 CHF franchis à 200.00 CHF<br>
            • Accident d’avion 100'000.00 CHF<br>
            • Protection juridique (Europe 250'000.00 CHF / Monde 50'000.00 CHF<br>
            • Assurance franchise (CDW) pour location voiture 10'000.00 CHF

          </p>
        </div>

      </div>
    </div>
    <script>
      $("#reservation_enfant1_<?php echo $id_reservation_valeur; ?>").poppy("popup-reservation_enfant1_<?php echo $id_reservation_valeur; ?>");
    </script>



    <div>
      <div class="poppy" id="popup-reservation_enfant2_<?php echo $id_reservation_valeur; ?>">
        <div class="col-sm-12 pop">
          <p>
            <span class="h4" style="margin-bottom: 10px !important;font-size: 25px;">
              Passeport assurance voyage all inclusive
            </span>
            <span class="close-btn h4"
              style="margin-bottom: 0;top: -56px;right: 28px;font-family: arial;font-size: 17px;">
              X
            </span>
          </p>
        </div>


        <div class="col-sm-12">
          <p style="text-align:left !important;padding: 0px 10px;line-height: 13px;font-size:11px;">

            <span style="font-size: 12px;font-weight: bold;color: #000;">Prestation :<br><br></span>
            • Frais annulation max 30'000.00 CHF<br>
            • Assistance illimitée<br>
            • Frais de recherche 30'000.00 CHF<br>
            • Retard de vol 2'000.00 CHF<br>
            • Voyage de remplacement max 30'000.00 CHF<br>
            • Assurance automobile illimitée pour les pannes<br>
            • Private medical 1'000'000.00 CHF<br>
            • Bagages 2'000.00 CHF franchis à 200.00 CHF<br>
            • Accident d’avion 100'000.00 CHF<br>
            • Protection juridique (Europe 250'000.00 CHF / Monde 50'000.00 CHF<br>
            • Assurance franchise (CDW) pour location voiture 10'000.00 CHF

          </p>
        </div>

      </div>
    </div>
    <script>
      $("#reservation_enfant2_<?php echo $id_reservation_valeur; ?>").poppy("popup-reservation_enfant2_<?php echo $id_reservation_valeur; ?>");
    </script>



    <div>
      <div class="poppy" id="popup-reservation_bebe1_<?php echo $id_reservation_valeur; ?>">
        <div class="col-sm-12 pop">
          <p>
            <span class="h4" style="margin-bottom: 10px !important;font-size: 25px;">
              Passeport assurance voyage all inclusive
            </span>
            <span class="close-btn h4"
              style="margin-bottom: 0;top: -56px;right: 28px;font-family: arial;font-size: 17px;">
              X
            </span>
          </p>
        </div>


        <div class="col-sm-12">
          <p style="text-align:left !important;padding: 0px 10px;line-height: 13px;font-size:11px;">

            <span style="font-size: 12px;font-weight: bold;color: #000;">Prestation :<br><br></span>
            • Frais annulation max 30'000.00 CHF<br>
            • Assistance illimitée<br>
            • Frais de recherche 30'000.00 CHF<br>
            • Retard de vol 2'000.00 CHF<br>
            • Voyage de remplacement max 30'000.00 CHF<br>
            • Assurance automobile illimitée pour les pannes<br>
            • Private medical 1'000'000.00 CHF<br>
            • Bagages 2'000.00 CHF franchis à 200.00 CHF<br>
            • Accident d’avion 100'000.00 CHF<br>
            • Protection juridique (Europe 250'000.00 CHF / Monde 50'000.00 CHF<br>
            • Assurance franchise (CDW) pour location voiture 10'000.00 CHF

          </p>
        </div>

      </div>
    </div>
    <script>
      $("#reservation_bebe1_<?php echo $id_reservation_valeur; ?>").poppy("popup-reservation_bebe1_<?php echo $id_reservation_valeur; ?>");
    </script>






    <?php
} else {
  ?>
    <div class="col-sm-12">
      <p><br><br></p>
      <p><br><br></p>
    </div>
    <div class="col-sm-12">
      <div class="row row_list_custom">
        <div class="container_list_head clearfix">
          <div class="col-sm-12">
            <div class="row row_cust_title">
              <span class="img_title"><img src="img/page-reservee.png" alt=""
                  style="max-width: 300px;width: 38px;"></span>
              <span class="h4">Page reservée</span>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-12">
      <div class="row reserve_info">
        <div class="col-sm-2">
          <img src="img/ref_infos.png" alt="">
        </div>
        <div class="col-sm-10">
          <p style="font-size: 22px;line-height: 30px;">Erreur sur le Clé de reservation, merci de bien configurer votre
            recherche depuis ce lien <a href="index.php">ici</a></p>
          <p><br><br></p>
        </div>
      </div>
    </div>
    <?php
}

?>





  <div class="col-sm-12 ombre">
    <p><br></p>
  </div>

  </div>
  </div>
  </div>
</section>









<script src="js/jquery.selectric.min.js"></script>



<?php
include ('footer.php');
?>