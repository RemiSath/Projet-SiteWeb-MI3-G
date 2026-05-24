<?php
session_start();

$nom = $_SESSION["nom"] ?? "";
$prenom = $_SESSION["prenom"] ?? "";
$email = $_SESSION["email"] ?? "";
$isConnected = isset($_SESSION["email"]);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réserver</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="Images/Among_Us.png">
</head>

<body>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playwrite+AT:ital,wght@0,100..400;1,100..400&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Annie+Use+Your+Telescope&display=swap" rel="stylesheet">

<header class="navbar">
    <a href="page-d'accueil.php" class="accueil">IMPOSTEUR</a>

    <div class="navliens">
        <div class="menu">
            <a>Réservation</a>
            <div class="infos">
                <a href="reserver.php">Réserver une table</a>
                <a href="mes-reservations.php">Mes réservations</a>
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

<div class="container">
    <div class="image-section">
        <img src="Images/restaurant.png" alt="Restaurant">
    </div>

    <div class="reservation">
        <p class="subtitle">FAIRE UNE RÉSERVATION</p>
        <h2>Réserver une table</h2>

        <form action="reservation-infos.php" method="POST">
            <?php
            if (isset($_SESSION["erreur"])) {
                echo "<div class='erreur'>" . htmlspecialchars($_SESSION["erreur"]) . "</div>";
                unset($_SESSION["erreur"]);
            }
            ?>

            <div class="row">
                <input type="text" name="nom" placeholder="Nom" value="<?php echo htmlspecialchars($nom); ?>" required>
                <input type="text" name="prenom" placeholder="Prénom" value="<?php echo htmlspecialchars($prenom); ?>" required>
            </div>

            <?php if (!$isConnected) { ?>
                <div class="row">
                    <input type="email" name="email" placeholder="Email" required>
                </div>
            <?php } else { ?>
                <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
            <?php } ?>

            <div class="row">
                <input type="number" name="adultes" placeholder="Adultes" min="1" required>
                <input type="number" name="enfants" placeholder="Enfants" min="0" required>
            </div>

            <div class="row">
                <input type="date" name="date" required>
                <input type="time" name="time" required>
            </div>

            <select name="restaurant" required>
                <option value="">Choisir un restaurant</option>
                <option value="Paris">Paris</option>
                <option value="Argenteuil">Argenteuil</option>
                <option value="Cergy">Cergy</option>
            </select>

            <textarea name="commentaire" placeholder="Commentaire ou demande spéciale"></textarea>

            <button type="submit">Réserver</button>
        </form>
    </div>
</div>

<footer class="footer">
    <p>📞 Téléphone : 07 67 01 02 03</p>
    <p>✉ Email : imposturecontact@gmail.com</p>
    <p>Horaires : Lundi - Vendredi 10h-21h | Samedi - Dimanche 12h-18h</p>
</footer>
</body>
</html>
