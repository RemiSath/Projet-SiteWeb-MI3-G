<?php
session_start();
include "bibliothèques/bloquer.php";

if (!isset($_SESSION["email"])) {
    $_SESSION["erreur2"] = "Connectez-vous pour voir vos réservations.";
    header("Location: connexion.php");
    exit();
}

$emailClient = strtolower(trim($_SESSION["email"]));
$fichier = __DIR__ . "/data/reservation.json";

$reservations = [];
$mesReservations = [];

if (file_exists($fichier)) {
    $json = file_get_contents($fichier);
    $reservations = json_decode($json, true);

    if (!is_array($reservations)) {
        $reservations = [];
    }
}

foreach ($reservations as $reservation) {
    if (strtolower(trim($reservation["email"] ?? "")) === $emailClient) {
        $mesReservations[] = $reservation;
    }
}

usort($mesReservations, function ($a, $b) {
    $dateA = ($a["date"] ?? "") . " " . ($a["time"] ?? "");
    $dateB = ($b["date"] ?? "") . " " . ($b["time"] ?? "");

    return strtotime($dateA) <=> strtotime($dateB);
});

function h($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}

function reservationFuture($reservation)
{
    $dateReservation = ($reservation["date"] ?? "") . " " . ($reservation["time"] ?? "00:00");
    return strtotime($dateReservation) > time();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes réservations</title>
    <link rel="stylesheet" href="styles.css">
    <link id="theme-link" rel="stylesheet" href="css/default.css">
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
        <button id="theme-button">Changer le thème</button>
    </div>
</header>

<div class="sitedescription3">
    <h1>Mes réservations</h1>
</div>

<main class="container">
    <?php if (isset($_SESSION["message2"])) { ?>
        <div class="message">
            <?php echo h($_SESSION["message2"]); ?>
        </div>
        <?php unset($_SESSION["message2"]); ?>
    <?php } ?>

    <?php if (isset($_SESSION["erreur"])) { ?>
        <div class="erreur">
            <?php echo h($_SESSION["erreur"]); ?>
        </div>
        <?php unset($_SESSION["erreur"]); ?>
    <?php } ?>

    <div class="grid2">
        <?php if (empty($mesReservations)) { ?>
            <p>Aucune réservation trouvée.</p>
        <?php } else { ?>
            <?php foreach ($mesReservations as $reservation) { ?>
                <?php $modifiable = reservationFuture($reservation); ?>

                <div class="order-card">
                    <div class="meta">
                        <div class="id">
                            #<?php echo h($reservation["id"] ?? ""); ?>
                        </div>

                        <div class="customer">
                            <?php echo h(($reservation["prenom"] ?? "") . " " . ($reservation["nom"] ?? "")); ?>
                        </div>

                        <div class="date">
                            <?php echo h($reservation["date"] ?? ""); ?>
                            à
                            <?php echo h($reservation["time"] ?? ""); ?>
                        </div>
                    </div>

                    <div class="details">
                        <div class="items">
                            <div class="item-row">
                                <span>Restaurant</span>
                                <span><?php echo h($reservation["restaurant"] ?? ""); ?></span>
                            </div>

                            <div class="item-row">
                                <span>Adultes</span>
                                <span><?php echo h($reservation["adultes"] ?? 0); ?></span>
                            </div>

                            <div class="item-row">
                                <span>Enfants</span>
                                <span><?php echo h($reservation["enfants"] ?? 0); ?></span>
                            </div>
                        </div>

                        <details class="order-details">
                            <summary>Voir le commentaire</summary>
                            <div class="order-extra">
                                <p>
                                    <?php
                                    $commentaire = trim($reservation["commentaire"] ?? "");
                                    echo $commentaire !== ""
                                        ? nl2br(h($commentaire))
                                        : "Aucun commentaire.";
                                    ?>
                                </p>
                            </div>
                        </details>

                        <?php if ($modifiable) { ?>
                            <a class="btn-link" href="modifier-reservation.php?id=<?php echo h($reservation["id"] ?? ""); ?>">
                                Modifier cette réservation
                            </a>
                        <?php } else { ?>
                            <p>Cette réservation est passée et ne peut plus être modifiée.</p>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>
    </div>
</main>

<footer class="footer">
    <p>📞 Téléphone : 07 67 01 02 03</p>
    <p>✉ Email : imposteurcontact@gmail.com</p>
    <p>Horaires : Lundi - Vendredi 10h-21h | Samedi - Dimanche 12h-18h</p>
</footer>

<script src="cookie.js"></script>
</body>
</html>
