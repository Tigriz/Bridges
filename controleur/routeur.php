<?php

require_once 'controleurAuthentification.php';
require_once 'controleurBridges.php';

class Routeur {
  private $ctrlAuthentification;
  private $ctrlBridges;

  // Constructeur de la classe
  public function __construct() {
    $this->ctrlAuthentification = new ControleurAuthentification();
    $this->ctrlBridges = new ControleurBridges();
  }

  // Traite une requête entrante
  public function routerRequete() {
    session_start();

    // Déconnexion
    if(isset($_GET["logout"])) {
      unset($_SESSION["pseudo"]);
      unset($_SESSION["dark"]);
      $this->ctrlAuthentification->accueil();
      return;
    }

    // Thème sombre
    if(isset($_GET["dark"]))
      if(!isset($_SESSION["dark"])) $_SESSION["dark"]=1;
      else unset($_SESSION["dark"]);

    // Vers les contrôleurs si la session est ouverte ou non
    if(!isset($_SESSION["pseudo"])) $this->ctrlAuthentification->accueil();
    if(isset($_SESSION["pseudo"]))  $this->ctrlBridges->accueil();
  }
}

?>
