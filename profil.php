<?php
    session_start();
    include "bibliothèques/bloquer.php";

    if(!isset($_SESSION["email"])){
        header("Location: connexion.php");
        exit;
    }

    $data = [ // Récupération des données de session avec des valeurs par défaut
        "nom" => $_SESSION["nom"] ?? "",
        "prenom" => $_SESSION["prenom"] ?? "",
        "email" => strtolower(trim($_SESSION["email"] ?? "")),
        "telephone" => $_SESSION["telephone"] ?? "",
        "adresse" => $_SESSION["adresse"] ?? "",
        "infos" => $_SESSION["infos"] ?? "",
        "statut" => $_SESSION["statut"] ?? ""
    ];

    $fichierCommandes = __DIR__ . "/data/commandes.json";
    $commandesUtilisateur = [];

    if(file_exists($fichierCommandes)){
        $jsonCommandes = file_get_contents($fichierCommandes);
        $commandes = json_decode($jsonCommandes, true) ?? [];

        $sessionEmail = $data["email"];

        foreach($commandes as $commande){
            if(!empty($commande["email"]) && strtolower(trim($commande["email"])) === $sessionEmail){ // Vérification de l'email de la commande
                $commandesUtilisateur[] = $commande;
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <link rel="icon" href="Images/Among_Us.png">
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
</head>

<body>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playwrite+AT:ital,wght@0,100..400;1,100..400&display=swap" rel="stylesheet">
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
    </header>

    <div class="profile-container">
        <div class="header-profil">
            <h1>Mon Profil</h1>
        </div>
        <div class="dashboard">
            <div class="carte">
                <h2>Mes Informations</h2>
                <form id="modifier">
                    <div class="info-ligne">
                        <span class="info-label">Nom</span>
                        <input type="text" name="nom" class="info-input" value="<?php echo htmlspecialchars($data["nom"]); ?>" disabled>
                    </div>
                    <div class="info-ligne">
                        <span class="info-label">Prénom</span>
                        <input type="text" name="prenom" class="info-input" value="<?php echo htmlspecialchars($data["prenom"]); ?>" disabled>
                    </div>
                    <div class="info-ligne">
                        <span class="info-label">Téléphone</span>
                        <input type="text" name="telephone" class="info-input" value="<?php echo htmlspecialchars($data["telephone"]); ?>" disabled>
                    </div>
                    <div class="info-ligne">
                        <span class="info-label">Nouveau mot de passe</span>
                        <input type="password" name="motdepasse" class="info-input" disabled>
                    </div>
                    <div class="info-ligne">
                        <span class="info-label">Confirmer mot de passe</span>
                        <input type="password" name="confirmation" class="info-input" disabled>
                    </div>
                    <div class="info-ligne">
                        <span class="info-label">Adresse</span>
                        <input type="text" name="adresse" class="info-input" value="<?php echo htmlspecialchars($data["adresse"]); ?>" disabled>
                    </div>
                    <div class="info-ligne">
                        <span class="info-label">Informations complémentaires</span>
                        <textarea name="infos" class="info-input" disabled><?php echo htmlspecialchars($data["infos"]); ?></textarea>
                    </div>
                    <button type="button" class="btn-modifier" id="btnModifier">
                        <img src="Images/stylo.png" alt="Modifier" class="crayon-icon">
                        Modifier
                    </button>
                    <button type="submit" class="btn-modifier" id="btnEnregistrer" style="display:none;">
                        Enregistrer
                    </button>
                    <p id="message"></p>
                </form>
            </div>

            <div class="carte">
                <?php $i = 1; ?>
                <h2>Anciennes Commandes</h2>
                <?php if(empty($commandesUtilisateur)): ?>
                    <p>Aucune commande trouvée.</p>
                <?php else: ?>
                    <?php foreach($commandesUtilisateur as $commande): ?>
                        <div class="commande">
                            <p><strong>Commande #<?php echo $i; $i++; ?></strong></p>
                            <p>Statut : <?php echo htmlspecialchars($commande["statut"]); ?></p>
                            <p>Date : <?php echo htmlspecialchars($commande["date_souhaitee"] ?? $commande["date"]); ?></p>
                            <p><strong>Plats :</strong></p>
                            <ul>
                                <?php foreach($commande["plats"] as $plat): ?>
                                    <li><?php echo htmlspecialchars($plat["nom"]); ?> x<?php echo $plat["quantite"]; ?> (<?php echo $plat["prix"]; ?> €)</li>
                                <?php endforeach; ?>
                            </ul>
                            <?php
                            $statutCommande = $commande["statut"] ?? "";
                            $modifiable = in_array($statutCommande, ["a_preparer", "payee", "en_attente"], true);
                            ?>
                            <p>Total : <?php echo number_format($commande["total_actuel"] ?? 0, 2, ',', ' '); ?> €</p>
                            <?php if (!empty($commande["ticket_reduction"])): ?>
                                <p>Ticket de réduction : <?php echo number_format($commande["ticket_reduction"], 2, ',', ' '); ?> €</p>
                            <?php endif; ?>
                            <?php if ($modifiable): ?>
                                <a class="btn-link" href="modifier-commande.php?id=<?php echo htmlspecialchars($commande["id"]); ?>">
                                    Modifier cette commande
                                </a>
                            <?php endif; ?>
                            <hr>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="carte fidelite">
                <h2>Compte Fidélité</h2>
                <p class="fidelite-texte">Points accumulés lors de vos commandes :</p>
                <div class="fidelite-score">0</div>
                <p class="fidelite-texte">pts</p>
            </div>
        </div>
    </div>

    <footer class="footer">
        <p>📞 Téléphone : 07 67 01 02 03</p>
        <p>✉ Email : imposturecontact@gmail.com</p>
        <p>Horaires : Lundi - Vendredi 10h-21h | Samedi - Dimanche 12h-18h</p>
    </footer>

    <script>
        const form = document.getElementById("modifier");
        const btnModifier = document.getElementById("btnModifier");
        const btnEnregistrer = document.getElementById("btnEnregistrer");
        const champs = form.querySelectorAll("input, textarea");
        const message = document.getElementById("message");
        btnModifier.addEventListener("click", () => {
            champs.forEach(champ => { 
                champ.disabled = false; 
            });

            btnModifier.style.display = "none";
            btnEnregistrer.style.display = "inline-block";
        });
        form.addEventListener("submit", profil);
        async function profil(event){
            event.preventDefault();
            const formData = new FormData(form);
            try{
                const response = await fetch("profil-à-jour.php", { 
                    method: "POST", 
                    body: formData 
                });
                const data = await response.text();
                message.textContent = data;
                if(data.toLowerCase().includes("succès")){
                    message.style.color = "green";
                    champs.forEach(champ => { 
                        champ.disabled = true; 
                    });
                    btnModifier.style.display = "inline-block";
                    btnEnregistrer.style.display = "none";
                }
                else{
                    message.style.color = "red";
                    btnEnregistrer.disabled = true;
                    champs.forEach(champ => { 
                        champ.addEventListener("input", () => {
                            btnEnregistrer.disabled = false;
                        }); 
                    });
                }
            }
            catch(error){
                message.textContent = "Erreur lors de la mise à jour.";
                message.style.color = "red";
                btnEnregistrer.disabled = true;
            }
        }
    </script>
</body>
</html>
