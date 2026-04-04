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

$planification = $_POST["planification"] ?? "immediate";
$date_souhaitee = $_POST["date_souhaitee"] ?? "";
$heure_souhaitee = $_POST["heure_souhaitee"] ?? "";

$date_planifiee = null;
if ($planification === "plus_tard" && $date_souhaitee !== "" && $heure_souhaitee !== "") {
    $date_planifiee = $date_souhaitee . " " . $heure_souhaitee;
}

$mode_commande = "livraison";
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

$statut = ($planification === "plus_tard") ? "en_attente" : "a_preparer";

$commande = [
    "id" => $id,
    "client" => $nom,
    "date" => date("Y-m-d H:i:s"),
    "mode_commande" => $mode_commande,
    "planification" => $planification,
    "date_souhaitee" => $date_planifiee,
    "paiement" => "en_attente",
    "statut" => $statut,
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

header("Location: profil.php");
exit();
?>
