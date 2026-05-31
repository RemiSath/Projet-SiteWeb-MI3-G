<?php
session_start();

require(__DIR__ . "/getapikey.php");
require_once __DIR__ . "/bibliothèques/logs.php";

function utiliserTicketReduction(&$commandes, $email, $montantAUtiliser)
{
    $email = strtolower(trim($email));
    $resteAUtiliser = floatval($montantAUtiliser);

    foreach ($commandes as &$commande) {
        if ($resteAUtiliser <= 0) {
            break;
        }

        if (strtolower(trim($commande["email"] ?? "")) !== $email) {
            continue;
        }

        $ticket = floatval($commande["ticket_reduction"] ?? 0);
        $utilise = floatval($commande["ticket_reduction_utilise"] ?? 0);
        $disponible = max(0, $ticket - $utilise);

        if ($disponible <= 0) {
            continue;
        }

        $montantPris = min($disponible, $resteAUtiliser);
        $commande["ticket_reduction_utilise"] = $utilise + $montantPris;
        $resteAUtiliser -= $montantPris;
    }

    unset($commande);
}

function montantIdentique($montantAttendu, $montantRecu)
{
    return number_format(floatval($montantAttendu), 2, '.', '') ===
        number_format(floatval($montantRecu), 2, '.', '');
}

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
    enregistrerLogIncident(
        "paiement_control_invalide",
        "Retour paiement avec controle invalide.",
        $_SESSION["email"] ?? "",
        [
            "transaction" => $transaction,
            "montant" => $montant,
            "statut" => $statut
        ]
    );

    die("Erreur de securite : donnees invalides");
}

if (!in_array($statut, ["accepted", "denied"], true)) {
    enregistrerLogIncident(
        "paiement_statut_invalide",
        "Retour paiement avec statut inconnu.",
        $_SESSION["email"] ?? "",
        [
            "transaction" => $transaction,
            "montant" => $montant,
            "statut" => $statut
        ]
    );

    die("Erreur de securite : statut de paiement invalide");
}

$fichierCommandes = __DIR__ . "/data/commandes.json";
$commandes = file_exists($fichierCommandes)
    ? json_decode(file_get_contents($fichierCommandes), true)
    : [];

if (!is_array($commandes)) {
    $commandes = [];
}

if (
    isset($_SESSION["commande_en_attente"]) &&
    ($_SESSION["commande_en_attente"]["transaction"] ?? "") === $transaction
) {
    $montantAttendu = $_SESSION["commande_en_attente"]["montant"] ?? 0;

    if (!montantIdentique($montantAttendu, $montant)) {
        enregistrerLogIncident(
            "paiement_montant_invalide",
            "Montant du paiement initial different du montant attendu.",
            $_SESSION["commande_en_attente"]["commande"]["email"] ?? "",
            [
                "transaction" => $transaction,
                "attendu" => $montantAttendu,
                "recu" => $montant
            ]
        );

        die("Erreur de securite : montant invalide");
    }

    if ($statut === "denied") {
        enregistrerLogIncident(
            "paiement_refuse",
            "Paiement initial refuse.",
            $_SESSION["commande_en_attente"]["commande"]["email"] ?? "",
            [
                "transaction" => $transaction,
                "montant" => $montant
            ]
        );

        unset($_SESSION["commande_en_attente"]);
        $_SESSION["erreur"] = "Paiement refuse. Votre commande a ete annulee.";
        header("Location: page-d'accueil.php");
        exit();
    }

    if ($statut !== "accepted") {
        die("Erreur de securite : paiement non accepte");
    }

    $commande = $_SESSION["commande_en_attente"]["commande"];
    $reductionUtilisee = floatval($commande["reduction_utilisee"] ?? 0);

    if ($reductionUtilisee > 0) {
        utiliserTicketReduction($commandes, $commande["email"] ?? "", $reductionUtilisee);
    }

    $commande["id"] = !empty($commandes)
        ? max(array_column($commandes, "id")) + 1
        : 1;

    $commande["total_paye"] = floatval($montant);
    $commande["reste_a_payer"] = 0;

    $commande["paiements"][] = [
        "transaction" => $transaction,
        "montant" => floatval($montant),
        "statut" => "accepte",
        "type" => "paiement_initial",
        "reduction_utilisee" => $reductionUtilisee,
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
    if (
        !empty($commande["modification_en_attente"]["transaction"]) &&
        $commande["modification_en_attente"]["transaction"] === $transaction
    ) {
        $commandeTrouvee = true;

        if ($statut === "denied") {
            enregistrerLogIncident(
                "paiement_difference_refuse",
                "Paiement de difference refuse.",
                $commande["email"] ?? "",
                [
                    "transaction" => $transaction,
                    "montant" => $montant
                ]
            );

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

        if ($statut !== "accepted") {
            die("Erreur de securite : paiement non accepte");
        }

        $modification = $commande["modification_en_attente"];

        if (!montantIdentique($modification["difference"] ?? 0, $montant)) {
            enregistrerLogIncident(
                "paiement_difference_montant_invalide",
                "Montant de difference different du montant attendu.",
                $commande["email"] ?? "",
                [
                    "transaction" => $transaction,
                    "attendu" => $modification["difference"] ?? 0,
                    "recu" => $montant
                ]
            );

            die("Erreur de securite : montant invalide");
        }

        $commande["plats"] = $modification["plats"];
        $commande["total_actuel"] = $modification["nouveau_total"];
        $commande["total_paye"] = floatval($commande["total_paye"] ?? 0) + floatval($montant);
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
        $_SESSION["erreur"] = "Paiement refuse. La modification n'a pas ete appliquee.";
    } else {
        $_SESSION["message"] = "Commande modifiee avec succes.";
    }

    header("Location: profil.php");
    exit();
}

enregistrerLogIncident(
    "paiement_transaction_introuvable",
    "Retour paiement sans commande correspondante.",
    $_SESSION["email"] ?? "",
    [
        "transaction" => $transaction,
        "montant" => $montant,
        "statut" => $statut
    ]
);

header("Location: profil.php");
exit();
?>