<?php
session_start();

$panier = $_SESSION["panier"] ?? [];
$total = 0;
$isConnected = isset($_SESSION["email"]);

$nom = $_SESSION["nom"] ?? "";
$prenom = $_SESSION["prenom"] ?? "";
$email = $_SESSION["email"] ?? "";
$telephone = $_SESSION["telephone"] ?? "";
$adresse = $_SESSION["adresse"] ?? "";
$infos = $_SESSION["infos"] ?? "";

foreach ($panier as $item) {
    $total += $item["prix"] * $item["quantite"];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Commander</title>
<link rel="stylesheet" href="styles.css">
<link rel="icon" href="Images/Among_Us.png">
</head>
<body>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Bungee&display=swap" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playwrite+AT:ital,wght@0,100..400;1,100..400&display=swap" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Annie+Use+Your+Telescope&display=swap" rel="stylesheet">

<header class="navbar">
    <a href="page-d'accueil.php" class="accueil">IMPOSTURE</a>
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

<div class="container2">

    <h4>📦 Informations de livraison</h4>

    <aside class="cart-box">
        <h5>🛒 Votre panier</h5>

        <?php if (empty($panier)) { ?>
            <div class="cart-empty">
                <p>Votre panier est vide.</p>
                <p>Vous pouvez remplir votre panier en visitant :</p>
                <ul class="cities">
                    <li><a href="Paris.php">Paris</a></li>
                    <li><a href="Argenteuil.php">Argenteuil</a></li>
                    <li><a href="Cergy.php">Cergy</a></li>
                </ul>
            </div>
        <?php } else { ?>
            <ul class="cart-items">
                <?php foreach ($panier as $item) { 
                    $sousTotal = $item["prix"] * $item["quantite"];
                ?>
                    <li>
                        <span class="item-name"><?php echo htmlspecialchars($item["nom"]); ?></span>
                        <span class="item-qty">x<?php echo $item["quantite"]; ?></span>
                        <span class="item-price"><?php echo number_format($sousTotal, 2, ',', ' '); ?> €</span>

                        <form method="post" action="supprimer_panier.php" style="display:inline;">
                            <input type="hidden" name="nom" value="<?php echo htmlspecialchars($item["nom"]); ?>">
                            <button type="submit" class="remove-btn">Supprimer</button>
                        </form>
                    </li>
                <?php } ?>
            </ul>

            <div class="cart-total">
                <strong>Total : <?php echo number_format($total, 2, ',', ' '); ?> €</strong>
            </div>
            <form method="post" action="vider_panier.php">
                <button type="submit">🧹 Vider le panier</button>
            </form>

            <details class="cart-sample">
                <summary>Voir les produits disponibles</summary>
                <div class="cart-actions">
                    <p class="note">
                        Continue ta commande ou ajoute d'autres desserts depuis les villes :
                    </p>
                    <div class="city-links">
                        <a class="btn-link" href="Paris.php">Voir Paris</a>
                        <a class="btn-link" href="Argenteuil.php">Voir Argenteuil</a>
                        <a class="btn-link" href="Cergy.php">Voir Cergy</a>
                    </div>
                </div>
            </details>
        <?php } ?>
    </aside>

    <div class="account-box">
        <p>Déjà client ?</p>
        <a href="connexion.php" class="connexion">Se connecter</a>
    </div>

    <form method="post" action="valider_commande.php">
    <h5>Adresse</h5>

    <?php if ($isConnected) { ?>
        <label>Nom complet</label>
        <input type="text" value="<?php echo htmlspecialchars($prenom . ' ' . $nom); ?>" readonly>

        <label>Adresse</label>
        <input type="text" value="<?php echo htmlspecialchars($adresse); ?>" readonly>

        <label>Code postal</label>
        <input type="text" name="postal_code" required>

        <label>Ville</label>
        <input type="text" name="city" required>

        <label>Téléphone</label>
        <input type="tel" value="<?php echo htmlspecialchars($telephone); ?>" readonly>

        <label>Email</label>
        <input type="email" value="<?php echo htmlspecialchars($email); ?>" readonly>

        <input type="hidden" name="name" value="<?php echo htmlspecialchars($prenom . ' ' . $nom); ?>">
        <input type="hidden" name="address" value="<?php echo htmlspecialchars($adresse); ?>">
        <input type="hidden" name="phone" value="<?php echo htmlspecialchars($telephone); ?>">
        <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">

    <?php } else { ?>
        <label>Nom complet</label>
        <input type="text" name="name" required>

        <label>Adresse</label>
        <input type="text" name="address" required>

        <label>Code postal</label>
        <input type="text" name="postal_code" required>

        <label>Ville</label>
        <input type="text" name="city" required>

        <label>Téléphone</label>
        <input type="tel" name="phone" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <h5>Créer un compte</h5>
        <label>Mot de passe</label>
        <input type="password" name="motdepasse" required>
    <?php } ?>

    <h5>Informations pour le livreur</h5>
    <label>Code interphone</label>
    <input type="text" name="interphone">

    <label>Étage</label>
    <input type="text" name="floor">

    <label>Commentaires</label>
    <textarea name="comments" rows="3"></textarea>

    <h5>Type de commande</h5>

    <div class="planification-container">
        <div class="option">
            <input type="radio" id="immediate" name="planification" value="immediate" checked>
            <label for="immediate">Préparation immédiate</label>
        </div>
        <div class="option">
            <input type="radio" id="plus_tard" name="planification" value="plus_tard">
            <label for="plus_tard">Commander pour plus tard</label>
        </div>
        <div class="planification-fields">
            <label>Date souhaitée</label>
            <input type="date" name="date_souhaitee">
            <label>Heure souhaitée</label>
            <input type="time" name="heure_souhaitee">
        </div>
    </div>

        <button type="submit" class="submit-btn">
            Continuer vers le paiement
        </button>
    </form>
</div>

<footer class="footer">
    <p>📞 Téléphone : 07 61 41 44 23</p>
    <p>✉ Email : imposturecontact@gmail.com</p>
    <p>Horaires : Lundi - Vendredi 10h-21h | Samedi - Dimanche 12h-18h</p>
</footer>

</body>
</html>
