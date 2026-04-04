<?php
    session_start();

    function ecritureFichier(){
        $fichier = __DIR__ . "/data/compte.json";

        if (!is_dir(__DIR__ . "/data")) {
            mkdir(__DIR__ . "/data", 0777, true);
        }

        if(file_exists($fichier)){
            $json = file_get_contents($fichier);
            $utilisateurs = json_decode($json, true) ?? [];
        }
        else{
            $utilisateurs = array();
        }

        $email = $_POST["email"] ?? null;
        $emailExiste = false;

        foreach($utilisateurs as $utilisateur){
            if($utilisateur["email"] === $email){
                $_SESSION["erreur"] = "Cet email est déjà utilisé.";
                header("Location: inscription.php");
                exit;
            }
        }

        if($emailExiste){
            return;
        }

        $utilisateurs[] = array(
            "id" => uniqid(),
            "nom" => $_POST["nom"],
            "prenom" => $_POST["prenom"],
            "email" => $email,
            "motdepasse" => password_hash($_POST["motdepasse"], PASSWORD_DEFAULT),
            "adresse" => $_POST["adresse"],
            "telephone" => trim($_POST["telephone"]),
            "infos" => $_POST["infos"],
            "statut" => "Client",
            "bloque" => false,
        );

        file_put_contents($fichier, json_encode($utilisateurs, JSON_PRETTY_PRINT));

        $_SESSION["nom"] = $_POST["nom"];
        $_SESSION["prenom"] = $_POST["prenom"];
        $_SESSION["email"] = strtolower(trim($email));
        $_SESSION["telephone"] = $_POST["telephone"];
        $_SESSION["adresse"] = $_POST["adresse"];
        $_SESSION["infos"] = $_POST["infos"];
        $_SESSION["statut"] = "Client";
        $_SESSION["bloque"] = false;
    }

    ecritureFichier();

    header("Location: profil.php");
    exit;
?>