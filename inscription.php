<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="icon" href="Images/Among_Us.png">
    <link rel="stylesheet" href="styles.css">
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
        </div>
    </header>

    <div class="container3"> <!-- Conteneur pour le formulaire d'inscription -->
        <h1>Inscription</h1>
        <form id="formInscription" action="inscription-infos.php" method="POST">
            <?php
                if(isset($_SESSION["erreur"])){ // Vérifie si une erreur est stockée dans la session
                    echo "<div class='erreur'>" . $_SESSION["erreur"] . "</div>";
                    unset($_SESSION["erreur"]);
                }
            ?>
            <div class="form-group">
                <label class="label1" for="nom">Nom</label>
                <input type="text" id="nom" name="nom" required placeholder="Votre nom">
            </div>
            <div class="form-group">
                <label class="label1" for="prenom">Prénom</label>
                <input type="text" id="prenom" name="prenom" required placeholder="Votre prénom">
            </div>
            <div class="form-group">
                <label class="label1" for="email">E-mail</label>
                <input type="email" id="email" name="email" required placeholder="exemple@domaine.com">
            </div>
            <div class="form-group">
                <label class="label1" for="motdepasse">Mot de passe</label>
                <div class="password-container">
                    <input type="password" id="motdepasse" name="motdepasse" required placeholder="XXXXXXXXXXX">
                    <button type="button" class="oeil" id="oeil">
                        <img id="image-oeil" src="Images/Oeil_amongus.png" alt="Afficher mot de passe">
                    </button>
                </div>
            </div>
            <div class="form-group">
                <label class="label1" for="adresse">Adresse de livraison</label>
                <input type="text" id="adresse" name="adresse" required placeholder="N° et Rue, Ville, Code Postal">
            </div>
            <div class="form-group">
                <label class="label1" for="telephone">Numéro de téléphone</label>
                <input type="tel" id="telephone" name="telephone" required placeholder="06 00 00 00 00">
            </div>
            <div class="form-group">
                <label class="label1" for="infos">Informations complémentaires</label>
                <textarea id="infos" name="infos" placeholder="Allergies, Etc..."></textarea>
            </div>
            <button type="submit" name="submit">S'inscrire</button>
        </form>

    </div>

    <footer class="footer">
        <p>📞 Téléphone : 07 67 01 02 03</p>
        <p>✉ Email : imposteurcontact@gmail.com</p>
        <p>Horaires : Lundi - Vendredi 10h-21h | Samedi - Dimanche 12h-18h</p>
    </footer>

    <script>
        const oeil = document.getElementById('oeil');
        const mdpInput = document.getElementById('motdepasse');
        const imageOeil = document.getElementById('image-oeil');

        oeil.addEventListener('click', () => {
            if(mdpInput.type === 'password'){
                mdpInput.type = 'text';
                imageOeil.src = "Images/Oeil_ferme.png";
            } 
            else{
                mdpInput.type = 'password';
                imageOeil.src = "Images/Oeil_amongus.png";
            }
        });
        
        const form = document.getElementById("formInscription");

        form.addEventListener("submit", inscription);

        async function inscription(event){
            supprimerMessages();
            let valide = true;
            const nom = document.getElementById("nom");
            const prenom = document.getElementById("prenom");
            const email = document.getElementById("email");
            const motdepasse = document.getElementById("motdepasse");
            const telephone = document.getElementById("telephone");
            const adresse = document.getElementById("adresse");

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

            const regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if(!regexEmail.test(email.value)){
                afficherErreur(email, "Email invalide");
                valide = false;
            }
            else{
                afficherCorrect(email, "Email valide");
            }
            if(motdepasse.value.length < 6){
                afficherErreur(motdepasse, "Mot de passe trop court (6 caractères minimum)");
                valide = false;
            }
            else{
                afficherCorrect(motdepasse, "Mot de passe valide");
            }

            const regexTelephone = /^0[1-9](\s?[0-9]{2}){4}$/;
            
            if(!regexTelephone.test(telephone.value)){
                afficherErreur(telephone, "Téléphone invalide");
                valide = false;
            }
            else{
                afficherCorrect(telephone, "Téléphone valide");
            }
            if(adresse.value.trim().length < 4){
                afficherErreur(adresse, "Adresse invalide");
                valide = false;
            }
            else{
                afficherCorrect(adresse, "Adresse valide");
            }
            if(valide == false){
                event.preventDefault();
            }

        }

        function afficherErreur(input, message){
            const erreur = document.createElement("div");
            erreur.innerText = message;
            erreur.classList.add("erreur-js");
            input.closest(".form-group").appendChild(erreur);
            input.classList.add("input-erreur");
        }

        function afficherCorrect(input, message){
            const correct = document.createElement("div");
            correct.innerText = message;
            correct.classList.add("correct-js");
            input.closest(".form-group").appendChild(correct);
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

        const infosInput = document.getElementById("infos");
        const compteur = document.createElement("div");
        compteur.classList.add("compteur");
        infosInput.parentElement.appendChild(compteur);
        
        infosInput.addEventListener("input", infos);
        async function infos() {
            if(infosInput.value.length > 200){
                infosInput.value = infosInput.value.substring(0, 200);
            }
            compteur.innerText = infosInput.value.length + "/200 caractères";
        }
    </script>
    
</body>

</html>