# Projet-page-web

Pour lancer le site, il faut mettre dans le terminal : php -S localhost:8080 router.php
Ainsi cela va nous permettre de démarrer le site tout en protégeant les fichiers dans le dossier data (voir le fichier router.php).

Pour se connecter aux différents comptes (admin, restaurateur, livreur, client1-5), les informations des comptes (adresse + mots de passe) sont dans le fichier data/compte.json

Admin : peut accéder à la page admin (mdp : admin, mail : admin@gmail.com)
Admin2 : peut accéder à la page admin (mdp : admin2, mail : admin2@gmail.com)
Restaurateur : peut accéder à la page des commandes (mdp : restaurateur, mail : restaurateur@gmail.com)
Livreur : peut accéder à la page de livraison (mdp : livreur, mail : livreur@gmail.com)
Client1-5 : peut accéder partout sauf aux pages de l'admin, du restaurateur et du livreur (mdp : client(1 à 5 selon le client), mail : client(1 à 5 selon le client)@gmail.com)

Pour la phase 4, on a ajouté une fonctionnalité de logs d’incidents. Les événements sensibles comme les mauvais mots de passe, les connexions à un compte bloqué, les actions administrateur et les anomalies de paiement sont enregistrés dans data/logs.json. L’administrateur peut consulter les derniers incidents depuis sa page admin.
