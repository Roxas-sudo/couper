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
