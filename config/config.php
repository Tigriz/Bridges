<?php

// Les chemins vers les différents répertoires liés au modèle MVC
// Chemin complet sur le serveur de la racine du site, il est supposé que config.php est dans un sous-repertoire de la racine du site
define("HOME_SITE",__DIR__."/..");
// Définition des chemins vers les divers répertoires liés au modèle MVC
define("PATH_VUE",HOME_SITE."/vue");
define("PATH_CONTROLEUR",HOME_SITE."/controleur");
define("PATH_MODELE",HOME_SITE."/modele");


// Données pour la connexion au sgbd
define("HOST","localhost");
define("BD","bd");
define("LOGIN","login");
define("PASSWORD","password");

?>
