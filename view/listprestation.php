<!DOCTYPE html>
<html lang="fr" xmlns:th="https://www.thymeleaf.org">
	<head>
		<title>Working Time Count</title>
		<meta charset="UTF-8">
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
		
		<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
		<link rel="stylesheet" href="../css/font.css">
	</head>
	<body class="bg-secondary">
	<?php
		require_once("../controller/Controller.php");	
		require_once("../controller/HTMLMaker.php");
		session_start(); 
		ob_start();
		if(!isset($_SESSION['userName'])) { 
			header("location: index.php");
		}
		$controller = $_SESSION['controller'];
		
		$html = new HTMLMaker();
		$html->echoNavBar();
	
	?>

		<div class="container contentPane bg-light p-3">
		
			<h1 class="pt-4 text-center">Client</h1>
			<hr>			
			
			<form name="create_client" method="post">
			
				<div class="d-flex text-center justify-content-center">
					<div name="client" class="<?php $html->echoClassFrom()?>">
						<label style="font-weight : 500" class="pb-2  pt-2">Client : </label>
						<div class="form-group">
							<label for="nom">Nom :</label>
							<input type="text" class="form-control text-center" id="nom" name="nom" value="<?php echo $controller->client->nom ?>" required>
						</div>

						<div class="form-group">
							<label for="prenom">Prénom :</label>
							<input type="text" class="form-control text-center" id="prenom" name="prenom" value="<?php echo $controller->client->prenom ?>" required>
						</div>

						<div class="form-group">
							<label for="telephone">Téléphone :</label>
							<input type="tel" class="form-control text-center" id="telephone" name="telephone" value="<?php echo $controller->client->telephone ?>" required>
						</div>

						<div class="form-group">
							<label for="email">E-mail :</label>
							<input type="email" class="form-control text-center" id="email" name="email" value="<?php echo $controller->client->email ?>" required>
						</div>
					</div>
					<div name="adresse" class="<?php $html->echoClassFrom()?>">
						<label style="font-weight : 500" class="pb-2  pt-2">Adresse : </label>
						<div class="form-group">
							<label for="rue">Rue :</label>
							<input type="text" class="form-control text-center" id="rue" name="rue" value="<?php echo $controller->client->adresse->rue ?>" required>
						</div>

						<div class="form-group">
							<label for="numero">Numéro :</label>
							<input type="text" class="form-control text-center" id="numero" name="numero" value="<?php echo $controller->client->adresse->numero ?>" required>
						</div>

						<div class="form-group">
							<label for="postal">Code postal :</label>
							<input type="text" class="form-control text-center" id="postal" name="postal" value="<?php echo $controller->client->adresse->codePostal ?>" required>
						</div>

						<div class="form-group">
							<label for="ville">Ville :</label>
							<input type="text" class="form-control text-center" id="ville" name="ville" value="<?php echo $controller->client->adresse->ville ?>" required>
						</div>
					</div>
				</div>
				<div class="d-flex text-center justify-content-center">
					<input type="submit" class="mt-3 btn btn-primary" name="updateClient" value="Sauvegarder">
				</div>
			</form>
			
			
			<h1 class="pt-4 text-center">Gestion des Prestations</h1>
			<hr>
			
			<div class="table-responsive">
				<table class="table table-hover table-striped table-dark table-bordered">
					<thead>
					<tr>
					<th scope="col">Id</th>
					<th scope="col">Date</th>
					<th scope="col">Temps</th>
					<th scope="col">Description</th>
					<th scope="col">tarif &#8364/h</th>
					<th scope="col">urgent</th>
					<th scope="col">total &#8364</th>
					<th scope="col">tva %</th>
					<th scope="col">Action</th>
					</tr>
					<tbody>
					<?php 

						foreach($controller->client->prestations as $prestation){
							echo '<tr>
							<td>'.$prestation->id.'</td>
							<td>'.$prestation->datePrestation.'</td>
							<td>'.gmdate("H:i:s",$prestation->tempsPrestation).'</td>
							<td>'.$prestation->description.'</td>
							<td>'.$prestation->tarif.'</td>';
							if($prestation->urgent){
								echo '<td>oui</td>';
							}
							else{
								echo '<td>non</td>';
							}
							
							echo '
							<td>'.number_format($prestation->total,2).'</td>
							<td>'.(($prestation->tva - 1.0) * 100).'</td>
							<td>
								<form method="post">
									<input type="hidden" name="prestation_id" value="'.$prestation->id.'">
									<input type="submit" class="btn btn-success" name="facturer_prestation" value="Facturer">
									<input type="submit" class="btn btn-danger" name="delete_prestation" value="Delete">							
								</form>

							</td></tr>';
						}
						
						if(isset($_POST['delete_prestation'])){
							ob_end_clean();
							$controller->removePrestationById($_POST['prestation_id']);
							
							header('Location: listprestation.php');
						}
						
						if(isset($_POST['facturer_prestation'])){
							ob_end_clean();
							$controller->prestation = $controller->findPrestationById($_POST['prestation_id']);
							$controller->facturer();
						}
						
						if(isset($_POST['new_prestation'])){
							ob_end_clean();
							$controller->prestation = new prestation();
							$_SESSION['pause'] = false;
							if (isset($_SESSION['start_time'])) {
								unset($_SESSION['start_time']);
							}
							
							header('Location: main.php');
						}
						
						if(isset($_POST['updateClient'])){
							ob_end_clean();
							$controller->client->nom = $_POST['nom'];
							$controller->client->prenom = $_POST['prenom'];
							$controller->client->telephone = $_POST['telephone'];
							$controller->client->email = $_POST['email'];
							$controller->client->adresse->rue = $_POST['rue'];
							$controller->client->adresse->numero = $_POST['numero'];
							$controller->client->adresse->codePostal = $_POST['postal'];
							$controller->client->adresse->ville = $_POST['ville'];
							$controller->updateClient();
							header('Location: listprestation.php');
						}
	
					?>
					</tbody>
				</table>
			</div>
			<div class="d-flex text-center justify-content-between">
				<a href="listclient.php" class="btn btn-secondary btn-sm mb-3 m-2">Retour</a>
				<form method="post">
					<input type="submit" class="btn btn-primary btn-sm mb-3 m-2" name="new_prestation" value="Nouvelle Prestation">
				</form>
			</div>
		</div>
		<?php $html->echoFooter() ?>
	</body>
</html>