<?php
session_start();

$panier = $_SESSION["panier"] ?? [];

if (empty($panier)) {
    header("Location: commander.php");
    exit();
}

$nomComplet = trim($_POST["name"] ?? "Anonyme");
$adresse = trim($_POST["address"] ?? "");
$code_postal = trim($_POST["postal_code"] ?? "");
$ville = trim($_POST["city"] ?? "");
$telephone = trim($_POST["phone"] ?? "");
$email = strtolower(trim($_POST["email"] ?? $_SESSION["email"] ?? ""));
$motdepasse = $_POST["motdepasse"] ?? "";

$interphone = trim($_POST["interphone"] ?? "");
$etage = trim($_POST["floor"] ?? "");
$commentaires = trim($_POST["comments"] ?? "");

$planification = $_POST["planification"] ?? "immediate";
$date_souhaitee = trim($_POST["date_souhaitee"] ?? "");
$heure_souhaitee = trim($_POST["heure_souhaitee"] ?? "");

$date_planifiee = null;
if ($planification === "plus_tard" && $date_souhaitee !== "" && $heure_souhaitee !== "") {
    $date_planifiee = $date_souhaitee . " " . $heure_souhaitee;
}

$adresse_complete = $adresse . ", " . $code_postal . " " . $ville;

$dirData = __DIR__ . "/data";
$fichierComptes = $dirData . "/compte.json";
$fichierCommandes = $dirData . "/commandes.json";

if (empty($_SESSION["email"])) {

    $email = strtolower(trim($_POST["email"] ?? ""));
    $motdepasse = $_POST["motdepasse"] ?? "";

    if ($email === "" || $motdepasse === "") {
        header("Location: commander.php?erreur=champs_compte");
        exit();
    }

    if (!is_dir($dirData)) {
        mkdir($dirData, 0777, true);
    }

    $comptes = file_exists($fichierComptes) 
        ? json_decode(file_get_contents($fichierComptes), true) 
        : [];

    if (!is_array($comptes)) $comptes = [];

    foreach ($comptes as $compte) {
        if (strtolower($compte["email"]) === $email) {
            header("Location: commander.php?erreur=email_existe");
            exit();
        }
    }

    $partiesNom = preg_split('/\s+/', trim($_POST["name"]), 2);
    $prenom = $partiesNom[0] ?? "";
    $nom = $partiesNom[1] ?? "";

    $nouveauCompte = [
        "id" => uniqid(),
        "nom" => $nom,
        "prenom" => $prenom,
        "email" => $email,
        "motdepasse" => password_hash($motdepasse, PASSWORD_DEFAULT),
        "adresse" => $adresse_complete,
        "telephone" => $telephone,
        "infos" => "",
        "statut" => "Client",
        "bloque" => false
    ];

    $comptes[] = $nouveauCompte;

    file_put_contents(
        $fichierComptes,
        json_encode($comptes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
        LOCK_EX
    );

    $_SESSION["email"] = $email;
    $_SESSION["nom"] = $nom;
    $_SESSION["prenom"] = $prenom;
    $_SESSION["telephone"] = $telephone;
    $_SESSION["adresse"] = $adresse_complete;
    $_SESSION["infos"] = "";
    $_SESSION["statut"] = "Client";
}

$commandes = file_exists($fichierCommandes) ? json_decode(file_get_contents($fichierCommandes), true) : [];
if (!is_array($commandes)) $commandes = [];

$id = !empty($commandes) ? max(array_column($commandes, "id")) + 1 : 1;

$statut = ($planification === "plus_tard") ? "en_attente" : "a_preparer";

$commande = [
    "id" => $id,
    "client" => $nomComplet,
    "date" => $date_planifiee ?? date("Y-m-d"),
    "mode_commande" => "livraison",
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

file_put_contents($fichierCommandes, json_encode($commandes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);

unset($_SESSION["panier"]);

header("Location: profil.php");
exit();
?>