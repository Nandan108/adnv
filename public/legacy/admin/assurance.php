<?php
require 'admin_init.php';
use App\Utils\URL;

?>

<style>
    .blockcontent {
        padding: 10px;
        margin-left: 20px;
        border: 3px solid;
        margin-bottom: 30px;
        overflow: hidden;
        height: 342px;
    }

    .blockcontent h4 {
        text-align: center;
        margin-bottom: 18px;
        background: #6b8c2d;
        padding: 5px;
        color: #FFF;
        margin-top: 0px;
        margin-bottom: 30px;
        text-transform: uppercase;
    }
</style>

<?php

$assurance_list_url = 'assurances.php';

$id_assurance = (int)($_POST['id'] ?? $_GET['id'] ?? $_POST['id'] ?? $_GET['id'] ?? null);

// liste des champs à tirer de $_POST et enregistrer dans la table `assurance`

$champs_assurance = [
    'titre_assurance',
    'prix_assurance',
    'prestation_assurance',
    'couverture',
    'duree',
    'pourcentage',
    'prix_minimum',
    'frais_annulation',
    'assistance',
    'fraisderecherche',
    'volretarde',
];

function getassurance(int $id)
{
    return dbGetOneObj(
        "SELECT * FROM assurance WHERE id = $id
    ",
    );
}

if (isset($_POST['save'])) {

    // ici on récupère toutes les valeurs de $_POST correspondant au champs dans la liste ci-dessus
    $valeurs_a_enregistrer = array_intersect_key($_POST, array_flip($champs_assurance));

    $valeurs_a_enregistrer['prix_assurance'] = $valeurs_a_enregistrer['prix_assurance'] ?: 0;
    $valeurs_a_enregistrer['pourcentage']    = $valeurs_a_enregistrer['pourcentage'] ?: 0;

    if ($id_assurance) {
        // génère une chaine: "<champ1> := <value1>, <champ2> := <value2>, <champ3> := <value3>, etc..."
        $SET = implode(",\n", array_map(fn($f) => "$f = :$f", array_keys($valeurs_a_enregistrer)));

        $update_stmt = $conn->prepare($sql =
            "UPDATE assurance
            SET $SET
            WHERE id = $id_assurance
        ");
        // en cas d'erreur lors de la création du statement, on quite avec message d'erreur
        if (!$update_stmt) {
            die(dd(['nPDO::errorInfo()' => $conn->errorInfo(), 'SQL' => $sql]));
        }

        // Et finalement on execute l'update sur ces valeurs
        $update_stmt->execute($valeurs_a_enregistrer);
        $assurance = getassurance($id_assurance);
        ?>
        <script>
            alert('La mise à jour de assurance a été effectuée avec succès !');
            // redirect to the list of assurances
            window.location = '<?= "$assurance_list_url?duree={$assurance->duree}&couverture={$assurance->couverture}&titre_assurance={$assurance->titre_assurance}" ?>';
        </script>
        <?php

    } else {
        // Pour la création d'un nouveau assurance
        $champs_cibles = implode(', ', array_keys($valeurs_a_enregistrer));
        $valeurs       = implode(', ', array_map(fn($f) => ":$f", array_keys($valeurs_a_enregistrer)));
        // préparation...
        $last_id = dbExec("INSERT INTO assurance ($champs_cibles) VALUES ($valeurs)", $valeurs_a_enregistrer);
        ?>
        <script>
            alert('L\'ajout de assurance a été effectuée avec succès !');
            // redirect to the list of excursion
            window.location = '<?= $assurance_list_url ?>';
        </script>
        <?php
    }
    die();
} // end if (isset($_POST['save']))

// récupère les données du assurance

if ($id_assurance) {
    $assurance = getassurance($id_assurance);
    ;
    if (!$assurance) erreur_404('Désolé, ce assurance n\'existe pas');
} else {
    // Pas d'$id_assurance ? Alors on est sur une page de création de nouveau assurance.
    // donc on va créer un objet vide...
    $assurance = (object)array_fill_keys($champs_assurance, null);
    // Specify default values
    $assurance->prestation_assurance = <<<EOF
        ✓ Frais annulation max 30'000.00 CHF avec 20 % franchise<br/>
        ✓ Assistance illimitée<br/>
        ✓ Frais de recherche 30'000.00 CHF<br/>
        ✓ Vol retardé 2'000.00 CHF<br/>
        ✓ Travel Hotline<br/>
        ✓ Service de conseil médical 24h / 24h<br/>
        ✓ Service de blocage des cartes de crédit et de client<br/>
        ✓ Home Care<br/>
        ✓ Avance des frais auprès de l'hôpital<br/>
    EOF;
    $assurance->frais_annulation     = <<<EOF
        Prise en charge des frais dans les cas suivants:<br/><br/>
        • Maladie grave (y compris le diagnostic d'une
        maladie épidémique ou pandémique telle que
        p. ex. la COVID-19), accident grave, décès et
        complications en cas de grossesse<br/><br/>
        • Atteinte grave aux biens de l'assuré à son
        domicile permanent par suite d'un vol, d'un
        incendie, d'un dégât d'eau ou de dégâts naturels<br/><br/>
        • Retard ou défaillance du moyen de transport
        public utilisé pour se rendre au lieu du début
        du voyage assuré<br/><br/>
        • Si des grèves rendent impossible la réalisation du voyage (à l’exception des grèves de
        la société organisatrice du voyage ou de ses
        prestataires)<br/><br/>
        • Dangers sur le lieu de destination tels que
        guerres, attentats terroristes ou troubles en
        tout genre, pour autant que les services officiels suisses (DFAE) déconseillent
        d’effectuer le voyage<br/><br/>
        • Catastrophes naturelles sur le lieu de destination, qui mettent en danger la vie de la
        personne assurée<br/><br/>
        • Changement inattendu de la situation professionnelle (chômage ou entrée en fonction)
        Les billets de manifestations sont également
        couverts en plus des prestations de voyage
        réservées.<br/>
    EOF;
    $assurance->assistance           = <<<EOF
        <p style="text-align:justify;">Organisation et prise en charge des coûts
        dans les cas suivants:<br/><br/>
        • Transport dans le centre hospitalier approprié le plus proche<br/><br/>
        • Rapatriement dans un hôpital au lieu de domicile (si nécessaire, avec un accompagnement médical)<br/><br/>
        • Rapatriement en cas de décès<br/><br/>
        • Retour en cas d'interruption de voyage assurée d'un accompagnant ou d'un membre de la famille<br/><br/>
        • Retour anticipé pour cause de maladie grave (y compris le diagnostic d'une maladie épidémique ou pandémique telle que p. ex. la COVID-19), d'accident grave ou de décès d'un proche ne participant pas au voyage ou du remplaçant au poste de travail<br/><br/>
        • Retour dû à des troubles, à des attentats terroristes, à des catastrophes naturelles ou à des grèves<br/></p>
    EOF;
    $assurance->fraisderecherche     = <<<EOF
        <p style="text-align:justify;">Prise en charge des frais de recherche et de sauvetage, si la personne assurée est réputée disparue à l'étranger ou doit être sauvée d'une situation d'urgence physique.<br/></p>
    EOF;
    $assurance->volretarde           = <<<EOF
        <p style="text-align:justify;">Prise en charge des coûts supplémentaires (hébergement à l'hôtel, changement de réservation, appels téléphoniques) en cas de correspondance manquée en raison d'un retard d'au moins trois heures imputable exclusivement à la première compagnie aérienne<br/></p>
    EOF;
}

// chargement des données de référence (lookup data)
$durees      = array_map(fn($t) => (object)['duree' => $t], ['annuelle', 'voyage']);
$couvertures = array_map(fn($l) => (object)['couverture' => $l], ['par famille', 'par personne']);

?>


<section class="nav-page" style="display: block;">
    <div class="container">
        <div class="row">
            <div class="span7">
                <header class="page-header">
                    <h3>ASSURANCES | <span style="font-size: 12px;color:#00CCF4;">Modification assurance </span></h3>
                </header>
            </div>
            <div class="span9">
                <ul class="nav nav-pills">
                    <li>

                        <a href="assurances.php" rel="tooltip" data-placement="left" title="Liste des hôtels">
                            <i class="icon-chevron-left pull-left"></i> Voir la liste des assurances
                        </a>

                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>



<section id="my-account-security-form" class="page container">
    <form id="userSecurityForm" class="form-horizontal" method="post" action="" enctype="multipart/form-data">

        <div class="container">

            <div class="alert alert-block alert-info">
                <p>
                    Pour l'ajout d' assurance, veuillez bien verifier que tous les étapes sont bien remplir
                </p>
            </div>
            <div class="row">

                <div id="acct-password-row" class="span8">
                    <div
                        style="padding: 10px;margin-left: 20px;border: 3px solid;margin-bottom: 30px;overflow: hidden;height: 342px; ">
                        <h4
                            style="text-align: center;margin-bottom: 18px;background:#6b8c2d;padding: 5px;color:#FFF;margin-top: 0px;margin-bottom: 30px;">
                            CARACTERISTIQUE</h4>

                        <div class="control-group ">
                            <label class="control-label">Titre de l'assurance</label>
                            <div class="controls">
                                <input id="current-pass-control" name="titre_assurance" class="span5" type="text"
                                    value="<?= $assurance->titre_assurance; ?>" autocomplete="false" required>

                            </div>
                        </div>

                        <div class="control-group ">
                            <label class="control-label">Durée</label>
                            <div class="controls">
                                <select class="span5" name="duree" id="duree">
                                    <?= printSelectOptions(
                                        source: $durees,
                                        valueSource: 'duree',
                                        selectedVal: $assurance->duree,
                                    ) ?>
                                </select>
                            </div>
                        </div>



                        <div class="control-group ">
                            <label class="control-label">Couverture</label>
                            <div class="controls">

                                <select class="span5" name="couverture" id="couverture">
                                    <?= printSelectOptions(
                                        source: $couvertures,
                                        valueSource: 'couverture',
                                        selectedVal: $assurance->couverture,
                                    ) ?>
                                </select>

                            </div>
                        </div>

                        <div class="control-group prix_assurance">
                            <label class="control-label">Prix de l'assurance</label>
                            <div class="controls">
                                <input id="current-pass-control" name="prix_assurance" class="span5" type="text"
                                    value="<?= $assurance->prix_assurance; ?>" autocomplete="false">
                            </div>
                        </div>

                        <div class="control-group pourcentage">
                            <div style='margin-bottom:0.5em'>
                                <label class="control-label">Pourcentage (%)</label>
                                <div class="controls">
                                    <input id="current-pass-control" name="pourcentage" class="span5" type="text"
                                        value="<?= $assurance->pourcentage; ?>" autocomplete="false">
                                </div>
                            </div>
                            <div>
                                <label class="control-label">Prix minimum (CHF)</label>
                                <div class="controls">
                                    <input id="current-pass-control" name="prix_minimum" class="span5" type="text"
                                        value="<?= $assurance->prix_minimum; ?>" autocomplete="false">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
                $i = 1;
                $prestations = [
                    'Prestation assurance' => 'prestation_assurance',
                    'Frais d’annulation' => 'frais_annulation',
                    'Assistance' => 'assistance',
                    'Frais de recherche et de sauvetage' => 'fraisderecherche',
                    'Vol retardé' => 'volretarde',
                ];
                foreach ($prestations as $label => $field) {
                    ?>

                    <div id="acct-password-row" class="span8">
                        <div class="blockcontent">
                            <h4><?= $label ?></h4>
                            <div class="control-group ">
                                <textarea class="content<?= $i ?>" name="<?= $field ?>">
                                                            <?= str_replace('?', '✓', ($assurance->{$field})); ?>
                                                        </textarea>
                            </div>
                        </div>
                    </div>

                    <?php
                    $i++;
                }
                ?>





            </div>


            <script type="text/javascript">

                // Mode d'affichage de textarea editable
                $('.content1').richText();
                $('.content2').richText();
                $('.content3').richText();
                $('.content4').richText();
                $('.content5').richText();

                $(document).ready(function () {
                    $("#duree").change(function () {
                        $(this).find("option:selected").each(function () {
                            if ($(this).attr("value") == "annuelle") {
                                $(".prix_assurance").show();
                                $(".pourcentage").hide();
                            }
                            else if ($(this).attr("value") == "voyage") {
                                $(".pourcentage").show();
                                $(".prix_assurance").hide();
                            }



                        });
                    }).change();
                });

            </script>


            <footer id="submit-actions" class="form-actions">
                <button type="reset" class="btn" name="action" value="CANCEL">Annuler</button>
                <button type="submit" class="btn btn-primary" name="save">Enregistrer</button>
            </footer>

        </div>
    </form>
</section>


<script src="js/jquery.chained.min.js"></script>
<script charset=utf-8>

    (function ($, window, document, undefined) {
        "use strict";

        $.fn.chained = function (parent_selector, options) {

            return this.each(function () {
                var child = this;
                var backup = $(child).clone();
                $(parent_selector).each(function () {
                    $(this).bind("change", function () {
                        updateChildren();
                    });

                    if (!$("option:selected", this).length) {
                        $("option", this).first().attr("selected", "selected");
                    }

                    updateChildren();
                });

                function updateChildren() {
                    var trigger_change = true;
                    var currently_selected_value = $("option:selected", child).val();

                    $(child).html(backup.html());

                    var selected = "";
                    $(parent_selector).each(function () {
                        var selectedClass = $("option:selected", this).val();
                        if (selectedClass) {
                            if (selected.length > 0) {
                                if (window.Zepto) {
                                    selected += "\\\\";
                                } else {
                                    selected += "\\";
                                }
                            }
                            selected += selectedClass;
                        }
                    });

                    var first;
                    if ($.isArray(parent_selector)) {
                        first = $(parent_selector[0]).first();
                    } else {
                        first = $(parent_selector).first();
                    }
                    var selected_first = $("option:selected", first).val();

                    $("option", child).each(function () {

                        if ($(this).hasClass(selected) && $(this).val() === currently_selected_value) {
                            $(this).prop("selected", true);
                            trigger_change = false;
                        } else if (!$(this).hasClass(selected) && !$(this).hasClass(selected_first) && $(this).val() !== "") {
                            $(this).remove();
                        }
                    });

                    if (1 === $("option", child).size() && $(child).val() === "") {
                        $(child).prop("disabled", true);
                    } else {
                        $(child).prop("disabled", false);
                    }
                    if (trigger_change) {
                        $(child).trigger("change");
                    }
                }
            });
        };

        $.fn.chainedTo = $.fn.chained;

        $.fn.chained.defaults = {};

    })(window.jQuery || window.Zepto, window, document);


    $(document).ready(function () {


        $("#par").chained("#info");
    });

</script>


<?php
// termine la page en l'incluant dans le layout (header et footer)
admin_finish();