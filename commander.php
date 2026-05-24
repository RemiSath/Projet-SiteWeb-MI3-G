<?php
session_start();
include "bibliothèques/bloquer.php";

$panier = $_SESSION["panier"] ?? [];
$total = 0;
$isConnected = !empty($_SESSION["email"]);

$nom = $_SESSION["nom"] ?? "";
$prenom = $_SESSION["prenom"] ?? "";
$email = $_SESSION["email"] ?? "";
$telephone = $_SESSION["telephone"] ?? "";
$adresse = $_SESSION["adresse"] ?? "";
$infos = $_SESSION["infos"] ?? "";

$totalTicketsReduction = 0;

if ($isConnected) {
    $fichierCommandes = __DIR__ . "/data/commandes.json";
    if (file_exists($fichierCommandes)) {
        $jsonCommandes = file_get_contents($fichierCommandes);
        $commandes = json_decode($jsonCommandes, true);
        if (is_array($commandes)) {
            foreach ($commandes as $commande) {
                if (strtolower(trim($commande["email"] ?? "")) === strtolower(trim($email))) {
                    $ticket = floatval($commande["ticket_reduction"] ?? 0);
                    $ticketUtilise = floatval($commande["ticket_reduction_utilise"] ?? 0);
                    $totalTicketsReduction += max(0, $ticket - $ticketUtilise);
                }
            }
        }
    }
}

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

<div class="container2">
    <h4>📦 Informations de livraison</h4>

    <?php
    if (isset($_SESSION["erreur"])) {
        echo "<div class='erreur'>" . htmlspecialchars($_SESSION["erreur"]) . "</div>";
        unset($_SESSION["erreur"]);
    }
    ?>

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
                        <span class="item-qty">x<?php echo htmlspecialchars($item["quantite"]); ?></span>
                        <span class="item-price"><?php echo number_format($sousTotal, 2, ',', ' '); ?> €</span>

                        <form method="post" action="supprimer-panier.php" style="display:inline;">
                            <input type="hidden" name="nom" value="<?php echo htmlspecialchars($item["nom"]); ?>">
                            <button type="submit" class="remove-btn">Supprimer</button>
                        </form>
                    </li>
                <?php } ?>
            </ul>

            <div class="cart-total">
                <strong>Total : <?php echo number_format($total, 2, ',', ' '); ?> €</strong>
            </div>

            <form method="post" action="vider-panier.php">
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

    <?php if (!$isConnected) { ?>
        <div class="account-box">
            <p>Déjà client ?</p>
            <a href="connexion.php" class="connexion">Se connecter</a>
        </div>
    <?php } else { ?>
        <div class="account-box">
            <p>Connecté en tant que <?php echo htmlspecialchars($_SESSION["prenom"]); ?></p>
        </div>
    <?php } ?>

    <form id="formCommande" method="post" action="valider-commande.php">
        <h5>Adresse</h5>

        <?php if ($isConnected) { ?>
            <label>Nom complet</label>
            <input type="text" value="<?php echo htmlspecialchars($prenom . ' ' . $nom); ?>" readonly>

            <label>Adresse</label>
            <input type="text" value="<?php echo htmlspecialchars($adresse); ?>" readonly>

            <label>Code postal</label>
            <input type="text" id="postal_code" name="postal_code" required>

            <label>Ville</label>
            <input type="text" id="city" name="city" required>

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
            <input type="text" id="name" name="name" required>

            <label>Adresse</label>
            <input type="text" id="address" name="address" required>

            <label>Code postal</label>
            <input type="text" id="postal_code" name="postal_code" required>

            <label>Ville</label>
            <input type="text" id="city" name="city" required>

            <label>Téléphone</label>
            <input type="tel" id="phone" name="phone" required>

            <label>Email</label>
            <input type="email" id="email" name="email" required>

            <h5>Créer un compte</h5>
            <label>Mot de passe</label>
            <input type="password" id="motdepasse" name="motdepasse" required>
        <?php } ?>

        <h5>Informations pour le livreur</h5>
        <label>Code interphone</label>
        <input type="text" id="interphone" name="interphone">

        <label>Étage</label>
        <input type="text" id="floor" name="floor">

        <label>Commentaires</label>
        <textarea id="comments" name="comments" rows="3"></textarea>

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
                <input type="date" id="date_souhaitee" name="date_souhaitee">

                <label>Heure souhaitée</label>
                <input type="time" id="heure_souhaitee" name="heure_souhaitee">
            </div>
        </div>

        <?php if ($isConnected && $totalTicketsReduction > 0) { ?>
            <h5>Bon de réduction</h5>

            <div class="option">
                <input type="checkbox" id="utiliser_reduction" name="utiliser_reduction" value="1">
                <label for="utiliser_reduction">
                    Utiliser mon bon de réduction de
                    <?php echo number_format($totalTicketsReduction, 2, ',', ' '); ?> €
                </label>
            </div>
        <?php } ?>

        <button type="submit" class="submit-btn">
            Valider la commande
        </button>
    </form>
</div>

<footer class="footer">
    <p>📞 Téléphone : 07 67 01 02 03</p>
    <p>✉ Email : imposteurcontact@gmail.com</p>
    <p>Horaires : Lundi - Vendredi 10h-21h | Samedi - Dimanche 12h-18h</p>
</footer>

<script>
    const form = document.getElementById("formCommande");

    form.addEventListener("submit", commande);

    async function commande(event){
        supprimerMessages();
        const panierVide = document.querySelector(".cart-empty");

        if(panierVide){
            event.preventDefault();
            alert("Votre panier est vide");
            return;
        }

        let valide = true;
        const postal = document.getElementById("postal_code");
        const ville = document.getElementById("city");
        const interphone = document.getElementById("interphone");
        const floor = document.getElementById("floor");
        const comments = document.getElementById("comments");
        const date = document.getElementById("date_souhaitee");
        const heure = document.getElementById("heure_souhaitee");
        const plusTard = document.getElementById("plus_tard");
        const nom = document.getElementById("name");
        const adresse = document.getElementById("address");
        const telephone = document.getElementById("phone");
        const email = document.getElementById("email");
        const motdepasse = document.getElementById("motdepasse");
        const regexPostal = /^[0-9]{5}$/;
        const regexTelephone = /^0[1-9](\s?[0-9]{2}){4}$/;
        const regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if(nom){
            if(nom.value.trim().length < 2){
                afficherErreur(nom, "Nom invalide");
                valide = false;
            }
            else{
                afficherCorrect(nom, "Nom valide");
            }
        }
        if(adresse){
            if(adresse.value.trim().length < 4){
                afficherErreur(adresse, "Adresse invalide");
                valide = false;
            }
            else{
                afficherCorrect(adresse, "Adresse valide");
            }
        }
        if(telephone){
            if(!regexTelephone.test(telephone.value)){
                afficherErreur(telephone, "Téléphone invalide");
                valide = false;
            }
            else{
                afficherCorrect(telephone, "Téléphone valide");
            }
        }
        if(email){
            if(!regexEmail.test(email.value)){
                afficherErreur(email, "Email invalide");
                valide = false;
            }
            else{
                afficherCorrect(email, "Email valide");
            }
        }
        if(motdepasse){
            if(motdepasse.value.length < 6){
                afficherErreur(motdepasse, "Mot de passe trop court");
                valide = false;
            }
            else{
                afficherCorrect(motdepasse, "Mot de passe valide");
            }
        }
        if(!regexPostal.test(postal.value)){
            afficherErreur(postal, "Code postal invalide");
            valide = false;
        }
        else{
            afficherCorrect(postal, "Code postal valide");
        }

        if(ville.value.trim().length < 2){
            afficherErreur(ville, "Ville invalide");
            valide = false;
        }
        else{
            afficherCorrect(ville, "Ville valide");
        }
        if(interphone.value.length > 30){
            afficherErreur(interphone, "30 caractères maximum");
            valide = false;
        }
        if(floor.value.length > 10){
            afficherErreur(floor, "Valeur invalide");
            valide = false;
        }

        if(comments.value.length > 200){
            afficherErreur(comments, "200 caractères maximum");
            valide = false;
        }
        if(plusTard.checked){
            const maintenant = new Date();
            const dateCommande = new Date(date.value + "T" + heure.value);
            if(date.value === "" || heure.value === ""){
                afficherErreur(date, "Date et heure obligatoires");
                valide = false;
            }
            else if(dateCommande <= maintenant){
                afficherErreur(date, "Choisissez une date future");
                valide = false;
            }
            else{
                afficherCorrect(date, "Date valide");
            }
        }
        if(valide == false){
            event.preventDefault();
        }
    }

    function afficherErreur(input, message){
        const erreur = document.createElement("div");
        erreur.innerText = message;
        erreur.classList.add("erreur-js");
        input.parentElement.appendChild(erreur);
        input.classList.add("input-erreur");
    }

    function afficherCorrect(input, message){
        const correct = document.createElement("div");
        correct.innerText = message;
        correct.classList.add("correct-js");
        input.parentElement.appendChild(correct);
        input.classList.add("input-correct");
    }

    function supprimerMessages(){
        const messages = document.querySelectorAll(".erreur-js, .correct-js");
        messages.forEach(function(message){
            message.remove();
        });
        const inputs = document.querySelectorAll("input, textarea");
        inputs.forEach(function(input){
            input.classList.remove("input-erreur");
            input.classList.remove("input-correct");
        });
    }
</script>

</body>
</html>
