<?php
session_start();

if (!isset($_SESSION["panier"])) {
    $_SESSION["panier"] = [];
}

$nbArticles = 0;
foreach ($_SESSION["panier"] as $item) {
    $nbArticles += $item["quantite"];
}

/* FILTRES */
$selectedType = $_GET['type'] ?? 'tous';
$selectedSaveur = $_GET['saveur'] ?? 'tous';
$selectedAllergene = $_GET['allergene'] ?? 'tous';

function h($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function active($current, $value) {
    return $current === $value ? 'active' : '';
}

/* Renvoie vrai si le produit doit être affiché */
function produitCorrespond($nom, $type, $saveur, $allergene) {
    $nom = mb_strtolower($nom, 'UTF-8');

    $typesFruit = ['citron', 'pomme', 'poire', 'mangue', 'mandarine', 'fraise', 'noix de coco', 'pêche', 'graine de mangoustan'];
    $typesClassique = ['noisette', 'oeuf au plat', 'cacahuète'];
    $typesChocolat = ['tasses', 'pommes de pin'];

    $saveursAgrumes = ['citron', 'mandarine'];
    $saveursExotique = ['mangue', 'noix de coco', 'graine de mangoustan'];
    $saveursFruitsRouges = ['fraise'];
    $saveursNoisette = ['noisette'];
    $saveursChocolat = ['tasses', 'pommes de pin'];
    $saveursFruits = ['pomme', 'poire', 'pêche'];
    $saveursClassiques = ['oeuf au plat', 'cacahuète'];

    $allergenesGluten = ['citron', 'pomme', 'poire', 'mangue', 'mandarine', 'fraise', 'noisette', 'noix de coco', 'tasses', 'pommes de pin', 'pêche', 'graine de mangoustan', 'oeuf au plat', 'cacahuète'];
    $allergenesLactose = ['citron', 'pomme', 'poire', 'mangue', 'mandarine', 'fraise', 'noisette', 'noix de coco', 'tasses', 'pommes de pin', 'pêche', 'graine de mangoustan', 'oeuf au plat', 'cacahuète'];
    $allergenesOeufs = ['noix de coco', 'tasses', 'pommes de pin', 'pêche', 'graine de mangoustan', 'oeuf au plat', 'cacahuète'];
    $allergenesSoja = ['tasses', 'pommes de pin', 'cacahuète'];
    $allergenesArachides = ['cacahuète'];
    $allergenesFruitsACoque = ['noisette', 'cacahuète'];

    if ($type === 'fruit' && !in_array($nom, $typesFruit, true)) return false;
    if ($type === 'classique' && !in_array($nom, $typesClassique, true)) return false;
    if ($type === 'chocolat' && !in_array($nom, $typesChocolat, true)) return false;

    if ($saveur === 'agrumes' && !in_array($nom, $saveursAgrumes, true)) return false;
    if ($saveur === 'exotique' && !in_array($nom, $saveursExotique, true)) return false;
    if ($saveur === 'fruits rouges' && !in_array($nom, $saveursFruitsRouges, true)) return false;
    if ($saveur === 'noisette' && !in_array($nom, $saveursNoisette, true)) return false;
    if ($saveur === 'chocolat' && !in_array($nom, $saveursChocolat, true)) return false;
    if ($saveur === 'fruits' && !in_array($nom, $saveursFruits, true)) return false;
    if ($saveur === 'classique' && !in_array($nom, $saveursClassiques, true)) return false;

    if ($allergene === 'gluten' && in_array($nom, $allergenesGluten, true)) return false;
    if ($allergene === 'lactose' && in_array($nom, $allergenesLactose, true)) return false;
    if ($allergene === 'oeufs' && in_array($nom, $allergenesOeufs, true)) return false;
    if ($allergene === 'soja' && in_array($nom, $allergenesSoja, true)) return false;
    if ($allergene === 'arachides' && in_array($nom, $allergenesArachides, true)) return false;
    if ($allergene === 'fruits à coque' && in_array($nom, $allergenesFruitsACoque, true)) return false;

    return true;
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playwrite+AT:ital,wght@0,100..400;1,100..400&display=swap"
        rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Annie+Use+Your+Telescope&display=swap" rel="stylesheet">

    <header class="navbar">
        <div class="left-group">
            <div class="burger" id="burger">☰</div>
            <a href="page-d'accueil.php" class="accueil">IMPOSTURE</a>
        </div>
        <div class="navliens">
            <div class="menu">
                <a>Réservation</a>
                <div class="infos">
                    <a href="reserver.html">Réserver une table</a>
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
                    <a href="livraison.html">Livraison</a>
                </div>
            </div>
            <div class="menu">
                <a href="Admin.php">Admin</a>
            </div>
            <input type="text" id="searchInput2" placeholder="Rechercher nos produits ..." autocomplete="off">
            <a href="commander.php" class="cart">
                🛒 <span class="cart_count"><?php echo $nbArticles; ?></span>
            </a>
        </div>
    </header>

    <!-- MENU OVERLAY -->
    <div class="overlay-menu" id="overlay">
        <div class="close-btn" id="close">✖</div>
        <ul>
            <li><a href="Paris.php">Paris</a></li>
            <li><a href="Argenteuil.php">Argenteuil</a></li>
            <li><a href="Cergy.php">Cergy</a></li>
        </ul>
    </div>

    <!-- PRODUITS -->
    <div class="sitedescriptionville">
        <h2>Nos Trompe-l’œil Fruités</h2>
        <p>Des desserts qui ressemblent à de vrais fruits, mais qui cachent des mousses, ganaches et inserts gourmands.</p>
    </div>

    <div class="filter-bar">
        <h2>Filtrer par catégorie</h2>

        <div class="filter-group">
            <p>Types de plats :</p>
            <form method="get">
                <input type="hidden" name="saveur" value="<?php echo h($selectedSaveur); ?>">
                <input type="hidden" name="allergene" value="<?php echo h($selectedAllergene); ?>">
                <button type="submit" name="type" value="tous" class="filter-btn <?php echo active($selectedType, 'tous'); ?>">Desserts</button>
                <button type="submit" name="type" value="fruit" class="filter-btn <?php echo active($selectedType, 'fruit'); ?>">Fruits</button>
                <button type="submit" name="type" value="chocolat" class="filter-btn <?php echo active($selectedType, 'chocolat'); ?>">Chocolat</button>
                <button type="submit" name="type" value="classique" class="filter-btn <?php echo active($selectedType, 'classique'); ?>">Classiques</button>
            </form>
        </div>

        <div class="filter-group">
            <p>Saveurs :</p>
            <form method="get">
                <input type="hidden" name="type" value="<?php echo h($selectedType); ?>">
                <input type="hidden" name="allergene" value="<?php echo h($selectedAllergene); ?>">
                <button type="submit" name="saveur" value="tous" class="filter-btn <?php echo active($selectedSaveur, 'tous'); ?>">Toutes</button>
                <button type="submit" name="saveur" value="chocolat" class="filter-btn <?php echo active($selectedSaveur, 'chocolat'); ?>">Chocolat</button>
                <button type="submit" name="saveur" value="agrumes" class="filter-btn <?php echo active($selectedSaveur, 'agrumes'); ?>">Agrumes</button>
                <button type="submit" name="saveur" value="exotique" class="filter-btn <?php echo active($selectedSaveur, 'exotique'); ?>">Exotique</button>
                <button type="submit" name="saveur" value="fruits rouges" class="filter-btn <?php echo active($selectedSaveur, 'fruits rouges'); ?>">Fruits rouges</button>
                <button type="submit" name="saveur" value="noisette" class="filter-btn <?php echo active($selectedSaveur, 'noisette'); ?>">Noisette</button>
                <button type="submit" name="saveur" value="fruits" class="filter-btn <?php echo active($selectedSaveur, 'fruits'); ?>">Fruits</button>
                <button type="submit" name="saveur" value="classique" class="filter-btn <?php echo active($selectedSaveur, 'classique'); ?>">Classique</button>
            </form>
        </div>

        <div class="filter-group">
            <p>Exclure les allergènes :</p>
            <form method="get">
                <input type="hidden" name="type" value="<?php echo h($selectedType); ?>">
                <input type="hidden" name="saveur" value="<?php echo h($selectedSaveur); ?>">
                <button type="submit" name="allergene" value="tous" class="filter-btn <?php echo active($selectedAllergene, 'tous'); ?>">Tous</button>
                <button type="submit" name="allergene" value="gluten" class="filter-btn <?php echo active($selectedAllergene, 'gluten'); ?>">Gluten</button>
                <button type="submit" name="allergene" value="lactose" class="filter-btn <?php echo active($selectedAllergene, 'lactose'); ?>">Lactose</button>
                <button type="submit" name="allergene" value="oeufs" class="filter-btn <?php echo active($selectedAllergene, 'oeufs'); ?>">Œufs</button>
                <button type="submit" name="allergene" value="soja" class="filter-btn <?php echo active($selectedAllergene, 'soja'); ?>">Soja</button>
                <button type="submit" name="allergene" value="arachides" class="filter-btn <?php echo active($selectedAllergene, 'arachides'); ?>">Arachides</button>
                <button type="submit" name="allergene" value="fruits à coque" class="filter-btn <?php echo active($selectedAllergene, 'fruits à coque'); ?>">Fruits à coque</button>
            </form>
        </div>

        <a href="Paris.php" class="filter-btn">Réinitialiser</a>
    </div>

    <section class="products">
        <div class="product-grid">
            <?php if (produitCorrespond('Citron', $selectedType, $selectedSaveur, $selectedAllergene)) : ?>
            <div class="product-card dessert gluten lactose" data-category="dessert" data-allergens="gluten,lactose">
                <div class="product-image">
                    <img src="Images/citron.png" alt="Citron">
                </div>
                <h3>Citron</h3>
                <p class="description_produit">
                    Réplique d’un citron jaune, peau texturée — mousse citron & yuzu légère et acidulée à l’intérieur.
                </p>
                <p class="price">6,50 €</p>
                <p class="allergens">Allergènes : Gluten, Lactose</p>

                <form method="post" action="ajouter-panier.php">
                    <input type="hidden" name="nom" value="Citron">
                    <input type="hidden" name="prix" value="6.5">
                    <button type="submit" class="add-to-cart" aria-label="Ajouter Citron au panier">
                        Ajouter au panier
                    </button>
                </form>
            </div>
            <?php endif; ?>

            <?php if (produitCorrespond('Pomme', $selectedType, $selectedSaveur, $selectedAllergene)) : ?>
            <div class="product-card dessert gluten lactose" data-category="dessert" data-allergens="gluten,lactose">
                <div class="product-image">
                    <img src="Images/pomme.png" alt="Pomme">
                </div>
                <h3>Pomme</h3>
                <p class="description_produit">
                    À première vue une vraie pomme brillante, mais coupe-la et retrouve mousse fruitée et cœur fondant.
                </p>
                <p class="price">7,00 €</p>
                <p class="allergens">Allergènes : Gluten, Lactose</p>

                <form method="post" action="ajouter-panier.php">
                    <input type="hidden" name="nom" value="Pomme">
                    <input type="hidden" name="prix" value="7.0">
                    <button type="submit" class="add-to-cart" aria-label="Ajouter Pomme au panier">
                        Ajouter au panier
                    </button>
                </form>
            </div>
            <?php endif; ?>

            <?php if (produitCorrespond('Poire', $selectedType, $selectedSaveur, $selectedAllergene)) : ?>
            <div class="product-card dessert gluten lactose" data-category="dessert" data-allergens="gluten,lactose">
                <div class="product-image">
                    <img src="Images/poire.png" alt="Poire">
                </div>
                <h3>Poire</h3>
                <p class="description_produit">
                    Moulée comme une poire juteuse, mais c’est une mousse fine et parfumée qui se cache sous la coque.
                </p>
                <p class="price">7,20 €</p>
                <p class="allergens">Allergènes : Gluten, Lactose</p>

                <form method="post" action="ajouter-panier.php">
                    <input type="hidden" name="nom" value="Poire">
                    <input type="hidden" name="prix" value="7.2">
                    <button type="submit" class="add-to-cart" aria-label="Ajouter Poire au panier">
                        Ajouter au panier
                    </button>
                </form>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <section class="products">
        <div class="product-grid">
            <?php if (produitCorrespond('Mangue', $selectedType, $selectedSaveur, $selectedAllergene)) : ?>
            <div class="product-card dessert gluten lactose" data-category="dessert" data-allergens="gluten,lactose">
                <div class="product-image">
                    <img src="Images/mangue.png" alt="Mangue">
                </div>
                <h3>Mangue</h3>
                <p class="description_produit">
                    Belle mangue orange, texture veloutée en apparence : une mousse mangue & gelée fruitée vous attend.
                </p>
                <p class="price">7,50 €</p>
                <p class="allergens">Allergènes : Gluten, Lactose</p>

                <form method="post" action="ajouter-panier.php">
                    <input type="hidden" name="nom" value="Mangue">
                    <input type="hidden" name="prix" value="7.5">
                    <button type="submit" class="add-to-cart" aria-label="Ajouter Mangue au panier">
                        Ajouter au panier
                    </button>
                </form>
            </div>
            <?php endif; ?>

            <?php if (produitCorrespond('Mandarine', $selectedType, $selectedSaveur, $selectedAllergene)) : ?>
            <div class="product-card dessert gluten lactose" data-category="dessert" data-allergens="gluten,lactose">
                <div class="product-image">
                    <img src="Images/mandarine.jpg" alt="Mandarine">
                </div>
                <h3>Mandarine</h3>
                <p class="description_produit">
                    Petites rainures, peau brillante : ce dessert cache une ganache mandarine & confit acidulé.
                </p>
                <p class="price">6,90 €</p>
                <p class="allergens">Allergènes : Gluten, Lactose</p>

                <form method="post" action="ajouter-panier.php">
                    <input type="hidden" name="nom" value="Mandarine">
                    <input type="hidden" name="prix" value="6.9">
                    <button type="submit" class="add-to-cart" aria-label="Ajouter Mandarine au panier">
                        Ajouter au panier
                    </button>
                </form>
            </div>
            <?php endif; ?>

            <?php if (produitCorrespond('Fraise', $selectedType, $selectedSaveur, $selectedAllergene)) : ?>
            <div class="product-card dessert gluten lactose" data-category="dessert" data-allergens="gluten,lactose">
                <div class="product-image">
                    <img src="Images/fraise.png" alt="Fraise">
                </div>
                <h3>Fraise</h3>
                <p class="description_produit">
                    Rouge vif et brillante, ce dessert cache une mousse fraise & insert fruité sous une coque délicate.
                </p>
                <p class="price">6,80 €</p>
                <p class="allergens">Allergènes : Gluten, Lactose</p>

                <form method="post" action="ajouter-panier.php">
                    <input type="hidden" name="nom" value="Fraise">
                    <input type="hidden" name="prix" value="6.8">
                    <button type="submit" class="add-to-cart" aria-label="Ajouter Fraise au panier">
                        Ajouter au panier
                    </button>
                </form>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <section class="products">
        <div class="product-grid">
            <?php if (produitCorrespond('Noisette', $selectedType, $selectedSaveur, $selectedAllergene)) : ?>
            <div class="product-card dessert gluten lactose" data-category="dessert" data-allergens="gluten,lactose">
                <div class="product-image">
                    <img src="Images/noisette.avif" alt="Noisette">
                </div>
                <h3>Noisette</h3>
                <p class="description_produit">
                    Réplique de noisette au réalisme bluffant, coque finement nervurée — praliné noisette et cœur fondant à l’intérieur.
                </p>
                <p class="price">7,50 €</p>
                <p class="allergens">Allergènes : fruits à coque (noisette), lait, gluten</p>

                <form method="post" action="ajouter-panier.php">
                    <input type="hidden" name="nom" value="Noisette">
                    <input type="hidden" name="prix" value="7.5">
                    <button type="submit" class="add-to-cart" aria-label="Ajouter Noisette au panier">
                        Ajouter au panier
                    </button>
                </form>
            </div>
            <?php endif; ?>

            <?php if (produitCorrespond('Noix de coco', $selectedType, $selectedSaveur, $selectedAllergene)) : ?>
            <div class="product-card dessert gluten lactose" data-category="dessert" data-allergens="gluten,lactose">
                <div class="product-image">
                    <img src="Images/noix_de_coco.jpg" alt="Noix de coco">
                </div>
                <h3>Noix de coco</h3>
                <p class="description_produit">
                    Faux morceau de noix de coco, écorce brute et chair blanche — intérieur moelleux au lait de coco, avec un cœur jaune gourmand.
                </p>
                <p class="price">6,90 €</p>
                <p class="allergens">Allergènes : lait, œufs, gluten</p>

                <form method="post" action="ajouter-panier.php">
                    <input type="hidden" name="nom" value="Noix de coco">
                    <input type="hidden" name="prix" value="6.9">
                    <button type="submit" class="add-to-cart" aria-label="Ajouter Noix de coco au panier">
                        Ajouter au panier
                    </button>
                </form>
            </div>
            <?php endif; ?>

            <?php if (produitCorrespond('Tasses', $selectedType, $selectedSaveur, $selectedAllergene)) : ?>
            <div class="product-card dessert gluten lactose" data-category="dessert" data-allergens="gluten,lactose">
                <div class="product-image">
                    <img src="Images/tasses.webp" alt="Tasses">
                </div>
                <h3>Tasses</h3>
                <p class="description_produit">
                    À première vue une tasse blanche épurée — elle renferme une ganache chocolat intense et onctueuse.
                </p>
                <p class="price">6,80 €</p>
                <p class="allergens">Allergènes : lait, œufs, gluten, soja</p>

                <form method="post" action="ajouter-panier.php">
                    <input type="hidden" name="nom" value="Tasses">
                    <input type="hidden" name="prix" value="6.8">
                    <button type="submit" class="add-to-cart" aria-label="Ajouter Tasses au panier">
                        Ajouter au panier
                    </button>
                </form>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <section class="products">
        <div class="product-grid">
            <?php if (produitCorrespond('Pommes de pin', $selectedType, $selectedSaveur, $selectedAllergene)) : ?>
            <div class="product-card dessert gluten lactose" data-category="dessert" data-allergens="gluten,lactose">
                <div class="product-image">
                    <img src="Images/Pommes_de_pin.png" alt="Pommes de pin">
                </div>
                <h3>Pommes de pin</h3>
                <p class="description_produit">
                    Réplique de pomme de pin en chocolat, écailles finement sculptées — extérieur croquant, cœur fondant cacaoté.
                </p>
                <p class="price">7,50 €</p>
                <p class="allergens">Allergènes : lait, œufs, gluten, soja</p>

                <form method="post" action="ajouter-panier.php">
                    <input type="hidden" name="nom" value="Pommes de pin">
                    <input type="hidden" name="prix" value="7.5">
                    <button type="submit" class="add-to-cart" aria-label="Ajouter Pommes de pin au panier">
                        Ajouter au panier
                    </button>
                </form>
            </div>
            <?php endif; ?>

            <?php if (produitCorrespond('Pêche', $selectedType, $selectedSaveur, $selectedAllergene)) : ?>
            <div class="product-card dessert gluten lactose" data-category="dessert" data-allergens="gluten,lactose">
                <div class="product-image">
                    <img src="Images/peche.jpg" alt="Pêche">
                </div>
                <h3>Pêche</h3>
                <p class="description_produit">
                    Une pêche plus vraie que nature, à la peau veloutée — mousse légère à la pêche et cœur fruité acidulé.
                </p>
                <p class="price">6,90 €</p>
                <p class="allergens">Allergènes : lait, œufs, gluten</p>

                <form method="post" action="ajouter-panier.php">
                    <input type="hidden" name="nom" value="Pêche">
                    <input type="hidden" name="prix" value="6.9">
                    <button type="submit" class="add-to-cart" aria-label="Ajouter Pêche au panier">
                        Ajouter au panier
                    </button>
                </form>
            </div>
            <?php endif; ?>

            <?php if (produitCorrespond('Graine de mangoustan', $selectedType, $selectedSaveur, $selectedAllergene)) : ?>
            <div class="product-card dessert gluten lactose" data-category="dessert" data-allergens="gluten,lactose">
                <div class="product-image">
                    <img src="Images/graine_de_mangoustan.jpg" alt="Graine de mangoustan">
                </div>
                <h3>Graine de mangoustan</h3>
                <p class="description_produit">
                    Trompe-l’œil exotique à la forme délicate — cœur fruité doux et parfumé inspiré du mangoustan.
                </p>
                <p class="price">6,80 €</p>
                <p class="allergens">Allergènes : lait, œufs, gluten</p>

                <form method="post" action="ajouter-panier.php">
                    <input type="hidden" name="nom" value="Graine de mangoustan">
                    <input type="hidden" name="prix" value="6.8">
                    <button type="submit" class="add-to-cart" aria-label="Ajouter Graine de mangoustan au panier">
                        Ajouter au panier
                    </button>
                </form>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <section class="products">
        <div class="product-grid">
            <?php if (produitCorrespond('Oeuf au plat', $selectedType, $selectedSaveur, $selectedAllergene)) : ?>
            <div class="product-card dessert gluten lactose" data-category="dessert" data-allergens="gluten,lactose">
                <div class="product-image">
                    <img src="Images/oeuf_plat.jpg" alt="Oeuf au plat">
                </div>
                <h3>Oeuf au plat</h3>
                <p class="description_produit">
                    Illusion parfaite d’un œuf au plat — blanc délicat et jaune coulant, révélant un dessert fondant et surprenant.
                </p>
                <p class="price">7,50 €</p>
                <p class="allergens">Allergènes : lait, œufs, gluten</p>

                <form method="post" action="ajouter-panier.php">
                    <input type="hidden" name="nom" value="Oeuf au plat">
                    <input type="hidden" name="prix" value="7.5">
                    <button type="submit" class="add-to-cart" aria-label="Ajouter Oeuf au plat au panier">
                        Ajouter au panier
                    </button>
                </form>
            </div>
            <?php endif; ?>

            <?php if (produitCorrespond('Cacahuète', $selectedType, $selectedSaveur, $selectedAllergene)) : ?>
            <div class="product-card dessert gluten lactose" data-category="dessert" data-allergens="gluten,lactose">
                <div class="product-image">
                    <img src="Images/cacahuete.jpg" alt="Cacahuète">
                </div>
                <h3>Cacahuète</h3>
                <p class="description_produit">
                    Réplique de cacahuète en coque texturée — praliné cacahuète et cœur croustillant.
                </p>
                <p class="price">6,90 €</p>
                <p class="allergens">Allergènes : arachides, fruits à coque, lait, gluten</p>

                <form method="post" action="ajouter-panier.php">
                    <input type="hidden" name="nom" value="Cacahuète">
                    <input type="hidden" name="prix" value="6.9">
                    <button type="submit" class="add-to-cart" aria-label="Ajouter Cacahuète au panier">
                        Ajouter au panier
                    </button>
                </form>
            </div>
            <?php endif; ?>

            <?php if (produitCorrespond('Tasses', $selectedType, $selectedSaveur, $selectedAllergene)) : ?>
            <div class="product-card dessert gluten lactose" data-category="dessert" data-allergens="gluten,lactose">
                <div class="product-image">
                    <img src="Images/tasses.webp" alt="Tasses">
                </div>
                <h3>Tasses</h3>
                <p class="description_produit">
                    Rouge vif et brillante, ce dessert cache une mousse fraise & insert fruité sous une coque délicate.
                </p>
                <p class="price">6,80 €</p>
                <p class="allergens">Allergènes : Gluten, Lactose</p>

                <form method="post" action="ajouter-panier.php">
                    <input type="hidden" name="nom" value="Tasses">
                    <input type="hidden" name="prix" value="6.8">
                    <button type="submit" class="add-to-cart" aria-label="Ajouter Tasses au panier">
                        Ajouter au panier
                    </button>
                </form>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <footer class="footer">
        <p>📞 Téléphone : 07 61 41 44 23</p>
        <p>✉ Email : imposturecontact@gmail.com</p>
        <p>Horaires : Lundi - Vendredi 10h-21h | Samedi - Dimanche 12h-18h</p>
    </footer>

    <script>
    /* MENU */
    const burger = document.getElementById("burger");
    const overlay = document.getElementById("overlay");
    const closeBtn = document.getElementById("close");

    burger.addEventListener("click", () => {
        overlay.classList.add("open");
    });

    closeBtn.addEventListener("click", () => {
        overlay.classList.remove("open");
    });
    </script>

</body>
</html>
