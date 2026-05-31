<?php
    session_start();

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        header("Location: commander.php");
        exit();
    }

    if(!isset($_SESSION["panier"])){
        $_SESSION["panier"] = [];
    }

    $nom = trim($_POST["nom"] ?? "");

    if($nom !== "" && isset($_SESSION["panier"][$nom])){ // Vérification que le nom du plat est fourni et existe dans le panier
        $_SESSION["panier"][$nom]["quantite"]--;
        if($_SESSION["panier"][$nom]["quantite"] <= 0){
            unset($_SESSION["panier"][$nom]);
        }
    }

    header("Location: commander.php");
    exit();
?>
