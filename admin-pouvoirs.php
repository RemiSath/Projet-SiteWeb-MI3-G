<?php
session_start();
include "bibliothèques/bloquer.php";

if (!isset($_SESSION["statut"]) || $_SESSION["statut"] !== "Admin") {
    header("Location: connexion.php");
    exit();
}

$fichierComptes = __DIR__ . "/data/compte.json";
$fichierCommandes = __DIR__ . "/data/commandes.json";
if (!is_dir(__DIR__ . "/data")) {
    mkdir(__DIR__ . "/data", 0777, true);
}

$utilisateurs = file_exists($fichierComptes)
    ? json_decode(file_get_contents($fichierComptes), true)
    : [];
if (!is_array($utilisateurs)) {
    $utilisateurs = [];
}

$commandes = file_exists($fichierCommandes)
    ? json_decode(file_get_contents($fichierCommandes), true)
    : [];
if (!is_array($commandes)) {
    $commandes = [];
}

if (!isset($_GET["id"])) {
    die("Utilisateur introuvable");
}

$id = $_GET["id"];
$utilisateur = null;
foreach ($utilisateurs as $u) {
    if (($u["id"] ?? "") === $id) {
        $utilisateur = $u;
        break;
    }
}

if (!$utilisateur) {
    die("Utilisateur introuvable");
}

$emailUtilisateur = strtolower(trim($utilisateur["email"] ?? ""));
$totalBons = 0;

foreach ($commandes as $commande) {
    if (strtolower(trim($commande["email"] ?? "")) === $emailUtilisateur) {
        $ticket = floatval($commande["ticket_reduction"] ?? 0);
        $utilise = floatval($commande["ticket_reduction_utilise"] ?? 0);
        $totalBons += max(0, $ticket - $utilise);
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion utilisateur</title>
    <link rel="icon" href="Images/Among_Us.png">
    <link rel="stylesheet" href="styles.css">
    <link id="theme-link" rel="stylesheet" href="css/default.css">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
</head>

<body>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playwrite+AT:ital,wght@0,100..400;1,100..400&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Annie+Use+Your+Telescope&display=swap" rel="stylesheet">

<header class="navbar">
    <a href="page-d'accueil.php" class="accueil">IMPOSTEUR</a>
    <div class="navliens">
        <div class="menu">
            <a>Réservation</a>
            <div class="infos">
                <a href="reserver.php">Réserver une table</a>
                <a href="mes-reservations.php">Mes réservations</a>
                <a href="commander.php">Commander</a>
            </div>
        </div>
        <div class="menu">
            <a href="Notation.php">Notation</a>
        </div>
        <div class="menu">
            <a>Compte</a>
            <div class="infos">
                <a href="profil.php">Voir Profil</a>
                <a href="connexion.php">Connexion</a>
                <a href="inscription.php">Inscription</a>
                <a href="deconnexion.php">Deconnexion</a>
            </div>
        </div>
        <div class="menu">
            <a>Services</a>
            <div class="infos">
                <a href="commandes.php">Commandes</a>
                <a href="livraison.php">Livraison</a>
            </div>
        </div>
        <div class="menu">
            <a href="Admin.php">Admin</a>
        </div>
        <button id="theme-button">Changer le thème</button>
    </div>
</header>

<div class="profile-container">
    <div class="header-profil">
        <h1>
            Gestion de
            <?php echo htmlspecialchars($utilisateur["nom"] ?? ""); ?>
            <?php echo htmlspecialchars($utilisateur["prenom"] ?? ""); ?>
        </h1>
    </div>

    <?php if (isset($_SESSION["message_admin"])) { ?>
        <p class="message">
            <?php echo htmlspecialchars($_SESSION["message_admin"]); ?>
        </p>
        <?php unset($_SESSION["message_admin"]); ?>
    <?php } ?>

    <?php if (isset($_SESSION["erreur_admin"])) { ?>
        <p class="erreur">
            <?php echo htmlspecialchars($_SESSION["erreur_admin"]); ?>
        </p>
        <?php unset($_SESSION["erreur_admin"]); ?>
    <?php } ?>

    <div class="dashboard">
        <div class="carte2">
            <div class="infos-user">
                <h2>Ses Informations</h2>

                <div class="info-ligne2">
                    <span class="info-label">Statut Client</span>
                    <div class="info-valeur2" id="statut-client">
                        <?php echo htmlspecialchars($utilisateur["statut"] ?? ""); ?>
                    </div>
                </div>
                <div class="info-ligne2">
                    <span class="info-label">Compte</span>
                    <div class="info-valeur2" id="statut-compte">
                        <?php echo !empty($utilisateur["bloque"]) ? "Bloqué" : "Actif"; ?>
                    </div>
                </div>
                <div class="info-ligne2">
                    <span class="info-label">Nom</span>
                    <div class="info-valeur2">
                        <?php echo htmlspecialchars($utilisateur["nom"] ?? ""); ?>
                    </div>
                </div>
                <div class="info-ligne2">
                    <span class="info-label">Prénom</span>
                    <div class="info-valeur2">
                        <?php echo htmlspecialchars($utilisateur["prenom"] ?? ""); ?>
                    </div>
                </div>
                <div class="info-ligne2">
                    <span class="info-label">E-mail</span>
                    <div class="info-valeur2">
                        <?php echo htmlspecialchars($utilisateur["email"] ?? ""); ?>
                    </div>
                </div>
                <div class="info-ligne2">
                    <span class="info-label">Téléphone</span>
                    <div class="info-valeur2">
                        <?php echo htmlspecialchars($utilisateur["telephone"] ?? ""); ?>
                    </div>
                </div>
                <div class="info-ligne2">
                    <span class="info-label">Adresse</span>
                    <div class="info-valeur2">
                        <?php echo htmlspecialchars($utilisateur["adresse"] ?? ""); ?>
                    </div>
                </div>
                <div class="info-ligne2">
                    <span class="info-label">Informations complémentaires</span>
                    <div class="info-valeur2">
                        <?php echo htmlspecialchars($utilisateur["infos"] ?? ""); ?>
                    </div>
                </div>
                <div class="info-ligne2">
                    <span class="info-label">Bons de réduction disponibles</span>
                    <div class="info-valeur2">
                        <?php echo number_format($totalBons, 2, ',', ' '); ?> €
                    </div>
                </div>
            </div>

            <div class="actions-admin">
                <h2>Actions administrateur</h2>
                <form method="post" action="admin-actions.php" id="formAdmin">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                    <button type="submit" name="action" value="bloquer" class="btn-modifier2">
                        Bloquer
                    </button>
                    <button type="submit" name="action" value="debloquer" class="btn-modifier2">
                        Débloquer
                    </button>
                    <button type="submit" name="action" value="premium" class="btn-modifier2">
                        Premium
                    </button>
                    <button type="submit" name="action" value="vip" class="btn-modifier2">
                        VIP
                    </button>
                    <button type="submit" name="action" value="client" class="btn-modifier2">
                        Client
                    </button>
                </form>
                <h2>Accorder un bon</h2>
                <form method="post" action="admin-actions.php">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                    <label>Montant du bon (€)</label>
                    <input type="number" name="montant_reduction" min="0.01" step="0.01" required>
                    <label>Commentaire</label>
                    <textarea name="commentaire_reduction" rows="3" placeholder="Ex : geste commercial"></textarea>
                    <button type="submit" name="action" value="remise" class="btn-modifier2">
                        Accorder le bon
                    </button>
                </form>
                <a href="Admin.php" class="btn-link">Retour admin</a>
            </div>
        </div>
    </div>
</div>

<footer class="footer">
    <p>📞 Téléphone : 07 67 01 02 03</p>
    <p>✉ Email : imposteurcontact@gmail.com</p>
    <p>Horaires : Lundi - Vendredi 10h-21h | Samedi - Dimanche 12h-18h</p>
</footer>

<script>
    const form = document.getElementById("formAdmin");
    const statutClient = document.getElementById("statut-client");
    const statutCompte = document.getElementById("statut-compte");
    form.addEventListener("click", actions);
    async function actions(event){
        if(event.target.tagName !== "BUTTON"){
            return;
        }
        event.preventDefault();
        const formData = new FormData();
        formData.append("action", event.target.value);
        formData.append("id", "<?php echo $id; ?>");
        formData.append("requete_fetch", "1");
        try{
            const response = await fetch("admin-actions.php", {
                method: "POST",
                body: formData
            });
            const resultat = await response.json();
            if(!resultat.success){
                alert(resultat.message);
                return;
            }
            if(resultat.bloque){
                statutCompte.innerText = "Bloqué";
            }
            else{
                statutCompte.innerText = "Actif";
            }
            if(resultat.statut){
                statutClient.innerText = resultat.statut;
            }
        }
        catch(error){
            alert("Erreur serveur");
        }
    }
</script>
<script src="cookie.js"></script>
</body>
</html>

