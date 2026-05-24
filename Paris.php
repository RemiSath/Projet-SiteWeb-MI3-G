<?php
session_start();
include "bibliothèques/bloquer.php";

if (!isset($_SESSION["panier"])) {
    $_SESSION["panier"] = [];
}

$nbArticles = 0;
foreach ($_SESSION["panier"] as $item) {
    $nbArticles += $item["quantite"];
}

function h($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Paris</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="Images/Among_Us.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playwrite+AT:ital,wght@0,100..400;1,100..400&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Annie+Use+Your+Telescope&display=swap" rel="stylesheet">
</head>

<body>
<header class="navbar">
    <div class="left-group">
        <div class="burger" id="burger">☰</div>
        <a href="page-d'accueil.php" class="accueil">IMPOSTEUR</a>
    </div>
    <div class="navliens">
        <div class="menu">
            <a>Réservation</a>
            <div class="infos">
                <a href="reserver.php">Réserver une table</a>
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
                <a href="deconnexion.php">Déconnexion</a>
            </div>
        </div>
        <div class="menu">
            <a>Services</a>
            <div class="infos">
                <a href="commandes.php">Commandes</a>
                <a href="livraison.php">Livraison</a>
            </div>
        </div

        <div class="menu">
            <a href="Admin.php">Admin</a>
        </div>
        <input type="text" id="searchInput2" placeholder="Rechercher nos produits ..." autocomplete="off">
        <a href="commander.php" class="cart">
            🛒 <span class="cart_count"><?php echo h($nbArticles); ?></span>
        </a>
    </div>
</header>

<div class="overlay-menu" id="overlay">
    <div class="close-btn" id="close">✖</div>
    <ul>
        <li><a href="Paris.php">Paris</a></li>
        <li><a href="Argenteuil.php">Argenteuil</a></li>
        <li><a href="Cergy.php">Cergy</a></li>
    </ul>
</div>

<div class="sitedescriptionville">
    <h2>Nos Trompe-l’œil Fruités</h2>
    <p>
        Des desserts qui ressemblent à de vrais fruits,
        mais qui cachent des mousses,
        ganaches et inserts gourmands.
    </p>
</div>

<div class="filter-bar">
    <h2>Filtrer par catégorie</h2>
    <div class="filter-group">
        <p>Menus :</p>
        <button type="button" class="filter-btn active" data-menu="tous">Tous</button>
        <button type="button" class="filter-btn" data-menu="fraicheur">Menu Fraîcheur</button>
        <button type="button" class="filter-btn" data-menu="exotique">Menu Exotique</button>
        <button type="button" class="filter-btn" data-menu="chocolat">Menu Chocolat</button>
    </div>
    <div class="filter-group">
        <p>Types :</p>
        <button type="button" class="filter-btn active" data-type="tous">Tous</button>
        <button type="button" class="filter-btn" data-type="fruit">Fruits</button>
        <button type="button" class="filter-btn" data-type="chocolat">Chocolat</button>
        <button type="button" class="filter-btn" data-type="classique">Classiques</button>
    </div>
    <div class="filter-group">
        <p>Saveurs :</p>
        <button type="button" class="filter-btn active" data-saveur="tous">Toutes</button>
        <button type="button" class="filter-btn" data-saveur="agrumes">Agrumes</button>
        <button type="button" class="filter-btn" data-saveur="exotique">Exotique</button>
        <button type="button" class="filter-btn" data-saveur="fruits-rouges">Fruits rouges</button>
        <button type="button" class="filter-btn" data-saveur="chocolat">Chocolat</button>
        <button type="button" class="filter-btn" data-saveur="fruits">Fruits</button>
        <button type="button" class="filter-btn" data-saveur="classique">Classique</button>
    </div>
    <div class="filter-group">
        <p>Exclure :</p>
        <button type="button" class="filter-btn active" data-allergene="tous">Aucun</button>
        <button type="button" class="filter-btn" data-allergene="gluten">Gluten</button>
        <button type="button" class="filter-btn" data-allergene="lactose">Lactose</button>
        <button type="button" class="filter-btn" data-allergene="oeufs">Œufs</button>
        <button type="button" class="filter-btn" data-allergene="soja">Soja</button>
        <button type="button" class="filter-btn" data-allergene="arachides">Arachides</button>
        <button type="button" class="filter-btn" data-allergene="fruits-a-coque">Fruits à coque</button>
    </div>
    <button type="button" id="resetFilters" class="filter-btn">Réinitialiser</button>
</div>
<div class="menu-selection" id="menuSelection" style="display:none;"></div>
<section class="products">
    <div class="product-grid">
        <div class="product-card type-fruit saveur-agrumes gluten lactose" data-menu="fraicheur">
            <div class="product-image">
                <img src="Images/citron.png" alt="Citron">
            </div>
            <h3>Citron</h3>
            <p class="description_produit">
                Réplique d’un citron jaune, peau texturée — mousse citron & yuzu légère et acidulée à l’intérieur.
            </p>
            <p class="price">6,50 €</p>
            <p class="allergens">Allergènes : gluten, lactose</p>
            <form method="post" action="ajouter-panier.php" class="add-cart-form">
                <input type="hidden" name="nom" value="Citron">
                <input type="hidden" name="prix" value="6.5">
                <button type="submit" class="add-to-cart">Ajouter au panier</button>
            </form>
        </div>
        <div class="product-card type-fruit saveur-fruits-rouges gluten lactose" data-menu="fraicheur">
            <div class="product-image">
                <img src="Images/fraise.png" alt="Fraise">
            </div>
            <h3>Fraise</h3>
            <p class="description_produit">
                Rouge vif et brillante, ce dessert cache une mousse fraise & insert fruité sous une coque délicate.
            </p>
            <p class="price">6,80 €</p>
            <p class="allergens">Allergènes : gluten, lactose</p>
            <form method="post" action="ajouter-panier.php" class="add-cart-form">
                <input type="hidden" name="nom" value="Fraise">
                <input type="hidden" name="prix" value="6.8">
                <button type="submit" class="add-to-cart">Ajouter au panier</button>
            </form>
        </div>
        <div class="product-card type-fruit saveur-fruits-rouges gluten lactose" data-menu="fraicheur">
            <div class="product-image">
                <img src="Images/framboise.png" alt="Framboise">
            </div>
            <h3>Framboise</h3>
            <p class="description_produit">
                Trompe-l’œil en forme de framboise — une coque délicatement texturée renfermant une mousse légère à la framboise et un cœur acidulé aux notes fruitées intenses.
            </p>
            <p class="price">6,80 €</p>
            <p class="allergens">Allergènes : gluten, lactose</p>
            <form method="post" action="ajouter-panier.php" class="add-cart-form">
                <input type="hidden" name="nom" value="Framboise">
                <input type="hidden" name="prix" value="6.8">
                <button type="submit" class="add-to-cart">Ajouter au panier</button>
            </form>
        </div>
        <div class="product-card type-fruit saveur-agrumes gluten lactose" data-menu="fraicheur">
            <div class="product-image">
                <img src="Images/mandarine.jpg" alt="Mandarine">
            </div>
            <h3>Mandarine</h3>
            <p class="description_produit">
                Petites rainures, peau brillante : ce dessert cache une ganache mandarine & confit acidulé.
            </p>
            <p class="price">6,90 €</p>
            <p class="allergens">Allergènes : gluten, lactose</p>
            <form method="post" action="ajouter-panier.php" class="add-cart-form">
                <input type="hidden" name="nom" value="Mandarine">
                <input type="hidden" name="prix" value="6.9">
                <button type="submit" class="add-to-cart">Ajouter au panier</button>
            </form>
        </div>
        <div class="product-card type-fruit saveur-exotique gluten lactose" data-menu="exotique">
            <div class="product-image">
                <img src="Images/mangue.png" alt="Mangue">
            </div>
            <h3>Mangue</h3>
            <p class="description_produit">
                Belle mangue orange, texture veloutée en apparence : une mousse mangue & gelée fruitée vous attend.
            </p>
            <p class="price">7,50 €</p>
            <p class="allergens">Allergènes : gluten, lactose</p>
            <form method="post" action="ajouter-panier.php" class="add-cart-form">
                <input type="hidden" name="nom" value="Mangue">
                <input type="hidden" name="prix" value="7.5">
                <button type="submit" class="add-to-cart">Ajouter au panier</button>
            </form>
        </div>
        <div class="product-card type-fruit saveur-agrumes gluten lactose" data-menu="exotique">
            <div class="product-image">
                <img src="Images/pomme.png" alt="Pomme">
            </div>
            <h3>Pomme</h3>
            <p class="description_produit">
                À première vue une vraie pomme brillante, mais coupe-la et retrouve mousse fruitée et cœur fondant.
            </p>
            <p class="price">7,00 €</p>
            <p class="allergens">Allergènes : gluten, lactose</p>
            <form method="post" action="ajouter-panier.php" class="add-cart-form">
                <input type="hidden" name="nom" value="Pomme">
                <input type="hidden" name="prix" value="7.0">
                <button type="submit" class="add-to-cart">Ajouter au panier</button>
            </form>
        </div>
        <div class="product-card type-fruit saveur-fruits gluten lactose" data-menu="exotique">
            <div class="product-image">
                <img src="Images/poire.png" alt="Poire">
            </div>
            <h3>Poire</h3>
            <p class="description_produit">
                Moulée comme une poire juteuse, mais c’est une mousse fine et parfumée qui se cache sous la coque.
            </p>
            <p class="price">7,20 €</p>
            <p class="allergens">Allergènes : gluten, lactose</p>
            <form method="post" action="ajouter-panier.php" class="add-cart-form">
                <input type="hidden" name="nom" value="Poire">
                <input type="hidden" name="prix" value="7.2">
                <button type="submit" class="add-to-cart">Ajouter au panier</button>
            </form>
        </div>
        <div class="product-card type-classique saveur-exotique gluten lactose oeufs" data-menu="exotique">
            <div class="product-image">
                <img src="Images/noix_de_coco.jpg" alt="Noix de coco">
            </div>
            <h3>Noix de coco</h3>
            <p class="description_produit">
                Faux morceau de noix de coco, écorce brute et chair blanche — intérieur moelleux au lait de coco, avec un cœur jaune gourmand.
            </p>
            <p class="price">6,90 €</p>
            <p class="allergens">Allergènes : lait, œufs, gluten</p>
            <form method="post" action="ajouter-panier.php" class="add-cart-form">
                <input type="hidden" name="nom" value="Noix de coco">
                <input type="hidden" name="prix" value="6.9">
                <button type="submit" class="add-to-cart">Ajouter au panier</button>
            </form>
        </div>
        <div class="product-card type-chocolat saveur-chocolat gluten lactose soja oeufs" data-menu="chocolat">
            <div class="product-image">
                <img src="Images/tasses.webp" alt="Tasses">
            </div>
            <h3>Tasses</h3>
            <p class="description_produit">
                Ganache chocolat intense.
            </p>
            <p class="price">6,80 €</p>
            <p class="allergens">Allergènes : gluten, lactose, soja, œufs</p>
            <form method="post" action="ajouter-panier.php" class="add-cart-form">
                <input type="hidden" name="nom" value="Tasses">
                <input type="hidden" name="prix" value="6.8">
                <button type="submit" class="add-to-cart">Ajouter au panier</button>
            </form>
        </div>
        <div class="product-card type-chocolat saveur-chocolat gluten lactose soja oeufs" data-menu="chocolat">
            <div class="product-image">
                <img src="Images/Pommes_de_pin.png" alt="Pommes de pin">
            </div>
            <h3>Pommes de pin</h3>
            <p class="description_produit">
                Réplique de pomme de pin en chocolat, écailles finement sculptées — extérieur croquant, cœur fondant cacaoté.
            </p>
            <p class="price">7,50 €</p>
            <p class="allergens">Allergènes : gluten, lactose, soja, œufs</p>
            <form method="post" action="ajouter-panier.php" class="add-cart-form">
                <input type="hidden" name="nom" value="Pommes de pin">
                <input type="hidden" name="prix" value="7.5">
                <button type="submit" class="add-to-cart">Ajouter au panier</button>
            </form>
        </div>
        <div class="product-card type-classique saveur-classique gluten lactose" data-menu="chocolat">
            <div class="product-image">
                <img src="Images/graine_de_café.jpg" alt="Graine de café">
            </div>
            <h3>Graine de café</h3>
            <p class="description_produit">
                Trompe-l’œil en forme de graine de café — une création aux notes torréfiées, au cœur intense évoquant toute la richesse aromatique du café.
            </p>
            <p class="price">6,80 €</p>
            <p class="allergens">Allergènes : gluten, lactose</p>
            <form method="post" action="ajouter-panier.php" class="add-cart-form">
                <input type="hidden" name="nom" value="Graine de café">
                <input type="hidden" name="prix" value="6.8">
                <button type="submit" class="add-to-cart">Ajouter au panier</button>
            </form>
        </div>
        <div class="product-card type-classique saveur-classique fruits-a-coque gluten lactose" data-menu="chocolat">
            <div class="product-image">
                <img src="Images/noisette.avif" alt="Noisette">
            </div>
            <h3>Noisette</h3>
            <p class="description_produit">
                Réplique de noisette au réalisme bluffant, coque finement nervurée — praliné noisette et cœur fondant à l’intérieur.
            </p>
            <p class="price">7,50 €</p>
            <p class="allergens">Allergènes : fruits à coque, gluten, lactose</p>
            <form method="post" action="ajouter-panier.php" class="add-cart-form">
                <input type="hidden" name="nom" value="Noisette">
                <input type="hidden" name="prix" value="7.5">
                <button type="submit" class="add-to-cart">Ajouter au panier</button>
            </form>
        </div>
        <div class="product-card type-classique saveur-classique gluten lactose oeufs">
            <div class="product-image">
                <img src="Images/oeuf_plat.jpg" alt="Oeuf au plat">
            </div>
            <h3>Oeuf au plat</h3>
            <p class="description_produit">
                Illusion parfaite d’un œuf au plat — blanc délicat et jaune coulant, révélant un dessert fondant et surprenant.
            </p>
            <p class="price">7,50 €</p>
            <p class="allergens">Allergènes : lait, œufs, gluten</p>
            <form method="post" action="ajouter-panier.php" class="add-cart-form">
                <input type="hidden" name="nom" value="Oeuf au plat">
                <input type="hidden" name="prix" value="7.5">
                <button type="submit" class="add-to-cart">Ajouter au panier</button>
            </form>
        </div>
        <div class="product-card type-classique saveur-classique fruits-a-coque arachides gluten lactose">
            <div class="product-image">
                <img src="Images/cacahuete.jpg" alt="Cacahuète">
            </div>
            <h3>Cacahuète</h3>
            <p class="description_produit">
                Réplique de cacahuète en coque texturée — praliné cacahuète et cœur croustillant.
            </p>
            <p class="price">6,90 €</p>
            <p class="allergens">Allergènes : arachides, fruits à coque, lait, gluten</p>
            <form method="post" action="ajouter-panier.php" class="add-cart-form">
                <input type="hidden" name="nom" value="Cacahuète">
                <input type="hidden" name="prix" value="6.9">
                <button type="submit" class="add-to-cart">Ajouter au panier</button>
            </form>
        </div>
        <div class="product-card type-fruit saveur-fruits gluten lactose oeufs">
            <div class="product-image">
                <img src="Images/peche.jpg" alt="Pêche">
            </div>
            <h3>Pêche</h3>
            <p class="description_produit">
                Une pêche plus vraie que nature, à la peau veloutée — mousse légère à la pêche et cœur fruité acidulé.
            </p>
            <p class="price">6,90 €</p>
            <p class="allergens">Allergènes : lait, œufs, gluten</p>
            <form method="post" action="ajouter-panier.php" class="add-cart-form">
                <input type="hidden" name="nom" value="Pêche">
                <input type="hidden" name="prix" value="6.9">
                <button type="submit" class="add-to-cart">Ajouter au panier</button>
            </form>
        </div>

    </div>
</section>

<footer class="footer">
    <p>📞 Téléphone : 07 61 41 44 23</p>
    <p>✉ Email : imposturecontact@gmail.com</p>
    <p>Horaires : Lundi - Vendredi 10h-21h | Samedi - Dimanche 12h-18h</p>
</footer>

<script>
const burger = document.getElementById("burger");
const overlay = document.getElementById("overlay");
const closeBtn = document.getElementById("close");
const searchInput = document.getElementById("searchInput2");
const resetBtn = document.getElementById("resetFilters");
const menuSelection = document.getElementById("menuSelection");
burger.addEventListener("click", () => {
    overlay.classList.add("open");
});
closeBtn.addEventListener("click", () => {
    overlay.classList.remove("open");
});
document.addEventListener("DOMContentLoaded", () => {
    const produits = document.querySelectorAll(".product-card");
    let filtreMenu = "tous";
    let filtreType = "tous";
    let filtreSaveur = "tous";
    let filtreAllergene = "tous";
    let recherche = "";
    const menuLabels = {
        tous: "Tous les menus",
        fraicheur: "Menu Fraîcheur Fruitée",
        exotique: "Menu Exotique & Gourmand",
        chocolat: "Menu Chocolat & Classiques"
    };
    function filtrerProduits() {
        produits.forEach(produit => {
            let afficher = true;
            const nom = (produit.querySelector("h3")?.textContent || "").toLowerCase();
            const menusProduit = (produit.dataset.menu || "").split(" ").filter(Boolean);
            if (filtreMenu !== "tous" && !menusProduit.includes(filtreMenu)) {
                afficher = false;
            }
            if (filtreType !== "tous" && !produit.classList.contains(`type-${filtreType}`)) {
                afficher = false;
            }
            if (filtreSaveur !== "tous" && !produit.classList.contains(`saveur-${filtreSaveur}`)) {
                afficher = false;
            }
            if (filtreAllergene !== "tous" && produit.classList.contains(filtreAllergene)) {
                afficher = false;
            }
            if (recherche && !nom.includes(recherche)) {
                afficher = false;
            }
            produit.style.display = afficher ? "block" : "none";
        });
        if (menuSelection) {
            if (filtreMenu === "tous") {
                menuSelection.style.display = "none";
                menuSelection.textContent = "";
            } else {
                menuSelection.style.display = "block";
                menuSelection.textContent = menuLabels[filtreMenu] || "";
            }
        }
    }
    function activerBoutons(selector, boutonActif) {
        document.querySelectorAll(selector).forEach(btn => btn.classList.remove("active"));
        boutonActif.classList.add("active");
    }
    document.querySelectorAll(".filter-btn[data-menu]").forEach(button => {
        button.addEventListener("click", () => {
            filtreMenu = button.dataset.menu;
            activerBoutons(".filter-btn[data-menu]", button);
            filtrerProduits();
        });
    });
    document.querySelectorAll("[data-type]").forEach(button => {
        button.addEventListener("click", () => {
            filtreType = button.dataset.type;
            activerBoutons("[data-type]", button);
            filtrerProduits();
        });
    });
    document.querySelectorAll("[data-saveur]").forEach(button => {
        button.addEventListener("click", () => {
            filtreSaveur = button.dataset.saveur;
            activerBoutons("[data-saveur]", button);
            filtrerProduits();
        });
    });
    document.querySelectorAll("[data-allergene]").forEach(button => {
        button.addEventListener("click", () => {
            filtreAllergene = button.dataset.allergene;
            activerBoutons("[data-allergene]", button);
            filtrerProduits();
        });
    });
    if (searchInput) {
        searchInput.addEventListener("input", () => {
            recherche = searchInput.value.trim().toLowerCase();
            filtrerProduits();
        });
    }
    if (resetBtn) {
        resetBtn.addEventListener("click", () => {
            filtreMenu = "tous";
            filtreType = "tous";
            filtreSaveur = "tous";
            filtreAllergene = "tous";
            recherche = "";
            if (searchInput) {
                searchInput.value = "";
            }
            document.querySelectorAll(".filter-btn[data-menu]").forEach(btn => btn.classList.remove("active"));
            document.querySelectorAll("[data-type]").forEach(btn => btn.classList.remove("active"));
            document.querySelectorAll("[data-saveur]").forEach(btn => btn.classList.remove("active"));
            document.querySelectorAll("[data-allergene]").forEach(btn => btn.classList.remove("active"));
            document.querySelector('.filter-btn[data-menu="tous"]')?.classList.add("active");
            document.querySelector('[data-type="tous"]')?.classList.add("active");
            document.querySelector('[data-saveur="tous"]')?.classList.add("active");
            document.querySelector('[data-allergene="tous"]')?.classList.add("active");
            filtrerProduits();
        });
    }
    filtrerProduits();
    document.querySelectorAll(".add-cart-form").forEach(form => {
        form.addEventListener("submit", async (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            try {
                const response = await fetch(form.action, {
                    method: "POST",
                    body: formData,
                    headers: {
                        "X-Requested-With": "XMLHttpRequest"
                    }
                });
                const data = await response.json();
                if (data.success) {
                    const compteur = document.querySelector(".cart_count");
                    if (compteur) {
                        compteur.textContent = data.total;
                    }
                    const bouton = form.querySelector(".add-to-cart");
                    const ancienTexte = bouton.textContent;

                    bouton.textContent = "Ajouté ✔";
                    bouton.disabled = true;

                    setTimeout(() => {
                        bouton.textContent = ancienTexte;
                        bouton.disabled = false;
                    }, 1000);
                }
            } catch (erreur) {
                console.error("Erreur AJAX :", erreur);
            }
        });
    });
});
</script>

</body>
</html>
