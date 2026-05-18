<?php
session_start();

#if (!isset($_SESSION["statut"]) || $_SESSION["statut"] !== "Restaurateur") {
#    $_SESSION["erreur2"] = "Uniquement pour les restaurateurs.";
#    header("Location: connexion.php");
#    exit();
#}

$fichier = "data/commandes.json";
$commandes = [];

if (file_exists($fichier)) {
    $contenu = file_get_contents($fichier);
    $commandes = json_decode($contenu, true);
    if (!is_array($commandes)) {
        $commandes = [];
    }
}

function normaliserStatut($statut) {
    switch ($statut) {
        case "a_preparer":
            return "payee";
        case "en_attente_livreur":
            return "prete";
        case "payee":
            return "payee";
        case "en_preparation":
            return "en_preparation";
        case "prete":
            return "prete";
        case "en_livraison":
            return "en_livraison";
        case "livree":
            return "livree";
        default:
            return "payee";
    }
}

function labelStatut($statut) {
    switch ($statut) {
        case "payee":
            return "Payée";
        case "en_preparation":
            return "En préparation";
        case "prete":
            return "Prête";
        case "en_livraison":
            return "En livraison";
        case "livree":
            return "Livrée";
        default:
            return "Inconnu";
    }
}

function classeStatut($statut) {
    return "status-" . htmlspecialchars($statut);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST["id"] ?? null;
    $action = $_POST["action"] ?? null;
    $livreur = $_POST["livreur"] ?? null;

    if ($id && $action) {
        foreach ($commandes as &$commande) {
            if (isset($commande["id"]) && $commande["id"] == $id) {
                $statutActuel = normaliserStatut($commande["statut"] ?? "payee");
                $commande["statut"] = $statutActuel;

                if ($action === "preparer" && $statutActuel === "payee") {
                    $commande["statut"] = "en_preparation";
                } elseif ($action === "prete" && $statutActuel === "en_preparation") {
                    $commande["statut"] = "prete";
                } elseif ($action === "assigner" && $statutActuel === "prete" && !empty($livreur)) {
                    $commande["statut"] = "en_livraison";
                    $commande["livreur"] = $livreur;
                } elseif ($action === "livree" && $statutActuel === "en_livraison") {
                    $commande["statut"] = "livree";
                }

                break;
            }
        }
        unset($commande);
        file_put_contents(
            $fichier,
            json_encode($commandes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );

        header("Location: commandes.php");
        exit();
    }
}

$filtre = $_GET["statut"] ?? "toutes";

$livreurs = [
    "Livreur disponible 1",
    "Livreur disponible 2",
    "Livreur disponible 3"
];

$compteurs = [
    "payee" => 0,
    "en_preparation" => 0,
    "prete" => 0,
    "en_livraison" => 0,
    "livree" => 0
];

foreach ($commandes as &$commande) {
    $commande["statut"] = normaliserStatut($commande["statut"] ?? "payee");
}
unset($commande);

foreach ($commandes as $commande) {
    $s = $commande["statut"] ?? "payee";
    if (isset($compteurs[$s])) {
        $compteurs[$s]++;
    }
}

usort($commandes, function ($a, $b) {
    return ($b["id"] ?? 0) <=> ($a["id"] ?? 0);
});
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="Images/Among_Us.png">
    <title>Commandes - Restaurateur</title>
</head>

<body>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bungee&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playwrite+AT:ital,wght@0,100..400;1,100..400&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Annie+Use+Your+Telescope&display=swap" rel="stylesheet">

    <nav class="navbar">
        <a href="page-d'accueil.php" class="accueil">IMPOSTEUR</a>
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
            <input type="text" id="searchInput2" placeholder="Rechercher nos produits ..." autocomplete="off">
        </div>
    </nav>

    <div class="sitedescription3">
        <h1>Page des Commandes</h1>
    </div>

    <main class="container">

        <div class="filters">
            <a class="filter-btn2" href="commandes.php">Toutes</a>
            <a class="filter-btn2" href="commandes.php?statut=payee">Payées (<?php echo $compteurs["payee"]; ?>)</a>
            <a class="filter-btn2" href="commandes.php?statut=en_preparation">En préparation (<?php echo $compteurs["en_preparation"]; ?>)</a>
            <a class="filter-btn2" href="commandes.php?statut=prete">Prêtes (<?php echo $compteurs["prete"]; ?>)</a>
            <a class="filter-btn2" href="commandes.php?statut=en_livraison">En livraison (<?php echo $compteurs["en_livraison"]; ?>)</a>
            <a class="filter-btn2" href="commandes.php?statut=livree">Livrées (<?php echo $compteurs["livree"]; ?>)</a>
        </div>

        <div class="grid2">
            <?php
            $affichees = 0;
            foreach ($commandes as $commande) {
                $statut = $commande["statut"] ?? "payee";
                if ($filtre !== "toutes" && $statut !== $filtre) {
                    continue;
                }
                $affichees++;
                $plats = $commande["plats"] ?? [];
                $peutAttribuer = ($statut === "prete");
            ?>
                <div class="order-card <?php echo classeStatut($statut); ?>">

                    <div class="meta">
                        <div class="id">#<?php echo htmlspecialchars($commande["id"] ?? ""); ?></div>
                        <div class="customer"><?php echo htmlspecialchars($commande["client"] ?? "Anonyme"); ?></div>
                        <div class="date"><?php echo htmlspecialchars($commande["date"] ?? ""); ?></div>
                    </div>

                    <div class="details">

                        <div class="items">
                            <?php foreach ($plats as $plat) { ?>
                                <div class="item-row">
                                    <span><?php echo htmlspecialchars($plat["nom"] ?? "Produit"); ?></span>
                                    <span>x<?php echo htmlspecialchars($plat["quantite"] ?? 1); ?></span>
                                </div>
                            <?php } ?>
                        </div>

                        <div class="status-badge badge-<?php echo htmlspecialchars($statut); ?>">
                            <?php echo labelStatut($statut); ?>
                        </div>

                        <details class="order-details">
                            <summary>Voir le détail</summary>

                            <div class="order-extra">
                                <p><strong>Adresse :</strong> <?php echo htmlspecialchars($commande["adresse"] ?? ""); ?></p>
                                <p><strong>Téléphone :</strong> <?php echo htmlspecialchars($commande["telephone"] ?? ""); ?></p>
                                <p><strong>Email :</strong> <?php echo htmlspecialchars($commande["email"] ?? ""); ?></p>
                                <p><strong>Interphone :</strong> <?php echo htmlspecialchars($commande["interphone"] ?? ""); ?></p>
                                <p><strong>Étage :</strong> <?php echo htmlspecialchars($commande["etage"] ?? ""); ?></p>
                                <p><strong>Commentaires :</strong> <?php echo htmlspecialchars($commande["commentaires"] ?? ""); ?></p>
                                <p><strong>Livreur :</strong> <?php echo htmlspecialchars($commande["livreur"] ?? "Non assigné"); ?></p>

                                <h4>Produits</h4>
                                <?php foreach ($plats as $plat) {
                                    $nom = $plat["nom"] ?? "Produit";
                                    $quantite = (int)($plat["quantite"] ?? 1);
                                    $prix = (float)($plat["prix"] ?? 0);
                                    $sousTotal = $prix * $quantite;
                                ?>
                                    <div class="item-row">
                                        <span><?php echo htmlspecialchars($nom); ?></span>
                                        <span>x<?php echo $quantite; ?></span>
                                        <span><?php echo number_format($sousTotal, 2, ',', ' '); ?> €</span>
                                    </div>
                                <?php } ?>
                            </div>
                        </details>

                        <div class="actions">
                            <form method="POST" class="status-form">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($commande["id"] ?? ""); ?>">

                                <?php if ($statut === "payee") { ?>
                                    <button class="btn2 primary" type="submit" name="action" value="preparer">
                                        Passer en préparation
                                    </button>

                                <?php } elseif ($statut === "en_preparation") { ?>
                                    <button class="btn2 primary" type="submit" name="action" value="prete">
                                        Marquer prête
                                    </button>

                                <?php } elseif ($statut === "prete") { ?>
                                    <select name="livreur" class="livreur-select" required>
                                        <option value="">Choisir un livreur</option>
                                        <?php foreach ($livreurs as $livreur) { ?>
                                            <option value="<?php echo htmlspecialchars($livreur); ?>">
                                                <?php echo htmlspecialchars($livreur); ?>
                                            </option>
                                        <?php } ?>
                                    </select>

                                    <button class="btn2 primary assign-btn" type="submit" name="action" value="assigner" disabled>
                                        Assigner au livreur
                                    </button>

                                <?php } elseif ($statut === "en_livraison") { ?>
                                    <button class="btn2 success" type="submit" name="action" value="livree">
                                        Marquer livrée
                                    </button>

                                <?php } elseif ($statut === "livree") { ?>
                                    <p>Commande terminée</p>
                                <?php } ?>
                            </form>
                        </div>

                    </div>
                </div>

            <?php } ?>

            <?php if ($affichees === 0) { ?>
                <p>Aucune commande ne correspond à ce filtre.</p>
            <?php } ?>

        </div>
    </main>

    <footer class="footer">
        <p>📞 Téléphone : 07 67 01 02 03</p>
        <p>✉ Email : imposturecontact@gmail.com</p>
        <p>Horaires : Lundi - Vendredi 10h-21h | Samedi - Dimanche 12h-18h</p>
    </footer>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const forms = document.querySelectorAll(".status-form");

            forms.forEach(form => {
                const select = form.querySelector(".livreur-select");
                const button = form.querySelector(".assign-btn");

                if (select && button) {
                    const updateButtonState = () => {
                        button.disabled = select.value === "";
                    };

                    select.addEventListener("change", updateButtonState);
                    updateButtonState();
                }

                form.addEventListener("submit", (e) => {
                    const confirmation = confirm("Confirmer le changement de statut ?");
                    if (!confirmation) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>
</body>
</html>
