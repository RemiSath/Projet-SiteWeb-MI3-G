<?php
    session_start();

    if(!isset($_SESSION["panier"])){ // Initialiser le panier s'il n'existe pas
        $_SESSION["panier"] = [];
    }

    $nom = trim($_POST["nom"] ?? "");
    $prix = floatval($_POST["prix"] ?? 0);

    if($nom !== ""){ // Vérifier que le nom n'est pas vide
        if(isset($_SESSION["panier"][$nom])){ // Si le produit existe déjà dans le panier, augmenter la quantité
            $_SESSION["panier"][$nom]["quantite"]++;
        } 
        else{
            $_SESSION["panier"][$nom] = [
                "nom" => $nom,
                "prix" => $prix,
                "quantite" => 1
            ];
        }
    }

    header("Location: Paris.php");
    exit();
?>