# saferProjetPW
L3 miage PW


1) Importer les composer 

aller dans le fichier et faire `composer install`

2) Installer la database: 

(peut-être la créer) 

php bin/console doctrine:database:create

`php bin/console doctrine:migrations:migrate`

3) mettre toutes les catégories 

`php bin/console app:create-categorie`

4) mettre les données 

`php bin/console app:csvToDBBien`

5) vérifier si c'est bon 

**Notation**

Le fichier "grille_notation_avec_comentaire.pdf" permet de vous aidez dans la correction en associant chaque demande à la page ou commande

vidéo : https://www.youtube.com/watch?v=kAiVHgKrAsQ
(peut être réduire la vitesse si on parle trop rapidement...)

**Sécurité**

- Les mots de passe sont hachés 
- Les formulaires sont protégés contre les failles CSRF
- Les utilisateurs sont authentifiés via le composant de sécurité de Symfony
- Les utilisateurs sont autorisés à accéder aux pages en fonction de leur rôle
- Les utilisateurs sont déconnectés après une inactivité prolongée
- Protection contre les attaques BrutForce


**bug**
Si il y a un bug du type

"
Expected to find class "App\Service\MenuExtension" in file "/Users/elouanbarbier/GitHub/saferProjetPW/src/Service/MenuExtension.php" while importing services from resource "../src/", but it was not found! Check the namespace prefix used with the resource in /Users/elouanbarbier/GitHub/saferProjetPW/config/services.yaml (which is being imported from "/Users/elouanbarbier/GitHub/saferProjetPW/src/Kernel.php").
"

Faire la commande :

``php bin/console cache:clear``
ou 
``composer install``

Le groupe :
Elouan BARBIER | Yannis BEN BRAHIM | Francois QUINAOU