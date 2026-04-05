<?php
    session_start();

    $fichier = __DIR__ . "/data/compte.json";

    if (!is_dir(__DIR__ . "/data")) {
        mkdir(__DIR__ . "/data", 0777, true);
    }

    if(!file_exists($fichier)){
        header("Location: inscription.php");
        exit;
    }

    $json = file_get_contents($fichier);
    $array = json_decode($json, true) ?? [];

    $email = $_POST["email"];
    $password = $_POST["password"];

    $utilisateurTrouve = false; // Variable pour suivre si l'utilisateur a été trouvé
    $motDePasseCorrect = false; // Variable pour suivre si le mot de passe est correct

    foreach($array as $utilisateur){
        if($utilisateur["email"] === $email){ // Vérification de l'email
            $utilisateurTrouve = true;

            if(password_verify($password, $utilisateur["motdepasse"])){ // Vérification du mot de passe
                $motDePasseCorrect = true;

                if(($utilisateur["bloque"] ?? false) === true){ // Vérification si le compte est bloqué
                    $_SESSION["erreur2"] = "Compte bloqué par l'administrateur.";
                    header("Location: connexion.php");
                    exit;
                }

                $_SESSION["nom"] = $utilisateur["nom"]; // Stockage des informations de l'utilisateur dans la session
                $_SESSION["prenom"] = $utilisateur["prenom"];
                $_SESSION["email"] = strtolower(trim($utilisateur["email"]));
                $_SESSION["telephone"] = $utilisateur["telephone"];
                $_SESSION["adresse"] = $utilisateur["adresse"];
                $_SESSION["infos"] = $utilisateur["infos"];
                $_SESSION["statut"] = $utilisateur["statut"];
                $_SESSION["bloque"] = $utilisateur["bloque"] ?? false;

                if($utilisateur["statut"] === "Admin"){ // Redirection en fonction du statut de l'utilisateur
                    header("Location: Admin.php");
                }
                elseif($utilisateur["statut"] === "Restaurateur"){ // Redirection en fonction du statut de l'utilisateur
                    header("Location: commandes.php");
                }
                else{ // Redirection en fonction du statut de l'utilisateur
                    header("Location: profil.php"); 
                }
                exit;
            }

            break;
        }
    }

    if(!$utilisateurTrouve || !$motDePasseCorrect){ // Si l'utilisateur n'est pas trouvé ou si le mot de passe est incorrect, afficher un message d'erreur
        $_SESSION["erreur2"] = "Email ou mot de passe incorrect.";
    }

    header("Location: connexion.php");
    exit;
?>