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


**Sécurité**

- Les mots de passe sont hachés 
- Les formulaires sont protégés contre les failles CSRF
- Les utilisateurs sont authentifiés via le composant de sécurité de Symfony
- Les utilisateurs sont autorisés à accéder aux pages en fonction de leur rôle
- Les utilisateurs sont déconnectés après une inactivité prolongée

