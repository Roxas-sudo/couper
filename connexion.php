<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Coupez ! - Page d'accueil</title>
  <link rel="stylesheet" href="/style.css">
</head>
<body>
    <header>
        <h1><a href="/">Coupez !</a></h1>
        <nav>  
            <a href="/films">Films</a>
            <a href="/connexion">Connexion</a>
        </nav>
    </header>
    
    <main>

<?php
        function insertUser($bdd,$username,$email,$mot_de_passe)
		{
			//requete
			$insert = "insert into utilisateur values(?,?,?)";

			//preparer la requete
			$stmt = $bdd->prepare($insert);
			$stmt->bind_param("ss", $username, $email, $mot_de_passe);
			//executer la requete
			$stmt->execute();

			header("Location: http://localhost/couper/index.php");

		}
        
?>

        <form method="post" enctype="multipart/form-data">
    
		<input type="text" name="username" placeholder="Nom d'utilisateur"><br>
		<input type="email" name="email" placeholder="Adresse email"><br>
		<input type="password" name="mot_de_passe" placeholder="Mot de passe"><br>

		<input type="submit" name="charger" value="Charger">
		
        </form>

    </main>