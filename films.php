<?php if (session_status() === PHP_SESSION_NONE) { session_start(); } $titre = "Films"; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Coupez ! - <?php echo isset($titre) ? $titre : "Accueil"; ?></title>
  <link rel="stylesheet" href="style.css?v=4">
</head>
<body>
    <header>
        <h1><a href="index.php">Coupez !</a></h1>
        <nav>
            <a href="ajout_film.php">Ajouter un film!</a>
            <a href="films.php">Films</a>
            <?php if (isset($_SESSION['user_id'])) { ?>
                <a href="profil.php">Profil</a>
                <a href="deconnexion.php">Déconnexion</a>
            <?php } else { ?>
                <a href="connexion.php">Connexion</a>
                <a href="inscription.php">Inscription</a>
            <?php } ?>
        </nav>
    </header>

    <main>

    <?php
    require "bdd.php";
    require "fonctions.php";

    echo "<form method='get' class='recherche-film'>
        <input type='text' name='recherche' placeholder='Rechercher un film...'>
        <input type='submit' value='Rechercher'>
    </form>";

    // Résultats de recherche
    if (isset($_GET['recherche'])) {
        $recherche = $_GET['recherche'];
        $stmt = $bdd->prepare("SELECT * FROM films WHERE titre LIKE (?) AND cache = 0");
        $motif = "%" . $recherche . "%";
        $stmt->bind_param("s", $motif);
        $stmt->execute();
        $result = $stmt->get_result();

        echo "<h2>Résultats de la recherche pour '" . htmlspecialchars($recherche) . "'</h2>";

        if ($result->num_rows === 0) {
            echo "<p>Film non trouvé.</p>";
            echo "<a href='ajout_film.php'>Ajouter un nouveau film</a>";
        } else {
            grilleFilms($result);
        }
    }

    // Films les plus populaires (ceux qui ont le plus d'avis)
    $populaires = $bdd->query("SELECT films.* FROM films
                               JOIN commentaires ON films.id = commentaires.id_film
                               WHERE films.cache = 0
                               GROUP BY films.id
                               ORDER BY COUNT(commentaires.id) DESC
                               LIMIT 5");
    if ($populaires && $populaires->num_rows > 0) {
        echo "<h2>Films les plus populaires du moment</h2>";
        grilleFilms($populaires);
    }

    // Tous les films
    echo "<h2>Tous les films</h2>";
    $tous = $bdd->query("SELECT * FROM films WHERE cache = 0 ORDER BY titre");
    if ($tous && $tous->num_rows > 0) {
        grilleFilms($tous);
    } else {
        echo "<p>Aucun film pour le moment. <a href='ajout_film.php'>Ajouter un film</a></p>";
    }
    ?>

    </main>

</body>
</html>
