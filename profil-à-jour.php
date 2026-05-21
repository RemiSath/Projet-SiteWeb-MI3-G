<?php
    session_start();

    if(!isset($_SESSION["email"])){
        echo "Utilisateur non connecté, veuillez vous connecter.";
        exit;
    }

    $fichier = __DIR__ . "/data/compte.json";

    if(!is_dir(__DIR__ . "/data")){
        mkdir(__DIR__ . "/data", 0777, true);
    }

    if(!file_exists($fichier)){
        header("Location: inscription.php");
        exit;
    }

    $json = file_get_contents($fichier);
    $utilisateurs = json_decode($json, true) ?? [];

    $emailSession = strtolower(trim($_SESSION["email"]));

    $nom = trim($_POST["nom"] ?? "");
    $prenom = trim($_POST["prenom"] ?? "");
    $telephone = trim($_POST["telephone"] ?? "");
    $adresse = trim($_POST["adresse"] ?? "");
    $infos = trim($_POST["infos"] ?? "");

    $motdepasse = trim($_POST["motdepasse"] ?? "");
    $confirmation = trim($_POST["confirmation"] ?? "");

    $utilisateurTrouve = false;

    foreach($utilisateurs as &$utilisateur){

        if(
            isset($utilisateur["email"]) &&
            strtolower(trim($utilisateur["email"])) === $emailSession
        ){

            $utilisateur["nom"] = $nom;
            $utilisateur["prenom"] = $prenom;
            $utilisateur["telephone"] = $telephone;
            $utilisateur["adresse"] = $adresse;
            $utilisateur["infos"] = $infos;


            if(!empty($motdepasse) || !empty($confirmation)){

                if($motdepasse !== $confirmation){
                    echo "Le mot de passe et sa confirmation ne correspondent pas.";
                    exit;
                }

                if(strlen($motdepasse) < 6){
                    echo "Le mot de passe doit contenir au moins 6 caractères.";
                    exit;
                }

                $utilisateur["motdepasse"] = password_hash($motdepasse, PASSWORD_DEFAULT);
            }

            $_SESSION["nom"] = $nom;
            $_SESSION["prenom"] = $prenom;
            $_SESSION["telephone"] = $telephone;
            $_SESSION["adresse"] = $adresse;
            $_SESSION["infos"] = $infos;

            $utilisateurTrouve = true;
            break;
        }
    }

    if(!$utilisateurTrouve){
        echo "Utilisateur introuvable.";
        exit;
    }

    $resultat = file_put_contents(
        $fichier,
        json_encode($utilisateurs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
    );

    if($resultat === false){
        echo "Erreur lors de la sauvegarde.";
        exit;
    }

    echo "Profil mis à jour avec succès.";
?>