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

$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST["id"] ?? null;
    $action = $_POST["action"] ?? null;
    $livreur = $_POST["livreur"] ?? null;

    $response = ["success" => false, "message" => "Action invalide."];

    if ($id && $action) {
        foreach ($commandes as &$commande) {
            if (isset($commande["id"]) && $commande["id"] == $id) {
                $statutAvant = normaliserStatut($commande["statut"] ?? "payee");
                $statutApres = $statutAvant;
                $changed = false;

                if ($action === "preparer" && $statutAvant === "payee") {
                    $statutApres = "en_preparation";
                    $changed = true;
                } elseif ($action === "prete" && $statutAvant === "en_preparation") {
                    $statutApres = "prete";
                    $changed = true;
                } elseif ($action === "assigner" && $statutAvant === "prete" && !empty($livreur)) {
                    $statutApres = "en_livraison";
                    $commande["livreur"] = $livreur;
                    $changed = true;
                } elseif ($action === "livree" && $statutAvant === "en_livraison") {
                    $statutApres = "livree";
                    $changed = true;
                }

                if ($changed) {
                    $commande["statut"] = $statutApres;

                    file_put_contents(
                        $fichier,
                        json_encode($commandes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
                    );

                    $response = [
                        "success" => true,
                        "id" => $id,
                        "old_status" => $statutAvant,
                        "new_status" => $statutApres,
                        "livreur" => $commande["livreur"] ?? ""
                    ];
                } else {
                    $response["message"] = "Transition de statut invalide.";
                }

                break;
            }
        }
        unset($commande);
    } else {
        $response["message"] = "Requête incomplète.";
    }

    if ($isAjax) {
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode($response);
        exit();
    }

    if ($response["success"]) {
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

$totalCommandes = count($commandes);

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
            <a class="filter-btn2" href="commandes.php">Toutes les commandes (<span id="count-toutes"><?php echo $totalCommandes; ?></span>)</a>
            <a class="filter-btn2" href="commandes.php?statut=payee">Payées (<span id="count-payee"><?php echo $compteurs["payee"]; ?></span>)</a>
            <a class="filter-btn2" href="commandes.php?statut=en_preparation">En préparation (<span id="count-en_preparation"><?php echo $compteurs["en_preparation"]; ?></span>)</a>
            <a class="filter-btn2" href="commandes.php?statut=prete">Prêtes (<span id="count-prete"><?php echo $compteurs["prete"]; ?></span>)</a>
            <a class="filter-btn2" href="commandes.php?statut=en_livraison">En livraison (<span id="count-en_livraison"><?php echo $compteurs["en_livraison"]; ?></span>)</a>
            <a class="filter-btn2" href="commandes.php?statut=livree">Livrées (<span id="count-livree"><?php echo $compteurs["livree"]; ?></span>)</a>
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
                <div class="order-card <?php echo classeStatut($statut); ?>" data-order-id="<?php echo htmlspecialchars($commande["id"] ?? ""); ?>">

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
    const currentFilter = new URLSearchParams(window.location.search).get("statut") || "toutes";

    const statusLabels = {
        payee: "Payée",
        en_preparation: "En préparation",
        prete: "Prête",
        en_livraison: "En livraison",
        livree: "Livrée"
    };

    const livreurs = <?php echo json_encode($livreurs, JSON_UNESCAPED_UNICODE); ?>;

    function escapeHtml(str) {
        return String(str).replace(/[&<>"']/g, (m) => ({
            "&": "&amp;",
            "<": "&lt;",
            ">": "&gt;",
            '"': "&quot;",
            "'": "&#039;"
        }[m]));
    }

    function renderActions(status) {
        if (status === "payee") {
            return `<button class="btn2 primary" type="submit" name="action" value="preparer">Passer en préparation</button>`;
        }

        if (status === "en_preparation") {
            return `<button class="btn2 primary" type="submit" name="action" value="prete">Marquer prête</button>`;
        }

        if (status === "prete") {
            return `
                <select name="livreur" class="livreur-select" required>
                    <option value="">Choisir un livreur</option>
                    ${livreurs.map(l => `<option value="${escapeHtml(l)}">${escapeHtml(l)}</option>`).join("")}
                </select>
                <button class="btn2 primary assign-btn" type="submit" name="action" value="assigner" disabled>
                    Assigner au livreur
                </button>`;
        }

        if (status === "en_livraison") {
            return `<button class="btn2 success" type="submit" name="action" value="livree">Marquer livrée</button>`;
        }

        if (status === "livree") {
            return `<p>Commande terminée</p>`;
        }

        return "";
    }

    function bindLivreurSelect(form) {
        const select = form.querySelector(".livreur-select");
        const button = form.querySelector(".assign-btn");

        if (select && button) {
            const updateButtonState = () => {
                button.disabled = select.value === "";
            };

            select.addEventListener("change", updateButtonState);
            updateButtonState();
        }
    }

    function updateCounters(oldStatus, newStatus) {
        const oldCounter = document.getElementById(`count-${oldStatus}`);
        const newCounter = document.getElementById(`count-${newStatus}`);
        if (oldCounter) {
            oldCounter.textContent = Math.max(0, parseInt(oldCounter.textContent, 10) - 1);
        }
        if (newCounter) {
            newCounter.textContent = parseInt(newCounter.textContent, 10) + 1;
        }
    }

    function updateCard(card, data) {
        card.dataset.status = data.new_status;
        card.className = card.className
            .replace(/\bstatus-\S+/g, "")
            .trim() + ` status-${data.new_status}`;
        const badge = card.querySelector(".status-badge");
        if (badge) {
            badge.className = `status-badge badge-${data.new_status}`;
            badge.textContent = statusLabels[data.new_status] || "Inconnu";
        }
        const form = card.querySelector(".status-form");
        if (form) {
            const id = form.querySelector('input[name="id"]')?.value || data.id;
            form.innerHTML = `<input type="hidden" name="id" value="${escapeHtml(id)}">${renderActions(data.new_status)}`;
            bindLivreurSelect(form);
        }
        updateCounters(data.old_status, data.new_status);
        if (currentFilter !== "toutes" && currentFilter !== data.new_status) {
            card.remove();
        }
    }
    document.querySelectorAll(".status-form").forEach(form => {
        bindLivreurSelect(form);
        form.addEventListener("submit", async (e) => {
            e.preventDefault();
            const ok = confirm("Confirmer le changement de statut ?");
            if (!ok) return;
            try {
                const formData = new FormData(form);
                if (e.submitter && e.submitter.name) {
                    formData.append(e.submitter.name, e.submitter.value);
                }
                const response = await fetch("commandes.php", {
                    method: "POST",
                    body: formData,
                    headers: {
                        "X-Requested-With": "XMLHttpRequest"
                    }
                });
                const data = await response.json();
                if (!data.success) {
                    alert(data.message || "Erreur lors de la mise à jour.");
                    return;
                }
                const card = form.closest(".order-card");
                if (card) {
                    updateCard(card, data);
                }
            } catch (error) {
                alert("Erreur réseau.");
            }
        });
    });
});
</script>
</body>
</html>
