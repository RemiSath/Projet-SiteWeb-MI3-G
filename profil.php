<?php
    $nom = $_POST ["nom"];
    $prenom = $_POST ["prenom"];
    $email = $_POST ["email"];
    $motdepasse = $_POST ["motdepasse"];
    $adresse = $_POST ["adresse"];
    $telephone = $_POST ["telephone"];
    $infos = $_POST ["infos"];
    if(!empty($nom)){
        $message = $nom;
    }
    if(!empty($prenom)){
        $message2 = $prenom;
    }
    if(!empty($email)){
        $message3 = $email;
    }
    if(!empty($telephone)){
        $message4 = $telephone;
    }
    if(!empty($adresse)){
        $message5 = $adresse;
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
        <a href="page-d'accueil.html" class="accueil">IMPOSTEUR</a>
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
                    <a href="profil.html">Voir Profil</a>
                    <a href="connexion.html">Connexion</a>
                    <a href="inscription.html">Inscription</a>
                </div>
            </div>
            <div class="menu">
                <a>Services</a>
                <div class="infos">
                    <a href="commandes.html">Commandes</a>
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
            <!-- Informations personnelles -->
            <div class="carte">
                <h2>Mes Informations</h2>
                <div class="info-ligne">
                    <span class="info-label">Nom</span>
                    <div class="info-valeur">
                        <?php
                            if(isset($message)){
                                echo $message;
                            }
                        ?>
                    </div>
                </div>
                <div class="info-ligne">
                    <span class="info-label">Prénom</span>
                    <div class="info-valeur">
                        <?php
                            if(isset($message2)){
                                echo $message2;
                            }
                        ?>
                    </div>
                </div>
                <div class="info-ligne">
                    <span class="info-label">E-mail</span>
                    <div class="info-valeur">
                        <?php
                            if(isset($message3)){
                                echo $message3;
                            }
                        ?>
                    </div>
                </div>
                <div class="info-ligne">
                    <span class="info-label">Téléphone</span>
                    <div class="info-valeur">
                        <?php
                            if(isset($message4)){
                                echo $message4;
                            }
                        ?>
                    </div>
                </div>
                <div class="info-ligne">
                    <span class="info-label">Adresse</span>
                    <div class="info-valeur">
                        <?php
                            if(isset($message5)){
                                echo($messge5);
                            }
                        ?>
                    </div>
                </div>
                <button class="btn-modifier">
                    <img src="Images/stylo.png" alt="Modifier" class="crayon-icon">
                    Modifier
                </button>
            </div>

            <!-- Anciennes commandes -->
            <div class="carte">
                <h2>Anciennes Commandes</h2>
                <ul class="liste-commandes">
                    <p style="text-align: center; color: #555; font-style: italic;">Vos commandes apparaîtront ici</p>
                </ul>
            </div>

            <!-- Fidélité -->
            <div class="carte fidelite">
                <h2>Compte Fidélité</h2>
                <p class="fidelite-texte">Points accumulés lors de vos commandes :</p>
                <div class="fidelite-score">320</div>
                <p class="fidelite-texte">pts</p>
                <p style="font-size: 12px; color: #888;">Encore 80 points pour obtenir un plat gratuit !</p>
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