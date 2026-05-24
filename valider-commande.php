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
        $total += floatval($item["prix"]) * intval($item["quantite"]);
    }

    return $total;
}

function calculerTicketDisponible($commandes, $email)
{
    $total = 0;
    $email = strtolower(trim($email));

    foreach ($commandes as $commande) {
        if (strtolower(trim($commande["email"] ?? "")) === $email) {
            $ticket = floatval($commande["ticket_reduction"] ?? 0);
            $utilise = floatval($commande["ticket_reduction_utilise"] ?? 0);
            $total += max(0, $ticket - $utilise);
        }
    }

    return $total;
}

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
$fichierCommandes = $dirData . "/commandes.json";

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
        if (strtolower($compte["email"] ?? "") === $email) {
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

$commandesExistantes = file_exists($fichierCommandes)
    ? json_decode(file_get_contents($fichierCommandes), true)
    : [];

if (!is_array($commandesExistantes)) {
    $commandesExistantes = [];
}

$total = totalPanier($panier);
$ticketDisponible = calculerTicketDisponible($commandesExistantes, $email);

$utiliserReduction = isset($_POST["utiliser_reduction"]) && $_POST["utiliser_reduction"] === "1";

if ($utiliserReduction) {
    $reductionUtilisee = min($total, $ticketDisponible);
} else {
    $reductionUtilisee = 0;
}

$totalAPayer = max(0, $total - $reductionUtilisee);

$commande = [
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
    "total_avant_reduction" => $total,
    "reduction_utilisee" => $reductionUtilisee,
    "total_a_payer" => $totalAPayer,
    "total_paye" => 0,
    "reste_a_payer" => $totalAPayer,
    "ticket_reduction" => 0,
    "ticket_reduction_utilise" => 0,
    "paiements" => []
];

if ($totalAPayer <= 0) {
    utiliserTicketReduction($commandesExistantes, $email, $reductionUtilisee);

    $commande["id"] = !empty($commandesExistantes)
        ? max(array_column($commandesExistantes, "id")) + 1
        : 1;

    $commande["total_paye"] = 0;
    $commande["reste_a_payer"] = 0;

    $commande["paiements"][] = [
        "transaction" => "ticket_" . uniqid(),
        "montant" => 0,
        "statut" => "accepte",
        "type" => "ticket_reduction",
        "reduction_utilisee" => $reductionUtilisee,
        "date" => date("Y-m-d H:i:s")
    ];

    $commandesExistantes[] = $commande;

    file_put_contents(
        $fichierCommandes,
        json_encode($commandesExistantes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
        LOCK_EX
    );

    unset($_SESSION["panier"]);

    header("Location: Notation.php");
    exit();
}

$transaction = uniqid();
$montant = number_format($totalAPayer, 2, '.', '');
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
    "commande" => $commande
];
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="Images/Among_Us.png">
    <title>Paiement</title>
</head>

<body>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playwrite+AT:ital,wght@0,100..400;1,100..400&display=swap"
        rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Annie+Use+Your+Telescope&display=swap" rel="stylesheet">

    <header class="navbar">
        <a href="page-d'accueil.php" class="accueil">IMPOSTEUR</a>
        <div class="navliens">
            <div class="menu">
                <a>Réservation</a>
                <div class="infos">
                    <a href="reserver.php">Réserver une table</a>
                    <a href="mes-reservations.php">Mes réservations</a>
                    <a href="commander.php">Commander</a>
                </div>
            </div>
            <div class="menu">
                <a href="Notation.php">Notation</a>
            </div>
            <div class="menu">
                <a>Compte</a>
                <div class="infos">
                    <a href="profil.php">Voir Profil</a>
                    <a href="connexion.php">Connexion</a>
                    <a href="inscription.php">Inscription</a>
                    <a href="deconnexion.php">Deconnexion</a>
                </div>
            </div>
            <div class="menu">
                <a>Services</a>
                <div class="infos">
                    <a href="commandes.php">Commandes</a>
                    <a href="livraison.php">Livraison</a>
                </div>
            </div>
            <div class="menu">
                <a href="Admin.php">Admin</a>
            </div>
        </div>
    </header>

<div class="container2">
    <h4>Finaliser le paiement</h4>

    <p>Total de la commande : <strong><?php echo number_format($total, 2, ',', ' '); ?> €</strong></p>

    <?php if ($reductionUtilisee > 0) { ?>
        <p>Bon de réduction utilisé : <strong><?php echo number_format($reductionUtilisee, 2, ',', ' '); ?> €</strong></p>
    <?php } ?>

    <p>Montant à payer : <strong><?php echo number_format($totalAPayer, 2, ',', ' '); ?> €</strong></p>

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
    <p>📞 Téléphone : 07 67 01 02 03</p>
    <p>✉ Email : imposteurcontact@gmail.com</p>
    <p>Horaires : Lundi - Vendredi 10h-21h | Samedi - Dimanche 12h-18h</p>
</footer>
</body>
</html>
