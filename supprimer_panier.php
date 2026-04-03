<?php
session_start();

if (!isset($_SESSION["panier"])) {
    $_SESSION["panier"] = [];
}

$nom = trim($_POST["nom"] ?? "");

if ($nom !== "" && isset($_SESSION["panier"][$nom])) {
    $_SESSION["panier"][$nom]["quantite"]--;
    if ($_SESSION["panier"][$nom]["quantite"] <= 0) {
        unset($_SESSION["panier"][$nom]);
    }
}

header("Location: commander.php");
exit();
?>