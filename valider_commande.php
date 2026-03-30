<?php
session_start();

$panier = $_SESSION["panier"] ?? [];

if (empty($panier)) {
    header("Location: commander.php");
    exit();
}

$nom = trim($_POST["name"] ?? "Anonyme");
$adresse = trim($_POST["address"] ?? "");
$code_postal = trim($_POST["postal_code"] ?? "");
$ville = trim($_POST["city"] ?? "");
$telephone = trim($_POST["phone"] ?? "");
$email = trim($_POST["email"] ?? "");
$interphone = trim($_POST["interphone"] ?? "");
$etage = trim($_POST["floor"] ?? "");
$commentaires = trim($_POST["comments"] ?? "");

$adresse_complete = $adresse . ", " . $code_postal . " " . $ville;

$fichier = "data/commandes.json";

if (file_exists($fichier)) {
    $contenu = file_get_contents($fichier);
    $commandes = json_decode($contenu, true);
    if (!is_array($commandes)) {
        $commandes = [];
    }
} else {
    $commandes = [];
}

$id = 1;
if (!empty($commandes)) {
    $ids = array_column($commandes, "id");
    $id = max($ids) + 1;
}

$commande = [
    "id" => $id,
    "client" => $nom,
    "date" => date("Y-m-d H:i:s"),
    "statut" => "a_preparer",

    "adresse" => $adresse_complete,
    "telephone" => $telephone,
    "email" => $email,
    "interphone" => $interphone,
    "etage" => $etage,
    "commentaires" => $commentaires,

    "plats" => array_values($panier)
];

$commandes[] = $commande;

file_put_contents(
    $fichier,
    json_encode($commandes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
    LOCK_EX
);

unset($_SESSION["panier"]);

header("Location: commandes.php?success=1");
exit();
?>