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


**Pour les statistiques**
Ajouter la fonction dans SQL

DELIMITER $$

CREATE FUNCTION get_quoted_values(longtext_field LONGTEXT) RETURNS VARCHAR(10000)
BEGIN
DECLARE start INT DEFAULT 1;
DECLARE end INT DEFAULT 1;
DECLARE result VARCHAR(10000) DEFAULT '';
DECLARE value VARCHAR(10000);

WHILE start > 0 DO
SET start = LOCATE('"', longtext_field, end + 1);
SET end = LOCATE('"', longtext_field, start + 1);
SET value = SUBSTRING(longtext_field, start + 1, end - start - 1);
IF start > 0 THEN
SET result = CONCAT(result, value, ',');
END IF;
END WHILE;

RETURN TRIM(TRAILING ',' FROM result);
END$$

DELIMITER ;

**Sécurité**

- Les mots de passe sont hachés 
- Les formulaires sont protégés contre les failles CSRF
- Les utilisateurs sont authentifiés via le composant de sécurité de Symfony
- Les utilisateurs sont autorisés à accéder aux pages en fonction de leur rôle
- Les utilisateurs sont déconnectés après une inactivité prolongée
- Protection contre les attaques BrutForce


``bug``
Si il y a un bug du type


Expected to find class "App\Service\MenuExtension" in file "/Users/elouanbarbier/GitHub/saferProjetPW/src/Service/MenuExtension.php" while importing services from resource "../src/", but it was not found! Check the namespace prefix used with the resource in /Users/elouanbarbier/GitHub/saferProjetPW/config/services.yaml (which is being imported from "/Users/elouanbarbier/GitHub/saferProjetPW/src/Kernel.php").

Faire la commande :

php bin/console cache:clear
ou 
composer install