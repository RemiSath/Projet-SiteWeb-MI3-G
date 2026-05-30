<?php

    if(session_status() === PHP_SESSION_NONE){
        session_start();
    }
    if(!isset($_SESSION["email"]) || empty($_SESSION["email"])){
        return;
    }

    $fichier = __DIR__ . "/../data/compte.json";
    if(!file_exists($fichier)){
        return;
    }

    $json = file_get_contents($fichier);
    $comptes = json_decode($json, true) ?? [];

    foreach($comptes as $utilisateur){
        if(isset($utilisateur["email"]) && strtolower(trim($utilisateur["email"])) === strtolower(trim($_SESSION["email"]))){
            if(($utilisateur["bloque"] ?? false) === true){
                session_unset();
                session_destroy();
                session_start();
                $_SESSION = [];
                $_SESSION["erreurConnexion"] = "Compte bloqué par l'administrateur.";
                header("Location: connexion.php");
                exit;
            }
            break;
        }
    }
?>