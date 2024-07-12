<?php

    if($adulte=="1")
    {  
      $stmt5 = $conn->prepare("SELECT * FROM chambre WHERE id_hotel =:id_hotel AND fin_validite !=:fin_validite AND debut_validite !=:debut_validite AND simple_adulte_max >=:simple_adulte_max AND simple_enfant_max >=:simple_enfant_max AND simple_bebe_max >=:simple_bebe_max ORDER BY adulte_1_total ASC");
      $stmt5 ->bindValue('simple_adulte_max', $adulte);
      $stmt5 ->bindValue('simple_enfant_max', $nb_enfant);
      $stmt5 ->bindValue('simple_bebe_max', $nb_bebe);
    }

    if($adulte=="2")
    { 
      $stmt5 = $conn->prepare("SELECT * FROM chambre WHERE id_hotel =:id_hotel AND fin_validite !=:fin_validite AND debut_validite !=:debut_validite AND double_adulte_max >=:double_adulte_max AND double_enfant_max >=:double_enfant_max AND double_bebe_max >=:double_bebe_max ORDER BY double_adulte_2_total ASC");
      $stmt5 ->bindValue('double_adulte_max', $adulte);
      $stmt5 ->bindValue('double_enfant_max', $nb_enfant);
      $stmt5 ->bindValue('double_bebe_max', $nb_bebe);
    }

    if($adulte=="3")
    {  
      $stmt5 = $conn->prepare("SELECT * FROM chambre WHERE id_hotel =:id_hotel AND fin_validite !=:fin_validite AND debut_validite !=:debut_validite AND tripple_adulte_max >=:tripple_adulte_max  ORDER BY tripple_adulte_2_total ASC");
      $stmt5 ->bindValue('tripple_adulte_max', $adulte);
    }

    if($adulte=="4")
    { 

      $stmt5 = $conn->prepare("SELECT * FROM chambre WHERE id_hotel =:id_hotel AND fin_validite !=:fin_validite AND debut_validite !=:debut_validite AND quatre_adulte_max >=:quatre_adulte_max ORDER BY quatre_adulte_2_total ASC");
      $stmt5 ->bindValue('quatre_adulte_max', $adulte);
    }

      $stmt5 ->bindValue('id_hotel', $id_hotel);
      $stmt5 ->bindValue('fin_validite', '');
      $stmt5 ->bindValue('debut_validite', '');

      $stmt5 ->execute();
      while($account5 = $stmt5 ->fetch(PDO::FETCH_OBJ))
      {
          $tab3 = explode("/", $account5 -> debut_validite);
          $debut_validite = $tab3[2].'-'.$tab3[1].'-'.$tab3[0];


          $tab2 = explode("/", $account5 -> fin_validite);
          $fin_validite = $tab2[2].'-'.$tab2[1].'-'.$tab2[0];
          if($debut_validite <= $dd AND $dd <=$fin_validite)
          {
              
//  ********************** 1 LIGNES DATES ************************************* //
//  ********************** 1 LIGNES DATES ************************************* //
//  ********************** 1 LIGNES DATES ************************************* //

          $startTime = strtotime($dd);    
          $endTime = strtotime($fin_validite);
          $j=1;
          for ( $i = $startTime; $i < $endTime; $i = $i + 86400 ) 
            {
                /// ICI TABLEAU
              $j++;
            }

         echo $nombre_jour=$j;
          include_once 'prix/prix_chambre.php';
          echo $prix_total_1 * $nombre_jour;
          $prix_chambre_1 = $prix_total_1 * $nombre_jour;
          echo '<br>';


              echo $dd.'---->'.$fin_validite.'<br>';
              echo $account5 -> id_chambre.' )'.$account5 -> nom_chambre.' //<br>';
              echo $account5 -> double_adulte_1_total.' '.$account5 -> double_enfant_1_total.' '.$account5 -> double_enfant_2_total.'CHF<br>----------------------<br>';

              if($da>$fin_validite)
              {
                  $dd2 = new DateTime($fin_validite);
                  $dd2->modify('+1 day');
                  $dd2=$dd2->format('Y-m-d');

                  $stmt55 = $conn->prepare("SELECT * FROM chambre WHERE nom_chambre =:nom_chambre AND id_hotel =:id_hotel");
                  $stmt55 ->bindValue('nom_chambre', $account5 -> nom_chambre);
                  $stmt55 ->bindValue('id_hotel', $account5 -> id_hotel);                  
                  $stmt55 ->execute();
                  while($account55 = $stmt55 ->fetch(PDO::FETCH_OBJ))
                  {
                      $tab33 = explode("/", $account55 -> debut_validite);
                      $debut_validite1 = $tab33[2].'-'.$tab33[1].'-'.$tab33[0];


                      $tab22 = explode("/", $account55 -> fin_validite);
                      $fin_validite1 = $tab22[2].'-'.$tab22[1].'-'.$tab22[0];

                      if($debut_validite1 <= $dd2 AND $dd2 <=$fin_validite1)
                      {

//  ********************** 2 LIGNES DATES ************************************* //
//  ********************** 2 LIGNES DATES ************************************* //
//  ********************** 2 LIGNES DATES ************************************* //

          $startTime = strtotime($dd2);    
          $endTime = strtotime($fin_validite1);
          $j=1;
          for ( $i = $startTime; $i < $endTime; $i = $i + 86400 ) 
            {
                /// ICI TABLEAU
              $j++;
            }

         echo $nombre_jour=$j;
          include_once 'prix/prix_chambre_2.php';
          echo $prix_total * $nombre_jour;
          $prix_chambre_2 = $prix_total * $nombre_jour;
          echo '<br>';

                        echo $dd2.'---->'.$fin_validite1.'<br>';
                        echo $account55 -> id_chambre.' )'.$account55 -> nom_chambre.'<br>';
                        echo $account55 ->  double_adulte_1_total.' '.$account55 ->  double_enfant_1_total.' '.$account55 ->  double_enfant_2_total.'CHF<br>----------------------<br>';

                            if($da>$fin_validite1)
                                {

                                  $dd22 = new DateTime($fin_validite1);
                                  $dd22->modify('+1 day');
                                  $dd22=$dd22->format('Y-m-d');

                                  $stmt555 = $conn->prepare("SELECT * FROM chambre WHERE nom_chambre =:nom_chambre AND id_hotel =:id_hotel");
                                  $stmt555 ->bindValue('nom_chambre', $account55 -> nom_chambre);
                                  $stmt555 ->bindValue('id_hotel', $account55 -> id_hotel);     
                                  $stmt555 ->execute();
                                  while($account555 = $stmt555 ->fetch(PDO::FETCH_OBJ))
                                  {
                                          $tab33 = explode("/", $account555 -> debut_validite);
                                          $debut_validite2 = $tab33[2].'-'.$tab33[1].'-'.$tab33[0];


                                          $tab22 = explode("/", $account555 -> fin_validite);
                                          $fin_validite2 = $tab22[2].'-'.$tab22[1].'-'.$tab22[0];

                                          if($debut_validite2 <= $dd22 AND $dd22 <=$fin_validite2)
                                          {

//  ********************** 3 LIGNES DATES ************************************* //
//  ********************** 3 LIGNES DATES ************************************* //
//  ********************** 3 LIGNES DATES ************************************* //                                           

                                              $startTime = strtotime($dd22);  
                                              if($da>=$fin_validite2) 
                                              {
                                                  $endTime = strtotime($fin_validite2);
                                              } 
                                              else
                                              {
                                                  $endTime = strtotime($da);
                                              }
                                              $j=1;
                                              for ( $i = $startTime; $i < $endTime; $i = $i + 86400 ) 
                                                {
                                                    /// ICI TABLEAU
                                                  $j++;
                                                }

                                             echo $nombre_jour=$j;
                                              include_once 'prix/prix_chambre_3.php';
                                              echo $prix_total * $nombre_jour;
                                              $prix_chambre_3 = $prix_total * $nombre_jour;
                                              echo '<br>';

                                              echo $dd22.'---->'.$fin_validite2.'<br>';
                                              echo $account555 -> id_chambre.' )'.$account555 -> nom_chambre.'<br>';
                                              echo $account555 -> double_adulte_1_total.' '.$account555 -> double_enfant_1_total.' '.$account555 -> double_enfant_2_total.'CHF<br>----------------------<br>';

                                              if($da>$fin_validite2)
                                              {
                                                  $dd2 = new DateTime($fin_validite2);
                                                  $dd2->modify('+1 day');
                                                  echo $dd2=$dd2->format('Y-m-d').'<br>';
                                              }
                                              else
                                              {
                                                  break;
                                              }
                                         }
                           }
                  }
              }

          }

      }
      if(isset($prix_chambre_3))
      {
        echo $prix_total_chambre = $prix_chambre_1 + $prix_chambre_2 + $prix_chambre_3;        
      }
      else
      {
        echo $prix_total_chambre = $prix_chambre_1 + $prix_chambre_2;        
      }
        echo "<br>****************************<br>";
    }
  }




?>