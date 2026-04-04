<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="icon" href="Images/Among_Us.png">
    <link rel="stylesheet" href="styles.css">
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

    <div class="container3">
        <h1>Inscription</h1>
        <form action="inscription-infos.php" method="POST">
            <div class="form-group">
                <label class="label1" for="nom">Nom</label>
                <input type="text" id="nom" name="nom" required placeholder="Votre nom">
            </div>
            <div class="form-group">
                <label class="label1" for="prenom">Prénom</label>
                <input type="text" id="prenom" name="prenom" required placeholder="Votre prénom">
            </div>
            <div class="form-group">
                <label class="label1" for="email">E-mail</label>
                <input type="email" id="email" name="email" required placeholder="exemple@domaine.com">
                <?php
                    if(isset($_SESSION["erreur"])){
                        echo "<div class='erreur'>" . $_SESSION["erreur"] . "</div>";
                        unset($_SESSION["erreur"]);
                    }
                ?>
            </div>
            <div class="form-group">
                <label class="label1" for="motdepasse">Mot de passe</label>
                <input type="password" id="motdepasse" name="motdepasse" required placeholder="XXXXXXXXXXX">
            </div>
            <div class="form-group">
                <label class="label1" for="adresse">Adresse de livraison</label>
                <input type="text" id="adresse" name="adresse" required placeholder="N° et Rue, Ville, Code Postal">
            </div>
            <div class="form-group">
                <label class="label1" for="telephone">Numéro de téléphone</label>
                <input type="tel" id="telephone" name="telephone" required placeholder="06 00 00 00 00">
            </div>
            <div class="form-group">
                <label class="label1" for="infos">Informations complémentaires</label>
                <textarea id="infos" name="infos" placeholder="Allergies, Etc..."></textarea>
            </div>
            <button type="submit" name="submit">S'inscrire</button>
        </form>

    </div>

    <footer class="footer">
        <p>📞 Téléphone : 07 67 01 02 03</p>
        <p>✉ Email : imposturecontact@gmail.com</p>
        <p>Horaires : Lundi - Vendredi 10h-21h | Samedi - Dimanche 12h-18h</p>
    </footer>
    
</body>

</html>