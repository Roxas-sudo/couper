<?php if (session_status() === PHP_SESSION_NONE) { session_start(); } ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Coupez ! - <?php echo isset($titre) ? $titre : "Accueil"; ?></title>
  <link rel="stylesheet" href="style.css?v=2">
</head>
<body>
    <header>
        <h1><a href="index.php">Coupez !</a></h1>
        <nav>
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
    $bdd = new mysqli(
        "sql107.infinityfree.com",
        "if0_42137935",
        "3bKq6saFJt4PuM5",
        "if0_42137935_coupez");

    echo "<form method='get'>
        <input type='text' name='recherche' placeholder='Rechercher un film...'>
        <input type='submit' value='recherche'>
    </form>";

    if (isset($_GET['recherche'])) {
        $recherche = $_GET['recherche'];
        $stmt = $bdd->prepare("SELECT * FROM films WHERE titre LIKE (?)");
        $motif = "%" . $recherche . "%";
        $stmt->bind_param("s", $motif);
        $stmt->execute();
        $result = $stmt->get_result();

        echo "<h2>Résultats de la recherche pour '$recherche'</h2>";

       if ($result->num_rows === 0) {
    echo "<p>Film non trouvé.</p>";
    echo "<a href='ajout_film.php'>Ajouter un nouveau film</a>";
    } else {
    while ($film = $result->fetch_assoc()) {
        echo "<a href='film.php?id=" . $film['id'] . "'>";
        echo "<div class='film'>";
        echo "<h3>" . htmlspecialchars($film['titre']) . "</h3>";
        echo "<img src='" . htmlspecialchars($film['image']) . "'>";
        echo "</div>";
        echo "</a>";
    }
    }

