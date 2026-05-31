<?php
session_start();

if (!isset($_SESSION["statut"]) || $_SESSION["statut"] !== "Admin") {
    header("Location: connexion.php");
    exit();
}

$isFetch = isset($_POST["requete_fetch"]);

function repondre($success, $message, $extra = [])
{
    global $isFetch;
    if ($isFetch) {
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode(array_merge([
            "success" => $success,
            "message" => $message
        ], $extra));
        exit();
    }
    if ($success) {
        $_SESSION["message_admin"] = $message;
    } else {
        $_SESSION["erreur_admin"] = $message;
    }
}

$fichierComptes = __DIR__ . "/data/compte.json";
$fichierCommandes = __DIR__ . "/data/commandes.json";

if (!is_dir(__DIR__ . "/data")) {
    mkdir(__DIR__ . "/data", 0777, true);
}

$utilisateurs = file_exists($fichierComptes)
    ? json_decode(file_get_contents($fichierComptes), true)
    : [];

if (!is_array($utilisateurs)) {
    $utilisateurs = [];
}

$commandes = file_exists($fichierCommandes)
    ? json_decode(file_get_contents($fichierCommandes), true)
    : [];

if (!is_array($commandes)) {
    $commandes = [];
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: Admin.php");
    exit();
}

$id = $_POST["id"] ?? "";
$action = $_POST["action"] ?? "";
$actionsAutorisees = ["bloquer", "debloquer", "premium", "vip", "client", "remise"];

if (!in_array($action, $actionsAutorisees, true)) {
    repondre(false, "Action non autorisée.");
    header("Location: admin-pouvoirs.php?id=" . urlencode($id));
    exit();
}

$index = null;

foreach ($utilisateurs as $key => $utilisateur) {
    if (($utilisateur["id"] ?? "") === $id) {
        $index = $key;
        break;
    }
}

if ($index === null) {
    repondre(false, "Utilisateur introuvable.");
    header("Location: Admin.php");
    exit();
}

if (
    strtolower(trim($utilisateurs[$index]["email"] ?? "")) === strtolower(trim($_SESSION["email"] ?? "")) ||
    ($utilisateurs[$index]["statut"] ?? "") === "Admin"
) {
    repondre(false, "Action interdite sur ce compte.");
    header("Location: admin-pouvoirs.php?id=" . urlencode($id));
    exit();
}

if ($action === "bloquer") {
    $utilisateurs[$index]["bloque"] = true;
    $message = "Compte bloqué.";
}

if ($action === "debloquer") {
    $utilisateurs[$index]["bloque"] = false;
    $message = "Compte débloqué.";
}

if ($action === "premium") {
    $utilisateurs[$index]["statut"] = "Premium";
    $message = "Statut Premium appliqué.";
}

if ($action === "vip") {
    $utilisateurs[$index]["statut"] = "VIP";
    $message = "Statut VIP appliqué.";
}

if ($action === "client") {
    $utilisateurs[$index]["statut"] = "Client";
    $message = "Statut Client appliqué.";
}

if ($action === "remise") {
    $montant = floatval($_POST["montant_reduction"] ?? 0);
    $commentaire = trim($_POST["commentaire_reduction"] ?? "");

    if ($montant < 0.01 || $montant > 200) {
        repondre(false, "Le montant du bon doit être compris entre 0,01 € et 200 €.");
        header("Location: admin-pouvoirs.php?id=" . urlencode($id));
        exit();
    }

    if (strlen($commentaire) > 200) {
        repondre(false, "Le commentaire du bon est limité à 200 caractères.");
        header("Location: admin-pouvoirs.php?id=" . urlencode($id));
        exit();
    }

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
        "commentaire" => $commentaire
    ];

    file_put_contents(
        $fichierCommandes,
        json_encode($commandes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
        LOCK_EX
    );
    $message = "Bon de réduction accordé avec succès.";
}

file_put_contents(
    $fichierComptes,
    json_encode($utilisateurs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
    LOCK_EX
);

repondre(true, $message ?? "Action effectuée.", [
    "statut" => $utilisateurs[$index]["statut"] ?? "",
    "bloque" => !empty($utilisateurs[$index]["bloque"])
]);

header("Location: admin-pouvoirs.php?id=" . urlencode($id));
exit();
?>
