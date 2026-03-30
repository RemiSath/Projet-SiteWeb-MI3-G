<?php
    session_start();

    function ecritureFichier(){
        $fichier = "compte.json";

        if(file_exists($fichier)){
            $json = file_get_contents($fichier);
            $array = json_decode($json, true) ?? [];
        }
        else{
            $array = array();
        }

        $email = $_POST["email"];
        $emailExiste = false;

        foreach($array as $utilisateur){
            if($utilisateur["email"] === $email){
                $_SESSION["erreur"] = "Cet email est déjà utilisé.";
                header("Location: inscription.php");
                exit;
            }
        }

        if($emailExiste){
            return;
        }

        $array[] = array(
            "id" => uniqid(),
            "nom" => $_POST["nom"],
            "prenom" => $_POST["prenom"],
            "email" => $email,
            "motdepasse" => password_hash($_POST["motdepasse"], PASSWORD_DEFAULT),
            "adresse" => $_POST["adresse"],
            "telephone" => trim($_POST["telephone"]),
            "infos" => $_POST["infos"],
        );

        file_put_contents($fichier, json_encode($array, JSON_PRETTY_PRINT));

        $_SESSION["nom"] = $_POST["nom"];
        $_SESSION["prenom"] = $_POST["prenom"];
        $_SESSION["email"] = $email;
        $_SESSION["motdepasse"] = $_POST["motdepasse"];
        $_SESSION["telephone"] = $_POST["telephone"];
        $_SESSION["adresse"] = $_POST["adresse"];
        $_SESSION["infos"] = $_POST["infos"];
    }

    ecritureFichier();

    header("Location: profil.php");
    exit;
?>