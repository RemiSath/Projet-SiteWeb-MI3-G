<?php
    session_start();

    $fichier = "compte.json";

    if(!file_exists($fichier)){
        header("Location: connexion.php");
        exit;
    }

    $json = file_get_contents($fichier);
    $array = json_decode($json, true) ?? [];

    $email = $_POST["email"];
    $password = $_POST["password"];

    $utilisateurTrouve = false;

    foreach ($array as $utilisateur) {
        if($utilisateur["email"] === $email){
            if(password_verify($password, $utilisateur["motdepasse"])){

                $_SESSION["nom"] = $utilisateur["nom"];
                $_SESSION["prenom"] = $utilisateur["prenom"];
                $_SESSION["email"] = $utilisateur["email"];
                $_SESSION["telephone"] = $utilisateur["telephone"];
                $_SESSION["adresse"] = $utilisateur["adresse"];
                $_SESSION["infos"] = $utilisateur["infos"];

                header("Location: profil.php");
                exit;
            }
            $utilisateurTrouve = true;
            break;
        }
    }

    if($utilisateurTrouve){
        $_SESSION["erreur2"] = "Email ou mot de passe incorrect.";
    }

    header("Location: connexion.php");
    exit;
?>