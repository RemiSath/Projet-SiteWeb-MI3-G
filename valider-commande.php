<?php
session_start();
include "bibliothèques/bloquer.php";

$panier = $_SESSION["panier"] ?? [];

if (empty($panier)) {
    header("Location: commander.php");
    exit();
}

function totalPanier($panier)
{
    $total = 0;
    foreach ($panier as $item) {
        $total += $item["prix"] * $item["quantite"];
    }
    return $total;
}

$nomComplet = trim($_POST["name"] ?? "Anonyme");
$adresse = trim($_POST["address"] ?? "");
$code_postal = trim($_POST["postal_code"] ?? "");
$ville = trim($_POST["city"] ?? "");
$telephone = trim($_POST["phone"] ?? "");
$email = strtolower(trim($_POST["email"] ?? $_SESSION["email"] ?? ""));
$motdepasse = $_POST["motdepasse"] ?? "";

$interphone = trim($_POST["interphone"] ?? "");
$etage = trim($_POST["floor"] ?? "");
$commentaires = trim($_POST["comments"] ?? "");

$planification = $_POST["planification"] ?? "immediate";
$date_souhaitee = trim($_POST["date_souhaitee"] ?? "");
$heure_souhaitee = trim($_POST["heure_souhaitee"] ?? "");

if ($planification === "plus_tard" && strtotime($date_souhaitee) <= strtotime(date("Y-m-d"))) {
    $_SESSION["erreur"] = "La date de commande doit être dans le futur.";
    header("Location: commander.php");
    exit();
}

$date_planifiee = null;
if ($planification === "plus_tard" && $date_souhaitee !== "" && $heure_souhaitee !== "") {
    $date_planifiee = $date_souhaitee . " " . $heure_souhaitee;
}

$adresse_complete = $adresse . ", " . $code_postal . " " . $ville;

$dirData = __DIR__ . "/data";
$fichierComptes = $dirData . "/compte.json";

if (empty($_SESSION["email"])) {
    if ($email === "" || $motdepasse === "") {
        header("Location: commander.php?erreur=champs_compte");
        exit();
    }

    if (!is_dir($dirData)) {
        mkdir($dirData, 0777, true);
    }

    $comptes = file_exists($fichierComptes)
        ? json_decode(file_get_contents($fichierComptes), true)
        : [];

    if (!is_array($comptes)) {
        $comptes = [];
    }

    foreach ($comptes as $compte) {
        if (strtolower($compte["email"]) === $email) {
            header("Location: commander.php?erreur=email_existe");
            exit();
        }
    }
    $partiesNom = preg_split('/\s+/', trim($nomComplet), 2);
    $prenom = $partiesNom[0] ?? "";
    $nom = $partiesNom[1] ?? "";
    $nouveauCompte = [
        "id" => uniqid(),
        "nom" => $nom,
        "prenom" => $prenom,
        "email" => $email,
        "motdepasse" => password_hash($motdepasse, PASSWORD_DEFAULT),
        "adresse" => $adresse_complete,
        "telephone" => $telephone,
        "infos" => "",
        "statut" => "Client",
        "bloque" => false
    ];
    $comptes[] = $nouveauCompte;
    file_put_contents(
        $fichierComptes,
        json_encode($comptes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
        LOCK_EX
    );
    $_SESSION["email"] = $email;
    $_SESSION["nom"] = $nom;
    $_SESSION["prenom"] = $prenom;
    $_SESSION["telephone"] = $telephone;
    $_SESSION["adresse"] = $adresse_complete;
    $_SESSION["infos"] = "";
    $_SESSION["statut"] = "Client";
}

$total = totalPanier($panier);
$transaction = uniqid();
$montant = number_format($total, 2, '.', '');
$vendeur = "MI-3_G";
$retour = "http://localhost:8080/retour-paiement.php";
require(__DIR__ . "/getapikey.php");
$api_key = getAPIKey($vendeur);
$control = md5(
    $api_key . "#" .
    $transaction . "#" .
    $montant . "#" .
    $vendeur . "#" .
    $retour . "#"
);

$_SESSION["commande_en_attente"] = [
    "transaction" => $transaction,
    "montant" => $montant,
    "commande" => [
        "client" => $nomComplet,
        "date" => $date_planifiee ?? date("Y-m-d"),
        "mode_commande" => "livraison",
        "planification" => $planification,
        "date_souhaitee" => $date_planifiee,
        "statut" => "a_preparer",
        "adresse" => $adresse_complete,
        "telephone" => $telephone,
        "email" => $email,
        "interphone" => $interphone,
        "etage" => $etage,
        "commentaires" => $commentaires,
        "plats" => array_values($panier),
        "total_initial" => $total,
        "total_actuel" => $total,
        "total_paye" => 0,
        "reste_a_payer" => $total,
        "ticket_reduction" => 0,
        "paiements" => []
    ]
];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Paiement</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="Images/Among_Us.png">
</head>
<body>
<header class="navbar">
    <a href="page-d'accueil.php" class="accueil">IMPOSTEUR</a>
</header>

<div class="container2">
    <h4>Finaliser le paiement</h4>
    <p>Total à payer : <strong><?php echo number_format($total, 2, ',', ' '); ?> €</strong></p>

    <form action="https://www.plateforme-smc.fr/cybank/index.php" method="POST">
        <input type="hidden" name="transaction" value="<?php echo htmlspecialchars($transaction); ?>">
        <input type="hidden" name="montant" value="<?php echo htmlspecialchars($montant); ?>">
        <input type="hidden" name="vendeur" value="<?php echo htmlspecialchars($vendeur); ?>">
        <input type="hidden" name="retour" value="<?php echo htmlspecialchars($retour); ?>">
        <input type="hidden" name="control" value="<?php echo htmlspecialchars($control); ?>">
        <button type="submit" class="bouttonclassique">Continuer vers le paiement</button>
    </form>
</div>

<footer class="footer">
    <p>📞 Téléphone : 07 61 41 44 23</p>
    <p>✉ Email : imposturecontact@gmail.com</p>
</footer>
</body>
</html>
