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


        $stmt7000 = $conn->prepare('SELECT * FROM client WHERE id_client =:id_client');
        $stmt7000 ->bindValue('id_client', $_GET['id_client']);
        $stmt7000 ->execute();
        $account7000 = $stmt7000 ->fetch(PDO::FETCH_OBJ);

?>    


 <script src="js/jquery-1.11.3.min.js"></script>     

        <section class="nav-page" style="display: block;">
            <div class="container">
                <div class="row">

                    <div class="span7">
                        <header class="page-header">
                            <h3>Clients  | <span style="font-size: 12px;color:#00CCF4;">

                                <?php echo $account7000 -> prenom.' '.$account7000 -> nom; ?>

                            </span></h3>
                        </header>
                    </div>
                    <div class="span9">
                        <ul class="nav nav-pills">
                            <li>
                                
                                    <a href="clients.php" rel="tooltip" data-placement="left" title="Voir liste clients">
                                        <i class="icon-plus"></i> Voir la liste de client
                                    </a>
                                
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>


        <section class="page container">
            <div class="row">
                <div class="span4">
                    <div class="blockoff-right">
                        <ul id="person-list" class="nav nav-list">
                            <li class="nav-header">Clients</li>

                           
                            <li>
                                <a href="#Person-2">
                                    <i class="icon-chevron-right pull-right"></i>
                                        Passager
                                </a>
                            </li>
                           
                            
                              
                            
                        </ul>
                    </div>
                </div>
                <div class="span12">

                

                    <div id="Person-2" class="box">
                        <div class="box-header">
                            <i class="icon-globe icon-large" ></i>
                            <h5>Passager ( Maximum 6 )</h5>
                            
                        </div>
                        <div class="box-content box-table">
                            <?php include('include_client/passager_edit.php'); ?>
                        </div>

                    </div>

                    



<div id="pop_form" style="position: fixed;background:#00000085;top: 0;width: 100%;left: 0;margin: auto;z-index: 9999999999;height: 100%;display: none">


</div>

                
                </div>
            </div>
        </section>
        
    
            </div>
        </div>

        <div id="spinner" class="spinner" style="display:none;">
            Loading&hellip;
        </div>

        <footer class="application-footer">
            <div class="container">
                                <p>ADN voyage Sarl <br>
Rue Le-Corbusier, 8
1208 Genève - Suisse
info@adnvoyage.com</p>
                <div class="disclaimer">
                    <p>Ramseb & Urssy - All right reserved</p>
                    <p>Copyright © ADN voyage Sarl 2022</p>
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
        $(function() {
            $('#person-list.nav > li > a').click(function(e){
                if($(this).attr('id') == "view-all"){
                    $('div[id*="Person-"]').fadeIn('fast');
                }else{
                    var aRef = $(this);
                    var tablesToHide = $('div[id*="Person-"]:visible').length > 1
                            ? $('div[id*="Person-"]:visible') : $($('#person-list > li[class="active"] > a').attr('href'));

                    tablesToHide.hide();
                    $(aRef.attr('href')).fadeIn('fast');
                }
                $('#person-list > li[class="active"]').removeClass('active');
                $(this).parent().addClass('active');
                e.preventDefault();
            });

            $(function(){
                $('table').tablesorter();
                $("[rel=tooltip]").tooltip();
            });
        });
    </script>

	</body>
</html>


<?php



      if(isset($_POST['save_option_autre']))
      {
           

        $characts   = 'ABCDEFGHIJKLMNOPQRSTUVWXYZaAbBcCdDeEfFgGhHiIkKlMmNnoOpPqOstuvwz';    
        $characts   .= '12345678909876543210'; 
        $code_aleatoire      = ''; 
        for($i=0;$i < 5;$i++)
        { 
            $code_aleatoire .= substr($characts,rand()%(strlen($characts)),1); 
        }
        $date = date("dmY");

        $name_old_1 = $_FILES["file"]["name"];
        $name_old = str_replace('.pdf', '', $name_old_1);
        $nom_image = $name_old."_".$code_aleatoire.$date.".pdf";

       if(!file_exists("document"))
         {
          mkdir ("document");
       }

     //////////////SLIDER//////////////////////

     if ($_FILES["file"]["error"] > 0)
          {
          $er = "ERROR Return Code: " .$_FILES["file"]["error"]. "<br />";
          $url_photo="";
      
      }
     else
          {

          $img = $nom_image;
          move_uploaded_file($_FILES["file"]["tmp_name"],
          "document/" .$nom_image);
           $url_photo = $nom_image;
                  
          }

    $date2 = date("d-m-Y");
    $stmt = $conn ->prepare ("insert into `document_voyage`(`doc`, `categorie`, `id_client`, `date_ajout`) VALUE (:doc,:categorie,:id_client,:date_ajout)");

    $stmt->bindValue('date_ajout',$date2);
    $stmt->bindValue('categorie',addslashes($_POST['categorie']));
    $stmt->bindValue('id_client',addslashes($_POST['id_client']));
    $stmt->bindValue('doc',$url_photo);
    $stmt->execute();

    echo "<script type='text/javascript'>alert('Ajout document effectué avec succés');</script>";
    echo "<meta http-equiv='refresh' content='0;url=clients.php'/>";
      }














}
else{
            header('Location:index.php');
           }
?>