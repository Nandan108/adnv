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
        <section id="my-account-security-form" class="page container">
            <form id="userSecurityForm" class="form-horizontal" action="" method="POST">
                <input type="hidden" name="verif_pass" value="<?php echo $account7 -> account_pass;?>">
                <input type="hidden" name="account_login" value="<?php echo $account7 -> account_login; ?>">
                <div class="container">

                    <div class="alert alert-block alert-info">
                        <p>
                            A tous moment vous pouvez gérer votre profil, en changaent votre mot de passe et vos autres informations.
                        </p>
                    </div>
                    <div class="row">
                        <div id="acct-password-row" class="span7">
                            <fieldset>
                                <legend>Mot de passe</legend><br>
                                <div class="control-group ">
                                    <label class="control-label">Mot de passe actuel <span class="required">*</span></label>
                                    <div class="controls">
                                        <input id="current-pass-control" name="password" class="span4" type="password" value="" autocomplete="false">

                                    </div>
                                </div>
                                <div class="control-group ">
                                    <label class="control-label">Nouveau mot de passe</label>
                                    <div class="controls">
                                        <input id="new-pass-control" name="account_pass" class="span4" type="password" value="" autocomplete="false">

                                    </div>
                                </div>
                                <div class="control-group ">
                                    <label class="control-label">Répeter le mot de passe</label>
                                    <div class="controls">
                                        <input id="new-pass-verify-control" name="re_account_pass" class="span4" type="password" value="" autocomplete="false">

                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <div id="acct-verify-row" class="span9">
                            <fieldset>
                                <legend>Profil</legend>

                                <div class="control-group ">
                                    <label class="control-label">Votre nom</label>
                                    <div class="controls">
                                        <input id="challenge-answer-control" name="nom" class="span5" type="text" value="<?php echo $nom; ?>" autocomplete="false">

                                    </div>
                                </div>
                                <div class="control-group ">
                                    <label class="control-label">Votre prénom(s)</label>
                                    <div class="controls">
                                        <input id="challenge-answer-verify-control" name="prenom" class="span5" type="text" value="<?php echo $prenom; ?>" autocomplete="false">

                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                    <footer id="submit-actions" class="form-actions">
                        <button id="submit-button" type="submit" class="btn btn-primary" name="changepass" value="CONFIRM" name="changepass">Enregistrer votre information</button>
                        <button type="submit" class="btn" name="action" value="CANCEL">Annuler</button>
                    </footer>
                </div>
            </form>
        </section>
    
            </div>
        </div>

        <footer class="application-footer">
            <div class="container">
                <p>Application Footer</p>
                <div class="disclaimer">
                    <p>This is an example disclaimer. All right reserved.</p>
                    <p>Copyright © keaplogik 2011-2012</p>
                </div>
            </div>
        </footer>
        <script src="../js/bootstrap/bootstrap-transition.js" type="text/javascript" ></script>
        <script src="../js/bootstrap/bootstrap-alert.js" type="text/javascript" ></script>
        <script src="../js/bootstrap/bootstrap-modal.js" type="text/javascript" ></script>
        <script src="../js/bootstrap/bootstrap-dropdown.js" type="text/javascript" ></script>
        <script src="../js/bootstrap/bootstrap-scrollspy.js" type="text/javascript" ></script>
        <script src="../js/bootstrap/bootstrap-tab.js" type="text/javascript" ></script>
        <script src="../js/bootstrap/bootstrap-tooltip.js" type="text/javascript" ></script>
        <script src="../js/bootstrap/bootstrap-popover.js" type="text/javascript" ></script>
        <script src="../js/bootstrap/bootstrap-button.js" type="text/javascript" ></script>
        <script src="../js/bootstrap/bootstrap-collapse.js" type="text/javascript" ></script>
        <script src="../js/bootstrap/bootstrap-carousel.js" type="text/javascript" ></script>
        <script src="../js/bootstrap/bootstrap-typeahead.js" type="text/javascript" ></script>
        <script src="../js/bootstrap/bootstrap-affix.js" type="text/javascript" ></script>
        <script src="../js/bootstrap/bootstrap-datepicker.js" type="text/javascript" ></script>
        <script src="../js/jquery/jquery-tablesorter.js" type="text/javascript" ></script>
        <script src="../js/jquery/jquery-chosen.js" type="text/javascript" ></script>
        <script src="../js/jquery/virtual-tour.js" type="text/javascript" ></script>
        <script type="text/javascript">
        $(function(){
            $('.chosen').chosen();
            $("[rel=tooltip]").tooltip();

            $("#vguide-button").click(function(e){
                new VTour(null, $('.nav-page')).tourGuide();
                e.preventDefault();
            });
            $("#vtour-button").click(function(e){
                new VTour(null, $('.nav-page')).tour();
                e.preventDefault();
            });
        });
    </script>

	</body>
</html>


<?php
}
else{
            header('Location:index.php');
           }
?>

<?php
    if(isset($_POST['changepass']))
    {
        $password = MD5($_POST['password']);
        $verif_pass = $_POST['verif_pass'];

        if($password != $verif_pass)
        {
            echo '<script type="text/javascript">alert("Mot de passe actuel incorrect")</script>';
        }

        else
        {
            $account_login = $_POST['account_login'];
            $re_account_pass = $_POST['re_account_pass'];
            $account_pass = $_POST['account_pass'];
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            if($re_account_pass != $account_pass)
            {
                echo "<script type='text/javascript'>alert('Les mots de passes ne sont pas identiques');</script>";
            }
            else
            {
                
                $stmt = $conn ->prepare ('UPDATE admin SET account_pass =:account_pass, nom =:nom , prenom =:prenom WHERE account_login =:account_login');
                $stmt -> bindValue('account_login', $_POST['account_login']);
                $stmt -> bindValue('account_pass', MD5($_POST['account_pass']));
                $stmt -> bindValue('nom', $_POST['nom']);
                $stmt -> bindValue('prenom', $_POST['prenom']);
                $stmt ->execute();
                
                echo "<script type='text/javascript'>alert('Le changement de mot de passe a été bien éfféctué, vous serez déconnecté');</script>";
                echo "<meta http-equiv='refresh' content='0;url=logout.php'/>";
                    
            }         
        }


        

        

        
    }

?>