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
	<body class="bg-secondary">
		<div class="container bg-light p-3" style="min-height: 85vh;">
	
			<form name="editEntreprise" method="POST">
				<div class="d-flex text-center justify-content-center">
					<div name="entreprise" class="<?php $html->echoClassFrom()?>">
						<label style="font-weight : 500" class="pb-2  pt-2">Entreprise : </label>
						<div class="form-group">
							<label for="identifiant">Identifiant:</label>
							<input type="text" class="form-control text-center" name="identifiant" disabled id="identifiant" value="<?php echo $controller->entreprise->identifiant?>">
						</div>
						<div class="form-group">
							<label for="mdp">Mot de passe:</label>
							<input type="password" class="form-control text-center" name="mdp" id="mdp" required>
						</div>
						
						<div class="form-group">
							<label for="mdp_confirme">Confirmer le mot de passe:</label>
							<input type="password" class="form-control text-center" name="mdp_confirme" id="mdp_confirme" required>
						</div>
						
						<input type="checkbox" onclick="afficheMDP()"> Afficher le mot de passe</input>
					</div>
					
					<div name="options" class="<?php $html->echoClassFrom()?>">
						<label style="font-weight : 500" class="pb-2  pt-2">Options : </label>
						<div class="form-group">
							<label for="nomEntreprise">Nom de l'entreprise:</label>
							<input type="text" class="form-control text-center" name="nomEntreprise" id="nomEntreprise" value="<?php echo $controller->entreprise->nom?>" required>
						</div>
						<div class="form-group">
							<label for="tarif">Tarif:</label>
							<input type="number" min="0" step="0.01" class="form-control text-center" name="tarif" id="tarif" value="<?php echo $controller->entreprise->tarif?>" required>
						</div>
						<div class="form-group">
							<label for="tarifUrgent">Tarif Urgent:</label>
							<input type="number" min="0" step="0.01" class="form-control text-center"  name="tarifUrgent" id="tarifUrgent" value="<?php echo $controller->entreprise->tarifUrgent?>" required>
						</div>
						<!--<div class="form-group">
							<label for="logo">Logo:</label>
							<input type="text" class="form-control" name="logo" id="logo" value="<?php echo $controller->entreprise->logo?>" required>
						</div>-->
						<div class="form-group">
							<label for="tva">TVA:</label>
							<select class="form-control text-center" name="tva" id="tva" required>
								<option value="1.21" <?php if($controller->entreprise->tva == 1.21) echo 'selected'; ?>>21%</option>
								<option value="1.12" <?php if($controller->entreprise->tva == 1.12) echo 'selected'; ?>>12%</option>
								<option value="1.06" <?php if($controller->entreprise->tva == 1.06) echo 'selected'; ?>>6%</option>
								<option value="1.00" <?php if($controller->entreprise->tva == 1.00) echo 'selected'; ?>>0%</option>
							</select>
						</div>
					</div>
					
					<div name="adresse" class="<?php $html->echoClassFrom()?>">
						<label style="font-weight : 500" class="pb-2  pt-2">Adresse : </label>
						<div class="form-group">
							<label for="rue">Rue:</label>
							<input type="text" class="form-control text-center" name="rue" id="rue" value="<?php echo $controller->entreprise->adresse->rue?>" required>
						</div>
						<div class="form-group">
							<label for="numero">Numero:</label>
							<input type="text" class="form-control text-center" name="numero" id="numero" value="<?php echo $controller->entreprise->adresse->numero?>" required>
						</div>
						<div class="form-group">
							<label for="postal">Code postal:</label>
							<input type="text" class="form-control text-center" name="postal" id="postal" value="<?php echo $controller->entreprise->adresse->codePostal?>" required>
						</div>
						<div class="form-group">
							<label for="ville">Ville:</label>
							<input type="text" class="form-control text-center" name="ville" id="ville" value="<?php echo $controller->entreprise->adresse->ville?>" required>
						</div>
					</div>
				</div>
				
				
				<div class="d-flex text-center justify-content-center">
					<input type="submit" class="btn btn-primary mb-3 m-2 p-2" name="editEntreprisesubmit" value="Sauvegarder les changements">
					<button  id="supprimerEntreprise" type="button" class="btn mb-3 m-2 p-2 btn-danger" data-toggle="modal" data-target="#confirm-delete-modal">Supprimer le compte</button>
				</div>
				
			</form>
			
			
			
			
		</div>
		
		
		<!-- Fenêtre de confirmation modal -->
		<div class="modal fade" id="confirm-delete-modal" tabindex="-1" role="dialog" aria-labelledby="confirm-delete-modal-title"
			aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="confirm-delete-modal-title">Confirmation de suppression</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						Êtes-vous sûr de vouloir supprimer votre compte? Cette action est irréversible.
					</div>
					<div class="modal-footer">
						<form name="confirmDeleteEntreprise" method="POST">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
							<input type="submit" class="btn btn-danger" name="deleteEntreprisesubmitconfirm" value="Supprimer">
						</form>
					</div>
				</div>
			</div>
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
			
			if(isset($_POST['editEntreprisesubmit'])){
				if($_POST['mdp'] == $_POST['mdp_confirme']){
					$controller->entreprise->mdp = $_POST['mdp'];
					
					$controller->entreprise->adresse->rue = $_POST['rue'];
					$controller->entreprise->adresse->numero = $_POST['numero'];
					$controller->entreprise->adresse->codePostal = $_POST['postal'];
					$controller->entreprise->adresse->ville = $_POST['ville'];
					
					$controller->entreprise->nom = $_POST['nomEntreprise'];
					$controller->entreprise->tarif = $_POST['tarif'];
					$controller->entreprise->tarifUrgent = $_POST['tarifUrgent'];
					$controller->entreprise->tva = $_POST['tva'];
					$controller->updateEntreprise();
					header('Location: listclient.php');
					
				}
				else{
					echo "<div class='alert alert-danger text-center'>Les 2 mots de passe doivent être identique</div>";
				}
			}
			
			if(isset($_POST['deleteEntreprisesubmitconfirm'])){
				$controller->removeEntreprise();
				ob_end_clean();	
				unset($_SESSION['userName']);
				header('Location: index.php');
			}
			
			$html->echoFooter();
		?>
	</body>
</html>