<?php
    session_start();

    function ecritureFichier(){

        $fichier = __DIR__ . "/data/reservation.json";

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

        $date = $_POST["date"];

        if(strtotime($date) < strtotime(date("Y-m-d"))) {
            $_SESSION["erreur"] = "La date de réservation doit être dans le futur.";
            header("Location: reserver.php");
            exit;
        }

        $utilisateurs[] = array( // Ajoute la personne qui réserve dans un fichier.json
            "id" => uniqid(),
            "nom" => $_POST["nom"],
            "prenom" => $_POST["prenom"],
            "adultes" => $_POST["adultes"],
            "enfants" => $_POST["enfants"],
            "date" => $date,
            "time" => $_POST["time"],
            "restaurant" => $_POST["restaurant"],
            "commentaire" => $_POST["commentaire"],
        );

        file_put_contents($fichier, json_encode($utilisateurs, JSON_PRETTY_PRINT));

        $_SESSION["nom"] = $_POST["nom"]; // Stocke les informations
        $_SESSION["prenom"] = $_POST["prenom"];
        $_SESSION["adultes"] = $_POST["adultes"];
        $_SESSION["enfants"] = $_POST["enfants"];
        $_SESSION["date"] = $_POST["date"];
        $_SESSION["time"] = $_POST["time"];
        $_SESSION["restaurant"] = $_POST["restaurant"];
        $_SESSION["commentaire"] = $_POST["commentaire"];
    }

    ecritureFichier();

    $_SESSION["message2"] = "Votre réservation a été enregistrée avec succès.";
    header("Location: page-d'accueil.php");
    exit;
?>
