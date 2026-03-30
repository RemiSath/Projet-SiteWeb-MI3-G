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
</head>
<body>
<h1>Page des Commandes</h1>
<p>Nombre de commandes : <?php echo count($commandes); ?></p>
<div class="grid2">
<?php foreach($commandes as $commande){ ?>
    <div class="order-card status-<?php echo $commande["statut"]; ?>">
        <div class="meta">
            <div>#<?php echo $commande["id"]; ?></div>
            <div><?php echo $commande["client"]; ?></div>
            <div><?php echo $commande["date"]; ?></div>
        </div>
        <div class="details">
            <div class="items">
            <?php
            $plats = explode(";", $commande["plats"]);

            foreach($plats as $p){
                $detail = explode(",", $p);
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
                echo "À préparer";
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
            <?php if($commande["statut"] == "a_preparer"){ ?>
                <button>Mettre en livraison</button>
            <?php } ?>

            <?php if($commande["statut"] == "en_livraison"){ ?>
                <button>Marquer livrée</button>
            <?php } ?>
            </div>
        </div>
    </div>
<?php } ?>
</div>
</body>
</html>