<?php
session_start();

// On vide et on détruit la session, puis retour à l'accueil
$_SESSION = array();
session_destroy();

header("Location: index.php");
exit;
