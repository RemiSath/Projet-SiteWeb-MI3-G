<?php
    session_start();

    $fichier = __DIR__ . "/data/compte.json";

    if(!is_dir(__DIR__ . "/data")){
        mkdir(__DIR__ . "/data", 0777, true);
    }
    if(!file_exists($fichier)){
        echo "inscription.php";
        exit;
    }

    $json = file_get_contents($fichier);
    $array = json_decode($json, true) ?? [];

    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $utilisateurTrouve = false;

    foreach($array as $utilisateur){

        if(strtolower(trim($utilisateur["email"])) === strtolower($email)){

            $utilisateurTrouve = true;

            if(!password_verify($password, $utilisateur["motdepasse"])){
                echo "password";
                exit;
            }
            if(($utilisateur["bloque"] ?? false) === true){
                echo "bloque";
                exit;
            }

            $_SESSION["nom"] = $utilisateur["nom"];
            $_SESSION["prenom"] = $utilisateur["prenom"];
            $_SESSION["email"] = strtolower(trim($utilisateur["email"]));
            $_SESSION["telephone"] = $utilisateur["telephone"];
            $_SESSION["adresse"] = $utilisateur["adresse"];
            $_SESSION["infos"] = $utilisateur["infos"];
            $_SESSION["statut"] = $utilisateur["statut"];
            $_SESSION["bloque"] = $utilisateur["bloque"] ?? false;

            if($utilisateur["statut"] === "Admin"){
                echo "Admin.php";
            }
            elseif($utilisateur["statut"] === "Restaurateur"){
                echo "commandes.php";
            }
            elseif($utilisateur["statut"] === "Livreur"){
                echo "livraison.php";
            }
            else{
                echo "profil.php";
            }
            exit;
        }
    }

    if(!$utilisateurTrouve){
        echo "email";
    }
?>