<?php
header("Content-Type: application/json; charset=utf-8");

$fichier = __DIR__ . "/data/produits.json";

if(!file_exists($fichier)){
    echo json_encode([]);
    exit();
}

$produits = json_decode(file_get_contents($fichier), true);

if(!is_array($produits)){
    echo json_encode([]);
    exit();
}

$menu = $_GET["menu"] ?? "tous";
$categorie = $_GET["categorie"] ?? "tous";
$type = $_GET["type"] ?? "tous";
$saveur = $_GET["saveur"] ?? "tous";
$allergene = $_GET["allergene"] ?? "tous";
$regime = $_GET["regime"] ?? "tous";
$gout = $_GET["gout"] ?? "tous";
$recherche = strtolower(trim($_GET["recherche"] ?? ""));

$resultats = [];

foreach ($produits as $produit){
    $afficher = true;

    if($menu !== "tous" && ($produit["menu"] ?? "") !== $menu){
        $afficher = false;
    }

    if($categorie !== "tous" && ($produit["categorie"] ?? "") !== $categorie){
        $afficher = false;
    }

    if($type !== "tous" && ($produit["type"] ?? "") !== $type){
        $afficher = false;
    }

    if($saveur !== "tous" && ($produit["saveur"] ?? "") !== $saveur){
        $afficher = false;
    }

    if($gout !== "tous" && ($produit["gout"] ?? "") !== $gout){
        $afficher = false;
    }

    $allergenesProduit = $produit["allergenes"] ?? [];
    $regimesProduit = $produit["regimes"] ?? [];

    if($allergene !== "tous" && in_array($allergene, $allergenesProduit, true)){
        $afficher = false;
    }

    if($regime !== "tous"){
        if($regime === "sans-gluten" && in_array("gluten", $allergenesProduit, true)){
            $afficher = false;
        } 
        elseif($regime === "sans-lactose" && in_array("lactose", $allergenesProduit, true)){
            $afficher = false;
        } 
        elseif($regime === "sans-oeufs" && in_array("oeufs", $allergenesProduit, true)){
            $afficher = false;
        } 
        elseif(!in_array($regime, ["sans-gluten", "sans-lactose", "sans-oeufs"], true) && !in_array($regime, $regimesProduit, true)){
            $afficher = false;
        }
    }

    if($recherche !== ""){
        $nom = strtolower($produit["nom"] ?? "");

        if(!str_contains($nom, $recherche)){
            $afficher = false;
        }
    }

    if($afficher){
        $resultats[] = $produit;
    }
}

echo json_encode($resultats, JSON_UNESCAPED_UNICODE);
exit();
?>