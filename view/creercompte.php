<!DOCTYPE html>
<html lang="fr">
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
	
	
	<body>
		<div class="container bg-light contentPane p-3">
			<h1 class="mt-4 text-center">Creer un compte</h1>
	
			<form name="sign_in" method="POST">
				<div class="form-group">
					<label for="identifiant">Identifiant:</label>
					<input type="text" class="form-control" name="identifiant" id="identifiant" required>
				</div>
				<div class="form-group">
					<label for="mdp">Mot de passe:</label>
					<input type="password" class="form-control" name="mdp" id="mdp" required>
				</div>
				<div class="form-group">
					<label for="mdp_confirme">Confirmer le mot de passe:</label>
					<input type="password" class="form-control" name="mdp_confirme" id="mdp_confirme" required>
				</div>
				
				<input type="checkbox" onclick="afficheMDP()"> Afficher le mot de passe</input>
				
				<div class="form-group">
					<label for="nomEntreprise">Nom de l'entreprise:</label>
					<input type="text" class="form-control" name="nomEntreprise" id="nomEntreprise" required>
				</div>
				<div class="form-group">
					<label for="rue">Rue:</label>
					<input type="text" class="form-control" name="rue" id="rue" required>
				</div>
				<div class="form-group">
					<label for="numero">Numero:</label>
					<input type="text" class="form-control" name="numero" id="numero" required>
				</div>
				<div class="form-group">
					<label for="postal">Code postal:</label>
					<input type="text" class="form-control" name="postal" id="postal" required>
				</div>
				<div class="form-group">
					<label for="ville">Ville:</label>
					<input type="text" class="form-control" name="ville" id="ville" required>
				</div>
				</br>
				<input type="submit" class="btn btn-primary" name="signinSubmit" value="Creer compte">
			</form>
			
			<p class="mt-4">
				Déjà un compte ?<br>
				<a href="index.php">Se connecter</a>
			</p>
		</div>
		
		<script>
			function afficheMDP() {
				var x = document.getElementById("mdp");
				var y = document.getElementById("mdp_confirme");
				if (x.type === "password") {
					x.type = "text";
				} else {
					x.type = "password";
				}
				
				if (y.type === "password") {
					y.type = "text";
				} else {
					y.type = "password";
				}
			}
		</script>
		
		
		<?php 
			
			require_once("../controller/Controller.php");
			require_once("../controller/HTMLMaker.php");
			$html = new HTMLMaker();
			
			if(isset($_POST['signinSubmit'])){
				$controller = new Controller();
				if($_POST['mdp'] == $_POST['mdp_confirme'])
				{
					$controller->entreprise = new entreprise($_POST['identifiant'],$_POST['mdp'], new Adresse($_POST['rue'], $_POST['numero'], $_POST['postal'], $_POST['ville']), $_POST['nomEntreprise']);
					$controller->saveEntreprise();
					header('Location: index.php');
					
				}
				else{
					echo "<div class='alert alert-danger text-center'>Les 2 mots de passe doivent être identique</div>";
				}
			}
			
			$html->echoFooter();
		?>
	</body>
</html>