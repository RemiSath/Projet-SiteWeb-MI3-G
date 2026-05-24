<?php
session_start();
include "bibliothèques/bloquer.php";

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
    </div>
</header>

<div class="container">
    <div class="image-section">
        <img src="Images/restaurant.png" alt="Restaurant">
    </div>

    <div class="reservation">
        <p class="subtitle">FAIRE UNE RÉSERVATION</p>
        <h2>Réserver une table</h2>

        <form id="formReservation" action="reservation-infos.php" method="POST">
            <?php
            if (isset($_SESSION["erreur"])) {
                echo "<div class='erreur'>" . htmlspecialchars($_SESSION["erreur"]) . "</div>";
                unset($_SESSION["erreur"]);
            }
            ?>

            <div class="row">
                <input type="text" id="nom" name="nom" placeholder="Nom" value="<?php echo htmlspecialchars($nom); ?>" required>
                <input type="text" id="prenom" name="prenom" placeholder="Prénom" value="<?php echo htmlspecialchars($prenom); ?>" required>
            </div>

            <?php if (!$isConnected) { ?>
                <div class="row">
                    <input type="email" id="email" name="email" placeholder="Email" required>
                </div>
            <?php } else { ?>
                <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
            <?php } ?>

            <div class="row">
                <input type="number" id="adultes" name="adultes" placeholder="Adultes" min="1" required>
                <input type="number" id="enfants" name="enfants" placeholder="Enfants" min="0" required>
            </div>

            <div class="row">
                <input type="date" id="date" name="date" required>
                <input type="time" id="time" name="time" required>
            </div>

            <select id="restaurant" name="restaurant" required>
                <option value="">Choisir un restaurant</option>
                <option value="Paris">Paris</option>
                <option value="Argenteuil">Argenteuil</option>
                <option value="Cergy">Cergy</option>
            </select>

            <textarea id="commentaire" name="commentaire" placeholder="Commentaire ou demande spéciale"></textarea>

            <button type="submit">Réserver</button>
        </form>
    </div>
</div>

<footer class="footer">
    <p>📞 Téléphone : 07 67 01 02 03</p>
    <p>✉ Email : imposteurcontact@gmail.com</p>
    <p>Horaires : Lundi - Vendredi 10h-21h | Samedi - Dimanche 12h-18h</p>
</footer>

<script>
    const form = document.getElementById("formReservation");

    form.addEventListener("submit", reservation);

    async function reservation(event){
        supprimerMessages();
        let valide = true;
        const nom = document.getElementById("nom");
        const prenom = document.getElementById("prenom");
        const email = document.getElementById("email");
        const adultes = document.getElementById("adultes");
        const enfants = document.getElementById("enfants");
        const date = document.getElementById("date");
        const time = document.getElementById("time");
        const restaurant = document.getElementById("restaurant");
        const commentaire = document.getElementById("commentaire");

        if(nom.value.trim().length < 1){
            afficherErreur(nom, "Nom invalide");
            valide = false;
        }
        else{
            afficherCorrect(nom, "Nom valide");
        }

        if(prenom.value.trim().length < 1){
            afficherErreur(prenom, "Prénom invalide");
            valide = false;
        }
        else{
            afficherCorrect(prenom, "Prénom valide");
        }

        if(email){
            const regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if(!regexEmail.test(email.value)){
                afficherErreur(email, "Email invalide");
                valide = false;
            }
            else{
                afficherCorrect(email, "Email valide");
            }
        }

        if(parseInt(adultes.value) < 1){
            afficherErreur(adultes, "Il faut au moins 1 adulte");
            valide = false;
        }
        else{
            afficherCorrect(adultes, "Nombre valide");
        }

        if(parseInt(enfants.value) < 0){
            afficherErreur(enfants, "Nombre invalide");
            valide = false;
        }
        else{
            afficherCorrect(enfants, "Nombre valide");
        }

        const maintenant = new Date();
        const dateReservation = new Date(date.value + "T" + time.value);

        if(date.value === "" || time.value === ""){
            afficherErreur(date, "Date ou heure invalide");
            valide = false;
        }
        else if(dateReservation <= maintenant){
            afficherErreur(date, "La réservation doit être dans le futur");
            valide = false;
        }
        else{
            afficherCorrect(date, "Date valide");
        }
        if(restaurant.value === ""){
            afficherErreur(restaurant, "Choisissez un restaurant");
            valide = false;
        }
        else{
            afficherCorrect(restaurant, "Restaurant valide");
        }
        if(commentaire.value.length > 200){
            afficherErreur(commentaire, "200 caractères maximum");
            valide = false;
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

        const inputs = document.querySelectorAll("input, textarea, select");
        inputs.forEach(function(input){
            input.classList.remove("input-erreur");
            input.classList.remove("input-correct");
        });
    }
</script>

</body>
</html>