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
session_start();

//essaie de mettre cette ligne en commentaire si ça fonctionne pas

$bdd = new mysqli(
	"sql107.infinityfree.com",
    "if0_42137935",
    "3bKq6saFJt4PuM5",
    "if0_42137935_coupez");

        function insertUser($bdd,$username,$email,$mot_de_passe)
		{
			//requete
			$insert = "INSERT INTO utilisateurs (username, email, mot_de_passe) VALUES (?,?,?)";

			//preparer la requete
			$stmt = $bdd->prepare($insert);
			$hash = hash('sha256', $mot_de_passe);
            $stmt->bind_param("sss", $username, $email, $hash);
			//executer la requete
			$stmt->execute();

			header("Location: http://localhost/couper/index.php");

		}
    
        if (isset($_POST['charger'])) {
        insertUser($bdd, $_POST['username'], $_POST['email'], $_POST['mot_de_passe']);
        }
        
?>

        <form method="post" enctype="multipart/form-data">
    
		<input type="text" name="username" placeholder="Nom d'utilisateur">
		<input type="email" name="email" placeholder="Adresse email">
		<input type="password" name="mot_de_passe" placeholder="Mot de passe">

		<input type="submit" name="charger" value="Charger">
		
        </form>

    </main>
