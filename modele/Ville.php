<?php

class Ville{
	// Permet d'identifier de manière unique la ville
	private $id;
	private $nombrePontsMax;
	private $nombrePonts;
	// Un tableau associatif qui stocke les villes qui sont reliées à la ville cible et le nombre de ponts qui les relient (ce nombre de ponts doit être <=2)
	private $villesLiees;

	// Constructeur de la classe
	function __construct($id,$nombrePontsMax,$nombrePonts){
		$this->id=$id;
		$this->nombrePontsMax=$nombrePontsMax;
		$this->nombrePonts=$nombrePonts;
		$this->villesLiees=null;
	}

	// Sélecteur qui retourne la valeur de l'attribut id
	function getId(){
		return $this->id;
	}

	// Sélecteur qui retourne la valeur de l'attribut nombrePontsMax
	function getNombrePontsMax(){
		return $this->nombrePontsMax;
	}

	// Sélecteur qui retourne la valeur de l'attribut nombrePonts
	function getNombrePonts(){
		return $this->nombrePonts;
	}

	// Sélecteur qui retourne la valeur de l'attribut villesLiees
	function getVillesLiees(){
		return $this->villesLiees;
	}

	// Modifieur qui permet de valuer l'attribut nombrePonts
	function setNombrePonts($nb){
		$this->nombrePonts=$nb;
	}

	// Lie la ville à une ville cible unilatéralement
	function addBridge($ville){
		// S'il y a 0 ou 1 pont, on peut incrémenter le nombre de ponts total et le nombre de pont vers la ville cible
		if($this->villesLiees[$ville->getId()] < 2){
			$this->setNombrePonts($this->getNombrePonts()+1);
			$this->villesLiees[$ville->getId()]=$this->villesLiees[$ville->getId()]+1;
		}

		// Sinon, on a 2 ponts, on enlève 2 au nombre total de ponts et on enlève la ville cible des villes liées
		else{
			$this->setNombrePonts($this->getNombrePonts()-2);
			unset($this->villesLiees[$ville->getId()]);
		}
	}
}

?>
