<?php
    session_start();
    
    function lectureFichier(){
        $fichier = "compte.json";
        $nom = "";
        $prenom = "";
        $email = "";
        $motdepasse = "";
        $telephone = "";
        $adresse = "";
        $infos = "";

        if(file_exists($fichier)){
            $json = file_get_contents($fichier);
            $array = json_decode($json, true) ?? [];
            if(!empty($array)){
                $utilisateur = end($array);
                $nom = $utilisateur["nom"] ?? "";
                $prenom = $utilisateur["prenom"] ?? "";
                $email = $utilisateur["email"] ?? "";
                $motdepasse = $utilisateur["motdepasse"] ?? "";
                $telephone = $utilisateur["telephone"] ?? "";
                $adresse = $utilisateur["adresse"] ?? "";
                $infos = $utilisateur["infos"] ?? "";
            }
        }

        return[
            "nom" => $nom,
            "prenom" => $prenom,
            "email" => $email,
            "motdepasse" => $motdepasse,
            "telephone" => $telephone,
            "adresse" => $adresse,
            "infos" => $infos
        ];
    }
    $data = lectureFichier();
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
                <a href="Notation.html">Notation</a>
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
                <a href="Admin.html">Admin</a>
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
                <div class="info-ligne">
                    <span class="info-label">Nom</span>
                    <?php 
                        echo htmlspecialchars($data["nom"]); 
                    ?>
                    <div class="info-valeur"></div>
                </div>
                <div class="info-ligne">
                    <span class="info-label">Prénom</span>
                    <?php 
                        echo htmlspecialchars($data["prenom"]); 
                    ?>
                    <div class="info-valeur"></div>
                </div>
                <div class="info-ligne">
                    <span class="info-label">E-mail</span>
                    <?php 
                    echo htmlspecialchars($data["email"]); ?>
                    <div class="info-valeur"></div>
                </div>
                <div class="info-ligne">
                    <span class="info-label">Mot de passe</span>
                    <?php 
                        echo "********"; 
                    ?>
                    <div class="info-valeur"></div>
                </div>
                <div class="info-ligne">
                    <span class="info-label">Téléphone</span>
                    <?php 
                        echo htmlspecialchars($data["telephone"]); 
                    ?>
                    <div class="info-valeur"></div>
                </div>
                <div class="info-ligne">
                    <span class="info-label">Adresse</span>
                    <?php 
                        echo htmlspecialchars($data["adresse"]); 
                    ?>
                    <div class="info-valeur"></div>
                </div>
                <div class="info-ligne">
                    <span class="info-label">Informations complémentaires</span>
                    <?php 
                        echo htmlspecialchars($data["infos"]); 
                    ?>
                    <div class="info-valeur"></div>
                </div>
                <button class="btn-modifier">
                    <img src="Images/stylo.png" alt="Modifier" class="crayon-icon">
                    Modifier
                </button>
            </div>

            <div class="carte">
                <h2>Anciennes Commandes</h2>
            </div>

            <div class="carte fidelite">
                <h2>Compte Fidélité</h2>
                <p class="fidelite-texte">Points accumulés lors de vos commandes :</p>
                <div class="fidelite-score">320</div>
                <p class="fidelite-texte">pts</p>
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
```
