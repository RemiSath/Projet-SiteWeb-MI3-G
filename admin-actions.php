<?php
    session_start();

    if(!isset($_SESSION["statut"]) || $_SESSION["statut"] !== "Admin"){
        exit();
    }

    $fichier = __DIR__ . "/data/compte.json";

    if(file_exists($fichier)){
        $json = file_get_contents($fichier);
        $utilisateurs = json_decode($json, true) ?? [];
    } 

    if(!isset($_POST["id"]) || !isset($_POST["action"])){
        echo "ERREUR";
        exit();
    }

    $id = $_POST["id"];
    $action = $_POST["action"];
    $index = null;

    foreach($utilisateurs as $key => $utilisateur){
        if($utilisateur["id"] === $id){
            $index = $key;
            break;
        }
    }

    if($index === null){
        echo "Utilisateur introuvable";
        exit();
    }
    if($action === "bloquer"){
        $utilisateurs[$index]["bloque"] = true;
    }
    elseif($action === "debloquer"){
        $utilisateurs[$index]["bloque"] = false;
    }
    elseif($action === "premium"){
        $utilisateurs[$index]["statut"] = "Premium";
    }
    elseif($action === "vip"){
        $utilisateurs[$index]["statut"] = "VIP";
    }
    elseif($action === "client"){
        $utilisateurs[$index]["statut"] = "Client";
    }

    file_put_contents($fichier, json_encode($utilisateurs, JSON_PRETTY_PRINT));

?>