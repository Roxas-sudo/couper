<?php
session_start();
require "bdd.php";

// Si l'utilisateur n'est pas connecté, on le renvoie vers la page de connexion
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit;
}

// Changement de photo de profil
if (isset($_POST['avatar'])) {
    $avatar = (int) $_POST['avatar'];

    if ($avatar >= 1 && $avatar <= 4) {
        $stmt = $bdd->prepare("UPDATE utilisateurs SET avatar = ? WHERE id = ?");

        if ($stmt) {
            $stmt->bind_param("ii", $avatar, $_SESSION['user_id']);
            $stmt->execute();
        }

        $_SESSION['avatar'] = $avatar;
    }
}

// Suppression du compte
if (isset($_POST['supprimer_compte'])) {
    $stmt = $bdd->prepare("DELETE FROM utilisateurs WHERE id = ?");

    if ($stmt) {
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
    }

    // On vide et on détruit la session, puis retour à l'accueil
    $_SESSION = array();
    session_destroy();

    header("Location: index.php");
    exit;
}

$avatar_actuel = isset($_SESSION['avatar']) ? (int) $_SESSION['avatar'] : 1;
if ($avatar_actuel < 1 || $avatar_actuel > 4) {
    $avatar_actuel = 1;
}

$titre = "Profil";
require "header.php";
?>

    <main>

        <p class="message succes">✅ Connexion réussie</p>

        <section class="profil">

            <img class="avatar-actuel" src="img/avatar<?php echo $avatar_actuel; ?>.svg" alt="Photo de profil">

            <h2>Bienvenue, <?php echo htmlspecialchars($_SESSION['username']); ?> !</h2>

            <a class="btn-deconnexion" href="deconnexion.php">Déconnexion</a>

            <h3>Choisis ta photo de profil</h3>

            <form method="post" class="choix-avatars">
                <?php for ($i = 1; $i <= 4; $i++) { ?>
                    <button type="submit" name="avatar" value="<?php echo $i; ?>" class="<?php echo ($i == $avatar_actuel) ? 'choisi' : ''; ?>" title="Avatar <?php echo $i; ?>">
                        <img src="img/avatar<?php echo $i; ?>.svg" alt="Avatar <?php echo $i; ?>">
                    </button>
                <?php } ?>
            </form>

            <form method="post" onsubmit="return confirm('Supprimer définitivement ton compte ? Cette action est irréversible.');">
                <button type="submit" name="supprimer_compte" class="btn-deconnexion">Supprimer mon compte</button>
            </form>

        </section>

    </main>

</body>
</html>
