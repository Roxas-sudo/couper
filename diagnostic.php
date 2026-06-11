<?php
// ============================================================
// PAGE TEMPORAIRE DE DIAGNOSTIC — à supprimer une fois réglé !
// Ouvre ton-site.xxx/diagnostic.php dans le navigateur
// ============================================================
error_reporting(E_ALL);
ini_set('display_errors', 1);
mysqli_report(MYSQLI_REPORT_OFF);

echo "<!DOCTYPE html><html lang='fr'><head><meta charset='UTF-8'><title>Diagnostic</title></head>";
echo "<body style='font-family:sans-serif;background:#111;color:#eee;padding:2rem;'>";
echo "<h1>Diagnostic Coupez !</h1>";

// --- PHP ---
echo "<h2>1. PHP</h2>";
echo "<p>Version PHP : <b>" . phpversion() . "</b></p>";
echo "<p>Extension mysqli : " . (extension_loaded('mysqli') ? "✅ OK" : "❌ MANQUANTE") . "</p>";
echo "<p>Sessions : " . (function_exists('session_start') ? "✅ OK" : "❌ MANQUANTES") . "</p>";

// --- Fichiers ---
echo "<h2>2. Fichiers présents sur le serveur</h2><ul>";
$fichiers = array(
    "index.php", "connexion.php", "inscription.php", "profil.php",
    "deconnexion.php", "bdd.php", "header.php", "style.css",
    "img/avatar1.svg", "img/avatar2.svg", "img/avatar3.svg", "img/avatar4.svg"
);
foreach ($fichiers as $f) {
    echo "<li>" . $f . " : " . (file_exists(__DIR__ . "/" . $f) ? "✅ présent" : "❌ <b>MANQUANT</b>") . "</li>";
}
echo "</ul>";
echo "<p>Dossier actuel : " . __DIR__ . "</p>";

// --- Base de données ---
echo "<h2>3. Base de données</h2>";
$bdd = @new mysqli("sql107.infinityfree.com", "if0_42137935", "3bKq6saFJt4PuM5", "if0_42137935_coupez");

if ($bdd->connect_error) {
    echo "<p>❌ Connexion impossible : <b>" . $bdd->connect_error . "</b></p>";
} else {
    echo "<p>✅ Connexion à la base OK</p>";

    $res = $bdd->query("SELECT COUNT(*) AS nb FROM utilisateurs");
    if ($res) {
        $ligne = $res->fetch_assoc();
        echo "<p>✅ Table utilisateurs : " . $ligne['nb'] . " comptes</p>";
    } else {
        echo "<p>❌ Table utilisateurs introuvable : " . $bdd->error . "</p>";
    }

    $col = $bdd->query("SHOW COLUMNS FROM utilisateurs LIKE 'avatar'");
    if ($col && $col->num_rows > 0) {
        echo "<p>✅ Colonne avatar : présente</p>";
    } else {
        echo "<p>⏳ Colonne avatar absente, tentative de création...</p>";
        if ($bdd->query("ALTER TABLE utilisateurs ADD COLUMN IF NOT EXISTS avatar TINYINT NOT NULL DEFAULT 1")) {
            echo "<p>✅ Colonne avatar créée !</p>";
        } else {
            echo "<p>❌ ALTER refusé : <b>" . $bdd->error . "</b> (pas grave, le site a un plan B)</p>";
        }
    }
}

// --- Inclusion du header ---
echo "<h2>4. Test d'inclusion de header.php</h2>";
if (file_exists(__DIR__ . "/header.php")) {
    echo "<p>Le header va s'afficher ci-dessous. S'il apparaît, tout est bon :</p><hr>";
    include __DIR__ . "/header.php";
    echo "</body></html>";
} else {
    echo "<p>❌ header.php est manquant : c'est probablement LA cause du problème (toutes les pages en ont besoin).</p>";
    echo "</body></html>";
}
