<?php
session_start();

if(!isset($_SESSION["email"])){ 
    $_SESSION["erreur"] = "Accès réservé aux livreurs.";
    header("Location: page-d'accueil.php");
    exit;
}

if (!isset($_SESSION["statut"]) || $_SESSION["statut"] !== "Livreur") {
    $_SESSION["erreur"] = "Accès réservé aux livreurs.";
    header("Location: page-d'accueil.php");
    exit();
}

$fichier = "data/commandes.json";
$commandes = [];

if (file_exists($fichier)) {
    $json = file_get_contents($fichier);
    $commandes = json_decode($json, true);

    if (!is_array($commandes)) {
        $commandes = [];
    }
}

$livreurEmail = $_SESSION["email"] ?? "";
$livreurNom = trim(($_SESSION["prenom"] ?? "") . " " . ($_SESSION["nom"] ?? ""));
$identifiantLivreur = $livreurEmail !== "" ? $livreurEmail : $livreurNom;

function labelStatut($statut)
{
    switch ($statut) {
        case "a_preparer":
            return "À préparer";
        case "payee":
            return "Payée";
        case "en_preparation":
            return "En préparation";
        case "prete":
            return "Prête";
        case "en_attente_livreur":
            return "En attente d'un livreur";
        case "en_livraison":
            return "En livraison";
        case "livree":
            return "Livrée";
        case "abandonnee":
            return "Abandonnée";
        default:
            return "Inconnu";
    }
}

function classStatut($statut)
{
    return "status-" . htmlspecialchars($statut);
}

usort($commandes, function ($a, $b) {
    return ($b["id"] ?? 0) <=> ($a["id"] ?? 0);
});

$commandesAttribuees = [];

foreach ($commandes as $commande) {
    $livreurCommande = $commande["livreur_email"] ?? ($commande["livreur"] ?? "");
    $statutCommande = $commande["statut"] ?? "";

    if (
        $livreurCommande === $identifiantLivreur &&
        $statutCommande === "en_livraison"
    ) {
        $commandesAttribuees[] = $commande;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Livraison</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="Images/Among_Us.png">
</head>

<body>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playwrite+AT:ital,wght@0,100..400;1,100..400&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Annie+Use+Your+Telescope&display=swap" rel="stylesheet">

<header class="navbar">
    <a href="page-d'accueil.php" class="accueil">IMPOSTURE</a>
    <div class="navliens">
        <div class="menu">
            <a>Réservation</a>
            <div class="infos">
                <a href="reserver.php">Réserver une table</a>
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

<div class="delivery-container">
    <h6>📦 Livraison en cours</h6>

    <?php if (empty($commandesAttribuees)) { ?>
        <div class="card3">
            <h3>Aucune livraison attribuée</h3>
            <p>Tu n’as aucune commande en cours de livraison pour le moment.</p>
        </div>
    <?php } else { ?>

        <?php foreach ($commandesAttribuees as $commande) {
            $commandeId = $commande["id"] ?? "";
            $client = $commande["client"] ?? "Client";
            $adresse = $commande["adresse"] ?? "";
            $telephone = $commande["telephone"] ?? "";
            $email = $commande["email"] ?? "";
            $interphone = $commande["interphone"] ?? "";
            $etage = $commande["etage"] ?? "";
            $commentaires = $commande["commentaires"] ?? "";
            $statut = $commande["statut"] ?? "en_livraison";
            $plats = $commande["plats"] ?? [];

            $adresseGoogle = "https://www.google.com/maps/search/?api=1&query=" . urlencode($adresse);
            $adresseWaze = "https://www.waze.com/ul?q=" . urlencode($adresse) . "&navigate=yes";
        ?>
        <div class="card3 <?php echo classStatut($statut); ?>">
            <h3>Commande #<?php echo htmlspecialchars($commandeId); ?></h3>
            <div class="meta" style="margin-bottom: 15px;">
                <p><strong>Client :</strong> <?php echo htmlspecialchars($client); ?></p>
                <p><strong>Date :</strong> <?php echo htmlspecialchars($commande["date"] ?? ""); ?></p>
                <p><strong>Statut :</strong> <?php echo labelStatut($statut); ?></p>
            </div>

            <h4>Adresse</h4>
            <p><?php echo nl2br(htmlspecialchars($adresse)); ?></p>
            <div style="margin-top: 12px;">
                <a href="<?php echo htmlspecialchars($adresseGoogle); ?>" target="_blank" class="btn4 map-btn4">
                    🗺 Ouvrir dans Maps
                </a>

                <a href="<?php echo htmlspecialchars($adresseWaze); ?>" target="_blank" class="btn4 map-btn4">
                    🚗 Ouvrir dans Waze
                </a>
            </div>
            <h4 style="margin-top: 20px;">Produits</h4>
            <div class="items">
                <?php foreach ($plats as $plat) {
                    $nomPlat = $plat["nom"] ?? "Produit";
                    $quantite = (int)($plat["quantite"] ?? 1);
                    $prix = (float)($plat["prix"] ?? 0);
                    $sousTotal = $prix * $quantite;
                ?>
                    <div class="item-row">
                        <span><?php echo htmlspecialchars($nomPlat); ?></span>
                        <span>x<?php echo $quantite; ?></span>
                        <span><?php echo number_format($sousTotal, 2, ',', ' '); ?> €</span>
                    </div>
                <?php } ?>
            </div>
            <h4 style="margin-top: 20px;">Informations client</h4>
            <p><strong>Code interphone :</strong> <?php echo htmlspecialchars($interphone); ?></p>
            <p><strong>Étage :</strong> <?php echo htmlspecialchars($etage); ?></p>
            <p><strong>Commentaires :</strong> <?php echo htmlspecialchars($commentaires); ?></p>
            <p><strong>Téléphone :</strong> <?php echo htmlspecialchars($telephone); ?></p>
            <p><strong>Email :</strong> <?php echo htmlspecialchars($email); ?></p>
            <a href="tel:<?php echo htmlspecialchars($telephone); ?>" class="btn4 call-btn4">
                📞 Appeler le client
            </a>
            <form method="post" action="maj_livraison.php" style="margin-top: 20px;">
                <input type="hidden" name="commande_id" value="<?php echo htmlspecialchars($commandeId); ?>">
                <button type="submit" name="action" value="livree" class="btn4 success-btn4">
                    ✅ Livraison terminée
                </button>
                <button type="submit" name="action" value="abandonnee" class="btn4" style="background:#b33; color:#fff; margin-left:10px;">
                    ❌ Abandonnée
                </button>
            </form>
        </div>
        <?php } ?>
    <?php } ?>
</div>

    <footer class="footer">
        <p>📞 Téléphone : 07 67 01 02 03</p>
        <p>✉ Email : imposteurcontact@gmail.com</p>
        <p>Horaires : Lundi - Vendredi 10h-21h | Samedi - Dimanche 12h-18h</p>
    </footer>

</body>
</html>
