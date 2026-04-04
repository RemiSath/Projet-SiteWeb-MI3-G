<?php
    session_start();

    function Admin(){

        $fichier = "compte.json";

        if(file_exists($fichier)){
            $json = file_get_contents($fichier);
            $array = json_decode($json, true) ?? [];
        } 
        
        else {
            $array = array();
        }

        if(!isset($_GET["id"])){
            return null;
        }

        $id = $_GET["id"];
        $index = null;

        foreach($array as $key => $utilisateur){
            if($utilisateur["id"] === $id){
                $index = $key;
                break;
            }
        }

        if($index === null){
            return null;
        }

        if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"])){
            if($_POST["action"] === "bloquer"){
                $array[$index]["bloque"] = true;
            }
            if($_POST["action"] === "debloquer"){
                $array[$index]["bloque"] = false;
            }
            if($_POST["action"] === "premium"){
                $array[$index]["statut"] = "Premium";
            }
            if($_POST["action"] === "vip"){
                $array[$index]["statut"] = "VIP";
            }
            if($_POST["action"] === "client"){
                $array[$index]["statut"] = "Client";
            }

            file_put_contents($fichier, json_encode($array, JSON_PRETTY_PRINT));

            header("Location: admin-pouvoirs.php?id=".$id);
            exit;
        }

        return $array[$index];
    }

    $utilisateur = Admin();

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

    <header class="navbar">
        <a href="page-d'accueil.php" class="accueil">IMPOSTEUR</a>
        <div class="navliens">
            <div class="menu">
                <a>Réservation</a>
                <div class="infos">
                    <a href="reserver.html">Réserver une table</a>
                    <a href="commander.html">Commander</a>
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

    <div class="profile-container">
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

        <div class="dashboard">
            <div class="carte2">
                <div class="infos-user">
                    <h2>Ses Informations</h2>
                    <div class="info-ligne2">
                        <span class="info-label">Statut Client</span>
                        <div class="info-valeur2">
                            <?php 
                                echo htmlspecialchars($utilisateur["statut"]);
                            ?>
                        </div>
                    </div>
                    <div class="info-ligne2">
                        <span class="info-label">Compte</span>
                        <div class="info-valeur2">
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

                <div class="actions-admin">
                    <h2>Actions administrateur</h2>
                    <form method="POST">
                        <button name="action" value="bloquer" class="btn-modifier2">Bloquer</button>
                    </form>
                    <form method="POST">
                        <button name="action" value="debloquer" class="btn-modifier2">Débloquer</button>
                    </form>
                    <form method="POST">
                        <button name="action" value="premium" class="btn-modifier2">Premium</button>
                    </form>
                    <form method="POST">
                        <button name="action" value="vip" class="btn-modifier2">VIP</button>
                    </form>
                    <form method="POST">
                        <button name="action" value="client" class="btn-modifier2">Client</button>
                    </form>
                    <form method="POST">
                        <button name="action" value="client" class="btn-modifier2">Remise</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer">
        <p>📞 Téléphone : 07 67 01 02 03</p>
        <p>✉ Email : imposturecontact@gmail.com</p>
        <p>Horaires : Lundi - Vendredi 10h-21h | Samedi - Dimanche 12h-18h</p>
    </footer>
</body>

</html>