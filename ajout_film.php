<?php
session_start();
require "bdd.php";
require "fonctions.php";

// Il faut être connecté pour ajouter un film
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}

function insertFilm($bdd, $titre, $description, $annee, $genre, $image, $cache, $id_utilisateur) {
    $insert = "INSERT INTO films (titre, description, annee, genre, image, cache, id_utilisateur) VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $bdd->prepare($insert);
    if ($stmt) {
        $stmt->bind_param("sssssii", $titre, $description, $annee, $genre, $image, $cache, $id_utilisateur);
        $stmt->execute();
    }

    header("Location: films.php");
    exit();
}

if (isset($_POST['charger'])) {
    $cache = isset($_POST['cache']) ? 1 : 0;

    // Priorité au fichier envoyé ; sinon on prend l'URL éventuellement saisie
    $image = gererUpload('image_fichier');
    if ($image === "") {
        $image = trim($_POST['image'] ?? "");
    }

    insertFilm($bdd, $_POST['titre'], $_POST['description'], $_POST['annee'], $_POST['genre'], $image, $cache, $_SESSION['user_id']);
}

$titre = "Ajouter un film";
require "header.php";
?>

    <main>

        <form method="POST" enctype="multipart/form-data">
            <label for="titre">Titre :</label>
            <input type="text" name="titre" id="titre" required><br>

            <label for="description">Description :</label>
            <textarea name="description" id="description" required></textarea><br>

            <label for="genre">Genre :</label>
            <input type="text" name="genre" id="genre" required><br>

            <label for="annee">Année :</label>
            <input type="number" name="annee" id="annee" required><br>

            <label for="image_fichier">Affiche (fichier depuis ton ordinateur) :</label>
            <input type="file" name="image_fichier" id="image_fichier" accept="image/*" style="color: var(--white);"><br>

            <label for="image">ou URL de l'image (optionnel) :</label>
            <input type="text" name="image" id="image"><br>

            <label class="case-secrete"><input type="checkbox" name="cache" value="1"> Film secret (visible uniquement dans la zone cachée)</label>

            <input type="submit" name="charger" value="Ajouter le film">
        </form>

    </main>

</body>
</html>
