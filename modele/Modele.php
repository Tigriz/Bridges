<?php

// Classe generale de définition d'exception
class MonException extends Exception{
  private $chaine;
  public function __construct($chaine){
    $this->chaine=$chaine;
  }
}

// Exception relative à un problème de connexion
class ConnexionException extends MonException{
}

// Exception relative à un problème d'accès à une table
class TableAccesException extends MonException{
}

// Classe qui gère les accès à la base de données
class Modele{
  private $connexion;

  // Constructeur de la classe
  public function __construct() {
    try {
      $chaine="mysql:host=".HOST.";dbname=".BD;
      $this->connexion = new PDO($chaine,LOGIN,PASSWORD);
      $this->connexion->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e) {
      $exception=new ConnexionException("Problème de connexion à la base");
      throw $exception;
    }
  }

  // Méthode qui permet de se deconnecter de la base
  public function deconnexion(){
    $this->connexion=null;
  }

  // Vérifie l'existence du couple login/password dans la table joueurs
  // Post-condition : retourne vrai si le couple existe, faux sinon
  // Si un problème est rencontré, une exception de type TableAccesException est levée
  public function connect($login, $password){
    try {
      $statement = $this->connexion->prepare("select motDePasse from joueurs where pseudo = ?;");
      $statement->bindParam(1, $login);
      $statement->execute();
      $result=$statement->fetch(PDO::FETCH_ASSOC);
      if (crypt($password, $result["motDePasse"]) == $result["motDePasse"]) return true;
      else return false;
    }
    catch(PDOException $e){
      $this->deconnexion();
      throw new TableAccesException("problème avec la table pseudonyme");
    }
  }

  // Insère le résultat de la partie dans la table parties
  // Si un problème est rencontré, une exception de type TableAccesException est levée
  public function sendResult($pseudo, $result){
    try {
      $statement = $this->connexion->prepare("INSERT INTO `parties` (`id`, `pseudo`, `partieGagnee`) VALUES (NULL, ?, ?)");
      $statement->bindParam(1, $pseudo);
      $statement->bindParam(2, $result);
      $statement->execute();
    }
    catch(PDOException $e){
      $this->deconnexion();
      throw new TableAccesException("Problème avec la table parties");
    }
  }

  // Récupère les statistiques du joueur
  // Post-condition : retourne une table pseudo/victoires/nombre de parties
  // Si un problème est rencontré, une exception de type TableAccesException est levée
  public function getStats($pseudo){
    try{
      $statement=$this->connexion->prepare("SELECT DISTINCT pseudo, (SELECT count(*) FROM parties WHERE parties.pseudo = p.pseudo AND parties.partieGagnee = 1) AS wins, (SELECT count(*) FROM parties WHERE parties.pseudo = p.pseudo AND parties.partieGagnee = 0) AS losses, (SELECT count(*) FROM parties WHERE parties.pseudo = p.pseudo) AS games FROM parties AS p WHERE pseudo = ?");
      $statement->bindParam(1, $pseudo);
      $statement->execute();
      return($statement->fetchAll(PDO::FETCH_ASSOC));
    }
    catch(PDOException $e){
      $this->deconnexion();
      throw new TableAccesException("Problème avec la table parties");
    }
  }

  // Récupère le top 3 des joueurs ayant gagné le plus de parties dans la table parties
  // Post-condition : retourne une table pseudo/victoires/nombre de parties
  // Si un problème est rencontré, une exception de type TableAccesException est levée
  public function getLeaderboard(){
    try{
      $statement=$this->connexion->query("SELECT DISTINCT pseudo, (SELECT count(*) FROM parties WHERE parties.pseudo = p.pseudo AND parties.partieGagnee = 1) AS wins, (SELECT count(*) FROM parties WHERE parties.pseudo = p.pseudo) AS games FROM parties AS p ORDER BY wins DESC LIMIT 3");
      return($statement->fetchAll(PDO::FETCH_ASSOC));
    }
    catch(PDOException $e){
      $this->deconnexion();
      throw new TableAccesException("Problème avec la table parties");
    }
  }

  // Vérifie l'existence du pseudo dans la table joueurs
  // Post-condition : retourne vrai si le pseudo existe, faux sinon
  // Si un problème est rencontré, une exception de type TableAccesException est levée
  public function existe($pseudo){
    try{
      $statement = $this->connexion->prepare("SELECT pseudo FROM joueurs WHERE pseudo=?;");
      $statement->bindParam(1, $pseudo);
      $statement->execute();
      $result=$statement->fetch(PDO::FETCH_ASSOC);
      if ($result["pseudo"]!=null) return true;
      else return false;
    }
    catch(PDOException $e){
      $this->deconnexion();
      throw new TableAccesException("problème avec la table pseudonyme");
    }
  }

  // Insère le couple login/password dans la table joueurs
  // Si un problème est rencontré, une exception de type TableAccesException est levée
  public function inscription($login, $password){
    error_reporting(E_ERROR | E_WARNING | E_PARSE);
    try {
      $statement = $this->connexion->prepare("INSERT INTO `joueurs` (`pseudo`, `motDePasse`) VALUES (?, ?)");
      $statement->bindParam(1, $login);
      $crypt = crypt($password);
      $statement->bindParam(2, $crypt);
      $statement->execute();
    }
    catch(PDOException $e){
      $this->deconnexion();
      throw new TableAccesException("problème avec la table joueurs");
    }
  }
}

?>
