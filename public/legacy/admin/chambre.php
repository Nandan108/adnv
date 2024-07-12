<?php
use App\Models\Chambre;
use App\Utils\URL;

// vérification login, connection à la base de donnée, démarrage du output buffering
require 'admin_init.php';

?>
<style type="text/css">
    .span2 {
        margin: 1px;
    }

    .prix input[type=number] {
        text-align: center;
    }

    h3>a {
        color: white
    }

    div.form-box {
        padding: 10px;
        margin-left: 20px;
        border: 3px solid;
        margin-bottom: 30px;
        height: 200px;
    }

    div.form-box>h4 {
        text-align: center;
        margin-bottom: 18px;
        background: #6b8c2d;
        padding: 5px;
        color: #FFF;
        margin-top: 0px;
        margin-bottom: 30px;
    }

    div.form-box div.controls {
        margin-left: 0;
    }

    div.form-box .field-clear {
        font-size: 150%;
        cursor: pointer;
        user-select: none;
    }
</style>

<?php

$id_hotel = (int) ($_GET['id'] ?? $_GET['dossier'] ?? null);
$id_chambre = (int) ($_POST['id_chambre'] ?? $_GET['id_chambre'] ?? null);

$champs_chambre = [
    'nom_chambre',
    'id_hotel',
    'surclassement',
    'debut_validite',
    'fin_validite',
    'taux_change',
    'taux_commission',
    'club',
    'photo_chambre',
    'monnaie',
    // champ pour le tarif chambre
    'simple_nb_max',
    'simple_adulte_max',
    'simple_enfant_max',
    'simple_bebe_max',
    'a',
    'b',
    'adulte_1_net',
    'adulte_1_brut',
    'adulte_1_total',
    'de_1_enfant',
    'a_1_enfant',
    'enfant_1_net',
    'enfant_1_brut',
    'enfant_1_total',
    'de_2_enfant',
    'a_2_enfant',
    'enfant_2_net',
    'enfant_2_brut',
    'enfant_2_total',
    'de_3_enfant',
    'a_3_enfant',
    'enfant_3_net',
    'enfant_3_brut',
    'enfant_3_total',
    'bebe_1',
    'bebe_1_net',
    'bebe_1_brut',
    'bebe_1_total',
    'double_nb_max',
    'double_adulte_max',
    'double_enfant_max',
    'double_bebe_max',
    'double_adulte_1_net',
    'double_adulte_1_brut',
    'double_adulte_1_total',
    'double_adulte_2_net',
    'double_adulte_2_brut',
    'double_adulte_2_total',
    'double_de_1_enfant',
    'double_a_1_enfant',
    'double_enfant_1_net',
    'double_enfant_1_brut',
    'double_enfant_1_total',
    'double_de_2_enfant',
    'double_a_2_enfant',
    'double_enfant_2_net',
    'double_enfant_2_brut',
    'double_enfant_2_total',
    'double_de_3_enfant',
    'double_a_3_enfant',
    'double_enfant_3_net',
    'double_enfant_3_brut',
    'double_enfant_3_total',
    'double_bebe_1',
    'double_bebe_1_net',
    'double_bebe_1_brut',
    'double_bebe_1_total',
    'tripple_nb_max',
    'tripple_adulte_max',
    'tripple_adulte_1_net',
    'tripple_adulte_1_brut',
    'tripple_adulte_1_total',
    'tripple_adulte_2_net',
    'tripple_adulte_2_brut',
    'tripple_adulte_2_total',
    'tripple_adulte_3_net',
    'tripple_adulte_3_brut',
    'tripple_adulte_3_total',
    'quatre_nb_max',
    'quatre_adulte_max',
    'quatre_adulte_1_net',
    'quatre_adulte_1_brut',
    'quatre_adulte_1_total',
    'quatre_adulte_2_net',
    'quatre_adulte_2_brut',
    'quatre_adulte_2_total',
    'quatre_adulte_3_net',
    'quatre_adulte_3_brut',
    'quatre_adulte_3_total',
    'quatre_adulte_4_net',
    'quatre_adulte_4_brut',
    'quatre_adulte_4_total',
    // champ si chambre est villa
    'villa_nb_max',
    'villa_adulte_max',
    'villa_adulte_1_net',
    'villa_adulte_1_brut',
    'villa_adulte_1_total',
    // champ information remise
    'code_promo',
    'unite',
    'remise',
    'debut_remise',
    'fin_remise',
    'debut_remise_voyage',
    'fin_remise_voyage',
    'code_promo2',
    'unite2',
    'remise2',
    'debut_remise2',
    'fin_remise2',
    'debut_remise2_voyage',
    'fin_remise2_voyage',
    'status_remise',
];


if (isset($_POST['save'])) {
    // prepare image name
    $code_aleatoire = strtr(base64_encode(random_bytes(15)), '+/', '-_');
    $date = date("dmY");
    $nom_image = $code_aleatoire . "_" . $date . ".png";

    if ($_FILES["file"]["error"] > 0) {
        $er = "ERROR Return Code: " . $_FILES["file"]["error"] . "<br />";
        $url_photo = $_POST['photo_chambre'] ?? $url_photo = "upload/imagedeloffrenondisponible.jpg";
    } else {
        // create upload directory if necessary
        if (!file_exists("upload"))
            mkdir("upload");
        move_uploaded_file($_FILES["file"]["tmp_name"], $url_photo = "upload/$nom_image");
    }

    // ici on récupère toutes les valeurs de $_POST correspondant au champs dans la liste ci-dessus
    $valeurs_a_enregistrer = array_intersect_key($_POST, array_flip($champs_chambre));
    $valeurs_a_enregistrer['photo_chambre'] = $url_photo;
    // la valeur de $_POST['monnaie'] devrait être qqch comme "EUR:1.05".
    if (preg_match('/^[A-Z]{3}:[\d.]+$/', $_POST['monnaie'])) {
        [$valeurs_a_enregistrer['monnaie'], $valeurs_a_enregistrer['taux_change']] = explode(':', $_POST['monnaie']);
    }

    if ($id_chambre) {
        // génère une chaine: "<champ1> := <value1>, <champ2> := <value2>, <champ3> := <value3>, etc..."
        $SET = implode(",\n", array_map(fn($f) => "$f = :$f", array_keys($valeurs_a_enregistrer)));

        $update_stmt = $conn->prepare($sql =
            "UPDATE chambre
            SET $SET
            WHERE id_chambre = $id_chambre
        ");
        // en cas d'erreur lors de la création du statement, on quite avec message d'erreur
        if (!$update_stmt) {
            die(debug_dump(['nPDO::errorInfo()' => $conn->errorInfo(), 'SQL' => $sql]));
        }

        // Et finalement on execute l'update sur ces valeurs
        $update_stmt->execute($valeurs_a_enregistrer);
    } else {
        // Pour la création d'un nouveau chambre
        $champs_cibles = implode(",\n", array_keys($valeurs_a_enregistrer));
        $valeurs = implode(",\n", array_map(fn($f) => ":$f", array_keys($valeurs_a_enregistrer)));
        // préparation...
        $insert_stmt = $conn->prepare($sql = "INSERT INTO chambre (\n$champs_cibles\n) VALUES (\n$valeurs\n)");
        // exécution!
        $result = $insert_stmt->execute($valeurs_a_enregistrer);
        // $id =
        // die(dd($sql));
    }

    $chambre_list_url = URL::get('chambres.php')->setParams([
        'dossier' => $_POST['id_hotel'],
        'nom_chambre' => $_POST['nom_chambre'],
    ]);

    ?>
    <script>
        alert('La mise à jour du chambre a été effectuée avec succès !');
        // redirect to the list of transferts
        window.location = '<?=$chambre_list_url?>';
    </script>
    <?php
    die();
} // end if (isset($_POST['save']))

// récupère les données du chambre
if ($id_chambre) {
    $chambre = dbGetOneObj('SELECT * FROM chambre WHERE id_chambre = ?', [$id_chambre]);
    if (!$chambre)
        erreur_404('Désolé, cet chambre n\'existe pas');
    $id_hotel = $chambre->id_hotel;
} else {
    // Pas d'$id_chambre ? Alors on est sur une page de création d'une nouvelle chambre.
    // donc on va créer un objet vide...
    $chambre = (object) array_fill_keys($champs_chambre, null);
    $chambre = new Chambre(['id_hotel' => $id_hotel]);
}

$hotel = dbGetOneObj(
    'SELECT h.*, l.id_lieu, l.pays, l.ville
    FROM hotels_new  h
        JOIN lieux l ON h.id_lieu = l.id_lieu
    WHERE id = ?',
    [$id_hotel]
);


if (!$hotel)
    erreur_404('Désolé, cet hotel n\'existe pas');

?>

<section class="nav-page" style="display: block;">
    <div class="container">
        <div class="row">
            <div class="span7">
                <header class="page-header">
                    <h3><a href='chambres.php?dossier=<?=$hotel->id?>'><?=$hotel->nom?></a> | <span
                            style="font-size: 12px;color:#00CCF4;">Modification de chambre</span></h3>
                </header>
            </div>
            <div class="span9">
                <ul class="nav nav-pills">
                    <li>
                        <a href="hotels.php?pays=<?=$hotel->pays?>&ville=<?=$hotel->ville?>" rel="tooltip"
                            data-placement="left" title="Liste des hôtels">
                            <i class="icon-chevron-left pull-left"></i> Voir la liste des hôtels
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section id="my-account-security-form" class="page container">
    <form id="userSecurityForm" class="form-horizontal" method="post" action="" enctype="multipart/form-data">
        <input type="hidden" name="photo_chambre" value="<?=$chambre->photo_chambre?>">
        <input type="hidden" name="id_chambre" value="<?=$chambre->id_chambre?>">
        <input type="hidden" name="id_hotel" value="<?=$id_hotel?>">
        <input type="hidden" name="redir" value="<?=htmlentities($_GET['redir'] ?? '')?>">

        <div class="container">
            <div class="alert alert-block alert-info">
                <p>
                    Pour la modification d' une chambre, veuillez bien verifier que tous les étapes sont bien remplir
                </p>
            </div>
            <div class="row">
                <div id="acct-password-row" class="span7">
                    <div class='form-box' style="height: 230px;overflow: hidden;">
                        <h4>CATEGORIE DE CHAMBRE</h4>
                        <div class="control-group ">
                            <label class="control-label">Ajouter photo</label>
                            <div class="controls">
                                <input type="file" name="file" />
                            </div>
                        </div>
                        <div class="control-group ">
                            <label class="control-label">Type de chambre</label>
                            <div class="controls">
                                <input name="nom_chambre" class="span4" type="text" value="<?=$chambre->nom_chambre?>"
                                    autocomplete="false" required>
                            </div>
                        </div>
                        <div class="control-group ">
                            <label class="control-label">Club</label>
                            <div class="controls">
                                <input name="club" class="span4" type="text" value="<?=$chambre->club?>"
                                    autocomplete="false">
                            </div>
                        </div>
                        <input type="hidden" name="surclassement" value="0">
                    </div>
                </div>

                <div id="acct-password-row" class="span4">
                    <div class='form-box' style="height: 230px;overflow: hidden;">
                        <h4>VALIDITE</h4>
                        <div class="control-group ">
                            <label class="control-label" style="width: 60px;">Début</label>
                            <div class="controls" style="margin-left: 100px;">
                                <input type="date" name="debut_validite" class="span2"
                                    value="<?=$chambre->debut_validite?>" autocomplete="false" required>
                            </div>
                        </div>
                        <div class="control-group ">
                            <label class="control-label" style="width: 60px;">Fin</label>
                            <div class="controls" style="margin-left: 100px;">
                                <input type="date" name="fin_validite" class="span2" value="<?=$chambre->fin_validite?>"
                                    autocomplete="false" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="acct-password-row" class="span5">
                    <div class='form-box' style="height: 230px;overflow: hidden;">
                        <h4>TAUX & COMMISSION</h4>
                        <div class="control-group ">
                            <label class="control-label" style="width: 60px;">Taux</label>
                            <div class="controls" style="margin-left: 100px;">
                                <select class="span3" name="monnaie" required>
                                    <option value="" disabled='disabled'>-- choisisez la monnaie --</option>
                                    <?php
                                    // chargement des données de référence (lookup data)
                                    $taux_monnaies = array_ObjByKey(dbGetAllObj('SELECT * FROM taux_monnaie'), 'code');
                                    echo printSelectOptions(
                                        source: $taux_monnaies,
                                        valueSource: fn($tm) => "$tm->code:$tm->taux",
                                        displaySource: fn($tm) => "$tm->nom_monnaie : $tm->code - $tm->taux",
                                        selectedVal: $chambre->monnaie . ':' . $taux_monnaies[$chambre->monnaie]->taux,
                                    );
                                    ?>
                                </select>
                            </div>
                        </div>
                        <input type="hidden" class="form-control" name="" id="taux"
                            value="<?=$chambre->taux_change?>">
                        <div class="control-group ">
                            <label class="control-label" style="width: 60px;">Commission</label>
                            <div class="controls" style="margin-left: 100px;">
                                <input type="number" class="span3" name="taux_commission"
                                    value="<?=$chambre->taux_commission?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- /////////////////////  CONFIGURATION TARIF //////////////////////// -->

            <div class="row">
                <div id="form_tarif_1" style="margin: auto;background:#FFF;padding: 20px;float: none;"
                    class="span16 prix">
                    <fieldset>
                        <legend>Etape 1 : Configuration Tarif</legend>
                    </fieldset>
                    <fieldset style="background:#F7F7F7;padding: 25px 0px;border-radius: 2px;">
                        <div class="span15">
                            <?php
                            // Création de tableau de type de chambre simple|double|tripple|quadriple|villa
                            $typesChambres = [
                                'simple' => (object) [
                                    'titre' => 'Chambre Individuelle',
                                    'max' => [
                                        ['lbl' => 'Pers. max.', 'field' => '_nb_max'],
                                        ['lbl' => 'Adulte', 'field' => '_adulte_max'],
                                        ['lbl' => 'Enfant', 'field' => '_enfant_max'],
                                        ['lbl' => 'Bébé', 'field' => '_bebe_max'],
                                    ],
                                    'occupants' => [
                                        ['lbl' => '1er adulte', 'prix' => 'adulte_1', 'prix_split' => ['a', 'b']],
                                        ['lbl' => '1er enfant A', 'prix' => 'enfant_1', 'ages' => ['de_1_enfant', 'a_1_enfant']],
                                        ['lbl' => '1er enfant B', 'prix' => 'enfant_2', 'ages' => ['de_2_enfant', 'a_2_enfant']],
                                        ['lbl' => '2em enfant', 'prix' => 'enfant_3', 'ages' => ['de_3_enfant', 'a_3_enfant']],
                                        ['lbl' => 'Bébé', 'prix' => 'bebe_1', 'ages' => ['bebe_1']],
                                    ],
                                ],
                                'double' => (object) [
                                    'titre' => 'Chambre Double',
                                    'max' => [
                                        ['lbl' => 'Pers. max.', 'field' => '_nb_max'],
                                        ['lbl' => 'Adulte', 'field' => '_adulte_max'],
                                        ['lbl' => 'Enfant', 'field' => '_enfant_max'],
                                        ['lbl' => 'Bébé', 'field' => '_bebe_max'],
                                    ],
                                    'occupants' => [
                                        ['lbl' => '1er adulte', 'prix' => '_adulte_1',],
                                        ['lbl' => '2em adulte', 'prix' => '_adulte_2',],
                                        ['lbl' => '1er enfant A', 'prix' => '_enfant_1', 'ages' => ['_de_1_enfant', '_a_1_enfant']],
                                        ['lbl' => '1er enfant B', 'prix' => '_enfant_2', 'ages' => ['_de_2_enfant', '_a_2_enfant']],
                                        ['lbl' => '2em enfant', 'prix' => '_enfant_3', 'ages' => ['_de_3_enfant', '_a_3_enfant']],
                                        ['lbl' => 'Bébé', 'prix' => '_bebe_1', 'ages' => ['_bebe_1']],
                                    ],
                                ],
                                'tripple' => (object) [
                                    'titre' => 'Chambre Triple',
                                    'max' => [
                                        ['lbl' => 'Pers. max.', 'field' => '_nb_max'],
                                        ['lbl' => 'Adulte', 'field' => '_adulte_max'],
                                    ],
                                    'occupants' => [
                                        ['lbl' => '1er adulte', 'prix' => '_adulte_1',],
                                        ['lbl' => '2em adulte', 'prix' => '_adulte_2',],
                                        ['lbl' => '3em adulte', 'prix' => '_adulte_3',],
                                    ],
                                ],
                                'quatre' => (object) [
                                    'titre' => 'Chambre Quadruple',
                                    'max' => [
                                        ['lbl' => 'Pers. max.', 'field' => '_nb_max'],
                                        ['lbl' => 'Adulte', 'field' => '_adulte_max'],
                                    ],
                                    'occupants' => [
                                        ['lbl' => '1er adulte', 'prix' => '_adulte_1',],
                                        ['lbl' => '2em adulte', 'prix' => '_adulte_2',],
                                        ['lbl' => '3em adulte', 'prix' => '_adulte_3',],
                                        ['lbl' => '4em adulte', 'prix' => '_adulte_4',],
                                    ],
                                ],
                                'villa' => (object) [
                                    'titre' => 'Villa',
                                    'max' => [
                                        ['lbl' => 'Pers. max.', 'field' => '_nb_max'],
                                        ['lbl' => 'Adulte', 'field' => '_adulte_max'],
                                    ],
                                    'occupants' => [
                                        ['lbl' => '1er adulte', 'prix' => '_adulte_1',],
                                    ],
                                ],
                            ];
                            foreach ($typesChambres as &$c) {
                                foreach ($c->occupants as &$o) {
                                    $o = ($o + ['ages' => null, 'prix_split' => null]);
                                }
                            }

                            $colonnesPrix = [
                                'net' => 'Prix Net',
                                'brut' => 'Prix Brut',
                                'total' => 'Total',
                            ];


                            // Création de l'entête du TAB
                            echo '<ul class="nav nav-tabs" role="tablist">';
                            foreach ($typesChambres as $typePrestation => $typeChambre) {
                                $active = $typePrestation === 'simple' ? 'active' : '';
                                ?>
                                <li role="presentation" class="<?=$active?>">
                                    <a href="#<?=$typePrestation?>" role="tab" data-toggle="tab">
                                        <?=$typeChambre->titre?>
                                    </a>
                                </li>
                                <?php
                            }
                            echo '</ul>';

                            // On créé des tableaux pour le nomenclature de champ - colonne
                            ?>
                            <!-- CORP DU TAB -->
                            <div class="tab-content" style="overflow: hidden;">

                                <?php
                                foreach ($typesChambres as $typePrestation => $typeChambre) {

                                    $active = $typePrestation === 'simple' ? 'active' : '';
                                    $addPrefix = fn($f) => $f[0] === '_' ? $typePrestation . $f : $f;
                                    $champNomPersonne = $typePrestation === 'simple' ? '' : $typePrestation . '_';

                                    //$NombreMaxPersonne = $typeChambre->max;
                                    ?>
                                    <div role="tabpanel" class="tab-pane <?=$active?>" id="<?=$typePrestation?>">
                                        <fieldset>
                                            <?php
                                            echo '<div class="span16">';
                                            foreach ($typeChambre->max as ['lbl' => $labelMax, 'field' => $suffixMax]) {
                                                $champ = $typePrestation . $suffixMax;
                                                ?>
                                                <div class="span4" style="margin-left: 0px;">
                                                    <?=$labelMax?> :
                                                    <?=selectNombre($champ, 6, $chambre->$champ ?? 0)?>
                                                </div>
                                                <?php
                                            }
                                            echo '</div><p style="margin-bottom: 50px;"><br></p>';
                                            ?>

                                            <div class="span7"></div>
                                            <?php
                                            foreach ($colonnesPrix as $colLabel) {
                                                ?>
                                                <div class="span2">
                                                    <div class="form-group" style="text-align: center;"><label>
                                                            <?=$colLabel?>
                                                        </label></div>
                                                </div>
                                                <?php
                                            }
                                            ?>
                                            <div class="span15">
                                                <?php
                                                foreach ($typeChambre->occupants as ['lbl' => $label, 'prix' => $nomChampPrix, 'ages' => $ages, 'prix_split' => $prix_split,]) {
                                                    ?>
                                                    <div class="span16">
                                                        <div class="span2">
                                                            <div class="form-group"><label><b>
                                                                        <?=$label?>
                                                                    </b></label></div>
                                                        </div>
                                                        <?php
                                                        if ($prix_split) {
                                                            ?>
                                                            <div class="span5" style='text-align:right'>
                                                                <div class="form-group">
                                                                    <input type="number" step="any" class="span2"
                                                                        name="<?=$prix_split[0]?>"
                                                                        value="<?=$chambre->{$prix_split[0]} ?: 0?>">
                                                                    +
                                                                    <input type="number" step="any" class="span2"
                                                                        name="<?=$prix_split[1]?>"
                                                                        value="<?=$chambre->{$prix_split[1]} ?: 0?>">
                                                                    =
                                                                </div>
                                                            </div>
                                                            <?php
                                                        } elseif ($ages) {
                                                            $champAgeA = $champAgeDe = $addPrefix($ages[0]);
                                                            if (count($ages) === 2) {
                                                                $champAgeA = $addPrefix($ages[1]);
                                                                ?>
                                                                <div class="span5">
                                                                    <div class="form-group">
                                                                        De:
                                                                        <?=selectNombre($champAgeDe, 18, $chambre->$champAgeDe)?>
                                                                        A :
                                                                        <?=selectNombre($champAgeA, 18, $chambre->$champAgeA)?>
                                                                    </div>
                                                                </div>
                                                                <?php
                                                            } else {
                                                                ?>
                                                                <div class="span5">
                                                                    <div class="form-group">Jusqu' à <input type="number" class="span2"
                                                                            name="<?=$champAgeA?>" style="width:30% !important"
                                                                            value="<?=$chambre->$champAgeA?>"> ans</div>
                                                                </div>
                                                                <?php
                                                            }
                                                        } else {
                                                            ?>
                                                            <div class="span5"></div>
                                                            <?php
                                                        }

                                                        foreach ($colonnesPrix as $netBrutTotal => $col) {
                                                            $nomChamp = $addPrefix($nomChampPrix . "_$netBrutTotal");
                                                            ?>
                                                            <div class="span2">
                                                                <input type="number" step="any" class="span2" name="<?=$nomChamp?>"
                                                                    value="<?=$chambre->$nomChamp ?: 0?>">
                                                            </div>
                                                            <?php
                                                        }
                                                        ?>
                                                    </div>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                        </fieldset>
                                    </div>
                                    <?php
                                } // END FOREACH
                                ?>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>

            <!-- //////////////  CONFIGURATION DE REMISE ///////////////// -->
            <div class="row">
                <?php
                // On va transformer les types de remise en tableau
                $tarifs = [
                    '_remise1' => 'Etape 2 : Configuration Early booking 1',
                    '_remise2' => 'Etape 3 : Configuration Remise Early booking 2',
                ];
                $i = 0;
                // On va parcourir le tableau de type de remise
                foreach ($tarifs as $remise => $tarif) {
                    // Test si remise N° 1 : On va créér le nom de champ "Remise" | N° "Remise2"
                    $numeroRemise = $i ? '2' : '';
                    $champRemisePct = "remise$numeroRemise";
                    $champCodePromo = "code_promo$numeroRemise";
                    ?>
                    <div id="form_tarif_1" style="margin: auto;background:#FFF;padding: 20px;float: none;"
                        class="span16 prix">
                        <fieldset>
                            <legend>
                                <?=$tarif?>
                            </legend>
                        </fieldset>
                        <fieldset>
                            <div class="span16">
                                <div class="row">
                                    <div id="acct-password-row" class="span7">
                                        <div class='form-box'>
                                            <h4>REDUCTION</h4>
                                            <div class="control-group ">
                                                <label class="control-label" style="width: 120px;">Promo code</label>
                                                <div class="controls" style="margin-left: 160px;">
                                                    <input name="<?=$champCodePromo?>" class="span4" type="text"
                                                        value="<?=$chambre->$champCodePromo?>" autocomplete="false">
                                                </div>
                                            </div>
                                            <div class="control-group ">
                                                <label class="control-label" style="width: 120px;">Remise %</label>
                                                <div class="controls" style="margin-left: 160px;">
                                                    <input type="number" class="span4" name="<?=$champRemisePct?>"
                                                        value="<?=$chambre->$champRemisePct?>" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="acct-password-row" class="span4">
                                        <div class='form-box'>
                                            <h4>VENTE</h4>
                                            <?php $champDate = function ($label, $nomChamp) use ($chambre) { ?>
                                                <div class="control-group">
                                                    <label class="control-label" style="width: 60px;">
                                                        <?=$label?>
                                                    </label>
                                                    <div class="controls">
                                                        <span class='field-clear'>&times;</span>&nbsp;
                                                        <input type="date" class="span2" name="<?=$nomChamp?>"
                                                            value="<?=$chambre->$nomChamp?>" />
                                                    </div>
                                                </div>
                                            <?php };
                                            $champDate('Début', "debut_remise$numeroRemise");
                                            $champDate('Fin', "fin_remise$numeroRemise");
                                            ?>
                                        </div>
                                    </div>
                                    <div id="acct-password-row" class="span4">
                                        <div class='form-box'>
                                            <h4>VOYAGE</h4>
                                            <?php
                                            $champDate('Début', "debut_remise{$numeroRemise}_voyage");
                                            $champDate('Fin', "fin_remise{$numeroRemise}_voyage");
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset style="background:#F7F7F7;padding: 25px 0px;border-radius: 2px;">
                            <div class="span16">
                                <?php

                                // Création de l'entête du TAB
                                echo '<ul class="nav nav-tabs" role="tablist">';
                                foreach ($typesChambres as $typePrestation => $typeChambre) {
                                    $active = $typePrestation === 'simple' ? 'active' : '';
                                    ?>
                                    <li role="presentation" class="<?=$active?>">
                                        <a href="#<?=$typePrestation . $remise?>" role="tab" data-toggle="tab">
                                            <?=$typeChambre->titre?>
                                        </a>
                                    </li>
                                    <?php
                                }
                                echo '</ul>';

                                // On créé des tableaux pour le nomenclature de champ - colonne
                                $colonnesPrix = [
                                    'total' => 'Total',
                                ];
                                ?>
                                <!-- CORP DU TAB -->
                                <div class="tab-content" style="overflow: hidden;">

                                    <?php
                                    foreach ($typesChambres as $typePrestation => $typeChambre) {
                                        $active = $typePrestation === 'simple' ? 'active' : '';
                                        $champNomPersonne = $typePrestation === 'simple' ? '' : $typePrestation . '_';
                                        $addPrefix = fn($f) => $f[0] === '_' ? $typePrestation . $f : $f;
                                        ?>
                                        <div role="tabpanel" class="tab-pane <?=$active?>" id="<?=$typePrestation . $remise?>">
                                            <fieldset>
                                                <div class="span8">
                                                    <div class="span3"></div>
                                                    <div class="span3" style='font-weight:bold;text-align:center'>Total</div>
                                                </div>
                                                <div class="span8">
                                                    <?php
                                                    foreach ($typeChambre->occupants as ['lbl' => $champLabel, 'prix' => $nomChampPrix]) {
                                                        ?>
                                                        <div class="span8">
                                                            <div class="span2">
                                                                <div class="form-group"><label><b><?=$champLabel?></b></label></div>
                                                            </div>
                                                            <div class="span1"></div>
                                                            <?php
                                                            foreach ($colonnesPrix as $netBrutTotal => $col) {
                                                                $nomChamp = $addPrefix($nomChampPrix."_$netBrutTotal");
                                                                $champVal = (int)$chambre->$nomChamp;
                                                                $pctRemise = ($chambre->{'remise'.$numeroRemise} ?: 0) / 100;
                                                                $remiseValeur = $pctRemise ? $champVal * (1 - $pctRemise) : 0;
                                                                ?>
                                                                <div class="span3">
                                                                    <input type="number" step="any" class="span3 center"
                                                                        name="<?=$nomChampPrix.$remise?>" value="<?=$remiseValeur?>" disabled>
                                                                </div>
                                                                <?php
                                                            }
                                                            ?>
                                                        </div>
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                            </fieldset>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <?php
                    $i++;
                }
                ?>
            </div>
        </div>

        <footer id="submit-actions" class="form-actions">
            <button type="reset" class="btn" name="action" value="CANCEL">Annuler</button>
            <button type="submit" class="btn btn-primary" name="save">Enregistrer</button>
        </footer>

    </form>
</section>

<script>
    $(() => {
        /************* CALCUL DES MONTANTS BRUT - TOTAUX ET REMISE *****************/

        let getElByName = (name, el = 'input') => $(el + `[name="${name}"]`);
        $tauxCommisionInput = getElByName('taux_commission');
        $tauxChangeInput = getElByName('monnaie', 'select');
        $remiseInput = getElByName('remise');
        $remise2Input = getElByName('remise2');

        // fonction de calcul remise
        function getCommPct() { return parseFloat($tauxCommisionInput.val()) / 100; }
        function getTauxChange() { return parseFloat($tauxChangeInput.val().split(':')[1]); }
        function getRemiseEB1Pct() { return parseFloat($remiseInput.val() || 0) / 100; }
        function getRemiseEB2Pct() { return parseFloat($remise2Input.val() || 0) / 100; }

        // Calcul de brut et Total à partir du valeur Net
        function ajusteMontant(montantNet, prefix) {
            let nomChampBrut = prefix + '_brut';
            let nomChampTotal = prefix + '_total';
            let nomChampTotalRemise1 = prefix + '_total_remise1';
            let nomChampTotalRemise2 = prefix + '_total_remise2';
            // on calcul le brut à partir du net (arrondi à 2 décimales)
            let montantBrut = Math.round(montantNet * getTauxChange() * (1 + getCommPct()) * 100) / 100;
            let montantTotal = Math.ceil(montantBrut);
            // On prepare le calcule de remise.. Test si pas de remise, affiche
            let montantRemise1 = Math.ceil(montantBrut * (1 - getRemiseEB1Pct()));
            let montantRemise2 = Math.ceil(montantBrut * (1 - getRemiseEB2Pct()));
            getElByName(nomChampBrut).val(montantBrut);
            getElByName(nomChampTotal).val(montantTotal);
            getElByName(nomChampTotalRemise1).val(montantRemise1);
            getElByName(nomChampTotalRemise2).val(montantRemise2);
        }

        // Calcul de Total REMISE à partir du valeur Net
        function ajusteMontantRemise(ValeurTotal, nomChampTotal, nomChampTotalRemise1, nomChampTotalRemise2) {
            let montantRemise1 = Math.ceil(ValeurTotal * (1 - getRemiseEB1Pct()));
            let montantRemise2 = Math.ceil(ValeurTotal * (1 - getRemiseEB2Pct()));
            getElByName(nomChampTotalRemise1).val(montantRemise1);
            getElByName(nomChampTotalRemise2).val(montantRemise2);
        }

        // sur modification d'une valuer "net", ajuster brut et total ||si on ajoute valeur A + B
        $('input').on('input', function (e) {
            let $this = $(this), thisVal = parseFloat($this.val());
            let fieldName = $this.attr('name');
            let [, typeChambre, typePersonne, nbPersone, netOuBrut] =
                fieldName.match(/^(|double|tripple|quatre|villa)_?(adulte|enfant|bebe)_(\d)_(net|brut)$/) || [];
            let prefix = (typeChambre ? typeChambre + '_' : '') + typePersonne + '_' + nbPersone;
            let [, champAouB] = fieldName.match(/^(a|b)$/) || [];
            // Si le champs qu'on vient de modifier est "net", il faut ajuster le brut correspondant
            if (netOuBrut === 'net') {
                ajusteMontant(thisVal, prefix);
            } else
                // Special case fields `a` and `b` (simple_adulte_1_net_a and simple_adulte_1_net_b in new structure)
                if (fieldName.match(/(a|b)/)) {
                    let adulte_1_net = parseFloat(getElByName('a').val()) + parseFloat(getElByName('b').val());
                    getElByName('adulte_1_net').val(adulte_1_net);
                    ajusteMontant(adulte_1_net, 'adulte_1');
                }
        });

        // Quand on 'click' sur un '.field-clear', vider le champ input suivant.
        $('.field-clear').on('click', function () {
            $(this).next('input').val('');
        });

        // sur changement de valeur du taux de change ou de la commission,
        // re-calculer tous les brut et totaux
        $('select[name="taux_change"], input[name="taux_commission"]').on('input', () => {
            // Création de tableau pour gerer les noms des champs
            let defChambres = [
                { prefix: '', maxAdulte: 1, maxEnfant: 3, maxBebe: 1 },
                { prefix: 'double_', maxAdulte: 2, maxEnfant: 3, maxBebe: 1 },
                { prefix: 'tripple_', maxAdulte: 3, maxEnfant: 0, maxBebe: 0 },
                { prefix: 'quatre_', maxAdulte: 4, maxEnfant: 0, maxBebe: 0 },
                { prefix: 'villa_', maxAdulte: 1, maxEnfant: 0, maxBebe: 0 },
            ];
            // On va parcourir le tableau définition des chambres
            defChambres.forEach(defChambre => {
                // Pour tous adulte
                for (let nbadulte = 1; nbadulte <= defChambre.maxAdulte; nbadulte++) {
                    let nomPrefix = defChambre.prefix + 'adulte_' + nbadulte;
                    let $net = $(`input[name="${nomPrefix}_net"]`);
                    // on récupère sa valeur
                    let montantNet = parseFloat($net.val());
                    // on calcule et on met à jour le montant brut et total
                    ajusteMontant(montantNet, nomPrefix);
                }

                // Pour tous enfants
                for (let nbenfant = 1; nbenfant <= defChambre.maxEnfant; nbenfant++) {
                    let nomPrefix = defChambre.prefix + 'enfant_' + nbenfant;
                    let $net = $(`input[name="${nomPrefix}_net"]`);
                    // on récupère sa valeur
                    let montantNet = parseFloat($net.val());
                    // on calcule et on met à jour le montant brut et total
                    ajusteMontant(montantNet, nomPrefix);
                }

                // Pour tous bebes
                for (let nbbebe = 1; nbbebe <= defChambre.maxBebe; nbbebe++) {
                    let nomPrefix = defChambre.prefix + 'bebe_' + nbbebe + '_net';
                    let $net = $(`input[name="${nomPrefix}_net"]`);
                    // on récupère sa valeur
                    let montantNet = parseFloat($net.val());
                    // on calcule et on met à jour le montant brut et total
                    ajusteMontant(montantNet, nomPrefix);
                }
            });
        });

        // Calcul les champs le tarif remise si on ajout de la valeur sur remise
        $('input[name="remise"], input[name="remise2"]').on('input', () => {
            // Création de tableau pour gerer les noms des champs
            var nbPersonnes = [
                ['', 'adulte', 1, 'enfant', 3, 'bebe', 1],
                ['double_', 'adulte', 2, 'enfant', 3, 'bebe', 1],
                ['tripple_', 'adulte', 3, 'enfant', 0, 'bebe', 0],
                ['quatre_', 'adulte', 4, 'enfant', 0, 'bebe', 0],
                ['villa_', 'adulte', 1, 'enfant', 0, 'bebe', 0],
            ];
            // On va parcourir le tableau
            // Pour tous adulte
            for (var i = 0; i < nbPersonnes.length; i++) {
                var nbPersonne = nbPersonnes[i];
                for (var nbadulte = 1; nbadulte <= (nbPersonnes[i][2]); nbadulte++) {
                    let name = `${nbPersonnes[i][0]}${nbPersonnes[i][1]}_${nbadulte}_total`;
                    let $total = $(`input[name="${name}"]`);
                    // on récupère sa valeur
                    let montantTotal = parseFloat($total.val());
                    // on calcule et on met à jour le montant brut et total
                    ajusteMontantRemise(montantTotal, name, `${name}_remise1`, `${name}_remise2`);
                }
            }
            // Pour tous enfants
            for (var i = 0; i < nbPersonnes.length; i++) {
                var nbPersonne = nbPersonnes[i];
                for (var nbenfant = 1; nbenfant <= (nbPersonnes[i][4]); nbenfant++) {
                    let $total = $(`input[name="${nbPersonnes[i][0]}${nbPersonnes[i][3]}_${nbenfant}_total"]`);
                    // on récupère sa valeur
                    let montantTotal = parseFloat($total.val());
                    // on calcule et on met à jour le montant brut et total
                    ajusteMontantRemise(montantTotal, `${nbPersonnes[i][0]}${nbPersonnes[i][3]}_${nbenfant}_total`, `${nbPersonnes[i][0]}${nbPersonnes[i][3]}_${nbenfant}_total_remise1`, `${nbPersonnes[i][0]}${nbPersonnes[i][3]}_${nbenfant}_total_remise2`);
                }
            }
            // Pour tous bébé
            for (var i = 0; i < nbPersonnes.length; i++) {
                var nbPersonne = nbPersonnes[i];
                for (var nbadulte = 1; nbadulte <= (nbPersonnes[i][6]); nbadulte++) {
                    let $total = $(`input[name="${nbPersonnes[i][0]}${nbPersonnes[i][5]}_${nbadulte}_total"]`);
                    // on récupère sa valeur
                    let montantTotal = parseFloat($total.val());
                    // on calcule et on met à jour le montant brut et total
                    ajusteMontantRemise(montantTotal, `${nbPersonnes[i][0]}${nbPersonnes[i][5]}_${nbadulte}_total`, `${nbPersonnes[i][0]}${nbPersonnes[i][5]}_${nbadulte}_total_remise1`, `${nbPersonnes[i][0]}${nbPersonnes[i][5]}_${nbadulte}_total_remise2`);
                }
            }
        });
    });
    //]]>
</script>
<?php
// termine la page en l'incluant dans le layout (header et footer)
admin_finish();
