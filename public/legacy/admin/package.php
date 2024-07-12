<?php
include 'admin_init.php';

?>
<style type="text/css">
    .btn-group-box a.btn,
    .btn-group-box button.btn {
        height: 300px;
        width: 228px;
    }

    .compte-span {
        margin-left: -90px !important;
        width: 61px;
    }
</style>

<section id="my-account-security-form" class="page container">
    <div class="container">
        <div class="alert alert-block alert-info">
            <legend class="lead">
                Configuration des séjours
            </legend>
            <p>
                Dans ce système, il y a trois types des séjours, à savoir le séjours simple, manuel et vol seul. Vous
                pouvez choisir entre les trois boutons ci-dessous le séjours que vous voulez configurer.
            </p>
        </div>
    </div>
</section>
<section class="page container">
    <div class="row">

        <div class="span16">
            <div class="box">
                <div class="box-header">
                    <i class="icon-calendar"></i>
                    <h5>Resumé chiffré des séjours</h5>
                </div>
                <div class="box-content" style="text-align: center;">
                    <div class="btn-group-box">
                        <button class="btn">
                            <a href="sejours.php" style="text-decoration: none;color : #555555">
                                <i class="icon-calendar icon-large" style="font-size: 100px"></i><br
                                 />
                                Séjours ADN
                                <span class="compte-span">
                                    <?= dbGetOneVal('SELECT COUNT(*) FROM sejours WHERE fin_vente > curdate()') ?>
                                </span>
                            </a>
                        </button>
                        <button class="btn">
                            <a href="package_manuel.php" style="text-decoration: none;color : #555555">
                                <i class="icon-bookmark icon-large" style="font-size: 100px"></i><br />
                                Séjours autres TO
                                <span class="compte-span">
                                    <?= dbGetOneVal('SELECT COUNT(*) FROM package_manuel') ?>
                                </span>
                            </a>
                        </button>
                        <button class="btn">
                            <a href="package_vol.php" style="text-decoration: none;color : #555555">
                                <i class="icon-plane icon-large" style="font-size: 100px"></i><br />
                                Vol seul
                                <!--  -->
                                <span class="compte-span">
                                    <?= dbGetOneVal('SELECT COUNT(*) FROM package_manuel_vol') ?>
                                </span>
                            </a>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php

// termine la page en l'incluant dans le layout (header et footer)
admin_finish();
