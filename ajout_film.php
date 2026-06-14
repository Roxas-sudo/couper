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

    if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
    }

     function insertFilm ($bdd,$titre,$description, $annee, $genre, $image)
		{
			//requete
			$insert = "INSERT INTO films (titre, description, annee, genre, image) VALUES (?,?,?,?,?)";

			//preparer la requete
			$stmt = $bdd->prepare($insert);
			$stmt->bind_param("sssss", $titre, $description, $annee, $genre, $image);
			//executer la requete
			$stmt->execute();

			header("Location: http://localhost/couper/index.php");

		}
    
        if (isset($_POST['charger'])) {
        insertFilm($bdd, $_POST['titre'], $_POST['description'], $_POST['annee'], $_POST['genre'], $_POST['image']);

        }

?>
        <form method="POST">
            <label for="titre">Titre :</label>
            <input type="text" name="titre" id="titre" required><br>

            <label for="description">Description :</label>
            <textarea name="description" id="description" required></textarea><br>

            <label for="genre">Genre :</label>
            <input type="text" name="genre" id="genre" required><br>

            <label for="annee">Année :</label>
            <input type="number" name="annee" id="annee" required><br>

            <label for="image">Image (URL) :</label>
            <input type="text" name="image" id="image" required><br>

            <input type="submit" name="charger" value="Ajouter le film">
        </form>

    </main>