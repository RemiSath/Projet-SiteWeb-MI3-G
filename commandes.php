<?php
if(!isset($_SESSION["role"]) || $_SESSION["role"] != "restaurateur"){
    header("Location: connexion.html");
    exit();
}
$lignes = file("data/commandes.txt");
$commandes = array();
foreach($lignes as $ligne){
    $ligne = trim($ligne);
    $infos = explode("|", $ligne);
    if(count($infos) >= 5){
        $commande = array(
            "id" => $infos[0],
            "client" => $infos[1],
            "date" => $infos[2],
            "statut" => $infos[3],
            "plats" => $infos[4]
        );
        array_push($commandes, $commande);
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Commandes</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="Images/Among_Us.png">
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
                    <a href="reserver.html">Réserver une table</a>
                    <a href="commander.html">Commander</a>
                </div>
            </div>
            <div class="menu">
                <a href="Notation.html">Notation</a>
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
                <a href="Admin.html">Admin</a>
            </div>
            <input type="text" id="searchInput2" placeholder="Rechercher nos produits ..." autocomplete="off">
        </div>
    </header>

    <div class="sitedescription3">
        <h1>Page des Commandes</h1>
    </div>
<main class="container">
    <div class="filters">
        <button class="filter-btn2">Toutes</button>
        <button class="filter-btn2">À préparer</button>
        <button class="filter-btn2">En livraison</button>
        <button class="filter-btn2">Livrée</button>
    </div>
    <p>Nombre de commandes : <?php echo count($commandes); ?></p>
    <div class="grid2">
        <?php foreach($commandes as $commande){ ?>
            <div class="order-card status-a_preparer-<?php echo $commande["statut"]; ?>">
                <div class="meta">
                <div><?php echo $commande["id"]; ?></div>
                <div><?php echo $commande["client"]; ?></div>
                <div><?php echo $commande["date"]; ?></div>
            </div>
            <div class="details">
                <div class="items">
                <?php $plats = explode(";",$commande["plats"]);
                foreach($plats as $p){
                    $detail = explode(",",$p);
                    ?>
                        <div class="item-row">
                            <span><?php echo $detail[0]; ?></span>
                            <span>x<?php echo $detail[1]; ?></span>
                        </div>
                    <?php } ?>
                    </div>
                    <div class="status-badge">
                        <?php
                        if($commande["statut"] == "a_preparer"){
                            echo "A préparer";
                        }
                        elseif($commande["statut"] == "en_livraison"){
                            echo "En livraison";
                        }
                        else{
                            echo "Livrée";
                        }
                        ?>
                    </div>
                    <div class="actions">
                    <?php if(commandes["statut"]== "a_preparer"){ ?>
                        <button> Mettre en livraison </button>
                    <?php } ?>
                    <?php if(commandes["statut"]== "en_livraison"){ ?>
                        <button> Marquer livrée </button>
                    <?php } ?>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</main>
<footer class="footer">
    <p>📞 Téléphone : 07 67 01 02 03</p>
    <p>✉ Email : imposturecontact@gmail.com</p>
    <p>Horaires : Lundi - Vendredi 10h-21h | Samedi - Dimanche 12h-18h</p>
</footer>
</body>
</html>
