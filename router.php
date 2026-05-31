<?php
$uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$chemin = realpath(__DIR__ . $uri);
$dossierData = realpath(__DIR__ . "/data");

if($dossierData !== false && $chemin !== false && str_starts_with($chemin, $dossierData)){
    http_response_code(403);
    echo "Acces interdit.";
    exit();
}

if($chemin !== false && is_file($chemin)){
    return false;
}

require __DIR__ . "/page-d'accueil.php";
?>
