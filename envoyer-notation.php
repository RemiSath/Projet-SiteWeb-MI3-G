<?php
session_start();

if(!isset($_SESSION["email"])){
    $_SESSION["erreur2"] = "Connectez-vous pour noter une commande.";
    header("Location: connexion.php");
    exit();
}

$fichierNotations = __DIR__ . "/data/notations.json";
$fichierCommandes = __DIR__ . "/data/commandes.json";

if(!is_dir(__DIR__ . "/data")){
    mkdir(__DIR__ . "/data", 0777, true);
}

$commandeId = $_POST["commande_id"] ?? "";
$livraison = isset($_POST["livraison"]) ? intval($_POST["livraison"]) : null;
$qualite = isset($_POST["qualite"]) ? intval($_POST["qualite"]) : null;
$commentaires = trim($_POST["commentaires"] ?? "");
$emailClient = strtolower(trim($_SESSION["email"]));

if($commandeId === "" || $livraison === null || $qualite === null){
    $_SESSION["message"] = "Formulaire incomplet.";
    header("Location: Notation.php");
    exit();
}

if($livraison < 0 || $livraison > 5 || $qualite < 0 || $qualite > 5){
    $_SESSION["message"] = "Les notes doivent être comprises entre 0 et 5.";
    header("Location: Notation.php");
    exit();
}

$commandes = file_exists($fichierCommandes)
    ? json_decode(file_get_contents($fichierCommandes), true)
    : [];

if(!is_array($commandes)){
    $commandes = [];
}

$commandeTrouvee = false;
$notationAutorisee = false;

foreach ($commandes as &$commande){
    if((string)($commande["id"] ?? "") === (string)$commandeId){
        $commandeTrouvee = true;

        $emailCommande = strtolower(trim($commande["email"] ?? ""));
        $statutCommande = $commande["statut"] ?? "";
        $dejaNotee = !empty($commande["note_donnee"]);

        if(
            $emailCommande === $emailClient &&
            $statutCommande === "livree" &&
            !$dejaNotee
        ){
            $notationAutorisee = true;
            $commande["note_donnee"] = true;
            $commande["date_notation"] = date("Y-m-d H:i:s");
        }

        break;
    }
}

unset($commande);

if(!$commandeTrouvee){
    $_SESSION["message"] = "Commande introuvable.";
    header("Location: Notation.php");
    exit();
}

if(!$notationAutorisee){
    $_SESSION["message"] = "Vous ne pouvez noter que vos commandes livrées non encore notées.";
    header("Location: Notation.php");
    exit();
}

$notations = file_exists($fichierNotations)
    ? json_decode(file_get_contents($fichierNotations), true)
    : [];

if(!is_array($notations)){
    $notations = [];
}

$notations[] = [
    "id" => uniqid(),
    "commande_id" => $commandeId,
    "email" => $emailClient,
    "livraison" => $livraison,
    "qualite" => $qualite,
    "commentaires" => $commentaires,
    "date" => date("Y-m-d H:i:s")
];

file_put_contents(
    $fichierNotations,
    json_encode($notations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
    LOCK_EX
);

file_put_contents(
    $fichierCommandes,
    json_encode($commandes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
    LOCK_EX
);

$_SESSION["message"] = "Merci pour votre avis !";

header("Location: Notation.php");
exit();
?>
