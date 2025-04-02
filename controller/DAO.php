<?php
	require_once("../model/Entreprise.php");
	require_once("../model/Adresse.php");
	require_once("../model/Client.php");
	require_once("../model/Prestation.php");
	
	
	class DAO
	{
		private $host = 'localhost:33020';
		private $dbname = 'web2023';
		private $username = 'root';
		private $password = 'password';
		
		
		public function __construct()
		{
			
		}
		
		private function connectDB()
		{
			
			try {
				$pdo = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->username, $this->password);
				$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
				
			} catch (PDOException $e) {
				echo 'Erreur de connexion : ' . $e->getMessage();
			}
			
			return $pdo;
		}
		
		public function login($identifiant, $mdp)
		{
			
			$pdo = $this->connectDB();
			
			$query = "SELECT * FROM entreprise WHERE identifiant = :identifiant";
			$stmt = $pdo->prepare($query);
			$stmt->execute(['identifiant' => $identifiant]);
			$entreprise = $stmt->fetch();
			$pdo = null;
			if($entreprise != null){
				$adresse = $this->findAdresseByID($entreprise['adresse']);
				if ($entreprise && password_verify($mdp,$entreprise['mdp'])) {
					$newEntreprise = new Entreprise($entreprise['identifiant'], $entreprise['mdp'], $adresse, $entreprise['nom']);
					$newEntreprise->tarif = $entreprise['tarif'];
					$newEntreprise->tva = $entreprise['tva'];
					$newEntreprise->tarifUrgent = $entreprise['tarifUrgent'];
					$newEntreprise->logo = $entreprise['logo'];
					foreach($this->findClientsByEntreprise($newEntreprise) as $client){
						$adresse = $this->findAdresseByID($client['adresse']);
						$cli = new Client($client['nom'], $client['prenom'], $client['telephone'], $client['email'], $adresse);
						$cli->id = $client['id'];
						foreach($this->findPrestationByClient($cli) as $prestation){
							$cli->addPrestation($prestation);
						}
						$newEntreprise->addClient($cli);
					}
					return $newEntreprise;
				} else {
					return null;
				}
			}
			
		}
		
		public function saveEntreprise($entreprise)
		{
			$pdo = $this->connectDB();
			
			$pdo->exec("SET autocommit = 0");
			
			$query = "INSERT INTO adresse (rue, numero, code_postal, ville) VALUES (:values1, :values2, :values3, :values4)";
			$stmt = $pdo->prepare($query);

			
			$stmt->execute(['values1' => $entreprise->adresse->rue, 'values2' => $entreprise->adresse->numero, 'values3' => $entreprise->adresse->codePostal, 'values4' => $entreprise->adresse->ville]);
			
			$id = $pdo->lastInsertId();
			
			$query = "INSERT INTO entreprise (identifiant, mdp, adresse, tarif, tarifUrgent, logo, nom, tva) VALUES (:values1, :values2, :values3, :values4, :values5, :values6, :values7, :values8)";
			$stmt = $pdo->prepare($query);
			
			$stmt->execute([
				'values1' => $entreprise->identifiant,
				'values2' => password_hash($entreprise->mdp, PASSWORD_BCRYPT),
				'values3' => $id,
				'values4' => $entreprise->tarif,
				'values5' => $entreprise->tarifUrgent,
				'values6' => $entreprise->logo,
				'values7' => $entreprise->nom,
				'values8' => $entreprise->tva
			]);
			
			$pdo->commit();
			$pdo->exec("SET autocommit = 1");
			$pdo = null;
		}
		
		public function updateEntreprise($entreprise)
		{
			$pdo = $this->connectDB();
			
			$pdo->exec("SET autocommit = 0");
			
			$query = "UPDATE adresse SET rue = :values1, numero = :values2, code_postal = :values3, ville = :values4 WHERE id = :id";
			$stmt = $pdo->prepare($query);

			
			$stmt->execute([
				'values1' => $entreprise->adresse->rue,
				'values2' => $entreprise->adresse->numero,
				'values3' => $entreprise->adresse->codePostal,
				'values4' => $entreprise->adresse->ville,
				'id' => $entreprise->adresse->id
			]);
			
			
			$query = "UPDATE entreprise SET mdp = :values2, tarif =:values4, tarifUrgent =:values5, logo = :values6, nom = :values7, tva = :values8 WHERE identifiant = :values1";
			$stmt = $pdo->prepare($query);
			
			$stmt->execute([
				'values1' => $entreprise->identifiant,
				'values2' => password_hash($entreprise->mdp, PASSWORD_BCRYPT),
				'values4' => $entreprise->tarif,
				'values5' => $entreprise->tarifUrgent,
				'values6' => $entreprise->logo,
				'values7' => $entreprise->nom,
				'values8' => $entreprise->tva
			]);
			
			$pdo->commit();
			$pdo->exec("SET autocommit = 1");
			$pdo = null;
		}
		
		public function findClientsByEntreprise($entreprise)
		{
			$pdo = $this->connectDB();
			
			$query = "SELECT * from client WHERE entreprise = :values1";
			$stmt = $pdo->prepare($query);

			
			$stmt->execute(['values1' => $entreprise->identifiant]);
			
			$resultats = $stmt->fetchAll();
			

			$pdo = null;
			
			return $resultats;
		}
		
		public function findClientById($id)
		{
			$pdo = $this->connectDB();
			
			$query = "SELECT * from client WHERE id = :values1";
			$stmt = $pdo->prepare($query);

			
			$stmt->execute(['values1' => $id]);
			
			$resultats = $stmt->fetch();
			
			$pdo = null;
			
			$newclient = new Client($resultats['nom'], $resultats['prenom'],$resultats['telephone'],$resultats['email'], $this->findAdresseByID($resultats['adresse']));
			$newclient->id =  $resultats['id'];
			
			foreach($this->findPrestationByClient($newclient) as $prestation){
				$newclient->addPrestation($prestation);
			}
			return $newclient;
		}
		
		public function removeClientById($id)
		{
			$pdo = $this->connectDB();
			
			$pdo->exec("SET autocommit = 0");
			
			$client = $this->findClientById($id);
			

			$query = "DELETE FROM prestation WHERE client = :values1";
			$stmt = $pdo->prepare($query);
		
			$stmt->execute(['values1' => $client->id]);
		
			$query = "DELETE FROM client WHERE id = :values1";
			$stmt = $pdo->prepare($query);

			
			$stmt->execute(['values1' => $id]);
			
			$query = "DELETE FROM adresse WHERE id = :values1";
			$stmt = $pdo->prepare($query);
			
			$stmt->execute(['values1' => $client->adresse->id]);
			
			$pdo->commit();
			$pdo->exec("SET autocommit = 1");
			
			$pdo = null;
		}
		
		public function findEntreprises()
		{
			$pdo = $this->connectDB();
			
			$query = "SELECT * from entreprise";
			$stmt = $pdo->prepare($query);

			
			$stmt->execute();
			
			$resultats = $stmt->fetchAll();
			
			$pdo = null;
			
			return $resultats;
		}
		public function  findEntrepriseByClient($client)
		{
			$pdo = $this->connectDB();
			
			
	
			$query = "SELECT * from client WHERE id = :id";
			$stmt = $pdo->prepare($query);
			
			$stmt->execute(['id' => $client->id]);
			
			$resultats = $stmt->fetch();
			
			$query = "SELECT * from entreprise WHERE identifiant = :identifiant";
			$stmt = $pdo->prepare($query);
			
			$stmt->execute(['identifiant' => $resultats['entreprise']]);
			
			$resultats = $stmt->fetch();
			
			$pdo = null;
			
			$newEntreprise = new Entreprise($entreprise['identifiant'], $entreprise['mdp'], $adresse, $entreprise['nom']);
			$newEntreprise->tarif = $entreprise['tarif'];
			$newEntreprise->tva = $entreprise['tva'];
			$newEntreprise->tarifUrgent = $entreprise['tarifUrgent'];
			$newEntreprise->logo = $entreprise['logo'];
			
			return $newEntreprise;
		}
		
		public function findEntrepriseByIdentifiant($identifiant)
		{
			$pdo = $this->connectDB();
			
			$query = "SELECT * from entreprise where identifiant = :values1";
			$stmt = $pdo->prepare($query);

			
			$stmt->execute(['values1' => $identifiant]);
			
			$resultat = $stmt->fetch();
			$adresse = $this->findAdresseByID($resultat['adresse']);
			$entreprise = new Entreprise($resultat['identifiant'], $resultat['mdp'], $adresse);
			$entreprise->nom = $resultat['nom'];
			$pdo = null;
			
			return $entreprise;
		}
		
		public function findAdresseByID($id)
		{
			$pdo = $this->connectDB();
			
			$query = "SELECT * from adresse WHERE id = :id";
			$stmt = $pdo->prepare($query);
			
			
			$stmt->execute(['id' => $id]);
			
			$resultats = $stmt->fetch();
			
			$pdo = null;
			$add = new adresse($resultats['rue'], $resultats['numero'], $resultats['code_postal'], $resultats['ville']);
			$add->id = $resultats['id'];
			return $add;
		}
		
		public function saveClient($client, $entreprise)
		{
			$pdo = $this->connectDB();
			
			$pdo->exec("SET autocommit = 0");
			
			$query = "INSERT INTO adresse (rue, numero, code_postal, ville) VALUES (:values1, :values2, :values3, :values4)";
			$stmt = $pdo->prepare($query);

			
			$stmt->execute(['values1' => $client->adresse->rue, 'values2' => $client->adresse->numero, 'values3' => $client->adresse->codePostal, 'values4' => $client->adresse->ville]);
			
			$adresseId = $pdo->lastInsertId();
			
			$query = "INSERT INTO client (nom, prenom, telephone, email, adresse, entreprise) VALUES (:values1, :values2, :values3, :values4, :values5, :values6)";
			$stmt = $pdo->prepare($query);
			
			$stmt->execute(['values1' => $client->nom, 'values2' => $client->prenom, 'values3' => $client->telephone, 'values4' => $client->email, 'values5' => $adresseId, 'values6' => $entreprise->identifiant]);
			
			$key = $pdo->lastInsertId();
			$pdo->commit();
			$pdo->exec("SET autocommit = 1");
			
			$pdo = null;
			
			return $key;
		}
		
		public function updateClient($client)
		{
			$pdo = $this->connectDB();
			
			$pdo->exec("SET autocommit = 0");
			
			$query = "UPDATE adresse SET rue = :values1, numero = :values2, code_postal = :values3, ville = :values4 WHERE id = :id";
			$stmt = $pdo->prepare($query);

			
			$stmt->execute([
				'values1' => $client->adresse->rue,
				'values2' => $client->adresse->numero,
				'values3' => $client->adresse->codePostal,
				'values4' => $client->adresse->ville,
				'id' => $client->adresse->id
			]);
			
			
			$query = "UPDATE client SET nom = :values2, prenom =:values4, telephone =:values5, email = :values6 WHERE id = :values1";
			$stmt = $pdo->prepare($query);
			
			$stmt->execute([
				'values1' => $client->id,
				'values2' => $client->nom,
				'values4' => $client->prenom,
				'values5' => $client->telephone,
				'values6' => $client->email,
			]);
			
			$pdo->commit();
			$pdo->exec("SET autocommit = 1");
			$pdo = null;
		}
		
		public function savePrestation($prestation)
		{
			$pdo = $this->connectDB();
			
			
			$pdo->exec("SET autocommit = 0");
			
			$query = "INSERT INTO prestation (datePrestation, tempsPrestation, description, tarif, urgent, tva, client)
						VALUES (:values1, :values2, :values3, :values4, :values5, :values6, :values7)";
			$stmt = $pdo->prepare($query);
			if($prestation->urgent){
				$urgent = 1;
			}
			else{
				$urgent = 0;
			}
			
			$stmt->execute(
				[
					'values1' => $prestation->datePrestation,
					'values2' => $prestation->tempsPrestation,
					'values3' => $prestation->description,
					'values4' => $prestation->tarif,
					'values5' => $urgent,
					'values6' => $prestation->tva,
					'values7' => $prestation->client->id
				]
			);	
			$key = $pdo->lastInsertId();
			$pdo->commit();
			$pdo->exec("SET autocommit = 1");
			
			$pdo = null;
			
			return $key;
			
		}
		
		public function findPrestationById($id)
		{
			$pdo = $this->connectDB();
			
			$query = "SELECT * from prestation WHERE id = :id";
			$stmt = $pdo->prepare($query);
			
			
			$stmt->execute(['id' => $id]);
			
			$resultats = $stmt->fetch();
			
			$pdo = null;
			$prestation = new Prestation();
			$prestation->setValues($resultats['datePrestation'], $resultats['tempsPrestation'], $resultats['description'], $resultats['tarif'], $resultats['tva']);
			if($resultats['urgent'] == 1){
				$prestation->urgent = true;
			}
			else{
				$prestation->urgent = false;
			}
			// $prestation->client = $this->findClientById($resultats['client']);
			// $prestation->entreprise = $this->findEntrepriseByClient($this->client);
			
			$prestation->id = $resultats['id'];
			
			return $prestation;
			
		}
		
		public function findPrestationByClient($client)
		{
			$pdo = $this->connectDB();
			
			$query = "SELECT * from prestation WHERE client = :client";
			$stmt = $pdo->prepare($query);
			
			
			$stmt->execute(['client' => $client->id]);
			
			$resultats = $stmt->fetchAll();
			
			$pdo = null;
			$prestation = new Prestation();
			$listPrestation = [];
			foreach($resultats as $result){
				$prestation = new Prestation();
				$prestation->setValues($result['datePrestation'], $result['tempsPrestation'], $result['description'], $result['tarif'], $result['tva']);
				if($result['urgent'] == 1){
					$prestation->urgent = true;
				}
				else{
					$prestation->urgent = false;
				}
				// $prestation->client = $this->findClientById($resultats['client']);
				// $prestation->entreprise = $this->findEntrepriseByClient($this->client);
				$prestation->id = $result['id'];
				
				$listPrestation[] = $prestation;
			}
			return $listPrestation;
			
		}
		
		public function removePrestationById($id)
		{
			$pdo = $this->connectDB();
			$pdo->exec("SET autocommit = 0");
			$query = "DELETE FROM prestation WHERE id = :values1";
			$stmt = $pdo->prepare($query);

			
			$stmt->execute(['values1' => $id]);
			
			$pdo->commit();
			$pdo->exec("SET autocommit = 1");
			
			$pdo = null;
		}
		
		public function removeEntreprise($entreprise)
		{
			$pdo = $this->connectDB();
			$pdo->exec("SET autocommit = 0");
			foreach($entreprise->listClients as $client){
				$this->removeClientById($client->id);
			}
			
			$query = "DELETE FROM entreprise WHERE identifiant = :values1";
			$stmt = $pdo->prepare($query);
			
			$stmt->execute(['values1' => $entreprise->identifiant]);

			$query = "DELETE FROM adresse WHERE id = :values1";
			$stmt = $pdo->prepare($query);

			
			$stmt->execute(['values1' => $entreprise->adresse->id]);
			
			
			
			$pdo->commit();
			$pdo->exec("SET autocommit = 1");
			
			$pdo = null;
		}
	}