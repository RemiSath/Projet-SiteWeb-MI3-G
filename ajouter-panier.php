<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: page-d'accueil.php");
    exit();
}

if(!isset($_SESSION["panier"])){
    $_SESSION["panier"] = [];
}

$nom = trim($_POST["nom"] ?? "");
$prix = 0;

$fichierProduits = __DIR__ . "/data/produits.json";
$produits = file_exists($fichierProduits)
    ? json_decode(file_get_contents($fichierProduits), true)
    : [];

if(!is_array($produits)){
    $produits = [];
}

foreach ($produits as $produit){
    if(($produit["nom"] ?? "") === $nom){
        $prix = floatval($produit["prix"] ?? 0);
        break;
    }
}

if($nom !== "" && $prix > 0){
    if(isset($_SESSION["panier"][$nom])){
        $_SESSION["panier"][$nom]["quantite"]++;
    } else {
        $_SESSION["panier"][$nom] = [
            "nom" => $nom,
            "prix" => $prix,
            "quantite" => 1
        ];
    }
}

$total = 0;

foreach ($_SESSION["panier"] as $item){
    $total += $item["quantite"];
}

if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && $_SERVER["HTTP_X_REQUESTED_WITH"] === "XMLHttpRequest"){
    header("Content-Type: application/json");
    echo json_encode([
        "success" => $prix > 0,
        "total" => $total
    ]);
    exit();
}

header("Location: Paris.php");
exit();
?>
