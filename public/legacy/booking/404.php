<?php
header('HTTP/1.1 404 Not Found');
$_page_subtitle ??= "404 - Page Not Found";
?>

<style>
    #err_container {
        text-align: center;
        padding: 3em;
    }

    h1 {
        font-size: 3em;
        margin-bottom: 0.5em;
    }

    p {
        font-size: 1.2em;
        margin-bottom: 2em;
    }

    .timer {
        font-size: 1.5em;
        font-weight: bold;
    }
</style>
<script>
    let countdown = 5;
    <?php if (!$_ENV['APP_DEBUG']) { ?>
        function updateTimer() {
            document.getElementById('timer').textContent = countdown;
            if (countdown > 0) {
                countdown--;
                setTimeout(updateTimer, 1000);
            } else {
                history.back();
            }
        }
        window.onload = function () {
            updateTimer();
        }
    <?php } ?>
</script>

<div id="err_container">
    <h1>404 - Recherche échouée</h1>
    <p>Nous sommes désolé, il semble qu'une erreur technique soit survenue.<br>L'objet recherché n'a pas pu être trouvé.</p>
    <?php if (!$_ENV['APP_DEBUG']) { ?>
        <p>Vous serez renvoyé à la page précédente dans <span class="timer" id="timer">5</span> secondes...</p>
    <?php } ?>
</div>