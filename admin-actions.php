<?php
    session_start();
    if(!isset($_SESSION["statut"]) || $_SESSION["statut"] !== "Admin"){
        header("Location: connexion.php");
        exit();
    }

    function Admin(){

        $fichier = __DIR__ . "/data/compte.json"; // Chemin vers le fichier JSON

        if(!is_dir(__DIR__ . "/data")){ // Vérifie si le dossier "data" existe, sinon le crée
            mkdir(__DIR__ . "/data", 0777, true);
        }

        if(file_exists($fichier)){ // Vérifie si le fichier JSON existe, sinon crée un tableau vide
            $json = file_get_contents($fichier);
            $utilisateurs = json_decode($json, true) ?? [];
        } 
        
        else {
            $utilisateurs = array();
        }

        if(!isset($_GET["id"])){ // Vérifie si l'ID est présent dans l'URL
            return null;
        }

        $id = $_GET["id"];
        $index = null;

        foreach($utilisateurs as $key => $utilisateur){ // Parcourt le tableau pour trouver l'utilisateur correspondant à l'ID
            if($utilisateur["id"] === $id){
                $index = $key;
                break;
            }
        }

        if($index === null){
            return null;
        }

        if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"])){ // Vérifie si une action est soumise via POST
            if($_POST["action"] === "bloquer"){
                $utilisateurs[$index]["bloque"] = true;
            }
            if($_POST["action"] === "debloquer"){
                $utilisateurs[$index]["bloque"] = false;
            }
            if($_POST["action"] === "premium"){
                $utilisateurs[$index]["statut"] = "Premium";
            }
            if($_POST["action"] === "vip"){
                $utilisateurs[$index]["statut"] = "VIP";
            }
            if($_POST["action"] === "client"){
                $utilisateurs[$index]["statut"] = "Client";
            }

            file_put_contents($fichier, json_encode($utilisateurs, JSON_PRETTY_PRINT));
            header("Location: admin-pouvoirs.php?id=".$id);
            exit;
        }
        return $utilisateurs[$index];
    }

    $utilisateur = Admin();

    if(!$utilisateur){ // Si l'utilisateur n'est pas trouvé, affiche un message d'erreur
        die("Utilisateur introuvable");
    }
?>
