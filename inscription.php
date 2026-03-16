<?php
    if(isset(($_POST["submit"]))){
        if(!empty($_POST["nom"]) and !empty($_POST["prenom"]) and !empty($_POST["email"]) and !empty($_POST["motdepasse"]) and !empty($_POST["adresse"]) and !empty($_POST["telephone"]) and !empty($_POST["infos"])){
            $nom = htmlspecialchars($_POST ["nom"]);
            $prenom = htmlspecialchars($_POST ["prenom"]);
            $email = htmlspecialchars($_POST ["email"]);
            $motdepasse = password_hash($_POST ["motdepasse"]);
            $adresse = htmlspecialchars($_POST ["adresse"]);
            $telephone = sha1($_POST ["telephone"]);
            $infos = htmlspecialchars($_POST ["infos"]);
            if(strlen($_POST["motdepasse"]<7)){
                $message = "Mot de passe trop court.";
            }
            elseif(!password_verify($_POST["motdepasse"])){
                $message = "Mot de passe incorrect.";
            }
        }
    }
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

    <div class="container3">
        <h1>Inscription</h1>
        <form action="profil.php" method="POST">
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
            </div>

            <div class="form-group">
                <label class="label1" for="motdepasse">Mot de passe</label>
                <input type="text" id="motdepasse" name="motdepasse" required placeholder="XXXXXXXXXXXXX">
                <?php
                    if(isset($message)){
                        echo $message;
                    }
                ?>
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