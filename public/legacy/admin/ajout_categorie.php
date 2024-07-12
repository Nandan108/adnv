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

            /*
				$prixnet = 7;
				$taux_change = 1.18;
				$taux_commission = 0.25;

				$result1 = ($prixnet * $taux_change) + (($prixnet * $taux_change)*$taux_commission);
				echo $result1;
			*/

if(isset($_POST['save']))
{

    $stmt = $conn ->prepare ("insert into  `carte_categorie`(`titre_categorie`) VALUE ( :titre_categorie)");
    $stmt->bindValue('titre_categorie',addslashes($_POST['titre_categorie']));
    $stmt->execute();

    echo "<script type='text/javascript'>alert('Ajout catégorie effectué, vous pouvez ajouté un autre');</script>";
    echo "<meta http-equiv='refresh' content='0;url=ajout_categorie.php'/>";


}

if(isset($_POST['save_edit']))
{

    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
    $stmt = $conn ->prepare ('UPDATE carte_categorie SET titre_categorie =:titre_categorie WHERE id_carte_categorie =:id_carte_categorie');
    $stmt->bindValue('id_carte_categorie',addslashes($_POST['id_carte_categorie']));
    $stmt->bindValue('titre_categorie',addslashes($_POST['titre_categorie']));
    $stmt->execute();

    echo "<script type='text/javascript'>alert('Modification de catégorie effectué, vous pouvez ajouté un autre');</script>";
    echo "<meta http-equiv='refresh' content='0;url=ajout_categorie.php'/>";

}

if(isset($_GET['action']) && $_GET['action']=='delete')
{
    $stmt = $conn->prepare('delete from carte_categorie WHERE id_carte_categorie = :id_carte_categorie');
    $stmt ->bindValue('id_carte_categorie', $_GET['id']);
$stmt ->execute();
}

?>


        <section class="nav-page" style="display: block;">
            <div class="container">
                <div class="row">
                    <div class="span7">
                        <header class="page-header">
                            <h3>CARTE CATEGORIE | <span style="font-size: 12px;color:#00CCF4;">Ajout catégorie </span></h3>
                        </header>
                    </div>
                    <div class="span9">
                        <ul class="nav nav-pills">
                            <li>
                                
                                    <a href="cartes.php" rel="tooltip" data-placement="left" title="Voir la carte">
                                        <i class="icon-chevron-left pull-left"></i> Voir la carte
                                    </a>
                                
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>




        <section id="my-account-security-form" class="page container">

                <div class="container">

                    <div class="alert alert-block alert-info">
                        <p>
                           Pour la configuration de site, veuillez bien verifier que tous les étapes sont bien remplir
                        </p>
                    </div>
                    <div class="row">
<?php
                    	
if(isset($_GET['action']) && $_GET['action']=='edit')
{


    $stmtx = $conn->prepare('SELECT * FROM carte_categorie WHERE id_carte_categorie = :id_carte_categorie');
    $stmtx -> bindValue('id_carte_categorie', $_GET['id']);
    $stmtx ->execute();
    $accountx = $stmtx ->fetch(PDO::FETCH_OBJ);


    ?>
                        <div id="acct-password-row" class="span6">
                            <form id="userSecurityForm" class="form-horizontal" method="post" action="" enctype="multipart/form-data">
                                <div style="padding: 10px;margin-left: 20px;border: 3px solid;margin-bottom: 30px;">
                                <h4 style="text-align: center;margin-bottom: 18px;background:#6b8c2d;padding: 5px;color:#FFF;margin-top: 0px;margin-bottom: 30px;text-transform: uppercase;">Ajout Taux monnaitaire</h4>
                                
                                <input type="hidden" value="<?php echo stripslashes($accountx -> id_carte_categorie); ?>" name="id_carte_categorie">
                                   
                                    <div class="control-group ">
                                        <label class="control-label">Catégorie</label>
                                        <div class="controls">
                                            <input id="current-pass-control" name="titre_categorie" class="span3" type="text" value="<?php echo ($accountx -> titre_categorie); ?>" autocomplete="false" required>

                                        </div>
                                    </div>
                                    
                                <footer id="submit-actions" class="form-actions">
                                    <button type="reset" class="btn" name="action" value="CANCEL">Annuler</button>
                                    <button id="submit-button" type="submit" class="btn btn-primary" name="save_edit" value="CONFIRM">Enregistrer</button>
                                    
                                </footer>

                            </div>
                            </form> 
                           </div>
<?php
}
else
{
   ?>
                        <div id="acct-password-row" class="span6">
                            <form id="userSecurityForm" class="form-horizontal" method="post" action="" enctype="multipart/form-data">
                                <div style="padding: 10px;margin-left: 20px;border: 3px solid;margin-bottom: 30px;">
                                <h4 style="text-align: center;margin-bottom: 18px;background:#6b8c2d;padding: 5px;color:#FFF;margin-top: 0px;margin-bottom: 30px;text-transform: uppercase;">Ajout catégorie lieu</h4>
                                   
                                    <div class="control-group ">
                                        <label class="control-label">Catégorie</label>
                                        <div class="controls">
                                            <input id="current-pass-control" name="titre_categorie" class="span3" type="text" value="" autocomplete="false" required>

                                        </div>
                                    </div>
                                 <footer id="submit-actions" class="form-actions">
                                    <button type="reset" class="btn" name="action" value="CANCEL">Annuler</button>
                                    <button id="submit-button" type="submit" class="btn btn-primary" name="save" value="CONFIRM">Enregistrer</button>
                                    
                                </footer>

                            </div>
                            </form> 
                           </div>
<?php
}
?>


                            
                            <div id="acct-password-row" class="span10">
                                <div style="padding: 10px;margin-left: 20px;border: 3px solid;margin-bottom: 30px;">
                                <h4 style="text-align: center;margin-bottom: 18px;background:#6b8c2d;padding: 5px;color:#FFF;margin-top: 0px;margin-bottom: 30px;text-transform: uppercase;">Liste des catégories</h4>
                                    <table style="width: 100%">
                                        <?php


                                        $stmt = $conn->prepare('SELECT * FROM carte_categorie ORDER BY titre_categorie ASC');
                                        $stmt ->execute();

                                        while($account = $stmt ->fetch(PDO::FETCH_OBJ))
                                        {
                                        ?>
                                            <tr>
                                                <td><?php echo stripslashes($account -> titre_categorie); ?></td>                       
                                                <td style="width: 29%;"><a href="ajout_categorie.php?id=<?php echo $account -> id_carte_categorie; ?>&action=delete" onclick="return confirm('Vous etes sur de supprimer cette ligne? Cette action va supprimer tous les informations réliées à cette enregistrement')" class="btn btn-danger" style="font-size: 10px;padding: 0 10px;margin-bottom: 5px;"><i class="icon-trash"></i> Supprimer</a>&nbsp;&nbsp;

<a href="ajout_categorie.php?id=<?php echo $account -> id_carte_categorie; ?>&action=edit"  class="btn btn-primary" style="font-size: 10px;padding: 0 10px;margin-bottom: 5px;"><i class="icon-edit"></i> Modifier</a>
                                                                            </td>
                                                                           

                                                                        </tr>
                                        <?php

                                        }

                                        ?>
                                    </table>

                                </div>
                            </div>                       
                          

<?php

}
else{
            header('Location:index.php');
           }
?>