<?php
session_start();
include "bibliothèques/bloquer.php";

if(!isset($_SESSION["panier"])){
    $_SESSION["panier"] = [];
}

$nbArticles = 0;

foreach ($_SESSION["panier"] as $item){
    $nbArticles += $item["quantite"];
}

function h($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Cergy</title>
    <link rel="stylesheet" href="styles.css">
    <link id="theme-link" rel="stylesheet" href="css/default.css">
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
                <a href="deconnexion.php">Déconnexion</a>
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
        <input type="text" id="searchInput2" placeholder="Rechercher nos produits ..." autocomplete="off">
        <a href="commander.php" class="cart">
            🛒 <span class="cart_count"><?php echo h($nbArticles); ?></span>
        </a>
        <button id="theme-button">Changer le thème</button>
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
    <div class="titre-crewmates">
        <img src="Images/crewmate1.png" alt="" class="crewmate-titre">
        <h2>Nos Trompe-l’œil Fruités - Cergy</h2>
        <img src="Images/crewmate2.png" alt="" class="crewmate-titre">
    </div>
    <p>
        Des desserts qui ressemblent à de vrais fruits,
        mais qui cachent des mousses,
        ganaches et inserts gourmands.
    </p>
</div>

<div class="filter-bar">
    <h2>Filtrer et trier</h2>

    <div class="filter-group">
        <p>Catégories :</p>
        <button type="button" class="filter-btn active" data-filter="categorie" data-value="tous">Toutes</button>
        <button type="button" class="filter-btn" data-filter="categorie" data-value="desserts">Desserts</button>
        <button type="button" class="filter-btn" data-filter="categorie" data-value="boissons">Boissons</button>
    </div>
    <div class="filter-group">
        <p>Menus :</p>
        <button type="button" class="filter-btn active" data-filter="menu" data-value="tous">Tous</button>
        <button type="button" class="filter-btn" data-filter="menu" data-value="fraicheur">Menu Fraîcheur</button>
        <button type="button" class="filter-btn" data-filter="menu" data-value="exotique">Menu Exotique</button>
        <button type="button" class="filter-btn" data-filter="menu" data-value="chocolat">Menu Chocolat</button>
    </div>
    <div class="filter-group">
        <p>Types :</p>
        <button type="button" class="filter-btn active" data-filter="type" data-value="tous">Tous</button>
        <button type="button" class="filter-btn" data-filter="type" data-value="fruit">Fruits</button>
        <button type="button" class="filter-btn" data-filter="type" data-value="chocolat">Chocolat</button>
        <button type="button" class="filter-btn" data-filter="type" data-value="classique">Classiques</button>
    </div>
    <div class="filter-group">
        <p>Saveurs :</p>
        <button type="button" class="filter-btn active" data-filter="saveur" data-value="tous">Toutes</button>
        <button type="button" class="filter-btn" data-filter="saveur" data-value="agrumes">Agrumes</button>
        <button type="button" class="filter-btn" data-filter="saveur" data-value="exotique">Exotique</button>
        <button type="button" class="filter-btn" data-filter="saveur" data-value="fruits-rouges">Fruits rouges</button>
        <button type="button" class="filter-btn" data-filter="saveur" data-value="chocolat">Chocolat</button>
        <button type="button" class="filter-btn" data-filter="saveur" data-value="fruits">Fruits</button>
        <button type="button" class="filter-btn" data-filter="saveur" data-value="classique">Classique</button>
    </div>
    <div class="filter-group">
        <p>Régimes :</p>
        <button type="button" class="filter-btn active" data-filter="regime" data-value="tous">Tous</button>
        <button type="button" class="filter-btn" data-filter="regime" data-value="vegetarien">Végétarien</button>
        <button type="button" class="filter-btn" data-filter="regime" data-value="vegan">Vegan</button>
        <button type="button" class="filter-btn" data-filter="regime" data-value="halal">Halal</button>
        <button type="button" class="filter-btn" data-filter="regime" data-value="sans-gluten">Sans gluten</button>
        <button type="button" class="filter-btn" data-filter="regime" data-value="sans-lactose">Sans lactose</button>
        <button type="button" class="filter-btn" data-filter="regime" data-value="sans-oeufs">Sans œufs</button>
    </div>
    <div class="filter-group">
        <p>Exclure :</p>
        <button type="button" class="filter-btn active" data-filter="allergene" data-value="tous">Aucun</button>
        <button type="button" class="filter-btn" data-filter="allergene" data-value="gluten">Gluten</button>
        <button type="button" class="filter-btn" data-filter="allergene" data-value="lactose">Lactose</button>
        <button type="button" class="filter-btn" data-filter="allergene" data-value="oeufs">Œufs</button>
        <button type="button" class="filter-btn" data-filter="allergene" data-value="soja">Soja</button>
        <button type="button" class="filter-btn" data-filter="allergene" data-value="arachides">Arachides</button>
        <button type="button" class="filter-btn" data-filter="allergene" data-value="fruits-a-coque">Fruits à coque</button>
    </div>
    <div class="filter-group">
        <p>Goût :</p>
        <button type="button" class="filter-btn active" data-filter="gout" data-value="tous">Tous</button>
        <button type="button" class="filter-btn" data-filter="gout" data-value="sucre">Sucré</button>
        <button type="button" class="filter-btn" data-filter="gout" data-value="sale">Salé</button>
        <button type="button" class="filter-btn" data-filter="gout" data-value="epice">Épicé</button>
    </div>
    <div class="filter-group">
        <p>Tri :</p>
        <select id="sortSelect">
            <option value="aucun">Aucun tri</option>
            <option value="prix_asc">Prix croissant</option>
            <option value="prix_desc">Prix décroissant</option>
            <option value="plus_commandes">Les plus commandés</option>
        </select>
    </div>

    <button type="button" id="resetFilters" class="filter-btn">Réinitialiser</button>
</div>

<section class="products">
    <div class="product-grid" id="productGrid"></div>

    <p id="noProductsMessage" style="display:none; text-align:center;">
        Aucun produit ne correspond aux filtres sélectionnés.
    </p>
</section>

    <footer class="footer">
        <p>📞 Téléphone : 07 67 01 02 03</p>
        <p>✉ Email : imposteurcontact@gmail.com</p>
        <p>Horaires : Lundi - Vendredi 10h-21h | Samedi - Dimanche 12h-18h</p>
    </footer>

<script>
const burger = document.getElementById("burger");
const overlay = document.getElementById("overlay");
const closeBtn = document.getElementById("close");
const searchInput = document.getElementById("searchInput2");
const resetBtn = document.getElementById("resetFilters");
const productGrid = document.getElementById("productGrid");
const noProductsMessage = document.getElementById("noProductsMessage");
const sortSelect = document.getElementById("sortSelect");

if(burger){
    burger.addEventListener("click", () => {
        overlay.classList.add("open");
    });
}

if(closeBtn){
    closeBtn.addEventListener("click", () => {
        overlay.classList.remove("open");
    });
}

document.addEventListener("DOMContentLoaded", () => {
    let filtres = {
        menu: "tous",
        categorie: "tous",
        type: "tous",
        saveur: "tous",
        allergene: "tous",
        regime: "tous",
        gout: "tous",
        recherche: ""
    };

    let produitsRecuperes = [];

    function h(value){
        return String(value)
            .replaceAll("&", "&amp;")
            .replaceAll("<", "&lt;")
            .replaceAll(">", "&gt;")
            .replaceAll('"', "&quot;")
            .replaceAll("'", "&#039;");
    }

    function formatPrix(prix){
        return Number(prix).toFixed(2).replace(".", ",") + " €";
    }

    function trierProduits(produits){
        const tri = sortSelect ? sortSelect.value : "aucun";
        const copie = [...produits];
        if(tri === "prix_asc"){
            copie.sort((a, b) => Number(a.prix) - Number(b.prix));
        }
        if(tri === "prix_desc"){
            copie.sort((a, b) => Number(b.prix) - Number(a.prix));
        }
        if(tri === "plus_commandes"){
            copie.sort((a, b) => Number(b.commandes || 0) - Number(a.commandes || 0));
        }
        return copie;
    }

    function afficherProduits(produits){
        const produitsTries = trierProduits(produits);
        productGrid.innerHTML = "";
        if(produitsTries.length === 0){
            noProductsMessage.style.display = "block";
            return;
        }
        noProductsMessage.style.display = "none";
        produitsTries.forEach(produit => {
            const allergenes = Array.isArray(produit.allergenes)
                ? produit.allergenes.join(", ")
                : "";
            productGrid.innerHTML += `
                <div class="product-card">
                    <div class="product-image">
                        <img src="${h(produit.image)}" alt="${h(produit.nom)}">
                    </div>
                    <h3>${h(produit.nom)}</h3>
                    <p class="description_produit">
                        ${h(produit.description)}
                    </p>
                    <p class="price">${formatPrix(produit.prix)}</p>
                    <p class="allergens">
                        Allergènes : ${h(allergenes || "aucun")}
                    </p>
                    <form method="post" action="ajouter-panier.php" class="add-cart-form">
                        <input type="hidden" name="nom" value="${h(produit.nom)}">
                        <input type="hidden" name="prix" value="${h(produit.prix)}">
                        <button type="submit" class="add-to-cart">Ajouter au panier</button>
                    </form>
                </div>
            `;
        });
        activerAjoutPanier();
    }

    async function chargerProduits(){
        const params = new URLSearchParams(filtres);
        try {
            const response = await fetch("filtrer-produits.php?" + params.toString());
            produitsRecuperes = await response.json();
            afficherProduits(produitsRecuperes);
        } catch (erreur){
            console.error("Erreur chargement produits :", erreur);
        }
    }

    function activerAjoutPanier(){
        document.querySelectorAll(".add-cart-form").forEach(form => {
            form.addEventListener("submit", async (e) => {
                e.preventDefault();
                const formData = new FormData(form);
                const bouton = form.querySelector(".add-to-cart");
                const ancienTexte = bouton.textContent;
                formData.append("requete_fetch", "1");
                try {
                    const response = await fetch(form.action, {
                        method: "POST",
                        body: formData
                    });
                    const data = await response.json();
                    if(data.success){
                        const compteur = document.querySelector(".cart_count");
                        if(compteur){
                            compteur.textContent = data.total;
                        }
                        bouton.textContent = "Ajouté ✔";
                        bouton.disabled = true;
                        setTimeout(() => {
                            bouton.textContent = ancienTexte;
                            bouton.disabled = false;
                        }, 1000);
                    }
                } catch (erreur){
                    console.error("Erreur fetch panier :", erreur);
                }
            });
        });
    }

    document.querySelectorAll(".filter-btn[data-filter]").forEach(button => {
        button.addEventListener("click", () => {
            const filtre = button.dataset.filter;
            const valeur = button.dataset.value;
            filtres[filtre] = valeur;
            document
                .querySelectorAll(`.filter-btn[data-filter="${filtre}"]`)
                .forEach(btn => btn.classList.remove("active"));
            button.classList.add("active");
            chargerProduits();
        });
    });
    if(searchInput){
        searchInput.addEventListener("input", () => {
            filtres.recherche = searchInput.value.trim().toLowerCase();
            chargerProduits();
        });
    }
    if(sortSelect){
        sortSelect.addEventListener("change", () => {
            afficherProduits(produitsRecuperes);
        });
    }
    if(resetBtn){
        resetBtn.addEventListener("click", () => {
            filtres = {
                menu: "tous",
                categorie: "tous",
                type: "tous",
                saveur: "tous",
                allergene: "tous",
                regime: "tous",
                gout: "tous",
                recherche: ""
            };
            if(searchInput){
                searchInput.value = "";
            }
            if(sortSelect){
                sortSelect.value = "aucun";
            }
            document.querySelectorAll(".filter-btn[data-filter]").forEach(btn => {
                btn.classList.remove("active");
            });
            document.querySelectorAll('.filter-btn[data-value="tous"]').forEach(btn => {
                btn.classList.add("active");
            });
            chargerProduits();
        });
    }
    chargerProduits();
});
</script>
<script src="cookie.js"></script>
</body>
</html>
