<?php
session_start();

require(__DIR__ . "/getapikey.php");

$transaction = $_GET["transaction"] ?? "";
$montant = $_GET["montant"] ?? "";
$vendeur = $_GET["vendeur"] ?? "";
$statut = $_GET["status"] ?? "";
$control = $_GET["control"] ?? "";

$api_key = getAPIKey($vendeur);

$control_local = md5(
    $api_key . "#" .
    $transaction . "#" .
    $montant . "#" .
    $vendeur . "#" .
    $statut . "#"
);

if ($control !== $control_local) {
    die("Erreur de sécurité : données invalides");
}

$fichierCommandes = __DIR__ . "/data/commandes.json";
$commandes = file_exists($fichierCommandes)
    ? json_decode(file_get_contents($fichierCommandes), true)
    : [];

if (!is_array($commandes)) {
    $commandes = [];
}

if (isset($_SESSION["commande_en_attente"]) && $_SESSION["commande_en_attente"]["transaction"] === $transaction) {
    if ($statut === "denied") {
        unset($_SESSION["commande_en_attente"]);
        $_SESSION["erreur"] = "Paiement refusé. Votre commande a été annulée.";
        header("Location: page-d'accueil.php");
        exit();
    }
    $commande = $_SESSION["commande_en_attente"]["commande"];
    $commande["id"] = !empty($commandes) ? max(array_column($commandes, "id")) + 1 : 1;
    $commande["total_paye"] = floatval($montant);
    $commande["reste_a_payer"] = 0;
    $commande["paiements"][] = [
        "transaction" => $transaction,
        "montant" => floatval($montant),
        "statut" => "accepte",
        "type" => "paiement_initial",
        "date" => date("Y-m-d H:i:s")
    ];
    $commandes[] = $commande;
    file_put_contents(
        $fichierCommandes,
        json_encode($commandes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
        LOCK_EX
    );
    unset($_SESSION["commande_en_attente"]);
    unset($_SESSION["panier"]);

    header("Location: Notation.php");
    exit();
}
$commandeTrouvee = false;
foreach ($commandes as &$commande) {
    if (!empty($commande["modification_en_attente"]["transaction"]) && $commande["modification_en_attente"]["transaction"] === $transaction) {
        $commandeTrouvee = true;
        if ($statut === "denied") {
            $commande["paiements"][] = [
                "transaction" => $transaction,
                "montant" => floatval($montant),
                "statut" => "refuse",
                "type" => "difference",
                "date" => date("Y-m-d H:i:s")
            ];
            unset($commande["modification_en_attente"]);
            break;
        }
        $modification = $commande["modification_en_attente"];
        $commande["plats"] = $modification["plats"];
        $commande["total_actuel"] = $modification["nouveau_total"];
        $commande["total_paye"] = ($commande["total_paye"] ?? 0) + floatval($montant);
        $commande["reste_a_payer"] = 0;
        $commande["paiements"][] = [
            "transaction" => $transaction,
            "montant" => floatval($montant),
            "statut" => "accepte",
            "type" => "difference",
            "date" => date("Y-m-d H:i:s")
        ];
        unset($commande["modification_en_attente"]);
        break;
    }
}
unset($commande);
if ($commandeTrouvee) {
    file_put_contents(
        $fichierCommandes,
        json_encode($commandes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
        LOCK_EX
    );
    if ($statut === "denied") {
        $_SESSION["erreur"] = "Paiement refusé. La modification n'a pas été appliquée.";
    } else {
        $_SESSION["message"] = "Commande modifiée avec succès.";
    }
    header("Location: profil.php");
    exit();
}
header("Location: profil.php");
exit();
?>
