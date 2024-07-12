<?php
    if($adulte=="1")
                                            {
                                                    $prix_total_adulte_4 = "0";
                                                    $prix_total_adulte_3 = "0";
                                                    $prix_total_adulte=round($prix_adulte , 2);
                                                    $prix_total_adulte_promo="0";

                                            // ************ POUR LE BLOC ROUGE ******************* //
                                            // ************ POUR LE BLOC ROUGE ******************* //

                                            $adulte_total = round(($account55 -> adulte_1_total),2);
                                            $adulte_total_2 = "0";
                                            $adulte_total_3 = "0";
                                            $adulte_total_4 = "0";  

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
                                              $bebe_total = round(($account55 -> bebe_1_total),2);
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

                                            $adulte_total = round(($account55 -> double_adulte_1_total),2);
                                            $adulte_total_2 = round(($account55 -> double_adulte_2_total),2);
                                            $adulte_total_3 = "0";
                                            $adulte_total_4 = "0";  


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
                                              $bebe_total = round(($account55 -> double_bebe_1_total),2);
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

                                            $adulte_total = round(($account55 -> tripple_adulte_1_total),2);
                                            $adulte_total_2 = round(($account55 -> tripple_adulte_2_total),2);
                                            $adulte_total_3 = round(($account55 -> tripple_adulte_3_total),2);
                                            $adulte_total_4 = "0";  


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
                                              $bebe_total = round(($account55 -> tripple_bebe_1_total),2);
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

                                            $adulte_total = round(($account55 -> quatre_adulte_1_total),2);
                                            $adulte_total_2 = round(($account55 -> quatre_adulte_2_total),2);
                                            $adulte_total_3 = round(($account55 -> quatre_adulte_3_total),2);
                                            $adulte_total_4 = round(($account55 -> quatre_adulte_4_total),2);


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
                                              $bebe_total = round(($account55 -> quatre_bebe_1_total),2);
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
                                                    $prix_bebe=round($account55 -> bebe_1_total,2);
                                                    $prix_total_bebe=round($prix_bebe * $nb_bebe , 2);                                    
                                                    $prix_total_bebe_promo="0";
                                            }
                                        else
                                            {
                                                    $prix_total_bebe="0";
                                                    $prix_total_bebe_promo="0";
                                            } 

?>