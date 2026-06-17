<?php
// Fonctions partagées pour l'affichage des films (utilisées par films.php, film.php, secret.php)

// Trouve l'affiche d'un film : l'URL en base si elle existe, sinon un fichier local
// nommé d'après le titre (ex : "Parasite" -> Parasite.jpg, "Le Village" -> levillage.jpg)
function afficheFilm($film) {
    $img = trim($film['image'] ?? "");
    if ($img !== "") {
        return $img;
    }

    // On cherche un fichier local d'après le titre, en testant plusieurs
    // graphies (titre exact / normalisé) et extensions courantes
    $noms = array(
        $film['titre'],
        str_replace(" ", "", strtolower($film['titre'])),
    );
    $extensions = array(".jpg", ".jpeg", ".png", ".webp");

    foreach ($noms as $nom) {
        foreach ($extensions as $ext) {
            if (file_exists(__DIR__ . "/" . $nom . $ext)) {
                return $nom . $ext;
            }
        }
    }

    return "";
}

// Affiche une grille de cartes cliquables menant vers la fiche du film
function grilleFilms($result) {
    echo "<div class='films-grille'>";
    while ($film = $result->fetch_assoc()) {
        $affiche = afficheFilm($film);
        echo "<a class='carte-film' href='film.php?id=" . $film['id'] . "'>";
        if ($affiche !== "") {
            echo "<img src='" . htmlspecialchars($affiche) . "' alt='Affiche de " . htmlspecialchars($film['titre']) . "'>";
        } else {
            echo "<span class='sans-affiche'>Pas d'affiche</span>";
        }
        $meta = trim((string) ($film['annee'] ?? ""));
        if (!empty($film['genre'])) {
            $meta = ($meta !== "" ? $meta . " · " : "") . $film['genre'];
        }
        echo "<div class='carte-infos'>";
        echo "<h3>" . htmlspecialchars($film['titre']) . "</h3>";
        if ($meta !== "") {
            echo "<p class='carte-meta'>" . htmlspecialchars($meta) . "</p>";
        }
        echo "</div>";
        echo "</a>";
    }
    echo "</div>";
}

// Renvoie true si l'utilisateur connecté est un administrateur (rôle 'admin')
function estAdmin($bdd) {
    if (!isset($_SESSION['user_id'])) {
        return false;
    }

    $stmt = $bdd->prepare("SELECT role FROM utilisateurs WHERE id = ?");
    if (!$stmt) {
        return false;
    }

    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();

    return $row && $row['role'] === 'admin';
}

// Gère l'envoi d'un fichier image (affiche de film). Renvoie le chemin enregistré
// (ex : "affiches/film_xxx.jpg") ou "" si aucun fichier valide n'a été envoyé.
function gererUpload($champ) {
    if (!isset($_FILES[$champ]) || $_FILES[$champ]['error'] !== UPLOAD_ERR_OK) {
        return "";
    }

    // On s'assure que le fichier est bien une image
    if (getimagesize($_FILES[$champ]['tmp_name']) === false) {
        return "";
    }

    $extensions = array('jpg', 'jpeg', 'png', 'webp', 'gif');
    $ext = strtolower(pathinfo($_FILES[$champ]['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $extensions)) {
        return "";
    }

    // Dossier de destination (créé au besoin)
    $dossier = "affiches/";
    if (!is_dir(__DIR__ . "/" . $dossier)) {
        @mkdir(__DIR__ . "/" . $dossier, 0755, true);
    }

    $nom = $dossier . uniqid("film_") . "." . $ext;
    if (move_uploaded_file($_FILES[$champ]['tmp_name'], __DIR__ . "/" . $nom)) {
        return $nom;
    }

    return "";
}
