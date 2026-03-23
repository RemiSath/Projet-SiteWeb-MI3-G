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
