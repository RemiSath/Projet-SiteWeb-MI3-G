<?php
session_start();

if (!isset($_SESSION["panier"])) {
    $_SESSION["panier"] = [];
}

$nom = trim($_POST["nom"] ?? "");
$prix = floatval($_POST["prix"] ?? 0);

if ($nom !== "") {
    if (isset($_SESSION["panier"][$nom])) {
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

foreach ($_SESSION["panier"] as $item) {
    $total += $item["quantite"];
}

if (
    isset($_SERVER["HTTP_X_REQUESTED_WITH"]) &&
    $_SERVER["HTTP_X_REQUESTED_WITH"] === "XMLHttpRequest"
) {
    header("Content-Type: application/json");
    echo json_encode([
        "success" => true,
        "total" => $total
    ]);
    exit();
}

header("Location: Paris.php");
exit();
?>
