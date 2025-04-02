<?php
	require_once("Adresse.php");
	
	class Client
	{
		public $id;
		public $nom = "";
		public $prenom = "";
		public $telephone = "";
		public $email = "";
		public $adresse;
		public $prestations = [];
		
		public function __construct($n, $p, $t, $e, $a)
		{
			$this->nom = $n;
			$this->prenom = $p;
			$this->telephone = $t;
			$this->email = $e;
			$this->adresse = $a;
		}
		
		public function addPrestation($p){
			$this->prestations[] = $p;
		}
		
		public function getNomPrenom()
		{
			return $this->nom.' '.$this->prenom;
		}
		
		public function removePrestationById($id)
		{
			foreach ($this->prestations as $key => $prestation) {
				if ($id == $prestation->id) {
					unset($this->prestations[$key]);
				}
			}
		}
	}