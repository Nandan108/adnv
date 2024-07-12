<?php

// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require_once dirname(__DIR__).'/adnv/bootstrap.php';

$mail = new PHPMailer(true); // Passing `true` enables exceptions

?>


<style type="text/css">
     body {
          padding: 0 !important;
     }

     footer {
          margin-top: 10px;
     }
</style>


<div class="col-sm-12">
     <div class="row">
          <div class="col-sm-12">
               <div class="col-sm-12">
                    <p style="text-align: center;"><img src="img/load.gif" style="width: 200px;"></p>
               </div>

          </div>

     </div>
</div>

<?php

if (isset ($_GET['id_client'])) {
     $id_client = $_GET['id_client'];





     $stmt80 = $conn->prepare('SELECT * FROM client WHERE id_client =:id_client');
     $stmt80->bindValue('id_client', $id_client);
     $stmt80->execute();
     $account80 = $stmt80->fetch(PDO::FETCH_OBJ);

     $id_client = $account80->id_client;




     $date_maj = explode('-', $account80->debut_voyage);
     if ($date_maj[1] == '01') {
          $mois = 'Janvier';
     }
     if ($date_maj[1] == '02') {
          $mois = 'Février';
     }
     if ($date_maj[1] == '03') {
          $mois = 'Mars';
     }
     if ($date_maj[1] == '04') {
          $mois = 'Avril';
     }
     if ($date_maj[1] == '05') {
          $mois = 'Mai';
     }
     if ($date_maj[1] == '06') {
          $mois = 'Juin';
     }
     if ($date_maj[1] == '07') {
          $mois = 'Juillet';
     }
     if ($date_maj[1] == '08') {
          $mois = 'Août';
     }
     if ($date_maj[1] == '08') {
          $mois = 'Août';
     }
     if ($date_maj[1] == '09') {
          $mois = 'Septembre';
     }
     if ($date_maj[1] == '10') {
          $mois = 'Octobre';
     }
     if ($date_maj[1] == '11') {
          $mois = 'Novembre';
     }
     if ($date_maj[1] == '12') {
          $mois = 'Décembre';
     }


     $debut_voyage = $date_maj[0] . ' ' . $mois . ' ' . $date_maj[2];

     $stmt5 = $conn->prepare('UPDATE client SET
    statut =:statut WHERE id_client =:id_client');

     $stmt5->bindValue('statut', '2');
     $stmt5->bindValue('id_client', $id_client);

     $stmt5->execute();

     ?>


     <div id="mail" style="color:#FFF">

          <?php



          $email = $account80->email;
          $password = $account80->password2;
          $nom = $account80->nom;
          $prenom = $account80->prenom;


          try {
               //Server settings
               //Server settings
               $mail->SMTPDebug = 1;                                 // Enable verbose debug output
               $mail->isSMTP();                                      // Set mailer to use SMTP
               $mail->Host = 'mail.infomaniak.com';  // Specify main and backup SMTP servers
               $mail->SMTPAuth = true;                               // Enable SMTP authentication
               $mail->Username = 'reservation@adnvoyage.com';                 // SMTP username
               $mail->Password = 'Reserv@tion@3205-@dnvoyage';                           // SMTP password
               $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
               $mail->Port = 587;                                    // TCP port to connect to

               //Recipients
               $mail->setFrom('reservation@adnvoyage.com', 'ADNVOYAGE');
               $mail->addAddress($email);     // Add a recipient



               $body = <<<EOF
               <html>
               <body style="width:70%; background:#FFF; padding:10px; font-size:14px; font-family:Arial,sans-serif; font-weight:500; text-align:center">
                   <h3 style="color:#000"></h3>

                   <p style="font-size: 14px;color:#000"><br>
                       <br>
                       Bonjour $prenom $nom<br>
                       <br>
                       Vous accés sur l'application de voyage ADN sont le suivante :<br>
                       Pseudo : $email<br>
                       Mot de passe : $password</br>
                       </br>
                       Cordialement,<br><br>

                       L'équipe d'ADN voyage<br><br><br></br>
                   </p>

                   <p style="text-align: center;"><br><br>
                       ADN Voyage SARL - Genève - Suisse<br>
                       <a href="https://www.adnvoyage.com">www.adnvoyage.com</a> - <a
                           href="mailto:info@adnvoyage.com">info@adnvoyage.com</a>
                   </p>
               </body>
               </html>
               EOF;


               $mail->isHTML(true);
               $mail->Subject = 'ADN Voyage APP';
               $mail->Body = utf8_decode($body);
               $mail->AltBody = strip_tags($body);

               $mail->send();
               echo 'Message has been sent';
               echo "<meta http-equiv='refresh' content='0;url=https://adnvoyage.com/admin/client.php'/>";


          } catch (Exception $e) {
               echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;

          }

} else {
     header('Location:index.php');
}

?>




</div>