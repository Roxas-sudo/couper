<?php
session_start();
require "bdd.php";
require "fonctions.php";

$titre = "Zone secrète";
require "header.php";
?>

    <main>

        <section class="zone-secrete">
            <h2>🤫 Zone secrète</h2>
            <p>Tu as trouvé la sélection cachée du public. Bienvenue.</p>
        </section>

        <?php
        $secrets = $bdd->query("SELECT * FROM films WHERE cache = 1 ORDER BY titre");
        if ($secrets && $secrets->num_rows > 0) {
            grilleFilms($secrets);
        } else {
            echo "<p class='aucun-avis'>Aucun film secret pour le moment.</p>";
        }
        ?>

        <p class="lien-form"><a href="index.php">Retour à l'accueil</a></p>

    </main>

</body>
</html>
