<?php
    session_start();
    include "bibliothèques/bloquer.php";

    $fichierCommandes = "data/commandes.json";
    $fichierNotations = "data/notations.json";
    $commandes = [];
    $notations = [];

    if(file_exists($fichierCommandes)){
        $json = file_get_contents($fichierCommandes);
        $commandes = json_decode($json, true) ?? [];
    }

    if(file_exists($fichierNotations)){
        $json = file_get_contents($fichierNotations);
        $notations = json_decode($json, true) ?? [];
    }

    $emailClient = $_SESSION["email"] ?? "";

    $commandesLivrees = [];

    foreach($commandes as $commande){
        $dejaNotee = false;
        foreach($notations as $notation){
            if(isset($notation["commande_id"]) && $notation["commande_id"] == $commande["id"]){
                $dejaNotee = true;
                break;
            }
        }

        if(isset($commande["email"]) && $commande["email"] === $emailClient && isset($commande["statut"]) && $commande["statut"] === "livree" && !$dejaNotee){
            $commandesLivrees[] = $commande;
        }
    }
?>

<!DOCTYPE html>
<html>

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
            <input type="text" id="searchInput2" placeholder="Rechercher nos produits ..." autocomplete="off">
        </div>
    </nav>

    <div class="sitedescription4"> 
        <h1>Notez-nous</h1>
        <p class="textedescription4">Votre avis compte pour nous !</p>
        <div class="container-notation">
            <div class="reservation2">
                <?php
                    if(isset($_SESSION["message"])){
                        echo "<div class='message'>" . $_SESSION["message"] . "</div>";
                        unset($_SESSION["message"]);
                    }
                ?>
                    <?php 
                        if(count($commandesLivrees) > 0){ 
                            foreach($commandesLivrees as $commande){ 
                    ?>
                        <form action="envoyer-notation.php" method="POST">
                            <input type="hidden" name="commande_id" value="<?php echo $commande["id"]; ?>">
                            <h3>Commande #<?php echo $commande["id"]; ?></h3>
                            <div class="row">
                                <input type="number" name="livraison" required placeholder="Livraison" min="0" max="5">
                                <p>0 à 5</p>
                            </div>
                            <div class="row">
                                <input type="number" name="qualite" required placeholder="Qualité des produits" min="0" max="5">
                                <p>0 à 5</p>
                            </div>
                            <div class="row">
                                <textarea name="commentaires" placeholder="Commentaires"
                                ></textarea>
                            </div>
                            <button type="submit">
                                Envoyer
                            </button>
                        </form>

                    <?php 
                            } 
                        }
                        else{
                    ?> <p>Aucune commande livrée à noter.</p>
                <?php 
                    } 
                ?>
            </div>
            <div class="vide"></div>
        </div>
    </div>

    <footer class="footer">
        <p>📞 Téléphone : 07 67 01 02 03</p>
        <p>✉ Email : imposturecontact@gmail.com</p>
        <p>Horaires : Lundi - Vendredi 10h-21h | Samedi - Dimanche 12h-18h</p>
    </footer>

</body>

</html>