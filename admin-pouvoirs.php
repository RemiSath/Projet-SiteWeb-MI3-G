<?php
    session_start();

    if(!isset($_SESSION["statut"]) || $_SESSION["statut"] !== "Admin"){
        header("Location: connexion.php");
        exit();
    }

    $fichier = __DIR__ . "/data/compte.json";

    if(file_exists($fichier)){
        $json = file_get_contents($fichier);
        $utilisateurs = json_decode($json, true) ?? [];
    } 
        
    else {
        $utilisateurs = array();
    }

    if(!isset($_GET["id"])){
        return null;
    }

    $id = $_GET["id"];
    $index = null;

    foreach($utilisateurs as $key => $utilisateur){
        if($utilisateur["id"] === $id){
            $index = $key;
            break;
        }
    }

    if($index === null){
        return null;
    }

    if(!$utilisateur){
        die("Utilisateur introuvable");
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
    <link href="https://fonts.googleapis.com/css2?family=Playwrite+AT:ital,wght@0,100..400;1,100..400&display=swap"
        rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Annie+Use+Your+Telescope&display=swap" rel="stylesheet">

    <header class="navbar"> <!-- Barre de navigation -->
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

    <div class="profile-container"> <!-- Conteneur principal du profil -->
        <div class="header-profil">
            <h1>Gestion de 
                <?php 
                    echo htmlspecialchars($utilisateur["nom"]); 
                ?> 
                <?php 
                    echo htmlspecialchars($utilisateur["prenom"]);
                ?>
            </h1>
        </div>

        <div class="dashboard"> <!-- Tableau de bord avec les informations et les actions -->
            <div class="carte2">
                <div class="infos-user">
                    <h2>Ses Informations</h2>
                    <div class="info-ligne2">
                        <span class="info-label">Statut Client</span>
                        <div class="info-valeur2" id="statut-client">
                            <?php 
                                echo htmlspecialchars($utilisateur["statut"]);
                            ?>
                        </div>
                    </div>
                    <div class="info-ligne2">
                        <span class="info-label">Compte</span>
                        <div class="info-valeur2" id="statut-compte">
                            <?php 
                                echo ($utilisateur["bloque"]) ? "Bloqué" : "Actif";
                            ?>
                        </div>
                    </div>
                    <div class="info-ligne2">
                        <span class="info-label">Nom</span>
                        <div class="info-valeur2">
                            <?php 
                                echo htmlspecialchars($utilisateur["nom"]);
                            ?>
                        </div>
                    </div>
                    <div class="info-ligne2">
                        <span class="info-label">Prénom</span>
                        <div class="info-valeur2">
                            <?php 
                                echo htmlspecialchars($utilisateur["prenom"]);
                            ?>
                        </div>
                    </div>
                    <div class="info-ligne2">
                        <span class="info-label">E-mail</span>
                        <div class="info-valeur2">
                            <?php 
                                echo htmlspecialchars($utilisateur["email"]);
                            ?>
                        </div>
                    </div>
                    <div class="info-ligne2">
                        <span class="info-label">Téléphone</span>
                        <div class="info-valeur2">
                            <?php 
                                echo htmlspecialchars($utilisateur["telephone"]);
                            ?>
                        </div>
                    </div>
                    <div class="info-ligne2">
                        <span class="info-label">Adresse</span>
                        <div class="info-valeur2">
                            <?php 
                                echo htmlspecialchars($utilisateur["adresse"]);
                            ?>
                        </div>
                    </div>
                    <div class="info-ligne2">
                        <span class="info-label">Informations complémentaires</span>
                        <div class="info-valeur2">
                            <?php 
                                echo htmlspecialchars($utilisateur["infos"]);
                            ?>
                        </div>
                    </div>
                </div>

                <div class="actions-admin"> <!-- Section des actions administrateur -->
                    <h2>Actions administrateur</h2>
                    <form id="formAdmin">
                        <button name="action" value="bloquer" class="btn-modifier2">Bloquer</button>
                        <button name="action" value="debloquer" class="btn-modifier2">Débloquer</button>                    
                        <button name="action" value="premium" class="btn-modifier2">Premium</button>                    
                        <button name="action" value="vip" class="btn-modifier2">VIP</button>                   
                        <button name="action" value="client" class="btn-modifier2">Client</button>                   
                        <button name="action" value="client" class="btn-modifier2">Remise</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer"> <!-- Pied de page avec les informations de contact -->
        <p>📞 Téléphone : 07 67 01 02 03</p>
        <p>✉ Email : imposturecontact@gmail.com</p>
        <p>Horaires : Lundi - Vendredi 10h-21h | Samedi - Dimanche 12h-18h</p>
    </footer>

    <script>
        const form = document.getElementById("formAdmin");
        const statutClient = document.getElementById("statut-client");
        const statutCompte = document.getElementById("statut-compte");

        form.addEventListener("click", actions);
        async function actions(event){

            if(event.target.tagName !== "BUTTON"){
                return;
            }

            event.preventDefault();
            const formData = new FormData();
            formData.append("action", event.target.value);
            formData.append("id", "<?php echo $id; ?>");

            try{
                await fetch("admin-actions.php", {
                    method: "POST",
                    body: formData
                });

                if(event.target.value === "bloquer"){
                    statutCompte.innerText = "Bloqué";
                }
                else if(event.target.value === "debloquer"){
                    statutCompte.innerText = "Actif";
                }
                else if(event.target.value === "premium"){
                    statutClient.innerText = "Premium";
                }
                else if(event.target.value === "vip"){
                    statutClient.innerText = "VIP";
                }
                else if(event.target.value === "client"){
                    statutClient.innerText = "Client";
                }
            }

            catch(error){
                console.log("Erreur serveur");
            }
        }
    </script>

</body>

</html>
