<!DOCTYPE html>
<html lang="fr" xmlns:th="https://www.thymeleaf.org">
	<head>
		<meta charset="UTF-8">
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
		<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
		<title>Working Time Count</title>
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
		
		<div class="container bg-light p-3" style="min-height: 85vh;">
			<h1 class="pt-4 text-center">Ajout d'un client</h1>
			<hr>			
			
			<form name="create_client" method="post">
				<div class="d-flex text-center justify-content-center">
					<div name="client" class="<?php $html->echoClassFrom()?>">
						<label style="font-weight : 500" class="pb-2  pt-2">Client : </label>
						<div class="form-group">
							<label for="nom">Nom :</label>
							<input type="text" class="form-control text-center" id="nom" name="nom" required>
						</div>

						<div class="form-group">
							<label for="prenom">Prénom :</label>
							<input type="text" class="form-control text-center" id="prenom" name="prenom" required>
						</div>

						<div class="form-group">
							<label for="telephone">Téléphone :</label>
							<input type="tel" class="form-control text-center" id="telephone" name="telephone" required>
						</div>

						<div class="form-group">
							<label for="email">E-mail :</label>
							<input type="email" class="form-control text-center" id="email" name="email" required>
						</div>
					</div>
					<div name="adresse" class="<?php $html->echoClassFrom()?>">
						<label style="font-weight : 500" class="pb-2  pt-2">Adresse : </label>
						<div class="form-group">
							<label for="rue">Rue :</label>
							<input type="text" class="form-control text-center" id="rue" name="rue" required>
						</div>

						<div class="form-group">
							<label for="numero">Numéro :</label>
							<input type="text" class="form-control text-center" id="numero" name="numero" required>
						</div>

						<div class="form-group">
							<label for="postal">Code postal :</label>
							<input type="text" class="form-control text-center" id="postal" name="postal" required>
						</div>

						<div class="form-group">
							<label for="ville">Ville :</label>
							<input type="text" class="form-control text-center" id="ville" name="ville" required>
						</div>
					</div>
				</div>
				<div class="d-flex text-center justify-content-center">
					<input type="submit" class="mt-3 btn btn-primary" name="createClient" value="Ajouter client">
				</div>
			</form>
					
			<hr>
			 
			<a href="listclient.php" class="mt-auto btn btn-dark">Retour</a>
		</div>
		<?php
			$html->echoFooter();
			require_once("../controller/Controller.php");
			

			
			if(isset($_POST['createClient'])){
				ob_end_clean();
				$client = new client($_POST['nom'],$_POST['prenom'],$_POST['telephone'], $_POST['email'], new Adresse($_POST['rue'], $_POST['numero'], $_POST['postal'], $_POST['ville']));
				$controller->saveClient($client);
				header('Location: listclient.php');
			}
		?>
	</body>
</html>