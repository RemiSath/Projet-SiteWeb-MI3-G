<?php
session_start();
include "bibliothèques/bloquer.php";

if (!isset($_SESSION["email"])) {
    $_SESSION["erreur"] = "Connectez-vous pour noter une commande.";
    header("Location: page-d'accueil.php");
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
    <link id="theme-link" rel="stylesheet" href="css/default.css">
    <link rel="icon" href="Images/Among_Us.png">
    <title>Notation</title>
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
                <form id="formNotation" action="envoyer-notation.php" method="POST">
                    <label>Commande livrée à noter</label>
                    <select id="commande_id" name="commande_id" required>
                        <?php foreach ($commandesLivrees as $commande) { ?>
                            <option value="<?php echo htmlspecialchars($commande["id"] ?? ""); ?>">
                                Commande #<?php echo htmlspecialchars($commande["id"] ?? ""); ?>
                                -
                                <?php echo htmlspecialchars($commande["date"] ?? ""); ?>
                            </option>
                        <?php } ?>
                    </select>
                    <div class="row">
                        <input type="number" id="livraison" name="livraison" placeholder="Livraison" min="0" max="5" required>
                        <p>0 à 5</p>
                    </div>
                    <div class="row">
                        <input type="number" id="qualite" name="qualite" placeholder="Qualité des produits" min="0" max="5" required>
                        <p>0 à 5</p>
                    </div>
                    <div class="row">
                        <textarea id="commentaires" name="commentaires" placeholder="Commentaires"></textarea>
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
        <div class="vide">
            <img src="Images/crewmate.gif" alt="" class="crewmate-notation">
        </div>
    </div>
</div>

<footer class="footer">        
    <p>📞 Téléphone : 07 67 01 02 03</p>
    <p>✉ Email : imposteurcontact@gmail.com</p>
    <p>Horaires : Lundi - Vendredi 10h-21h | Samedi - Dimanche 12h-18h</p>
</footer>

<script>
    const form = document.getElementById("formNotation");
    if(form){
        form.addEventListener("submit", notation);
    }
    function notation(event){
        supprimerMessages();
        let valide = true;
        const commande = document.getElementById("commande_id");
        const livraison = document.getElementById("livraison");
        const qualite = document.getElementById("qualite");
        const commentaires = document.getElementById("commentaires");
        if(commande.value === ""){
            afficherErreur(commande, "Choisissez une commande");
            valide = false;
        }
        else{
            afficherCorrect(commande, "Commande sélectionnée");
        }
        if(livraison.value === "" || livraison.value < 0 || livraison.value > 5){
            afficherErreur(livraison, "Note entre 0 et 5");
            valide = false;
        }
        else{
            afficherCorrect(livraison, "Note valide");
        }
        if(qualite.value === "" || qualite.value < 0 || qualite.value > 5){
            afficherErreur(qualite, "Note entre 0 et 5");
            valide = false;
        }
        else{
            afficherCorrect(qualite, "Note valide");
        }
        if(commentaires.value.length > 300){
            afficherErreur(commentaires, "300 caractères maximum");
            valide = false;
        }
        else{
            afficherCorrect(commentaires, "Commentaire valide");
        }
        if(valide){
            event.preventDefault();

            const message = document.createElement("div");
            message.innerText = "✓ Formulaire valide, envoi en cours...";
            message.classList.add("correct-js");

            form.prepend(message);

            setTimeout(function(){
                form.submit();
            }, 3000);
        }
        else{
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
        const inputs = document.querySelectorAll("input, textarea, select");
        inputs.forEach(function(input){
            input.classList.remove("input-erreur");
            input.classList.remove("input-correct");
        });
    }
</script>
<script src="cookie.js"></script>
</body>
</html>
