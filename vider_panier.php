<?php
session_start();

unset($_SESSION["panier"]); // Suppression du panier de la session pour le vider après la validation de la commande

header("Location: commander.php");
exit();