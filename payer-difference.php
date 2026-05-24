<?php
session_start();

if (!isset($_SESSION["email"])) {
    header("Location: connexion.php");
    exit();
}

$id = intval($_GET["id"] ?? 0);
$fichierCommandes = __DIR__ . "/data/commandes.json";

$commandes = file_exists($fichierCommandes)
    ? json_decode(file_get_contents($fichierCommandes), true)
    : [];

if (!is_array($commandes)) {
    $commandes = [];
}

$commandeIndex = null;
$emailSession = strtolower(trim($_SESSION["email"]));

foreach ($commandes as $index => $commande) {
    if (
        intval($commande["id"] ?? 0) === $id &&
        strtolower(trim($commande["email"] ?? "")) === $emailSession
    ) {
        $commandeIndex = $index;
        break;
    }
}

if ($commandeIndex === null || empty($commandes[$commandeIndex]["modification_en_attente"])) {
    header("Location: profil.php");
    exit();
}

$transaction = uniqid();
$difference = $commandes[$commandeIndex]["modification_en_attente"]["difference"];
$commandes[$commandeIndex]["modification_en_attente"]["transaction"] = $transaction;

file_put_contents(
    $fichierCommandes,
    json_encode($commandes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
    LOCK_EX
);

require(__DIR__ . "/getapikey.php");

$montant = number_format($difference, 2, '.', '');
$vendeur = "MI-3_G";
$retour = "http://localhost:8080/retour-paiement.php";

$api_key = getAPIKey($vendeur);

$control = md5(
    $api_key . "#" .
    $transaction . "#" .
    $montant . "#" .
    $vendeur . "#" .
    $retour . "#"
);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Payer la différence</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="Images/Among_Us.png">
</head>
<body>
<header class="navbar">
    <a href="page-d'accueil.php" class="accueil">IMPOSTEUR</a>
</header>

<div class="container2">
    <h4>Paiement complémentaire</h4>
    <p>Votre commande est plus chère.</p>
    <p>Différence à payer : <strong><?php echo number_format($difference, 2, ',', ' '); ?> €</strong></p>
    <form action="https://www.plateforme-smc.fr/cybank/index.php" method="POST">
        <input type="hidden" name="transaction" value="<?php echo htmlspecialchars($transaction); ?>">
        <input type="hidden" name="montant" value="<?php echo htmlspecialchars($montant); ?>">
        <input type="hidden" name="vendeur" value="<?php echo htmlspecialchars($vendeur); ?>">
        <input type="hidden" name="retour" value="<?php echo htmlspecialchars($retour); ?>">
        <input type="hidden" name="control" value="<?php echo htmlspecialchars($control); ?>">
        <button type="submit">Payer la différence</button>
    </form>
</div>

    <footer class="footer">
        <p>📞 Téléphone : 07 67 01 02 03</p>
        <p>✉ Email : imposteurcontact@gmail.com</p>
        <p>Horaires : Lundi - Vendredi 10h-21h | Samedi - Dimanche 12h-18h</p>
    </footer>
</body>
</html>