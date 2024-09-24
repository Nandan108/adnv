<?php
require 'admin_init.php';

$counts = [
    'pays' => \App\Models\Pays::count(),
    'hotels' => \App\Models\Hotel::count(),
    'vols' => \App\Models\Vol::count(),
    'circuits' => \App\Models\Circuit::count(),
    'croisieres' => \App\Models\Croisiere::count(),
    'transfert' => \App\Models\Transfert::count(),
    'tours' => \App\Models\Tour::count(),
    'sejours' => \App\Models\Sejour::count(),
    // 'package_manuel'
    // 'package_manuel_vol'
];

$hotels = \App\Models\Hotel::with('lieu.paysObj')
    ->orderBy('id', 'desc')
    ->limit(8)
    ->get();

?>
<section id="my-account-security-form" class="page container">
    <div class="container">
        <div class="alert alert-block alert-info">
            <legend class="lead">
                Bienvenue
            </legend>
            <p>
                Bienvenue sur l' interface d'administration du site de ADN Voyage. Dans cet interface vous pouvez gérer
                plusieurs fonctionnalités à savoir la gestion des vols, hôtels, circuits, croisières, transferts,...
                Vous pouvez égelement gérer votre profil.
            </p>
        </div>
    </div>
</section>
<section class="page container">
    <div class="row">

        <div class="span16">
            <div class="box">
                <div class="box-header">
                    <i class="icon-bookmark"></i>
                    <h5>Resumé chiffré de la base de donnée</h5>
                </div>
                <div class="box-content" style="text-align: center;">
                    <div class="btn-group-box">
                        <button class="btn"><a href="lieu.php" style="text-decoration: none;color : #555555"><i
                                    class="icon-globe icon-large" style="font-size: 40px"></i><br />
                                Pays <span class="compte-span">
                                    <?= $counts['pays'] ?>
                                </span>
                            </a></button>

                        <button class="btn"><a href="hotels.php" style="text-decoration: none;color : #555555"><i
                                    class="icon-tasks icon-large" style="font-size: 40px"></i><br />
                                Hôtels<span class="compte-span">
                                    <?= $counts['hotels'] ?>
                                </span></a></button>

                        <button class="btn"><a href="vols.php" style="text-decoration: none;color : #555555"><i
                                    class="icon-plane icon-large" style="font-size: 40px"></i><br />
                                Vols<span class="compte-span">
                                    <?= $counts['vols'] ?>
                                </span></a></button>

                        <button class="btn"><a href="circuits.php" style="text-decoration: none;color : #555555"><i
                                    class="icon-map-marker icon-large" style="font-size: 40px"></i><br />
                                Circuits<span class="compte-span">
                                    <?= $counts['circuits'] ?>
                                </span></a></button>

                        <button class="btn"><a href="croisieres.php" style="text-decoration: none;color : #555555"><i
                                    class="icon-list-alt icon-large" style="font-size: 40px"></i><br />
                                Croisières<span class="compte-span">
                                    <?= $counts['croisieres'] ?>
                                </span></a></button>

                        <button class="btn"><a href="transferts.php" style="text-decoration: none;color : #555555"><i
                                    class="icon-bar-chart icon-large" style="font-size: 40px"></i><br />
                                Transferts<span class="compte-span">
                                    <?= $counts['transfert'] ?>
                                </span></a></button>

                        <button class="btn"><a href="excursions.php" style="text-decoration: none;color : #555555"><i
                                    class="icon-bookmark icon-large" style="font-size: 40px"></i><br />
                                Excursions<span class="compte-span">
                                    <?= $counts['tours'] ?>
                                </span></a></button>

                        <button class="btn"><a href="package.php?order&page=1"
                                style="text-decoration: none;color : #555555"><i class="icon-calendar icon-large"
                                    style="font-size: 40px"></i><br />
                                Séjours<span class="compte-span">
                                    <?= $counts['sejours'] ?>
                                </span></a></button>
                    </div>
                </div>
            </div>
        </div>


        <div class="span8">
            <div class="box pattern pattern-sandstone">
                <div class="box-header">
                    <i class="icon-tasks"></i>
                    <h5>Dérniers hôtels enregistrés</h5>
                    <button class="btn btn-box-right" data-toggle="collapse" data-target=".box-list">
                        <i class="icon-reorder"></i>
                    </button>
                </div>

                <div class="box-content box-table">
                    <table id="sample-table" class="table table-hover table-bordered tablesorter no-border">

                        <tbody>

                            <?php
                            function hotelRow($hotel)
                            {
                                ?>
                                <tr>
                                    <td style="width: 25%"><img src="<?= $hotel->photo ?>" width="150"></td>
                                    <td>
                                        <a href="#" class="news-item-title">
                                            <?= $hotel->nom ?>
                                        </a>
                                        <hr style="margin: 10px 0;">
                                        <p class="news-item-preview">
                                            <?= "{$hotel->lieu->paysObj->nom} -
                                        {$hotel->lieu->ville} -
                                        {$hotel->lieu->lieu} -
                                        $hotel->adresse $hotel->postal {$hotel->lieu->ville}." ?>
                                        </p>
                                    </td>
                                </tr>
                            <?php }

                            foreach ($hotels->slice(0, 4) as $hotel) {
                                hotelRow($hotel);
                            }
                            ?>


                        </tbody>
                    </table>
                </div>



                <div class="box-content box-list collapse in">

                    <div class="box-collapse ">
                        <button class="btn btn-box" data-toggle="collapse" data-target=".more-list">
                            Afficher plus
                        </button>
                    </div>


                    <div class="box-content box-table more-list collapse out">
                        <table id="sample-table" class="table table-hover table-bordered tablesorter no-border">

                            <tbody>
                                <?php
                                foreach ($hotels->slice(4, 4) as $hotel) {
                                    hotelRow($hotel);
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                </div>

            </div>
        </div>
        <div class="span8">
            <div class="box">
                <div class="box-header">
                    <i class="icon-book"></i>
                    <h5>Guide</h5>
                </div>
                <div class="box-content">
                    <br>
                    <p><a href="#" class="news-item-title">1) J'aimerai modifier mon profil ?</a></p>
                    <p><a href="#" class="news-item-title">2) Comment ajouter une fiche hôtel ?</a></p>
                    <p><a href="#" class="news-item-title">3) Comment ajouter une chambre ?</a></p>
                    <p><a href="#" class="news-item-title">4) Je vais changer le taux de change, comment le faire ?</a>
                    </p>
                    <p><a href="#" class="news-item-title">5) Rémise et promotion?</a></p>
                    <p><a href="#" class="news-item-title">6) Comment configurer une séjour?</a></p>
                    <p><a href="#" class="news-item-title">7) Quels sont les différents types des séjours?</a></p>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="icon-ok"></i>
                        Voir tous les FAQ
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
// termine la page en l'incluant dans le layout (header et footer)
admin_finish();