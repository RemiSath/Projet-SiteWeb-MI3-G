<?php
session_start();
include "bibliothèques/bloquer.php";

if (!isset($_SESSION["statut"]) || $_SESSION["statut"] !== "Restaurateur") {
    $_SESSION["erreur2"] = "Uniquement pour les restaurateurs.";
    header("Location: connexion.php");
    exit();
}

$fichier = "data/commandes.json";
$commandes = [];

if (file_exists($fichier)) {
    $contenu = file_get_contents($fichier);
    $commandes = json_decode($contenu, true);

    if (!is_array($commandes)) {
        $commandes = [];
    }
}

function normaliserStatut($statut)
{
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
        case "abandonnee":
            return "abandonnee";
        default:
            return "payee";
    }
}

function labelStatut($statut)
{
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
        case "abandonnee":
            return "abandonnee";
        default:
            return "Inconnu";
    }
}

function classeStatut($statut)
{
    return "status-" . htmlspecialchars($statut);
}

$isAjax =
    isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

$cheminComptes = "data/compte.json";
$comptes = [];

if (file_exists($cheminComptes)) {

    $jsonComptes = file_get_contents($cheminComptes);
    $comptes = json_decode($jsonComptes, true);

    if (!is_array($comptes)) {
        $comptes = [];
    }
}

$livreurs = [];

foreach ($comptes as $compte) {
    if (
        isset($compte["statut"]) &&
        $compte["statut"] === "Livreur" &&
        empty($compte["bloque"])
    ) {
        $livreurs[] = [
            "email" => $compte["email"] ?? "",
            "nom" => trim(
                ($compte["prenom"] ?? "") .
                " " .
                ($compte["nom"] ?? "")
            )
        ];
    }
}


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST["id"] ?? null;
    $action = $_POST["action"] ?? null;
    $livreurEmail = $_POST["livreur"] ?? null;
    $response = [
        "success" => false,
        "message" => "Action invalide."
    ];
    if ($id && $action) {
        foreach ($commandes as &$commande) {
            if (
                isset($commande["id"]) &&
                $commande["id"] == $id
            ) {
                $statutAvant =
                    normaliserStatut(
                        $commande["statut"] ?? "payee"
                    );
                $statutApres = $statutAvant;
                $changed = false;
                if (
                    $action === "preparer" &&
                    $statutAvant === "payee"
                ) {

                    $statutApres = "en_preparation";
                    $changed = true;
                }
                elseif (
                    $action === "prete" &&
                    $statutAvant === "en_preparation"
                ) {

                    $statutApres = "prete";
                    $changed = true;
                }
                elseif (
                    $action === "assigner" &&
                    $statutAvant === "prete" &&
                    !empty($livreurEmail)
                ) {
                    $nomLivreur = "";
                    foreach ($livreurs as $livreurData) {
                        if (
                            $livreurData["email"] === $livreurEmail
                        ) {
                            $nomLivreur =
                                $livreurData["nom"];
                            break;
                        }
                    }
                    $statutApres = "en_livraison";
                    $commande["livreur_email"] =
                        $livreurEmail;
                    $commande["livreur"] =
                        $nomLivreur;

                    $changed = true;
                }
                elseif (
                    $action === "livree" &&
                    $statutAvant === "en_livraison"
                ) {
                    $statutApres = "livree";
                    $changed = true;
                }
                if ($changed) {
                    $commande["statut"] = $statutApres;
                    file_put_contents(
                        $fichier,
                        json_encode(
                            $commandes,
                            JSON_PRETTY_PRINT |
                            JSON_UNESCAPED_UNICODE
                        )
                    );
                    $response = [
                        "success" => true,
                        "id" => $id,
                        "old_status" => $statutAvant,
                        "new_status" => $statutApres,
                        "livreur" =>
                            $commande["livreur"] ?? ""
                    ];
                } else {
                    $response["message"] =
                        "Transition de statut invalide.";
                }
                break;
            }
        }
        unset($commande);
    } else {
        $response["message"] =
            "Requête incomplète.";
    }
    if ($isAjax) {
        header(
            "Content-Type: application/json; charset=utf-8"
        );
        echo json_encode($response);
        exit();
    }

    if ($response["success"]) {
        header("Location: commandes.php");
        exit();
    }
}

$compteurs = [
    "payee" => 0,
    "en_preparation" => 0,
    "prete" => 0,
    "en_livraison" => 0,
    "livree" => 0
];

$totalCommandes = count($commandes);

foreach ($commandes as &$commande) {

    $commande["statut"] =
        normaliserStatut(
            $commande["statut"] ?? "payee"
        );
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

    <title>
        Commandes - Restaurateur
    </title>

    <link rel="stylesheet" href="styles.css">

    <link rel="icon" href="Images/Among_Us.png">
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
<nav class="navbar">
    <a href="page-d'accueil.php" class="accueil">
        IMPOSTEUR
    </a>
    <div class="navliens">
        <div class="menu">
            <a>Réservation</a>
            <div class="infos">
                <a href="reserver.php">
                    Réserver une table
                </a>
                <a href="commander.php">
                    Commander
                </a>
            </div>
        </div>
        <div class="menu">
            <a href="Notation.php">
                Notation
            </a>
        </div>
        <div class="menu">
            <a>Compte</a>
            <div class="infos">
                <a href="profil.php">
                    Voir Profil
                </a>
                <a href="connexion.php">
                    Connexion
                </a>
                <a href="inscription.php">
                    Inscription
                </a>
                <a href="deconnexion.php">
                    Déconnexion
                </a>

            </div>
        </div>
        <div class="menu">
            <a>Services</a>
            <div class="infos">
                <a href="commandes.php">
                    Commandes
                </a>
                <a href="livraison.php">
                    Livraison
                </a>
            </div>
        </div>
        <div class="menu">
            <a href="Admin.php">
                Admin
            </a>
        </div>
    </div>
</nav>

<div class="sitedescription3">
    <h1>Page des Commandes</h1>
</div>

<main class="container">

    <div class="filters">

        <a class="filter-btn2" data-filter="toutes" href="#">
            Toutes les commandes
            (<span id="count-toutes">
                <?php echo $totalCommandes; ?>
            </span>)
        </a>
        <a class="filter-btn2" data-filter="payee" href="#">
            Payées
            (<span id="count-payee">
                <?php echo $compteurs["payee"]; ?>
            </span>)
        </a>
        <a class="filter-btn2" data-filter="en_preparation" href="#">
            En préparation
            (<span id="count-en_preparation">
                <?php echo $compteurs["en_preparation"]; ?>
            </span>)
        </a>
        <a class="filter-btn2" data-filter="prete" href="#">
            Prêtes
            (<span id="count-prete">
                <?php echo $compteurs["prete"]; ?>
            </span>)
        </a>
        <a class="filter-btn2" data-filter="en_livraison" href="#">
            En livraison
            (<span id="count-en_livraison">
                <?php echo $compteurs["en_livraison"]; ?>
            </span>)
        </a>
        <a class="filter-btn2" data-filter="livree" href="#">
            Livrées
            (<span id="count-livree">
                <?php echo $compteurs["livree"]; ?>
            </span>)
        </a>
    </div>
    <div class="grid2">
        <?php foreach ($commandes as $commande) {
            $statut = $commande["statut"] ?? "payee";
            $plats = $commande["plats"] ?? [];
        ?>
        <div
            class="order-card <?php echo classeStatut($statut); ?>"
            data-status="<?php echo htmlspecialchars($statut); ?>"
        >
            <div class="meta">
                <div class="id">
                    #<?php echo htmlspecialchars($commande["id"] ?? ""); ?>
                </div>
                <div class="customer">
                    <?php echo htmlspecialchars($commande["client"] ?? "Anonyme"); ?>
                </div>
                <div class="date">
                    <?php echo htmlspecialchars($commande["date"] ?? ""); ?>
                </div>
            </div>
            <div class="details">
                <div class="items">
                    <?php foreach ($plats as $plat) { ?>
                    <div class="item-row">
                        <span>
                            <?php echo htmlspecialchars($plat["nom"] ?? "Produit"); ?>
                        </span>
                        <span>
                            x<?php echo htmlspecialchars($plat["quantite"] ?? 1); ?>
                        </span>
                    </div>
                    <?php } ?>
                </div>
                <div class="status-badge badge-<?php echo htmlspecialchars($statut); ?>">
                    <?php echo labelStatut($statut); ?>
                </div>
                <details class="order-details">
                    <summary>
                        Voir le détail
                    </summary>
                    <div class="order-extra">
                        <p>
                            <strong>Adresse :</strong>
                            <?php echo htmlspecialchars($commande["adresse"] ?? ""); ?>
                        </p>
                        <p>
                            <strong>Téléphone :</strong>
                            <?php echo htmlspecialchars($commande["telephone"] ?? ""); ?>
                        </p>
                        <p>
                            <strong>Email :</strong>
                            <?php echo htmlspecialchars($commande["email"] ?? ""); ?>
                        </p>
                        <p>
                            <strong>Livreur :</strong>
                            <?php echo htmlspecialchars($commande["livreur"] ?? "Non assigné"); ?>
                        </p>
                    </div>
                </details>
                <div class="actions">
                    <form method="POST">
                        <input
                            type="hidden"
                            name="id"
                            value="<?php echo htmlspecialchars($commande["id"] ?? ""); ?>"
                        >
                        <?php if ($statut === "payee") { ?>
                            <button
                                class="btn2 primary"
                                type="submit"
                                name="action"
                                value="preparer"
                            >
                                Passer en préparation
                            </button>
                        <?php } elseif ($statut === "en_preparation") { ?>
                            <button
                                class="btn2 primary"
                                type="submit"
                                name="action"
                                value="prete"
                            >
                                Marquer prête
                            </button>
                        <?php } elseif ($statut === "prete") { ?>
                            <select
                                name="livreur"
                                class="livreur-select"
                                required
                            >
                                <option value="">
                                    Choisir un livreur
                                </option>
                                <?php foreach ($livreurs as $livreur) { ?>
                                <option
                                    value="<?php echo htmlspecialchars($livreur["email"]); ?>"
                                >
                                    <?php echo htmlspecialchars($livreur["nom"]); ?>
                                </option>
                                <?php } ?>
                            </select>
                            <button
                                class="btn2 primary assign-btn"
                                type="submit"
                                name="action"
                                value="assigner"
                                disabled
                            >
                                Assigner au livreur
                            </button>
                        <?php } elseif ($statut === "en_livraison") { ?>
                            <p>
                                Livraison en cours
                            </p>
                        <?php } elseif ($statut === "livree") { ?>
                            <p>
                                Commande terminée
                            </p>
                        <?php } ?>
                    </form>
                </div>
            </div>
        </div>
        <?php } ?>
        <p
            class="no-results-message"
            style="display:none;"
        >
            Aucune commande ne correspond à ce filtre.
        </p>
    </div>
</main>

<footer class="footer">
    <p>📞 Téléphone : 07 67 01 02 03</p>
    <p>✉ Email : imposturecontact@gmail.com</p>
</footer>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const cards = document.querySelectorAll(".order-card");
    const filterButtons = document.querySelectorAll(".filter-btn2");
    const noResults = document.querySelector(".no-results-message");
    function filtrerCommandes(filtre) {
        let visible = 0;
        cards.forEach(card => {
            const status =
                card.dataset.status;
            if (
                filtre === "toutes" ||
                status === filtre
            ) {
                card.style.display = "block";
                visible++;
            } else {
                card.style.display = "none";
            }
        });
        if (visible === 0) {
            noResults.style.display = "block";
        } else {
            noResults.style.display = "none";
        }
        filterButtons.forEach(btn => {
            btn.classList.remove("active");
        });
        const activeBtn =
            document.querySelector(
                `[data-filter="${filtre}"]`
            );
        if (activeBtn) {
            activeBtn.classList.add("active");
        }
    }
    filterButtons.forEach(button => {
        button.addEventListener("click", (e) => {
            e.preventDefault();
            const filtre =
                button.dataset.filter;
            filtrerCommandes(filtre);
        });
    });
    document
        .querySelectorAll(".livreur-select")
        .forEach(select => {
            select.addEventListener("change", () => {
                const form =
                    select.closest("form");
                const btn =
                    form.querySelector(".assign-btn");
                btn.disabled =
                    select.value === "";
            });
        });

    filtrerCommandes("toutes");
});
</script>
</body>
</html>
