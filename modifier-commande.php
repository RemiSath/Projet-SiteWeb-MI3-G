<?php
session_start();

if (!isset($_SESSION["email"])) {
    header("Location: connexion.php");
    exit();
}

function h($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}

function totalPlats($plats)
{
    $total = 0;
    foreach ($plats as $plat) {
        $total += $plat["prix"] * $plat["quantite"];
    }
    return $total;
}

function commandeModifiable($statut)
{
    return in_array($statut, ["a_preparer", "payee", "en_attente"], true);
}

$produits = [
    ["nom" => "Citron", "prix" => 6.5],
    ["nom" => "Fraise", "prix" => 6.8],
    ["nom" => "Framboise", "prix" => 6.8],
    ["nom" => "Mandarine", "prix" => 6.9],
    ["nom" => "Mangue", "prix" => 7.5],
    ["nom" => "Pomme", "prix" => 7.0],
    ["nom" => "Poire", "prix" => 7.2],
    ["nom" => "Noix de coco", "prix" => 6.9],
    ["nom" => "Tasses", "prix" => 6.8],
    ["nom" => "Pommes de pin", "prix" => 7.5],
    ["nom" => "Graine de café", "prix" => 6.8],
    ["nom" => "Noisette", "prix" => 7.5],
    ["nom" => "Oeuf au plat", "prix" => 7.5],
    ["nom" => "Cacahuète", "prix" => 6.9],
    ["nom" => "Pêche", "prix" => 6.9]
];

$id = intval($_GET["id"] ?? 0);
$fichierCommandes = __DIR__ . "/data/commandes.json";

$commandes = file_exists($fichierCommandes)
    ? json_decode(file_get_contents($fichierCommandes), true)
    : [];

if (!is_array($commandes)) {
    $commandes = [];
}

$commande = null;
$emailSession = strtolower(trim($_SESSION["email"]));

foreach ($commandes as $cmd) {
    if (
        intval($cmd["id"] ?? 0) === $id &&
        strtolower(trim($cmd["email"] ?? "")) === $emailSession
    ) {
        $commande = $cmd;
        break;
    }
}

if (!$commande) {
    header("Location: profil.php");
    exit();
}

$statut = $commande["statut"] ?? "a_preparer";
$modifiable = commandeModifiable($statut);
$plats = $commande["plats"] ?? [];
$total = $commande["total_actuel"] ?? totalPlats($plats);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="Images/Among_Us.png">
    <title>Modifier la commande</title>
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
    <h4>Modifier la commande #<?php echo h($commande["id"]); ?></h4>

    <?php if (isset($_SESSION["erreur"])) { ?>
        <div class="erreur"><?php echo h($_SESSION["erreur"]); unset($_SESSION["erreur"]); ?></div>
    <?php } ?>

    <?php if (isset($_SESSION["message"])) { ?>
        <div class="message"><?php echo h($_SESSION["message"]); unset($_SESSION["message"]); ?></div>
    <?php } ?>

    <?php if (!$modifiable) { ?>
        <p>Cette commande est déjà en préparation. Elle ne peut plus être modifiée.</p>
    <?php } else { ?>
        <p>Total actuel : <strong><?php echo number_format($total, 2, ',', ' '); ?> €</strong></p>

        <?php if (!empty($commande["ticket_reduction"])) { ?>
            <p>Ticket de réduction disponible : <strong><?php echo number_format($commande["ticket_reduction"], 2, ',', ' '); ?> €</strong></p>
        <?php } ?>

        <h5>Produits dans la commande</h5>

        <ul class="cart-items">
            <?php foreach ($plats as $plat) { ?>
                <li>
                    <span><?php echo h($plat["nom"]); ?></span>
                    <span>x<?php echo h($plat["quantite"]); ?></span>
                    <span><?php echo number_format($plat["prix"] * $plat["quantite"], 2, ',', ' '); ?> €</span>

                    <form method="post" action="modifier-commande-action.php">
                        <input type="hidden" name="commande_id" value="<?php echo h($commande["id"]); ?>">
                        <input type="hidden" name="nom" value="<?php echo h($plat["nom"]); ?>">
                        <input type="hidden" name="action" value="retirer">
                        <button type="submit">Retirer</button>
                    </form>
                </li>
            <?php } ?>
        </ul>

        <h5>Ajouter un produit</h5>

        <div class="grid2">
            <?php foreach ($produits as $produit) { ?>
                <div class="carte">
                    <p><strong><?php echo h($produit["nom"]); ?></strong></p>
                    <p><?php echo number_format($produit["prix"], 2, ',', ' '); ?> €</p>

                    <form method="post" action="modifier-commande-action.php">
                        <input type="hidden" name="commande_id" value="<?php echo h($commande["id"]); ?>">
                        <input type="hidden" name="nom" value="<?php echo h($produit["nom"]); ?>">
                        <input type="hidden" name="prix" value="<?php echo h($produit["prix"]); ?>">
                        <input type="hidden" name="action" value="ajouter">
                        <button type="submit">Ajouter</button>
                    </form>
                </div>
            <?php } ?>
        </div>
    <?php } ?>

    <a class="btn-link" href="profil.php">Retour au profil</a>
</div>

    <footer class="footer">
        <p>📞 Téléphone : 07 67 01 02 03</p>
        <p>✉ Email : imposteurcontact@gmail.com</p>
        <p>Horaires : Lundi - Vendredi 10h-21h | Samedi - Dimanche 12h-18h</p>
    </footer>
</body>
</html>