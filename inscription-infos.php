<?php
    session_start();

    function ecritureFichier(){

        $fichier = __DIR__ . "/data/compte.json";

        if(!is_dir(__DIR__ . "/data")){
            mkdir(__DIR__ . "/data", 0777, true);
        }

        if(file_exists($fichier)){
            $json = file_get_contents($fichier);
            $utilisateurs = json_decode($json, true) ?? [];
        }

        else{
            $utilisateurs = array();
        }

        $nom = trim($_POST["nom"] ?? "");
        $prenom = trim($_POST["prenom"] ?? "");
        $email = trim($_POST["email"] ?? "");
        $motdepasse = $_POST["motdepasse"] ?? "";
        $adresse = trim($_POST["adresse"] ?? "");
        $telephone = trim($_POST["telephone"] ?? "");
        $infos = trim($_POST["infos"] ?? "");

        if(strlen($nom) < 1){
            $_SESSION["erreur"] = "Nom invalide.";
            header("Location: inscription.php");
            exit;
        }

        if(strlen($prenom) < 1){
            $_SESSION["erreur"] = "Prénom invalide.";
            header("Location: inscription.php");
            exit;
        }

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $_SESSION["erreur"] = "Adresse email invalide.";
            header("Location: inscription.php");
            exit;
        }

        if(strlen($motdepasse) < 6){
            $_SESSION["erreur"] = "Le mot de passe doit contenir au moins 6 caractères.";
            header("Location: inscription.php");
            exit;
        }

        if(!preg_match("/^0[1-9](\s?[0-9]{2}){4}$/", $telephone)){
            $_SESSION["erreur"] = "Numéro de téléphone invalide.";
            header("Location: inscription.php");
            exit;
        }

        if(strlen($adresse) < 5){
            $_SESSION["erreur"] = "Adresse invalide.";
            header("Location: inscription.php");
            exit;
        }

        foreach($utilisateurs as $utilisateur){

            if($utilisateur["email"] === $email){
                $_SESSION["erreur"] = "Cet email est déjà utilisé.";
                header("Location: inscription.php");
                exit;
            }
        }

        $utilisateurs[] = array(
            "id" => uniqid(),
            "nom" => $nom,
            "prenom" => $prenom,
            "email" => $email,
            "motdepasse" => password_hash($motdepasse, PASSWORD_DEFAULT),
            "adresse" => $adresse,
            "telephone" => $telephone,
            "infos" => $infos,
            "statut" => "Client",
            "bloque" => false,
        );

        file_put_contents($fichier, json_encode($utilisateurs, JSON_PRETTY_PRINT));

        $_SESSION["nom"] = $nom;
        $_SESSION["prenom"] = $prenom;
        $_SESSION["email"] = strtolower($email);
        $_SESSION["telephone"] = $telephone;
        $_SESSION["adresse"] = $adresse;
        $_SESSION["infos"] = $infos;
        $_SESSION["statut"] = "Client";
        $_SESSION["bloque"] = false;
    }

    ecritureFichier();

    header("Location: profil.php");
    exit;
?>