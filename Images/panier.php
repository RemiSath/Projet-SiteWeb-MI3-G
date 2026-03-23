<?php
if(!isset($_SESSION['panier'])){
    $_SESSION['panier'] = [];
}

if(isset($_GET['nom']) && isset($_GET['prix'])){
    $produit = [
        "nom" => $_GET['nom'],
        "prix" => $_GET['prix'],
        "quantite" => 1
    ];
    $_SESSION['panier'][] = $produit;
}
