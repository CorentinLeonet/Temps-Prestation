<?php
	require_once("Adresse.php");
	
	class Entreprise
	{
		public $identifiant = "";
		public $mdp = "";
		
		public $nom ="";
		public $logo ="";
		
		public $tarif;
		public $tarifUrgent;
		public $tva;
		
		public $adresse;
		public $listClients = [];
		
		public function __construct($l, $m, $a, $n)
		{
			$this->identifiant = $l;
			$this->mdp = $m;
			$this->adresse = $a;
			
			$this->tva = 1.21;
			$this->tarif = 15; //default set
			$this->tarifUrgent = 20;
			$this->nom = $n;
		}
		
		public function addClient($c)
		{
			$this->listClients[] = $c;
		}
		
		public function removeClientById($id)
		{
			foreach ($this->listClients as $key => $client) {
				if ($id == $client->id) {
					unset($this->listClients[$key]);
				}
			}
		}
	}