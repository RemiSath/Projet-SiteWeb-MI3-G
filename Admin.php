<?php
    session_start();

    if(!isset($_SESSION["email"])){
        $_SESSION["erreur2"] = "Accès réservé aux administrateurs.";
        header("Location: connexion.php");
        exit;
    }

    if(!isset($_SESSION["statut"]) || $_SESSION["statut"] !== "Admin"){
        $_SESSION["erreur"] = "Accès réservé aux administrateurs.";
        header("Location: page-d'accueil.php");
        exit;
    }

    $fichier = "compte.json";

    if(file_exists($fichier)){
        $json = file_get_contents($fichier);
        $utilisateurs = json_decode($json, true) ?? [];
    } 
    else {
        $utilisateurs = [];
    }
    
    $recherche = $_GET['recherche'] ?? '';

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="Images/Among_Us.png">
    <title>Administrateur</title>
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
                    <a href="reserver.html">Réserver une table</a>
                    <a href="#">Commander</a>
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
                    <a href="commandes.html">Commandes</a>
                    <a href="livraison.html">Livraison</a>
                </div>
            </div>
            <div class="menu">
                <a href="Admin.php">Admin</a>
            </div>
            <input type="text" id="searchInput2" placeholder="Rechercher nos produits ..." autocomplete="off">
        </div>
    </nav>

    <div class="sitedescription2">
        <h1>Page Administrateur</h1>
    </div>

    <header id="topBar">
        <form method="GET">
            <input type="text" name="recherche" id="searchInput" placeholder="Rechercher un client..." value="<?php echo htmlspecialchars($_GET['recherche'] ?? '') ?>">
        </form>
        <form action="lecture.php" method="post">
            <div id="results">
                <div class="utilisateur">
                <?php 
                    foreach($utilisateurs as $utilisateur):
                        if($utilisateur["email"] === $_SESSION["email"] || $utilisateur["statut"] === "Admin"){
                            continue;
                        }
                        if($recherche !== "" && stripos($utilisateur["prenom"], $recherche) === false){
                            continue;
                        }
                ?>
                <div class="card">
                    <a href="admin-pouvoirs.php?id=<?= $utilisateur["id"] ?>">
                    <?php
                        echo htmlspecialchars($utilisateur["prenom"]);
                    ?>
                    </a>
                </div>
                <?php 
                    endforeach;
                ?>
                </div>
            </div>
        </form>
    </header>

    <footer class="footer">
        <p>📞 Téléphone : 07 67 01 02 03</p>
        <p>✉ Email : imposturecontact@gmail.com</p>
        <p>Horaires : Lundi - Vendredi 10h-21h | Samedi - Dimanche 12h-18h</p>
    </footer>

</body>

</html>