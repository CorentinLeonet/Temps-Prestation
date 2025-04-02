<?php
	require_once("../model/Entreprise.php");
	require_once("../model/Client.php");
	require_once("../model/Prestation.php");
	require_once("DAO.php");
	
	class Controller
	{
		public $entreprise;
		public $client;
		public $prestation;
		public $dao;
		
		public function __construct()
		{
			$this->dao = new DAO();
		}
		
		public function saveEntreprise(){
			$this->dao->saveEntreprise($this->entreprise);
		}
		
		public function login($identifiant, $mdp)
		{
			$this->entreprise = $this->dao->login($identifiant, $mdp);
			if($this->entreprise == null){
				return false;
			}
			else{
				return true;
			}
		}

		public function saveClient($client)
		{
			$id = $this->dao->saveClient($client, $this->entreprise);
			$this->entreprise->addClient($this->dao->findClientById($id));
		}
		
		public function updateEntreprise()
		{
			$this->dao->updateEntreprise($this->entreprise);
		}
		
		public function removeClientById($id)
		{
			$this->dao->removeClientById($id);
			$this->entreprise->removeClientById($id);
		}
		
		public function facturer()
		{
			$this->prestation->client = $this->client;
			$this->prestation->entreprise = $this->entreprise;
			$this->prestation->createFacture();
		}
		
		public function savePrestation(){
			$this->dao->savePrestation($this->prestation);
		}
		
		public function removePrestationById($id)
		{
			$this->dao->removePrestationById($id);
			$this->client->removePrestationById($id);
		}
		
		public function findPrestationById($id)
		{
			return $this->dao->findPrestationById($id);
		}
		
		public function removeEntreprise(){
			$this->dao->removeEntreprise($this->entreprise);
		}
		
		public function updateClient()
		{
			$this->dao->updateClient($this->client);
		}

	}