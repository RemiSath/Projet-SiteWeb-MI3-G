<?php
session_start();
include "bibliothèques/bloquer.php";

if (!isset($_SESSION["email"])) {
    $_SESSION["erreur"] = "Acces reserve aux administrateurs.";
    header("Location: page-d'accueil.php");
    exit;
}

if (!isset($_SESSION["statut"]) || $_SESSION["statut"] !== "Admin") {
    $_SESSION["erreur"] = "Acces reserve aux administrateurs.";
    header("Location: page-d'accueil.php");
    exit;
}

$fichier = __DIR__ . "/data/compte.json";

if (file_exists($fichier)) {
    $json = file_get_contents($fichier);
    $utilisateurs = json_decode($json, true) ?? [];
} else {
    $utilisateurs = [];
}

$fichierLogs = __DIR__ . "/data/logs.json";
$logsIncidents = file_exists($fichierLogs)
    ? json_decode(file_get_contents($fichierLogs), true)
    : [];

if (!is_array($logsIncidents)) {
    $logsIncidents = [];
}

$logsIncidents = array_reverse($logsIncidents);
$logsIncidents = array_slice($logsIncidents, 0, 10);

$recherche = $_GET["recherche"] ?? "";
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrateur</title>
    <link rel="stylesheet" href="styles.css">
    <link id="theme-link" rel="stylesheet" href="css/default.css">
    <link rel="icon" href="Images/Among_Us.png">
</head>

<body>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Bungee&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Playwrite+AT:ital,wght@0,100..400;1,100..400&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Annie+Use+Your+Telescope&display=swap" rel="stylesheet">

<nav class="navbar">
    <a href="page-d'accueil.php" class="accueil">IMPOSTEUR</a>

    <div class="navliens">
        <div class="menu">
            <a>Reservation</a>
            <div class="infos">
                <a href="reserver.php">Reserver une table</a>
                <a href="mes-reservations.php">Mes reservations</a>
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

        <button id="theme-button">Changer le theme</button>
    </div>
</nav>

<div class="sitedescription2">
    <h1>Page Administrateur</h1>
</div>

<header id="topBar">
    <form method="GET">
        <input
            type="text"
            name="recherche"
            id="searchInput"
            placeholder="Rechercher un client..."
            value="<?php echo htmlspecialchars($recherche); ?>"
        >
    </form>

    <div id="results">
        <div class="utilisateur">
            <?php foreach ($utilisateurs as $utilisateur) { ?>
                <?php
                if (($utilisateur["email"] ?? "") === ($_SESSION["email"] ?? "")) {
                    continue;
                }

                if (($utilisateur["statut"] ?? "") === "Admin") {
                    continue;
                }

                if (
                    $recherche !== "" &&
                    stripos($utilisateur["prenom"] ?? "", $recherche) === false &&
                    stripos($utilisateur["nom"] ?? "", $recherche) === false &&
                    stripos($utilisateur["email"] ?? "", $recherche) === false
                ) {
                    continue;
                }
                ?>

                <div class="card">
                    <a
                        class="utilisateurs-links"
                        href="admin-pouvoirs.php?id=<?php echo htmlspecialchars($utilisateur["id"] ?? ""); ?>"
                    >
                        <?php echo htmlspecialchars($utilisateur["prenom"] ?? ""); ?>
                    </a>
                </div>
            <?php } ?>
        </div>
    </div>
</header>

<section class="profile-container">
    <div class="carte">
        <h2>Logs d'incidents</h2>
        <?php if (empty($logsIncidents)) { ?>
            <p>Aucun incident enregistre pour le moment.</p>
        <?php } else { ?>
            <ul class="liste-commandes">
                <?php foreach ($logsIncidents as $log) { ?>
                    <li>
                        <span>
                            <?php echo htmlspecialchars($log["date"] ?? ""); ?>
                            -
                            <?php echo htmlspecialchars($log["type"] ?? ""); ?>
                        </span>
                        <span>
                            <?php echo htmlspecialchars($log["email"] ?? ""); ?>
                        </span>
                    </li>
                <?php } ?>
            </ul>
        <?php } ?>
    </div>
</section>

<footer class="footer">
    <p>Telephone : 07 67 01 02 03</p>
    <p>Email : imposteurcontact@gmail.com</p>
    <p>Horaires : Lundi - Vendredi 10h-21h | Samedi - Dimanche 12h-18h</p>
</footer>

<script src="cookie.js"></script>
</body>

</html>