<?php
session_start();
$titre = "Accueil";
require "header.php";
?>

    <main>

        <section class="hero">
            <h2>Bienvenue sur Coupez !</h2>
            <p>Le site des passionnés de cinéma.</p>
            <?php if (isset($_SESSION['username'])) { ?>
                <p>Content de te revoir, <?php echo htmlspecialchars($_SESSION['username']); ?> 🎬</p>
            <?php } ?>
        </section>

    </main>

    <script>
    // Easter egg : 5 clics rapides sur le logo « Coupez ! » ouvrent la zone secrète
    (function () {
        var logo = document.querySelector('header h1 a');
        if (!logo) return;
        var clics = 0, minuteur = null;
        logo.addEventListener('click', function (e) {
            e.preventDefault();
            clics++;
            clearTimeout(minuteur);
            minuteur = setTimeout(function () { clics = 0; }, 1500);
            if (clics >= 5) {
                window.location.href = 'secret.php';
            }
        });
    })();
    </script>

</body>
</html>
