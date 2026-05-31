<?php
session_start();
require_once __DIR__ . "/bibliothèques/logs.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: connexion.php");
    exit;
}

$fichier = __DIR__ . "/data/compte.json";

if (!is_dir(__DIR__ . "/data")) {
    mkdir(__DIR__ . "/data", 0777, true);
}

if (!file_exists($fichier)) {
    echo "inscription.php";
    exit;
}

$json = file_get_contents($fichier);
$array = json_decode($json, true) ?? [];

$email = strtolower(trim($_POST["email"] ?? ""));
$password = $_POST["password"] ?? "";

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === "") {
    enregistrerLogIncident(
        "connexion_formulaire_invalide",
        "Tentative de connexion avec un formulaire invalide.",
        $email
    );

    echo "email";
    exit;
}

$utilisateurTrouve = false;

foreach ($array as $utilisateur) {
    if (strtolower(trim($utilisateur["email"])) === strtolower($email)) {
        $utilisateurTrouve = true;

        if (!password_verify($password, $utilisateur["motdepasse"])) {
            enregistrerLogIncident(
                "mauvais_mot_de_passe",
                "Tentative de connexion avec un mauvais mot de passe.",
                $email
            );

            echo "password";
            exit;
        }

        if (($utilisateur["bloque"] ?? false) === true) {
            enregistrerLogIncident(
                "connexion_compte_bloque",
                "Tentative de connexion sur un compte bloque.",
                $email
            );

            echo "bloque";
            exit;
        }

        $_SESSION["nom"] = $utilisateur["nom"];
        $_SESSION["prenom"] = $utilisateur["prenom"];
        $_SESSION["email"] = strtolower(trim($utilisateur["email"]));
        $_SESSION["telephone"] = $utilisateur["telephone"];
        $_SESSION["adresse"] = $utilisateur["adresse"];
        $_SESSION["infos"] = $utilisateur["infos"];
        $_SESSION["statut"] = $utilisateur["statut"];
        $_SESSION["bloque"] = $utilisateur["bloque"] ?? false;

        if ($utilisateur["statut"] === "Admin") {
            echo "Admin.php";
        } elseif ($utilisateur["statut"] === "Restaurateur") {
            echo "commandes.php";
        } elseif ($utilisateur["statut"] === "Livreur") {
            echo "livraison.php";
        } else {
            echo "profil.php";
        }

        exit;
    }
}

if (!$utilisateurTrouve) {
    enregistrerLogIncident(
        "email_inconnu",
        "Tentative de connexion avec un email inconnu.",
        $email
    );

    echo "email";
}
?>