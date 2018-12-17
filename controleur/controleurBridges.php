<?php

require_once PATH_VUE."/vue.php";
require_once PATH_MODELE."/Villes.php";

class ControleurBridges{
  private $vue;
  private $connexion;
  private $bridges;

	// Constructeur de la classe
  function __construct(){
    $this->vue = new Vue();
    $this->connexion = new Modele();
    $this->bridges = new Villes();
  }

  // Redirection vers les vues et méthodes
  function accueil(){
    $this->vue->header(isset($_SESSION["dark"]));

    if($this->connexion->existe($_SESSION["pseudo"])){

      // Affichage leaderboard
      if(isset($_GET["leaderboard"])){
        $stats = $this->connexion->getStats($_SESSION["pseudo"]);
        $score = $this->connexion->getLeaderboard();
        $this->vue->navbar(isset($_SESSION["dark"]), 2, "&leaderboard");
        $this->vue->leaderboard($stats, $score);
        return;
      }

      // Si la partie n'est pas encore stockée dans SESSION
      if(!isset($_SESSION["game"])) $_SESSION["game"] = $this->bridges;

      // Réinitialisation et défaite
      if(isset($_POST["reset"])){
        $this->connexion->sendResult($_SESSION["pseudo"], "0");
        $this->bridges = new Villes();
        $_SESSION["game"] = $this->bridges;
        $this->vue->navbar(isset($_SESSION["dark"]), 1, "");
        $this->vue->demandeBridges($this->bridges, -1, -1);
        $this->vue->modal("Perdu","Vous avez perdu","");
      }

      // Actions sur le jeu
      else {

        // Récupération du jeu
        $this->bridges = $_SESSION["game"];
        $this->vue->navbar(isset($_SESSION["dark"]), 1, "");

        // Annulation du dernier coup
        if(isset($_GET["undo"])){
          $action = $this->bridges->undo();
          $this->bridges->addBridge($action[0],$action[1],$action[2],$action[3],true);
          $this->bridges->addBridge($action[0],$action[1],$action[2],$action[3],true);
          $_SESSION["game"] = $this->bridges;
          $this->vue->demandeBridges($this->bridges, -1, -1);
        }

        // Si le joueur a choisi 2 villes, construire le pont
        else if(isset($_GET["x1"]) && isset($_GET["y1"]) && isset($_GET["x2"]) && isset($_GET["y2"])){
          // Si le pont ne peut pas être construit, afficher une erreur
          if(!$this->bridges->addBridge($_GET["x1"],$_GET["y1"],$_GET["x2"],$_GET["y2"],false)){
            $this->vue->demandeBridges($this->bridges, -1, -1);
            $this->vue->modal("Erreur", "Impossible de créer le pont", "");
          }
          // Sinon vérifier l'état de la partie, continuer, gagner ou perdre en conséquence
          else {
            $result = $this->bridges->isOver();
            if($result != -1){
              $this->connexion->sendResult($_SESSION["pseudo"], "".$result);
              $this->bridges = new Villes();
              $_SESSION["game"] = $this->bridges;
              $this->vue->demandeBridges($this->bridges, -1, -1);
              if($result == 0) $this->vue->modal("Perdu","Vous avez perdu","");
              else $this->vue->modal("Gagné","Vous avez gagné","");
            } else $this->vue->demandeBridges($this->bridges, -1, -1);
          }

        // Si le joueur a choisi une ville, renseigner les GET pour le choix de sa 2ème ville
        } else if(isset($_GET["x1"]) && isset($_GET["y1"])) $this->vue->demandeBridges($this->bridges, $_GET["x1"], $_GET["y1"]);

        // Sinon afficher le jeu
        else $this->vue->demandeBridges($this->bridges, -1, -1);
      }
    }
    else {
     unset($_SESSION["pseudo"]);
     unset($_SESSION["dark"]);
     $this->vue->demandePseudo();
     $this->vue->modal("Hein ?","Votre pseudo n'existe plus..?","");
   }
  }
}

?>
