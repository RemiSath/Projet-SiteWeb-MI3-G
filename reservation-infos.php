<?php
session_start();

$fichier = __DIR__ . "/data/reservation.json";

if(!is_dir(__DIR__ . "/data")){
    mkdir(__DIR__ . "/data", 0777, true);
}

$reservations = [];

if(file_exists($fichier)){
    $json = file_get_contents($fichier);
    $reservations = json_decode($json, true);

    if(!is_array($reservations)){
        $reservations = [];
    }
}

$nom = trim($_POST["nom"] ?? "");
$prenom = trim($_POST["prenom"] ?? "");
$email = strtolower(trim($_SESSION["email"] ?? $_POST["email"] ?? ""));
$adultes = intval($_POST["adultes"] ?? 1);
$enfants = intval($_POST["enfants"] ?? 0);
$date = $_POST["date"] ?? "";
$time = $_POST["time"] ?? "";
$restaurant = trim($_POST["restaurant"] ?? "");
$commentaire = trim($_POST["commentaire"] ?? "");

$restaurantsAutorises = ["Paris", "Cergy", "Argenteuil"];

if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
    $_SESSION["erreur"] = "Email invalide.";
    header("Location: reserver.php");
    exit();
}

if(!in_array($restaurant, $restaurantsAutorises, true)){
    $_SESSION["erreur"] = "Restaurant invalide.";
    header("Location: reserver.php");
    exit();
}

if($adultes > 20 || $enfants > 20){
    $_SESSION["erreur"] = "Nombre de personnes trop élevé.";
    header("Location: reserver.php");
    exit();
}

if(strlen($commentaire) > 200){
    $_SESSION["erreur"] = "Commentaire trop long.";
    header("Location: reserver.php");
    exit();
}

if($nom === "" || $prenom === "" || $email === "" || $date === "" || $time === "" || $restaurant === ""){
    $_SESSION["erreur"] = "Veuillez remplir tous les champs obligatoires.";
    header("Location: reserver.php");
    exit();
}

$dateReservation = strtotime($date . " " . $time);

if($dateReservation <= time()){
    $_SESSION["erreur"] = "La date de réservation doit être dans le futur.";
    header("Location: reserver.php");
    exit();
}

if($adultes < 1){
    $_SESSION["erreur"] = "Il faut au moins un adulte pour réserver.";
    header("Location: reserver.php");
    exit();
}

if($enfants < 0){
    $_SESSION["erreur"] = "Le nombre d'enfants ne peut pas être négatif.";
    header("Location: reserver.php");
    exit();
}

$reservations[] = [
    "id" => uniqid(),
    "email" => $email,
    "nom" => $nom,
    "prenom" => $prenom,
    "adultes" => $adultes,
    "enfants" => $enfants,
    "date" => $date,
    "time" => $time,
    "restaurant" => $restaurant,
    "commentaire" => $commentaire,
    "date_creation" => date("Y-m-d H:i:s")
];

file_put_contents(
    $fichier,
    json_encode($reservations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
    LOCK_EX
);

$_SESSION["nom"] = $nom;
$_SESSION["prenom"] = $prenom;
$_SESSION["message2"] = "Votre réservation a été enregistrée avec succès.";

if(isset($_SESSION["email"])){
    header("Location: mes-reservations.php");
} else {
    header("Location: page-d'accueil.php");
}

exit();
?>
