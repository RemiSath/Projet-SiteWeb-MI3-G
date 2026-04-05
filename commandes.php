<?php
    session_start();

    if(!isset($_SESSION["statut"]) || $_SESSION["statut"] !== "Restaurateur"){ // Vérifie que l'utilisateur est connecté et a le statut de restaurateur
        $_SESSION["erreur2"] = "Uniquement pour les restaurateurs.";
        header("Location: connexion.php");
        exit();
    }

    $fichier = "data/commandes.json";
    $commandes = [];

    if(file_exists($fichier)){
        $contenu = file_get_contents($fichier);
        $commandes = json_decode($contenu, true);
        if(!is_array($commandes)){ // Vérifie que le contenu est un tableau
            $commandes = [];
        }
    }

    function labelStatut($statut){ // Retourne une étiquette lisible pour un statut donné
        switch ($statut){
            case "a_preparer":
                return "À préparer";
            case "en_attente":
                return "En attente";
            case "en_cours":
                return "En cours";
            case "en_livraison":
                return "En livraison";
            case "livree":
                return "Livrée";
            default:
                return "Inconnu";
        }
    }

    function classeStatut($statut){ // Retourne une classe CSS pour un statut donné (utilisée pour le style)
        return "status-" . htmlspecialchars($statut);
    }

    $filtre = $_GET["statut"] ?? "toutes";

    $livreurs = [ // Liste fictive de livreurs pour la phase 3
        "Livreur disponible 1",
        "Livreur disponible 2",
        "Livreur disponible 3"
    ];

    $compteurs = [ // Initialise les compteurs pour chaque statut
        "a_preparer" => 0,
        "en_attente" => 0,
        "en_cours" => 0,
        "en_livraison" => 0,
        "livree" => 0
    ];

    foreach($commandes as $commande){ // Compte le nombre de commandes pour chaque statut
        $s = $commande["statut"] ?? "a_preparer";
        if(isset($compteurs[$s])){
            $compteurs[$s]++;
        }
    }

    usort($commandes, function ($a, $b){ // Trie les commandes par ID décroissant (les plus récentes en premier)
        return ($b["id"] ?? 0) <=> ($a["id"] ?? 0);
    });
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commandes</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="Images/Among_Us.png">
</head>

<body>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playwrite+AT:ital,wght@0,100..400;1,100..400&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Annie+Use+Your+Telescope&display=swap" rel="stylesheet">

    <header class="navbar">
        <a href="page-d'accueil.php" class="accueil">IMPOSTURE</a>
        <div class="navliens">
            <div class="menu">
                <a>Réservation</a>
                <div class="infos">
                    <a href="reserver.html">Réserver une table</a>
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
                    <a href="livraison.html">Livraison</a>
                </div>
            </div>
            <div class="menu">
                <a href="Admin.php">Admin</a>
            </div>
            <input type="text" id="searchInput2" placeholder="Rechercher nos produits ..." autocomplete="off">
        </div>
    </header>

    <div class="sitedescription3">
        <h1>Page des Commandes</h1>
    </div>

    <main class="container"> <!-- Conteneur principal pour les commandes -->
        <div class="filters">
            <a class="filter-btn2" href="commandes.php">Toutes</a>
            <a class="filter-btn2" href="commandes.php?statut=a_preparer">À préparer (<?php echo $compteurs["a_preparer"]; ?>)</a>
            <a class="filter-btn2" href="commandes.php?statut=en_attente">En attente (<?php echo $compteurs["en_attente"]; ?>)</a>
            <a class="filter-btn2" href="commandes.php?statut=en_cours">En cours (<?php echo $compteurs["en_cours"]; ?>)</a>
            <a class="filter-btn2" href="commandes.php?statut=en_livraison">En livraison (<?php echo $compteurs["en_livraison"]; ?>)</a>
            <a class="filter-btn2" href="commandes.php?statut=livree">Livrée (<?php echo $compteurs["livree"]; ?>)</a>
        </div>

        <div class="grid2">
            <?php
            $affichees = 0;

            foreach($commandes as $commande){ // Parcourt les commandes pour les afficher
                $statut = $commande["statut"] ?? "a_preparer";

                if($filtre !== "toutes" && $statut !== $filtre){
                    continue;
                }

                $affichees++;
                $plats = $commande["plats"] ?? [];
                ?>
                <div class="order-card <?php echo classeStatut($statut); ?>"> <!-- Carte de commande avec une classe CSS basée sur le statut -->
                    <div class="meta">
                        <div class="id">#<?php echo htmlspecialchars($commande["id"] ?? ""); ?></div>
                        <div class="customer"><?php echo htmlspecialchars($commande["client"] ?? "Anonyme"); ?></div>
                        <div class="date"><?php echo htmlspecialchars($commande["date"] ?? ""); ?></div>
                    </div>

                    <div class="details">
                        <div class="items">
                            <?php foreach($plats as $plat){ ?> <!-- Affiche les plats de la commande -->
                                <div class="item-row">
                                    <span><?php echo htmlspecialchars($plat["nom"] ?? "Produit"); ?></span>
                                    <span>x<?php echo htmlspecialchars($plat["quantite"] ?? 1); ?></span>
                                </div>
                            <?php } ?>
                        </div>

                        <div class="status-badge badge-<?php echo htmlspecialchars($statut); ?>"> <!-- Affiche le statut de la commande avec un badge coloré -->
                            <?php echo labelStatut($statut); ?>
                        </div>

                        <details class="order-details">
                            <summary>Voir le détail</summary>

                            <div class="order-extra"> <!-- Affiche les informations supplémentaires de la commande -->
                                <p><strong>Adresse :</strong> <?php echo htmlspecialchars($commande["adresse"] ?? ""); ?></p>
                                <p><strong>Téléphone :</strong> <?php echo htmlspecialchars($commande["telephone"] ?? ""); ?></p>
                                <p><strong>Email :</strong> <?php echo htmlspecialchars($commande["email"] ?? ""); ?></p>
                                <p><strong>Interphone :</strong> <?php echo htmlspecialchars($commande["interphone"] ?? ""); ?></p>
                                <p><strong>Étage :</strong> <?php echo htmlspecialchars($commande["etage"] ?? ""); ?></p>
                                <p><strong>Commentaires :</strong> <?php echo htmlspecialchars($commande["commentaires"] ?? ""); ?></p>

                                <h4>Produits</h4>
                                <?php foreach($plats as $plat){ // Affiche les détails de chaque plat (nom, quantité, prix, sous-total)
                                    $nom = $plat["nom"] ?? "Produit";
                                    $quantite = (int)($plat["quantite"] ?? 1);
                                    $prix = (float)($plat["prix"] ?? 0);
                                    $sousTotal = $prix * $quantite;
                                ?>
                                    <div class="item-row"> <!-- Affiche une ligne pour chaque produit avec son nom, sa quantité et son sous-total -->
                                        <span><?php echo htmlspecialchars($nom); ?></span>
                                        <span>x<?php echo $quantite; ?></span>
                                        <span><?php echo number_format($sousTotal, 2, ',', ' '); ?> €</span>
                                    </div>
                                <?php } ?>
                            </div>
                        </details>

                        <div class="actions">  <!-- Affiche les boutons d'action pour la commande (désactivés pour l'instant, préparés pour la phase 3) -->
                            <button class="btn2 primary" disabled>Mettre en livraison</button>
                            <button class="btn2 ghost" disabled>Supprimer</button>
                            <button class="btn2 success" disabled>Marquer livrée</button>
                        </div>

                        <div class="assignment-box">
                            <h4>Attribuer un livreur</h4> <!-- Affiche une section pour attribuer un livreur à la commande (désactivée pour l'instant, préparée pour la phase 3) -->
                            <select disabled>
                                <?php foreach($livreurs as $livreur){ ?>
                                    <option><?php echo htmlspecialchars($livreur); ?></option>
                                <?php } ?>
                            </select>
                            <button class="btn2 primary" disabled>Attribuer</button>
                            <p class="note">Affichage préparé pour la phase 3.</p>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <?php if($affichees === 0){ ?> <!-- Affiche un message si aucune commande ne correspond au filtre sélectionné -->
                <p>Aucune commande ne correspond à ce filtre.</p>
            <?php } ?>
        </div>
    </main>

    <footer class="footer">
        <p>📞 Téléphone : 07 67 01 02 03</p>
        <p>✉ Email : imposturecontact@gmail.com</p>
        <p>Horaires : Lundi - Vendredi 10h-21h | Samedi - Dimanche 12h-18h</p>
    </footer>

</body>
</html>