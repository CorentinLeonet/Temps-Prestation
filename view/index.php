<!DOCTYPE html>
<html lang="fr">
<head>
	<title>Working Time Count</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
		<link rel="stylesheet" href="../css/font.css">
	<style>
		form {
			margin: auto;
			margin-top: 20px;
			width: 400px;
			border: 1px solid #ddd;
			padding: 20px;
			border-radius: 5px;
			background-color: #f8f8f8;
		}
	</style>
</head>
<body>
	<div class="container" style="min-height : 90vh">
		<h1 class="text-center p-2">Working Time Count</h1>
		<form name="log_in" method="POST" action="" class="form-group">
			<label for="identifiant">Identifiant:</label>
			<input type="text" name="identifiant" id="identifiant" class="form-control" required>
			<label for="mdp">Mot de passe:</label>
			<input type="password" name="mdp" id="mdp" class="form-control" required>
			<div class="form-check">
				<input type="checkbox" class="form-check-input" onclick="afficheMDP()">
				<label class="form-check-label">Afficher le mot de passe</label>
			</div>
			<input type="submit" name="loginSubmit" value="Se connecter" class="btn btn-primary mt-3">
		</form>
		<p class="text-center">
			Pas de compte ?
			<a href="creercompte.php">Créer un compte</a>
		</p>
	</div>
	<script>
		function afficheMDP() {
			var x = document.getElementById("mdp");
			if (x.type === "password") {
				x.type = "text";
			} else {
				x.type = "password";
			}
		}
	</script>
	<?php 
		
		require_once("../controller/Controller.php");
		require_once("../controller/HTMLMaker.php");
		$html = new HTMLMaker();
		
		session_start(); 
		unset($_SESSION['userName']);
		if(isset($_POST['loginSubmit'])){
			$controller = new Controller();
			if($controller->login($_POST['identifiant'], $_POST['mdp'])){
				$_SESSION['userName'] = $_POST['identifiant'];
				$_SESSION['controller'] = $controller;
				header('Location: listclient.php');
			}
			else{
				echo "<div class='alert alert-danger text-center'>Erreur : identifiant ou mot de passe incorrect</div>";
			}
		}
		$html->echoFooter();
	?>
</body>
</html>
