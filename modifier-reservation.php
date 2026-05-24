<?php
session_start();
include "bibliothèques/bloquer.php";

if (!isset($_SESSION["email"])) {
    $_SESSION["erreur"] = "Connectez-vous pour modifier vos réservations.";
    header("Location: connexion.php");
    exit();
}

$emailClient = strtolower(trim($_SESSION["email"]));
$fichier = __DIR__ . "/data/reservation.json";

$reservations = [];

if (file_exists($fichier)) {
    $json = file_get_contents($fichier);
    $reservations = json_decode($json, true);

    if (!is_array($reservations)) {
        $reservations = [];
    }
}

$id = $_GET["id"] ?? $_POST["id"] ?? "";
$reservation = null;
$indexReservation = null;

foreach ($reservations as $index => $r) {
    if (
        ($r["id"] ?? "") === $id &&
        strtolower(trim($r["email"] ?? "")) === $emailClient
    ) {
        $reservation = $r;
        $indexReservation = $index;
        break;
    }
}

if (!$reservation) {
    $_SESSION["erreur"] = "Réservation introuvable.";
    header("Location: mes-reservations.php");
    exit();
}

function h($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}

function reservationFuture($reservation)
{
    $dateReservation = ($reservation["date"] ?? "") . " " . ($reservation["time"] ?? "00:00");
    return strtotime($dateReservation) > time();
}

if (!reservationFuture($reservation)) {
    $_SESSION["erreur"] = "Cette réservation est passée et ne peut plus être modifiée.";
    header("Location: mes-reservations.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = trim($_POST["nom"] ?? "");
    $prenom = trim($_POST["prenom"] ?? "");
    $adultes = intval($_POST["adultes"] ?? 1);
    $enfants = intval($_POST["enfants"] ?? 0);
    $date = $_POST["date"] ?? "";
    $time = $_POST["time"] ?? "";
    $restaurant = trim($_POST["restaurant"] ?? "");
    $commentaire = trim($_POST["commentaire"] ?? "");

    if ($nom === "" || $prenom === "" || $date === "" || $time === "" || $restaurant === "") {
        $_SESSION["erreur"] = "Veuillez remplir tous les champs obligatoires.";
        header("Location: modifier-reservation.php?id=" . urlencode($id));
        exit();
    }

    if (strtotime($date . " " . $time) <= time()) {
        $_SESSION["erreur"] = "La date de réservation doit être dans le futur.";
        header("Location: modifier-reservation.php?id=" . urlencode($id));
        exit();
    }

    if ($adultes < 1) {
        $_SESSION["erreur"] = "Il faut au moins un adulte pour réserver.";
        header("Location: modifier-reservation.php?id=" . urlencode($id));
        exit();
    }

    if ($enfants < 0) {
        $_SESSION["erreur"] = "Le nombre d'enfants ne peut pas être négatif.";
        header("Location: modifier-reservation.php?id=" . urlencode($id));
        exit();
    }

    $reservations[$indexReservation]["nom"] = $nom;
    $reservations[$indexReservation]["prenom"] = $prenom;
    $reservations[$indexReservation]["adultes"] = $adultes;
    $reservations[$indexReservation]["enfants"] = $enfants;
    $reservations[$indexReservation]["date"] = $date;
    $reservations[$indexReservation]["time"] = $time;
    $reservations[$indexReservation]["restaurant"] = $restaurant;
    $reservations[$indexReservation]["commentaire"] = $commentaire;
    $reservations[$indexReservation]["date_modification"] = date("Y-m-d H:i:s");

    file_put_contents(
        $fichier,
        json_encode($reservations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
        LOCK_EX
    );

    $_SESSION["message2"] = "Votre réservation a été modifiée avec succès.";
    header("Location: mes-reservations.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier réservation</title>
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
    </div>
</header>

<div class="container">
    <div class="image-section">
        <img src="Images/restaurant.png" alt="Restaurant">
    </div>

    <div class="reservation">
        <p class="subtitle">MODIFIER UNE RÉSERVATION</p>
        <h2>Modifier ma réservation</h2>

        <?php if (isset($_SESSION["erreur"])) { ?>
            <div class="erreur">
                <?php echo h($_SESSION["erreur"]); ?>
            </div>
            <?php unset($_SESSION["erreur"]); ?>
        <?php } ?>

        <form method="POST" action="modifier-reservation.php">
            <input type="hidden" name="id" value="<?php echo h($reservation["id"] ?? ""); ?>">

            <div class="row">
                <input type="text" name="nom" placeholder="Nom" value="<?php echo h($reservation["nom"] ?? ""); ?>" required>
                <input type="text" name="prenom" placeholder="Prénom" value="<?php echo h($reservation["prenom"] ?? ""); ?>" required>
            </div>

            <div class="row">
                <input type="number" name="adultes" placeholder="Adultes" min="1" value="<?php echo h($reservation["adultes"] ?? 1); ?>" required>
                <input type="number" name="enfants" placeholder="Enfants" min="0" value="<?php echo h($reservation["enfants"] ?? 0); ?>" required>
            </div>

            <div class="row">
                <input type="date" name="date" value="<?php echo h($reservation["date"] ?? ""); ?>" required>
                <input type="time" name="time" value="<?php echo h($reservation["time"] ?? ""); ?>" required>
            </div>

            <select name="restaurant" required>
                <option value="">Choisir un restaurant</option>
                <option value="Paris" <?php echo ($reservation["restaurant"] ?? "") === "Paris" ? "selected" : ""; ?>>Paris</option>
                <option value="Argenteuil" <?php echo ($reservation["restaurant"] ?? "") === "Argenteuil" ? "selected" : ""; ?>>Argenteuil</option>
                <option value="Cergy" <?php echo ($reservation["restaurant"] ?? "") === "Cergy" ? "selected" : ""; ?>>Cergy</option>
            </select>

            <textarea name="commentaire" placeholder="Commentaire ou demande spéciale"><?php echo h($reservation["commentaire"] ?? ""); ?></textarea>

            <button type="submit">Enregistrer les modifications</button>
        </form>

        <a class="btn-link" href="mes-reservations.php">Retour à mes réservations</a>
    </div>
</div>

<footer class="footer">
    <p>📞 Téléphone : 07 67 01 02 03</p>
    <p>✉ Email : imposturecontact@gmail.com</p>
    <p>Horaires : Lundi - Vendredi 10h-21h | Samedi - Dimanche 12h-18h</p>
</footer>

</body>
</html>
