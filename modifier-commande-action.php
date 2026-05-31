<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: profil.php");
    exit();
}

if(!isset($_SESSION["email"])){
    header("Location: connexion.php");
    exit();
}

function totalPlats($plats){
    $total = 0;
    foreach ($plats as $plat){
        $total += $plat["prix"] * $plat["quantite"];
    }
    return $total;
}

function commandeModifiable($statut){
    return in_array($statut, ["a_preparer", "payee", "en_attente"], true);
}

function prixProduit($nom){
    $fichierProduits = __DIR__ . "/data/produits.json";
    $produits = file_exists($fichierProduits)
        ? json_decode(file_get_contents($fichierProduits), true)
        : [];

    if(!is_array($produits)){
        return 0;
    }

    foreach ($produits as $produit){
        if(($produit["nom"] ?? "") === $nom){
            return floatval($produit["prix"] ?? 0);
        }
    }

    return 0;
}

$fichierCommandes = __DIR__ . "/data/commandes.json";
$commandes = file_exists($fichierCommandes)
    ? json_decode(file_get_contents($fichierCommandes), true)
    : [];

if(!is_array($commandes)){
    $commandes = [];
}

$id = intval($_POST["commande_id"] ?? 0);
$action = $_POST["action"] ?? "";
$nom = trim($_POST["nom"] ?? "");
$prix = prixProduit($nom);
$emailSession = strtolower(trim($_SESSION["email"]));

if (!in_array($action, ["ajouter", "retirer"], true)) {
    $_SESSION["erreur"] = "Action invalide.";
    header("Location: profil.php");
    exit();
}

foreach ($commandes as &$commande){
    if(intval($commande["id"] ?? 0) === $id && strtolower(trim($commande["email"] ?? "")) === $emailSession){
        $statut = $commande["statut"] ?? "a_preparer";
        if(!commandeModifiable($statut)){
            $_SESSION["erreur"] = "Cette commande est déjà en préparation, elle ne peut plus être modifiée.";
            header("Location: profil.php");
            exit();
        }
        $plats = $commande["plats"] ?? [];
        $ancienTotal = $commande["total_actuel"] ?? totalPlats($plats);
        if($action === "ajouter" && $nom !== "" && $prix > 0){
            $trouve = false;
            foreach ($plats as &$plat){
                if($plat["nom"] === $nom){
                    $plat["quantite"]++;
                    $trouve = true;
                    break;
                }
            }
            unset($plat);
            if(!$trouve){
                $plats[] = [
                    "nom" => $nom,
                    "prix" => $prix,
                    "quantite" => 1
                ];
            }
        }
        if($action === "retirer" && $nom !== ""){
            foreach ($plats as $index => &$plat){
                if($plat["nom"] === $nom){
                    $plat["quantite"]--;

                    if($plat["quantite"] <= 0){
                        unset($plats[$index]);
                    }
                    break;
                }
            }
            unset($plat);
            $plats = array_values($plats);
        }
        if(empty($plats)){
            $_SESSION["erreur"] = "Une commande ne peut pas être vide.";
            header("Location: modifier-commande.php?id=" . $id);
            exit();
        }
        $nouveauTotal = totalPlats($plats);
        if($nouveauTotal > $ancienTotal){
            $commande["modification_en_attente"] = [
                "plats" => $plats,
                "ancien_total" => $ancienTotal,
                "nouveau_total" => $nouveauTotal,
                "difference" => $nouveauTotal - $ancienTotal,
                "date" => date("Y-m-d H:i:s")
            ];
            file_put_contents(
                $fichierCommandes,
                json_encode($commandes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
                LOCK_EX
            );
            header("Location: payer-difference.php?id=" . $id);
            exit();
        }
        $commande["plats"] = $plats;
        $commande["total_actuel"] = $nouveauTotal;
        $commande["reste_a_payer"] = 0;
        if($nouveauTotal < $ancienTotal){
            $commande["ticket_reduction"] = ($commande["ticket_reduction"] ?? 0) + ($ancienTotal - $nouveauTotal);
        }
        file_put_contents(
            $fichierCommandes,
            json_encode($commandes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
            LOCK_EX
        );
        $_SESSION["message"] = "Commande modifiée.";
        header("Location: modifier-commande.php?id=" . $id);
        exit();
    }
}
unset($commande);

$_SESSION["erreur"] = "Commande introuvable.";
header("Location: profil.php");
exit();
?>
