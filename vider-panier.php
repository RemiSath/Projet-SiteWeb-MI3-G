<?php
    session_start();

    if($_SERVER["REQUEST_METHOD"] !== "POST"){
        header("Location: commander.php");
        exit();
    }

    unset($_SESSION["panier"]); // Suppression du panier de la session pour le vider après la validation de la commande

    header("Location: commander.php");
    exit();
?>
