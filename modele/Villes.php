<?php

require "Ville.php";

class Villes{
	private $villes;
	public $stack;

	// Constructeur de la classe
	function __construct(){
		// Tableau représentatif d'un jeu qui servira à développer votre code
		$this->villes[0][0]=new Ville("0",3,0);
		$this->villes[0][6]=new Ville("1",2,0);
		$this->villes[3][0]=new Ville("2",6,0);
		$this->villes[3][5]=new Ville("3",2,0);
		$this->villes[5][1]=new Ville("4",1,0);
		$this->villes[5][6]=new Ville("5",2,0);
		$this->villes[6][0]=new Ville("6",2,0);
	}

	// Sélecteur qui retourne la ville en position $i et $j
	// Pré-condition : la ville en position $i et $j existe
	function getVille($i,$j){
	return $this->villes[$i][$j];
	}

	// Modifieur qui value le nombre de ponts de la ville en position $i et $j;
	// Pré-condition : la ville en position $i et $j existe
	function setVille($i,$j,$nombrePonts){
	$this->villes[$i][$j]->setNombrePonts($nombrePonts);
	}

	// Permet de tester si la ville en position $i et $j existe
	// Post-condition: vrai si la ville existe, faux sinon
	function existe($i,$j){
	return isset($this->villes[$i][$j]);
	}

	// Vérifie si la partie doit s'arrêter
  // Post-condition : retourne -1 si la partie continue, 0 en si défaite, 1 si victoire
	function isOver(){
		error_reporting(E_ERROR | E_PARSE);

		// Initialisation de 2 listes pour vérifier qu'elles sont toutes liées
		$marked = [];
		$all = [];

		// Si une des villes n'a pas le nombre de pont exact, ce booléen est vrai
		$incomplet = false;

		for ($i=0; $i < $this->getSize(); $i++) {
			for ($j=0; $j < $this->getSize(); $j++) {

				// Vérifier que c'est une ville
				if($this->villes[$i][$j] instanceof Ville){
					// La partie est perdue si la ville possède plus de ponts que son nombre maximum
					if($this->villes[$i][$j]->getNombrePontsMax() < $this->villes[$i][$j]->getNombrePonts()) return 0;
					if($this->villes[$i][$j]->getNombrePontsMax() > $this->villes[$i][$j]->getNombrePonts()) $incomplet = true;

					// Si la liste des villes marquées est vide, ajoute la première ville rencontrée
					if(empty($marked)) $marked[]=$this->villes[$i][$j]->getId();

					// Pour chaque ville liée à la ville actuelle, l'ajouter à la liste (si elle n'est pas encore dedans)
					foreach ($this->villes[$i][$j]->getVillesLiees() as $key => $value) {
						if(in_array($this->villes[$i][$j]->getId(), $marked) && !in_array($key, $marked)) $marked[] = $key;
					}

					// Liste de toutes les villes du jeu
					$all[] = $this->villes[$i][$j]->getId();
				}
			}
		}

		// Continue le jeu si les villes n'ont pas le nombre de pont exact
		if($incomplet) return -1;

		// Comparaison des 2 listes
		natsort($all);
		natsort($marked);
		$result = array_diff($all, $marked);

		// Si toutes les villes sont marquées, la partie est gagnée
		if(empty($result)) return 1;

		// Sinon le jeu continue
		return -1;
	}

	// Ajoute un pont entre deux villes
	// Si le booléen undo est vrai, ne rajoute pas l'action à la pile
  // Post-condition : construit le pont simple ou double ou retire le pont
	function addBridge($x1,$y1,$x2,$y2,$undo){
		error_reporting(E_ERROR | E_WARNING | E_PARSE);

		// Vérification que ce sont des villes
		if(!$this->villes[$x1][$y1] instanceof Ville || !$this->villes[$x2][$y2] instanceof Ville) return false;

		// Si x1 = x2, le pont est horizontal
		if($x1==$x2 && $y1 != $y2){

			// S'il y a quelque chose sur la route (ville ou pont vertical), on ne peut pas créer le pont
			for ($i = min($y1,$y2) + 1; $i < max($y1,$y2); $i++) if($this->villes[$x1][$i] instanceof Ville || strpos($this->villes[$x1][$i],"vertical")){
				return false;
			}

			// Création du pont
			for ($i = min($y1,$y2) + 1; $i < max($y1,$y2); $i++){
				// S'il n'y avait rien, on pose le pont simple
				if(!isset($this->villes[$x1][$i])) $this->villes[$x1][$i] = "single-horizontal";
				// S'il y a un pont simple, on le change en pont double
				else if($this->villes[$x1][$i] == "single-horizontal") $this->villes[$x1][$i] = "double-horizontal";
				// S'il y a un pont double, on enlève le pont
				else $this->villes[$x1][$i] = null;
			}

			// Ajout des liaisons pour chaque ville
     	$this->getVille($x1,$y1)->addBridge($this->getVille($x2,$y2));
			$this->getVille($x2,$y2)->addBridge($this->getVille($x1,$y1));

			// Pile des actions
			if(!$undo) $this->stack[] = [$x1,$y1,$x2,$y2];

			// Le pont a été créé !
			return true;
		}

		// Si y1 = y2, le pont est vertical
		if($y1==$y2 && $x1 != $x2) {

			// S'il y a quelque chose sur la route (ville ou pont horizontal), on ne peut pas créer le pont
			for ($i = min($x1,$x2) + 1; $i < max($x1,$x2); $i++) if($this->villes[$i][$y1] instanceof Ville || strpos($this->villes[$i][$y1],"horizontal")){
				return false;
			}

			// Création du pont
			for ($i = min($x1,$x2) + 1; $i < max($x1,$x2); $i++){
				// S'il n'y avait rien, on pose le pont simple
				if(!isset($this->villes[$i][$y1])) $this->villes[$i][$y1] = "single-vertical";
				// S'il y a un pont simple, on le change en pont double
				else if($this->villes[$i][$y1] == "single-vertical") $this->villes[$i][$y1] = "double-vertical";
				// S'il y a un pont double, on enlève le pont
				else $this->villes[$i][$y1] = null;
			}

			// Ajout des liaisons pour chaque ville
			$this->getVille($x1,$y1)->addBridge($this->getVille($x2,$y2));
			$this->getVille($x2,$y2)->addBridge($this->getVille($x1,$y1));

			// Pile des actions
			if(!$undo) $this->stack[] = [$x1,$y1,$x2,$y2];

			// Le pont a été créé !
			return true;
		}

		// x1 != x2 & y1 != y2 : impossible de créer un pont
		// x1 = x2 & y1 = y2 : le joueur a choisi 2 fois la même ville
		return false;
	}

	// Dépile la dernière action
	function undo(){
		return array_pop($this->stack);
	}

	// Retourne la taille de l'attribut villes
	function getSize(){
		return max(array_keys($this->villes))+1;
	}
}

?>
