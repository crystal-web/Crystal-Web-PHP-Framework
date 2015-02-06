## Information:

Open-Source depuis le 11 février 2012
Version: 15.01		(YY.MM)
Auteur: Christophe BUFFET
Description: Système MVC permettant au développeurs PHP de réaliser des sites web, rapidement et efficacement.

Nécessite: PHP 5, une base de donnée et mod_rewrite on

## Installation:

ATTENTION public_html/ correspond à la racine du site, le dossier peut être renommé si nécéssaire
Si vous ne disposez pas d'un dossier public_html ou www. Renommé application/ et indiqué le nouveau nom dans index.php ligne 26 
```php
define ( '__APP_PATH', dirname ( __SITE_PATH ) . DS . 'application' );
```


# Modifier le fichier /includes/init.php

* Indiquer les informations de connexion SQL
* Chmod en 777 est requis pour application/cache
* Le script doit générer les tables SQL automatiquement.
* En cas d'erreur, merci de prendre contact

## Information de license:

Licence Creative Commons
Crystal-Web Framework de Christophe BUFFET est mis à disposition selon les termes de la licence Creative Commons Attribution 4.0 International.
Fondé(e) sur une œuvre à https://github.com/crystal-web/CMS-for-Developer-PHP.
Les autorisations au-delà du champ de cette licence peuvent être obtenues à https://github.com/crystal-web/CMS-for-Developer-PHP/issues.


> Soyer honnète, si vous utilisez le script de quelqu'un, indiqué qui est le propriétaire.
> Dans le cas contraire, vous risquez de tuer le partage et les ressources open-sources, qui font la richesse de l'internet actuelle.


## Site réalisé avec Crystal-Web: 

* http://Crystal-Web.org - Site de l'éditeur
* http://ImagineYourCraft.com - Serveur MineCraft hautement modé.
* http://LegendCraft.fr - Serveur Minecraft 
