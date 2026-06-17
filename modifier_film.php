<?php
session_start();
require "bdd.php";
require "fonctions.php";

// Il faut être connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}

$id_film = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Récupération du film
$film = null;
$stmt = $bdd->prepare("SELECT * FROM films WHERE id = ?");
if ($stmt) {
    $stmt->bind_param("i", $id_film);
    $stmt->execute();
    $film = $stmt->get_result()->fetch_assoc();
}

// Le film doit exister, et l'utilisateur doit en être l'auteur OU être administrateur
if (!$film) {
    header("Location: films.php");
    exit();
}

$admin = estAdmin($bdd);
$estProprietaire = (int) ($film['id_utilisateur'] ?? 0) !== 0
    && (int) ($film['id_utilisateur'] ?? 0) === (int) $_SESSION['user_id'];

if (!$estProprietaire && !$admin) {
    header("Location: films.php");
    exit();
}

// Enregistrement des modifications
if (isset($_POST['enregistrer'])) {
    $cache = isset($_POST['cache']) ? 1 : 0;

    // Nouveau fichier envoyé ? sinon on garde l'URL du champ (qui contient déjà l'affiche actuelle)
    $image = gererUpload('image_fichier');
    if ($image === "") {
        $image = trim($_POST['image'] ?? "");
    }

    if ($admin) {
        $stmt = $bdd->prepare("UPDATE films SET titre = ?, description = ?, annee = ?, genre = ?, image = ?, cache = ? WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param("sssssii", $_POST['titre'], $_POST['description'], $_POST['annee'], $_POST['genre'], $image, $cache, $id_film);
        }
    } else {
        $stmt = $bdd->prepare("UPDATE films SET titre = ?, description = ?, annee = ?, genre = ?, image = ?, cache = ? WHERE id = ? AND id_utilisateur = ?");
        if ($stmt) {
            $stmt->bind_param("sssssiii", $_POST['titre'], $_POST['description'], $_POST['annee'], $_POST['genre'], $image, $cache, $id_film, $_SESSION['user_id']);
        }
    }

    if ($stmt) {
        $stmt->execute();
    }

    header("Location: film.php?id=" . $id_film);
    exit();
}

$titre = "Modifier le film";
require "header.php";
?>

    <main>

        <form method="POST" enctype="multipart/form-data">
            <label for="titre">Titre :</label>
            <input type="text" name="titre" id="titre" value="<?php echo htmlspecialchars($film['titre']); ?>" required><br>

            <label for="description">Description :</label>
            <textarea name="description" id="description" required><?php echo htmlspecialchars($film['description'] ?? ""); ?></textarea><br>

            <label for="genre">Genre :</label>
            <input type="text" name="genre" id="genre" value="<?php echo htmlspecialchars($film['genre'] ?? ""); ?>" required><br>

            <label for="annee">Année :</label>
            <input type="number" name="annee" id="annee" value="<?php echo htmlspecialchars($film['annee'] ?? ""); ?>" required><br>

            <label for="image_fichier">Changer l'affiche (fichier depuis ton ordinateur) :</label>
            <input type="file" name="image_fichier" id="image_fichier" accept="image/*" style="color: var(--white);"><br>

            <label for="image">ou URL de l'image (optionnel) :</label>
            <input type="text" name="image" id="image" value="<?php echo htmlspecialchars($film['image'] ?? ""); ?>"><br>

            <label class="case-secrete"><input type="checkbox" name="cache" value="1" <?php echo ((int) $film['cache'] === 1) ? 'checked' : ''; ?>> Film secret (visible uniquement dans la zone cachée)</label>

            <input type="submit" name="enregistrer" value="Enregistrer les modifications">
        </form>

    </main>

</body>
</html>
