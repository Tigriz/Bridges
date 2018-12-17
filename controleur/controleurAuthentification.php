<?php

require_once PATH_VUE."/vue.php";
require_once PATH_MODELE."/Modele.php";

class ControleurAuthentification{
  private $vue;
  private $connexion;

	// Constructeur de la classe
  function __construct(){
    $this->vue = new Vue();
    $this->connexion = new Modele();
  }

  // Redirection vers les vues et méthodes
  function accueil(){
    $this->vue->header(isset($_SESSION["dark"]));

    // Vers la page d'inscription
    if(isset($_GET["register"])){
      $this->vue->formInscription();
      return;
    }

    // Vérification de l'inscription
    if(isset($_POST["loginInscription"]) && isset($_POST["passwordInscription"])){

      // Si l'identifiant est vide
      if($_POST["loginInscription"]==""){
        $this->vue->formInscription();
        $this->vue->modal("Erreur","Vous n'avez pas renseigné d'identifiant !","?register");
        return;
      }

      if(!$this->connexion->existe($_POST["loginInscription"])){
        $this->connexion->inscription($_POST["loginInscription"], $_POST["passwordInscription"]);
        $this->vue->demandePseudo();
      } else {
        $this->vue->formInscription();
        $this->vue->modal("Erreur","Le pseudo existe déjà !","?register");
      }
      return;
    }

    // Vérfication de la connexion
    if(isset($_POST["login"]) && isset($_POST["password"])){
      if($this->connexion->connect($_POST["login"], $_POST["password"])){
        $_SESSION["pseudo"] = $_POST["login"];
      }
      else {
        $this->vue->demandePseudo();
        $this->vue->modal("Erreur","Mauvais identifiant ou mauvais mot de passe","");
      }
      return;
    }
    $this->vue->demandePseudo();
  }
}

?>
