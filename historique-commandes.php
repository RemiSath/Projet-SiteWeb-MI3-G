<?php
    session_start();

    $fichier = "compte.json";
    $fichierCommandes = "commandes.json";

    if(!file_exists($fichier)){
        header("Location: inscription.php");
        exit;
    }
    elseif(!file_exists($fichierCommandes)){
        exit;
    }

    $json = file_get_contents($fichier);
    $ultilisateurs = json_decode($json, true) ?? [];

    $jsonCommandes = file_get_contents($fichierCommandes);
    $commandes = json_decode($jsonCommandes, true) ?? [];

    $email = $_POST["email"];

    $utilisateurTrouve = false;

    foreach($utilisateurs as $utilisateur){
        if($utilisateur["email"] === $email){
            $utilisateurTrouve = true;

            $_SESSION["nom"] = $utilisateur["nom"];
            $_SESSION["prenom"] = $utilisateur["prenom"];
            $_SESSION["email"] = $utilisateur["email"];
            $_SESSION["telephone"] = $utilisateur["telephone"];
            $_SESSION["adresse"] = $utilisateur["adresse"];
            $_SESSION["infos"] = $utilisateur["infos"];
            $_SESSION["statut"] = $utilisateur["statut"];
            $_SESSION["bloque"] = $utilisateur["bloque"] ?? false;
            break;
        }
    }

    header("Location: connexion.php");
    exit;
?>