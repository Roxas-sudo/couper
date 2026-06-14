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

        function loginUser($bdd, $email, $mot_de_passe) {
            $select = "SELECT * FROM utilisateurs WHERE email = ?";
            $stmt = $bdd->prepare($select);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                $hash = hash('sha256', $mot_de_passe);
                if ($hash === $user['mot_de_passe']) {
                    $_SESSION['user_id'] = $user['id'];
                    header("Location: index.php");
                    exit();
                } else {
                    echo "Mot de passe incorrect.";
                }
            } else {
                    echo 'Email non trouvé.' . ' <a href="inscription.php">Créer un compte</a>';
                }
        }

    if (isset($_POST['se_connecter'])) {
        loginUser($bdd, $_POST['email'], $_POST['password']);
    }
    ?>
    <form method="POST">
        <label for="email">Email :</label>
        <input type="email" name="email" id="email" required><br>

        <label for="password">Mot de passe :</label>
        <input type="password" name="password" id="password" required><br>

        <input type="submit" name="se_connecter" value="Se connecter">
    </form>


    
