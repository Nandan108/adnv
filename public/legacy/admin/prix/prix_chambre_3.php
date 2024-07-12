<?php

     if($adulte=="1")
    {
        if($enfant=="1")
          {
            if($enfant_age>=$account555  -> de_1_enfant AND $enfant_age<=$account555  -> a_1_enfant)
            {
                $prix_enfant = round(($account555  -> enfant_1_total) ,2);
                $prix_enfant_1 = round(($account555  -> enfant_1_total) ,2);
            }
            if($enfant_age>=$account555  -> de_2_enfant AND $enfant_age<=$account555  -> a_2_enfant)
            {
                $prix_enfant = round(($account555  -> enfant_2_total) ,2);
                $prix_enfant_1 = round(($account555  -> enfant_2_total) ,2);
            }
            if($enfant_age<$account555  -> de_1_enfant)
            {
                $prix_enfant = "0";
                $prix_enfant_1 = "0";
            }

          }
          if($enfant=="2")
          {

              if(($enfant_age>=$account555  -> de_1_enfant AND $enfant_age<=$account555  -> a_1_enfant) AND ($enfant_age_1<$account555  -> de_2_enfant))
              {
                $prix_enfant = round(($account555  -> enfant_1_total) ,2) * 2;

                $prix_enfant_1 = round(($account555  -> enfant_1_total) ,2);
                $prix_enfant_2 = round(($account555  -> enfant_1_total) ,2);
              }
              if(($enfant_age>=$account555  -> de_2_enfant AND $enfant_age<=$account555  -> a_2_enfant) AND ($enfant_age_1<$account555  -> de_3_enfant))
              {
                $prix_enfant = round(($account555  -> enfant_2_total) ,2) * 2;

                $prix_enfant_1 = round(($account555  -> enfant_2_total) ,2);
                $prix_enfant_2 = round(($account555  -> enfant_2_total) ,2);
              }

              if(($enfant_age>=$account555  -> de_1_enfant AND $enfant_age<=$account555  -> a_1_enfant) AND ($enfant_age_1>=$account555  -> de_3_enfant AND $enfant_age_1<=$account555  -> a_3_enfant))
              {
                $prix_enfant = round(($account555  -> enfant_1_total) + ($account555  -> enfant_3_total) ,2) / 2;

                $prix_enfant_1 = round(($account555  -> enfant_1_total) ,2);
                $prix_enfant_2 = round(($account555  -> enfant_3_total) ,2);
              }

              if(($enfant_age>=$account555  -> de_2_enfant AND $enfant_age<=$account555  -> a_2_enfant) AND ($enfant_age_1>=$account555  -> de_3_enfant AND $enfant_age_1<=$account555  -> a_3_enfant))
              {
                $prix_enfant = round(($account555  -> enfant_2_total) + ($account555  -> enfant_3_total) ,2) / 2;
              }
              if($enfant_age<$account555  -> de_1_enfant)
              {
                  $prix_enfant = "0";

                  $prix_enfant_1 = "0";
                  $prix_enfant_2 = "0";
              }


          }

        if($enfant=="0")
        {
          $prix_enfant = "0";
        }


          $enfant1 = round(($account555  -> enfant_1_total) ,2);
          $enfant2 = round(($account555  -> enfant_2_total) ,2);
          $prix_adulte = round(($account555  -> adulte_1_total),2);
          $prix_adulte_t = round(($account555  -> adulte_1_total),2);
          $prixChambre = round($account555  -> adulte_1_total + $prix_enfant ,2);
          $prix_adulte_3 = "0";
          $prix_adulte_4 = "0";

        $type_chambre="Single";
        $nb_max=$account555  -> simple_nb_max;
        $nb_adulte_max=$account555  -> simple_adulte_max;
        $nb_enfant_max=$account555  -> simple_enfant_max;
        $nb_bebe_max=$account555  -> simple_bebe_max;

        $type_chambre_texte= $type_chambre.'<span style="color:#FFF"> ('.$nb_adulte_max.':'.$nb_enfant_max.':'.$nb_bebe_max.')</span>';

    }
    if($adulte=="2")
    {

          if($enfant=="1")
          {
            if($enfant_age>=$account555  -> double_de_1_enfant AND $enfant_age<=$account555  -> double_a_1_enfant)
            {
                $prix_enfant = round(($account555  -> double_enfant_1_total) ,2);
                $prix_enfant_1 = round(($account555  -> double_enfant_1_total) ,2);
            }
            if($enfant_age>=$account555  -> double_de_2_enfant AND $enfant_age<=$account555  -> double_a_2_enfant)
            {
                $prix_enfant = round(($account555  -> double_enfant_2_total) ,2);
                $prix_enfant_1 = round(($account555  -> double_enfant_2_total) ,2);
            }
            if($enfant_age<$account555  -> double_de_1_enfant)
            {
                $prix_enfant = "0";
                $prix_enfant_1 = "0";
            }

          }
          if($enfant=="2")
          {

              if(($enfant_age>=$account555  -> double_de_1_enfant AND $enfant_age<=$account555  -> double_a_1_enfant) AND ($enfant_age_1<$account555  -> double_de_3_enfant))
              {
                $prix_enfant = round(($account555  -> double_enfant_1_total) ,2) * 2;

                $prix_enfant_1 = round(($account555  -> double_enfant_1_total) ,2);
                $prix_enfant_2 = round(($account555  -> double_enfant_1_total) ,2);
              }
              if(($enfant_age>=$account555  -> double_de_2_enfant AND $enfant_age<=$account555  -> double_a_2_enfant) AND ($enfant_age_1<$account555  -> double_de_3_enfant))
              {
                $prix_enfant = round(($account555  -> double_enfant_2_total) ,2) * 2;

                $prix_enfant_1 = round(($account555  -> double_enfant_2_total) ,2);
                $prix_enfant_2 = round(($account555  -> double_enfant_2_total) ,2);
              }

              if(($enfant_age>=$account555  -> double_de_1_enfant AND $enfant_age<=$account555  -> double_a_1_enfant) AND ($enfant_age_1>=$account555  -> double_de_3_enfant AND $enfant_age_1<=$account555  -> double_a_3_enfant))
              {
                $prix_enfant = round(($account555  -> double_enfant_1_total) + ($account555  -> double_enfant_3_total) ,2) / 2;

                $prix_enfant_1 = round(($account555  -> double_enfant_1_total) ,2);
                $prix_enfant_2 = round(($account555  -> double_enfant_3_total) ,2);
              }

              if($enfant_age < $account555  -> double_de_1_enfant AND $enfant_age_1>=$account555  -> double_de_3_enfant AND $enfant_age_1<=$account555  -> double_a_3_enfant)
              {

                  $prix_enfant = "0";

                  $prix_enfant_1 = "0";
                  $prix_enfant_2 = round(($account555  -> double_enfant_3_total) ,2);



              }

              if($enfant_age < $account555  -> double_de_1_enfant AND $enfant_age_1 < $account555  -> double_de_3_enfant)
              {

                  $prix_enfant = "0";

                  $prix_enfant_1 = "0";
                  $prix_enfant_2 = "0";

              }


          }

        if($enfant=="0")
        {
          $prix_enfant = "0";
        }

         $enfant1 = round(($account555  -> double_enfant_1_total) ,2);
         $enfant2 = round(($account555  -> double_enfant_2_total) ,2);

         $prix_adulte = round((($account555  -> double_adulte_1_total + $account555  -> double_adulte_2_total) / 2) ,2);
         $prix_adulte_t = $prix_adulte * 2;

         $prixChambre = round((($account555  -> double_adulte_2_total) + ($account555  -> double_adulte_2_total)) + ($prix_enfant) ,2);
         $prix_adulte_3 = "0";
         $prix_adulte_4 = "0";

        $type_chambre="Double";
        $nb_max=$account555  -> double_nb_max;
        $nb_adulte_max=$account555  -> double_adulte_max;
        $nb_enfant_max=$account555  -> double_enfant_max;
        $nb_bebe_max=$account555  -> double_bebe_max;
        $type_chambre_texte= $type_chambre.'<span style="color:#FFF"> ('.$nb_adulte_max.':'.$nb_enfant_max.':'.$nb_bebe_max.')</span>';

    }
    if($adulte=="3" AND $enfant=="1")
    {
     $enfant1 =  round(($account555  -> quatre_adulte_4_total) ,2);
     $enfant2 = "0";
     $prix_adulte = round($account555  -> quatre_adulte_2_total ,2);
     $prix_adulte_t = round(($account555  -> quatre_adulte_2_total) ,2) * 2;
     $prix_adulte_3 = round($account555  -> quatre_adulte_3_total ,2);
     $prix_enfant = round(($account555  -> quatre_adulte_4_total) ,2);
      $prix_adulte_4 = "0";
     $prixChambre = round($account555  -> quatre_adulte_2_total + $account555  -> quatre_adulte_2_total + $account555  -> quatre_adulte_3_total + $account555  -> quatre_adulte_4_total, 2);

        $type_chambre="Quadruple";
        $nb_max=$account555  -> quatre_nb_max;
        $nb_adulte_max=$account555  -> quatre_adulte_max;
        $nb_enfant_max="0";
        $nb_bebe_max="0";
        $type_chambre_texte= $type_chambre.'<span style="color:#FFF"> ('.$nb_adulte_max.':'.$nb_enfant_max.':'.$nb_bebe_max.')</span>';

    }
    if($adulte=="4")
    {
     $enfant1 = "0";
    $enfant2 = "0";
      $prix_adulte = round($account555  -> quatre_adulte_2_total ,2);
      $prix_adulte_t = round(($account555  -> quatre_adulte_2_total) ,2) * 2;
      $prix_adulte_3 = round($account555  -> quatre_adulte_3_total ,2);
      $prix_adulte_4 = round($account555  -> quatre_adulte_4_total ,2);
      $prix_enfant = "0";
      $prixChambre = round($account555  -> quatre_adulte_2_total + $account555  -> quatre_adulte_2_total + $account555  -> quatre_adulte_3_total + $account555  -> quatre_adulte_4_total, 2);

        $type_chambre="Quadruple";
        $nb_max=$account555  -> quatre_nb_max;
        $nb_adulte_max=$account555  -> quatre_adulte_max;
        $nb_enfant_max="0";
        $nb_bebe_max="0";

        $type_chambre_texte= $type_chambre.'<span style="color:#FFF"> ('.$nb_adulte_max.':'.$nb_enfant_max.':'.$nb_bebe_max.')</span>';

    }
    if($adulte=="3" AND $enfant=="0")
    {
        $enfant1 = "0";
        $enfant2 = "0";

        $prix_adulte = round($account555  -> tripple_adulte_2_total ,2);
        $prix_adulte_t = round(($account555  -> tripple_adulte_2_total) ,2) * 2;
        $prix_adulte_3 = round($account555  -> tripple_adulte_3_total ,2);
        $prix_enfant = "0";
        $prix_adulte_4 = "0";
        $prixChambre = round((($account555  -> tripple_adulte_2_total) + ($account555  -> tripple_adulte_2_total) + ($account555  -> tripple_adulte_3_total)), 2);

        $type_chambre="Triple";
        $nb_max=$account555  -> tripple_nb_max;
        $nb_adulte_max=$account555  -> tripple_adulte_max;
        $nb_enfant_max="0";
        $nb_bebe_max="0";
        $type_chambre_texte= $type_chambre.'<span style="color:#FFF"> ('.$nb_adulte_max.':'.$nb_enfant_max.':'.$nb_bebe_max.')</span>';
    }

  ?>





<!-- rrrrrrrrr------------   PROMOTION -------------------- -->





<?php

if($account555 -> debut_remise2!='' AND $account555 -> fin_remise2!='' AND $account555 -> debut_remise2_voyage!='' AND $account555 -> fin_remise2_voyage)
  {
        // ********************** REMISE OFFRE SPECIALE ************************ //
        // ********************** REMISE OFFRE SPECIALE ************************ //
        // ********************** REMISE OFFRE SPECIALE ************************ //

        $debut_remise = $account555  -> debut_remise2;
        $tab_debut_remise = explode(' ', $debut_remise);
        $fin_remise = $account555  -> fin_remise2;
        $tab_fin_remise=explode(" ", $fin_remise);

        if(isset($tab_debut_remise[0]))
        {
            $tab33 = explode("/", $tab_debut_remise[0]);
            $debut_validite_remise = $tab33[2].'-'.$tab33[1].'-'.$tab33[0];
        }
        $tab22 = explode("/", $tab_fin_remise[0]);
        $fin_validite_remise = $tab22[2].'-'.$tab22[1].'-'.$tab22[0];
        if($debut_validite_remise <= $date_visiteur AND $date_visiteur <=$fin_validite_remise)
        {
              $debut_remise = $account555 -> debut_remise2_voyage;
              $tab_debut_remise = explode(' ', $debut_remise);
              $fin_remise = $account555 -> fin_remise2_voyage;
              $tab_fin_remise=explode(" ", $fin_remise);

              if(isset($tab_debut_remise[0]))
              {
                  $tab33 = explode("/", $tab_debut_remise[0]);
                  $debut_validite_remise = $tab33[2].'-'.$tab33[1].'-'.$tab33[0];
              }

              $tab22 = explode("/", $tab_fin_remise[0]);
              $fin_validite_remise = $tab22[2].'-'.$tab22[1].'-'.$tab22[0];
              if(($debut_validite_remise <= $dd AND $da >=$fin_validite_remise) OR ($debut_validite_remise <= $da AND $da <=$fin_validite_remise) OR ($debut_validite_remise <= $da AND $da >=$fin_validite_remise))
              {
                      //************************* CALCUL TARIF AVEC PROMOTION OFFRE SPECIAL ********** //
                      //************************* CALCUL TARIF AVEC PROMOTION OFFRE SPECIAL ********** //
                      if($account555 -> unite2 =="chf_2")
                      {


                            if($adulte=="1")
                            {
                                            $prix_total_adulte_4 = "0";
                                            $prix_total_adulte_3 = "0";
                                            $prix_total_adulte_2 = "1";
                                            $prix_total_adulte=round($prix_adulte, 2);

                                            $promo=$account555 -> adulte_1_total_chf;
                                            $adulte_total_30 = $account555 -> adulte_1_total_chf;
                                            $adulte_total_30_2 = "0";
                                            $prix_total_adulte_promo=$promo;
                                            $adulte_total_30_3 = "0";
                                            $adulte_total_30_4 = "0";


                                            if($nb_enfant=="1")
                                            {
                                                    if($enfant_age>=$account555  -> de_1_enfant AND $enfant_age<=$account555  -> a_1_enfant)
                                                    {
                                                        $prix_total_enfant_promo = round(($account555  -> enfant_1_total_chf) ,2);
                                                        $enfant_total = round(($account555  -> enfant_1_total_chf) ,2);
                                                    }
                                                    if($enfant_age>=$account555  -> de_2_enfant AND $enfant_age<=$account555  -> a_2_enfant)
                                                    {
                                                        $prix_total_enfant_promo = round(($account555  -> enfant_2_total_chf) ,2);
                                                        $enfant_total = round(($account555  -> enfant_2_total_chf) ,2);
                                                    }

                                                    $prix_total_enfant=$prix_enfant_1;
                                                    $enfant_total_2 ="0";
                                            }

                                            if($nb_enfant=="2")
                                            {

                                                  if(($enfant_age>=$account555  -> de_1_enfant AND $enfant_age<=$account555  -> a_1_enfant) AND ($enfant_age_1<$account555  -> de_3_enfant))
                                                  {
                                                    $prix_total_enfant_promo = round(($account555  -> enfant_1_total_chf) ,2) * 2;

                                                    $enfant_total = round(($account555  -> enfant_1_total_chf) ,2);
                                                    $enfant_total_2 = round(($account555  -> enfant_1_total_chf) ,2);
                                                  }
                                                  if(($enfant_age>=$account555  -> de_2_enfant AND $enfant_age<=$account555  -> a_2_enfant) AND ($enfant_age_1<$account555  -> de_3_enfant))
                                                  {
                                                    $prix_total_enfant_promo = round(($account555  -> enfant_2_total_chf) ,2) * 2;

                                                    $enfant_total = round(($account555  -> enfant_2_total_chf) ,2);
                                                    $enfant_total_2 = round(($account555  -> enfant_2_total_chf) ,2);
                                                  }

                                                  if(($enfant_age>=$account555  -> de_1_enfant AND $enfant_age<=$account555  -> a_1_enfant) AND ($enfant_age_1>=$account555  -> de_3_enfant AND $enfant_age_1<=$account555  -> a_3_enfant))
                                                  {
                                                    $prix_total_enfant_promo = round(($account555  -> enfant_1_total_chf) + ($account555  -> enfant_3_total_chf) ,2);

                                                    $enfant_total = round(($account555  -> enfant_1_total_chf) ,2);
                                                    $enfant_total_2 = round(($account555  -> enfant_3_total_chf) ,2);
                                                  }

                                                  if(($enfant_age>=$account555  -> de_2_enfant AND $enfant_age<=$account555  -> a_2_enfant) AND ($enfant_age_1>=$account555  -> de_3_enfant AND $enfant_age_1<=$account555  -> a_3_enfant))
                                                  {
                                                    $prix_total_enfant_promo = round(($account555  -> enfant_2_total_chf) + ($account555  -> enfant_3_total_chf) ,2);
                                                  }

                                                  $prix_total_enfant=round(($prix_enfant_1 + $prix_enfant_2), 2);
                                            }

                                            if($nb_enfant=="0")
                                            {
                                              $enfant_total="0";
                                              $enfant_total_2 ="0";
                                              $prix_total_enfant_promo= "0";
                                              $prix_total_enfant= "0";
                                            }

                                            if($nb_bebe=="1")
                                            {
                                                if($account555 -> bebe_1_net_chf!="")
                                                {
                                                    $bebe_total=$account555 -> bebe_1_net_chf;
                                                    $prix_bebe=round($account5555 -> bebe_1_total,2);
                                                    $prix_total_bebe=round($prix_bebe, 2);

                                                    $promo=round($account555 -> bebe_1_net_chf,2);
                                                    $prix_total_bebe_promo=$promo;
                                                }
                                                else
                                                {
                                                    $bebe_total=$account555 -> bebe_1_net_chf;
                                                    $prix_bebe=round($account5555 -> bebe_1_total,2);
                                                    $prix_total_bebe=round($prix_bebe, 2);
                                                    $prix_total_bebe_promo=$bebe_total;
                                                }
                                            }

                                            if($nb_bebe=="0")
                                            {
                                              $prix_bebe ="0";
                                              $bebe_total = "0";
                                              $prix_total_bebe_promo= "0";
                                              $prix_total_bebe= "0";

                                            }
                                        }

                            if($adulte=="2")
                            {
                                            $prix_total_adulte_4 = "0";
                                            $prix_total_adulte_3 = "0";
                                            $prix_total_adulte=round($prix_adulte * 2 , 2);

                                            $promo=$account555 -> double_adulte_1_total_chf + $account555 -> double_adulte_2_total_chf;
                                            $adulte_total_30 = $account555 -> double_adulte_1_total_chf;
                                            $adulte_total_30_2 = $account555 -> double_adulte_2_total_chf;
                                            $prix_total_adulte_promo=$promo;
                                            $adulte_total_30_3 = "0";
                                            $adulte_total_30_4 = "0";


                                            if($nb_enfant=="1")
                                            {
                                                    if($enfant_age>=$account555  -> double_de_1_enfant AND $enfant_age<=$account555  -> double_a_1_enfant)
                                                    {
                                                        $prix_total_enfant_promo = round(($account555  -> double_enfant_1_total_chf) ,2);
                                                        $enfant_total = round(($account555  -> double_enfant_1_total_chf) ,2);
                                                    }
                                                    if($enfant_age>=$account555  -> double_de_2_enfant AND $enfant_age<=$account555  -> double_a_2_enfant)
                                                    {
                                                        $prix_total_enfant_promo = round(($account555  -> double_enfant_2_total_chf) ,2);
                                                        $enfant_total = round(($account555  -> double_enfant_2_total_chf) ,2);
                                                    }

                                                    $prix_total_enfant=$prix_enfant_1;
                                                    $enfant_total_2 ="0";
                                            }

                                            if($nb_enfant=="2")
                                            {

                                                  if(($enfant_age>=$account555  -> double_de_1_enfant AND $enfant_age<=$account555  -> double_a_1_enfant) AND ($enfant_age_1<$account555  -> double_de_3_enfant))
                                                  {
                                                    $prix_total_enfant_promo = round(($account555  -> double_enfant_1_total_chf) ,2) * 2;

                                                    $enfant_total = round(($account555  -> double_enfant_1_total_chf) ,2);
                                                    $enfant_total_2 = round(($account555  -> double_enfant_1_total_chf) ,2);
                                                  }
                                                  if(($enfant_age>=$account555  -> double_de_2_enfant AND $enfant_age<=$account555  -> double_a_2_enfant) AND ($enfant_age_1<$account555  -> double_de_3_enfant))
                                                  {
                                                    $prix_total_enfant_promo = round(($account555  -> double_enfant_2_total_chf) ,2) * 2;

                                                    $enfant_total = round(($account555  -> double_enfant_2_total_chf) ,2);
                                                    $enfant_total_2 = round(($account555  -> double_enfant_2_total_chf) ,2);
                                                  }

                                                  if(($enfant_age>=$account555  -> double_de_1_enfant AND $enfant_age<=$account555  -> double_a_1_enfant) AND ($enfant_age_1>=$account555  -> double_de_3_enfant AND $enfant_age_1<=$account555  -> double_a_3_enfant))
                                                  {
                                                    $prix_total_enfant_promo = round(($account555  -> double_enfant_1_total_chf) + ($account555  -> double_enfant_3_total_chf) ,2);

                                                    $enfant_total = round(($account555  -> double_enfant_1_total_chf) ,2);
                                                    $enfant_total_2 = round(($account555  -> double_enfant_3_total_chf) ,2);
                                                  }

                                                  if(($enfant_age>=$account555  -> double_de_2_enfant AND $enfant_age<=$account555  -> double_a_2_enfant) AND ($enfant_age_1>=$account555  -> double_de_3_enfant AND $enfant_age_1<=$account555  -> double_a_3_enfant))
                                                  {
                                                    $prix_total_enfant_promo = round(($account555  -> double_enfant_2_total_chf) + ($account555  -> double_enfant_3_total_chf) ,2);
                                                  }

                                                  $prix_total_enfant=round(($prix_enfant_1 + $prix_enfant_2), 2);
                                            }

                                            if($nb_enfant=="0")
                                            {
                                              $enfant_total="0";
                                              $enfant_total_2 ="0";
                                              $prix_total_enfant_promo= "0";
                                              $prix_total_enfant= "0";
                                            }

                                            if($nb_bebe=="1")
                                            {
                                                if($account555 -> double_bebe_1_net_chf!="")
                                                {
                                                    $bebe_total=$account555 -> double_bebe_1_net_chf;
                                                    $prix_bebe=round($account5555 -> double_bebe_1_total,2);
                                                    $prix_total_bebe=round($prix_bebe, 2);

                                                    $promo=round($account555 -> double_bebe_1_net_chf,2);
                                                    $prix_total_bebe_promo=$promo;
                                                }
                                                else
                                                {
                                                    $bebe_total=$account555 -> double_bebe_1_net_chf;
                                                    $prix_bebe=round($account5555 -> double_bebe_1_total,2);
                                                    $prix_total_bebe=round($prix_bebe, 2);
                                                    $prix_total_bebe_promo=$bebe_total;
                                                }
                                            }

                                            if($nb_bebe=="0")
                                            {
                                              $prix_bebe ="0";
                                              $bebe_total = "0";
                                              $prix_total_bebe_promo= "0";
                                              $prix_total_bebe= "0";

                                            }
                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //
                                        }

                                      if($adulte=="3")
                                      {
                                            $prix_total_adulte_4 = "0";
                                            $prix_total_adulte=round($prix_adulte * 3 , 2);

                                            $promo=$account555 -> tripple_adulte_1_total_chf + $account555 -> tripple_adulte_2_total_chf + $account555 -> tripple_adulte_3_total_chf;
                                            $adulte_total_30 = $account555 -> tripple_adulte_1_total_chf;
                                            $adulte_total_30_2 = $account555 -> tripple_adulte_2_total_chf;
                                            $adulte_total_30_3 = $account555 -> tripple_adulte_3_total_chf;

                                            $prix_total_adulte_promo=$promo;
                                            $adulte_total_30_4 = "0";

                                            if($nb_enfant=="0")
                                            {
                                              $enfant_total="0";
                                              $enfant_total_2 ="0";
                                              $prix_total_enfant_promo= "0";
                                              $prix_total_enfant= "0";
                                            }

                                            if($nb_bebe=="0")
                                            {
                                              $prix_bebe ="0";
                                              $bebe_total = "0";
                                              $prix_total_bebe_promo= "0";
                                              $prix_total_bebe= "0";

                                            }

                                      }

                                      if($adulte=="4")
                                      {
                                            $prix_total_adulte=round($prix_adulte * 4 , 2);

                                            $promo=($account555 -> quatre_adulte_1_total_chf + $account555 -> quatre_adulte_2_total_chf + $account555 -> quatre_adulte_3_total_chf + $account555 -> quatre_adulte_4_total_chf)/4;
                                            $adulte_total_30 = $account555 -> quatre_adulte_1_total_chf;
                                            $adulte_total_30_2 = $account555 -> quatre_adulte_2_total_chf;
                                            $adulte_total_30_3 = $account555 -> quatre_adulte_3_total_chf;
                                            $adulte_total_30_4 = $account555 -> quatre_adulte_4_total_chf;

                                            $adulte_total_30_4 = "0";

                                            if($nb_enfant=="0")
                                            {
                                              $enfant_total="0";
                                              $enfant_total_2 ="0";
                                              $prix_total_enfant_promo= "0";
                                              $prix_total_enfant= "0";
                                            }

                                            if($nb_bebe=="0")
                                            {
                                              $prix_bebe ="0";
                                              $bebe_total = "0";
                                              $prix_total_bebe_promo= "0";
                                              $prix_total_bebe= "0";

                                            }

                                      }
                      }
                      else
                      {


                            if($adulte=="1")
                            {
                                            $prix_total_adulte_4 = "0";
                                            $prix_total_adulte_3 = "0";
                                            $prix_total_adulte_2 = "1";
                                            $prix_total_adulte=round($prix_adulte, 2);

                                            $promo=$account555 -> adulte_1_total_pourcentage;
                                            $adulte_total_30 = $account555 -> adulte_1_total_pourcentage;
                                            $adulte_total_30_2 = "0";
                                            $prix_total_adulte_promo=$promo;
                                            $adulte_total_30_3 = "0";
                                            $adulte_total_30_4 = "0";


                                            if($nb_enfant=="1")
                                            {
                                                    if($enfant_age>=$account555  -> de_1_enfant AND $enfant_age<=$account555  -> a_1_enfant)
                                                    {
                                                        $prix_total_enfant_promo = round(($account555  -> enfant_1_total_pourcentage) ,2);
                                                        $enfant_total = round(($account555  -> enfant_1_total_pourcentage) ,2);
                                                    }
                                                    if($enfant_age>=$account555  -> de_2_enfant AND $enfant_age<=$account555  -> a_2_enfant)
                                                    {
                                                        $prix_total_enfant_promo = round(($account555  -> enfant_2_total_pourcentage) ,2);
                                                        $enfant_total = round(($account555  -> enfant_2_total_pourcentage) ,2);
                                                    }

                                                    $prix_total_enfant=$prix_enfant_1;
                                                    $enfant_total_2 ="0";
                                            }

                                            if($nb_enfant=="2")
                                            {

                                                  if(($enfant_age>=$account555  -> de_1_enfant AND $enfant_age<=$account555  -> a_1_enfant) AND ($enfant_age_1<$account555  -> de_3_enfant))
                                                  {
                                                    $prix_total_enfant_promo = round(($account555  -> enfant_1_total_pourcentage) ,2) * 2;

                                                    $enfant_total = round(($account555  -> enfant_1_total_pourcentage) ,2);
                                                    $enfant_total_2 = round(($account555  -> enfant_1_total_pourcentage) ,2);
                                                  }
                                                  if(($enfant_age>=$account555  -> de_2_enfant AND $enfant_age<=$account555  -> a_2_enfant) AND ($enfant_age_1<$account555  -> de_3_enfant))
                                                  {
                                                    $prix_total_enfant_promo = round(($account555  -> enfant_2_total_pourcentage) ,2) * 2;

                                                    $enfant_total = round(($account555  -> enfant_2_total_pourcentage) ,2);
                                                    $enfant_total_2 = round(($account555  -> enfant_2_total_pourcentage) ,2);
                                                  }

                                                  if(($enfant_age>=$account555  -> de_1_enfant AND $enfant_age<=$account555  -> a_1_enfant) AND ($enfant_age_1>=$account555  -> de_3_enfant AND $enfant_age_1<=$account555  -> a_3_enfant))
                                                  {
                                                    $prix_total_enfant_promo = round(($account555  -> enfant_1_total_pourcentage) + ($account555  -> enfant_3_total_pourcentage) ,2);

                                                    $enfant_total = round(($account555  -> enfant_1_total_pourcentage) ,2);
                                                    $enfant_total_2 = round(($account555  -> enfant_3_total_pourcentage) ,2);
                                                  }

                                                  if(($enfant_age>=$account555  -> de_2_enfant AND $enfant_age<=$account555  -> a_2_enfant) AND ($enfant_age_1>=$account555  -> de_3_enfant AND $enfant_age_1<=$account555  -> a_3_enfant))
                                                  {
                                                    $prix_total_enfant_promo = round(($account555  -> enfant_2_total_pourcentage) + ($account555  -> enfant_3_total_pourcentage) ,2);
                                                  }

                                                  $prix_total_enfant=round(($prix_enfant_1 + $prix_enfant_2), 2);
                                            }

                                            if($nb_enfant=="0")
                                            {
                                              $enfant_total="0";
                                              $enfant_total_2 ="0";
                                              $prix_total_enfant_promo= "0";
                                              $prix_total_enfant= "0";
                                            }

                                            if($nb_bebe=="1")
                                            {
                                                if($account555 -> bebe_1_net_pourcentage!="")
                                                {
                                                    $bebe_total=$account555 -> bebe_1_net_pourcentage;
                                                    $prix_bebe=round($account5555 -> bebe_1_total,2);
                                                    $prix_total_bebe=round($prix_bebe, 2);

                                                    $promo=round($account555 -> bebe_1_net_pourcentage,2);
                                                    $prix_total_bebe_promo=$promo;
                                                }
                                                else
                                                {
                                                    $bebe_total=$account555 -> bebe_1_net_pourcentage;
                                                    $prix_bebe=round($account5555 -> bebe_1_total,2);
                                                    $prix_total_bebe=round($prix_bebe, 2);
                                                    $prix_total_bebe_promo=$bebe_total;
                                                }
                                            }

                                            if($nb_bebe=="0")
                                            {
                                              $prix_bebe ="0";
                                              $bebe_total = "0";
                                              $prix_total_bebe_promo= "0";
                                              $prix_total_bebe= "0";

                                            }
                                        }

                            if($adulte=="2")
                            {
                                            $prix_total_adulte_4 = "0";
                                            $prix_total_adulte_3 = "0";
                                            $prix_total_adulte=round($prix_adulte * 2 , 2);

                                            $promo=$account555 -> double_adulte_1_total_pourcentage + $account555 -> double_adulte_2_total_pourcentage;
                                            $adulte_total_30 = $account555 -> double_adulte_1_total_pourcentage;
                                            $adulte_total_30_2 = $account555 -> double_adulte_2_total_pourcentage;
                                            $prix_total_adulte_promo=$promo;
                                            $adulte_total_30_3 = "0";
                                            $adulte_total_30_4 = "0";


                                            if($nb_enfant=="1")
                                            {
                                                    if($enfant_age>=$account555  -> double_de_1_enfant AND $enfant_age<=$account555  -> double_a_1_enfant)
                                                    {
                                                        $prix_total_enfant_promo = round(($account555  -> double_enfant_1_total_pourcentage) ,2);
                                                        $enfant_total = round(($account555  -> double_enfant_1_total_pourcentage) ,2);
                                                    }
                                                    if($enfant_age>=$account555  -> double_de_2_enfant AND $enfant_age<=$account555  -> double_a_2_enfant)
                                                    {
                                                        $prix_total_enfant_promo = round(($account555  -> double_enfant_2_total_pourcentage) ,2);
                                                        $enfant_total = round(($account555  -> double_enfant_2_total_pourcentage) ,2);
                                                    }

                                                    $prix_total_enfant=$prix_enfant_1;
                                                    $enfant_total_2 ="0";
                                            }

                                            if($nb_enfant=="2")
                                            {

                                                  if(($enfant_age>=$account555  -> double_de_1_enfant AND $enfant_age<=$account555  -> double_a_1_enfant) AND ($enfant_age_1<$account555  -> double_de_3_enfant))
                                                  {
                                                    $prix_total_enfant_promo = round(($account555  -> double_enfant_1_total_pourcentage) ,2) * 2;

                                                    $enfant_total = round(($account555  -> double_enfant_1_total_pourcentage) ,2);
                                                    $enfant_total_2 = round(($account555  -> double_enfant_1_total_pourcentage) ,2);
                                                  }
                                                  if(($enfant_age>=$account555  -> double_de_2_enfant AND $enfant_age<=$account555  -> double_a_2_enfant) AND ($enfant_age_1<$account555  -> double_de_3_enfant))
                                                  {
                                                    $prix_total_enfant_promo = round(($account555  -> double_enfant_2_total_pourcentage) ,2) * 2;

                                                    $enfant_total = round(($account555  -> double_enfant_2_total_pourcentage) ,2);
                                                    $enfant_total_2 = round(($account555  -> double_enfant_2_total_pourcentage) ,2);
                                                  }

                                                  if(($enfant_age>=$account555  -> double_de_1_enfant AND $enfant_age<=$account555  -> double_a_1_enfant) AND ($enfant_age_1>=$account555  -> double_de_3_enfant AND $enfant_age_1<=$account555  -> double_a_3_enfant))
                                                  {
                                                    $prix_total_enfant_promo = round(($account555  -> double_enfant_1_total_pourcentage) + ($account555  -> double_enfant_3_total_pourcentage) ,2);

                                                    $enfant_total = round(($account555  -> double_enfant_1_total_pourcentage) ,2);
                                                    $enfant_total_2 = round(($account555  -> double_enfant_3_total_pourcentage) ,2);
                                                  }

                                                  if(($enfant_age>=$account555  -> double_de_2_enfant AND $enfant_age<=$account555  -> double_a_2_enfant) AND ($enfant_age_1>=$account555  -> double_de_3_enfant AND $enfant_age_1<=$account555  -> double_a_3_enfant))
                                                  {
                                                    $prix_total_enfant_promo = round(($account555  -> double_enfant_2_total_pourcentage) + ($account555  -> double_enfant_3_total_pourcentage) ,2);
                                                  }

                                                  $prix_total_enfant=round(($prix_enfant_1 + $prix_enfant_2), 2);
                                            }

                                            if($nb_enfant=="0")
                                            {
                                              $enfant_total="0";
                                              $enfant_total_2 ="0";
                                              $prix_total_enfant_promo= "0";
                                              $prix_total_enfant= "0";
                                            }

                                            if($nb_bebe=="1")
                                            {
                                                if($account555 -> double_bebe_1_net_pourcentage!="")
                                                {
                                                    $bebe_total=$account555 -> double_bebe_1_net_pourcentage;
                                                    $prix_bebe=round($account5555 -> double_bebe_1_total,2);
                                                    $prix_total_bebe=round($prix_bebe, 2);

                                                    $promo=round($account555 -> double_bebe_1_net_pourcentage,2);
                                                    $prix_total_bebe_promo=$promo;
                                                }
                                                else
                                                {
                                                    $bebe_total=$account555 -> double_bebe_1_net_pourcentage;
                                                    $prix_bebe=round($account5555 -> double_bebe_1_total,2);
                                                    $prix_total_bebe=round($prix_bebe, 2);
                                                    $prix_total_bebe_promo=$bebe_total;
                                                }
                                            }

                                            if($nb_bebe=="0")
                                            {
                                              $prix_bebe ="0";
                                              $bebe_total = "0";
                                              $prix_total_bebe_promo= "0";
                                              $prix_total_bebe= "0";

                                            }
                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //
                                        }

                                      if($adulte=="3")
                                      {
                                            $prix_total_adulte_4 = "0";
                                            $prix_total_adulte=round($prix_adulte * 3 , 2);

                                            $promo=$account555 -> tripple_adulte_1_total_pourcentage + $account555 -> tripple_adulte_2_total_pourcentage + $account555 -> tripple_adulte_3_total_pourcentage;
                                            $adulte_total_30 = $account555 -> tripple_adulte_1_total_pourcentage;
                                            $adulte_total_30_2 = $account555 -> tripple_adulte_2_total_pourcentage;
                                            $adulte_total_30_3 = $account555 -> tripple_adulte_3_total_pourcentage;

                                            $prix_total_adulte_promo=$promo;
                                            $adulte_total_30_4 = "0";

                                            if($nb_enfant=="0")
                                            {
                                              $enfant_total="0";
                                              $enfant_total_2 ="0";
                                              $prix_total_enfant_promo= "0";
                                              $prix_total_enfant= "0";
                                            }

                                            if($nb_bebe=="0")
                                            {
                                              $prix_bebe ="0";
                                              $bebe_total = "0";
                                              $prix_total_bebe_promo= "0";
                                              $prix_total_bebe= "0";

                                            }

                                      }

                                      if($adulte=="4")
                                      {
                                            $prix_total_adulte=round($prix_adulte * 4 , 2);

                                            $promo=($account555 -> quatre_adulte_1_total_pourcentage + $account555 -> quatre_adulte_2_total_pourcentage + $account555 -> quatre_adulte_3_total_pourcentage + $account555 -> quatre_adulte_4_total_pourcentage)/4;
                                            $adulte_total_30 = $account555 -> quatre_adulte_1_total_pourcentage;
                                            $adulte_total_30_2 = $account555 -> quatre_adulte_2_total_pourcentage;
                                            $adulte_total_30_3 = $account555 -> quatre_adulte_3_total_pourcentage;
                                            $adulte_total_30_4 = $account555 -> quatre_adulte_4_total_pourcentage;

                                            $adulte_total_30_4 = "0";

                                            if($nb_enfant=="0")
                                            {
                                              $enfant_total="0";
                                              $enfant_total_2 ="0";
                                              $prix_total_enfant_promo= "0";
                                              $prix_total_enfant= "0";
                                            }

                                            if($nb_bebe=="0")
                                            {
                                              $prix_bebe ="0";
                                              $bebe_total = "0";
                                              $prix_total_bebe_promo= "0";
                                              $prix_total_bebe= "0";

                                            }

                                      }

                      }


                      //************************* FIN CALCUL TARIF AVEC PROMOTION OFFRE SPECIAL ********** //
                      //************************* FIN CALCUL TARIF AVEC PROMOTION OFFRE SPECIAL ********** //
              }
              else
              {


                      if($account555 -> debut_remise!='' AND $account555 -> fin_remise!='' AND $account555 -> debut_remise_voyage!='' AND $account555 -> fin_remise_voyage)
                      {
                        // ********************** REMISE EARLY BOOKING ************************ //
                        // ********************** REMISE EARLY BOOKING ************************ //
                        // ********************** REMISE EARLY BOOKING ************************ //



        $debut_remise = $account555  -> debut_remise;
        $tab_debut_remise = explode(' ', $debut_remise);
        $fin_remise = $account555  -> fin_remise;
        $tab_fin_remise=explode(" ", $fin_remise);

        if(isset($tab_debut_remise[0]))
        {
            $tab33 = explode("/", $tab_debut_remise[0]);
            $debut_validite_remise = $tab33[2].'-'.$tab33[1].'-'.$tab33[0];
        }
        $tab22 = explode("/", $tab_fin_remise[0]);
        $fin_validite_remise = $tab22[2].'-'.$tab22[1].'-'.$tab22[0];
        if($debut_validite_remise <= $date_visiteur AND $date_visiteur <=$fin_validite_remise)
        {
              $debut_remise = $account555 -> debut_remise_voyage;
              $tab_debut_remise = explode(' ', $debut_remise);
              $fin_remise = $account555 -> fin_remise_voyage;
              $tab_fin_remise=explode(" ", $fin_remise);

              if(isset($tab_debut_remise[0]))
              {
                  $tab33 = explode("/", $tab_debut_remise[0]);
                  $debut_validite_remise = $tab33[2].'-'.$tab33[1].'-'.$tab33[0];
              }

              $tab22 = explode("/", $tab_fin_remise[0]);
              $fin_validite_remise = $tab22[2].'-'.$tab22[1].'-'.$tab22[0];
              if(($debut_validite_remise <= $dd AND $da >=$fin_validite_remise) OR ($debut_validite_remise <= $da AND $da <=$fin_validite_remise) OR ($debut_validite_remise <= $da AND $da >=$fin_validite_remise))
              {

                                    if($adulte=="1")
                                        {
                                            $prix_total_adulte_4 = "0";
                                            $prix_total_adulte_3 = "0";
                                            $prix_total_adulte=round($prix_adulte , 2);
                                            $promo=$prix_total_adulte - ($prix_total_adulte * $account555 -> remise / 100);
                                            $prix_total_adulte_promo=$promo;

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //

                                            $adulte_total_30 = round(($account555 -> adulte_1_total),2) - (round(($account555 -> adulte_1_total),2) * $account555 -> remise / 100);
                                            $adulte_total_30_2 = "0";
                                            $adulte_total_30_3 = "0";
                                            $adulte_total_30_4 = "0";

                                       if($nb_enfant=="1")
                                            {
                                                  $enfant_total = $prix_enfant_1 - ($prix_enfant_1 * $account555 -> remise / 100);
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_enfant=="2")
                                            {
                                                  $enfant_total = $prix_enfant_1 - ($prix_enfant_1 * $account555 -> remise / 100);
                                                  $enfant_total_2 = $prix_enfant_2 - ($prix_enfant_2 * $account555 -> remise / 100);
                                            }

                                            if($nb_enfant=="0")
                                            {
                                              $enfant_total="0";
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_bebe=="1")
                                            {
                                              $bebe_total = round(($account555 -> bebe_1_total),2) - (round(($account555 -> bebe_1_total),2) * $account555 -> remise / 100);
                                            }

                                            if($nb_bebe=="0")
                                            {
                                              $bebe_total = "0";
                                            }

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //


                                        }
                                    if($adulte=="2")
                                        {

                                            $prix_total_adulte_4 = "0";
                                            $prix_total_adulte_3 = "0";
                                            $prix_total_adulte=round($prix_adulte * 2 , 2);
                                            $promo=$prix_total_adulte - ($prix_total_adulte * $account555 -> remise / 100);
                                            $prix_total_adulte_promo=$promo;

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //

                                            $adulte_total_30 = round(($account555 -> double_adulte_1_total),2) - (round(($account555 -> double_adulte_1_total),2) * $account555 -> remise / 100);
                                            $adulte_total_30_2 = round(($account555 -> double_adulte_2_total),2) - (round(($account555 -> double_adulte_2_total),2) * $account555 -> remise / 100);
                                            $adulte_total_30_3 = "0";
                                            $adulte_total_30_4 = "0";


                                       if($nb_enfant=="1")
                                            {
                                                  $enfant_total = $prix_enfant_1 - ($prix_enfant_1 * $account555 -> remise / 100);
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_enfant=="2")
                                            {
                                                  $enfant_total = $prix_enfant_1 - ($prix_enfant_1 * $account555 -> remise / 100);
                                                  $enfant_total_2 = $prix_enfant_2 - ($prix_enfant_2 * $account555 -> remise / 100);
                                            }


                                            if($nb_enfant=="0")
                                            {
                                              $enfant_total="0";
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_bebe=="1")
                                            {
                                              $bebe_total = round(($account555 -> double_bebe_1_total),2) - (round(($account555 -> double_bebe_1_total),2) * $account555 -> remise / 100);
                                            }

                                            if($nb_bebe=="0")
                                            {
                                              $bebe_total = "0";
                                            }

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //

                                        }
                                    if($adulte=="3")
                                        {
                                            $prix_total_adulte_4 = "0";
                                            $prix_total_adulte=round($prix_adulte * 2, 2);
                                            $prix_total_adulte_3 = round($prix_adulte_3, 2);
                                            $prix_total_adulte = $prix_total_adulte + $prix_total_adulte_3;
                                            $promo=$prix_total_adulte - ($prix_total_adulte * $account555 -> remise / 100);
                                            $prix_total_adulte_promo=$promo;

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //

                                            $adulte_total_30 = round(($account555 -> tripple_adulte_1_total),2) - (round(($account555 -> tripple_adulte_1_total),2) * $account555 -> remise / 100);
                                            $adulte_total_30_2 = round(($account555 -> tripple_adulte_2_total),2) - (round(($account555 -> tripple_adulte_2_total),2) * $account555 -> remise / 100);
                                            $adulte_total_30_3 = round(($account555 -> tripple_adulte_3_total),2) - (round(($account555 -> tripple_adulte_3_total),2) * $account555 -> remise / 100);
                                            $adulte_total_30_4 = "0";


                                       if($nb_enfant=="1")
                                            {
                                                  $enfant_total = $prix_enfant_1 - ($prix_enfant_1 * $account555 -> remise / 100);
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_enfant=="2")
                                            {
                                                  $enfant_total = $prix_enfant_1 - ($prix_enfant_1 * $account555 -> remise / 100);
                                                  $enfant_total_2 = $prix_enfant_2 - ($prix_enfant_2 * $account555 -> remise / 100);
                                            }


                                            if($nb_enfant=="0")
                                            {
                                              $enfant_total="0";
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_bebe=="1")
                                            {
                                              $bebe_total = round(($account555 -> tripple_bebe_1_total),2) - (round(($account555 -> tripple_bebe_1_total),2) * $account555 -> remise / 100);
                                            }

                                            if($nb_bebe=="0")
                                            {
                                              $bebe_total = "0";
                                            }

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //

                                        }
                                    if($adulte=="4")
                                        {
                                            $prix_total_adulte=round($prix_adulte * 2 , 2);
                                            $prix_total_adulte_3 = round($prix_adulte_3 , 2);
                                            $prix_total_adulte_4 = round($prix_adulte_4, 2);
                                            $prix_total_adulte = $prix_total_adulte + $prix_total_adulte_3 + $prix_total_adulte_4;
                                            $promo=$prix_total_adulte - ($prix_total_adulte * $account555 -> remise / 100);
                                            $prix_total_adulte_promo=$promo;

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //

                                            $adulte_total_30 = round(($account555 -> quatre_adulte_1_total),2) - (round(($account555 -> quatre_adulte_1_total),2) * $account555 -> remise / 100);
                                            $adulte_total_30_2 = round(($account555 -> quatre_adulte_2_total),2) - (round(($account555 -> quatre_adulte_2_total),2) * $account555 -> remise / 100);
                                            $adulte_total_30_3 = round(($account555 -> quatre_adulte_3_total),2) - (round(($account555 -> quatre_adulte_3_total),2) * $account555 -> remise / 100);
                                            $adulte_total_30_4 = round(($account555 -> quatre_adulte_4_total),2) - (round(($account555 -> quatre_adulte_4_total),2) * $account555 -> remise / 100);

                                       if($nb_enfant=="1")
                                            {
                                                  $enfant_total = $prix_enfant_1 - ($prix_enfant_1 * $account555 -> remise / 100);
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_enfant=="2")
                                            {
                                                  $enfant_total = $prix_enfant_1 - ($prix_enfant_1 * $account555 -> remise / 100);
                                                  $enfant_total_2 = $prix_enfant_2 - ($prix_enfant_2 * $account555 -> remise / 100);
                                            }


                                            if($nb_enfant=="0")
                                            {
                                              $enfant_total="0";
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_bebe=="1")
                                            {
                                              $bebe_total = round(($account555 -> quatre_bebe_1_total),2) - (round(($account555 -> quatre_bebe_1_total),2) * $account555 -> remise / 100);
                                            }

                                            if($nb_bebe=="0")
                                            {
                                              $bebe_total = "0";
                                            }

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //

                                        }
                                    if($nb_enfant!="0")
                                        {
                                            $prix_total_enfant=round($prix_enfant * $nb_enfant , 2);
                                            $promo=$prix_total_enfant - ($prix_total_enfant * $account555 -> remise / 100);
                                            $prix_total_enfant_promo=$promo;
                                        }
                                    else
                                        {
                                            $prix_total_enfant_promo="0";
                                            $prix_total_enfant="0";
                                        }
                                    if($nb_bebe!="0")
                                        {
                                            $prix_bebe=round($account555 -> bebe_1_total,2);
                                            $prix_total_bebe=round($prix_bebe * $nb_bebe , 2);
                                            $promo=$prix_total_bebe - ($prix_total_bebe * $account555 -> remise / 100);
                                            $prix_total_bebe_promo=$promo;
                                        }
                                    else
                                        {
                                            $prix_total_bebe="0";
                                            $prix_total_bebe_promo="0";
                                        }

              }
        }
        else
        {


                               if($adulte=="1")
                                            {
                                                    $prix_total_adulte_4 = "0";
                                                    $prix_total_adulte_3 = "0";
                                                    $prix_total_adulte=round($prix_adulte , 2);
                                                    $prix_total_adulte_promo="0";

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //

                                            $adulte_total_30 = round(($account555 -> adulte_1_total),2);
                                            $adulte_total_30_2 = "0";
                                            $adulte_total_30_3 = "0";
                                            $adulte_total_30_4 = "0";

                                          if($nb_enfant=="1")
                                            {
                                                  $enfant_total = $prix_enfant_1;
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_enfant=="2")
                                            {
                                                  $enfant_total = $prix_enfant_1;
                                                  $enfant_total_2 = $prix_enfant_2;
                                            }


                                            if($nb_enfant=="0")
                                            {
                                              $enfant_total="0";
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_bebe=="1")
                                            {
                                              $bebe_total = round(($account555 -> bebe_1_total),2);
                                            }

                                            if($nb_bebe=="0")
                                            {
                                              $bebe_total = "0";
                                            }

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            }
                                        if($adulte=="2")
                                            {


                                                    $prix_total_adulte_4 = "0";
                                                    $prix_total_adulte_3 = "0";
                                                    $prix_total_adulte=round($prix_adulte * 2 , 2);
                                                    $prix_total_adulte_promo="0";

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //

                                            $adulte_total_30 = round(($account555 -> double_adulte_1_total),2);
                                            $adulte_total_30_2 = round(($account555 -> double_adulte_2_total),2);
                                            $adulte_total_30_3 = "0";
                                            $adulte_total_30_4 = "0";


                                          if($nb_enfant=="1")
                                            {
                                                  $enfant_total = $prix_enfant_1;
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_enfant=="2")
                                            {
                                                  $enfant_total = $prix_enfant_1;
                                                  $enfant_total_2 = $prix_enfant_2;
                                            }

                                            if($nb_enfant=="0")
                                            {
                                              $enfant_total="0";
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_bebe=="1")
                                            {
                                              $bebe_total = round(($account555 -> double_bebe_1_total),2);
                                            }

                                            if($nb_bebe=="0")
                                            {
                                              $bebe_total = "0";
                                            }

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //

                                            }
                                        if($adulte=="3")
                                            {
                                                    $prix_total_adulte_4 = "0";
                                                    $prix_total_adulte=round($prix_adulte * 2, 2);
                                                    $prix_total_adulte_3 = round($prix_adulte_3, 2);
                                                    $prix_total_adulte = $prix_total_adulte + $prix_total_adulte_3;
                                                    $prix_total_adulte_promo="0";

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //

                                            $adulte_total_30 = round(($account555 -> tripple_adulte_1_total),2);
                                            $adulte_total_30_2 = round(($account555 -> tripple_adulte_2_total),2);
                                            $adulte_total_30_3 = round(($account555 -> tripple_adulte_3_total),2);
                                            $adulte_total_30_4 = "0";


                                          if($nb_enfant=="1")
                                            {
                                                  $enfant_total = $prix_enfant_1;
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_enfant=="2")
                                            {
                                                  $enfant_total = $prix_enfant_1;
                                                  $enfant_total_2 = $prix_enfant_2;
                                            }

                                            if($nb_enfant=="0")
                                            {
                                              $enfant_total="0";
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_bebe=="1")
                                            {
                                              $bebe_total = round(($account555 -> tripple_bebe_1_total),2);
                                            }

                                            if($nb_bebe=="0")
                                            {
                                              $bebe_total = "0";
                                            }

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            }
                                        if($adulte=="4")
                                            {
                                                    $prix_total_adulte=round($prix_adulte * 2 , 2);
                                                    $prix_total_adulte_3 = round($prix_adulte_3 , 2);
                                                    $prix_total_adulte_4 = round($prix_adulte_4, 2);
                                                    $prix_total_adulte = $prix_total_adulte + $prix_total_adulte_3 + $prix_total_adulte_4;
                                                    $prix_total_adulte_promo="0";

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //

                                            $adulte_total_30 = round(($account555 -> quatre_adulte_1_total),2);
                                            $adulte_total_30_2 = round(($account555 -> quatre_adulte_2_total),2);
                                            $adulte_total_30_3 = round(($account555 -> quatre_adulte_3_total),2);
                                            $adulte_total_30_4 = round(($account555 -> quatre_adulte_4_total),2);


                                          if($nb_enfant=="1")
                                            {
                                                  $enfant_total = $prix_enfant_1;
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_enfant=="2")
                                            {
                                                  $enfant_total = $prix_enfant_1;
                                                  $enfant_total_2 = $prix_enfant_2;
                                            }

                                            if($nb_enfant=="0")
                                            {
                                              $enfant_total="0";
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_bebe=="1")
                                            {
                                              $bebe_total = round(($account555 -> quatre_bebe_1_total),2);
                                            }

                                            if($nb_bebe=="0")
                                            {
                                              $bebe_total = "0";
                                            }

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            }
                                        if($nb_enfant!="0")
                                            {
                                                    $prix_total_enfant=round($prix_enfant * $nb_enfant , 2);
                                                    $prix_total_enfant_promo="0";
                                            }
                                        else
                                            {
                                                    $prix_total_enfant_promo="0";
                                                    $prix_total_enfant="0";
                                            }

                                        if($nb_bebe!="0")
                                            {
                                                    $prix_bebe=round($account555 -> bebe_1_total,2);
                                                    $prix_total_bebe=round($prix_bebe * $nb_bebe , 2);
                                                    $prix_total_bebe_promo="0";
                                            }
                                        else
                                            {
                                                    $prix_total_bebe="0";
                                                    $prix_total_bebe_promo="0";
                                            }

        }


                      }
                      else
                      {

                        // ********************** PRIX NORMAL ************************ //
                        // ********************** PRIX NORMAL ************************ //
                        // ********************** PRIX NORMAL ************************ //
                         if($adulte=="1")
                                            {
                                                    $prix_total_adulte_4 = "0";
                                                    $prix_total_adulte_3 = "0";
                                                    $prix_total_adulte=round($prix_adulte , 2);
                                                    $prix_total_adulte_promo="0";

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //

                                            $adulte_total_30 = round(($account555 -> adulte_1_total),2);
                                            $adulte_total_30_2 = "0";
                                            $adulte_total_30_3 = "0";
                                            $adulte_total_30_4 = "0";

                                          if($nb_enfant=="1")
                                            {
                                                  $enfant_total = $prix_enfant_1;
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_enfant=="2")
                                            {
                                                  $enfant_total = $prix_enfant_1;
                                                  $enfant_total_2 = $prix_enfant_2;
                                            }


                                            if($nb_enfant=="0")
                                            {
                                              $enfant_total="0";
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_bebe=="1")
                                            {
                                              $bebe_total = round(($account555 -> bebe_1_total),2);
                                            }

                                            if($nb_bebe=="0")
                                            {
                                              $bebe_total = "0";
                                            }

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            }
                                        if($adulte=="2")
                                            {


                                                    $prix_total_adulte_4 = "0";
                                                    $prix_total_adulte_3 = "0";
                                                    $prix_total_adulte=round($prix_adulte * 2 , 2);
                                                    $prix_total_adulte_promo="0";

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //

                                            $adulte_total_30 = round(($account555 -> double_adulte_1_total),2);
                                            $adulte_total_30_2 = round(($account555 -> double_adulte_2_total),2);
                                            $adulte_total_30_3 = "0";
                                            $adulte_total_30_4 = "0";


                                          if($nb_enfant=="1")
                                            {
                                                  $enfant_total = $prix_enfant_1;
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_enfant=="2")
                                            {
                                                  $enfant_total = $prix_enfant_1;
                                                  $enfant_total_2 = $prix_enfant_2;
                                            }

                                            if($nb_enfant=="0")
                                            {
                                              $enfant_total="0";
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_bebe=="1")
                                            {
                                              $bebe_total = round(($account555 -> double_bebe_1_total),2);
                                            }

                                            if($nb_bebe=="0")
                                            {
                                              $bebe_total = "0";
                                            }

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //

                                            }
                                        if($adulte=="3")
                                            {
                                                    $prix_total_adulte_4 = "0";
                                                    $prix_total_adulte=round($prix_adulte * 2, 2);
                                                    $prix_total_adulte_3 = round($prix_adulte_3, 2);
                                                    $prix_total_adulte = $prix_total_adulte + $prix_total_adulte_3;
                                                    $prix_total_adulte_promo="0";

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //

                                            $adulte_total_30 = round(($account555 -> tripple_adulte_1_total),2);
                                            $adulte_total_30_2 = round(($account555 -> tripple_adulte_2_total),2);
                                            $adulte_total_30_3 = round(($account555 -> tripple_adulte_3_total),2);
                                            $adulte_total_30_4 = "0";


                                          if($nb_enfant=="1")
                                            {
                                                  $enfant_total = $prix_enfant_1;
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_enfant=="2")
                                            {
                                                  $enfant_total = $prix_enfant_1;
                                                  $enfant_total_2 = $prix_enfant_2;
                                            }

                                            if($nb_enfant=="0")
                                            {
                                              $enfant_total="0";
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_bebe=="1")
                                            {
                                              $bebe_total = round(($account555 -> tripple_bebe_1_total),2);
                                            }

                                            if($nb_bebe=="0")
                                            {
                                              $bebe_total = "0";
                                            }

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            }
                                        if($adulte=="4")
                                            {
                                                    $prix_total_adulte=round($prix_adulte * 2 , 2);
                                                    $prix_total_adulte_3 = round($prix_adulte_3 , 2);
                                                    $prix_total_adulte_4 = round($prix_adulte_4, 2);
                                                    $prix_total_adulte = $prix_total_adulte + $prix_total_adulte_3 + $prix_total_adulte_4;
                                                    $prix_total_adulte_promo="0";

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //

                                            $adulte_total_30 = round(($account555 -> quatre_adulte_1_total),2);
                                            $adulte_total_30_2 = round(($account555 -> quatre_adulte_2_total),2);
                                            $adulte_total_30_3 = round(($account555 -> quatre_adulte_3_total),2);
                                            $adulte_total_30_4 = round(($account555 -> quatre_adulte_4_total),2);


                                          if($nb_enfant=="1")
                                            {
                                                  $enfant_total = $prix_enfant_1;
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_enfant=="2")
                                            {
                                                  $enfant_total = $prix_enfant_1;
                                                  $enfant_total_2 = $prix_enfant_2;
                                            }

                                            if($nb_enfant=="0")
                                            {
                                              $enfant_total="0";
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_bebe=="1")
                                            {
                                              $bebe_total = round(($account555 -> quatre_bebe_1_total),2);
                                            }

                                            if($nb_bebe=="0")
                                            {
                                              $bebe_total = "0";
                                            }

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            }
                                        if($nb_enfant!="0")
                                            {
                                                    $prix_total_enfant=round($prix_enfant * $nb_enfant , 2);
                                                    $prix_total_enfant_promo="0";
                                            }
                                        else
                                            {
                                                    $prix_total_enfant_promo="0";
                                                    $prix_total_enfant="0";
                                            }

                                        if($nb_bebe!="0")
                                            {
                                                    $prix_bebe=round($account555 -> bebe_1_total,2);
                                                    $prix_total_bebe=round($prix_bebe * $nb_bebe , 2);
                                                    $prix_total_bebe_promo="0";
                                            }
                                        else
                                            {
                                                    $prix_total_bebe="0";
                                                    $prix_total_bebe_promo="0";
                                            }
                      }
              }

        }
        else
        {


                if($account555 -> debut_remise!='' AND $account555 -> fin_remise!='' AND $account555 -> debut_remise_voyage!='' AND $account555 -> fin_remise_voyage)
                  {
                    // ********************** REMISE EARLY BOOKING ************************ //
                    // ********************** REMISE EARLY BOOKING ************************ //
                    // ********************** REMISE EARLY BOOKING ************************ //



        $debut_remise = $account555  -> debut_remise;
        $tab_debut_remise = explode(' ', $debut_remise);
        $fin_remise = $account555  -> fin_remise;
        $tab_fin_remise=explode(" ", $fin_remise);

        if(isset($tab_debut_remise[0]))
        {
            $tab33 = explode("/", $tab_debut_remise[0]);
            $debut_validite_remise = $tab33[2].'-'.$tab33[1].'-'.$tab33[0];
        }
        $tab22 = explode("/", $tab_fin_remise[0]);
        $fin_validite_remise = $tab22[2].'-'.$tab22[1].'-'.$tab22[0];
        if($debut_validite_remise <= $date_visiteur AND $date_visiteur <=$fin_validite_remise)
        {


              $debut_remise = $account555 -> debut_remise_voyage;
              $tab_debut_remise = explode(' ', $debut_remise);
              $fin_remise = $account555 -> fin_remise_voyage;
              $tab_fin_remise=explode(" ", $fin_remise);

              if(isset($tab_debut_remise[0]))
              {
                  $tab33 = explode("/", $tab_debut_remise[0]);
                  $debut_validite_remise = $tab33[2].'-'.$tab33[1].'-'.$tab33[0];
              }

              $tab22 = explode("/", $tab_fin_remise[0]);
              $fin_validite_remise = $tab22[2].'-'.$tab22[1].'-'.$tab22[0];
              if(($debut_validite_remise <= $dd AND $da >=$fin_validite_remise) OR ($debut_validite_remise <= $da AND $da <=$fin_validite_remise) OR ($debut_validite_remise <= $da AND $da >=$fin_validite_remise))
              {



                                    if($adulte=="1")
                                        {
                                            $prix_total_adulte_4 = "0";
                                            $prix_total_adulte_3 = "0";
                                            $prix_total_adulte=round($prix_adulte , 2);
                                            $promo=$prix_total_adulte - ($prix_total_adulte * $account555 -> remise / 100);
                                            $prix_total_adulte_promo=$promo;

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //

                                            $adulte_total_30 = round(($account555 -> adulte_1_total),2) - (round(($account555 -> adulte_1_total),2) * $account555 -> remise / 100);
                                            $adulte_total_30_2 = "0";
                                            $adulte_total_30_3 = "0";
                                            $adulte_total_30_4 = "0";

                                       if($nb_enfant=="1")
                                            {
                                                  $enfant_total = $prix_enfant_1 - ($prix_enfant_1 * $account555 -> remise / 100);
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_enfant=="2")
                                            {
                                                  $enfant_total = $prix_enfant_1 - ($prix_enfant_1 * $account555 -> remise / 100);
                                                  $enfant_total_2 = $prix_enfant_2 - ($prix_enfant_2 * $account555 -> remise / 100);
                                            }

                                            if($nb_enfant=="0")
                                            {
                                              $enfant_total="0";
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_bebe=="1")
                                            {
                                              $bebe_total = round(($account555 -> bebe_1_total),2) - (round(($account555 -> bebe_1_total),2) * $account555 -> remise / 100);
                                            }

                                            if($nb_bebe=="0")
                                            {
                                              $bebe_total = "0";
                                            }

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //


                                        }
                                    if($adulte=="2")
                                        {

                                            $prix_total_adulte_4 = "0";
                                            $prix_total_adulte_3 = "0";
                                            $prix_total_adulte=round($prix_adulte * 2 , 2);
                                            $promo=$prix_total_adulte - ($prix_total_adulte * $account555 -> remise / 100);
                                            $prix_total_adulte_promo=$promo;

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //

                                            $adulte_total_30 = round(($account555 -> double_adulte_1_total),2) - (round(($account555 -> double_adulte_1_total),2) * $account555 -> remise / 100);
                                            $adulte_total_30_2 = round(($account555 -> double_adulte_2_total),2) - (round(($account555 -> double_adulte_2_total),2) * $account555 -> remise / 100);
                                            $adulte_total_30_3 = "0";
                                            $adulte_total_30_4 = "0";


                                       if($nb_enfant=="1")
                                            {
                                                  $enfant_total = $prix_enfant_1 - ($prix_enfant_1 * $account555 -> remise / 100);
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_enfant=="2")
                                            {
                                                  $enfant_total = $prix_enfant_1 - ($prix_enfant_1 * $account555 -> remise / 100);
                                                  $enfant_total_2 = $prix_enfant_2 - ($prix_enfant_2 * $account555 -> remise / 100);
                                            }


                                            if($nb_enfant=="0")
                                            {
                                              $enfant_total="0";
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_bebe=="1")
                                            {
                                              $bebe_total = round(($account555 -> double_bebe_1_total),2) - (round(($account555 -> double_bebe_1_total),2) * $account555 -> remise / 100);
                                            }

                                            if($nb_bebe=="0")
                                            {
                                              $bebe_total = "0";
                                            }

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //

                                        }
                                    if($adulte=="3")
                                        {
                                            $prix_total_adulte_4 = "0";
                                            $prix_total_adulte=round($prix_adulte * 2, 2);
                                            $prix_total_adulte_3 = round($prix_adulte_3, 2);
                                            $prix_total_adulte = $prix_total_adulte + $prix_total_adulte_3;
                                            $promo=$prix_total_adulte - ($prix_total_adulte * $account555 -> remise / 100);
                                            $prix_total_adulte_promo=$promo;

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //

                                            $adulte_total_30 = round(($account555 -> tripple_adulte_1_total),2) - (round(($account555 -> tripple_adulte_1_total),2) * $account555 -> remise / 100);
                                            $adulte_total_30_2 = round(($account555 -> tripple_adulte_2_total),2) - (round(($account555 -> tripple_adulte_2_total),2) * $account555 -> remise / 100);
                                            $adulte_total_30_3 = round(($account555 -> tripple_adulte_3_total),2) - (round(($account555 -> tripple_adulte_3_total),2) * $account555 -> remise / 100);
                                            $adulte_total_30_4 = "0";


                                       if($nb_enfant=="1")
                                            {
                                                  $enfant_total = $prix_enfant_1 - ($prix_enfant_1 * $account555 -> remise / 100);
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_enfant=="2")
                                            {
                                                  $enfant_total = $prix_enfant_1 - ($prix_enfant_1 * $account555 -> remise / 100);
                                                  $enfant_total_2 = $prix_enfant_2 - ($prix_enfant_2 * $account555 -> remise / 100);
                                            }


                                            if($nb_enfant=="0")
                                            {
                                              $enfant_total="0";
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_bebe=="1")
                                            {
                                              $bebe_total = round(($account555 -> tripple_bebe_1_total),2) - (round(($account555 -> tripple_bebe_1_total),2) * $account555 -> remise / 100);
                                            }

                                            if($nb_bebe=="0")
                                            {
                                              $bebe_total = "0";
                                            }

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //

                                        }
                                    if($adulte=="4")
                                        {
                                            $prix_total_adulte=round($prix_adulte * 2 , 2);
                                            $prix_total_adulte_3 = round($prix_adulte_3 , 2);
                                            $prix_total_adulte_4 = round($prix_adulte_4, 2);
                                            $prix_total_adulte = $prix_total_adulte + $prix_total_adulte_3 + $prix_total_adulte_4;
                                            $promo=$prix_total_adulte - ($prix_total_adulte * $account555 -> remise / 100);
                                            $prix_total_adulte_promo=$promo;

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //

                                            $adulte_total_30 = round(($account555 -> quatre_adulte_1_total),2) - (round(($account555 -> quatre_adulte_1_total),2) * $account555 -> remise / 100);
                                            $adulte_total_30_2 = round(($account555 -> quatre_adulte_2_total),2) - (round(($account555 -> quatre_adulte_2_total),2) * $account555 -> remise / 100);
                                            $adulte_total_30_3 = round(($account555 -> quatre_adulte_3_total),2) - (round(($account555 -> quatre_adulte_3_total),2) * $account555 -> remise / 100);
                                            $adulte_total_30_4 = round(($account555 -> quatre_adulte_4_total),2) - (round(($account555 -> quatre_adulte_4_total),2) * $account555 -> remise / 100);

                                       if($nb_enfant=="1")
                                            {
                                                  $enfant_total = $prix_enfant_1 - ($prix_enfant_1 * $account555 -> remise / 100);
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_enfant=="2")
                                            {
                                                  $enfant_total = $prix_enfant_1 - ($prix_enfant_1 * $account555 -> remise / 100);
                                                  $enfant_total_2 = $prix_enfant_2 - ($prix_enfant_2 * $account555 -> remise / 100);
                                            }


                                            if($nb_enfant=="0")
                                            {
                                              $enfant_total="0";
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_bebe=="1")
                                            {
                                              $bebe_total = round(($account555 -> quatre_bebe_1_total),2) - (round(($account555 -> quatre_bebe_1_total),2) * $account555 -> remise / 100);
                                            }

                                            if($nb_bebe=="0")
                                            {
                                              $bebe_total = "0";
                                            }

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //

                                        }
                                    if($nb_enfant!="0")
                                        {
                                            $prix_total_enfant=round($prix_enfant * $nb_enfant , 2);
                                            $promo=$prix_total_enfant - ($prix_total_enfant * $account555 -> remise / 100);
                                            $prix_total_enfant_promo=$promo;
                                        }
                                    else
                                        {
                                            $prix_total_enfant_promo="0";
                                            $prix_total_enfant="0";
                                        }
                                    if($nb_bebe!="0")
                                        {
                                            $prix_bebe=round($account555 -> bebe_1_total,2);
                                            $prix_total_bebe=round($prix_bebe * $nb_bebe , 2);
                                            $promo=$prix_total_bebe - ($prix_total_bebe * $account555 -> remise / 100);
                                            $prix_total_bebe_promo=$promo;
                                        }
                                    else
                                        {
                                            $prix_total_bebe="0";
                                            $prix_total_bebe_promo="0";
                                        }

              }
              else
              {
                include 'include_3.php';
              }
        }
        else
        {
                               if($adulte=="1")
                                            {
                                                    $prix_total_adulte_4 = "0";
                                                    $prix_total_adulte_3 = "0";
                                                    $prix_total_adulte=round($prix_adulte , 2);
                                                    $prix_total_adulte_promo="0";

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //

                                            $adulte_total_30 = round(($account555 -> adulte_1_total),2);
                                            $adulte_total_30_2 = "0";
                                            $adulte_total_30_3 = "0";
                                            $adulte_total_30_4 = "0";

                                          if($nb_enfant=="1")
                                            {
                                                  $enfant_total = $prix_enfant_1;
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_enfant=="2")
                                            {
                                                  $enfant_total = $prix_enfant_1;
                                                  $enfant_total_2 = $prix_enfant_2;
                                            }


                                            if($nb_enfant=="0")
                                            {
                                              $enfant_total="0";
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_bebe=="1")
                                            {
                                              $bebe_total = round(($account555 -> bebe_1_total),2);
                                            }

                                            if($nb_bebe=="0")
                                            {
                                              $bebe_total = "0";
                                            }

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            }
                                        if($adulte=="2")
                                            {


                                                    $prix_total_adulte_4 = "0";
                                                    $prix_total_adulte_3 = "0";
                                                    $prix_total_adulte=round($prix_adulte * 2 , 2);
                                                    $prix_total_adulte_promo="0";

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //

                                            $adulte_total_30 = round(($account555 -> double_adulte_1_total),2);
                                            $adulte_total_30_2 = round(($account555 -> double_adulte_2_total),2);
                                            $adulte_total_30_3 = "0";
                                            $adulte_total_30_4 = "0";


                                          if($nb_enfant=="1")
                                            {
                                                  $enfant_total = $prix_enfant_1;
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_enfant=="2")
                                            {
                                                  $enfant_total = $prix_enfant_1;
                                                  $enfant_total_2 = $prix_enfant_2;
                                            }

                                            if($nb_enfant=="0")
                                            {
                                              $enfant_total="0";
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_bebe=="1")
                                            {
                                              $bebe_total = round(($account555 -> double_bebe_1_total),2);
                                            }

                                            if($nb_bebe=="0")
                                            {
                                              $bebe_total = "0";
                                            }

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //

                                            }
                                        if($adulte=="3")
                                            {
                                                    $prix_total_adulte_4 = "0";
                                                    $prix_total_adulte=round($prix_adulte * 2, 2);
                                                    $prix_total_adulte_3 = round($prix_adulte_3, 2);
                                                    $prix_total_adulte = $prix_total_adulte + $prix_total_adulte_3;
                                                    $prix_total_adulte_promo="0";

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //

                                            $adulte_total_30 = round(($account555 -> tripple_adulte_1_total),2);
                                            $adulte_total_30_2 = round(($account555 -> tripple_adulte_2_total),2);
                                            $adulte_total_30_3 = round(($account555 -> tripple_adulte_3_total),2);
                                            $adulte_total_30_4 = "0";


                                          if($nb_enfant=="1")
                                            {
                                                  $enfant_total = $prix_enfant_1;
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_enfant=="2")
                                            {
                                                  $enfant_total = $prix_enfant_1;
                                                  $enfant_total_2 = $prix_enfant_2;
                                            }

                                            if($nb_enfant=="0")
                                            {
                                              $enfant_total="0";
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_bebe=="1")
                                            {
                                              $bebe_total = round(($account555 -> tripple_bebe_1_total),2);
                                            }

                                            if($nb_bebe=="0")
                                            {
                                              $bebe_total = "0";
                                            }

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            }
                                        if($adulte=="4")
                                            {
                                                    $prix_total_adulte=round($prix_adulte * 2 , 2);
                                                    $prix_total_adulte_3 = round($prix_adulte_3 , 2);
                                                    $prix_total_adulte_4 = round($prix_adulte_4, 2);
                                                    $prix_total_adulte = $prix_total_adulte + $prix_total_adulte_3 + $prix_total_adulte_4;
                                                    $prix_total_adulte_promo="0";

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //

                                            $adulte_total_30 = round(($account555 -> quatre_adulte_1_total),2);
                                            $adulte_total_30_2 = round(($account555 -> quatre_adulte_2_total),2);
                                            $adulte_total_30_3 = round(($account555 -> quatre_adulte_3_total),2);
                                            $adulte_total_30_4 = round(($account555 -> quatre_adulte_4_total),2);


                                          if($nb_enfant=="1")
                                            {
                                                  $enfant_total = $prix_enfant_1;
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_enfant=="2")
                                            {
                                                  $enfant_total = $prix_enfant_1;
                                                  $enfant_total_2 = $prix_enfant_2;
                                            }

                                            if($nb_enfant=="0")
                                            {
                                              $enfant_total="0";
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_bebe=="1")
                                            {
                                              $bebe_total = round(($account555 -> quatre_bebe_1_total),2);
                                            }

                                            if($nb_bebe=="0")
                                            {
                                              $bebe_total = "0";
                                            }

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            }
                                        if($nb_enfant!="0")
                                            {
                                                    $prix_total_enfant=round($prix_enfant * $nb_enfant , 2);
                                                    $prix_total_enfant_promo="0";
                                            }
                                        else
                                            {
                                                    $prix_total_enfant_promo="0";
                                                    $prix_total_enfant="0";
                                            }

                                        if($nb_bebe!="0")
                                            {
                                                    $prix_bebe=round($account555 -> bebe_1_total,2);
                                                    $prix_total_bebe=round($prix_bebe * $nb_bebe , 2);
                                                    $prix_total_bebe_promo="0";
                                            }
                                        else
                                            {
                                                    $prix_total_bebe="0";
                                                    $prix_total_bebe_promo="0";
                                            }

        }


                  }
                  else
                  {
                    // ********************** PRIX NORMAL ************************ //
                    // ********************** PRIX NORMAL ************************ //
                    // ********************** PRIX NORMAL ************************ //

                     if($adulte=="1")
                                            {
                                                    $prix_total_adulte_4 = "0";
                                                    $prix_total_adulte_3 = "0";
                                                    $prix_total_adulte=round($prix_adulte , 2);
                                                    $prix_total_adulte_promo="0";

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //

                                            $adulte_total_30 = round(($account555 -> adulte_1_total),2);
                                            $adulte_total_30_2 = "0";
                                            $adulte_total_30_3 = "0";
                                            $adulte_total_30_4 = "0";

                                          if($nb_enfant=="1")
                                            {
                                                  $enfant_total = $prix_enfant_1;
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_enfant=="2")
                                            {
                                                  $enfant_total = $prix_enfant_1;
                                                  $enfant_total_2 = $prix_enfant_2;
                                            }


                                            if($nb_enfant=="0")
                                            {
                                              $enfant_total="0";
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_bebe=="1")
                                            {
                                              $bebe_total = round(($account555 -> bebe_1_total),2);
                                            }

                                            if($nb_bebe=="0")
                                            {
                                              $bebe_total = "0";
                                            }

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            }
                                        if($adulte=="2")
                                            {


                                                    $prix_total_adulte_4 = "0";
                                                    $prix_total_adulte_3 = "0";
                                                    $prix_total_adulte=round($prix_adulte * 2 , 2);
                                                    $prix_total_adulte_promo="0";

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //

                                            $adulte_total_30 = round(($account555 -> double_adulte_1_total),2);
                                            $adulte_total_30_2 = round(($account555 -> double_adulte_2_total),2);
                                            $adulte_total_30_3 = "0";
                                            $adulte_total_30_4 = "0";


                                          if($nb_enfant=="1")
                                            {
                                                  $enfant_total = $prix_enfant_1;
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_enfant=="2")
                                            {
                                                  $enfant_total = $prix_enfant_1;
                                                  $enfant_total_2 = $prix_enfant_2;
                                            }

                                            if($nb_enfant=="0")
                                            {
                                              $enfant_total="0";
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_bebe=="1")
                                            {
                                              $bebe_total = round(($account555 -> double_bebe_1_total),2);
                                            }

                                            if($nb_bebe=="0")
                                            {
                                              $bebe_total = "0";
                                            }

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //

                                            }
                                        if($adulte=="3")
                                            {
                                                    $prix_total_adulte_4 = "0";
                                                    $prix_total_adulte=round($prix_adulte * 2, 2);
                                                    $prix_total_adulte_3 = round($prix_adulte_3, 2);
                                                    $prix_total_adulte = $prix_total_adulte + $prix_total_adulte_3;
                                                    $prix_total_adulte_promo="0";

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //

                                            $adulte_total_30 = round(($account555 -> tripple_adulte_1_total),2);
                                            $adulte_total_30_2 = round(($account555 -> tripple_adulte_2_total),2);
                                            $adulte_total_30_3 = round(($account555 -> tripple_adulte_3_total),2);
                                            $adulte_total_30_4 = "0";


                                          if($nb_enfant=="1")
                                            {
                                                  $enfant_total = $prix_enfant_1;
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_enfant=="2")
                                            {
                                                  $enfant_total = $prix_enfant_1;
                                                  $enfant_total_2 = $prix_enfant_2;
                                            }

                                            if($nb_enfant=="0")
                                            {
                                              $enfant_total="0";
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_bebe=="1")
                                            {
                                              $bebe_total = round(($account555 -> tripple_bebe_1_total),2);
                                            }

                                            if($nb_bebe=="0")
                                            {
                                              $bebe_total = "0";
                                            }

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            }
                                        if($adulte=="4")
                                            {
                                                    $prix_total_adulte=round($prix_adulte * 2 , 2);
                                                    $prix_total_adulte_3 = round($prix_adulte_3 , 2);
                                                    $prix_total_adulte_4 = round($prix_adulte_4, 2);
                                                    $prix_total_adulte = $prix_total_adulte + $prix_total_adulte_3 + $prix_total_adulte_4;
                                                    $prix_total_adulte_promo="0";

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //

                                            $adulte_total_30 = round(($account555 -> quatre_adulte_1_total),2);
                                            $adulte_total_30_2 = round(($account555 -> quatre_adulte_2_total),2);
                                            $adulte_total_30_3 = round(($account555 -> quatre_adulte_3_total),2);
                                            $adulte_total_30_4 = round(($account555 -> quatre_adulte_4_total),2);


                                          if($nb_enfant=="1")
                                            {
                                                  $enfant_total = $prix_enfant_1;
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_enfant=="2")
                                            {
                                                  $enfant_total = $prix_enfant_1;
                                                  $enfant_total_2 = $prix_enfant_2;
                                            }

                                            if($nb_enfant=="0")
                                            {
                                              $enfant_total="0";
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_bebe=="1")
                                            {
                                              $bebe_total = round(($account555 -> quatre_bebe_1_total),2);
                                            }

                                            if($nb_bebe=="0")
                                            {
                                              $bebe_total = "0";
                                            }

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            }
                                        if($nb_enfant!="0")
                                            {
                                                    $prix_total_enfant=round($prix_enfant * $nb_enfant , 2);
                                                    $prix_total_enfant_promo="0";
                                            }
                                        else
                                            {
                                                    $prix_total_enfant_promo="0";
                                                    $prix_total_enfant="0";
                                            }

                                        if($nb_bebe!="0")
                                            {
                                                    $prix_bebe=round($account555 -> bebe_1_total,2);
                                                    $prix_total_bebe=round($prix_bebe * $nb_bebe , 2);
                                                    $prix_total_bebe_promo="0";
                                            }
                                        else
                                            {
                                                    $prix_total_bebe="0";
                                                    $prix_total_bebe_promo="0";
                                            }
                  }
        }
  }
else
  {

      if($account555 -> debut_remise!='' AND $account555 -> fin_remise!='' AND $account555 -> debut_remise_voyage!='' AND $account555 -> fin_remise_voyage)
      {
        // ********************** REMISE EARLY BOOKING ************************ //
        // ********************** REMISE EARLY BOOKING ************************ //
        // ********************** REMISE EARLY BOOKING ************************ //


        $debut_remise = $account555  -> debut_remise;
        $tab_debut_remise = explode(' ', $debut_remise);
        $fin_remise = $account555  -> fin_remise;
        $tab_fin_remise=explode(" ", $fin_remise);

        if(isset($tab_debut_remise[0]))
        {
            $tab33 = explode("/", $tab_debut_remise[0]);
            $debut_validite_remise = $tab33[2].'-'.$tab33[1].'-'.$tab33[0];
        }
        $tab22 = explode("/", $tab_fin_remise[0]);
        $fin_validite_remise = $tab22[2].'-'.$tab22[1].'-'.$tab22[0];
        if($debut_validite_remise <= $date_visiteur AND $date_visiteur <=$fin_validite_remise)
        {
              $debut_remise = $account555 -> debut_remise_voyage;
              $tab_debut_remise = explode(' ', $debut_remise);
              $fin_remise = $account555 -> fin_remise_voyage;
              $tab_fin_remise=explode(" ", $fin_remise);

              if(isset($tab_debut_remise[0]))
              {
                  $tab33 = explode("/", $tab_debut_remise[0]);
                  $debut_validite_remise = $tab33[2].'-'.$tab33[1].'-'.$tab33[0];
              }

              $tab22 = explode("/", $tab_fin_remise[0]);
              $fin_validite_remise = $tab22[2].'-'.$tab22[1].'-'.$tab22[0];
              if(($debut_validite_remise <= $dd AND $da >=$fin_validite_remise) OR ($debut_validite_remise <= $da AND $da <=$fin_validite_remise) OR ($debut_validite_remise <= $da AND $da >=$fin_validite_remise))
              {



                                    if($adulte=="1")
                                        {
                                            $prix_total_adulte_4 = "0";
                                            $prix_total_adulte_3 = "0";
                                            $prix_total_adulte=round($prix_adulte , 2);
                                            $promo=$prix_total_adulte - ($prix_total_adulte * $account555 -> remise / 100);
                                            $prix_total_adulte_promo=$promo;

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //

                                            $adulte_total_30 = round(($account555 -> adulte_1_total),2) - (round(($account555 -> adulte_1_total),2) * $account555 -> remise / 100);
                                            $adulte_total_30_2 = "0";
                                            $adulte_total_30_3 = "0";
                                            $adulte_total_30_4 = "0";

                                       if($nb_enfant=="1")
                                            {
                                                  $enfant_total = $prix_enfant_1 - ($prix_enfant_1 * $account555 -> remise / 100);
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_enfant=="2")
                                            {
                                                  $enfant_total = $prix_enfant_1 - ($prix_enfant_1 * $account555 -> remise / 100);
                                                  $enfant_total_2 = $prix_enfant_2 - ($prix_enfant_2 * $account555 -> remise / 100);
                                            }

                                            if($nb_enfant=="0")
                                            {
                                              $enfant_total="0";
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_bebe=="1")
                                            {
                                              $bebe_total = round(($account555 -> bebe_1_total),2) - (round(($account555 -> bebe_1_total),2) * $account555 -> remise / 100);
                                            }

                                            if($nb_bebe=="0")
                                            {
                                              $bebe_total = "0";
                                            }

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //


                                        }
                                    if($adulte=="2")
                                        {

                                            $prix_total_adulte_4 = "0";
                                            $prix_total_adulte_3 = "0";
                                            $prix_total_adulte=round($prix_adulte * 2 , 2);
                                            $promo=$prix_total_adulte - ($prix_total_adulte * $account555 -> remise / 100);
                                            $prix_total_adulte_promo=$promo;

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //

                                            $adulte_total_30 = round(($account555 -> double_adulte_1_total),2) - (round(($account555 -> double_adulte_1_total),2) * $account555 -> remise / 100);
                                            $adulte_total_30_2 = round(($account555 -> double_adulte_2_total),2) - (round(($account555 -> double_adulte_2_total),2) * $account555 -> remise / 100);
                                            $adulte_total_30_3 = "0";
                                            $adulte_total_30_4 = "0";


                                       if($nb_enfant=="1")
                                            {
                                                  $enfant_total = $prix_enfant_1 - ($prix_enfant_1 * $account555 -> remise / 100);
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_enfant=="2")
                                            {
                                                  $enfant_total = $prix_enfant_1 - ($prix_enfant_1 * $account555 -> remise / 100);
                                                  $enfant_total_2 = $prix_enfant_2 - ($prix_enfant_2 * $account555 -> remise / 100);
                                            }


                                            if($nb_enfant=="0")
                                            {
                                              $enfant_total="0";
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_bebe=="1")
                                            {
                                              $bebe_total = round(($account555 -> double_bebe_1_total),2) - (round(($account555 -> double_bebe_1_total),2) * $account555 -> remise / 100);
                                            }

                                            if($nb_bebe=="0")
                                            {
                                              $bebe_total = "0";
                                            }

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //

                                        }
                                    if($adulte=="3")
                                        {
                                            $prix_total_adulte_4 = "0";
                                            $prix_total_adulte=round($prix_adulte * 2, 2);
                                            $prix_total_adulte_3 = round($prix_adulte_3, 2);
                                            $prix_total_adulte = $prix_total_adulte + $prix_total_adulte_3;
                                            $promo=$prix_total_adulte - ($prix_total_adulte * $account555 -> remise / 100);
                                            $prix_total_adulte_promo=$promo;

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //

                                            $adulte_total_30 = round(($account555 -> tripple_adulte_1_total),2) - (round(($account555 -> tripple_adulte_1_total),2) * $account555 -> remise / 100);
                                            $adulte_total_30_2 = round(($account555 -> tripple_adulte_2_total),2) - (round(($account555 -> tripple_adulte_2_total),2) * $account555 -> remise / 100);
                                            $adulte_total_30_3 = round(($account555 -> tripple_adulte_3_total),2) - (round(($account555 -> tripple_adulte_3_total),2) * $account555 -> remise / 100);
                                            $adulte_total_30_4 = "0";


                                       if($nb_enfant=="1")
                                            {
                                                  $enfant_total = $prix_enfant_1 - ($prix_enfant_1 * $account555 -> remise / 100);
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_enfant=="2")
                                            {
                                                  $enfant_total = $prix_enfant_1 - ($prix_enfant_1 * $account555 -> remise / 100);
                                                  $enfant_total_2 = $prix_enfant_2 - ($prix_enfant_2 * $account555 -> remise / 100);
                                            }


                                            if($nb_enfant=="0")
                                            {
                                              $enfant_total="0";
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_bebe=="1")
                                            {
                                              $bebe_total = round(($account555 -> tripple_bebe_1_total),2) - (round(($account555 -> tripple_bebe_1_total),2) * $account555 -> remise / 100);
                                            }

                                            if($nb_bebe=="0")
                                            {
                                              $bebe_total = "0";
                                            }

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //

                                        }
                                    if($adulte=="4")
                                        {
                                            $prix_total_adulte=round($prix_adulte * 2 , 2);
                                            $prix_total_adulte_3 = round($prix_adulte_3 , 2);
                                            $prix_total_adulte_4 = round($prix_adulte_4, 2);
                                            $prix_total_adulte = $prix_total_adulte + $prix_total_adulte_3 + $prix_total_adulte_4;
                                            $promo=$prix_total_adulte - ($prix_total_adulte * $account555 -> remise / 100);
                                            $prix_total_adulte_promo=$promo;

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //

                                            $adulte_total_30 = round(($account555 -> quatre_adulte_1_total),2) - (round(($account555 -> quatre_adulte_1_total),2) * $account555 -> remise / 100);
                                            $adulte_total_30_2 = round(($account555 -> quatre_adulte_2_total),2) - (round(($account555 -> quatre_adulte_2_total),2) * $account555 -> remise / 100);
                                            $adulte_total_30_3 = round(($account555 -> quatre_adulte_3_total),2) - (round(($account555 -> quatre_adulte_3_total),2) * $account555 -> remise / 100);
                                            $adulte_total_30_4 = round(($account555 -> quatre_adulte_4_total),2) - (round(($account555 -> quatre_adulte_4_total),2) * $account555 -> remise / 100);

                                       if($nb_enfant=="1")
                                            {
                                                  $enfant_total = $prix_enfant_1 - ($prix_enfant_1 * $account555 -> remise / 100);
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_enfant=="2")
                                            {
                                                  $enfant_total = $prix_enfant_1 - ($prix_enfant_1 * $account555 -> remise / 100);
                                                  $enfant_total_2 = $prix_enfant_2 - ($prix_enfant_2 * $account555 -> remise / 100);
                                            }


                                            if($nb_enfant=="0")
                                            {
                                              $enfant_total="0";
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_bebe=="1")
                                            {
                                              $bebe_total = round(($account555 -> quatre_bebe_1_total),2) - (round(($account555 -> quatre_bebe_1_total),2) * $account555 -> remise / 100);
                                            }

                                            if($nb_bebe=="0")
                                            {
                                              $bebe_total = "0";
                                            }

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //

                                        }
                                    if($nb_enfant!="0")
                                        {
                                            $prix_total_enfant=round($prix_enfant * $nb_enfant , 2);
                                            $promo=$prix_total_enfant - ($prix_total_enfant * $account555 -> remise / 100);
                                            $prix_total_enfant_promo=$promo;
                                        }
                                    else
                                        {
                                            $prix_total_enfant_promo="0";
                                            $prix_total_enfant="0";
                                        }
                                    if($nb_bebe!="0")
                                        {
                                            $prix_bebe=round($account555 -> bebe_1_total,2);
                                            $prix_total_bebe=round($prix_bebe * $nb_bebe , 2);
                                            $promo=$prix_total_bebe - ($prix_total_bebe * $account555 -> remise / 100);
                                            $prix_total_bebe_promo=$promo;
                                        }
                                    else
                                        {
                                            $prix_total_bebe="0";
                                            $prix_total_bebe_promo="0";
                                        }

              }
               else
        {
                               if($adulte=="1")
                                            {
                                                    $prix_total_adulte_4 = "0";
                                                    $prix_total_adulte_3 = "0";
                                                    $prix_total_adulte=round($prix_adulte , 2);
                                                    $prix_total_adulte_promo="0";

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //

                                            $adulte_total_30 = round(($account555 -> adulte_1_total),2);
                                            $adulte_total_30_2 = "0";
                                            $adulte_total_30_3 = "0";
                                            $adulte_total_30_4 = "0";

                                          if($nb_enfant=="1")
                                            {
                                                  $enfant_total = $prix_enfant_1;
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_enfant=="2")
                                            {
                                                  $enfant_total = $prix_enfant_1;
                                                  $enfant_total_2 = $prix_enfant_2;
                                            }


                                            if($nb_enfant=="0")
                                            {
                                              $enfant_total="0";
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_bebe=="1")
                                            {
                                              $bebe_total = round(($account555 -> bebe_1_total),2);
                                            }

                                            if($nb_bebe=="0")
                                            {
                                              $bebe_total = "0";
                                            }

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            }
                                        if($adulte=="2")
                                            {


                                                    $prix_total_adulte_4 = "0";
                                                    $prix_total_adulte_3 = "0";
                                                    $prix_total_adulte=round($prix_adulte * 2 , 2);
                                                    $prix_total_adulte_promo="0";

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //

                                            $adulte_total_30 = round(($account555 -> double_adulte_1_total),2);
                                            $adulte_total_30_2 = round(($account555 -> double_adulte_2_total),2);
                                            $adulte_total_30_3 = "0";
                                            $adulte_total_30_4 = "0";


                                          if($nb_enfant=="1")
                                            {
                                                  $enfant_total = $prix_enfant_1;
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_enfant=="2")
                                            {
                                                  $enfant_total = $prix_enfant_1;
                                                  $enfant_total_2 = $prix_enfant_2;
                                            }

                                            if($nb_enfant=="0")
                                            {
                                              $enfant_total="0";
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_bebe=="1")
                                            {
                                              $bebe_total = round(($account555 -> double_bebe_1_total),2);
                                            }

                                            if($nb_bebe=="0")
                                            {
                                              $bebe_total = "0";
                                            }

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //

                                            }
                                        if($adulte=="3")
                                            {
                                                    $prix_total_adulte_4 = "0";
                                                    $prix_total_adulte=round($prix_adulte * 2, 2);
                                                    $prix_total_adulte_3 = round($prix_adulte_3, 2);
                                                    $prix_total_adulte = $prix_total_adulte + $prix_total_adulte_3;
                                                    $prix_total_adulte_promo="0";

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //

                                            $adulte_total_30 = round(($account555 -> tripple_adulte_1_total),2);
                                            $adulte_total_30_2 = round(($account555 -> tripple_adulte_2_total),2);
                                            $adulte_total_30_3 = round(($account555 -> tripple_adulte_3_total),2);
                                            $adulte_total_30_4 = "0";


                                          if($nb_enfant=="1")
                                            {
                                                  $enfant_total = $prix_enfant_1;
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_enfant=="2")
                                            {
                                                  $enfant_total = $prix_enfant_1;
                                                  $enfant_total_2 = $prix_enfant_2;
                                            }

                                            if($nb_enfant=="0")
                                            {
                                              $enfant_total="0";
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_bebe=="1")
                                            {
                                              $bebe_total = round(($account555 -> tripple_bebe_1_total),2);
                                            }

                                            if($nb_bebe=="0")
                                            {
                                              $bebe_total = "0";
                                            }

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            }
                                        if($adulte=="4")
                                            {
                                                    $prix_total_adulte=round($prix_adulte * 2 , 2);
                                                    $prix_total_adulte_3 = round($prix_adulte_3 , 2);
                                                    $prix_total_adulte_4 = round($prix_adulte_4, 2);
                                                    $prix_total_adulte = $prix_total_adulte + $prix_total_adulte_3 + $prix_total_adulte_4;
                                                    $prix_total_adulte_promo="0";

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //

                                            $adulte_total_30 = round(($account555 -> quatre_adulte_1_total),2);
                                            $adulte_total_30_2 = round(($account555 -> quatre_adulte_2_total),2);
                                            $adulte_total_30_3 = round(($account555 -> quatre_adulte_3_total),2);
                                            $adulte_total_30_4 = round(($account555 -> quatre_adulte_4_total),2);


                                          if($nb_enfant=="1")
                                            {
                                                  $enfant_total = $prix_enfant_1;
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_enfant=="2")
                                            {
                                                  $enfant_total = $prix_enfant_1;
                                                  $enfant_total_2 = $prix_enfant_2;
                                            }

                                            if($nb_enfant=="0")
                                            {
                                              $enfant_total="0";
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_bebe=="1")
                                            {
                                              $bebe_total = round(($account555 -> quatre_bebe_1_total),2);
                                            }

                                            if($nb_bebe=="0")
                                            {
                                              $bebe_total = "0";
                                            }

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            }
                                        if($nb_enfant!="0")
                                            {
                                                    $prix_total_enfant=round($prix_enfant * $nb_enfant , 2);
                                                    $prix_total_enfant_promo="0";
                                            }
                                        else
                                            {
                                                    $prix_total_enfant_promo="0";
                                                    $prix_total_enfant="0";
                                            }

                                        if($nb_bebe!="0")
                                            {
                                                    $prix_bebe=round($account555 -> bebe_1_total,2);
                                                    $prix_total_bebe=round($prix_bebe * $nb_bebe , 2);
                                                    $prix_total_bebe_promo="0";
                                            }
                                        else
                                            {
                                                    $prix_total_bebe="0";
                                                    $prix_total_bebe_promo="0";
                                            }

        }
        }
        else
        {
                               if($adulte=="1")
                                            {
                                                    $prix_total_adulte_4 = "0";
                                                    $prix_total_adulte_3 = "0";
                                                    $prix_total_adulte=round($prix_adulte , 2);
                                                    $prix_total_adulte_promo="0";

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //

                                            $adulte_total_30 = round(($account555 -> adulte_1_total),2);
                                            $adulte_total_30_2 = "0";
                                            $adulte_total_30_3 = "0";
                                            $adulte_total_30_4 = "0";

                                          if($nb_enfant=="1")
                                            {
                                                  $enfant_total = $prix_enfant_1;
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_enfant=="2")
                                            {
                                                  $enfant_total = $prix_enfant_1;
                                                  $enfant_total_2 = $prix_enfant_2;
                                            }


                                            if($nb_enfant=="0")
                                            {
                                              $enfant_total="0";
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_bebe=="1")
                                            {
                                              $bebe_total = round(($account555 -> bebe_1_total),2);
                                            }

                                            if($nb_bebe=="0")
                                            {
                                              $bebe_total = "0";
                                            }

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            }
                                        if($adulte=="2")
                                            {


                                                    $prix_total_adulte_4 = "0";
                                                    $prix_total_adulte_3 = "0";
                                                    $prix_total_adulte=round($prix_adulte * 2 , 2);
                                                    $prix_total_adulte_promo="0";

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //

                                            $adulte_total_30 = round(($account555 -> double_adulte_1_total),2);
                                            $adulte_total_30_2 = round(($account555 -> double_adulte_2_total),2);
                                            $adulte_total_30_3 = "0";
                                            $adulte_total_30_4 = "0";


                                          if($nb_enfant=="1")
                                            {
                                                  $enfant_total = $prix_enfant_1;
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_enfant=="2")
                                            {
                                                  $enfant_total = $prix_enfant_1;
                                                  $enfant_total_2 = $prix_enfant_2;
                                            }

                                            if($nb_enfant=="0")
                                            {
                                              $enfant_total="0";
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_bebe=="1")
                                            {
                                              $bebe_total = round(($account555 -> double_bebe_1_total),2);
                                            }

                                            if($nb_bebe=="0")
                                            {
                                              $bebe_total = "0";
                                            }

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //

                                            }
                                        if($adulte=="3")
                                            {
                                                    $prix_total_adulte_4 = "0";
                                                    $prix_total_adulte=round($prix_adulte * 2, 2);
                                                    $prix_total_adulte_3 = round($prix_adulte_3, 2);
                                                    $prix_total_adulte = $prix_total_adulte + $prix_total_adulte_3;
                                                    $prix_total_adulte_promo="0";

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //

                                            $adulte_total_30 = round(($account555 -> tripple_adulte_1_total),2);
                                            $adulte_total_30_2 = round(($account555 -> tripple_adulte_2_total),2);
                                            $adulte_total_30_3 = round(($account555 -> tripple_adulte_3_total),2);
                                            $adulte_total_30_4 = "0";


                                          if($nb_enfant=="1")
                                            {
                                                  $enfant_total = $prix_enfant_1;
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_enfant=="2")
                                            {
                                                  $enfant_total = $prix_enfant_1;
                                                  $enfant_total_2 = $prix_enfant_2;
                                            }

                                            if($nb_enfant=="0")
                                            {
                                              $enfant_total="0";
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_bebe=="1")
                                            {
                                              $bebe_total = round(($account555 -> tripple_bebe_1_total),2);
                                            }

                                            if($nb_bebe=="0")
                                            {
                                              $bebe_total = "0";
                                            }

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            }
                                        if($adulte=="4")
                                            {
                                                    $prix_total_adulte=round($prix_adulte * 2 , 2);
                                                    $prix_total_adulte_3 = round($prix_adulte_3 , 2);
                                                    $prix_total_adulte_4 = round($prix_adulte_4, 2);
                                                    $prix_total_adulte = $prix_total_adulte + $prix_total_adulte_3 + $prix_total_adulte_4;
                                                    $prix_total_adulte_promo="0";

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //

                                            $adulte_total_30 = round(($account555 -> quatre_adulte_1_total),2);
                                            $adulte_total_30_2 = round(($account555 -> quatre_adulte_2_total),2);
                                            $adulte_total_30_3 = round(($account555 -> quatre_adulte_3_total),2);
                                            $adulte_total_30_4 = round(($account555 -> quatre_adulte_4_total),2);


                                          if($nb_enfant=="1")
                                            {
                                                  $enfant_total = $prix_enfant_1;
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_enfant=="2")
                                            {
                                                  $enfant_total = $prix_enfant_1;
                                                  $enfant_total_2 = $prix_enfant_2;
                                            }

                                            if($nb_enfant=="0")
                                            {
                                              $enfant_total="0";
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_bebe=="1")
                                            {
                                              $bebe_total = round(($account555 -> quatre_bebe_1_total),2);
                                            }

                                            if($nb_bebe=="0")
                                            {
                                              $bebe_total = "0";
                                            }

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            }
                                        if($nb_enfant!="0")
                                            {
                                                    $prix_total_enfant=round($prix_enfant * $nb_enfant , 2);
                                                    $prix_total_enfant_promo="0";
                                            }
                                        else
                                            {
                                                    $prix_total_enfant_promo="0";
                                                    $prix_total_enfant="0";
                                            }

                                        if($nb_bebe!="0")
                                            {
                                                    $prix_bebe=round($account555 -> bebe_1_total,2);
                                                    $prix_total_bebe=round($prix_bebe * $nb_bebe , 2);
                                                    $prix_total_bebe_promo="0";
                                            }
                                        else
                                            {
                                                    $prix_total_bebe="0";
                                                    $prix_total_bebe_promo="0";
                                            }

        }


      }
      else
      {
        // ********************** PRIX NORMAL ************************ //
        // ********************** PRIX NORMAL ************************ //
        // ********************** PRIX NORMAL ************************ //
         if($adulte=="1")
                                            {
                                                    $prix_total_adulte_4 = "0";
                                                    $prix_total_adulte_3 = "0";
                                                    $prix_total_adulte=round($prix_adulte , 2);
                                                    $prix_total_adulte_promo="0";

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //

                                            $adulte_total_30 = round(($account555 -> adulte_1_total),2);
                                            $adulte_total_30_2 = "0";
                                            $adulte_total_30_3 = "0";
                                            $adulte_total_30_4 = "0";

                                          if($nb_enfant=="1")
                                            {
                                                  $enfant_total = $prix_enfant_1;
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_enfant=="2")
                                            {
                                                  $enfant_total = $prix_enfant_1;
                                                  $enfant_total_2 = $prix_enfant_2;
                                            }


                                            if($nb_enfant=="0")
                                            {
                                              $enfant_total="0";
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_bebe=="1")
                                            {
                                              $bebe_total = round(($account555 -> bebe_1_total),2);
                                            }

                                            if($nb_bebe=="0")
                                            {
                                              $bebe_total = "0";
                                            }

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            }
                                        if($adulte=="2")
                                            {


                                                    $prix_total_adulte_4 = "0";
                                                    $prix_total_adulte_3 = "0";
                                                    $prix_total_adulte=round($prix_adulte * 2 , 2);
                                                    $prix_total_adulte_promo="0";

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //

                                            $adulte_total_30 = round(($account555 -> double_adulte_1_total),2);
                                            $adulte_total_30_2 = round(($account555 -> double_adulte_2_total),2);
                                            $adulte_total_30_3 = "0";
                                            $adulte_total_30_4 = "0";


                                          if($nb_enfant=="1")
                                            {
                                                  $enfant_total = $prix_enfant_1;
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_enfant=="2")
                                            {
                                                  $enfant_total = $prix_enfant_1;
                                                  $enfant_total_2 = $prix_enfant_2;
                                            }

                                            if($nb_enfant=="0")
                                            {
                                              $enfant_total="0";
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_bebe=="1")
                                            {
                                              $bebe_total = round(($account555 -> double_bebe_1_total),2);
                                            }

                                            if($nb_bebe=="0")
                                            {
                                              $bebe_total = "0";
                                            }

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //

                                            }
                                        if($adulte=="3")
                                            {
                                                    $prix_total_adulte_4 = "0";
                                                    $prix_total_adulte=round($prix_adulte * 2, 2);
                                                    $prix_total_adulte_3 = round($prix_adulte_3, 2);
                                                    $prix_total_adulte = $prix_total_adulte + $prix_total_adulte_3;
                                                    $prix_total_adulte_promo="0";

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //

                                            $adulte_total_30 = round(($account555 -> tripple_adulte_1_total),2);
                                            $adulte_total_30_2 = round(($account555 -> tripple_adulte_2_total),2);
                                            $adulte_total_30_3 = round(($account555 -> tripple_adulte_3_total),2);
                                            $adulte_total_30_4 = "0";


                                          if($nb_enfant=="1")
                                            {
                                                  $enfant_total = $prix_enfant_1;
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_enfant=="2")
                                            {
                                                  $enfant_total = $prix_enfant_1;
                                                  $enfant_total_2 = $prix_enfant_2;
                                            }

                                            if($nb_enfant=="0")
                                            {
                                              $enfant_total="0";
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_bebe=="1")
                                            {
                                              $bebe_total = round(($account555 -> tripple_bebe_1_total),2);
                                            }

                                            if($nb_bebe=="0")
                                            {
                                              $bebe_total = "0";
                                            }

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            }
                                        if($adulte=="4")
                                            {
                                                    $prix_total_adulte=round($prix_adulte * 2 , 2);
                                                    $prix_total_adulte_3 = round($prix_adulte_3 , 2);
                                                    $prix_total_adulte_4 = round($prix_adulte_4, 2);
                                                    $prix_total_adulte = $prix_total_adulte + $prix_total_adulte_3 + $prix_total_adulte_4;
                                                    $prix_total_adulte_promo="0";

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //

                                            $adulte_total_30 = round(($account555 -> quatre_adulte_1_total),2);
                                            $adulte_total_30_2 = round(($account555 -> quatre_adulte_2_total),2);
                                            $adulte_total_30_3 = round(($account555 -> quatre_adulte_3_total),2);
                                            $adulte_total_30_4 = round(($account555 -> quatre_adulte_4_total),2);


                                          if($nb_enfant=="1")
                                            {
                                                  $enfant_total = $prix_enfant_1;
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_enfant=="2")
                                            {
                                                  $enfant_total = $prix_enfant_1;
                                                  $enfant_total_2 = $prix_enfant_2;
                                            }

                                            if($nb_enfant=="0")
                                            {
                                              $enfant_total="0";
                                              $enfant_total_2 ="0";
                                            }

                                            if($nb_bebe=="1")
                                            {
                                              $bebe_total = round(($account555 -> quatre_bebe_1_total),2);
                                            }

                                            if($nb_bebe=="0")
                                            {
                                              $bebe_total = "0";
                                            }

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            }
                                        if($nb_enfant!="0")
                                            {
                                                    $prix_total_enfant=round($prix_enfant * $nb_enfant , 2);
                                                    $prix_total_enfant_promo="0";
                                            }
                                        else
                                            {
                                                    $prix_total_enfant_promo="0";
                                                    $prix_total_enfant="0";
                                            }

                                        if($nb_bebe!="0")
                                            {
                                                    $prix_bebe=round($account555 -> bebe_1_total,2);
                                                    $prix_total_bebe=round($prix_bebe * $nb_bebe , 2);
                                                    $prix_total_bebe_promo="0";
                                            }
                                        else
                                            {
                                                    $prix_total_bebe="0";
                                                    $prix_total_bebe_promo="0";
                                            }
      }


  }



$adulte_total_11=$adulte_total_30;
$adulte_total_12=$adulte_total_30_2;
$adulte_total_13=$adulte_total_30_3;
$adulte_total_14=$adulte_total_30_4;
$enfant_total_11=$enfant_total;
$enfant_total_12=$enfant_total_2;
$bebe_total_11=$bebe_total;


$prix_total_3 = $prix_total_adulte + $prix_total_enfant +$prix_total_bebe;


$prix_total_promo_3 = $prix_total_adulte_promo + $prix_total_enfant_promo + $prix_total_bebe_promo;
// $id_hotel.' - '.$account555 -> id_chambre.' - '.$prix_total_3.'<br>';


?>