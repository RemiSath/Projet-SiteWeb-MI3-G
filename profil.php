<?php
session_start();

if (!isset($_SESSION["email"])) {
    header("Location: connexion.php");
    exit;
}

$data = [
    "nom" => $_SESSION["nom"] ?? "",
    "prenom" => $_SESSION["prenom"] ?? "",
    "email" => strtolower(trim($_SESSION["email"] ?? "")),
    "telephone" => $_SESSION["telephone"] ?? "",
    "adresse" => $_SESSION["adresse"] ?? "",
    "infos" => $_SESSION["infos"] ?? "",
    "statut" => $_SESSION["statut"] ?? ""
];

$fichierCommandes = __DIR__ . "/data/commandes.json";
$commandesUtilisateur = [];

if (file_exists($fichierCommandes)) {
    $jsonCommandes = file_get_contents($fichierCommandes);
    $commandes = json_decode($jsonCommandes, true) ?? [];

    $sessionEmail = $data["email"];

    foreach ($commandes as $commande) {
        if (!empty($commande["email"]) && strtolower(trim($commande["email"])) === $sessionEmail) {
            $commandesUtilisateur[] = $commande;
        }
    }
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
<link href="https://fonts.googleapis.com/css2?family=Playwrite+AT:ital,wght@0,100..400;1,100..400&display=swap" rel="stylesheet">
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
        <h1>Mon Profil</h1>
    </div>

    <div class="dashboard">
        <div class="carte">
            <h2>Mes Informations</h2>
            <div class="info-ligne"><span class="info-label">Statut</span><?php echo htmlspecialchars($data["statut"]); ?></div>
            <div class="info-ligne"><span class="info-label">Nom</span><?php echo htmlspecialchars($data["nom"]); ?></div>
            <div class="info-ligne"><span class="info-label">Prénom</span><?php echo htmlspecialchars($data["prenom"]); ?></div>
            <div class="info-ligne"><span class="info-label">E-mail</span><?php echo htmlspecialchars($data["email"]); ?></div>
            <div class="info-ligne"><span class="info-label">Mot de passe</span>********</div>
            <div class="info-ligne"><span class="info-label">Téléphone</span><?php echo htmlspecialchars($data["telephone"]); ?></div>
            <div class="info-ligne"><span class="info-label">Adresse</span><?php echo htmlspecialchars($data["adresse"]); ?></div>
            <div class="info-ligne"><span class="info-label">Informations complémentaires</span><?php echo htmlspecialchars($data["infos"]); ?></div>
            <button class="btn-modifier">
                <img src="Images/stylo.png" alt="Modifier" class="crayon-icon">
                Modifier
            </button>
        </div>

        <div class="carte">
            <h2>Anciennes Commandes</h2>
            <?php if(empty($commandesUtilisateur)): ?>
                <p>Aucune commande trouvée.</p>
            <?php else: ?>
                <?php foreach($commandesUtilisateur as $commande): ?>
                    <div class="commande">
                        <p><strong>Commande #<?php $i = 1; echo $i; $i++;?></strong></p>
                        <p>Date : <?php echo htmlspecialchars($commande["date_souhaitee"] ?? $commande["date"]); ?></p>
                        <p><strong>Plats :</strong></p>
                        <ul>
                            <?php foreach($commande["plats"] as $plat): ?>
                                <li><?php echo htmlspecialchars($plat["nom"]); ?> x<?php echo $plat["quantite"]; ?> (<?php echo $plat["prix"]; ?> €)</li>
                            <?php endforeach; ?>
                        </ul>
                        <hr>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
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