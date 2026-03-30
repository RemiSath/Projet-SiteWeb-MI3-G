<?php
session_start();

// Initialiser le panier
if (!isset($_SESSION["panier"])) {
    $_SESSION["panier"] = [];
}

// Compter les articles
$nbArticles = 0;
foreach ($_SESSION["panier"] as $item) {
    $nbArticles += $item["quantite"];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Paris</title>
<link rel="stylesheet" href="styles.css">
<link rel="icon" href="Images/Among_Us.png">
</head>

<body>

<header class="navbar">
    <div class="left-group">
        <div class="burger">☰</div>
        <a href="page-d'accueil.html" class="accueil">IMPOSTEUR</a>
    </div>

    <div class="navliens">
        <a href="commander.php" class="cart">
            🛒 <span class="cart_count"><?php echo $nbArticles; ?></span>
        </a>
    </div>
</header>

<div class="sitedescriptionville">
    <h2>Nos Trompe-l’œil Fruités</h2>
    <p>Des desserts qui ressemblent à de vrais fruits.</p>
</div>

<section class="products">
<div class="product-grid">

<!-- CITRON -->
<div class="product-card">
    <img src="Images/citron.png">
    <h3>Citron</h3>
    <p>6,50 €</p>

    <form method="post" action="ajouter_panier.php">
        <input type="hidden" name="nom" value="Citron">
        <input type="hidden" name="prix" value="6.50">
        <button type="submit">Ajouter au panier</button>
    </form>
</div>

<!-- POMME -->
<div class="product-card">
    <img src="Images/pomme.png">
    <h3>Pomme</h3>
    <p>7,00 €</p>

    <form method="post" action="ajouter_panier.php">
        <input type="hidden" name="nom" value="Pomme">
        <input type="hidden" name="prix" value="7.00">
        <button type="submit">Ajouter au panier</button>
    </form>
</div>

<!-- POIRE -->
<div class="product-card">
    <img src="Images/poire.png">
    <h3>Poire</h3>
    <p>7,20 €</p>

    <form method="post" action="ajouter_panier.php">
        <input type="hidden" name="nom" value="Poire">
        <input type="hidden" name="prix" value="7.20">
        <button type="submit">Ajouter au panier</button>
    </form>
</div>

<!-- MANGUE -->
<div class="product-card">
    <img src="Images/mangue.png">
    <h3>Mangue</h3>
    <p>7,50 €</p>

    <form method="post" action="ajouter_panier.php">
        <input type="hidden" name="nom" value="Mangue">
        <input type="hidden" name="prix" value="7.50">
        <button type="submit">Ajouter au panier</button>
    </form>
</div>

<!-- MANDARINE -->
<div class="product-card">
    <img src="Images/mandarine.jpg">
    <h3>Mandarine</h3>
    <p>6,90 €</p>

    <form method="post" action="ajouter_panier.php">
        <input type="hidden" name="nom" value="Mandarine">
        <input type="hidden" name="prix" value="6.90">
        <button type="submit">Ajouter au panier</button>
    </form>
</div>

<!-- FRAISE -->
<div class="product-card">
    <img src="Images/fraise.png">
    <h3>Fraise</h3>
    <p>6,80 €</p>

    <form method="post" action="ajouter_panier.php">
        <input type="hidden" name="nom" value="Fraise">
        <input type="hidden" name="prix" value="6.80">
        <button type="submit">Ajouter au panier</button>
    </form>
</div>

</div>
</section>

</body>
</html>