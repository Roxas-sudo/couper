<?php

// Connexion à la base de données (fichier commun, inclus dans chaque page)

// PHP 8 transforme les erreurs SQL en plantage fatal : on désactive ce mode strict
mysqli_report(MYSQLI_REPORT_OFF);

$bdd = @new mysqli(
    "sql107.infinityfree.com",
    "if0_42137935",
    "3bKq6saFJt4PuM5",
    "if0_42137935_coupez"
);

if ($bdd->connect_error) {
    die("Erreur de connexion à la base de données : " . $bdd->connect_error);
}

$bdd->set_charset("utf8mb4");

// Crée la colonne avatar au premier passage (ne fait rien si elle existe déjà,
// et le site continue de fonctionner même si la requête est refusée)
$bdd->query("ALTER TABLE utilisateurs ADD COLUMN IF NOT EXISTS avatar TINYINT NOT NULL DEFAULT 1");
