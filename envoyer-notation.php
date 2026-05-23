<?php
    session_start();

    function enregistrerNotation(){
        $fichier = __DIR__ . "/data/notations.json";

        if(!is_dir(__DIR__ . "/data")){
            mkdir(__DIR__ . "/data", 0777, true);
        }

        if(file_exists($fichier)){
            $json = file_get_contents($fichier);
            $notations = json_decode($json, true) ?? [];
        } 
        else{
            $notations = [];
        }

        $commandeId = $_POST["commande_id"] ?? "";

        foreach($notations as $notation){
            if(isset($notation["commande_id"]) && $notation["commande_id"] == $commandeId){
                $_SESSION["message"] = "Vous avez déjà noté cette commande.";
                header("Location: Notation.php");
                exit;
            }
        }

        $livraison = isset($_POST["livraison"]) ? intval($_POST["livraison"]) : null;
        $qualite = isset($_POST["qualite"]) ? intval($_POST["qualite"]) : null;
        $commentaires = trim($_POST["commentaires"] ?? "");

        $notations[] = array(
            "id" => uniqid(),
            "commande_id" => $commandeId,
            "livraison" => $livraison,
            "qualite" => $qualite,
            "commentaires" => $commentaires,
            "date" => date("Y-m-d")
        );

        file_put_contents($fichier, json_encode($notations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        $_SESSION["message"] = "Merci pour votre avis !";
    }

    enregistrerNotation();
    header("Location: Notation.php");
    exit;
?>