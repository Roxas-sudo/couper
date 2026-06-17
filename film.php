<?php
session_start();
require "bdd.php";
require "fonctions.php";

// Filet de sécurité : crée la table commentaires si elle n'existe pas, et ajoute
// la colonne note (étoiles) au premier passage. Ne fait rien si déjà présent.
$bdd->query("CREATE TABLE IF NOT EXISTS commentaires (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_utilisateur INT(11) NOT NULL,
    id_film INT(11) NOT NULL,
    contenu TEXT NOT NULL,
    note TINYINT NOT NULL DEFAULT 0,
    created_at DATETIME DEFAULT current_timestamp()
)");
$bdd->query("ALTER TABLE commentaires ADD COLUMN IF NOT EXISTS note TINYINT NOT NULL DEFAULT 0");

// Film concerné
$id_film = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Ajout d'un avis (note + commentaire) — réservé aux utilisateurs connectés
if (isset($_POST['ajouter_commentaire']) && isset($_SESSION['user_id'])) {
    $contenu = trim($_POST['contenu']);
    $note = (int) $_POST['note'];

    if ($contenu !== "" && $note >= 1 && $note <= 5) {
        $stmt = $bdd->prepare("INSERT INTO commentaires (id_utilisateur, id_film, contenu, note) VALUES (?, ?, ?, ?)");

        if ($stmt) {
            $stmt->bind_param("iisi", $_SESSION['user_id'], $id_film, $contenu, $note);
            $stmt->execute();
        }
    }

    header("Location: film.php?id=" . $id_film);
    exit;
}

// Suppression d'un commentaire — uniquement le sien (id_utilisateur = utilisateur connecté)
if (isset($_POST['supprimer_commentaire']) && isset($_SESSION['user_id'])) {
    $id_commentaire = (int) $_POST['id_commentaire'];

    $stmt = $bdd->prepare("DELETE FROM commentaires WHERE id = ? AND id_utilisateur = ?");

    if ($stmt) {
        $stmt->bind_param("ii", $id_commentaire, $_SESSION['user_id']);
        $stmt->execute();
    }

    header("Location: film.php?id=" . $id_film);
    exit;
}

// Suppression du film — réservé à l'auteur du film ou à un administrateur
if (isset($_POST['supprimer_film']) && isset($_SESSION['user_id'])) {
    if (estAdmin($bdd)) {
        $stmt = $bdd->prepare("DELETE FROM films WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param("i", $id_film);
        }
    } else {
        $stmt = $bdd->prepare("DELETE FROM films WHERE id = ? AND id_utilisateur = ?");
        if ($stmt) {
            $stmt->bind_param("ii", $id_film, $_SESSION['user_id']);
        }
    }

    if ($stmt) {
        $stmt->execute();

        // Si le film a bien été supprimé, on enlève aussi ses commentaires
        if ($stmt->affected_rows > 0) {
            $del = $bdd->prepare("DELETE FROM commentaires WHERE id_film = ?");
            if ($del) {
                $del->bind_param("i", $id_film);
                $del->execute();
            }
        }
    }

    header("Location: films.php");
    exit;
}

// Récupération du film
$film = null;
$stmt = $bdd->prepare("SELECT * FROM films WHERE id = ?");
if ($stmt) {
    $stmt->bind_param("i", $id_film);
    $stmt->execute();
    $film = $stmt->get_result()->fetch_assoc();
}

// Récupération des commentaires du film (avec l'auteur et son avatar)
$commentaires = null;
if ($film) {
    $stmt = $bdd->prepare("SELECT commentaires.*, utilisateurs.username, utilisateurs.avatar
                           FROM commentaires
                           JOIN utilisateurs ON commentaires.id_utilisateur = utilisateurs.id
                           WHERE commentaires.id_film = ?
                           ORDER BY commentaires.created_at DESC");
    if ($stmt) {
        $stmt->bind_param("i", $id_film);
        $stmt->execute();
        $commentaires = $stmt->get_result();
    }
}

// Affiche du film (image en base, ou fichier local d'après le titre)
$affiche = $film ? afficheFilm($film) : "";

// L'utilisateur peut-il gérer ce film ? (son auteur, ou un administrateur)
$estProprietaire = $film
    && isset($_SESSION['user_id'])
    && (int) ($film['id_utilisateur'] ?? 0) !== 0
    && (int) ($film['id_utilisateur'] ?? 0) === (int) $_SESSION['user_id'];

$peutGerer = $estProprietaire || ($film && estAdmin($bdd));

$titre = $film ? $film['titre'] : "Film introuvable";
require "header.php";
?>

    <main>

    <?php if (!$film) { ?>

        <p class="message erreur">Film introuvable.</p>
        <p class="lien-form"><a href="films.php">Retour aux films</a></p>

    <?php } else { ?>

        <section class="fiche-film">

            <?php if ($affiche !== "") { ?>
                <img class="affiche" src="<?php echo htmlspecialchars($affiche); ?>" alt="Affiche de <?php echo htmlspecialchars($film['titre']); ?>">
            <?php } ?>

            <div class="infos-film">
                <h2><?php echo htmlspecialchars($film['titre']); ?></h2>
                <p class="meta-film"><?php echo htmlspecialchars($film['annee'] ?? ""); ?> · <?php echo htmlspecialchars($film['genre'] ?? ""); ?></p>
                <p class="description-film"><?php echo htmlspecialchars($film['description'] ?? ""); ?></p>

                <?php if ($peutGerer) { ?>
                    <div class="actions-film">
                        <a class="btn-modifier" href="modifier_film.php?id=<?php echo $id_film; ?>">Modifier</a>
                        <form method="post" onsubmit="return confirm('Supprimer définitivement ce film et tous ses avis ?');">
                            <button type="submit" name="supprimer_film" class="btn-supprimer-film">Supprimer le film</button>
                        </form>
                    </div>
                <?php } ?>
            </div>

        </section>

        <section class="commentaires">

            <h3>Avis des spectateurs</h3>

            <?php if (isset($_SESSION['user_id'])) { ?>

                <form method="post" class="form-avis">

                    <span class="consigne-note">Ta note</span>

                    <div class="etoiles">
                        <input type="radio" name="note" id="note5" value="5" required><label for="note5" title="5 étoiles">★</label>
                        <input type="radio" name="note" id="note4" value="4"><label for="note4" title="4 étoiles">★</label>
                        <input type="radio" name="note" id="note3" value="3"><label for="note3" title="3 étoiles">★</label>
                        <input type="radio" name="note" id="note2" value="2"><label for="note2" title="2 étoiles">★</label>
                        <input type="radio" name="note" id="note1" value="1"><label for="note1" title="1 étoile">★</label>
                    </div>

                    <textarea name="contenu" placeholder="Écris ton avis sur le film..." required></textarea>
                    <input type="submit" name="ajouter_commentaire" value="Publier mon avis">

                </form>

            <?php } else { ?>

                <p class="lien-form"><a href="connexion.php">Connecte-toi</a> pour laisser un avis.</p>

            <?php } ?>

            <?php if ($commentaires && $commentaires->num_rows > 0) { ?>

                <?php while ($c = $commentaires->fetch_assoc()) { ?>

                    <article class="commentaire">

                        <div class="commentaire-tete">
                            <img class="avatar-commentaire" src="img/avatar<?php echo (int) $c['avatar']; ?>.svg" alt="">
                            <span class="commentaire-auteur"><?php echo htmlspecialchars($c['username']); ?></span>
                            <span class="commentaire-note"><?php for ($s = 1; $s <= 5; $s++) { echo $s <= (int) $c['note'] ? "<span class='etoile-on'>★</span>" : "<span class='etoile-off'>★</span>"; } ?></span>
                        </div>

                        <p class="commentaire-texte"><?php echo nl2br(htmlspecialchars($c['contenu'])); ?></p>

                        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $c['id_utilisateur']) { ?>
                            <form method="post" class="form-supprimer" onsubmit="return confirm('Supprimer ce commentaire ?');">
                                <input type="hidden" name="id_commentaire" value="<?php echo (int) $c['id']; ?>">
                                <button type="submit" name="supprimer_commentaire" class="btn-supprimer">Supprimer</button>
                            </form>
                        <?php } ?>

                    </article>

                <?php } ?>

            <?php } else { ?>

                <p class="aucun-avis">Aucun avis pour le moment. Sois le premier !</p>

            <?php } ?>

        </section>

    <?php } ?>

    </main>

</body>
</html>
