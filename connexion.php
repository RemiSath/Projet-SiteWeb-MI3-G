<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="icon" href="Images/Among_Us.png">
    <link rel="stylesheet" href="styles.css">
</head>

<body class="pageconnexion">
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
            <input type="text" id="searchInput2" placeholder="Rechercher nos produits ..." autocomplete="off">
        </div>
    </header>

    <div class="container3"> <!-- Conteneur pour le formulaire de connexion -->
        <h1 class="h7">Connexion</h1>
        <?php
            if(isset($_SESSION["erreurConnexion"])){
                echo "<div class='erreur-js'>" . $_SESSION["erreurConnexion"] . "</div>";
                unset($_SESSION["erreurConnexion"]);
            }
        ?> 
        <form id="formConnexion" action="lecture.php" method="POST">
            <div class="form-group"> 
                <label class="label1" for="email">E-mail</label>
                <input type="email" id="email" name="email" required placeholder="exemple@domaine.com">
            </div>
            <div class="form-group">
                <label class="label1" for="password">Mot de passe</label>
                <div class="password-container">
                    <input type="password" id="password" name="password" required placeholder="Votre mot de passe">
                <button type="button" class="oeil" id="oeil">
                    <img id="image-oeil" src="Images/Oeil_amongus.png" alt="Afficher mot de passe">
                </button>
                </div>
            </div>

            <button type="submit">Se connecter</button>
        </form>

        <div class="liens-secondaires">
            <a href="inscription.php">Pas encore de compte ? S'inscrire</a>
        </div>
    </div>

    <footer class="footer">
        <p>📞 Téléphone : 07 67 01 02 03</p>
        <p>✉ Email : imposteurcontact@gmail.com</p>
        <p>Horaires : Lundi - Vendredi 10h-21h | Samedi - Dimanche 12h-18h</p>
    </footer>

    <script>
        const form = document.getElementById("formConnexion");
        const email = document.getElementById("email");
        const password = document.getElementById("password");
        const regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const oeil = document.getElementById("oeil");
        const motdepasse = document.getElementById("password");
        const imageOeil = document.getElementById("image-oeil");

        oeil.addEventListener("click", () => {
            if(motdepasse.type === "password"){
                motdepasse.type = "text";
                imageOeil.src = "Images/Oeil_ferme.png";
            }
            else{
                motdepasse.type = "password";
                imageOeil.src = "Images/Oeil_amongus.png";
            }
        });

        form.addEventListener("submit", connexion); 
        
        async function connexion(event){

            event.preventDefault();
            supprimerMessages();

            if(!regexEmail.test(email.value)){
                afficherErreur(email, "Adresse mail invalide, pas la bonne syntaxe");
                return;
            }
            
            const formData = new FormData(form);

            try{
                const response = await fetch("lecture.php", {
                    method: "POST",
                    body: formData
                });

                const data = await response.text();

                if(data === "email"){
                    afficherErreur(email, "Adresse mail introuvable ou incorrecte");
                }
                else if(data === "password"){
                    afficherErreur(password, "Mot de passe incorrect");
                }
                else if(data === "bloque"){
                    afficherErreur(password, "Compte bloqué par l'administrateur");
                }
                else{
                    window.location.href = data;
                }
            }

            catch(error){
                afficherErreur(password, "ERREUR : Impossible de se connecter");
            }
        }

        function afficherErreur(input, message){
            const erreur = document.createElement("div");
            erreur.innerText = message;
            erreur.classList.add("erreur-js");
            input.closest(".form-group").appendChild(erreur);
            input.classList.add("input-erreur");
        }

        function supprimerMessages(){
            document.querySelectorAll(".erreur-js").forEach(e => e.remove());
            
            document.querySelectorAll("input").forEach(i => {
                i.classList.remove("input-erreur");
            });
        }
    </script>
</body>

</html>
