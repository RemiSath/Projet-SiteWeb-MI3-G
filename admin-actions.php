<?php
session_start();

if(!isset($_SESSION["statut"]) || $_SESSION["statut"] !== "Admin"){
    header("Location: connexion.php");
    exit();
}

$fichierComptes = __DIR__ . "/data/compte.json";
$fichierCommandes = __DIR__ . "/data/commandes.json";

if(!is_dir(__DIR__ . "/data")){
    mkdir(__DIR__ . "/data", 0777, true);
}

$utilisateurs = file_exists($fichierComptes)
    ? json_decode(file_get_contents($fichierComptes), true)
    : [];

if(!is_array($utilisateurs)){
    $utilisateurs = [];
}

$commandes = file_exists($fichierCommandes)
    ? json_decode(file_get_contents($fichierCommandes), true)
    : [];

if(!is_array($commandes)){
    $commandes = [];
}

$id = $_POST["id"] ?? $_GET["id"] ?? "";
$action = $_POST["action"] ?? "";
$actionsAutorisees = ["bloquer", "debloquer", "premium", "vip", "client", "remise"];

$index = null;

foreach($utilisateurs as $key => $utilisateur){
    if(($utilisateur["id"] ?? "") === $id){
        $index = $key;
        break;
    }
}

if($index === null){
    die("Utilisateur introuvable");
}

if($_SERVER["REQUEST_METHOD"] === "POST" && $action !== ""){
    if(!in_array($action, $actionsAutorisees, true)){
        die("Action non autorisée");
    }

    if(($utilisateurs[$index]["email"] ?? "") === ($_SESSION["email"] ?? "") || ($utilisateurs[$index]["statut"] ?? "") === "Admin"){
        die("Action interdite sur ce compte");
    }

    if($action === "bloquer"){
        $utilisateurs[$index]["bloque"] = true;
    }

    if($action === "debloquer"){
        $utilisateurs[$index]["bloque"] = false;
    }

    if($action === "premium"){
        $utilisateurs[$index]["statut"] = "Premium";
    }

    if($action === "vip"){
        $utilisateurs[$index]["statut"] = "VIP";
    }

    if($action === "client"){
        $utilisateurs[$index]["statut"] = "Client";
    }

    if($action === "remise"){
        $montant = floatval($_POST["montant_reduction"] ?? 0);

        if($montant > 0 && $montant <= 200){
            $nouvelId = !empty($commandes)
                ? max(array_column($commandes, "id")) + 1
                : 1;

            $commandes[] = [
                "id" => $nouvelId,
                "type" => "bon_reduction_admin",
                "client" => trim(($utilisateurs[$index]["prenom"] ?? "") . " " . ($utilisateurs[$index]["nom"] ?? "")),
                "date" => date("Y-m-d"),
                "statut" => "bon_reduction",
                "adresse" => $utilisateurs[$index]["adresse"] ?? "",
                "telephone" => $utilisateurs[$index]["telephone"] ?? "",
                "email" => strtolower(trim($utilisateurs[$index]["email"] ?? "")),
                "plats" => [],
                "total_initial" => 0,
                "total_actuel" => 0,
                "total_paye" => 0,
                "reste_a_payer" => 0,
                "ticket_reduction" => $montant,
                "ticket_reduction_utilise" => 0,
                "accorde_par_admin" => $_SESSION["email"] ?? "",
                "commentaire" => trim($_POST["commentaire_reduction"] ?? "")
            ];

            file_put_contents(
                $fichierCommandes,
                json_encode($commandes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
                LOCK_EX
            );

            $_SESSION["message_admin"] = "Bon de réduction accordé avec succès.";
        } else {
            $_SESSION["erreur_admin"] = "Le montant du bon doit être compris entre 0 et 200 €.";
        }
    }

    file_put_contents(
        $fichierComptes,
        json_encode($utilisateurs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
        LOCK_EX
    );

    header("Location: admin-pouvoirs.php?id=" . urlencode($id));
    exit();
}

header("Location: admin-pouvoirs.php?id=" . urlencode($id));
exit();
?>
