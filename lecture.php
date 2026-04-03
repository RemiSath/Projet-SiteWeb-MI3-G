<?php
    session_start();

    $fichier = "compte.json";

    if(!file_exists($fichier)){
        header("Location: inscription.php");
        exit;
    }

    $json = file_get_contents($fichier);
    $array = json_decode($json, true) ?? [];

    $email = $_POST["email"];
    $password = $_POST["password"];

    $utilisateurTrouve = false;

    foreach($array as $utilisateur){
        if($utilisateur["email"] === $email){
            if(password_verify($password, $utilisateur["motdepasse"])){
                if(($utilisateur["bloque"] ?? false) === true){
                    $_SESSION["erreur2"] = "Compte bloqué par l'administrateur.";
                    header("Location: connexion.php");
                    exit;
                }
                
                $_SESSION["nom"] = $utilisateur["nom"];
                $_SESSION["prenom"] = $utilisateur["prenom"];
                $_SESSION["email"] = $utilisateur["email"];
                $_SESSION["telephone"] = $utilisateur["telephone"];
                $_SESSION["adresse"] = $utilisateur["adresse"];
                $_SESSION["infos"] = $utilisateur["infos"];
                $_SESSION["statut"] = $utilisateur["statut"];
                $_SESSION["bloque"] = $utilisateur["bloque"] ?? false;


                if($utilisateur["statut"] === "admin"){
                    header("Location: Admin.php");
                    exit;
                } 
                else{
                    header("Location: profil.php");
                    exit;
                }
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