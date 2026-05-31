<?php
session_start();

if (!isset($_SESSION["email"])) {
    header("Location: connexion.php");
    exit();
}

header("Location: profil.php");
exit();
?>
