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

</body>
</html>
