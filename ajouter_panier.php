<?php
session_start();

if (!isset($_SESSION["panier"])) {
    $_SESSION["panier"] = [];
}

$nom = trim($_POST["nom"] ?? "");
$prix = floatval($_POST["prix"] ?? 0);

if ($nom !== "") {
    if (isset($_SESSION["panier"][$nom])) {
        $_SESSION["panier"][$nom]["quantite"]++;
    } else {
        $_SESSION["panier"][$nom] = [
            "nom" => $nom,
            "prix" => $prix,
            "quantite" => 1
        ];
    }
}

header("Location: Paris.php");
exit();