<?php
session_start();

function enregistrerNotation() {
    $fichier = "notations.json";

    if(file_exists($fichier)){
        $json = file_get_contents($fichier);
        $notations = json_decode($json, true) ?? [];
    } 
    else{
        $notations = [];
    }

    $livraison = isset($_POST["livraison"]) ? intval($_POST["livraison"]) : null;
    $qualite = isset($_POST["qualite"]) ? intval($_POST["qualite"]) : null;
    $commentaires = trim($_POST["commentaires"] ?? "");

    if($livraison < 0 || $livraison > 5 || $qualite < 0 || $qualite > 5){
        $_SESSION["erreur"] = "Les notes doivent être entre 0 et 5.";
        header("Location: Notation.php");
        exit;
    }

    $notations[] = array(
        "id" => uniqid(),
        "livraison" => $livraison,
        "qualite" => $qualite,
        "commentaires" => $commentaires,
        "date" => date("Y-m-d H:i:s")
    );

    file_put_contents($fichier, json_encode($notations, JSON_PRETTY_PRINT));

    $_SESSION["message"] = "Merci pour votre avis !";
}

enregistrerNotation();

header("Location: Notation.php");
exit;
?>