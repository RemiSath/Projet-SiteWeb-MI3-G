<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: livraison.php");
    exit();
}

if (!isset($_SESSION["statut"]) || $_SESSION["statut"] !== "Livreur") {
    header("Location: connexion.php");
    exit();
}

$fichier = "data/commandes.json";

if (!file_exists($fichier)) {
    header("Location: livraison.php");
    exit();
}

$commandes = json_decode(file_get_contents($fichier), true);

if (!is_array($commandes)) {
    $commandes = [];
}

$commandeId = $_POST["commande_id"] ?? "";
$action = $_POST["action"] ?? "";

if (!in_array($action, ["livree", "abandonnee"], true)) {
    header("Location: livraison.php");
    exit();
}

$livreurEmail = $_SESSION["email"] ?? "";
$livreurNom = trim(($_SESSION["prenom"] ?? "") . " " . ($_SESSION["nom"] ?? ""));
$identifiantLivreur = $livreurEmail !== "" ? $livreurEmail : $livreurNom;

$commandeTrouvee = false;
$commandeModifiee = false;

foreach ($commandes as &$commande) {
    if ((string)($commande["id"] ?? "") === (string)$commandeId) {
        $commandeTrouvee = true;
        $livreurCommande = $commande["livreur_email"] ?? ($commande["livreur"] ?? "");
        if ($livreurCommande !== $identifiantLivreur) {
            break;
        }
        if (($commande["statut"] ?? "") !== "en_livraison") {
            break;
        }
        if ($action === "livree") {
            $commande["statut"] = "livree";
            $commande["date_livraison"] = date("Y-m-d H:i:s");
            $commandeModifiee = true;

        } elseif ($action === "abandonnee") {
            $commande["statut"] = "abandonnee";
            $commande["motif_abandon"] = "Livraison impossible";
            $commande["date_abandon"] = date("Y-m-d H:i:s");
            $commandeModifiee = true;
        }
        break;
    }
}
unset($commande);

if ($commandeModifiee) {
    file_put_contents(
        $fichier,
        json_encode($commandes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
        LOCK_EX
    );
}

header("Location: livraison.php");
exit();
