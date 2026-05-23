<?php
    session_start();

    require(__DIR__ . '/getapikey.php');

    $transaction = $_GET['transaction'] ?? '';
    $montant = $_GET['montant'] ?? '';
    $vendeur = $_GET['vendeur'] ?? '';
    $statut = $_GET['status'] ?? '';
    $control = $_GET['control'] ?? '';

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

    if ($statut === "denied") {
        $commandes = file_exists($fichierCommandes)
            ? json_decode(file_get_contents($fichierCommandes), true)
            : [];

        if (!empty($commandes)) {
            array_pop($commandes);

            file_put_contents(
                $fichierCommandes,
                json_encode($commandes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
                LOCK_EX
            );
        }

        $_SESSION["erreur"] = "Paiement refusé. Votre commande a été annulée.";

        header("Location: page-d'accueil.php");
        exit;
    }

    header("Location: profil.php");
    exit;
?>