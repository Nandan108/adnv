<?php

include 'admin_init.php';

if (isset($_GET['action']) && $_GET['action'] == 'delete') {

    $stmt = dbExec('DELETE FROM partenaire_liste WHERE id_partenaire_list = ?', [
        $_GET['id_partenaire_list'],
    ]);

    echo "<script type='text/javascript'>alert('Suppression partenaire effectué');</script>";
    echo "<meta http-equiv='refresh' content='0;url=liste_partenaires.php'/>";
    return;
}

$partners       = collect(dbGetAllObj('SELECT * FROM partenaire_liste'));
$partnersByType = $partners->groupBy('type');
$partnerTypes   = $partnersByType->keys();

?>
<section class="nav-page" style="display: block;">
    <div class="container">
        <div class="row">
            <div class="span7">
                <header class="page-header">
                    <h3>Partenaires | <span style="font-size: 12px;color:#00CCF4;">Liste partenaires</span></h3>
                </header>
            </div>
            <div class="span9">
                <ul class="nav nav-pills">
                    <li>

                        <a href="ajout_liste_partenaire.php" rel="tooltip" data-placement="left"
                            title="Nouveau partenaire">
                            <i class="icon-plus"></i> Nouveau partenaire
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
                    <li class="nav-header">Partenaires</li>
                    <li class="active">
                        <a id="view-all" href="#">
                            <i class="icon-chevron-right pull-right"></i>
                            <b>Afficher tous</b>
                        </a>
                    </li>

                    <?php
                    foreach ($partnerTypes as $i => $partnerType) {
                        ?>
                        <li>
                            <a href="#Person-<?= $i + 1 ?>">
                                <i class="icon-chevron-right pull-right"></i>
                                <?= $partnerType ?>
                            </a>
                        </li>
                        <?php
                    }
                    ?>
                </ul>
            </div>
        </div>
        <div class="span12">

            <?php
            foreach ($partnerTypes as $i => $partnerType) {
                ?>
                <div id="Person-<?= $i + 1 ?>" class="box">
                    <div class="box-header">
                        <i class="icon-globe icon-large"></i>
                        <h5><?= $partnerType ?></h5>

                    </div>
                    <div class="box-content box-table">
                        <table class="table table-hover tablesorter">
                            <thead>
                                <tr>
                                    <th style="width:5%">#</th>
                                    <th style="width:40%">Nom</th>
                                    <th style="width:15%">Actions</th>

                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                $parners = $partnersByType[$partnerType];
                                foreach ($parners as $partner) {
                                    ?>
                                    <tr>
                                        <td><img src="<?= $partner->photo ?>" width="100"></td>
                                        <td><?= $partner->nom ?></td>
                                        <td>
                                            <a href="liste_partenaires.php?id_partenaire_list=<?= $partner->id_partenaire_list ?>&action=delete"
                                                onclick="return confirm('Vous etes sur de supprimer cette ligne')" class="btn"
                                                style="font-size: 10px;padding: 0 10px;margin-bottom: 5px;"><i
                                                    class="icon-trash"></i> Supprimer</a>
                                            <br>
                                            <a href="ajout_liste_partenaire.php?id_partenaire_list=<?= $partner->id_partenaire_list ?>&action=edit"
                                                class="btn" style="font-size: 10px;padding: 0 14px;margin-bottom: 5px;"><i
                                                    class="icon-edit"></i> Modifier</a>
                                            <br>
                                            <a href="duplication_liste_partenaire.php?id_partenaire_list=<?= $partner->id_partenaire_list ?>"
                                                class="btn" style="font-size: 10px;padding: 0 12px;margin-bottom: 5px;"><i
                                                    class="icon-bookmark"></i> Dupliquer</a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                </div>
                <?php
            }
            ?>
        </div>
    </div>
</section>

<script type="text/javascript">
    $(function () {
        $('#person-list.nav > li > a').click(function (e) {
            if ($(this).attr('id') == "view-all") {
                $('div[id*="Person-"]').fadeIn('fast');
            } else {
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

        $(function () {
            $('table').tablesorter();
            $("[rel=tooltip]").tooltip();
        });
    });
</script>

<?php
// termine la page en l'incluant dans le layout (header et footer)
admin_finish();
