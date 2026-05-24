<?php
session_start();

if (!isset($_SESSION["email"])) {
    $_SESSION["erreur2"] = "Connectez-vous pour noter une commande.";
    header("Location: connexion.php");
    exit();
}

$emailClient = strtolower(trim($_SESSION["email"]));
$fichierCommandes = __DIR__ . "/data/commandes.json";

$commandes = [];
$commandesLivrees = [];

if (file_exists($fichierCommandes)) {
    $json = file_get_contents($fichierCommandes);
    $commandes = json_decode($json, true);

    if (!is_array($commandes)) {
        $commandes = [];
    }
}

foreach ($commandes as $commande) {
    $emailCommande = strtolower(trim($commande["email"] ?? ""));
    $statutCommande = $commande["statut"] ?? "";

    if (
        $emailCommande === $emailClient &&
        $statutCommande === "livree" &&
        empty($commande["note_donnee"])
    ) {
        $commandesLivrees[] = $commande;
    }
}

$peutNoter = !empty($commandesLivrees);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="Images/Among_Us.png">
    <title>Notation</title>
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
    </div>
</nav>

<div class="sitedescription4">
    <h1>Notez-nous</h1>
    <p class="textedescription4">Votre avis compte pour nous !</p>

    <div class="container-notation">
        <div class="reservation2">
            <?php if (isset($_SESSION["message"])) { ?>
                <div class="message">
                    <?php echo htmlspecialchars($_SESSION["message"]); ?>
                </div>
                <?php unset($_SESSION["message"]); ?>
            <?php } ?>

            <?php if ($peutNoter) { ?>
                <form action="envoyer-notation.php" method="POST">
                    <label>Commande livrée à noter</label>

                    <select name="commande_id" required>
                        <?php foreach ($commandesLivrees as $commande) { ?>
                            <option value="<?php echo htmlspecialchars($commande["id"] ?? ""); ?>">
                                Commande #<?php echo htmlspecialchars($commande["id"] ?? ""); ?>
                                -
                                <?php echo htmlspecialchars($commande["date"] ?? ""); ?>
                            </option>
                        <?php } ?>
                    </select>

                    <div class="row">
                        <input type="number" name="livraison" placeholder="Livraison" min="0" max="5" required>
                        <p>0 à 5</p>
                    </div>

                    <div class="row">
                        <input type="number" name="qualite" placeholder="Qualité des produits" min="0" max="5" required>
                        <p>0 à 5</p>
                    </div>

                    <div class="row">
                        <textarea name="commentaires" placeholder="Commentaires"></textarea>
                    </div>

                    <button type="submit">Envoyer</button>
                </form>
            <?php } else { ?>
                <div class="message2">
                    Vous pourrez noter votre expérience lorsqu’une commande sera livrée.
                    Si vous avez déjà noté vos commandes livrées, elles ne s’affichent plus ici.
                </div>
            <?php } ?>
        </div>

        <div class="vide"></div>
    </div>
</div>

<footer class="footer">        
    <p>📞 Téléphone : 07 67 01 02 03</p>
    <p>✉ Email : imposteurcontact@gmail.com</p>
    <p>Horaires : Lundi - Vendredi 10h-21h | Samedi - Dimanche 12h-18h</p>
</footer>

</body>
</html>
